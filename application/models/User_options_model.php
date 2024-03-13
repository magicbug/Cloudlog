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

	public function set_option_at_all_users($option_type, $option_name, $option_array) {
		$query = $this->db->select('user_id')->get('users');
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$user_id = $row->user_id;
				$sql = 'INSERT INTO user_options (user_id, option_type, option_name, option_key, option_value) VALUES (?, ?, ?, ?, ?)
						ON DUPLICATE KEY UPDATE option_value = ?';
				foreach ($option_array as $option_key => $option_value) {
					$this->db->query($sql, array($user_id, $option_type, $option_name, $option_key, $option_value, $option_value));
				}
			}
			return true;
		} else {
			log_message('error','set_option_at_all_users() failed because users table is empty');
		}
	}	

	public function get_options($option_type, $option_array=null, $uid=null) {
		if ($uid ?? '' == '') {
			$uid=$this->session->userdata('user_id');
		}
		$sql_more = "";
		$array_sql_value = array($uid, $option_type);
		if (is_array($option_array)) {
			foreach ($option_array as $key => $value) {
				$sql_more .= ' and '.$key.'=?';
				$array_sql_value[] = $value;
			}
		}
		$sql='select option_name,option_key,option_value from user_options where user_id=? and option_type=?'.$sql_more;
		return $this->db->query($sql, $array_sql_value);
	}

	public function del_option($option_type, $option_name, $option_array=null) {
		$uid=$this->session->userdata('user_id');
		$sql_more = "";
		$array_sql_value = array($uid, $option_type, $option_name);
		if (is_array($option_array)) {
			foreach ($option_array as $key => $value) {
				$sql_more .= ' and '.$key.'=?';
				$array_sql_value[] = $value;
			}
		}		
		$sql='delete from user_options where user_id=? and option_type=? and option_name=?'.$sql_more;
		return $this->db->query($sql, $array_sql_value);
	}

}

?>
