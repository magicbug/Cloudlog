<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	This controller contains features for oqrs (Online QSL Request System)
*/

class Oqrs extends CI_Controller {

	function __construct() {
		parent::__construct();
		// Commented out to get public access
		// $this->load->model('user_model');
		// if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
		$this->load->model('oqrs_model');

		$data['stations'] = $this->oqrs_model->get_oqrs_stations();
		$data['page_title'] = "Log Search & OQRS";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('oqrs/index');
		$this->load->view('interface_assets/footer');
    }

	public function get_station_info() {
		$this->load->model('oqrs_model');
		$result = $this->oqrs_model->get_station_info($this->input->post('station_id'));

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function get_qsos() {
		$this->load->model('bands');
		$data['bands'] = $this->bands->get_worked_bands_oqrs($this->security->xss_clean($this->input->post('station_id')));
		
		$this->load->model('oqrs_model');
		$result = $this->oqrs_model->get_qsos($this->input->post('station_id'), $this->input->post('callsign'), $data['bands']);
		$data['callsign'] = $this->security->xss_clean($this->input->post('callsign'));
		$data['result'] = $result['qsoarray'];
		$data['qsocount'] = $result['qsocount'];

		$this->load->view('oqrs/result', $data);
	}

	public function not_in_log() {
		$data['page_title'] = "Log Search & OQRS";
		
		$this->load->model('bands');
		$data['bands'] = $this->bands->get_worked_bands_oqrs($this->security->xss_clean($this->input->post('station_id')));

		$this->load->view('oqrs/notinlogform', $data);
	}

	public function save_not_in_log() {
		$postdata = $this->input->post();
		$this->load->model('oqrs_model');
		$this->oqrs_model->save_not_in_log($postdata);
	}

	/*
	* Fetches data when the user wants to make a request form, and loads info via the view
	*/
	public function request_form() {
		$this->load->model('oqrs_model');
		$data['result'] = $this->oqrs_model->getQueryData($this->input->post('station_id'), $this->input->post('callsign'));
		$data['callsign'] = $this->security->xss_clean($this->input->post('callsign'));

		$this->load->view('oqrs/request', $data);
	}

	public function requests() {
		$data['page_title'] = "OQRS Requests";

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if ($logbooks_locations_array) {
			$location_list = "'".implode("','",$logbooks_locations_array)."'";
		} else {
            $location_list = null;
        }

		$this->load->model('oqrs_model');
		$data['result'] = $this->oqrs_model->getOqrsRequests($location_list);

		$this->load->view('interface_assets/header', $data);
		$this->load->view('oqrs/showrequests');
		$this->load->view('interface_assets/footer');
	}

	public function save_oqrs_request() {
		$postdata = $this->input->post();
		$this->load->model('oqrs_model');
		$this->oqrs_model->save_oqrs_request($postdata);
	}

	public function delete_oqrs_line() {
		$id = $this->input->post('id');
		$this->load->model('oqrs_model');
		$this->oqrs_model->delete_oqrs_line($id);
	}

	public function search_log() {
		$this->load->model('oqrs_model');
		$callsign = $this->input->post('callsign');

        $data['qsos'] = $this->oqrs_model->search_log($this->security->xss_clean($callsign));

		$this->load->view('qslprint/qsolist', $data);
	}

	public function search_log_time_date() {
		$this->load->model('oqrs_model');
		$time = $this->security->xss_clean($this->input->post('time'));
		$date = $this->security->xss_clean($this->input->post('date'));
		$mode = $this->security->xss_clean($this->input->post('mode'));
		$band = $this->security->xss_clean($this->input->post('band'));

        $data['qsos'] = $this->oqrs_model->search_log_time_date($time, $date, $band, $mode);

		$this->load->view('qslprint/qsolist', $data);
	}
}
