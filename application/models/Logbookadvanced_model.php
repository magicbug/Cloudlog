<?php
use Cloudlog\QSLManager\QSO;

class Logbookadvanced_model extends CI_Model {

	public function searchDb($searchCriteria) {
		$conditions = [];
		$binding = [$searchCriteria['user_id']];

		if ((isset($searchCriteria['dupes'])) && ($searchCriteria['dupes'] !== '')) {
			$id_sql="select GROUP_CONCAT(col_primary_key separator ',') as qsoids, COL_CALL, COL_MODE, COL_SUBMODE, station_callsign, COL_SAT_NAME, COL_BAND,  min(col_time_on) Mintime, max(col_time_on) Maxtime from " . $this->config->item('table_name') . "
				 join station_profile on " . $this->config->item('table_name') . ".station_id = station_profile.station_id where station_profile.user_id=?
				group by col_call, col_mode, COL_SUBMODE, STATION_CALLSIGN, col_band, COL_SAT_NAME having count(*) > 1 and timediff(maxtime, mintime) < 3000";
			$id_query = $this->db->query($id_sql, $searchCriteria['user_id']);
			$ids2fetch = '';
			foreach ($id_query->result() as $id) {
				$ids2fetch .= ','.$id->qsoids;
			}
			$ids2fetch = ltrim($ids2fetch, ',');
			if ($ids2fetch ?? '' !== '') {
				$conditions[] = "qsos.COL_PRIMARY_KEY in (".$ids2fetch.")";
			} else {
				$conditions[] = "1=0";
			}
		}

        if ($searchCriteria['dateFrom'] !== '') {
            $from = $searchCriteria['dateFrom'];
			$conditions[] = "date(COL_TIME_ON) >= ?";
			$binding[] = $from;
		}
        if ($searchCriteria['dateTo'] !== '') {
            $to = $searchCriteria['dateTo'];
			$conditions[] = "date(COL_TIME_ON) <= ?";
			$binding[] = $to;
		}
		if ($searchCriteria['de'] !== '') {
			$conditions[] = "COL_STATION_CALLSIGN = ?";
			$binding[] = trim($searchCriteria['de']);
		}
		if ($searchCriteria['dx'] !== '') {
			$conditions[] = "COL_CALL LIKE ?";
			$binding[] = '%' . trim($searchCriteria['dx']) . '%';
		}
		if ($searchCriteria['mode'] !== '') {
			$conditions[] = "(COL_MODE = ? or COL_SUBMODE = ?)";
			$binding[] = $searchCriteria['mode'];
			$binding[] = $searchCriteria['mode'];
		}
		if ($searchCriteria['band'] !== '') {
			if($searchCriteria['band'] != "SAT") {
				$conditions[] = "COL_BAND = ? and COL_PROP_MODE != 'SAT'";
				$binding[] = trim($searchCriteria['band']);
			} else {
				$conditions[] = "COL_PROP_MODE = 'SAT'";
				if ($searchCriteria['sats'] !== 'All') {
					$conditions[] = "COL_SAT_NAME = ?";
					$binding[] = trim($searchCriteria['sats']);
				}
			}
		}
		if ($searchCriteria['qslSent'] !== '') {
			$condition = "COL_QSL_SENT = ?";
			if ($searchCriteria['qslSent'] == 'N') {
				$condition = '('.$condition;
				$condition .= " OR COL_QSL_SENT IS NULL OR COL_QSL_SENT = '')";
			}
			$conditions[] = $condition;
			$binding[] = $searchCriteria['qslSent'];
		}
		if ($searchCriteria['qslReceived'] !== '') {
			$condition = "COL_QSL_RCVD = ?";
			if ($searchCriteria['qslReceived'] == 'N') {
				$condition = '('.$condition;
				$condition .= " OR COL_QSL_RCVD IS NULL OR COL_QSL_RCVD = '')";
			}
			$conditions[] = $condition;
			$binding[] = $searchCriteria['qslReceived'];
		}

		if ($searchCriteria['qslSentMethod'] !== '') {
			$condition = "COL_QSL_SENT_VIA = ?";
			$conditions[] = $condition;
			$binding[] = $searchCriteria['qslSentMethod'];
		}

		if ($searchCriteria['qslReceivedMethod'] !== '') {
			$condition = "COL_QSL_RCVD_VIA = ?";
			$conditions[] = $condition;
			$binding[] = $searchCriteria['qslReceivedMethod'];
		}

		if ($searchCriteria['lotwSent'] !== '') {
			$condition = "COL_LOTW_QSL_SENT = ?";
			if ($searchCriteria['lotwSent'] == 'N') {
				$condition = '('.$condition;
				$condition .= " OR COL_LOTW_QSL_SENT IS NULL OR COL_LOTW_QSL_SENT = '')";
			}
			$conditions[] = $condition;
			$binding[] = $searchCriteria['lotwSent'];
		}
		if ($searchCriteria['lotwReceived'] !== '') {
			$condition = "COL_LOTW_QSL_RCVD = ?";
			if ($searchCriteria['lotwReceived'] == 'N') {
				$condition = '('.$condition;
				$condition .= " OR COL_LOTW_QSL_RCVD IS NULL OR COL_LOTW_QSL_RCVD = '')";
			}
			$conditions[] = $condition;
			$binding[] = $searchCriteria['lotwReceived'];
		}

		if ($searchCriteria['eqslSent'] !== '') {
			$condition = "COL_EQSL_QSL_SENT = ?";
			if ($searchCriteria['eqslSent'] == 'N') {
				$condition = '('.$condition;
				$condition .= " OR COL_EQSL_QSL_SENT IS NULL OR COL_EQSL_QSL_SENT = '')";
			}
			$conditions[] = $condition;
			$binding[] = $searchCriteria['eqslSent'];
		}
		if ($searchCriteria['eqslReceived'] !== '') {
			$condition = "COL_EQSL_QSL_RCVD = ?";
			if ($searchCriteria['eqslReceived'] == 'N') {
				$condition = '('.$condition;
				$condition .= " OR COL_EQSL_QSL_RCVD IS NULL OR COL_EQSL_QSL_RCVD = '')";
			}
			$conditions[] = $condition;
			$binding[] = $searchCriteria['eqslReceived'];
		}

        if ($searchCriteria['iota'] !== '') {
			$conditions[] = "COL_IOTA = ?";
			$binding[] = $searchCriteria['iota'];
		}

        if ($searchCriteria['dxcc'] !== '') {
			$conditions[] = "COL_DXCC = ?";
			$binding[] = $searchCriteria['dxcc'];
		}

        if ($searchCriteria['state'] !== '') {
			$conditions[] = "COL_STATE = ?";
			$binding[] = $searchCriteria['state'];
		}

		if ($searchCriteria['cqzone'] !== '') {
			$conditions[] = "COL_CQZ = ?";
			$binding[] = $searchCriteria['cqzone'];
		}

		if ($searchCriteria['qslvia'] !== '') {
			$conditions[] = "COL_QSL_VIA like ?";
			$binding[] = $searchCriteria['qslvia'].'%';
		}

		if ($searchCriteria['sota'] !== '') {
			$conditions[] = "COL_SOTA_REF like ?";
			$binding[] = $searchCriteria['sota'].'%';
		}

		if ($searchCriteria['pota'] !== '') {
			$conditions[] = "COL_POTA_REF like ?";
			$binding[] = $searchCriteria['pota'].'%';
		}

		if ($searchCriteria['wwff'] !== '') {
			$conditions[] = "COL_WWFF_REF like ?";
			$binding[] = $searchCriteria['wwff'].'%';
		}

		if ($searchCriteria['operator'] !== '') {
			$conditions[] = "COL_OPERATOR like ?";
			$binding[] = $searchCriteria['operator'].'%';
		}

        if ($searchCriteria['gridsquare'] !== '') {
                $conditions[] = "(COL_GRIDSQUARE like ? or COL_VUCC_GRIDS like ?)";
                $binding[] = '%' . $searchCriteria['gridsquare'] . '%';
                $binding[] = '%' . $searchCriteria['gridsquare'] . '%';
        }

        if ($searchCriteria['propmode'] !== '') {
                $conditions[] = "COL_PROP_MODE = ?";
                $binding[] = $searchCriteria['propmode'];
                if($searchCriteria['propmode'] == "SAT") {
                        if ($searchCriteria['sats'] !== 'All') {
                                $conditions[] = "COL_SAT_NAME = ?";
                                $binding[] = trim($searchCriteria['sats']);
                        }
                }
        }

		if (($searchCriteria['ids'] ?? '') !== '') {
			$conditions[] = "qsos.COL_PRIMARY_KEY in (".implode(",",$searchCriteria['ids']).")";
		}

		$where = trim(implode(" AND ", $conditions));
		if ($where != "") {
			$where = "AND $where";
		}

		$limit = $searchCriteria['qsoresults'];
		// Ensure limit has a valid value, default to 250 if empty or invalid
		if (empty($limit) || !is_numeric($limit) || $limit <= 0) {
			$limit = 250;
		}

		// Create a version of $where for the inner subquery with proper table alias
		$whereInner = str_replace('qsos.', 'qsos_inner.', $where);

		$where2 = '';

		if ($searchCriteria['qslimages'] !== '') {
			if ($searchCriteria['qslimages'] == 'Y') {
				$where2 .= ' and x.qslcount > "0"';
			}
			if ($searchCriteria['qslimages'] == 'N') {
				$where2 .= ' and x.qslcount is null';
			}
		}

		$sql = "
			SELECT qsos.*, station_profile.*, dxcc_entities.*, lotw.callsign, lotw.lastupload, x.qslcount
			FROM (
				SELECT qsos_inner.COL_PRIMARY_KEY
				FROM " . $this->config->item('table_name') . " qsos_inner
				INNER JOIN station_profile sp_inner ON qsos_inner.station_id = sp_inner.station_id
				WHERE sp_inner.user_id = ?
				$whereInner
				ORDER BY qsos_inner.COL_TIME_ON desc, qsos_inner.COL_PRIMARY_KEY desc
				LIMIT $limit
			) AS FilteredIDs
			INNER JOIN " . $this->config->item('table_name') . " qsos ON qsos.COL_PRIMARY_KEY = FilteredIDs.COL_PRIMARY_KEY
			INNER JOIN station_profile ON qsos.station_id = station_profile.station_id
			LEFT OUTER JOIN dxcc_entities ON qsos.col_dxcc = dxcc_entities.adif
			LEFT JOIN lotw_users lotw ON qsos.col_call = lotw.callsign
			LEFT OUTER JOIN (
				select count(*) as qslcount, qsoid
				from qsl_images
				group by qsoid
			) x on qsos.COL_PRIMARY_KEY = x.qsoid
			WHERE 1=1
			$where2
			ORDER BY qsos.COL_TIME_ON desc, qsos.COL_PRIMARY_KEY desc
		";
		$data = $this->db->query($sql, $binding);

        $results = $data->result('array');
		return $results;
	}

