<?php

class adif_data extends CI_Model {

    function export_all($api_key = null) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        if ($api_key != null) {
            $CI->load->model('api_model');
            if (strpos($this->api_model->access($api_key), 'r') !== false) {
                $this->api_model->update_last_used($api_key);
                $user_id = $this->api_model->key_userid($api_key);
                $logbooks_locations_array = $this->list_station_locations($user_id);
            }
        } else {
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        }

        $this->db->select($this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');
        $this->db->order_by("COL_TIME_ON", "ASC");
        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->where_in('station_profile.station_id', $logbooks_locations_array);
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif');
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    function list_station_locations($user_id) {
        $this->db->where('user_id', $user_id);
		$query = $this->db->get('station_profile');
		
		if ($query->num_rows() == 0) {
            return array();
		}
        
        $locations_array = array();
        foreach ($query->result() as $row) {
            array_push($locations_array, $row->station_id);
        }

        return $locations_array;
	}

    function export_printrequested($station_id = NULL) {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->select($this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');

		if ($station_id == NULL) {
			$this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
		} else if ($station_id != 'All') {
			$this->db->where($this->config->item('table_name').'.station_id', $station_id);
		}

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif');
        // always filter user. this ensures that even if the station_id is from another user no inaccesible QSOs will be returned
        $this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
        $this->db->where_in('COL_QSL_SENT', array('R', 'Q'));
        $this->db->order_by("COL_TIME_ON", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    function sat_all() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
        $this->db->where($this->config->item('table_name').'.COL_PROP_MODE', 'SAT');

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif', 'left outer');

        return $this->db->get();
    }

    function satellte_lotw() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
        $this->db->where($this->config->item('table_name').'.COL_PROP_MODE', 'SAT');

        $where = $this->config->item('table_name').".COL_LOTW_QSLRDATE IS NOT NULL";
        $this->db->where($where);

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");


        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif', 'left outer');

        return $this->db->get();
    }

    function export_custom($from, $to, $station_id, $exportLotw = false) {
        // be sure that station belongs to user
        $CI =& get_instance();
        $CI->load->model('Stations');
        if (!$CI->Stations->check_station_is_accessible($station_id)) {
            return;
        }

        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $station_id);

        // If date is set, we format the date and add it to the where-statement
        if ($from) {
            $this->db->where("date(".$this->config->item('table_name').".COL_TIME_ON) >= '".$from."'");
        }
        if ($to) {
            $this->db->where("date(".$this->config->item('table_name').".COL_TIME_ON) <= '".$to."'");
        }
        if ($exportLotw) {
            $this->db->group_start();
            $this->db->where($this->config->item('table_name').".COL_LOTW_QSL_SENT != 'Y'");
            $this->db->or_where($this->config->item('table_name').".COL_LOTW_QSL_SENT", NULL);
            $this->db->group_end();
        }

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif', 'left outer');

        return $this->db->get();
    }

    function export_lotw() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();


        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
        $this->db->group_start();
        $this->db->where($this->config->item('table_name').".COL_LOTW_QSL_SENT != 'Y'");
        $this->db->or_where($this->config->item('table_name').".COL_LOTW_QSL_SENT", NULL);
        $this->db->group_end();

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif', 'left outer');

        return $this->db->get();
    }

    function mark_lotw_sent($id) {
       $data = array(
       		'COL_LOTW_QSL_SENT' => 'Y'
    	  );

		$this->db->set('COL_LOTW_QSLSDATE', 'now()', FALSE);
    	$this->db->where('COL_PRIMARY_KEY', $id);
    	$this->db->update($this->config->item('table_name'), $data);
    }

	function sig_all($type) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$this->db->select(''.$this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in($this->config->item('table_name').'.station_id', $logbooks_locations_array);
		$this->db->where($this->config->item('table_name').'.COL_SIG', $type);

		$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif', 'left outer');

		return $this->db->get();
	}
}

?>
