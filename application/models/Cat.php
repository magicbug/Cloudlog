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

			if ($query->num_rows() > 0)
			{
				// Update the record
				foreach ($query->result() as $row)
				{
					$radio_id = $row->id;

					if ($prop_mode == "SAT") {
						$data = array(
							'prop_mode' => $prop_mode,
							'sat_name' => $result['sat_name'],
							'downlink_freq' => $result['downlink_freq'],
							'uplink_freq' => $result['uplink_freq'],
							'downlink_mode' => $result['downlink_mode'],
							'uplink_mode' => $result['uplink_mode'],
							'timestamp' => $timestamp,
							'mode' => NULL,
							'frequency' => NULL,
						);
					} else {
						$data = array(
							'prop_mode' => $result['prop_mode'],
							'mode' => $result['mode'],
							'frequency' => $result['frequency'],
							'downlink_freq' => NULL,
							'downlink_mode' => NULL,
							'uplink_freq' => NULL,
							'uplink_mode' => NULL,
							'timestamp' => $timestamp,
						);
					}

					if (isset($result['power'])) {
						$data['power'] = $result['power'];
					}

					$this->db->where('id', $radio_id);
					$this->db->where('user_id', $user_id);
					$this->db->update('cat', $data);
				}
			} else {
				// Add a new record

				if ($prop_mode == "SAT") {
					$data = array(
						'radio' => $result['radio'],
						'frequency' => NULL,
						'mode' => NULL,
						'sat_name' => $result['sat_name'],
						'downlink_freq' => $result['downlink_freq'],
						'uplink_freq' => $result['uplink_freq'],
						'downlink_mode' => $result['downlink_mode'],
						'uplink_mode' => $result['uplink_mode'],
						'prop_mode' => $prop_mode,
						'user_id' => $user_id,
						'timestamp' => $timestamp,
					);
				} else {
					$data = array(
						'radio' => $result['radio'],
						'prop_mode' => $prop_mode,
						'mode' => $result['mode'],
						'frequency' => $result['frequency'],
						'downlink_freq' => NULL,
						'downlink_mode' => NULL,
						'uplink_freq' => NULL,
						'uplink_mode' => NULL,
						'user_id' => $user_id,
						'timestamp' => $timestamp,
					);
				}

				if (isset($result['power'])) {
					$data['power'] = $result['power'];
				}

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
