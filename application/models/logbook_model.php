<?php

class Logbook_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
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
		$query = $this->db->query('SELECT * FROM TABLE_HRD_CONTACTS_V01 WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

		return $query;
	}
  
     function total_qsos() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01');

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
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01 WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');
        
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
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01 WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

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
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01 WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
    
    function total_ssb() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01 WHERE COL_MODE = \'ssb\' or COL_MODE = \'lsb\' or COL_MODE = \'usb\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    function total_cw() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01 WHERE COL_MODE = \'cw\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
    function total_fm() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01 WHERE COL_MODE = \'fm\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    function total_digi() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM TABLE_HRD_CONTACTS_V01 WHERE COL_MODE = \'psk31\' or COL_MODE = \'rtty\' or COL_MODE = \'psk63\' or COL_MODE = \'psk125\' or COL_MODE = \'JT65\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }
    
   function total_bands() {
        $query = $this->db->query('SELECT DISTINCT (COL_BAND) AS band, count( * ) AS count FROM TABLE_HRD_CONTACTS_V01 GROUP BY band ORDER BY count DESC');

        return $query;
    }

}

?>