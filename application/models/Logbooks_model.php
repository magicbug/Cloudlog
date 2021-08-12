<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logbooks_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


    function show_all() {
        $this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->get('station_logbooks');
	}
}
?>