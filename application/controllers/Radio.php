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
				echo "<td><a href=\"".site_url('radio/delete')."/".$row->id."\" ><img src=\"".base_url()."/images/delete.png\" width=\"16\" height=\"16\" alt=\"Delete\" /></a></td>" ;
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
		echo json_encode(array(
			"uplink_freq" => $this->frequency($id, false),
			"downlink_freq" => $this->frequency($id, true),
			"mode" => $this->mode($id),
			"satmode" => $this->satmode($id),
			"satname" => $this->satname($id),
		), JSON_PRETTY_PRINT);
	}


	function frequency_downlink($id) {
		return $this->frequency($id, true);
	}

	function frequency($id, $downlink = false) {

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
								if ($downlink)
									return strtoupper($row->downlink_freq);
								else	
									return strtoupper($row->uplink_freq);
							}
						}
					} else {
						if ($downlink)
							return "";
						else	
							return strtoupper($row->frequency);	
					}
				}
			}
		return ""; 
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
					$uplink_mode = ""; 
					$downlink_mode = ""; 

					if ($row->uplink_freq > 144000000 && $row->uplink_freq < 147000000) {
						$uplink_mode = "V";
					} elseif ($row->uplink_freq > 432000000 && $row->uplink_freq < 438000000) {
						$uplink_mode = "U";
					} elseif ($row->uplink_freq > 28000000 && $row->uplink_freq < 30000000) {
						$uplink_mode = "A";
					}

					if ($row->downlink_freq > 144000000 && $row->downlink_freq < 147000000) {
						$downlink_mode = "V";
					} elseif ($row->downlink_freq > 432000000 && $row->downlink_freq < 438000000) {
						$downlink_mode = "U";
					} elseif ($row->downlink_freq > 28000000 && $row->downlink_freq < 30000000) {
						$downlink_mode = "A";
					}
					
					if ($uplink_mode != "" && $downlink_mode != "")
						return $uplink_mode."/".$downlink_mode;
				}
			}

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