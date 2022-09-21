<?php

/* user_model.php
 *
 * This model implements user authentication and authorization
 *
 */


// Uses 'phpass' from http://www.openwall.com/phpass/ to implement password hashing
// TODO migration away from this?
//require_once('application/third_party/PasswordHash.php');

class User_Model extends CI_Model {

	// FUNCTION: object get($username)
	// Retrieve a user
	function get($username) {
		// Clean ID
		$clean_username = $this->security->xss_clean($username);

		$this->db->where('user_name', $clean_username);
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	// FUNCTION: object get_by_id($id)
	// Retrieve a user by user ID
	function get_by_id($id) {
				// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$this->db->where('user_id', $clean_id);
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	// FUNCTION: object get_all_lotw_users
	// Returns all users with lotw details
	function get_all_lotw_users() {
		$this->db->where('user_lotw_name !=', null);
		$this->db->where('user_lotw_name !=', "");
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	// FUNCTION: object get_by_email($email)
	// Retrieve a user by email address
	function get_by_email($email) {

		$clean_email = $this->security->xss_clean($email);

		$this->db->where('user_email', $clean_email);
		$r = $this->db->get($this->config->item('auth_table'));
		return $r;
	}

	/*
	 * Function: check_email_address
	 * 
	 * Checks if an email address is already in use
	 * 
	 * @param string $email
	 */
	function check_email_address($email) {

		$clean_email = $this->security->xss_clean($email);

		$this->db->where('user_email', $clean_email);
		$query = $this->db->get($this->config->item('auth_table'));
		
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	// FUNCTION: bool exists($username)
	// Check if a user exists (by username)
	function exists($username) {
		$clean_username = $this->security->xss_clean($username);
		if($this->get($clean_username)->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	// FUNCTION: bool exists_by_id($id)
	// Check if a user exists (by user ID)
	function exists_by_id($id) {
		$clean_id = $this->security->xss_clean($id);

		if($this->get_by_id($clean_id)->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	// FUNCTION: bool exists_by_email($email)
	// Check if a user exists (by email address)
	function exists_by_email($email) {
		if($this->get_by_email($email)->num_rows() == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	// FUNCTION: bool add($username, $password, $email, $type)
	// Add a user
	function add($username, $password, $email, $type, $firstname, $lastname, $callsign, $locator, $timezone,
				 $measurement, $user_date_format, $user_stylesheet, $user_sota_lookup, $user_show_notes,
				 $user_column1, $user_column2, $user_column3, $user_column4, $user_column5, $user_show_profile_image) {
		// Check that the user isn't already used
		if(!$this->exists($username)) {
			$data = array(
				'user_name' => xss_clean($username),
				'user_password' => $this->_hash($password),
				'user_email' => xss_clean($email),
				'user_type' => xss_clean($type),
				'user_firstname' => xss_clean($firstname),
				'user_lastname' => xss_clean($lastname),
				'user_callsign' => xss_clean($callsign),
				'user_locator' => xss_clean($locator),
				'user_timezone' => xss_clean($timezone),
				'user_measurement_base' => xss_clean($measurement),
				'user_date_format' => xss_clean($user_date_format),
				'user_stylesheet' => xss_clean($user_stylesheet),
				'user_sota_lookup' => xss_clean($user_sota_lookup),
				'user_show_notes' => xss_clean($user_show_notes),
				'user_column1' => xss_clean($user_column1),
				'user_column2' => xss_clean($user_column2),
				'user_column3' => xss_clean($user_column3),
				'user_column4' => xss_clean($user_column4),
				'user_column5' => xss_clean($user_column5),
				'user_show_profile_image' => xss_clean($user_show_profile_image),
			);

			// Check the password is valid
			if($data['user_password'] == EPASSWORDINVALID) {
				return EPASSWORDINVALID;
			}

			// Check the email address isn't in use
			if($this->exists_by_email($email)) {
				return EEMAILEXISTS;
			}

			// Add user and insert bandsettings for user
			$this->db->insert($this->config->item('auth_table'), $data);
			$insert_id = $this->db->insert_id();
			$this->db->query("insert into bandxuser (bandid, userid, active, cq, dok, dxcc, iota, sig, sota, uscounties, was, wwff, vucc) select bands.id, " . $insert_id . ", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1 from bands;");
			return OK;
		} else {
			return EUSERNAMEEXISTS;
		}
	}

	// FUNCTION: bool edit()
	// Edit a user
	function edit($fields) {

		// Check user privileges
		if(($this->session->userdata('user_type') == 99) || ($this->session->userdata('user_id') == $fields['id'])) {
			if($this->exists_by_id($fields['id'])) {
				$data = array(
					'user_name' => xss_clean($fields['user_name']),
					'user_email' => xss_clean($fields['user_email']),
					'user_callsign' => xss_clean($fields['user_callsign']),
					'user_locator' => xss_clean($fields['user_locator']),
					'user_firstname' => xss_clean($fields['user_firstname']),
					'user_lastname' => xss_clean($fields['user_lastname']),
					'user_timezone' => xss_clean($fields['user_timezone']),
					'user_lotw_name' => xss_clean($fields['user_lotw_name']),
					'user_eqsl_name' => xss_clean($fields['user_eqsl_name']),
					'user_clublog_name' => xss_clean($fields['user_clublog_name']),
					'user_measurement_base' => xss_clean($fields['user_measurement_base']),
					'user_date_format' => xss_clean($fields['user_date_format']),
					'user_stylesheet' => xss_clean($fields['user_stylesheet']),
					'user_sota_lookup' => xss_clean($fields['user_sota_lookup']),
					'user_show_notes' => xss_clean($fields['user_show_notes']),
					'user_column1' => xss_clean($fields['user_column1']),
					'user_column2' => xss_clean($fields['user_column2']),
					'user_column3' => xss_clean($fields['user_column3']),
					'user_column4' => xss_clean($fields['user_column4']),
					'user_column5' => xss_clean($fields['user_column5']),
					'user_show_profile_image' => xss_clean($fields['user_show_profile_image']),
				);

				// Check to see if the user is allowed to change user levels
				if($this->session->userdata('user_type') == 99) {
					$data['user_type'] = $fields['user_type'];
				}

				// Check to see if username is used already
				if($this->exists($fields['user_name']) && $this->get($fields['user_name'])->row()->user_id != $fields['id']) {
					return EUSERNAMEEXISTS;
				}
				// Check to see if email address is used already
				if($this->exists_by_email($fields['user_email']) && $this->get_by_email($fields['user_email'])->row()->user_id != $fields['id']) {
					return EEMAILEXISTS;
				}

				// Hash password
				if($fields['user_password'] != NULL)
				{
					$data['user_password'] = $this->_hash($fields['user_password']);
					if($data['user_password'] == EPASSWORDINVALID) {
						return EPASSWORDINVALID;
					}
				}

				if($fields['user_lotw_password'] != NULL)
				{
					$data['user_lotw_password'] = $fields['user_lotw_password'];
				}

				if($fields['user_clublog_password'] != NULL)
				{
					$data['user_clublog_password'] = $fields['user_clublog_password'];
				}

				if($fields['user_eqsl_password'] != NULL)
				{
					$data['user_eqsl_password'] = $fields['user_eqsl_password'];
				}

				// Update the user
				$this->db->where('user_id', $fields['id']);
				$this->db->update($this->config->item('auth_table'), $data);
				return OK;
			} else {
				return ENOSUCHUSER;
			}
		} else {
			return EFORBIDDEN;
		}
	}

	// FUNCTION: bool delete()
	// Deletes a user
	function delete($user_id) {

		if($this->exists_by_id($user_id)) {
			$this->db->query("DELETE FROM ".$this->config->item('auth_table')." WHERE user_id = '".$user_id."'");

			return 1;
		} else {
			return 0;
		}
	}

	// FUNCTION: bool login()
	// Validates a username/password combination
	// This is really just a wrapper around User_Model::authenticate
	function login() {

		$username = $this->input->post('user_name', true);
		$password = $this->input->post('user_password', true);

		return $this->authenticate($username, $password);
	}

	// FUNCTION: void clear_session()
	// Clears a user's login session
	// Nothing is returned - it can be assumed that if this is called, the user's
	// login session *will* be cleared, no matter what state it is in
	function clear_session() {

		$this->session->sess_destroy();
	}

	// FUNCTION: void update_session()
	// Updates a user's login session after they've logged in
	// TODO: This should return bool TRUE/FALSE or 0/1
	function update_session($id) {

		$u = $this->get_by_id($id);

		$userdata = array(
			'user_id'		 => $u->row()->user_id,
			'user_name'		 => $u->row()->user_name,
			'user_type'		 => $u->row()->user_type,
			'user_callsign'		 => $u->row()->user_callsign,
			'user_locator'		 => $u->row()->user_locator,
			'user_lotw_name'	 => $u->row()->user_lotw_name,
			'user_eqsl_name'	 => $u->row()->user_eqsl_name,
			'user_eqsl_qth_nickname' => $u->row()->user_eqsl_qth_nickname,
			'user_hash'		 => $this->_hash($u->row()->user_id."-".$u->row()->user_type),
			'radio' => isset($_COOKIE["radio"])?$_COOKIE["radio"]:"",
			'station_profile_id' => isset($_COOKIE["station_profile_id"])?$_COOKIE["station_profile_id"]:"",
			'user_measurement_base' => $u->row()->user_measurement_base,
			'user_date_format' => $u->row()->user_date_format,
			'user_stylesheet' => $u->row()->user_stylesheet,
			'user_sota_lookup' => isset($u->row()->user_sota_lookup) ? $u->row()->user_sota_lookup : 0,
			'user_show_notes' => isset($u->row()->user_show_notes) ? $u->row()->user_show_notes : 1,
			'user_show_profile_image' => isset($u->row()->user_show_profile_image) ? $u->row()->user_show_profile_image : 0,
			'user_column1' => isset($u->row()->user_column1) ? $u->row()->user_column1: 'Mode',
			'user_column2' => isset($u->row()->user_column2) ? $u->row()->user_column2: 'RSTS',
			'user_column3' => isset($u->row()->user_column3) ? $u->row()->user_column3: 'RSTR',
			'user_column4' => isset($u->row()->user_column4) ? $u->row()->user_column4: 'Band',
			'user_column5' => isset($u->row()->user_column5) ? $u->row()->user_column5: 'Country',
			'active_station_logbook' => $u->row()->active_station_logbook,
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
				// Freshen the session
				$this->update_session($user_id);
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
		if($u->num_rows() != 0)
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
		$l = $this->config->item('auth_mode');
		// Check to see if the minimum level of access is higher than
		// the user's own level. If it is, use that.
		if($this->config->item('auth_mode') > $level) {
			$level = $this->config->item('auth_mode');
		}
		if(($this->validate_session()) && ($u->row()->user_type >= $level) || $this->config->item('use_auth') == FALSE || $level == 0) {
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

	// FUNCTION: array timezones()
	// Returns a list of timezones
	function timezones() {
		$r = $this->db->query('SELECT id, name FROM timezones ORDER BY `offset`');
		$ts = array();
		foreach ($r->result_array() as $t) {
			$ts[$t['id']] = $t['name'];
		}
		return $ts;
	}

	// FUNCTION: array getThemes()
	// Returns a list of themes
	function getThemes() {
		$result = $this->db->query('SELECT * FROM themes order by name');

		return $result->result();
	}

	/*
	 * FUNCTION: set_password_reset_code
	 *
	 * Stores generated password reset code in the database and sets the date to exactly
	 * when the sql query runs.
	 * 
	 * @param string $user_email
	 * @return string $reset_code
	 */
	function set_password_reset_code($user_email, $reset_code) {
		$data = array(
			'reset_password_code' => $reset_code,
			'reset_password_date' => date('Y-m-d H:i:s')
		);
				
		$this->db->where('user_email', $user_email);
		$this->db->update('users', $data);
	}

	/*
	 * FUNCTION: reset_password
	 *
	 * Sets new password for users account where the reset code matches then clears the password reset code and password reset date.
	 * 
	 * @param string $password
	 * @return string $reset_code
	 */
	function reset_password($password, $reset_code) {
		$data = array(
			'user_password' => $this->_hash($password),
			'reset_password_code' => NULL,
			'reset_password_date' => NULL
		);
				
		$this->db->where('reset_password_code', $reset_code);
		$this->db->update('users', $data);
	}

	// FUNCTION: bool _auth($password, $hash)
	// Checks a password against the stored hash
	private function _auth($password, $hash) {
		if(password_verify($password, $hash)) {
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
        $hash = password_hash($password, PASSWORD_DEFAULT);

		if(strlen($hash) < 20) {
			return EPASSWORDINVALID;
		} else {
			return $hash;
		}
	}

}

?>
