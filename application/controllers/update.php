<?php
class Update extends CI_Controller {

	/*
		Controls Updating Elements of Cloudlog
		Functions:
			dxcc - imports the latest clublog cty.xml data
			lotw_users - imports lotw users
	*/
	
	public function index()
	{
		// Create frontend pages.
	}
	
	// Updates the DXCC & Exceptions from the Clublog Cty.xml file.
	public function dxcc() {
	
		// Load Migration data if any.
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
		
				// empty table
		$this->dxcc->empty_table("dxccexceptions");
		
		echo "<h2>Exceptions</h2>";
		
		echo "<table>";
		
		foreach ($xml_data->exceptions as $exceptions) {
			foreach ($exceptions->exception as $callsign) {
			
			echo "<tr>";
				echo "<td>".$callsign->call."</td>";
				echo "<td>".$callsign->entity."</td>";
			echo "</tr>";

				
				if(!$callsign->start) {
					$data = array(
						'prefix' => (string)  $callsign->call,
						'name' =>  (string) $callsign->entity,
						'cqz' => (string) $callsign->cqz,
						'ituz' => (string) $callsign->ituz,
						'cont' => (string) $callsign->cont,
						'long' => (string) $callsign->long,
						'lat' => (string) $callsign->lat
					);	
				} else {
				
					$startinfo = strtotime($callsign->start);
				
					if($startinfo) {
						$start = date('Y-m-d H:i:s',$startinfo);
					} else {
						$start = "";
					}
				
					$endinfo = strtotime($callsign->end);
				
					if($endinfo) {
						$end = date('Y-m-d H:i:s',$endinfo);
					} else {
						$end = "";
					}
				
					$data = array(
						'prefix' => (string) $callsign->call,
						'name' =>  (string) $callsign->entity,
						'cqz' => (string) $callsign->cqz,
						'ituz' => (string) $callsign->ituz,
						'cont' => (string) $callsign->cont,
						'long' => (string) $callsign->long,
						'lat' => (string) $callsign->lat,
						'start' => $start,
						'end' => $end
					);	
				}

				$this->db->insert('dxccexceptions', $data); 
				
			}
		}
		
		echo "<table>";
		
	}
	
	public function lotw_users() {
		// Load Database connectors
		$this->load->model('lotw');
		
		$this->lotw->empty_table("lotw_list");
		
		$lines = file('http://www.hb9bza.net/lotw/lotw1.txt');

		// Loop through our array, show HTML source as HTML source; and line numbers too.
		foreach ($lines as $line_num => $line) {
			 echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
			 
			 $data = array(
					'Callsign' => $line,
				);

			$this->db->insert('lotw_list', $data); 
			 
		}
	}
}
?>