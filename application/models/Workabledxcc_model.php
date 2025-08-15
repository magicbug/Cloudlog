<?php

class Workabledxcc_model extends CI_Model
{
    // Cache for DXCC lookups to avoid repeated queries
    private $dxccCache = array();
    private $workedCache = array();

    /**
     * Batch DXCC lookup for multiple callsigns
     * @param array $callsigns Array of callsigns
     * @param array $dates Array of dates corresponding to callsigns
     * @return array Array of DXCC entities indexed by callsign index
     */
    public function batchDxccLookup($callsigns, $dates)
    {
        $this->load->model('logbook_model');
        $entities = array();
        
        foreach ($callsigns as $index => $callsign) {
            $cacheKey = $callsign . '_' . $dates[$index];
            
            if (!isset($this->dxccCache[$cacheKey])) {
                $dxccInfo = $this->logbook_model->dxcc_lookup($callsign, $dates[$index]);
                $this->dxccCache[$cacheKey] = isset($dxccInfo['entity']) ? $dxccInfo['entity'] : null;
            }
            
            $entities[$index] = $this->dxccCache[$cacheKey];
        }
        
        return $entities;
    }

    /**
     * Batch check if DXCC entities have been worked/confirmed
     * @param array $entities Array of unique DXCC entities
     * @return array Array of worked/confirmed status indexed by entity
     */
    public function batchDxccWorkedStatus($entities)
    {
        if (empty($entities)) {
            return array();
        }

        $user_default_confirmation = $this->session->userdata('user_default_confirmation');
        $this->load->model('logbooks_model');
        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        
        if (empty($logbooks_locations_array)) {
            return array_fill_keys($entities, [
                'workedBefore' => false, 
                'confirmed' => false,
                'workedViaSatellite' => false
            ]);
        }

        $results = array();
        
        // Build confirmation criteria once
        $confirmationCriteria = $this->buildConfirmationCriteria($user_default_confirmation);
        
        // Debug: Log entities being checked
        log_message('debug', 'Workable DXCC: Checking entities: ' . implode(', ', $entities));
        
        // Batch query for worked status (terrestrial)
        $workedResults = $this->batchWorkedQuery($entities, $logbooks_locations_array);
        
        // Batch query for confirmed status (terrestrial)
        $confirmedResults = $this->batchConfirmedQuery($entities, $logbooks_locations_array, $confirmationCriteria);
        
        // Batch query for satellite contacts
        $satelliteResults = $this->batchSatelliteQuery($entities, $logbooks_locations_array);
        
        // Debug: Log results
        log_message('debug', 'Workable DXCC: Worked results: ' . json_encode($workedResults));
        log_message('debug', 'Workable DXCC: Confirmed results: ' . json_encode($confirmedResults));
        log_message('debug', 'Workable DXCC: Satellite results: ' . json_encode($satelliteResults));
        
        // Combine results
        foreach ($entities as $entity) {
            $results[$entity] = [
                'workedBefore' => isset($workedResults[$entity]),
                'confirmed' => isset($confirmedResults[$entity]),
                'workedViaSatellite' => isset($satelliteResults[$entity])
            ];
        }
        
        return $results;
    }

    /**
     * Batch check IOTA worked/confirmed status
     * @param array $iotas Array of unique IOTA tags
     * @return array Array indexed by IOTA tag with ['worked'=>bool,'confirmed'=>bool]
     */
    public function batchIotaWorkedStatus($iotas)
    {
        if (empty($iotas)) {
            return array();
        }

        $user_default_confirmation = $this->session->userdata('user_default_confirmation');
        $this->load->model('logbooks_model');
        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (empty($logbooks_locations_array)) {
            $out = [];
            foreach ($iotas as $i) {
                $out[$i] = ['worked' => false, 'confirmed' => false];
            }
            return $out;
        }

        // Build confirmation criteria once
        $confirmationCriteria = $this->buildConfirmationCriteria($user_default_confirmation);

        // Build case-insensitive WHERE conditions for COL_IOTA
        $whereConditions = array();
        foreach ($iotas as $iota) {
            $whereConditions[] = "UPPER(COL_IOTA) = UPPER('" . $this->db->escape_str($iota) . "')";
        }

        if (empty($whereConditions)) {
            return array();
        }

        $whereClause = '(' . implode(' OR ', $whereConditions) . ')';

        // Worked query (any mode)
        $this->db->select('COL_IOTA')
                 ->distinct()
                 ->from($this->config->item('table_name'))
                 ->where_in('station_id', $logbooks_locations_array)
                 ->where($whereClause);

        $workedQuery = $this->db->get();
        log_message('debug', 'Workable DXCC IOTA worked query: ' . $this->db->last_query());

        $workedResults = array();
        foreach ($workedQuery->result() as $row) {
            foreach ($iotas as $iota) {
                if (strtoupper($row->COL_IOTA) === strtoupper($iota)) {
                    $workedResults[$iota] = true;
                    break;
                }
            }
        }

        // Confirmed query (apply confirmation criteria, exclude satellite confirmations if desired)
        $confirmedResults = array();
        if ($confirmationCriteria !== '1=0') {
            $this->db->select('COL_IOTA')
                     ->distinct()
                     ->from($this->config->item('table_name'))
                     ->where($confirmationCriteria)
                     ->where_in('station_id', $logbooks_locations_array)
                     ->where($whereClause);

            $confirmedQuery = $this->db->get();
            foreach ($confirmedQuery->result() as $row) {
                foreach ($iotas as $iota) {
                    if (strtoupper($row->COL_IOTA) === strtoupper($iota)) {
                        $confirmedResults[$iota] = true;
                        break;
                    }
                }
            }
        }

        $out = array();
        foreach ($iotas as $iota) {
            $out[$iota] = [
                'worked' => isset($workedResults[$iota]),
                'confirmed' => isset($confirmedResults[$iota])
            ];
        }

        // Debug
        log_message('debug', 'Workable DXCC: IOTA worked results: ' . json_encode($workedResults));
        log_message('debug', 'Workable DXCC: IOTA confirmed results: ' . json_encode($confirmedResults));

        return $out;
    }

