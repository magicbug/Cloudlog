<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 

	Provides outputted kml files for use with Google Map services 
	All maps are stored within /kml in the root directory

*/

class Kmlexport extends CI_Controller {

    public function index() {
        $this->load->model('user_model');
        $this->load->model('modes');
        $this->load->model('logbook_model');
	$this->load->model('bands');

        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

        $data['worked_bands'] = $this->bands->get_worked_bands(); // Used in the view for band select
        $data['modes'] = $this->modes->active(); // Used in the view for mode select
        $data['dxcc'] = $this->logbook_model->fetchDxcc(); // Used in the view for dxcc select

        $data['page_title'] = "KML Export";

        $this->load->view('interface_assets/header', $data);
        $this->load->view('kml/index');
        $this->load->view('interface_assets/footer');
    }

	public function export() {
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		// Load Libraries
		$this->load->library('qra');
		$this->load->helper('download');

		// Load Database connections
		$this->load->model('logbook_model');

		// Parameters
        $band = $this->input->post('band');
        $mode = $this->input->post('mode');
        $dxcc = $this->input->post('dxcc_id');
        $cqz = $this->input->post('cqz');
        $propagation = $this->input->post('prop_mode');
        $fromdate = $this->input->post('from');
        $todate = $this->input->post('to');

		// Get QSOs with Valid QRAs
		$qsos = $this->logbook_model->kml_get_all_qsos($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate);

		$placemarks = '';
		if ($qsos) {
			foreach ($qsos->result() as $row) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				if (!is_array($stn_loc) || !isset($stn_loc[0], $stn_loc[1])) {
					continue;
				}

				$lat = $stn_loc[0];
				$lng = $stn_loc[1];
				$timestamp = strtotime($row->COL_TIME_ON);
				$call = htmlspecialchars((string) $row->COL_CALL, ENT_XML1 | ENT_QUOTES, 'UTF-8');
				$qso_band = htmlspecialchars((string) $row->COL_BAND, ENT_XML1 | ENT_QUOTES, 'UTF-8');
				$datetime = htmlspecialchars(date('Y-m-d H:i:s', $timestamp), ENT_XML1, 'UTF-8');

				$placemarks .= "    <Placemark>\n";
				$placemarks .= "      <name>".$call."</name>\n";
				$placemarks .= "      <description><![CDATA[<p>Date/Time: ".$datetime."<br/>Band: ".$qso_band."<br /></p>]]></description>\n";
				$placemarks .= "      <Point>\n";
				$placemarks .= "        <coordinates>".$lng.",".$lat.",0</coordinates>\n";
				$placemarks .= "      </Point>\n";
				$placemarks .= "    </Placemark>\n";
			}
		}

		// XML declaration and <kml> must be on separate lines. Google Earth rejects
		// a single-line file as an invalid root element (line 1, column 7).
		$output  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$output .= "<kml xmlns=\"http://www.opengis.net/kml/2.2\">\n";
		$output .= "  <Document>\n";
		$output .= "    <name>Cloudlog QSOs</name>\n";
		$output .= $placemarks;
		$output .= "  </Document>\n";
		$output .= "</kml>\n";

		force_download('qsos.kml', $output, TRUE);
	}
}
