<?php

class adif_data extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function export_all() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->where('station_id', $active_station_id);
        $this->db->order_by("COL_TIME_ON", "ASC"); 
        $query = $this->db->get($this->config->item('table_name'));
        
        return $query;
    }

    function export_printrequested() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->where('station_id', $active_station_id);
        $this->db->where('COL_QSL_SENT', 'R');
        $this->db->order_by("COL_TIME_ON", "ASC"); 
        $query = $this->db->get($this->config->item('table_name'));
        
        return $query;
    }

    function sat_all() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->where('station_id', $active_station_id);
        $this->db->where('COL_PROP_MODE', 'SAT');
        $this->db->order_by("COL_TIME_ON", "ASC"); 
        $query = $this->db->get($this->config->item('table_name'));
        
        return $query;
    }

    function satellte_lotw() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->where('station_id', $active_station_id);
        $this->db->where('COL_PROP_MODE', 'SAT');

        $where = "COL_LOTW_QSLRDATE != ''";
        $this->db->where($where);

        $this->db->order_by("COL_TIME_ON", "ASC"); 
        $query = $this->db->get($this->config->item('table_name'));
        
        return $query;
    }
    
    function export_custom($from, $to) {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();
        $this->db->where('station_id', $active_station_id);

        // If date is set, we format the date and add it to the where-statement
        if ($from != 0) {
            $from = DateTime::createFromFormat('d/m/Y', $from);
            $from = $from->format('Y-m-d');
            $this->db->where("date(COL_TIME_ON) >= '".$from."'");
        }
        if ($to != 0) {
            $to = DateTime::createFromFormat('d/m/Y', $to);
            $to = $to->format('Y-m-d');
            $this->db->where("date(COL_TIME_ON) <= '".$to."'");
        }
        $this->db->order_by("COL_TIME_ON", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }
    
    function export_lotw() {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();
        $this->db->where('station_id', $active_station_id);
        $this->db->where("COL_LOTW_QSL_SENT != 'Y'");
        $this->db->order_by("COL_TIME_ON", "ASC"); 
        $query = $this->db->get($this->config->item('table_name'));
        
        return $query;
    }
    
    function mark_lotw_sent($id) {
       $data = array(
       		'COL_LOTW_QSL_SENT' => 'Y'
    	  );
	
		$this->db->set('COL_LOTW_QSLSDATE', 'now()', FALSE);
    	$this->db->where('COL_PRIMARY_KEY', $id);
    	$this->db->update($this->config->item('table_name'), $data); 
    }
}

?>