  /*
   * @param array $searchCriteria
   * @return array
   */
  public function searchQsos($searchCriteria) : array {
		$results = $this->searchDb($searchCriteria);

        $qsos = [];
        foreach ($results as $data) {
            $qsos[] = new QSO($data);
        }

		return $qsos;
	}

    public function getQsosForAdif($ids, $user_id, $sortorder = null) : object {
		$binding = [$user_id];
        $conditions[] = "COL_PRIMARY_KEY in ?";
        $binding[] = json_decode($ids, true);

		$where = trim(implode(" AND ", $conditions));
		if ($where != "") {
			$where = "AND $where";
		}

		$order = $this->getSortorder($sortorder);

		$sql = "
			SELECT qsos.*, d2.*, lotw.*, station_profile.*, x.qslcount, dxcc_entities.name AS station_country
			FROM " . $this->config->item('table_name') . " qsos
			INNER JOIN station_profile ON qsos.station_id = station_profile.station_id
			LEFT OUTER JOIN dxcc_entities ON qsos.COL_MY_DXCC = dxcc_entities.adif
			LEFT OUTER JOIN dxcc_entities d2 ON qsos.COL_DXCC = d2.adif
			LEFT JOIN lotw_users lotw ON qsos.col_call = lotw.callsign
			LEFT OUTER JOIN (
				select count(*) as qslcount, qsoid
				from qsl_images
				group by qsoid
			) x on qsos.COL_PRIMARY_KEY = x.qsoid
			WHERE station_profile.user_id =  ?
			$where
			$order
		";

		return $this->db->query($sql, $binding);
    }

