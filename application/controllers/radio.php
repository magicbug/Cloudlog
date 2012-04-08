<?php

	class Radio extends CI_Controller {

	public function index()
	{
		// load the view
		$data['page_title'] = "Radio Status";

		$this->load->view('layout/header', $data);
		$this->load->view('radio/index');
		$this->load->view('layout/footer');
	}
	
	function status() {
		$this->load->model('cat');
		$query = $this->cat->status();
		if ($query->num_rows() > 0)
		{
			echo "<tr>";
				echo "<td>Radio</td>";
				echo "<td>Frequency</td>";
				echo "<td>Mode</td>";
				echo "<td>Timestamp</td>" ;
			echo "</tr>";
			foreach ($query->result() as $row)
			{
				echo "<tr>";
				echo "<td>".$row->radio."</td>";
				echo "<td>".$row->frequency."</td>";
				echo "<td>".$row->mode."</td>";
				echo "<td>".$row->timestamp."</td>" ;
				echo "</tr>";
			}
		} else {
			echo "<tr>";
				echo "<td colspan=\"4\">No CAT Interfaced radios found.</td>";
			echo "</tr>";
		}
			
	}
	
	function frequency($id) {
		//$this->db->where('radio', $result['radio']); 
			$this->db->select('frequency');
			$this->db->where('id', $id); 
			$query = $this->db->get('cat');
			
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
				{
					echo $row->frequency;
				}
			}
	}
	
	function mode($id) {
		//$this->db->where('radio', $result['radio']); 
			$this->db->select('mode');
			$this->db->where('id', $id); 
			$query = $this->db->get('cat');
			
			if ($query->num_rows() > 0)
			{
			   foreach ($query->result() as $row)
				{
					echo strtoupper($row->mode);
				}
			}
	}
}

?>