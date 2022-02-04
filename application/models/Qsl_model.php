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

        $this->db->select('*, qsl_images.id as qsl_id');
        $this->db->from($this->config->item('table_name'));
        $this->db->join('qsl_images', 'qsl_images.qsoid = ' . $this->config->item('table_name') . '.col_primary_key');
		$this->db->join('files', 'files.id = qsl_images.file_id', 'left');
        $this->db->where('station_id', $station_id);

        return $this->db->get();
    }

    function getQslForQsoId($id) {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        $this->db->select('*, qsl_images.id as qsl_id');
        $this->db->from('qsl_images');
		$this->db->join('files', 'files.id = qsl_images.file_id', 'left');
        $this->db->where('qsoid', $clean_id);

        return $this->db->get()->result();
    }

    function saveQsl($qsoid, $file_id) {
        $data = array(
            'qsoid' => $qsoid,
            'file_id' => $file_id
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

    function getFileId($id) {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        $this->db->select('file_id');
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

	function addQsotoQsl($qsoid, $file_id) {
		$clean_qsoid = $this->security->xss_clean($qsoid);
		$clean_file_id = $this->security->xss_clean($file_id);

		$data = array(
			'qsoid' => $clean_qsoid,
			'file_id' => $clean_file_id
		);

		$this->db->insert('qsl_images', $data);

		return $this->db->insert_id();
	}

	function getQslFileReference($file_id)
	{
		$this->db->select('count(*) as num');
		$this->db->from('qsl_images');
		$this->db->where('file_id', $file_id);

		return $this->db->get()->result()[0]->num;
	}
}