	public function getSortOrder($sortorder) {
		if ($sortorder == null) {
			return 'ORDER BY qsos.COL_TIME_ON desc';
		} else {
			$sortorder = explode(',', $sortorder);

			if ($this->session->userdata('user_lotw_name') != "" && $this->session->userdata('user_eqsl_name') != ""){
				switch($sortorder[0]) {
					case 1: return 'ORDER BY qsos.COL_TIME_ON ' . $sortorder[1];
					case 2: return 'ORDER BY station_profile.station_callsign ' . $sortorder[1];
					case 3: return 'ORDER BY qsos.COL_CALL ' . $sortorder[1];
					case 4: return 'ORDER BY qsos.COL_MODE' .  $sortorder[1] . ', qsos.COL_SUBMODE ' . $sortorder[1];
					case 7: return 'ORDER BY qsos.COL_BAND ' . $sortorder[1] . ', qsos.COL_SAT_NAME ' . $sortorder[1];
					case 16: return 'ORDER BY qsos.COL_COUNTRY ' . $sortorder[1];
					case 17: return 'ORDER BY qsos.COL_STATE ' . $sortorder[1];
					case 18: return 'ORDER BY qsos.COL_CQZ ' . $sortorder[1];
					case 19: return 'ORDER BY qsos.COL_IOTA ' . $sortorder[1];
					default: return 'ORDER BY qsos.COL_TIME_ON desc';
				}
			}

			else if (($this->session->userdata('user_eqsl_name') != "" && $this->session->userdata('user_lotw_name') == "") || ($this->session->userdata('user_eqsl_name') == "" && $this->session->userdata('user_lotw_name') != "")) {
				switch($sortorder[0]) {
					case 1: return 'ORDER BY qsos.COL_TIME_ON ' . $sortorder[1];
					case 2: return 'ORDER BY station_profile.station_callsign ' . $sortorder[1];
					case 3: return 'ORDER BY qsos.COL_CALL ' . $sortorder[1];
					case 4: return 'ORDER BY qsos.COL_MODE' .  $sortorder[1] . ', qsos.COL_SUBMODE ' . $sortorder[1];
					case 7: return 'ORDER BY qsos.COL_BAND ' . $sortorder[1] . ', qsos.COL_SAT_NAME ' . $sortorder[1];
					case 15: return 'ORDER BY qsos.COL_COUNTRY ' . $sortorder[1];
					case 16: return 'ORDER BY qsos.COL_STATE ' . $sortorder[1];
					case 17: return 'ORDER BY qsos.COL_CQZ ' . $sortorder[1];
					case 18: return 'ORDER BY qsos.COL_IOTA ' . $sortorder[1];
					default: return 'ORDER BY qsos.COL_TIME_ON desc';
				}
			}

			else if ($this->session->userdata('user_eqsl_name') == "" && $this->session->userdata('user_lotw_name') == ""){
				switch($sortorder[0]) {
					case 1: return 'ORDER BY qsos.COL_TIME_ON ' . $sortorder[1];
					case 2: return 'ORDER BY station_profile.station_callsign ' . $sortorder[1];
					case 3: return 'ORDER BY qsos.COL_CALL ' . $sortorder[1];
					case 4: return 'ORDER BY qsos.COL_MODE' .  $sortorder[1] . ', qsos.COL_SUBMODE ' . $sortorder[1];
					case 7: return 'ORDER BY qsos.COL_BAND ' . $sortorder[1] . ', qsos.COL_SAT_NAME ' . $sortorder[1];
					case 14: return 'ORDER BY qsos.COL_COUNTRY ' . $sortorder[1];
					case 15: return 'ORDER BY qsos.COL_STATE ' . $sortorder[1];
					case 16: return 'ORDER BY qsos.COL_CQZ ' . $sortorder[1];
					case 17: return 'ORDER BY qsos.COL_IOTA ' . $sortorder[1];
					default: return 'ORDER BY qsos.COL_TIME_ON desc';
				}
			}
		}
	}

