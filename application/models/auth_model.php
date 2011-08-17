<?php

// Uses 'phpass' from http://www.openwall.com/phpass/ to implement password hashing
require_once('application/third_party/PasswordHash.php');

class Auth_Model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	// Test function, can be removed once class is complete
	function test() {
		$hash = $this->_hash("password");
		echo "Password hashed is '".$hash."\n";
		echo "Does 'password' match '$hash'? result is ".$this->_auth("password", $hash)."\n";

	}

	// Retrieve a user
	function get($username) {
		$this->db->where('user_name', $username);
		$r = $this->db->get($this->config->item('auth_table'));
		if($r->num_rows == 1) {
			return $r->result();
		} else {
			return 0;
		}
	}

	function exists($username) {
		if($this->get($username)->num_rows == 0) {
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

	function authenticate($username, $password) {
		$u = $this->get($username);
		if($this->_hash($password, $u['user_password'])) {
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
