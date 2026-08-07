<?php

class Clublog_model extends CI_Model {

	function get_clublog_users() {
		$this->db->select('user_clublog_name, user_clublog_password, user_id');
		$this->db->where('coalesce(user_clublog_name, "") != ""');
		$this->db->where('coalesce(user_clublog_password, "") != ""');
		$query = $this->db->get($this->config->item('auth_table'));
		return $query->result();
	}

	function get_clublog_auth_info($username) {
		$this->db->select('user_name, user_clublog_name, user_clublog_password');
		$this->db->where('user_name', $username);
		$query = $this->db->get($this->config->item('auth_table'));
		return $row = $query->row_array();
	}

	function user_has_clublog_credentials($user_id) {
		$this->db->select('user_id');
		$this->db->from($this->config->item('auth_table'));
		$this->db->where('user_id', $user_id);
		$this->db->where('coalesce(user_clublog_name, "") != ""');
		$this->db->where('coalesce(user_clublog_password, "") != ""');

		return $this->db->count_all_results() > 0;
	}

	function user_needs_clublogcron_station_warning($user_id) {
		if (!$this->user_has_clublog_credentials($user_id)) {
			return false;
		}

		$this->db->from('station_profile');
		$this->db->where('user_id', $user_id);
		$total_stations = $this->db->count_all_results();

		if ($total_stations == 0) {
			return false;
		}

		$this->db->from('station_profile');
		$this->db->where('user_id', $user_id);
		if ($this->db->field_exists('clublogcron', 'station_profile')) {
			$this->db->where('clublogcron', 1);
		} else {
			$this->db->where('clublogrealtime', 1);
		}

		return $this->db->count_all_results() == 0;
	}

	// function to reset clublog fields for the user in the auth table
	function reset_clublog_user_fields($user_id, $notify_user = false, $reset_context = '', $set_flash_warning = false) {
		$data = array(
			'user_clublog_name' => null,
			'user_clublog_password' => null,
		);

		$this->db->where('user_id', $user_id);
		$updated = $this->db->update($this->config->item('auth_table'), $data);

		if ($updated && $notify_user) {
			$this->send_clublog_credentials_reset_email($user_id, $reset_context);
		}

		if ($updated && $set_flash_warning && isset($this->session) && method_exists($this->session, 'userdata') && method_exists($this->session, 'set_flashdata')) {
			if ((int) $this->session->userdata('user_id') === (int) $user_id) {
				$this->session->set_flashdata('warning', 'Your Clublog credentials were cleared after an authorization failure. Please update your Clublog email and password in your account settings.');
			}
		}

		return $updated;
	}

	private function send_clublog_credentials_reset_email($user_id, $reset_context = '') {
		if ($this->optionslib->get_option('emailProtocol') == '') {
			log_message('info', 'Email not configured - skipping Clublog credentials reset notification for user ID: ' . $user_id);
			return false;
		}

		$this->db->select('user_email, user_callsign, user_firstname, user_lastname, user_name');
		$this->db->from($this->config->item('auth_table'));
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();

		if ($query->num_rows() === 0) {
			log_message('error', 'Clublog credentials reset notification failed: user not found for user ID: ' . $user_id);
			return false;
		}

		$user = $query->row();
		if (empty($user->user_email) || !filter_var($user->user_email, FILTER_VALIDATE_EMAIL)) {
			log_message('error', 'Clublog credentials reset notification skipped: invalid or missing email for user ID: ' . $user_id);
			return false;
		}

		$this->load->library('email');

		if ($this->optionslib->get_option('emailProtocol') == 'smtp') {
			$config = array(
				'protocol' => $this->optionslib->get_option('emailProtocol'),
				'smtp_crypto' => $this->optionslib->get_option('smtpEncryption'),
				'smtp_host' => $this->optionslib->get_option('smtpHost'),
				'smtp_port' => $this->optionslib->get_option('smtpPort'),
				'smtp_user' => $this->optionslib->get_option('smtpUsername'),
				'smtp_pass' => $this->optionslib->get_option('smtpPassword'),
				'crlf' => "\r\n",
				'newline' => "\r\n"
			);
			$this->email->initialize($config);
		}

		$email_data = array(
			'user_firstname' => $user->user_firstname,
			'user_lastname' => $user->user_lastname,
			'user_callsign' => $user->user_callsign,
			'user_name' => $user->user_name,
			'base_url' => base_url(),
			'reset_context' => $reset_context,
		);

		$message = $this->load->view('email/clublog_credentials_reset', $email_data, true);

		$this->email->from($this->optionslib->get_option('emailAddress'), $this->optionslib->get_option('emailSenderName'));
		$this->email->to($user->user_email);
		$this->email->subject('Cloudlog - Clublog Credentials Reset');
		$this->email->message($message);

		if (!$this->email->send()) {
			log_message('error', 'Failed to send Clublog credentials reset email to ' . $user->user_email . '. Error: ' . $this->email->print_debugger());
			return false;
		}

		log_message('info', 'Clublog credentials reset notification sent to ' . $user->user_email);
		return true;
	}

