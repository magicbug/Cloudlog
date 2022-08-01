<?php

	class Cat extends CI_Model {

		function update($result, $user_id) {

			if ($result['timestamp'] != "") {
				$timestamp = gmdate("Y-m-d H:i:s");
			} else {
				$timestamp = gmdate("Y-m-d H:i:s");
			}

			$this->db->where('radio', $result['radio']);
			$this->db->where('user_id', $user_id);
			$query = $this->db->get('cat');

			if ($query->num_rows() > 0)
			{
				if($result['radio'] == "SatPC32") {
					// Update the record
					foreach ($query->result() as $row)
					{
						$radio_id = $row->id;

						$data = array(
							'sat_name' => $result['sat_name'],
							'downlink_freq' => $result['downlink_freq'],
							'uplink_freq' => $result['uplink_freq'],
							'downlink_mode' => $result['downlink_mode'],
							'uplink_mode' => $result['uplink_mode'],
							'prop_mode' => 'SAT',
							'timestamp' => $timestamp,
						);

						$this->db->where('id', $radio_id);
						$this->db->where('user_id', $user_id);
						$this->db->update('cat', $data);
					}
				} else if($result['radio'] == "CloudLogCATQt") {
					// Update the record
					foreach ($query->result() as $row)
					{
						$radio_id = $row->id;

						if ($result['prop_mode'] == "SAT") {
							$data = array(
								'sat_name' => $result['sat_name'],
								'prop_mode' => $result['prop_mode'],
								'mode' => NULL,
								'frequency' => NULL,
								'downlink_freq' => $result['downlink_freq'],
								'uplink_freq' => $result['uplink_freq'],
								'downlink_mode' => $result['downlink_mode'],
								'uplink_mode' => $result['uplink_mode'],
								'timestamp' => $timestamp,
							);
							if (isset($result['power'])) {
								$data['power'] = $result['power'];
							}
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
							if (isset($result['power'])) {
								$data['power'] = $result['power'];
							}
						}

						$this->db->where('id', $radio_id);
						$this->db->where('user_id', $user_id);
						$this->db->update('cat', $data);
					}
				} else {
					// Update the record
					foreach ($query->result() as $row)
					{
						$radio_id = $row->id;

						$data = array(
						'frequency' => $result['frequency'],
						'mode' => $result['mode'],
						'timestamp' => $timestamp,
						);

						if (isset($result['power'])) {
							$data['power'] = $result['power'];
						}

						$this->db->where('id', $radio_id);
						$this->db->where('user_id', $user_id);
						$this->db->update('cat', $data);
					}
				}
			} else {
				// Add a new record

				if($result['radio'] == "SatPC32") {
					$data = array(
						'radio' => $result['radio'],
						'frequency' => $result['frequency'],
						'mode' => $result['mode'],
						'sat_name' => $result['sat_name'],
						'downlink_freq' => $result['downlink_freq'],
						'uplink_freq' => $result['uplink_freq'],
						'downlink_mode' => $result['downlink_mode'],
						'uplink_mode' => $result['uplink_mode'],
						'prop_mode' => 'SAT',
						'user_id' => $user_id,
						'timestamp' => $timestamp,
					);
				} else if($result['radio'] == "CloudLogCATQt") {
					if ($result['prop_mode'] == "SAT") {
						$data = array(
							'radio' => $result['radio'],
							'sat_name' => $result['sat_name'],
							'prop_mode' => $result['prop_mode'],
							'mode' => NULL,
							'frequency' => NULL,
							'downlink_freq' => $result['downlink_freq'],
							'uplink_freq' => $result['uplink_freq'],
							'downlink_mode' => $result['downlink_mode'],
							'uplink_mode' => $result['uplink_mode'],
							'user_id' => $user_id,
							'timestamp' => $timestamp,
						);
						if (isset($result['power'])) {
							$data['power'] = $result['power'];
						}
					} else {
						$data = array(
							'radio' => $result['radio'],
							'prop_mode' => $result['prop_mode'],
							'mode' => $result['mode'],
							'frequency' => $result['frequency'],
							'downlink_freq' => NULL,
							'downlink_mode' => NULL,
							'uplink_freq' => NULL,
							'uplink_mode' => NULL,
							'user_id' => $user_id,
							'timestamp' => $timestamp,
						);
						if (isset($result['power'])) {
							$data['power'] = $result['power'];
						}
					}
				} else {
					$data = array(
						'radio' => $result['radio'],
						'frequency' => $result['frequency'],
						'mode' => $result['mode'],
						'timestamp' => $timestamp,
						'user_id' => $user_id,
					);

					if (isset($result['power'])) {
						$data['power'] = $result['power'];
					}
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
