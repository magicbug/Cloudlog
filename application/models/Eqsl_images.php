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

}

?>