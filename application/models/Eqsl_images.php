<?php

class Eqsl_images extends CI_Model {

	function get_image($qso_id) {
		$this->db->where('qso_id', $qso_id);
		$query = $this->db->get('eQSL_images'); 
		
		$row = $query->row();

		if(isset($row)) {
			return $row->image_file;
		} else {
			return "No Image";
		}
	}

	function save_image($qso_id, $image_name) {
		$data = array(
		        'qso_id' => $qso_id,
		        'image_file' => $image_name,
		);

		$this->db->insert('eQSL_images', $data);
	}

	function eqsl_qso_list() {
		$this->db->select('qso_id, COL_CALL, COL_MODE, , COL_SUBMODE, COL_TIME_ON, COL_BAND, COL_SAT_NAME, image_file');
		$this->db->join($this->config->item('table_name'), 'qso_id = COL_PRIMARY_KEY', 'left outer');
		$this->db->order_by('COL_TIME_ON', 'DESC');
		return $this->db->get('eQSL_images');
	}

}

?>