	public function updateQsl($ids, $user_id, $method, $sent) {
		$this->load->model('user_model');

		if(!$this->user_model->authorize(2)) {
			return array('message' => 'Error');
		} else {
			if ($method != '') {
				$data = array(
					'COL_QSLSDATE' => date('Y-m-d H:i:s'),
					'COL_QSL_SENT' => $sent,
					'COL_QSL_SENT_VIA' => $method
				);
			} else {
				$data = array(
					'COL_QSLSDATE' => date('Y-m-d H:i:s'),
					'COL_QSL_SENT' => $sent,
				);
			}
			$this->db->where_in('COL_PRIMARY_KEY', json_decode($ids, true));
			$this->db->update($this->config->item('table_name'), $data);

			return array('message' => 'OK');
		}
	}

	public function updateQslReceived($ids, $user_id, $method, $sent) {
        $this->load->model('user_model');

        if(!$this->user_model->authorize(2)) {
            return array('message' => 'Error');
        } else {
            $data = array(
                'COL_QSLRDATE' => date('Y-m-d H:i:s'),
                'COL_QSL_RCVD' => $sent,
                'COL_QSL_RCVD_VIA' => $method
            );
            $this->db->where_in('COL_PRIMARY_KEY', json_decode($ids, true));
            $this->db->update($this->config->item('table_name'), $data);

            return array('message' => 'OK');
        }
    }

