<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 

	Provides outputted kml files for use with Google Map services 
	All maps are stored within /kml in the root directory

*/

class Kml extends CI_Controller {

    public function index() {
        $this->load->model('user_model');
        $this->load->model('modes');
        $this->load->model('logbook_model');
		$this->load->model('bands');

        if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        $data['worked_bands'] = $this->bands->get_worked_bands(); // Used in the view for band select
        $data['modes'] = $this->modes->active(); // Used in the view for mode select
        $data['dxcc'] = $this->logbook_model->fetchDxcc(); // Used in the view for dxcc select

        $data['page_title'] = "KML Export";

        $this->load->view('interface_assets/header', $data);
        $this->load->view('kml/index');
        $this->load->view('interface_assets/footer');
    }

	public function export() {
		// Load Libraries
		$this->load->library('qra');
		$this->load->helper('file');

		// Load Database connections
		$this->load->model('logbook_model');

		// Parameters
        $band = $this->input->post('band');
        $mode = $this->input->post('mode');
        $dxcc = $this->input->post('dxcc_id');
        $cqz = $this->input->post('cqz');
        $propagation = $this->input->post('prop_mode');
        $fromdate = $this->input->post('fromdate');
        $todate = $this->input->post('todate');

		// Get QSOs with Valid QRAs
		$qsos = $this->logbook_model->kml_get_all_qsos($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

		$output = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$output .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\">";

		$output .= "<Document>";

		foreach ($qsos->result() as $row) {
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				
				$lat = $stn_loc[0];
				$lng = $stn_loc[1];
			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc_entities
					WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1 
				');
					foreach ($query->result() as $dxcc) {
						$lat = $dxcc->lat;
						$lng = $dxcc->long;
					}
			}

			if (isset($lat) && isset($lng)) {
				$output .= "<Placemark>";

				$timestamp = strtotime($row->COL_TIME_ON); 

				$output .= "<name>".$row->COL_CALL."</name>";
				$output .= "<description><![CDATA[<p>Date/Time: ".date('Y-m-d H:i:s', ($timestamp))."<br/>Band: ".$row->COL_BAND."<br /></p>]]></description>";		
				$output .= "<Point>";
				$output .= "<coordinates>".$lng.",".$lat.",0</coordinates>";
				$output .= "</Point>";
				$output .= "</Placemark>";
			}
		}

		$output .= "</Document>";
		$output .= "</kml>";

        if (!file_exists('kml')) {
            mkdir('kml', 0755, true);
        }

		if ( ! write_file('kml/qsos.kml', $output)) {
		     echo 'Unable to write the file. Make sure the folder KML has write permissions.';
		}
		else {
		    header("Content-Disposition: attachment; filename=\"qsos.kml\"");
			echo $output;
		}

	}
}