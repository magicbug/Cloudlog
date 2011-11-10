<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dxcluster extends CI_Controller {

	/* Controls the functions for interacting with the cluster */

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

/* End of file dxcluster.php */