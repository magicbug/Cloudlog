<?php

	class Cat extends CI_Model {

		function update($result, $user_id) {

			if ($result['timestamp'] != "") {
				$timestamp = gmdate("Y-m-d H:i:s");
			} else {
				$timestamp = gmdate("Y-m-d H:i:s");
			}

			if (isset($result['prop_mode'])) {
				$prop_mode = $result['prop_mode'];
			} else {
				// For backward compatibility, SatPC32 does not set propergation mode
				if (isset($result['sat_name'])) {
					$prop_mode = "SAT";
				} else {
					$prop_mode = NULL;
				}
			}

			$this->db->where('radio', $result['radio']);
			$this->db->where('user_id', $user_id);
			$query = $this->db->get('cat');

			// Let's keep uplink_freq, downlink_freq, uplink_mode and downlink_mode for backward compatibility
			$data = array(
				'prop_mode' => $prop_mode,
				'frequency' => $result['frequency'] ?? $result['uplink_freq'],
				'downlink_freq' => $result['frequency_rx'] ?? $result['downlink_freq'],
				'mode' => $result['mode'] ?? $result['uplink_mode'],
				'downlink_mode' => $result['mode_rx'] ?? $result['downlink_mode'],
				'power' => $result['power'],
				'sat_name' => $result['sat_name'],
				'timestamp' => $timestamp,
			);

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
	}
?>
