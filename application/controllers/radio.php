<?php

	class Radio extends CI_Controller {

	public function index()
	{
		
	}
	
	function status() {
		$this->load->model('cat');
		print_r($this->cat->status());
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