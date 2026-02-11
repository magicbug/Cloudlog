<?php

class Eqslmethods_model extends CI_Model {

	function mark_all_as_sent() {
		$data = array(
            'COL_EQSL_QSL_SENT' => 'Y',
            'COL_EQSL_QSLSDATE'  => date('Y-m-d')." 00:00:00",
        );

        $this->db->group_start();
		$this->db->where('COL_EQSL_QSL_SENT', 'N');
        $this->db->or_where('COL_EQSL_QSL_SENT', 'R');
        $this->db->or_where('COL_EQSL_QSL_SENT', 'Q');
        $this->db->or_where('COL_EQSL_QSL_SENT', null);
		$this->db->group_end();
       
        $this->db->update($this->config->item('table_name'), $data);
	}

    function get_eqsl_users() {
        $this->db->select('user_eqsl_name, user_eqsl_password, user_id');
        $this->db->where('coalesce(user_eqsl_name, "") != ""');
        $this->db->where('coalesce(user_eqsl_password, "") != ""');
        $query = $this->db->get($this->config->item('auth_table'));
        return $query->result();
    }

    /*
     * Gets all station location for user, for use in cron where we don't have any login
     */
    function get_all_user_locations($userid) {
		$this->db->select('station_profile.*, dxcc_entities.name as station_country, dxcc_entities.end as dxcc_end');
		$this->db->where('user_id', $userid);
		$this->db->join('dxcc_entities','station_profile.station_dxcc = dxcc_entities.adif','left outer');
		return $this->db->get('station_profile');
	}

    // Show all QSOs we need to send to eQSL
    function eqsl_not_yet_sent($userid = null) {
        $CI =& get_instance();
        if ($userid == null) {
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $stations = $this->get_all_user_locations($userid);
            $logbooks_locations_array = array();
            foreach ($stations->result() as $row) {
                array_push($logbooks_locations_array, $row->station_id);
            }
	    array_push($logbooks_locations_array, -9999);
        }

        $this->db->select('station_profile.*, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_COMMENT, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_PROP_MODE, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_SAT_MODE, '.$this->config->item('table_name').'.COL_QSLMSG');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->where("coalesce(station_profile.eqslqthnickname, '') <> ''");
        $this->db->where($this->config->item('table_name').'.COL_CALL !=', '');
        $this->db->group_start();
        $this->db->where($this->config->item('table_name').'.COL_EQSL_QSL_SENT is null');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', '');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'R');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'Q');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'N');
        $this->db->group_end();
        if (!empty($logbooks_locations_array)) {
            $this->db->where_in('station_profile.station_id', $logbooks_locations_array);
        } else {
            // Option 1: Skip the query altogether (return no results)
            return [];
        }

        return $this->db->get();
    }

    // Show all QSOs whose eQSL card images we did not download yet
    function eqsl_not_yet_downloaded($userid = null) {
        $CI =& get_instance();
        if ($userid == null) {
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $stations = $this->get_all_user_locations($userid);
            $logbooks_locations_array = array();
            foreach ($stations->result() as $row) {
                array_push($logbooks_locations_array, $row->station_id);
            }
	array_push($logbooks_locations_array, -9999);
        }

        $this->db->select('station_profile.station_id, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_PROP_MODE, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_SAT_MODE, '.$this->config->item('table_name').'.COL_QSLMSG, eQSL_images.qso_id');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('eQSL_images','eQSL_images.qso_id = '.$this->config->item('table_name').'.COL_PRIMARY_KEY','left outer');
        //$this->db->where("coalesce(station_profile.eqslqthnickname, '') <> ''");
        $this->db->where($this->config->item('table_name').'.COL_CALL !=', '');
        $this->db->where($this->config->item('table_name').'.COL_EQSL_QSL_RCVD', 'Y');
        $this->db->where('qso_id', NULL);
        if (!empty($logbooks_locations_array)) {
            $this->db->where_in('station_profile.station_id', $logbooks_locations_array);
        } else {
            // Option 1: Skip the query altogether (return no results)
            return [];
        }
        $this->db->order_by("COL_TIME_ON", "desc");

        return $this->db->get();
    }

    // Mark the QSO as sent to eQSL
    function eqsl_mark_sent($primarykey) {
        $data = array(
            'COL_EQSL_QSLSDATE' => date('Y-m-d H:i:s'), // eQSL doesn't give us a date, so let's use current
            'COL_EQSL_QSL_SENT' => 'Y',
        );

        $this->db->where('COL_PRIMARY_KEY', $primarykey);

        $this->db->update($this->config->item('table_name'), $data);

        return "eQSL Sent";
    }

    // Returns all the distinct callsign, eqsl nick pair for the current user/supplied user
	function all_of_user_with_eqsl_nick_defined($userid = null) {
        if ($userid == null) {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        } else {
            $this->db->where('user_id', $userid);
        }

		$this->db->where('eqslqthnickname IS NOT NULL');
		$this->db->where('eqslqthnickname !=', '');
		$this->db->from('station_profile');
		$this->db->select('station_callsign, eqslqthnickname, station_id');
		$this->db->distinct(TRUE);

		return $this->db->get();
	}

