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


	// Updates the DXCC
	public function dxcc() {
		// Set timeout to unlimited
		set_time_limit(0);
	
		// Load Database connectors
		$this->load->model('dxcc');

		// Load the cty file
		$xml_data = simplexml_load_file("updates/cty.xml");
		
		$this->dxcc->empty_table("dxcc");
		
		echo "<h2>Prefix List</h2>";
		foreach ($xml_data->prefixes as $prefixs) {
			foreach ($prefixs->prefix as $callsign) {
				echo $callsign->call." ".$callsign->entity;
				
				if(!$callsign->cqz) {
					$data = array(
					   'prefix' => (string)  $callsign->call,
					   'name' =>  (string) $callsign->entity,
					);
				} else {
					$data = array(
					   'prefix' => (string)  $callsign->call,
					   'name' =>  (string) $callsign->entity,
					   'cqz' => $callsign->cqz,
					   'ituz' => $callsign->ituz,
					   'cont' => (string) $callsign->cont,
					   'long' => $callsign->long,
					   'lat' => $callsign->lat
					);	
				}

				$this->db->insert('dxcc', $data); 

				echo " Inserted <br />";
			}
		}
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