	function mark_qsos_sent($station_id) {
		$data = array(
	        'COL_CLUBLOG_QSO_UPLOAD_DATE' => date('Y-m-d'),
	        'COL_CLUBLOG_QSO_UPLOAD_STATUS' => "Y",
		);

		$this->db->where("station_id", $station_id);
		$this->db->group_start();
		$this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", null);
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "");
	    		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "N");
	    		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "M");
		$this->db->group_end();
		$this->db->update($this->config->item('table_name'), $data);
	}

	function mark_qso_sent($qso_id) {
		$data = array(
	        'COL_CLUBLOG_QSO_UPLOAD_DATE' => date('Y-m-d'),
	        'COL_CLUBLOG_QSO_UPLOAD_STATUS' => "Y",
		);

		$this->db->where("COL_PRIMARY_KEY", $qso_id);
		$this->db->update($this->config->item('table_name'), $data);
	}

	function get_last_five($station_id) {
		$this->db->where('station_id', $station_id);
		$this->db->group_start();
	    $this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", null);
	    $this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "");
	    $this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "N");
		$this->db->group_end();
	    $this->db->limit(5); 
	    $query = $this->db->get($this->config->item('table_name'));

	    return $query;
	}

	function mark_all_qsos_notsent($station_id) {
		$data = array(
	        'COL_CLUBLOG_QSO_UPLOAD_DATE' => null,
	        'COL_CLUBLOG_QSO_UPLOAD_STATUS' => "M",
	        'COL_CLUBLOG_QSO_UPLOAD_STATUS' => "N",
		);

		$this->db->where("station_id", $station_id);
		$this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "Y");
		$this->db->update($this->config->item('table_name'), $data);
	}

	function get_clublog_qsos($station_id){
		$this->db->select('*, dxcc_entities.name as station_country');
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif', 'left outer');
		$this->db->where($this->config->item('table_name').'.station_id', $station_id);
		$this->db->group_start();
		$this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", null);
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "");
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "M");
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "N");
		$this->db->group_end();
	
		$query = $this->db->get($this->config->item('table_name'));

		return $query;
	  }

	  function all_with_count($userid) {
		$this->db->select('station_profile.station_id, station_profile.station_callsign, station_profile.user_id, count('.$this->config->item('table_name').'.station_id) as qso_total');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id','left');
       	$this->db->group_by('station_profile.station_id');
		$this->db->where('station_profile.user_id', $userid);
		if ($this->db->field_exists('clublogcron', 'station_profile')) {
			$this->db->where('station_profile.clublogcron', 1);
		} else {
			$this->db->where('station_profile.clublogrealtime', 1);
		}
		$this->db->group_start();
		$this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", null);
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "");
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "M");
		$this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "N");
		$this->db->group_end();
		
        return $this->db->get();
	}
}

?>
