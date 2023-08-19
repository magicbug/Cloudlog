<?php

class User_options_model extends CI_Model {

	public function options($option_type) {
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('option_type', $option_type);
		return $this->db->get('user_options');
	}

	public function set_option($option_type, $option_name, $option_array) {
		$uid=$this->session->userdata('user_id');
		$sql='insert into user_options (user_id,option_type,option_name,option_key,option_value) values (?,?,?,?,?) ON DUPLICATE KEY UPDATE option_value=?';
		foreach($option_array as $option_key => $option_value) { 
			$query = $this->db->query($sql, array($uid, $option_type, $option_name, $option_key, $option_value, $option_value));
		}
	}

	public function get_options($option_type) {
		$uid=$this->session->userdata('user_id');
		$sql='select option_name,option_key,option_value from user_options where user_id=? and option_type=?';
		return $this->db->query($sql, array($uid, $option_type));
	}

	public function del_option($option_type, $option_name) {
		$uid=$this->session->userdata('user_id');
		$sql='delete from user_options where user_id=? and option_type=? and option_name=?';
		return $this->db->query($sql, array($uid, $option_type,$option_name));
	}

}

?>
