<?php

class adif_data extends CI_Model {

    function export_all() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();
        $this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
        $this->db->order_by("COL_TIME_ON", "ASC");
        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    function export_printrequested($station_id = NULL) {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

		if ($station_id == NULL) {
			$this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
		} else if ($station_id != 'All') {
			$this->db->where($this->config->item('table_name').'.station_id', $station_id);
		}

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
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

        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
        $this->db->where($this->config->item('table_name').'.COL_PROP_MODE', 'SAT');

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');

        return $this->db->get();
    }

    function satellte_lotw() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
        $this->db->where($this->config->item('table_name').'.COL_PROP_MODE', 'SAT');

        $where = $this->config->item('table_name').".COL_LOTW_QSLRDATE != ''";
        $this->db->where($where);

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");


        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');

        return $this->db->get();
    }

    function export_custom($from, $to, $station_id, $exportLotw = false) {
        // be sure that station belongs to user
        $CI =& get_instance();
        $CI->load->model('Stations');
        if (!$CI->Stations->check_station_is_accessible($station_id)) {
            return;
        }

        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $station_id);

        // If date is set, we format the date and add it to the where-statement
        if ($from != 0) {
            $from = DateTime::createFromFormat('d/m/Y', $from);
            $from = $from->format('Y-m-d');
            $this->db->where("date(".$this->config->item('table_name').".COL_TIME_ON) >= '".$from."'");
        }
        if ($to != 0) {
            $to = DateTime::createFromFormat('d/m/Y', $to);
            $to = $to->format('Y-m-d');
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

        return $this->db->get();
    }

    function export_lotw() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();


        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
        $this->db->group_start();
        $this->db->where($this->config->item('table_name').".COL_LOTW_QSL_SENT != 'Y'");
        $this->db->or_where($this->config->item('table_name').".COL_LOTW_QSL_SENT", NULL);
        $this->db->group_end();

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');

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

		$this->db->select(''.$this->config->item('table_name').'.*, station_profile.*');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in($this->config->item('table_name').'.station_id', $logbooks_locations_array);
		$this->db->where($this->config->item('table_name').'.COL_SIG', $type);

		$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');

		return $this->db->get();
	}
}

?>
