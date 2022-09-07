<?php

class Sig extends CI_Model {

	function get_all($type) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }

		$this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('sig');

		$this->db->where_in("station_id", $logbooks_locations_array);
		$this->db->where_in("col_band", $bandslots);
		$this->db->order_by("COL_SIG_INFO", "ASC");
		$this->db->where('COL_SIG =', $type);

		return $this->db->get($this->config->item('table_name'));
	}

    function get_all_sig_types() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
            return null;
        }

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$this->load->model('bands');

		$bandslots = $this->bands->get_worked_bands('sig');

		$bandslots_list = "'".implode("','",$bandslots)."'";

        $sql = "select col_sig, count(*) qsos, count(distinct col_sig_info) refs from " . $this->config->item('table_name') .
                " where col_sig <> ''" .
                " and station_id in (" . $location_list . ")" .
				" and col_band in (" . $bandslots_list . ")" .
                " group by col_sig";

        $query = $this->db->query($sql);

        return $query->result();
    }


}

?>
