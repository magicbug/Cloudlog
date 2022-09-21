<?php

	class Radio extends CI_Controller {

	public function index()
	{
		// Check Auth
		$this->load->model('user_model');

		// Check if users logged in

		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}
		// load the view
		$data['page_title'] = "Hardware Interfaces";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('radio/index');
		$this->load->view('interface_assets/footer');
	}

	function status() {

		// Check Auth
		$this->load->model('user_model');

		// Check if users logged in

		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}

		$this->load->model('cat');
		$query = $this->cat->status();
		if ($query->num_rows() > 0)
		{
			echo "<tr class=\"titles\">";
				echo "<td>Radio</td>";
				echo "<td>Identifier</td>";
				echo "<td>Frequency</td>";
				echo "<td>Mode</td>";
				echo "<td>Timestamp</td>" ;
				echo "<td>Options</td>";
			echo "</tr>";
			foreach ($query->result() as $row)
			{
				echo "<tr>";
				echo "<td>".$row->radio."</td>";
				echo $row->identifier != "" ? "<td>".$row->identifier."</td>" : "<td>n/a</td>";
				if($row->frequency != "0" && $row->frequency != NULL) {
					echo "<td>".$this->frequency->hz_to_mhz($row->frequency)."</td>";
				} else {
					echo "<td>".$this->frequency->hz_to_mhz($row->downlink_freq)." / ".$this->frequency->hz_to_mhz($row->uplink_freq)."</td>";
				}

				if($row->mode != "non" && $row->mode != NULL) {
					echo "<td>".$row->mode."</td>";
				} else {
					echo "<td>".$row->downlink_mode." / ".$row->uplink_mode."</td>";
				}
				$phpdate = strtotime($row->timestamp);
				echo "<td>".date('H:i:s d-m-y', $phpdate)."</td>" ;
				echo "<td><a href=\"".site_url('radio/delete')."/".$row->id."\" class=\"btn btn-danger\"> <i class=\"fas fa-trash-alt\"></i> Delete</a></td>" ;
				echo "</tr>";
			}
		} else {
			echo "<tr>";
				echo "<td colspan=\"4\">No CAT Interfaced radios found.</td>";
			echo "</tr>";
		}

	}

	function json($id)
	{

		header('Content-Type: application/json');

		$this->load->model('cat');

		$query = $this->cat->radio_status($id);

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{


				if($row->prop_mode == "SAT") {
					$uplink_freq = $row->uplink_freq;
					$downlink_freq = $row->downlink_freq;

					$power = $row->power;

					$prop_mode = $row->prop_mode;

					// Check Mode
					if(strtoupper($row->uplink_mode) == "FMN"){
						$mode = "FM";
					} else {
						$mode = strtoupper($row->uplink_mode);
					}

					// Get Satellite Name
					if($row->sat_name == "AO-07") {
						$sat_name = "AO-7";
					} elseif ($row->sat_name == "LILACSAT") {
						$sat_name = "CAS-3H";
					} else {
						$sat_name =  strtoupper($row->sat_name);
					}

					// Get Satellite Mode
					$uplink_mode = $this->get_mode_designator($row->uplink_freq);
					$downlink_mode = $this->get_mode_designator($row->downlink_freq);

					if ($uplink_mode != "" && $downlink_mode != "") {
						$sat_mode = $uplink_mode."/".$downlink_mode;
					}

				} else {
					$frequency = $row->frequency;

					$power = $row->power;

					$prop_mode = $row->prop_mode;

					// Check Mode
					if(strtoupper($row->mode) == "FMN"){
						$mode = "FM";
					} else {
						$mode = strtoupper($row->mode);
					}

					$sat_name = "";
					$sat_mode = "";
				}

				// Calculate how old the data is in minutes
				$datetime1 = new DateTime("now", new DateTimeZone('UTC')); // Today's Date/Time
				$datetime2 = new DateTime($row->timestamp, new DateTimeZone('UTC'));
				$interval = $datetime1->diff($datetime2);

				$minutes = $interval->days * 24 * 60;
				$minutes += $interval->h * 60;
				$minutes += $interval->i;

				$updated_at = $minutes;

				// Return Json data
				if ($prop_mode == "SAT") {
					echo json_encode(array(
						"uplink_freq" => $uplink_freq,
						"downlink_freq" => $downlink_freq,
						"mode" => $mode,
						"satmode" => $sat_mode,
						"satname" => $sat_name,
						"power" => $power,
						"prop_mode" => $prop_mode,
						"updated_minutes_ago" => $updated_at,
					), JSON_PRETTY_PRINT);
				} else {
					echo json_encode(array(
						"frequency" => $frequency,
						"mode" => $mode,
						"power" => $power,
						"prop_mode" => $prop_mode,
						"updated_minutes_ago" => $updated_at,
					), JSON_PRETTY_PRINT);
				}
			}
		}

	}

	function get_mode_designator($frequency)
	{
		if ($frequency > 21000000 && $frequency < 22000000)
			return "H";
		if ($frequency > 28000000 && $frequency < 30000000)
			return "A";
		if ($frequency > 144000000 && $frequency < 147000000)
			return "V";
		if ($frequency > 432000000 && $frequency < 438000000)
			return "U";
		if ($frequency > 1240000000 && $frequency < 1300000000)
			return "L";
		if ($frequency > 2320000000 && $frequency < 2450000000)
			return "S";
		if ($frequency > 3400000000 && $frequency < 3475000000)
			return "S2";
		if ($frequency > 5650000000 && $frequency < 5850000000)
			return "C";
		if ($frequency > 10000000000 && $frequency < 10500000000)
			return "X";
		if ($frequency > 24000000000 && $frequency < 24250000000)
			return "K";
		if ($frequency > 47000000000 && $frequency < 47200000000)
			return "R";

		return "";
	}

	function delete($id) {
		// Check Auth
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$this->load->model('cat');

		$this->cat->delete($id);

		$this->session->set_flashdata('message', 'Radio Profile Deleted');

		redirect('radio');

	}
}


?>
