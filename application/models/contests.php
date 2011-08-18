<?php

class Contests extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function information($id) {
		$this->db->select('*');
		$this->db->from('contests');
		//$this->db->where('id', $id); 
		$this->db->join('contest_template', 'contest_template.id = contests.template');
		$query = $this->db->get();
		return $query->row(); 
	}

	function create_template() {
		$data = array(
			'name' => $this->input->post('contest_name'),
			'band_160' => $this->input->post('160m'),
			'band_80' => $this->input->post('80m'),
			'band_40' => $this->input->post('40m'),
			'band_20' => $this->input->post('20m'),
			'band_15' => $this->input->post('15m'),
			'band_10' => $this->input->post('10m'),
			'band_6m' => $this->input->post('6m'),
			'band_4m' => $this->input->post('4m'),
			'band_2m' => $this->input->post('2m'),
			'band_70cm' => $this->input->post('70cm'),
			'band_23cm' => $this->input->post('23cm'),
			'mode_ssb' => $this->input->post('SSB'),
			'mode_cw' => $this->input->post('CW'),
			'serial' => $this->input->post('serial_num'),
			'point_per_km' => $this->input->post('points_per_km'),
			'qra' => $this->input->post('qra'),
			'other_exch' => $this->input->post('other_exch'),
			'scoring' => $this->input->post('scoring'),
		);

		$this->db->insert('contest_template', $data); 
	}
	
	
	function create_contest() {
		$start = $this->input->post('start_date')." ".$this->input->post('start_time');
		$end = $this->input->post('end_date')." ".$this->input->post('end_time');
		$data = array(
			'name' => $this->input->post('contest_name'),
			'start' => $start,
			'end' => $end,
			'template' => $this->input->post('template'),
		);

		$this->db->insert('contests', $data); 
	}

	function list_templates() {
		return $this->db->get('contest_template');
	}
	
	function list_contests() {
		return $this->db->get('contests');
	}

	function contest_summary_bands($start_date, $end_date, $info) {
		$query = $this->db->query('SELECT DISTINCT (COL_BAND) AS band, count( * ) AS count, COL_TIME_ON FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON >= \''.$start_date.'\' AND COL_TIME_ON <= \''.$end_date.'\' GROUP BY band ORDER BY count DESC');

		 return $query;
	}

	function contest_log_view($start_date, $end_date, $info) {
		$this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME,COL_STX_STRING,COL_SRX_STRING, COL_COUNTRY, COL_PRIMARY_KEY');
		$this->db->where('COL_TIME_ON >=', $start_date);
		$this->db->where('COL_TIME_ON <=', $end_date);
		if($info->band_160 == "Y") {
			$this->db->where('COL_BAND', '160m');
		}
		if($info->band_80 == "Y") {
			$this->db->where('COL_BAND', '80m');
		}
		if($info->band_40 == "Y") {
			$this->db->where('COL_BAND', '40m');
		}
		if($info->band_20 == "Y") {
			$this->db->where('COL_BAND', '20m');
		}
		if($info->band_15 == "Y") {
			$this->db->where('COL_BAND', '15m');
		}
		if($info->band_10 == "Y") {
			$this->db->where('COL_BAND', '10m');
		}
		if($info->band_6m == "Y") {
			$this->db->where('COL_BAND', '6m');
		}
		if($info->band_4m == "Y") {
			$this->db->where('COL_BAND', '4m');
		}
		if($info->band_2m == "Y") {
			$this->db->where('COL_BAND', '2m');
		}
		if($info->band_70cm == "Y") {
			$this->db->where('COL_BAND', '70cm');
		}
		if($info->band_23cm == "Y") {
			$this->db->where('COL_BAND', '23cm');
		}
		if($info->mode_ssb == "Y") {
			$this->db->where('COL_MODE', 'SSB');
		}
		if($info->mode_cw == "Y") {
			$this->db->where('COL_MODE', 'CW');
		}
		//$this->db->order_by("COL_PRIMARY_KEY", "asc");
		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit(10);
		
		return $this->db->get($this->config->item('table_name'));
	
	}
	
	function add($contest_id) {
		// Join date+time
		$datetime = date('Y-m-d') ." ". $this->input->post('start_time');

		// Create array with QSO Data
		$data = array(
		   'COL_TIME_ON' => $datetime,
		   'COL_TIME_OFF' => $datetime,
		   'COL_CALL' => strtoupper($this->input->post('callsign')),
		   'COL_BAND' => $this->input->post('band'),
		   'COL_FREQ' => $this->input->post('freq'),
		   'COL_MODE' => $this->input->post('mode'),
		   'COL_RST_RCVD' => $this->input->post('rst_recv'),
		   'COL_SRX_STRING' => $this->input->post('recv_serial'),
		   'COL_RST_SENT' => $this->input->post('rst_sent'),
		   'COL_STX_STRING' => $this->input->post('sent_serial'),
		   'COL_GRIDSQUARE' => $this->input->post('locator'),
		   'COL_COUNTRY' => $this->input->post('country'),
		   'COL_QSLSDATE' => date('Y-m-d'),
		   'COL_QSLRDATE' => date('Y-m-d'),
		   'COL_QSL_SENT' => 'N',
		   'COL_QSL_RCVD' => 'N',
		);

		// Add QSO to database
		$this->db->insert($this->config->item('table_name'), $data);
		
		// Update contest file stored serial number.
		$data = array(
			   'serial_num' => $this->input->post('sent_serial'),
			);

		$this->db->where('id', $contest_id);
		$this->db->update('contests', $data); 
	}

}

?>