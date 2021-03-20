<?php

class Sig extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function get_all($type) {
		$CI =& get_instance();
      	$CI->load->model('Stations');
      	$station_id = $CI->Stations->find_active();

		$this->db->where("station_id", $station_id);
		$this->db->order_by("COL_SIG_INFO", "ASC");
		$this->db->where('COL_SIG =', $type);
		
		return $this->db->get($this->config->item('table_name'));
	}

    function get_all_sig_types() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $sql = "select col_sig, count(*) qsos, count(distinct col_sig_info) refs from " . $this->config->item('table_name') .
                " where col_sig <> ''" .
                " and station_id = " . $station_id .
                " group by col_sig";

        $query = $this->db->query($sql);

        return $query->result();
    }


}

?>