    /**
     * Batch query to check which entities have been worked via satellite
     */
    private function batchSatelliteQuery($entities, $logbooks_locations_array)
    {
        // Create case-insensitive matching for DXCC entities
        $whereConditions = array();
        foreach ($entities as $entity) {
            $whereConditions[] = "UPPER(COL_COUNTRY) = UPPER('" . $this->db->escape_str($entity) . "')";
        }
        
        if (empty($whereConditions)) {
            return array();
        }
        
        $whereClause = '(' . implode(' OR ', $whereConditions) . ')';
        
        $this->db->select('COL_COUNTRY')
                 ->distinct()
                 ->from($this->config->item('table_name'))
                 ->where('COL_PROP_MODE', 'SAT')  // Only satellite contacts
                 ->where_in('station_id', $logbooks_locations_array)
                 ->where($whereClause);
        
        $query = $this->db->get();
        
        // Debug: Log the SQL query
        log_message('debug', 'Workable DXCC satellite query: ' . $this->db->last_query());
        
        $results = array();
        
        foreach ($query->result() as $row) {
            // Store with the original entity case for lookup
            foreach ($entities as $entity) {
                if (strtoupper($row->COL_COUNTRY) === strtoupper($entity)) {
                    $results[$entity] = true;
                    log_message('debug', 'Workable DXCC: Found satellite match: ' . $entity . ' matches ' . $row->COL_COUNTRY);
                    break;
                }
            }
        }
        
        return $results;
    }

