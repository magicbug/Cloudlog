<?php
class Qsl_model extends CI_Model {
    function getQsoWithQslList() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->db->select('*, qsl_images.id as qsl_id');
        $this->db->from($this->config->item('table_name'));
        $this->db->join('qsl_images', 'qsl_images.qsoid = ' . $this->config->item('table_name') . '.col_primary_key');
		$this->db->join('files', 'files.id = qsl_images.file_id', 'left');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->order_by("id", "desc");

        return $this->db->get();
    }

    function getQslForQsoId($qsoid) {
        // Clean ID
        $clean_id = $this->security->xss_clean($qsoid);

        // be sure that QSO belongs to user
        $CI =& get_instance();
        $CI->load->model('logbook_model');
        if (!$CI->logbook_model->check_qso_is_accessible($clean_id)) {
            return;
        }

        $this->db->select('*, qsl_images.id as qsl_id');
        $this->db->from('qsl_images');
		$this->db->join('files', 'files.id = qsl_images.file_id', 'left');
        $this->db->where('qsoid', $clean_id);

        return $this->db->get()->result();
    }

    function saveQsl($qsoid, $file_id) {
        $clean_id = $this->security->xss_clean($qsoid);
        $clean_file_id = $this->security->xss_clean($file_id);

        // be sure that QSO belongs to user
        $CI =& get_instance();
        $CI->load->model('logbook_model');
        if (!$CI->logbook_model->check_qso_is_accessible($clean_id)) {
            return;
        }

        $data = array(
            'qsoid' => $clean_id,
            'file_id' => $clean_file_id
        );

        $this->db->insert('qsl_images', $data);

        return $this->db->insert_id();
    }

    function deleteQsl($qsl_id) {
        // Clean ID
        $clean_id = $this->security->xss_clean($qsl_id);

        // be sure that QSO belongs to user
        $CI =& get_instance();
        $CI->load->model('logbook_model');
        if (!$CI->logbook_model->check_qso_is_accessible($clean_id)) {
            //return;
        }

        // Delete Mode
        $this->db->delete('qsl_images', array('id' => $clean_id));
    }

    function getFileId($qsl_id) {
        // Clean ID
        $clean_id = $this->security->xss_clean($qsl_id);

        // be sure that QSO belongs to user
        $CI =& get_instance();
        $CI->load->model('logbook_model');
        if (!$CI->logbook_model->check_qso_is_accessible($clean_id)) {
            //return;
        }

        $this->db->select('file_id');
        $this->db->from('qsl_images');
        $this->db->where('id', $clean_id);

        return $this->db->get();
    }

    function searchQsos($callsign) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$this->db->select('*');
		$this->db->from($this->config->item('table_name'));
		$this->db->where_in('station_id', $logbooks_locations_array);
		$this->db->where('col_call', $callsign);

		return $this->db->get();
	}

	function addQsotoQsl($qsoid, $file_id) {
		$clean_qsoid = $this->security->xss_clean($qsoid);
		$clean_file_id = $this->security->xss_clean($file_id);

		// be sure that QSO belongs to user
		$CI =& get_instance();
		$CI->load->model('logbook_model');
		if (!$CI->logbook_model->check_qso_is_accessible($clean_qsoid)) {
			return;
		}

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
