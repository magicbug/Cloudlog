<?php

// Uses 'phpass' from http://www.openwall.com/phpass/ to implement password hashing
require_once('application/third_party/PasswordHash.php');

class User_Model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	// Retrieve a user
	function get($username) {
		$this->db->where('user_name', $username);
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	function get_by_id($id) {
		$this->db->where('user_id', $id);
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	function exists($username) {
		if($this->get($username)->results()->num_rows == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	function add($username, $password, $email, $type) {
		if(!$this->exists($username)) {
			$data = array(
				'user_name' => $username,
				'user_password' => $this->_hash($password),
				'user_email' => $email,
				'user_type' => $type
			);

			$this->db->insert($this->config->item('auth_table'));
			return 1;
		} else {
			return 0;
		}
	}

	function edit() {
		
		$data = array(
			'user_name' => $this->input->post('user_name'),
			'user_email' => $this->input->post('user_email'),
			'user_type' => $this->input->post('user_type')
		);

		if($this->input->post('user_password') != NULL)
		{
			$data['user_password'] = $this->_hash($this->input->post('user_password'));
		}

		$this->db->where('user_id', $this->input->post('id'));
		$this->db->update($this->config->item('auth_table'), $data);

	}

	function login() {
		
		$username = $this->input->post('user_name');
		$password = $this->input->post('user_password');

		return $this->authenticate($username, $password);
	}

	function clear_session() {
	
		$this->session->unset_userdata(array('user_id' => '', 'user_type' => '', 'user_email' => '', 'user_hash' => ''));

	}
		
	function update_session($id) {
		
		$u = $this->get_by_id($id);

		$userdata = array(
			'user_id'		=> $u->row()->user_id,
			'user_name'		=> $u->row()->user_name,
			'user_type'		=> $u->row()->user_type,
			'user_hash'		=> $this->_hash($u->row()->user_id."-".$u->row()->user_type)
		);

		$this->session->set_userdata($userdata);
	}

	function validate_session() {

		if($this->session->userdata('user_id'))
		{
			$user_id = $this->session->userdata('user_id');
			$user_type = $this->session->userdata('user_type');
			$user_hash = $this->session->userdata('user_hash');

			if($this->_auth($user_id."-".$user_type, $user_hash)) {
				return 1;
			} else {
				$this->clear_session();
				return 0;
			}
		} else {
			return 0;
		}
	}

	function authenticate($username, $password) {
		$u = $this->get($username);
		if($u->num_rows != 0)
		{
			if($this->_auth($password, $u->row()->user_password)) {
				return 1;
			}
		}
		return 0;
	}

	function authorize($level) {
		$u = $this->get_by_id($this->session->userdata('user_id'));
		if(($this->validate_session) && ($u->row()->user_type >= $level)) {
			return 1;
		} else {
			return 0;
		}
	}

	function set($username, $data) {
		$this->db->where('user_name', $username);
		$this->db->update($this->config->item('auth_table', $data));
		return 1;
	}

	function users() {
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	private function _auth($password, $hash) {
		$h = new PasswordHash(8, FALSE);
		if($h->CheckPassword($password, $hash)) {
			return 1;
		} else {
			return 0;
		}
	}

	private function _hash($password) {
		$h = new PasswordHash(8, FALSE);
		$hash = $h->HashPassword($password);
		unset($h);

		if(strlen($hash) < 20) {
			return 0;
		} else {
			return $hash;
		}
	}
		
}

?>