    // Get the last date we received an eQSL
    function eqsl_last_qsl_rcvd_date($callsign, $nickname) {
        $qso_table_name = $this->config->item('table_name');
        $this->db->from($qso_table_name);

        $this->db->join('station_profile',
            'station_profile.station_id = '.$qso_table_name.'.station_id AND station_profile.eqslqthnickname != ""');
        $this->db->where('station_profile.station_callsign', $callsign);
        $this->db->where('station_profile.eqslqthnickname', $nickname);

        $this->db->select("DATE_FORMAT(COL_EQSL_QSLRDATE,'%Y%m%d') AS COL_EQSL_QSLRDATE", FALSE);
        $this->db->where('COL_EQSL_QSLRDATE IS NOT NULL');
        $this->db->order_by("COL_EQSL_QSLRDATE", "desc");
        $this->db->limit(1);

        $query = $this->db->get();
        $row = $query->row();

        if (isset($row->COL_EQSL_QSLRDATE)){
            return $row->COL_EQSL_QSLRDATE;
        } else {
            // No previous date (first time import has run?), so choose UNIX EPOCH!
            // Note: date is yyyy/mm/dd format
            return '19700101';
        }
    }

    // Update a QSO with eQSL QSL info
    // We could also probably use this:
    // https://eqsl.cc/qslcard/VerifyQSO.txt
    // https://www.eqsl.cc/qslcard/ImportADIF.txt
    function eqsl_update($datetime, $callsign, $band, $mode, $qsl_status, $station_callsign, $station_id) {
        $data = array(
            'COL_EQSL_QSLRDATE' => date('Y-m-d H:i:s'), // eQSL doesn't give us a date, so let's use current
            'COL_EQSL_QSL_RCVD' => $qsl_status
        );

        $this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -15 MINUTE )');
        $this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 15 MINUTE )');
        $this->db->where('COL_CALL', $callsign);
	$this->db->where('COL_STATION_CALLSIGN', $station_callsign);
        $this->db->where('COL_BAND', $band);
        $this->db->where('COL_MODE', $mode);
        $this->db->where('station_id', $station_id);

        $this->db->update($this->config->item('table_name'), $data);

        return "Updated";
    }

    // Determine if we've already received an eQSL for this QSO
    function eqsl_dupe_check($datetime, $callsign, $band, $mode, $qsl_status, $station_callsign, $station_id) {
        $this->db->select('COL_EQSL_QSLRDATE');
        $this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -15 MINUTE )');
        $this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 15 MINUTE )');
        $this->db->where('COL_CALL', $callsign);
        $this->db->where('COL_BAND', $band);
        $this->db->where('COL_MODE', $mode);
	$this->db->where('COL_STATION_CALLSIGN', $station_callsign);
        $this->db->where('COL_EQSL_QSL_RCVD', $qsl_status);
        $this->db->where('station_id', $station_id);
        $this->db->limit(1);
    
        $query = $this->db->get($this->config->item('table_name'));
        $row = $query->row();
    
        if ($row != null) {
            return true;
        } 
        return false;
    }

    /*
     * Batch update multiple QSOs from eQSL confirmation imports
     * Replaces N individual eqsl_update() calls with batch operations
     * Provides massive performance improvement for large eQSL imports
     * 
     * @param array $records Array of eQSL confirmation records with keys:
     *                       datetime, callsign, band, mode, qsl_status, station_callsign, station_id
     * @return array Statistics array with 'updated', 'duplicates', 'errors' counts
     */
    function eqsl_update_batch($records)
    {
        if (empty($records) || !is_array($records)) {
            log_message('debug', 'eQSL batch update: No records provided');
            return array('updated' => 0, 'duplicates' => 0, 'errors' => 0);
        }

        $record_count = count($records);
        log_message('info', "eQSL batch update: Processing {$record_count} confirmation records");

        $table_name = $this->config->item('table_name');
        
        // Step 1: Build WHERE conditions to find all matching QSOs
        $match_conditions = array();
        foreach ($records as $record) {
            $match_conditions[] = sprintf(
                "(COL_TIME_ON >= DATE_ADD(DATE_FORMAT('%s', '%%Y-%%m-%%d %%H:%%i'), INTERVAL -15 MINUTE) AND " .
                "COL_TIME_ON <= DATE_ADD(DATE_FORMAT('%s', '%%Y-%%m-%%d %%H:%%i'), INTERVAL 15 MINUTE) AND " .
                "COL_CALL = '%s' AND COL_BAND = '%s' AND COL_MODE = '%s' AND COL_STATION_CALLSIGN = '%s' AND station_id = %d)",
                $this->db->escape_str($record['datetime']),
                $this->db->escape_str($record['datetime']),
                $this->db->escape_str($record['callsign']),
                $this->db->escape_str($record['band']),
                $this->db->escape_str($record['mode']),
                $this->db->escape_str($record['station_callsign']),
                intval($record['station_id'])
            );
        }
        
        // Step 2: Get all matching QSOs with their current eQSL status
        $sql = "SELECT COL_PRIMARY_KEY, 
                       COL_TIME_ON,
                       COL_CALL, COL_BAND, COL_MODE, COL_STATION_CALLSIGN, station_id,
                       COL_EQSL_QSL_RCVD,
                       COL_EQSL_QSLRDATE
                FROM {$table_name}
                WHERE (" . implode(' OR ', $match_conditions) . ")";
        
        $query = $this->db->query($sql);
        
        if ($query->num_rows() == 0) {
            log_message('warning', 'eQSL batch update: No matching QSOs found in database');
            return array('updated' => 0, 'duplicates' => 0, 'errors' => 0);
        }
        
        // Step 3: Build lookup map
        $qso_map = array();
        foreach ($query->result() as $qso) {
            // Create a time window for matching (within +/- 15 minutes)
            $qso_time = strtotime($qso->COL_TIME_ON);
            for ($offset = -15; $offset <= 15; $offset++) {
                $time_key = date('Y-m-d H:i', $qso_time + ($offset * 60));
                $key = $time_key . '|' . $qso->COL_CALL . '|' . $qso->COL_BAND . '|' . $qso->COL_MODE . '|' . 
                       $qso->COL_STATION_CALLSIGN . '|' . $qso->station_id;
                
                // Store first match only (avoid overwriting with duplicates)
                if (!isset($qso_map[$key])) {
                    $qso_map[$key] = $qso;
                }
            }
        }
        
        // Step 4: Process records and build batch update array
        $batch_updates = array();
        $duplicates = 0;
        
        foreach ($records as $record) {
            $key = $record['datetime'] . '|' . $record['callsign'] . '|' . $record['band'] . '|' . 
                   $record['mode'] . '|' . $record['station_callsign'] . '|' . $record['station_id'];
            
            if (!isset($qso_map[$key])) {
                continue; // QSO not found in database
            }
            
            $qso = $qso_map[$key];
            
            // Skip if already confirmed with this exact status (duplicate)
            if ($qso->COL_EQSL_QSL_RCVD == $record['qsl_status'] && !empty($qso->COL_EQSL_QSLRDATE)) {
                $duplicates++;
                continue;
            }
            
            $batch_updates[] = array(
                'COL_PRIMARY_KEY' => $qso->COL_PRIMARY_KEY,
                'COL_EQSL_QSLRDATE' => date('Y-m-d H:i:s'),
                'COL_EQSL_QSL_RCVD' => $record['qsl_status']
            );
        }
        
        // Step 5: Execute batch update
        $updated_count = 0;
        if (!empty($batch_updates)) {
            $this->db->update_batch($table_name, $batch_updates, 'COL_PRIMARY_KEY');
            $updated_count = $this->db->affected_rows();
            log_message('info', "eQSL batch update: Updated {$updated_count} QSO records, {$duplicates} duplicates skipped");
        }
        
        return array(
            'updated' => $updated_count,
            'duplicates' => $duplicates,
            'errors' => 0
        );
    }

    /*
     * Batch mark multiple QSOs as sent to eQSL
     * Replaces N individual eqsl_mark_sent() calls with single batch operation
     * 
     * @param array $qso_ids Array of QSO primary keys to mark as sent
     * @return int Number of rows affected
     */
    function eqsl_mark_sent_batch($qso_ids)
    {
        // Return early if no QSOs to update
        if (empty($qso_ids) || !is_array($qso_ids)) {
            log_message('debug', 'eQSL batch mark sent: No QSO IDs provided');
            return 0;
        }

        // Sanitize the QSO IDs to prevent SQL injection
        $qso_ids = array_map('intval', $qso_ids);
        $qso_ids = array_filter($qso_ids, function($id) { return $id > 0; });

        if (empty($qso_ids)) {
            log_message('warning', 'eQSL batch mark sent: All QSO IDs were invalid');
            return 0;
        }

        $qso_count = count($qso_ids);
        log_message('info', "eQSL batch mark sent: Processing {$qso_count} QSOs");

        // Use CodeIgniter's query builder for safe batch update
        $this->db->set('COL_EQSL_QSLSDATE', date('Y-m-d H:i:s'));
        $this->db->set('COL_EQSL_QSL_SENT', 'Y');
        $this->db->where_in('COL_PRIMARY_KEY', $qso_ids);
        
        $this->db->update($this->config->item('table_name'));

        $affected_rows = $this->db->affected_rows();
        
        if ($affected_rows != $qso_count) {
            log_message('warning', "eQSL batch mark sent: Expected to update {$qso_count} QSOs but affected {$affected_rows}");
        } else {
            log_message('info', "eQSL batch mark sent: Successfully marked {$affected_rows} QSOs as sent");
        }

        return $affected_rows;
    }

}

?>
