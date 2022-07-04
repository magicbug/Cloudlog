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

}

?>