<?php
use Cloudlog\QSLManager\QSO;

class Logbookadvanced_model extends CI_Model {
    /**
	 * Returns worked modes in the supplied stations as a simple array
	 * @param array $stationIds
	 * @return array
	 */
	function get_worked_modes(array $stationIds): array	{
		$CI =& get_instance();
		$CI->load->model('logbooks_model');

		$ids =  "'".implode("','",$stationIds)."'";

		$sql = "
		SELECT distinct `COL_MODE`, `COL_SUBMODE`
		FROM `" . $this->config->item('table_name') . "` qsos
		WHERE qsos.station_id IN (".$ids.")
		ORDER BY COL_MODE, COL_SUBMODE";

		$data = $this->db->query($sql);
			
		$results = [];
		foreach ($data->result() as $row) {
			$results[] = [
				'mode' => $row->COL_MODE,
				'submode' => $row->COL_SUBMODE
			];
		}
		return $results;
	}

  /*
   * @param array $searchCriteria
   * @return array
   */
  public function searchQsos($searchCriteria) : array {
		$conditions = [];
		$binding = [$searchCriteria['user_id']];

        if ($searchCriteria['dateFrom'] !== '') {
            $from = DateTime::createFromFormat('d/m/Y', $searchCriteria['dateFrom']);
			$from = $from->format('Y-m-d');
			$conditions[] = "date(COL_TIME_ON) >= ?";
			$binding[] = $from;
		}
        if ($searchCriteria['dateTo'] !== '') {
            $to = DateTime::createFromFormat('d/m/Y', $searchCriteria['dateTo']);
			$to = $to->format('Y-m-d');
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
			list ($mode, $subMode) = explode("|", trim($searchCriteria['mode']));
			$mode = trim($mode);
			$subMode = trim($subMode);
			$conditions[] = "COL_MODE = ?";
			$binding[] = $mode;
			if ($subMode !== '') {
				$conditions[] = "COL_SUBMODE = ?";
				$binding[] = $subMode;
			}
		}
		if ($searchCriteria['band'] !== '') {
			$conditions[] = "COL_BAND = ?";
			$binding[] = trim($searchCriteria['band']);
		}
		if ($searchCriteria['qslSent'] !== '') {
			$conditions[] = "COL_QSL_SENT = ?";
			$binding[] = $searchCriteria['qslSent'];
		}
		if ($searchCriteria['qslReceived'] !== '') {
			$conditions[] = "COL_QSL_RCVD = ?";
			$binding[] = $searchCriteria['qslReceived'];
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

        if ($searchCriteria['gridsquare'] !== '') {
                $conditions[] = "(COL_GRIDSQUARE like ? or COL_VUCC_GRIDS like ?)";
                $binding[] = '%' . $searchCriteria['gridsquare'] . '%';
                $binding[] = '%' . $searchCriteria['gridsquare'] . '%';
        }

        if ($searchCriteria['propmode'] !== '') {
                $conditions[] = "COL_PROP_MODE = ?";
                $binding[] = $searchCriteria['propmode'];
        }
    
		$where = trim(implode(" AND ", $conditions));
		if ($where != "") {
			$where = "AND $where";
		}

		$limit = $searchCriteria['qsoresults'];

		$sql = "
			SELECT *
			FROM " . $this->config->item('table_name') . " qsos
			INNER JOIN station_profile ON qsos.station_id=station_profile.station_id
			WHERE station_profile.user_id =  ?
			$where
			ORDER BY qsos.COL_TIME_ON desc
			LIMIT $limit
		";

		$data = $this->db->query($sql, $binding);

        $results = $data->result('array');
        
        $qsos = [];
        foreach ($results as $data) {
            $qsos[] = new QSO($data);
        }
		
	    return $qsos;
	}

    public function getQsosForAdif($ids, $user_id) : object {
		$binding = [$user_id];
        $conditions[] = "COL_PRIMARY_KEY in ?";
        $binding[] = json_decode($ids, true);

		$where = trim(implode(" AND ", $conditions));
		if ($where != "") {
			$where = "AND $where";
		}

        $sql = "
            SELECT *
			FROM " . $this->config->item('table_name') . " qsos
			INNER JOIN station_profile ON qsos.station_id = station_profile.station_id
			WHERE station_profile.user_id =  ?
			$where
			ORDER BY qsos.COL_TIME_ON desc
		";

		return $this->db->query($sql, $binding);
    }

    public function updateQsl($ids, $user_id, $method, $sent) {
        $this->load->model('user_model');

        if(!$this->user_model->authorize(2)) {
            return array('message' => 'Error');
        } else {
            $data = array(
                'COL_QSLSDATE' => date('Y-m-d H:i:s'),
                'COL_QSL_SENT' => $sent,
                'COL_QSL_SENT_VIA' => $method
            );
            $this->db->where_in('COL_PRIMARY_KEY', json_decode($ids, true));
            $this->db->update($this->config->item('table_name'), $data);
            
            return array('message' => 'OK');
        }
    }
}