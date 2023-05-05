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

    function get_eqsl_users() {
        $this->db->select('user_eqsl_name, user_eqsl_password, user_id');
        $this->db->where('coalesce(user_eqsl_name, "") != ""');
        $this->db->where('coalesce(user_eqsl_password, "") != ""');
        $query = $this->db->get($this->config->item('auth_table'));
        return $query->result();
    }


    // Show all QSOs we need to send to eQSL
    function eqsl_not_yet_sent($userid = null) {
        $CI =& get_instance();
        if ($userid == null) {
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $CI->load->model('Stations');
            $stations = $CI->Stations->all_of_user();
            $logbooks_locations_array = array();
            foreach ($stations->result() as $row) {
                array_push($logbooks_locations_array, $row->station_id);
            }
        }
    
        $this->db->select('station_profile.*, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_COMMENT, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_PROP_MODE, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_SAT_MODE, '.$this->config->item('table_name').'.COL_QSLMSG');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->where("coalesce(station_profile.eqslqthnickname, '') <> ''");
        $this->db->where($this->config->item('table_name').'.COL_CALL !=', '');
        $this->db->group_start();
        $this->db->where($this->config->item('table_name').'.COL_EQSL_QSL_SENT is null');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', '');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'R');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'Q');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'N');
        $this->db->group_end();
        $this->db->where_in('station_profile.station_id', $logbooks_locations_array);
    
        return $this->db->get();
    }

    // Mark the QSO as sent to eQSL
    function eqsl_mark_sent($primarykey) {
        $data = array(
            'COL_EQSL_QSLSDATE' => date('Y-m-d H:i:s'), // eQSL doesn't give us a date, so let's use current
            'COL_EQSL_QSL_SENT' => 'Y',
        );

        $this->db->where('COL_PRIMARY_KEY', $primarykey);

        $this->db->update($this->config->item('table_name'), $data);

        return "eQSL Sent";
    }

}

?>