<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	This controller contains features for oqrs (Online QSL Request System)
*/

class Oqrs extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->lang->load('lotw');
		$this->lang->load('eqsl');
		// Commented out to get public access
		// $this->load->model('user_model');
		// if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

    public function index() {
		$this->load->model('oqrs_model');

		$data['stations'] = $this->oqrs_model->get_oqrs_stations();
		$data['page_title'] = "Log Search & OQRS";
		$data['global_oqrs_text'] = $this->optionslib->get_option('global_oqrs_text');
		$data['groupedSearch'] = $this->optionslib->get_option('groupedSearch');

		$this->load->view('visitor/layout/header', $data);
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

	public function get_qsos_grouped() {
		$this->load->model('oqrs_model');
		$data['result'] = $this->oqrs_model->getQueryDataGrouped($this->input->post('callsign'));
		$data['callsign'] = $this->security->xss_clean($this->input->post('callsign'));

		$this->load->view('oqrs/request_grouped', $data);
	}

	public function not_in_log() {
		$data['page_title'] = "Log Search & OQRS";

		$this->load->model('bands');
		// $data['bands'] = $this->bands->get_worked_bands_oqrs($this->security->xss_clean($this->input->post('station_id')));

		$this->load->view('oqrs/notinlogform', $data);
	}

	public function save_not_in_log() {
		$station_ids = array();

		$postdata = $this->input->post();
		$this->load->model('oqrs_model');
		$this->oqrs_model->save_not_in_log($postdata);
		array_push($station_ids, xss_clean($this->input->post('station_id')));
		$this->alert_oqrs_request($postdata, $station_ids);
	}

	/*
	* Fetches data when the user wants to make a request form, and loads info via the view
	*/
	public function request_form() {
		$this->load->model('oqrs_model');
		$data['result'] = $this->oqrs_model->getQueryData($this->input->post('station_id'), $this->input->post('callsign'));
		$data['callsign'] = $this->security->xss_clean($this->input->post('callsign'));
		$data['qslinfo'] =  $this->oqrs_model->getQslInfo($this->input->post('station_id'));

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
		$data['stations'] = $this->oqrs_model->get_oqrs_stations();

		$this->load->view('interface_assets/header', $data);
		$this->load->view('oqrs/showrequests');
		$this->load->view('interface_assets/footer');
	}

	public function save_oqrs_request() {
		$postdata = $this->input->post();
		$this->load->model('oqrs_model');
		$station_ids = $this->oqrs_model->save_oqrs_request($postdata);
		$this->alert_oqrs_request($postdata, $station_ids);
	}

	public function save_oqrs_request_grouped() {
		$postdata = $this->input->post();
		$this->load->model('oqrs_model');
		$station_ids = $this->oqrs_model->save_oqrs_request_grouped($postdata);
		$this->alert_oqrs_request($postdata, $station_ids);
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

		$this->load->view('oqrs/qsolist', $data);
	}

	public function alert_oqrs_request($postdata, $station_ids) {
		foreach ($station_ids as $id) {
			$this->load->model('user_model');

			$email = $this->user_model->get_email_address($id);

			$this->load->model('oqrs_model');

			$sendEmail = $this->oqrs_model->getOqrsEmailSetting($id);

			if($email != "" && $sendEmail == "1") {

				$this->load->library('email');

				if($this->optionslib->get_option('emailProtocol') == "smtp") {
					$config = Array(
						'protocol' => $this->optionslib->get_option('emailProtocol'),
						'smtp_crypto' => $this->optionslib->get_option('smtpEncryption'),
						'smtp_host' => $this->optionslib->get_option('smtpHost'),
						'smtp_port' => $this->optionslib->get_option('smtpPort'),
						'smtp_user' => $this->optionslib->get_option('smtpUsername'),
						'smtp_pass' => $this->optionslib->get_option('smtpPassword'),
						'crlf' => "\r\n",
						'newline' => "\r\n"
					);

					$this->email->initialize($config);
				}

				$data['callsign'] = $this->security->xss_clean($postdata['callsign']);
				$data['usermessage'] = $this->security->xss_clean($postdata['message']);

				$message = $this->load->view('email/oqrs_request', $data,  TRUE);

				$this->email->from($this->optionslib->get_option('emailAddress'), $this->optionslib->get_option('emailSenderName'));
				$this->email->to($email);
				$this->email->reply_to($this->security->xss_clean($postdata['email']), strtoupper($data['callsign']));

				$this->email->subject('Cloudlog OQRS from ' . strtoupper($data['callsign']));
				$this->email->message($message);

				if (! $this->email->send()) {
					log_message('error', 'OQRS Alert! Email settings are incorrect.');
				} else {
					log_message('info', 'An OQRS request is made.');
				}
			}
		}
	}

	public function mark_oqrs_line_as_done() {
		$this->load->model('oqrs_model');
		$id = $this->security->xss_clean($this->input->post('id'));

        $this->oqrs_model->mark_oqrs_line_as_done($id);
	}

	public function search() {
		$this->load->model('oqrs_model');

		$searchCriteria = array(
			'user_id' => (int)$this->session->userdata('user_id'),
			'de' => xss_clean($this->input->post('de')),
			'dx' => xss_clean($this->input->post('dx')),
			'status' => xss_clean($this->input->post('status')),
			'oqrsResults' => xss_clean($this->input->post('oqrsResults')),
		);

		$qsos = $this->oqrs_model->searchOqrs($searchCriteria);

		header("Content-Type: application/json");
		print json_encode($qsos);
	}
}