	public function updateStationLocation($ids, $user_id, $station_id) {
		$this->load->model('user_model');

		if(!$this->user_model->authorize(2)) {
			return array('message' => 'Error');
		} else {
			// Verify that the station_id belongs to the user - optimized to avoid N+1 query
			$this->load->model('Stations');
			if (!$this->Stations->user_owns_station($user_id, $station_id)) {
				return array('message' => 'Invalid station ID');
			}

			$data = array(
				'station_id' => $station_id
			);
			$this->db->where_in('COL_PRIMARY_KEY', json_decode($ids, true));
			$this->db->update($this->config->item('table_name'), $data);

			return array('message' => 'OK');
		}
	}

	public function updateSatellite($ids, $user_id, $sat_name, $sat_mode, $uplink_freq, $downlink_freq, $uplink_mode, $downlink_mode) {
		$this->load->model('user_model');

		if(!$this->user_model->authorize(2)) {
			return array('message' => 'Error');
		} else {
			$this->load->library('frequency');
			
			// Calculate bands from frequencies using existing Frequency library
			$uplink_band = $this->frequency->GetBand($uplink_freq);
			$downlink_band = $this->frequency->GetBand($downlink_freq);
			
			// Determine mode based on uplink and downlink modes
			$mode = $uplink_mode;
			if (($uplink_mode == 'USB' && $downlink_mode == 'LSB') || ($uplink_mode == 'LSB' && $downlink_mode == 'USB')) {
				$mode = 'SSB';
			}

			$data = array(
				'COL_SAT_NAME' => $sat_name,
				'COL_SAT_MODE' => $sat_mode,
				'COL_FREQ' => $uplink_freq,
				'COL_FREQ_RX' => $downlink_freq,
				'COL_BAND' => $uplink_band,
				'COL_BAND_RX' => $downlink_band,
				'COL_MODE' => $mode,
				'COL_PROP_MODE' => 'SAT'
			);
			
			$this->db->where_in('COL_PRIMARY_KEY', json_decode($ids, true));
			$this->db->update($this->config->item('table_name'), $data);

			return array('message' => 'OK');
		}
	}

