<?php

class Logbook_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	/* Add QSO to Logbook */
	function add() {
		// Join date+time
		$datetime = date('Y-m-d') ." ". $this->input->post('start_time');
	    
		if ($this->input->post('prop_mode') != null) {
			$prop_mode = $this->input->post('prop_mode');
		} else {
			$prop_mode = "";
		}
		
        if($this->input->post('sat_name')) {
            $prop_mode = "SAT";
        }
        
        if($this->session->userdata('user_locator')){
                $locator = $this->session->userdata('user_locator');
        } else {
                $locator = $this->config->item('locator');
        }
    
    
		// Create array with QSO Data
		$data = array(
		   'COL_TIME_ON' => $datetime,
		   'COL_TIME_OFF' => $datetime,
		   'COL_CALL' => strtoupper($this->input->post('callsign')),
		   'COL_BAND' => $this->input->post('band'),
		   'COL_FREQ' => $this->input->post('freq_display'),
		   'COL_MODE' => $this->input->post('mode'),
		   'COL_RST_RCVD' => $this->input->post('rst_recv'),
		   'COL_RST_SENT' => $this->input->post('rst_sent'),
           'COL_NAME' => $this->input->post('name'),
		   'COL_COMMENT' => $this->input->post('comment'),
		   'COL_SAT_NAME' => $this->input->post('sat_name'),
		   'COL_SAT_MODE' => $this->input->post('sat_mode'),
		   'COL_GRIDSQUARE' => trim($this->input->post('locator')),
		   'COL_COUNTRY' => $this->input->post('country'),
		   'COL_MY_RIG' => $this->input->post('equipment'),
           'COL_QSLSDATE' => date('Y-m-d'),
           'COL_QSLRDATE' => date('Y-m-d'),
           'COL_QSL_SENT' => $this->input->post('qsl_sent'),
           'COL_QSL_RCVD' => $this->input->post('qsl_recv'),
           'COL_QSL_SENT_VIA' => $this->input->post('qsl_sent_method'),
           'COL_QSL_RCVD_VIA' => $this->input->post('qsl_recv_method'),
		   'COL_QSL_VIA' => $this->input->post('qsl_via'),
           'COL_OPERATOR' => $this->session->userdata('user_callsign'),
           'COL_QTH' => $this->input->post('qth'),
           'COL_PROP_MODE' => $prop_mode,
           'COL_IOTA' => $this->input->post('iota_ref'),
           'COL_MY_GRIDSQUARE' => $locator,
           'COL_DISTANCE' => "0",
           'COL_FREQ_RX' => 0,
           'COL_BAND_RX' => null,
           'COL_ANT_AZ' => null,
           'COL_ANT_EL' => null,
           'COL_A_INDEX' => null,
           'COL_AGE' => null,
           'COL_TEN_TEN' => null,
           'COL_TX_PWR' => null,
           'COL_STX' => null,
           'COL_SRX' => null,
           'COL_NR_BURSTS' => null,
           'COL_NR_PINGS' => null,
           'COL_MAX_BURSTS' => null,
           'COL_K_INDEX' => null,
           'COL_SFI' => null,
           'COL_RX_PWR' => null,
           'COL_LAT' => null,
           'COL_LON' => null,
		);

		$this->add_qso($data);
	}

	function add_qso($data) {
		// Add QSO to database
		$this->db->insert($this->config->item('table_name'), $data);
	}
	
	/* Edit QSO */
	function edit() {
	
		$data = array(
		   'COL_TIME_ON' => $this->input->post('time_on'),
		   'COL_TIME_OFF' => $this->input->post('time_off'),
		   'COL_CALL' => strtoupper($this->input->post('callsign')),
		   'COL_BAND' => $this->input->post('band'),
		   'COL_FREQ' => $this->input->post('freq'),
		   'COL_MODE' => $this->input->post('mode'),
		   'COL_RST_RCVD' => $this->input->post('rst_recv'),
		   'COL_RST_SENT' => $this->input->post('rst_sent'),
           'COL_GRIDSQUARE' => $this->input->post('locator'),
		   'COL_COMMENT' => $this->input->post('comment'),
		   'COL_NAME' => $this->input->post('name'),
		   'COL_SAT_NAME' => $this->input->post('sat_name'),
		   'COL_SAT_MODE' => $this->input->post('sat_mode'),
           'COL_QSLSDATE' => date('Y-m-d'),
           'COL_QSLRDATE' => date('Y-m-d'),
           'COL_QSL_SENT' => $this->input->post('qsl_sent'),
           'COL_QSL_RCVD' => $this->input->post('qsl_recv'),
           'COL_QSL_SENT_VIA' => $this->input->post('qsl_sent_method'),
           'COL_QSL_RCVD_VIA' => $this->input->post('qsl_recv_method'),
           'COL_IOTA' => $this->input->post('iota_ref'),
           'COL_QTH' => $this->input->post('qth'),
           'COL_FREQ_RX' => '0',
		);

		$this->db->where('COL_PRIMARY_KEY', $this->input->post('id'));
		$this->db->update($this->config->item('table_name'), $data); 
	
	}

	/* Return last 10 QSOs */
	function last_ten() {
		$this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit(10);
		
		return $this->db->get($this->config->item('table_name'));
	}
	
	function last_custom($num) {
		$this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit($num);
		
		return $this->db->get($this->config->item('table_name'));
	}
	
    /* Callsign QRA */
    
    function call_qra($callsign) {
        $this->db->select('COL_CALL, COL_GRIDSQUARE, COL_TIME_ON');
        $this->db->where('COL_CALL', $callsign);
        $where = "COL_GRIDSQUARE != \"\"";
    
        $this->db->where($where);
    
        $this->db->order_by("COL_TIME_ON", "desc"); 
        $this->db->limit(1);
        $query = $this->db->get($this->config->item('table_name'));
        $callsign = "";
        if ($query->num_rows() > 0)
        {
            $data = $query->row(); 
            $callsign = strtoupper($data->COL_GRIDSQUARE);
        }
    
            return $callsign;
    }
    
    function call_name($callsign) {
        $this->db->select('COL_CALL, COL_NAME, COL_TIME_ON');
        $this->db->where('COL_CALL', $callsign);
        $where = "COL_NAME != \"\"";
    
        $this->db->where($where);
    
        $this->db->order_by("COL_TIME_ON", "desc"); 
        $this->db->limit(1);
        $query = $this->db->get($this->config->item('table_name'));
        $name = "";
        if ($query->num_rows() > 0)
        {
            $data = $query->row(); 
            $name = $data->COL_NAME;
        } else {
            //$json = file_get_contents("http://callbytxt.org/db/".$callsign.".json");

            //$obj = json_decode($json);
            //$uppercase_name = strtolower($obj->{'calls'}->{'first_name'});
           // $name = ucwords($uppercase_name);
        }

        return $name;
    }
    
	/* Return QSO Info */
	function qso_info($id) {
		$this->db->where('COL_PRIMARY_KEY', $id); 
		
		return $this->db->get($this->config->item('table_name'));
	}
	

  function get_qsos($num, $offset) {
  $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME, COL_GRIDSQUARE');
  $this->db->order_by("COL_TIME_ON", "desc"); 
	$query = $this->db->get($this->config->item('table_name'), $num, $offset);	
	return $query;
  }
  
	function get_last_qsos($num) {
		$this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
		$this->db->order_by("COL_TIME_ON", "desc"); 
		$this->db->limit($num);
		$query = $this->db->get($this->config->item('table_name'));
		
		return $query;
	}

    /* Get All QSOs with a Valid Grid */
    function kml_get_all_qsos() {
        $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME, COL_GRIDSQUARE');
        $this->db->where('COL_GRIDSQUARE != \'null\'');
        $query = $this->db->get($this->config->item('table_name'));
        
        return $query;
    }

    function get_date_qsos($date) {
        $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
        $this->db->order_by("COL_TIME_ON", "desc"); 
        $start = $date." 00:00:00";
        $end = $date." 23:59:59";

        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $query = $this->db->get($this->config->item('table_name'));
        
        return $query;
    }
	
	function get_todays_qsos() {

		$morning = date('Y-m-d 00:00:00');
		$night = date('Y-m-d 23:59:59');
		$query = $this->db->query('SELECT * FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

		return $query;
	}
  
    /* Return total number of qsos */
     function total_qsos() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
   
    /* Return number of QSOs had today */
    function todays_qsos() {
    
        $morning = date('Y-m-d 00:00:00');
        $night = date('Y-m-d 23:59:59');
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');
        
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
    /* Return QSOs over a period of days */
    function map_week_qsos($start, $end) {
    
        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $this->db->order_by("COL_TIME_ON", "ASC"); 
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    /* Returns QSOs for the date sent eg 2011-09-30 */
    function map_day($date) {
    
        $start = $date." 00:00:00";
        $end = $date." 23:59:59";

        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $this->db->order_by("COL_TIME_ON", "ASC"); 
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }
    
    // Return QSOs made during the current month
    function month_qsos() {

        $morning = date('Y-m-01 00:00:00');
        $night = date('Y-m-30 23:59:59');
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
    /* Return QSOs made during the current Year */
    function year_qsos() {

        $morning = date('Y-01-01 00:00:00');
        $night = date('Y-12-31 23:59:59');
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
    /* Return total amount of SSB QSOs logged */
    function total_ssb() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE = \'SSB\' OR COL_MODE = \'LSB\' OR COL_MODE = \'USB\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

   /* Return total number of satellite QSOs */
   function total_sat() {
        $query = $this->db->query('SELECT COL_SAT_NAME, COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_SAT_NAME != \'null\' GROUP BY COL_SAT_NAME');

        return $query;
    }
    
    /* Return total number of CW QSOs */
    function total_cw() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE = \'CW\' ');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
    /* Return total number of FM QSOs */
    function total_fm() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE = \'FM\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return total number of Digital QSOs */
    function total_digi() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE != \'SSB\' AND COL_MODE != \'LSB\' AND COL_MODE != \'USB\' AND COL_MODE != \'CW\' AND COL_MODE != \'FM\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
    /* Return total number of QSOs per band */
   function total_bands() {
        $query = $this->db->query('SELECT DISTINCT (COL_BAND) AS band, count( * ) AS count FROM '.$this->config->item('table_name').' GROUP BY band ORDER BY count DESC');

        return $query;
    }
    
    /* Return total number of QSL Cards sent */
    function total_qsl_sent() {
        $query = $this->db->query('SELECT DISTINCT (COL_QSL_SENT) AS band, count(COL_QSL_SENT) AS count FROM '.$this->config->item('table_name').' WHERE COL_QSL_SENT = "Y" GROUP BY band');

        $row = $query->row();
        
        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
    }
    
    /* Return total number of QSL Cards requested */
    function total_qsl_requested() {
        $query = $this->db->query('SELECT DISTINCT (COL_QSL_SENT) AS band, count(COL_QSL_SENT) AS count FROM '.$this->config->item('table_name').' WHERE COL_QSL_SENT = "R" GROUP BY band');

        $row = $query->row();

        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
    }
    
    /* Return total number of QSL Cards received */
    function total_qsl_recv() {
        $query = $this->db->query('SELECT DISTINCT (COL_QSL_RCVD) AS band, count(COL_QSL_RCVD) AS count FROM '.$this->config->item('table_name').' WHERE COL_QSL_RCVD = "Y" GROUP BY band');

        $row = $query->row();

        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
    }
    
    /* Return total number of countrys worked */
    function total_countrys() {
        $query = $this->db->query('SELECT DISTINCT (COL_COUNTRY) FROM '.$this->config->item('table_name').'');

        return $query->num_rows();
    }

	function api_search_query($query) {
		$time_start = microtime(true);
		$results = $this->db->query($query);
    if(!$results) {
      return array('query' => $query, 'error' => $this->db->_error_number(), 'time' => 0);
    }
		$time_end = microtime(true);
		$time = round($time_end - $time_start, 4);

		return array('query' => $query, 'results' => $results, 'time' => $time);
	}

	function api_insert_query($query) {
		$time_start = microtime(true);
		$results = $this->db->insert($this->config->item('table_name'), $query);
    if(!$results) {
      return array('query' => $query, 'error' => $this->db->_error_number(), 'time' => 0);
    }
		$time_end = microtime(true);
		$time = round($time_end - $time_start, 4);

		return array('query' => $this->db->queries[2], 'result_string' => $results, 'time' => $time);
	}

    /* Delete QSO based on the QSO ID */
    function delete($id) {
        $this->db->where('COL_PRIMARY_KEY', $id);
        $this->db->delete($this->config->item('table_name')); 
    }

    function import($record) {
        // Join date+time
        //$datetime = date('Y-m-d') ." ". $this->input->post('start_time');
        //$myDate = date('Y-m-d', $record['qso_date']);
        $time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));

        if (isset($record['time_off'])) {
            $time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_off']));
        } else {
           $time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));  
        }
        
    
        if(isset($record['freq'])) {
            $cleansedstring = preg_replace('#\W#', '', $record['freq']);
            $freq = $cleansedstring."000";
        } else {
            $freq = "0";
        }
        if(isset($record['name'])) {
            $name = $record['name'];
        } else {
            $name = "";
        }
        if(isset($record['NOTES'])) {
            $comment = $record['NOTES'];
        } else {
            $comment = "";
        }

        if(isset($record['sat_name'])) {
            $sat_name = $record['sat_name'];
        } else {
            $sat_name = "";
        }
    
        if(isset($record['sat_mode'])) {
            $sat_mode = $record['sat_mode'];
        } else {
            $sat_mode = "";
        }

        if(isset($record['gridsquare'])) {
            $gridsquare = $record['gridsquare'];
        } else {
            $gridsquare = "";
        }
    
        if(isset($record['country'])) {
            $country = $record['country'];
        } else {
            $this->load->model('dxcc');

            $dxccinfo = $this->dxcc->info($record['call']); 

            if ($dxccinfo->num_rows() > 0)
            {
                foreach ($dxccinfo->result() as $row1)
                {
                    $country = ucfirst(strtolower($row1->name));
                }
            } else {
                $country = "";
            }
        }

        if(isset($record['qth'])) {
            $qth = $record['qth'];
        } else {
            $qth = "";
        }

        if(isset($record['prop_mode'])) {
            $prop_mode = $record['prop_mode'];
        } else {
            $prop_mode = "";
        }


        if(isset($record['rst_rcvd'])) {
                $rst_rx = $record['rst_rcvd'];
        } else {
                $rst_rx = "59"  ;
        }
        
          if(isset($record['rst_sent'])) {
                $rst_tx = $record['rst_sent'];
        } else {
                $rst_tx = "59"  ;
        }


        $this->db->where('COL_CALL', $record['call']);
        $this->db->where('COL_TIME_ON', $time_on);
        $check = $this->db->get($this->config->item('table_name'));

        if ($check->num_rows() <= 0)
        {
            // Create array with QSO Data
            $data = array(
               'COL_TIME_ON' => $time_on,
               'COL_TIME_OFF' => $time_off,
               'COL_CALL' => strtoupper($record['call']),
               'COL_BAND' => $record['band'],
               'COL_FREQ' => $freq,
               'COL_MODE' => $record['mode'],
               'COL_RST_RCVD' => $rst_rx,
               'COL_RST_SENT' => $rst_tx,
               'COL_NAME' => $name,
               'COL_COMMENT' => $comment,
               'COL_SAT_NAME' => $sat_name,
               'COL_SAT_MODE' => $sat_mode,
               'COL_GRIDSQUARE' => $gridsquare,
               'COL_COUNTRY' => $country,
               'COL_QTH' =>$qth,
               'COL_PROP_MODE' => $prop_mode,
               'COL_DISTANCE' => 0,
               'COL_FREQ_RX' => 0,
               'COL_BAND_RX' => 0,
               'COL_ANT_AZ' => 0,
               'COL_ANT_EL' => 0,
            );

            $this->add_qso($data);
        }
    }

}

?>
