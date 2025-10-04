<?php

	class Cat extends CI_Model {

		function update($result, $user_id) {

			$timestamp = gmdate("Y-m-d H:i:s");

			if (isset($result['prop_mode'])) {
				$prop_mode = $result['prop_mode'];
			// For backward compatibility, SatPC32 does not set propergation mode
			} else if (isset($result['sat_name'])) {
				$prop_mode = "SAT";
			} else {
				$prop_mode = NULL;
			}

			$this->db->where('radio', $result['radio']);
			$this->db->where('user_id', $user_id);
			$query = $this->db->get('cat');

			// Let's keep uplink_freq, downlink_freq, uplink_mode and downlink_mode for backward compatibility
			$data = array(
				'prop_mode' => $prop_mode,
				'power' => $result['power'] ?? NULL,
				'sat_name' => $result['sat_name'] ?? NULL,
				'timestamp' => $timestamp,
			);
			if (isset($result['frequency']) && $result['frequency'] != "NULL") {
				$data['frequency'] = $result['frequency'];
			} else {
				$data['frequency'] = $result['uplink_freq'];
			}
			if (isset($result['mode']) && $result['mode'] != "NULL") {
				$data['mode'] = $result['mode'];
			} else {
				if (isset($result['uplink_mode']) && $result['uplink_mode'] != "NULL") {
					$data['mode'] = $result['uplink_mode'];
				} else {
					$data['mode'] = NULL;
				}
			}
			if (isset($result['frequency_rx'])) {
				$data['frequency_rx'] = $result['frequency_rx'];
			} else if (isset($result['downlink_freq']) && $result['downlink_freq'] != "NULL") {
				$data['frequency_rx'] = $result['downlink_freq'];
			} else {
				$data['frequency_rx'] = NULL;
			}
			if (isset($result['mode_rx'])) {
				$data['mode_rx'] = $result['mode_rx'];
			} else if (isset($result['downlink_mode']) && $result['downlink_mode'] != "NULL") {
				$data['mode_rx'] = $result['downlink_mode'];
			} else {
				$data['mode_rx'] = NULL;
			}

			if ($query->num_rows() > 0)
			{
				// Update the record
				foreach ($query->result() as $row)
				{
					$radio_id = $row->id;

					$this->db->where('id', $radio_id);
					$this->db->where('user_id', $user_id);
					$this->db->update('cat', $data);
				}
			} else {
				// Add a new record
				$data['radio'] = $result['radio'];
				$data['user_id'] = $user_id;

				$this->db->insert('cat', $data);
			}
		}

		function status() {
			//$this->db->where('radio', $result['radio']);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$query = $this->db->get('cat');

			return $query;
		}

		function recent_status() {
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where("timestamp > date_sub(UTC_TIMESTAMP(), interval 15 minute)", NULL, FALSE);

			$query = $this->db->get('cat');
			return $query;
		}

		/* Return list of radios */
		function radios() {
			$this->db->select('id, radio');
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$query = $this->db->get('cat');

			return $query;
		}

		function radio_status($id) {
			$sql = 'SELECT * FROM `cat` WHERE id = ' . $id . ' and user_id =' . $this->session->userdata('user_id');
			return $this->db->query($sql);
		}

	function delete($id) {
		$this->db->where('id', $id);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->delete('cat');

		return true;
	}

	/* Radio Command Functions */

	/**
	 * Queue a command for a radio
	 * @param array $command_data Array containing command details
	 * @return int|bool Command ID on success, FALSE on failure
	 */
	function queue_command($command_data) {
		// Get radio name if not provided
		if (!isset($command_data['radio_name']) && isset($command_data['radio_id'])) {
			$radio_query = $this->db->select('radio')
				->where('id', $command_data['radio_id'])
				->where('user_id', $command_data['user_id'])
				->get('cat');
			
			if ($radio_query->num_rows() > 0) {
				$command_data['radio_name'] = $radio_query->row()->radio;
			}
		}

        // Set default expires_at if not provided (30 minutes from now)
        if (!isset($command_data['expires_at'])) {
            $command_data['expires_at'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        }		// Set created_at timestamp
		$command_data['created_at'] = date('Y-m-d H:i:s');

		$this->db->insert('radio_commands', $command_data);
		
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		}
		
		return FALSE;
	}

	/**
	 * Get pending commands for a specific radio
	 * @param int $radio_id Radio ID
	 * @param int $user_id User ID (optional, uses session if not provided)
	 * @return array Array of pending commands
	 */
	function get_pending_commands($radio_id, $user_id = null) {
		if ($user_id === null) {
			$user_id = $this->session->userdata('user_id');
		}

		$this->db->select('*');
		$this->db->where('radio_id', $radio_id);
		$this->db->where('user_id', $user_id);
		$this->db->where('status', 'PENDING');
		$this->db->where('expires_at >', date('Y-m-d H:i:s'));
		$this->db->order_by('created_at', 'ASC');
		
		$query = $this->db->get('radio_commands');
		return $query->result_array();
	}

	/**
	 * Get all pending commands for all radios (for desktop app polling)
	 * @param int $user_id User ID (optional, gets all users if not provided)
	 * @return array Array of pending commands
	 */
	function get_all_pending_commands($user_id = null) {
		$this->db->select('*');
		if ($user_id !== null) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->where('status', 'PENDING');
		$this->db->where('expires_at >', date('Y-m-d H:i:s'));
		$this->db->order_by('created_at', 'ASC');
		
		$query = $this->db->get('radio_commands');
		return $query->result_array();
	}

	/**
	 * Get pending commands for a radio by radio name (for Aurora)
	 * @param string $radio_name Radio name as stored in cat table
	 * @param int $user_id User ID (optional, uses session if not provided)
	 * @return array Array of pending commands
	 */
	function get_pending_commands_by_name($radio_name, $user_id = null) {
		if ($user_id === null) {
			$user_id = $this->session->userdata('user_id');
		}

		// First get the radio ID from the cat table using radio name
		$radio_query = $this->db->select('id, user_id, radio')
			->where('radio', $radio_name)
			->where('user_id', $user_id)
			->get('cat');

		if ($radio_query->num_rows() == 0) {
			// Debug: Check if radio exists for any user
			$all_radios_query = $this->db->select('id, user_id, radio')
				->where('radio', $radio_name)
				->get('cat');
			
			log_message('error', "Radio '{$radio_name}' not found for user_id {$user_id}. Found for other users: " . $all_radios_query->num_rows());
			return array(); // No radio found with that name for this user
		}

		$radio_row = $radio_query->row();
		$radio_id = $radio_row->id;

		// Debug log
		log_message('error', "Found radio '{$radio_name}' with ID {$radio_id} for user_id {$user_id}");

		// Now get pending commands for this radio
		$this->db->select('*');
		$this->db->where('radio_id', $radio_id);
		$this->db->where('user_id', $user_id);
		$this->db->where('status', 'PENDING');
		$this->db->where('expires_at >', date('Y-m-d H:i:s'));
		$this->db->order_by('created_at', 'ASC');
		
		$query = $this->db->get('radio_commands');
		$commands = $query->result_array();
		
		// Debug log
		log_message('error', "Found " . count($commands) . " pending commands for radio_id {$radio_id}, user_id {$user_id}");
		
		return $commands;
	}

	/**
	 * Update command status
	 * @param int $command_id Command ID
	 * @param string $status New status (PENDING, PROCESSING, COMPLETED, FAILED)
	 * @param string $error_message Error message if status is FAILED
	 * @return bool TRUE on success, FALSE on failure
	 */
	function update_command_status($command_id, $status, $error_message = null) {
		$data = array(
			'status' => $status,
			'processed_at' => date('Y-m-d H:i:s')
		);

		if ($error_message !== null) {
			$data['error_message'] = $error_message;
		}

		$this->db->where('id', $command_id);
		$this->db->update('radio_commands', $data);
		
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Get command history for a radio
	 * @param int $radio_id Radio ID
	 * @param int $user_id User ID (optional, uses session if not provided)
	 * @param int $limit Number of records to return (default 50)
	 * @return array Array of command history
	 */
	function get_command_history($radio_id, $user_id = null, $limit = 50) {
		if ($user_id === null) {
			$user_id = $this->session->userdata('user_id');
		}

		$this->db->select('*');
		$this->db->where('radio_id', $radio_id);
		$this->db->where('user_id', $user_id);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit);
		
		$query = $this->db->get('radio_commands');
		return $query->result_array();
	}

	/**
	 * Clean up expired commands
	 * @return int Number of deleted commands
	 */
	function cleanup_expired_commands() {
		$this->db->where('expires_at <', date('Y-m-d H:i:s'));
		$this->db->where('status', 'PENDING');
		$this->db->delete('radio_commands');
		
		return $this->db->affected_rows();
	}

	/**
	 * Get command by ID
	 * @param int $command_id Command ID
	 * @param int $user_id User ID (optional, uses session if not provided)
	 * @return array|null Command data or null if not found
	 */
	function get_command($command_id, $user_id = null) {
		if ($user_id === null) {
			$user_id = $this->session->userdata('user_id');
		}

		$this->db->select('*');
		$this->db->where('id', $command_id);
		$this->db->where('user_id', $user_id);
		
		$query = $this->db->get('radio_commands');
		
		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
		
		return null;
	}

	/**
	 * Cancel a pending command
	 * @param int $command_id Command ID
	 * @param int $user_id User ID (optional, uses session if not provided)
	 * @return bool TRUE on success, FALSE on failure
	 */
	function cancel_command($command_id, $user_id = null) {
		if ($user_id === null) {
			$user_id = $this->session->userdata('user_id');
		}

		$this->db->where('id', $command_id);
		$this->db->where('user_id', $user_id);
		$this->db->where('status', 'PENDING');
		$this->db->update('radio_commands', array(
			'status' => 'FAILED',
			'error_message' => 'Cancelled by user',
			'processed_at' => date('Y-m-d H:i:s')
		));
		
		return $this->db->affected_rows() > 0;
	}
}
?>