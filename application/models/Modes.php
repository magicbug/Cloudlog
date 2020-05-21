<?php

class Modes extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	function all() {
		return $this->db->get('adif_modes');
	}
	
	function active() {
		$this->db->where('active', 1);
		return $this->db->get('adif_modes');
	}

	function mode($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);


		$this->db->where('id', $clean_id);
		return $this->db->get('adif_modes');
	}


	function add() {
		$data = array(
			'mode' => xss_clean($this->input->post('mode', true)),
			'qrgmode' =>  xss_clean(strtoupper($this->input->post('qrgmode', true))),
			'active' =>  xss_clean($this->input->post('active', true)),
		);

		$this->db->insert('adif_modes', $data); 
	}

	function edit() {
		$data = array(
			'mode' => xss_clean($this->input->post('mode', true)),
			'qrgmode' =>  xss_clean(strtoupper($this->input->post('qrgmode', true))),
			'active' =>  xss_clean($this->input->post('active', true)),
		);

		$this->db->where('id', xss_clean($this->input->post('id', true)));
		$this->db->update('adif_modes', $data); 
	}

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		// Delete Mode
		$this->db->delete('adif_modes', array('id' => $clean_id)); 
	}

}

?>