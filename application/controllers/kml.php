<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 

	Provides outputted kml files for use with Google Map services 
	All maps are stored within /kml in the root directory

*/

class Kml extends CI_Controller {

	public function index()
	{
		// Load Librarys
		$this->load->library('qra');
		$this->load->helper('file');

		// Load Database connections
		$this->load->model('logbook_model');

		// Get QSOs with Valid QRAs
		$qsos = $this->logbook_model->kml_get_all_qsos();

		//header('Content-type: text/xml');
		//header("Cache-Control: no-cache");
		
		$output = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$output .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\">";

		$output .= "<Document>";

		foreach ($qsos->result() as $row)
		{
			$output .= "<Placemark>";
			//print_r($row);
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				
				$lat = $stn_loc[0];
				$lng = $stn_loc[1];
			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc
					WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1 
				');

				foreach ($query->result() as $dxcc) {
					$lat = $dxcc->lat;
					$lng = $dxcc->long;
				}
			}

			$timestamp = strtotime($row->COL_TIME_ON); 


			$output .= "<name>".$row->COL_CALL."</name>";
			$output .= "<description><![CDATA[<p>Date/Time: ".date('Y-m-d H:i:s', ($timestamp))."<br/>Band: ".$row->COL_BAND."<br /></p>]]></description>";		
			$output .= "<Point>";
	  		$output .= "<coordinates>".$lng.",".$lat.",0</coordinates>";
			$output .= "</Point>";
			$output .= "</Placemark>";
		}


		$output .= "</Document>";
		$output .= "</kml>";

		if ( ! write_file('kml/qsos.kml', $output))
		{
		     echo 'Unable to write the file - Make the folder KML has write permissions.';
		}
		else
		{
		    header("Content-Disposition: attachment; filename=\"qsos.kml\"");
			echo $output;
		}

	}
}