<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dxcluster extends CI_Controller {

	/* Controls the functions for interacting with the cluster */


       public function __construct()
	   {
			parent::__construct();
			
			if (isDomainAvailible('http://www.dxcluster.co.uk')) {
				// internet is available
			}
			else {
				show_error('DX Cluster isnt available without internet access', '500');
			}
	   }

	/* Loads default view */
	public function index()
	{
			$data['page_title'] = "DX Cluster";

			$this->load->view('layout/header', $data);
			$this->load->view('dxcluster/main');
			$this->load->view('layout/footer');
	}

	/* loads custom spots based on band */
	public function custom($band)
	{
			$data['page_title'] = "DX Cluster";

			$data['band'] = $band;

			$this->load->view('layout/header', $data);
			$this->load->view('dxcluster/custom');
			$this->load->view('layout/footer');
	}

	/* returns formatted json for all spots */
	public function all_spots() {
		
		$jsonurl = "http://www.dxcluster.co.uk/api/all";
		
		$json = @file_get_contents($jsonurl,0,null,null);
		$json_output = json_decode($json);

		//print_r($json_output);
		$i = 0;	
		foreach ($json_output as $name => $value) {

			echo '<tr class="tr'.($i & 1).'">';
				echo "<td class=\"time\">".$value->mytime."</td>";
				echo "<td class=\"callsign\">".$value->call."</td>";
				echo "<td class=\"freq\">".$value->freq."</td>";
				echo "<td class=\"dxcallsgin\">".$value->dxcall."</td>";
				echo "<td class=\"comment\">".htmlspecialchars($value->comment)."</td>";
			echo "</tr>";
			$i++; 
		}
	}

	/* returns formatted json for custom spots */
	public function custom_spots($band) {
		
		$jsonurl = "http://www.dxcluster.co.uk/api/data_band/".$band;
		
		$json = @file_get_contents($jsonurl,0,null,null);
		$json_output = json_decode($json);

		//print_r($json_output);
		$i = 0;	
		foreach ($json_output as $name => $value) {

			echo '<tr class="tr'.($i & 1).'">';
				echo "<td class=\"time\">".$value->mytime."</td>";
				echo "<td class=\"callsign\">".$value->call."</td>";
				echo "<td class=\"freq\">".$value->freq."</td>";
				echo "<td class=\"dxcallsgin\">".$value->dxcall."</td>";
				echo "<td class=\"comment\">".htmlspecialchars($value->comment)."</td>";
			echo "</tr>";
			$i++; 
		}
	}
}

function isDomainAvailible($domain)
	   {
			   //check, if a valid url is provided
			   if(!filter_var($domain, FILTER_VALIDATE_URL))
			   {
					   return false;
			   }

			   //initialize curl
			   $curlInit = curl_init($domain);
			   curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
			   curl_setopt($curlInit,CURLOPT_HEADER,true);
			   curl_setopt($curlInit,CURLOPT_NOBODY,true);
			   curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

			   //get answer
			   $response = curl_exec($curlInit);

			   curl_close($curlInit);

			   if ($response) return true;

			   return false;
	   }



/* End of file dxcluster.php */