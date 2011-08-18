<?php

/* user_model.php
 *
 * This model implements user authentication and authorization
 *
 */
 

// Uses 'phpass' from http://www.openwall.com/phpass/ to implement password hashing
require_once('application/third_party/PasswordHash.php');

class User_Model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	// FUNCTION: object get($username)
	// Retrieve a user
	function get($username) {
		$this->db->where('user_name', $username);
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	// FUNCTION: object get_by_id($id)
	// Retrieve a user by user ID
	function get_by_id($id) {
		$this->db->where('user_id', $id);
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	// FUNCTION: bool exists($username)
	// Check if a user exists (by username)
	function exists($username) {
		if($this->get($username)->num_rows == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	// FUNCTION: bool add($username, $password, $email, $type)
	// Add a user
	function add($username, $password, $email, $type) {
		if(!$this->exists($username)) {
			$data = array(
				'user_name' => $username,
				'user_password' => $this->_hash($password),
				'user_email' => $email,
				'user_type' => $type
			);

			$this->db->insert($this->config->item('auth_table'), $data);
			return 1;
		} else {
			return 0;
		}
	}

	// FUNCTION: void edit()
	// Edit a user
	// TODO: This should return bool TRUE/FALSE or 0/1
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

	// FUNCTION: bool login()
	// Validates a username/password combination
	// This is really just a wrapper around User_Model::authenticate
	function login() {
		
		$username = $this->input->post('user_name');
		$password = $this->input->post('user_password');

		return $this->authenticate($username, $password);
	}

	// FUNCTION: void clear_session()
	// Clears a user's login session
	// Nothing is returned - it can be assumed that if this is called, the user's
	// login session *will* be cleared, no matter what state it is in
	function clear_session() {
	
		$this->session->unset_userdata(array('user_id' => '', 'user_type' => '', 'user_email' => '', 'user_hash' => ''));
	}
		
	// FUNCTION: void update_session()
	// Updates a user's login session after they've logged in
	// TODO: This should return bool TRUE/FALSE or 0/1
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

	// FUNCTION: bool validate_session()
	// Validate a user's login session
	// If the user's session is corrupted in any way, it will clear the session
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

	// FUNCTION: bool authenticate($username, $password)
	// Authenticate a user against the users table
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

	// FUNCTION: bool authorize($level)
	// Checks a user's level of access against the given $level
	function authorize($level) {
		$u = $this->get_by_id($this->session->userdata('user_id'));
		if(($this->validate_session) && ($u->row()->user_type >= $level)) {
			return 1;
		} else {
			return 0;
		}
	}

	// FUNCTION: bool set($username, $data)
	// Updates a user's record in the database
	// TODO: This returns TRUE/1 no matter what at the moment - should
	// TODO: return TRUE/FALSE or 0/1 depending on success/failure
	function set($username, $data) {
		$this->db->where('user_name', $username);
		$this->db->update($this->config->item('auth_table', $data));
		return 1;
	}

	// FUNCTION: object users()
	// Returns a list of users
	function users() {
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	// FUNCTION: bool _auth($password, $hash)
	// Checks a password against the stored hash
	private function _auth($password, $hash) {
		$h = new PasswordHash(8, FALSE);
		if($h->CheckPassword($password, $hash)) {
			return 1;
		} else {
			return 0;
		}
	}

	// FUNCTION: string _hash($password)
	// Returns a hashed version of the supplied $password
	// Will return '0' in the event of problems with the
	// hashing function
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
