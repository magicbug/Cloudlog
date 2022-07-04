<?php

class Eqslmethods_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function mark_all_as_sent() {
		$data = array(
            'COL_EQSL_QSL_SENT' => 'Y',
            'COL_EQSL_QSLSDATE'  => date('Y-m-d')." 00:00:00",
        );
        
        $this->db->update($this->config->item('table_name'), $data);
	}

}

?>