<?php

class Workabledxcc_model extends CI_Model
{

    public function GetThisWeek()
    {
		$json = file_get_contents($this->optionslib->get_option('dxped_url'));

        // Step 2: Convert the JSON data to an array.
        $data = json_decode($json, true);

        // Step 3: Create a new array to hold the records for this week.
        $thisWeekRecords = [];

        // Get the start and end of this week.
        $startOfWeek = (new DateTime())->setISODate((new DateTime())->format('o'), (new DateTime())->format('W'), 1);
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');

        // Step 4: Iterate over the array.
        foreach ($data as $record) {

            // Convert "0" and "1" to DateTime objects.
            $startDate = new DateTime($record['0']);
            $endDate = new DateTime($record['1']);

            // Step 5: Check if the start date or end date is within this week.
            if (($startDate >= $startOfWeek && $startDate <= $endOfWeek) || ($endDate >= $startOfWeek && $endDate <= $endOfWeek)) {
                $endDate = new DateTime($record['1']);
                $now = new DateTime();
                $interval = $now->diff($endDate);
                $daysLeft = $interval->days;

                // If daysLeft is 0, set it to "Last day"
                if ($daysLeft == 0) {
                    $daysLeft = "Last day";
                } else {
                    $daysLeft = $daysLeft . " days left";
                }

                // Add daysLeft to record
                $record['daysLeft'] = $daysLeft;
                // Get Date format
                if ($this->session->userdata('user_date_format')) {
                    // If Logged in and session exists
                    $custom_date_format = $this->session->userdata('user_date_format');
                } else {
                    // Get Default date format from /config/cloudlog.php
                    $custom_date_format = $this->config->item('qso_date_format');
                }

                // Create a new array with the required fields and add it to the main array
                $oldStartDate = DateTime::createFromFormat('Y-m-d', $record['0']);

                $StartDate = $oldStartDate->format($custom_date_format);
                $record['startDate'] = $StartDate;

                $oldEndDate = DateTime::createFromFormat('Y-m-d', $record['1']);
                $EndDate = $oldEndDate->format($custom_date_format);
                $record['endDate'] = $EndDate;

                $record['confirmed'] = true; // or false, depending on your logic

                $CI = &get_instance();
                $CI->load->model('logbook_model');
                $dxccInfo = $CI->logbook_model->dxcc_lookup($record['callsign'], $startDate->format('Y-m-d'));

                // Call DXCC Worked function to check if the DXCC has been worked before
                if (isset($dxccInfo['entity'])) {
                    $dxccWorkedData = $this->dxccWorked($dxccInfo['entity']);
                    $record = array_merge($record, $dxccWorkedData);
                } else {
                    // Handle the case where 'entity' is not set in $dxccInfo
                    $itemsToAdd = array(
                        'workedBefore' => false,
                        'confirmed' => false,
                    );
                    $record = array_merge($record, $itemsToAdd);
                }

                $thisWeekRecords[] = $record;
            }
        }
        return $thisWeekRecords;

    }

    function dxccWorked($country)
    {

        $return = [
            "workedBefore" => false,
            "confirmed" => false,
        ];

        $user_default_confirmation = $this->session->userdata('user_default_confirmation');
        $this->load->model('logbooks_model');
        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        $this->load->model('logbook_model');

        if (!empty($logbooks_locations_array)) {
            $this->db->where('COL_PROP_MODE !=', 'SAT');

            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('COL_COUNTRY', urldecode($country));

            $query = $this->db->get($this->config->item('table_name'), 1, 0);
            foreach ($query->result() as $workedBeforeRow) {
                $return['workedBefore'] = true;
            }

            $extrawhere = '';
            if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
                $extrawhere = "COL_QSL_RCVD='Y'";
            }
            if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
                if ($extrawhere != '') {
                    $extrawhere .= " OR";
                }
                $extrawhere .= " COL_LOTW_QSL_RCVD='Y'";
            }
            if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
                if ($extrawhere != '') {
                    $extrawhere .= " OR";
                }
                $extrawhere .= " COL_EQSL_QSL_RCVD='Y'";
            }

            if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Z') !== false) {
                if ($extrawhere != '') {
                    $extrawhere .= " OR";
                }
                $extrawhere .= " COL_QRZCOM_QSO_DOWNLOAD_STATUS='Y'";
            }


            $this->load->model('logbook_model');
            $this->db->where('COL_PROP_MODE !=', 'SAT');
            if ($extrawhere != '') {
                $this->db->where('(' . $extrawhere . ')');
            } else {
                $this->db->where("1=0");
            }


            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('COL_COUNTRY', urldecode($country));

            $query = $this->db->get($this->config->item('table_name'), 1, 0);
            foreach ($query->result() as $workedBeforeRow) {
                $return['confirmed'] = true;
            }

            return $return;
        } else {
            $return['workedBefore'] = false;
            $return['confirmed'] = false;


            return $return;;
        }
    }
}
