<?php

	class Radio extends CI_Controller {

	public function index()
	{
		// Check Auth
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		
		// load the view
		$data['page_title'] = "Radio Status";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('radio/index');
		$this->load->view('interface_assets/footer');
	}
	
	function status() {
	
		// Check Auth
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	
		$this->load->model('cat');
		$query = $this->cat->status();
		if ($query->num_rows() > 0)
		{
			echo "<tr class=\"titles\">";
				echo "<td>Radio</td>";
				echo "<td>Frequency</td>";
				echo "<td>Mode</td>";
				echo "<td>Timestamp</td>" ;
				echo "<td>Options</td>";
			echo "</tr>";
			foreach ($query->result() as $row)
			{
				echo "<tr>";
				echo "<td>".$row->radio."</td>";
				if($row->frequency != "0") {
					echo "<td>".$row->frequency."</td>";
				} else {
					echo "<td>".$row->downlink_freq." / ".$row->uplink_freq."</td>";
				}

				if($row->mode != "non") {
					echo "<td>".$row->mode."</td>";
				} else {
					echo "<td>".$row->uplink_mode."</td>";
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
		$frequency = $this->frequency($id); 

		echo json_encode(array(
			"uplink_freq" => $frequency['uplink_freq'],
			"downlink_freq" => $frequency['downlink_freq'],
			"mode" => $this->mode($id),
			"satmode" => $this->satmode($id),
			"satname" => $this->satname($id),
		), JSON_PRETTY_PRINT);
	}

	function frequency($id) {

		// Check Auth
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		//$this->db->where('radio', $result['radio']); 
			$this->db->select('frequency');
			$this->db->where('id', $id); 
			$query = $this->db->get('cat');
			
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
				{
					if( $row->frequency == "0") {
						$this->db->select('uplink_freq, downlink_freq');
						$this->db->where('id', $id); 
						$query = $this->db->get('cat');
						
						if ($query->num_rows() > 0)
						{
							foreach ($query->result() as $row)
							{
								return array("downlink_freq" => strtoupper($row->downlink_freq), "uplink_freq" => strtoupper($row->uplink_freq));
							}
						}
					} else {
						return array("downlink_freq" => "", "uplink_freq" => strtoupper($row->frequency));	
					}
				}
			}
		return array("downlink_freq" => "", "uplink_freq" => ""); 
	}
	
	function mode($id) {
	
		// Check Auth
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		//$this->db->where('radio', $result['radio']); 
			$this->db->select('mode, radio, uplink_mode');
			$this->db->where('id', $id); 
			$query = $this->db->get('cat');
			
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
				{
					if($row->radio != "SatPC32") {
						if(strtoupper($row->mode) == "FMN"){
							return "FM";
						} else {
							return strtoupper($row->mode);
						}
					} else {
						if(strtoupper($row->uplink_mode) == "FMN"){
							return "FM";
						} else {
							return strtoupper($row->uplink_mode);
						}
					}
				}
			}
		return "";
	}

	function satname($id) {
	
		// Check Auth
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		//$this->db->where('radio', $result['radio']); 
			$this->db->select('sat_name');
			$this->db->where('id', $id); 
			$query = $this->db->get('cat');
			
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
				{
					if($row->sat_name == "AO-07") {
						return "AO-7";
					} elseif ($row->sat_name == "LILACSAT") {
						return "CAS-3H";
					} else {
						return strtoupper($row->sat_name);
					}
				}
			}
		return "";
	}

	function satmode($id) {
	
		// Check Auth
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		//$this->db->where('radio', $result['radio']); 
			$this->db->select('uplink_freq, downlink_freq');
			$this->db->where('id', $id); 
			$query = $this->db->get('cat');
			
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
				{
					$uplink_mode = $this->get_mode_designator($row->uplink_freq); 
					$downlink_mode = $this->get_mode_designator($row->downlink_freq); 

					if ($uplink_mode != "" && $downlink_mode != "")
						return $uplink_mode."/".$downlink_mode;
				}
			}

			return ""; 
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