	public function updateQsoWithCallbookInfo($qsoID, $qso, $callbook) {
		$updatedData = array();
		if (!empty($callbook['name']) && empty($qso['COL_NAME'])) {
			$updatedData['COL_NAME'] = $callbook['name'];
		}
		if (!empty($callbook['gridsquare']) && empty($qso['COL_GRIDSQUARE']) && empty($qso['COL_VUCC_GRIDS'] )) {
			// Validate gridsquare before adding - reject obvious invalid/placeholder values
			if ($this->isValidGridsquare($callbook['gridsquare'])) {
				if (strpos(trim($callbook['gridsquare']), ',') === false) {
					$updatedData['COL_GRIDSQUARE'] = strtoupper(trim($callbook['gridsquare']));
				} else {
					$updatedData['COL_VUCC_GRIDS'] = strtoupper(trim($callbook['gridsquare']));
				}
			}
		}
		if (!empty($callbook['city']) && empty($qso['COL_QTH'])) {
			$updatedData['COL_QTH'] = $callbook['city'];
		}
		if (!empty($callbook['lat']) && empty($qso['COL_LAT'])) {
			$updatedData['COL_LAT'] = $callbook['lat'];
		}
		if (!empty($callbook['long']) && empty($qso['COL_LON'])) {
			$updatedData['COL_LON'] = $callbook['long'];
		}
		if (!empty($callbook['iota']) && empty($qso['COL_IOTA'])) {
			$updatedData['COL_IOTA'] = $callbook['iota'];
		}
		if (!empty($callbook['state']) && empty($qso['COL_STATE'])) {
			$updatedData['COL_STATE'] = $callbook['state'];
		}
		if (!empty($callbook['us_county']) && empty($qso['COL_USACA_COUNTIES'])) {
			$updatedData['COL_USACA_COUNTIES'] = $callbook['us_county'];
		}
		if (!empty($callbook['qslmgr']) && empty($qso['COL_QSL_VIA'])) {
			$updatedData['COL_QSL_VIA'] = $callbook['qslmgr'];
		}

		if (count($updatedData) > 0) {
			$this->db->where('COL_PRIMARY_KEY', $qsoID);
			$this->db->update($this->config->item('table_name'), $updatedData);
			return true;
		}

		return false;
    }

	/**
	 * Validate gridsquare to reject obvious invalid/placeholder values
	 * Returns false for gridsquares like AA00AA, JJ00JJ, etc. which are
	 * commonly used as placeholders when the actual grid is unknown
	 */
	private function isValidGridsquare($gridsquare) {
		if (empty($gridsquare)) {
			return false;
		}

		$gridsquare = strtoupper(trim($gridsquare));
		
		// Handle multiple gridsquares (VUCC format)
		$grids = explode(',', $gridsquare);
		
		foreach ($grids as $grid) {
			$grid = trim($grid);
			
			// Must be valid length (4, 6, 8, or 10 characters)
			$len = strlen($grid);
			if ($len < 4 || $len > 10 || $len % 2 != 0) {
				return false;
			}
			
			// Basic format validation
			if (!preg_match('/^[A-R]{2}[0-9]{2}([A-X]{2})?([0-9]{2})?([A-X]{2})?$/', $grid)) {
				return false;
			}
			
			// Reject obvious placeholder patterns where field and subfield are identical
			// e.g., AA00AA, BB11BB, JJ00JJ etc.
			if ($len >= 6) {
				$field = substr($grid, 0, 2);      // First 2 chars (e.g., "AA", "JJ")
				$subfield = substr($grid, 4, 2);   // Chars 5-6 (e.g., "AA", "JJ")
				
				// If both field letters are the same AND both subfield letters are the same
				// AND they match each other, it's likely a placeholder
				if ($field[0] === $field[1] && $subfield[0] === $subfield[1] && $field[0] === $subfield[0]) {
					return false;
				}
			}
		}
		
		return true;
	}

	function get_modes() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$modes = array();

		$this->db->select('distinct col_mode, coalesce(col_submode, "") col_submode', FALSE);
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->order_by('col_mode, col_submode', 'ASC');

		$query = $this->db->get($this->config->item('table_name'));

		foreach($query->result() as $mode){
			if ($mode->col_submode == null || $mode->col_submode == "") {
				array_push($modes, $mode->col_mode);
			} else {
				array_push($modes, $mode->col_submode);
			}
		}

		return $modes;
	}

	function getQslsForQsoIds($ids) {
		$CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->db->select('*');
		$this->db->from($this->config->item('table_name'));
        $this->db->join('qsl_images', 'qsl_images.qsoid = ' . $this->config->item('table_name') . '.col_primary_key');
        $this->db->where_in('qsoid', $ids);
		$this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->order_by("id", "desc");

        return $this->db->get()->result();
    }
}
