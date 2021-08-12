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

    function add() {
		// Create data array with field values
		$data = array(
			'user_id' => $this->session->userdata('user_id'),
			'logbook_name' =>  xss_clean($this->input->post('stationLogbook_Name', true)),
		);

		// Insert Records
		$this->db->insert('station_logbooks', $data); 
	}

    function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		// Delete QSOs
        $this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', $id);
		$this->db->delete('station_logbooks'); 
	}

    function edit() {
		$data = array(
			'logbook_name' => xss_clean($this->input->post('station_logbook_name', true)),
		);

        $this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', xss_clean($this->input->post('logbook_id', true)));
		$this->db->update('station_logbooks', $data); 
	}

    function logbook($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

        $this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', $clean_id);
		return $this->db->get('station_logbooks');
	}
}
?>