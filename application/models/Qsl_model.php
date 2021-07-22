<?php
class Qsl_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }

    function getQsoWithQslList() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $this->db->select('*');
        $this->db->from($this->config->item('table_name'));
        $this->db->join('qsl_images', 'qsl_images.qsoid = ' . $this->config->item('table_name') . '.col_primary_key');
        $this->db->where('station_id', $station_id);

        return $this->db->get();
    }

    function getQslForQsoId($id) {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        $this->db->select('*');
        $this->db->from('qsl_images');
        $this->db->where('qsoid', $clean_id);

        return $this->db->get()->result();
    }

    function saveQsl($qsoid, $filename) {
        $data = array(
            'qsoid' => $qsoid,
            'filename' => $filename
        );

        $this->db->insert('qsl_images', $data);

        return $this->db->insert_id();
    }

    function deleteQsl($id) {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        // Delete Mode
        $this->db->delete('qsl_images', array('id' => $clean_id));
    }

    function getFilename($id) {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        $this->db->select('filename');
        $this->db->from('qsl_images');
        $this->db->where('id', $clean_id);

        return $this->db->get();
    }

	function searchQsos($callsign) {
		$CI =& get_instance();
		$CI->load->model('Stations');
		$station_id = $CI->Stations->find_active();

		$this->db->select('*');
		$this->db->from($this->config->item('table_name'));
		$this->db->where('station_id', $station_id);
		$this->db->where('col_call', $callsign);

		return $this->db->get();
	}

	function addQsotoQsl($qsoid, $filename) {
		$clean_qsoid = $this->security->xss_clean($qsoid);
		$clean_filename = $this->security->xss_clean($filename);

		$data = array(
			'qsoid' => $qsoid,
			'filename' => $filename
		);

		$this->db->insert('qsl_images', $data);

		return $this->db->insert_id();
	}
}
