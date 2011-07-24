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
	
		// Create array with QSO Data
		$data = array(
		   'COL_TIME_ON' => $datetime,
		   'COL_TIME_OFF' => $datetime,
		   'COL_CALL' => strtoupper($this->input->post('callsign')),
		   'COL_BAND' => $this->input->post('band'),
		   'COL_FREQ' => $this->input->post('freq'),
		   'COL_MODE' => $this->input->post('mode'),
		   'COL_RST_RCVD' => $this->input->post('rst_recv'),
		   'COL_RST_SENT' => $this->input->post('rst_sent'),
           'COL_NAME' => $this->input->post('name'),
		   'COL_COMMENT' => $this->input->post('comment'),
		   'COL_SAT_NAME' => $this->input->post('sat_name'),
		   'COL_SAT_MODE' => $this->input->post('sat_mode'),
		   'COL_GRIDSQUARE' => $this->input->post('locator'),
		   'COL_COUNTRY' => $this->input->post('country'),
		   'COL_MY_RIG' => $this->input->post('equipment'),
		);

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
		   'COL_COMMENT' => $this->input->post('comment'),
		   'COL_NAME' => $this->input->post('name'),
		   'COL_SAT_NAME' => $this->input->post('sat_name'),
		   'COL_SAT_MODE' => $this->input->post('sat_mode'),
		);

		$this->db->where('COL_PRIMARY_KEY', $this->input->post('id'));
		$this->db->update($this->config->item('table_name'), $data); 
	
	}

	/* Return last 10 QSOs */
	function last_ten() {
		$this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY');
		$this->db->order_by("COL_TIME_ON", "desc"); 
		$this->db->limit(10);
		
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
  $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY');
  $this->db->order_by("COL_TIME_ON", "desc"); 
	$query = $this->db->get($this->config->item('table_name'), $num, $offset);	
	return $query;
  }
  
	function get_last_qsos($num) {
		$this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY');
		$this->db->order_by("COL_TIME_ON", "desc"); 
		$this->db->limit($num);
		$query = $this->db->get($this->config->item('table_name'));
		
		return $query;
	}
	
	function get_todays_qsos() {

		$morning = date('Y-m-d 00:00:00');
		$night = date('Y-m-d 23:59:59');
		$query = $this->db->query('SELECT * FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

		return $query;
	}
  
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
    
    
    function total_ssb() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE = \'SSB\' or COL_MODE = \'LSB\' or COL_MODE = \'USB\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
   function total_sat() {
        $query = $this->db->query('SELECT COL_SAT_NAME, COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_SAT_NAME != \'null\' GROUP BY COL_SAT_NAME');

        return $query;
    }
    

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

    function total_digi() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE != \'SSB\' or COL_MODE != \'LSB\' or COL_MODE = \'USB\' or COL_MODE = \'CW\' or COL_MODE = \'FM\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
   function total_bands() {
        $query = $this->db->query('SELECT DISTINCT (COL_BAND) AS band, count( * ) AS count FROM '.$this->config->item('table_name').' GROUP BY band ORDER BY count DESC');

        return $query;
    }

}

?>