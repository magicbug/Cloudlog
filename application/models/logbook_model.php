<?php

class Logbook_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

  function get_qsos($num, $offset) {
  $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY');
  $this->db->order_by("COL_TIME_ON", "desc"); 
	$query = $this->db->get('table_hrd_contacts_v01', $num, $offset);	
	return $query;
  }
}

?>