    /**
     * Build confirmation criteria SQL based on user preferences
     */
    private function buildConfirmationCriteria($user_default_confirmation)
    {
        $criteria = array();
        
        if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
            $criteria[] = "COL_QSL_RCVD='Y'";
        }
        if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
            $criteria[] = "COL_LOTW_QSL_RCVD='Y'";
        }
        if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
            $criteria[] = "COL_EQSL_QSL_RCVD='Y'";
        }
        if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Z') !== false) {
            $criteria[] = "COL_QRZCOM_QSO_DOWNLOAD_STATUS='Y'";
        }
        
        return empty($criteria) ? '1=0' : '(' . implode(' OR ', $criteria) . ')';
    }

    /**
     * Batch query to check which entities have been worked
     */
    private function batchWorkedQuery($entities, $logbooks_locations_array)
    {
        // Create case-insensitive matching for DXCC entities
        $whereConditions = array();
        foreach ($entities as $entity) {
            $whereConditions[] = "UPPER(COL_COUNTRY) = UPPER('" . $this->db->escape_str($entity) . "')";
        }
        
        if (empty($whereConditions)) {
            return array();
        }
        
        $whereClause = '(' . implode(' OR ', $whereConditions) . ')';
        
        $this->db->select('COL_COUNTRY')
                 ->distinct()
                 ->from($this->config->item('table_name'))
                 ->where('COL_PROP_MODE !=', 'SAT')
                 ->where_in('station_id', $logbooks_locations_array)
                 ->where($whereClause);
        
        $query = $this->db->get();
        
        // Debug: Log the SQL query
        log_message('debug', 'Workable DXCC worked query: ' . $this->db->last_query());
        
        $results = array();
        
        foreach ($query->result() as $row) {
            // Store with the original entity case for lookup
            foreach ($entities as $entity) {
                if (strtoupper($row->COL_COUNTRY) === strtoupper($entity)) {
                    $results[$entity] = true;
                    log_message('debug', 'Workable DXCC: Found worked match: ' . $entity . ' matches ' . $row->COL_COUNTRY);
                    break;
                }
            }
        }
        
        return $results;
    }

    /**
     * Batch query to check which entities have been confirmed
     */
    private function batchConfirmedQuery($entities, $logbooks_locations_array, $confirmationCriteria)
    {
        if ($confirmationCriteria === '1=0') {
            return array();
        }
        
        // Create case-insensitive matching for DXCC entities
        $whereConditions = array();
        foreach ($entities as $entity) {
            $whereConditions[] = "UPPER(COL_COUNTRY) = UPPER('" . $this->db->escape_str($entity) . "')";
        }
        
        if (empty($whereConditions)) {
            return array();
        }
        
        $whereClause = '(' . implode(' OR ', $whereConditions) . ')';
        
        $this->db->select('COL_COUNTRY')
                 ->distinct()
                 ->from($this->config->item('table_name'))
                 ->where('COL_PROP_MODE !=', 'SAT')
                 ->where($confirmationCriteria)
                 ->where_in('station_id', $logbooks_locations_array)
                 ->where($whereClause);
        
        $query = $this->db->get();
        $results = array();
        
        foreach ($query->result() as $row) {
            // Store with the original entity case for lookup
            foreach ($entities as $entity) {
                if (strtoupper($row->COL_COUNTRY) === strtoupper($entity)) {
                    $results[$entity] = true;
                    break;
                }
            }
        }
        
        return $results;
    }

    public function GetThisWeek()
    {
        $json = file_get_contents($this->optionslib->get_option('dxped_url'));
        $data = json_decode($json, true);

        if (empty($data)) {
            return array();
        }

        $thisWeekRecords = [];
        $startOfWeek = (new DateTime())->setISODate((new DateTime())->format('o'), (new DateTime())->format('W'), 1);
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');

        // Get Date format
        if ($this->session->userdata('user_date_format')) {
            $custom_date_format = $this->session->userdata('user_date_format');
        } else {
            $custom_date_format = $this->config->item('qso_date_format');
        }

        // First pass: filter records for this week
        $weekRecords = array();
        foreach ($data as $record) {
            $startDate = new DateTime($record['0']);
            $endDate = new DateTime($record['1']);

            if (($startDate >= $startOfWeek && $startDate <= $endOfWeek) || 
                ($endDate >= $startOfWeek && $endDate <= $endOfWeek)) {
                $weekRecords[] = $record;
            }
        }

        if (empty($weekRecords)) {
            return array();
        }

        // Batch process DXCC lookups
        $callsigns = array_column($weekRecords, 'callsign');
        $dates = array_column($weekRecords, '0');
        $dxccEntities = $this->batchDxccLookup($callsigns, $dates);

        // Get worked/confirmed status for all entities in batch
        $uniqueEntities = array_unique(array_filter($dxccEntities));
        $dxccStatus = $this->batchDxccWorkedStatus($uniqueEntities);

        // Process results
        foreach ($weekRecords as $index => $record) {
            $endDate = new DateTime($record['1']);
            $now = new DateTime();
            $interval = $now->diff($endDate);
            $daysLeft = $interval->days;

            $daysLeft = ($daysLeft == 0) ? "Last day" : $daysLeft . " days left";
            $record['daysLeft'] = $daysLeft;

            $oldStartDate = DateTime::createFromFormat('Y-m-d', $record['0']);
            $record['startDate'] = $oldStartDate->format($custom_date_format);

            $oldEndDate = DateTime::createFromFormat('Y-m-d', $record['1']);
            $record['endDate'] = $oldEndDate->format($custom_date_format);

            // Get DXCC status for this callsign
            $entity = $dxccEntities[$index] ?? null;
            $worked = $entity && isset($dxccStatus[$entity]) ? $dxccStatus[$entity] : [
                'workedBefore' => false, 
                'confirmed' => false,
                'workedViaSatellite' => false
            ];

            $record['workedBefore'] = $worked['workedBefore'];
            $record['confirmed'] = $worked['confirmed'];
            $record['workedViaSatellite'] = $worked['workedViaSatellite'];

            $thisWeekRecords[] = $record;
        }

        return $thisWeekRecords;
    }

    function dxccWorked($country)
    {

        $return = [
            "workedBefore" => false,
            "confirmed" => false,
            "workedViaSatellite" => false,
        ];

        $user_default_confirmation = $this->session->userdata('user_default_confirmation');
        $this->load->model('logbooks_model');
        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        $this->load->model('logbook_model');

        if (!empty($logbooks_locations_array)) {
            // Check for terrestrial contacts
            $this->db->where('COL_PROP_MODE !=', 'SAT');

            $this->db->where_in('station_id', $logbooks_locations_array);
            // Fix case sensitivity issue for DXCC country matching
            $this->db->where('UPPER(COL_COUNTRY) = UPPER(?)', urldecode($country));

            $query = $this->db->get($this->config->item('table_name'), 1, 0);
            foreach ($query->result() as $workedBeforeRow) {
                $return['workedBefore'] = true;
            }

            // Check for satellite contacts
            $this->db->where('COL_PROP_MODE', 'SAT');
            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('UPPER(COL_COUNTRY) = UPPER(?)', urldecode($country));

            $query = $this->db->get($this->config->item('table_name'), 1, 0);
            foreach ($query->result() as $satelliteRow) {
                $return['workedViaSatellite'] = true;
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
            // Fix case sensitivity issue for DXCC country matching
            $this->db->where('UPPER(COL_COUNTRY) = UPPER(?)', urldecode($country));

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
