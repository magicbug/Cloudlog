<?php
class Update extends CI_Controller {

	/*
		Controls Updating Elements of Cloudlog
		Functions:
			dxcc
			dxcc_exceptions
	*/
	
	public function index()
	{
		echo 'show fancy html page';
	}


	public function download() {
	}

	// Updates the DXCC
	public function dxcc() {
	
	$this->load->library('migration');

	if ( ! $this->migration->latest())
	{
		show_error($this->migration->error_string());
	}
	
		// Download latest file.
		$url = "https://secure.clublog.org/cty.php?api=a11c3235cd74b88212ce726857056939d52372bd";
		
		$gz = gzopen($url, 'r');
		$data = "";
		while (!gzeof($gz)) {
		  $data .= gzgetc($gz);
		}
		gzclose($gz);
		
		
		file_put_contents('./updates/cty.xml', $data);
	
	
		// Set timeout to unlimited
		set_time_limit(0);
	
		// Load Database connectors
		$this->load->model('dxcc');

		// Load the cty file
		$xml_data = simplexml_load_file("updates/cty.xml");
		
		$this->dxcc->empty_table("dxcc");
		
		echo "<h2>Prefix List</h2>";
		
		echo "<table>";
		echo "<tr>";
		echo "<td>Prefix</td>";
		echo "<td>Country Name</td>";
		echo "<td>DXCC Expire Date</td>";
		echo "</tr>";
		
		foreach ($xml_data->prefixes as $prefixs) {
			foreach ($prefixs->prefix as $callsign) {
				$endinfo = strtotime($callsign->end);
				
				if($endinfo) {
					$end_date = date('Y-m-d H:i:s',$endinfo);
				} else {
					$end_date = "";
				}
			
				if(!$callsign->cqz) {
					$data = array(
					   'prefix' => (string) $callsign->call,
					   'name' =>  (string) $callsign->entity,
					);
				} else {
					$data = array(
					   'prefix' => (string)  $callsign->call,
					   'name' =>  (string) $callsign->entity,
					   'cqz' => (string) $callsign->cqz,
					   'ituz' => (string) $callsign->ituz,
					   'cont' => (string) $callsign->cont,
					   'long' => (string) $callsign->long,
					   'lat' => (string) $callsign->lat,
						 'end_date' => $end_date,
					);	
				}
			
				echo "<tr>";
				echo "<td>".$callsign->call."</td>";
				echo "<td>".ucwords(strtolower($callsign->entity))."</td>";
				echo "<td>".$end_date."</td>";
				echo "<td>".$callsign->deleted."</td>";
				echo "</tr>";

				$this->db->insert('dxcc', $data); 
			}
		}
		echo "<table>";
	}

	public function dxcc_exceptions()
	{
		set_time_limit(0);
		// Load Database connectors
		$this->load->model('dxcc');

		// Load the cty file
		$xml_data = simplexml_load_file("updates/cty.xml");

		// empty table
		$this->dxcc->empty_table("dxccexceptions");
		
		echo "<h2>Exceptions</h2>";
		foreach ($xml_data->exceptions as $exceptions) {
			foreach ($exceptions->exception as $callsign) {
				echo $callsign->call." ".$callsign->entity;
			
				
				if(!$callsign->start) {
					$data = array(
					   'prefix' => (string)  $callsign->call,
					   'name' =>  (string) $callsign->entity,
					   'cqz' => $callsign->cqz,
					   'ituz' => $callsign->ituz,
					   'cont' => (string) $callsign->cont,
					   'long' => $callsign->long,
					   'lat' => $callsign->lat
					);	
				} else {
				
					$start = date('Y-m-d h:i', $timestamp);
					$end = date('Y-m-d h:i', $timestamp);
				
					$data = array(
					   'prefix' => (string) $callsign->call,
					   'name' =>  (string) $callsign->entity,
					   'cqz' => $callsign->cqz,
					   'ituz' => $callsign->ituz,
					   'cont' => (string) $callsign->cont,
					   'long' => $callsign->long,
					   'lat' => $callsign->lat,
					   'start' => $start,
					   'end' => $end
					);	
				}

				$this->db->insert('dxccexceptions', $data); 

				echo " Inserted <br />";
				
			}
		}
	}
}
?>