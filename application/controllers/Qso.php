<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
TODO
	- Update Edit
	- Store Radio Information
	- Upload to clublog (request api key)
*/

class QSO extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->lang->load('qso');

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{

		$this->load->model('cat');
		$this->load->model('stations');
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		$this->load->model('modes');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['active_station_profile'] = $this->stations->find_active();
		$data['notice'] = false;
		$data['stations'] = $this->stations->all();
		$data['radios'] = $this->cat->radios();
		$data['query'] = $this->logbook_model->last_custom('5');
		$data['dxcc'] = $this->logbook_model->fetchDxcc();
		$data['iota'] = $this->logbook_model->fetchIota();
		$data['modes'] = $this->modes->active();


		$this->load->library('form_validation');

		$this->form_validation->set_rules('start_date', 'Date', 'required');
		$this->form_validation->set_rules('start_time', 'Time', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add QSO";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('qso/index');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			// Store Basic QSO Info for reuse
			// Put data in an array first, then call set_userdata once.
			// This solves the problem of CI dumping out the session
			// cookie each time set_userdata is called.
			// For more info, see http://bizhole.com/codeigniter-nginx-error-502-bad-gateway/
			// $qso_data = [
			// 18-Jan-2016 - make php v5.3 friendly!
			$qso_data = array(
                'start_date' => $this->input->post('start_date'),
                'start_time' => $this->input->post('start_time'),
				'time_stamp' => time(),
				'band' => $this->input->post('band'),
				'band_rx' => $this->input->post('band_rx'),
				'freq' => $this->input->post('freq_display'),
				'freq_rx' => $this->input->post('freq_display_rx'),
				'mode' => $this->input->post('mode'),
				'sat_name' => $this->input->post('sat_name'),
				'sat_mode' => $this->input->post('sat_mode'),
				'prop_mode' => $this->input->post('prop_mode'),
				'radio' => $this->input->post('radio'),
				'station_profile_id' => $this->input->post('station_profile'),
				'transmit_power' => $this->input->post('transmit_power')
			);
			// ];

			setcookie("radio", $qso_data['radio'], time()+3600*24*99);
			setcookie("station_profile_id", $qso_data['station_profile_id'], time()+3600*24*99);

			$this->session->set_userdata($qso_data);

			// If SAT name is set make it session set to sat
			if($this->input->post('sat_name')) {
        		$this->session->set_userdata('prop_mode', 'SAT');
    		}

			// Add QSO
			// $this->logbook_model->add();
			//change to create_qso function as add and create_qso duplicate functionality
			$this->logbook_model->create_qso();

			// Get last 5 qsos
			$data['query'] = $this->logbook_model->last_custom('5');

			// Set Any Notice Messages
			$data['notice'] = "QSO Added";

			// Load view to create another contact
			$data['page_title'] = "Add QSO";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('qso/index');
			$this->load->view('interface_assets/footer');
		}
	}

	/*
	 * This is used for contest-logging and the ajax-call
	 */
	public function saveqso() {
        $this->load->model('logbook_model');
        $this->logbook_model->create_qso();
    }

	function edit() {

		$this->load->model('logbook_model');
		$this->load->model('user_model');
		$this->load->model('modes');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$query = $this->logbook_model->qso_info($this->uri->segment(3));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('time_on', 'Start Date', 'required');
		$this->form_validation->set_rules('time_off', 'End Date', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

        $data['qso'] = $query->row();
        $data['dxcc'] = $this->logbook_model->fetchDxcc();
        $data['iota'] = $this->logbook_model->fetchIota();
		$data['modes'] = $this->modes->all();

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('qso/edit', $data);
		}
		else
		{
			$this->logbook_model->edit();
			$this->session->set_flashdata('notice', 'Record Updated');
			$this->load->view('qso/edit_done');
		}
	}

    function edit_ajax() {

        $this->load->model('logbook_model');
        $this->load->model('user_model');
        $this->load->model('modes');
		$this->load->model('contesting_model');

        $this->load->library('form_validation');

        if(!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard');
        }

        $id = str_replace('"', "", $this->input->post("id"));
        $query = $this->logbook_model->qso_info($id);

        $data['qso'] = $query->row();
        $data['dxcc'] = $this->logbook_model->fetchDxcc();
        $data['iota'] = $this->logbook_model->fetchIota();
        $data['modes'] = $this->modes->all();
        $data['contest'] = $this->contesting_model->getActivecontests();

        $this->load->view('qso/edit_ajax', $data);
    }

    function qso_save_ajax() {
        $this->load->model('logbook_model');
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard');
        }

        $this->logbook_model->edit();
    }

	function qsl_rcvd($id, $method) {
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

			// Update Logbook to Mark Paper Card Received

			$this->logbook_model->paperqsl_update($id, $method);

			$this->session->set_flashdata('notice', 'QSL Card: Marked as Received');

			redirect('logbook');
	}

    function qsl_rcvd_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));
        $method = str_replace('"', "", $this->input->post("method"));

        $this->load->model('logbook_model');
        $this->load->model('user_model');

        header('Content-Type: application/json');

        if(!$this->user_model->authorize(2)) {
            echo json_encode(array('message' => 'Error'));

        }
        else {
            // Update Logbook to Mark Paper Card Received
            $this->logbook_model->paperqsl_update($id, $method);

            echo json_encode(array('message' => 'OK'));
        }
    }

    function qsl_requested_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));
        $method = str_replace('"', "", $this->input->post("method"));

        $this->load->model('logbook_model');
        $this->load->model('user_model');

        header('Content-Type: application/json');

        if(!$this->user_model->authorize(2)) {
            echo json_encode(array('message' => 'Error'));

        }
        else {
            // Update Logbook to Mark Paper Card Received
            $this->logbook_model->paperqsl_requested($id, $method);

            echo json_encode(array('message' => 'OK'));
        }
    }

	function qsl_ignore_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));
        $method = str_replace('"', "", $this->input->post("method"));

        $this->load->model('logbook_model');
        $this->load->model('user_model');

        header('Content-Type: application/json');

        if(!$this->user_model->authorize(2)) {
            echo json_encode(array('message' => 'Error'));

        }
        else {
            // Update Logbook to Mark Paper Card Received
            $this->logbook_model->paperqsl_ignore($id, $method);

            echo json_encode(array('message' => 'OK'));
        }
    }

	/* Delete QSO */
	function delete($id) {
		$this->load->model('logbook_model');

		$this->logbook_model->delete($id);

		$this->session->set_flashdata('notice', 'QSO Deleted Successfully');
		$data['message_title'] = "Deleted";
		$data['message_contents'] = "QSO Deleted Successfully";
		$this->load->view('messages/message', $data);


		// If deletes from /logbook dropdown redirect
		if (strpos($_SERVER['HTTP_REFERER'], '/logbook') !== false) {
		    redirect($_SERVER['HTTP_REFERER']);
		}
	}

    /* Delete QSO */
    function delete_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));

        $this->load->model('logbook_model');

        $this->logbook_model->delete($id);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'OK'));
        return;
    }


	function band_to_freq($band, $mode) {

		$this->load->library('frequency');

		echo $this->frequency->convent_band($band, $mode);
	}

	/*
	 * Function is used for autocompletion of SOTA in the QSO entry form
	 */
	public function get_sota() {
        $json = [];

        if(!empty($this->input->get("query"))) {
            $query = isset($_GET['query']) ? $_GET['query'] : FALSE;
            $sota = strtoupper($query);

            $file = 'assets/json/sota.txt';

            if (is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                $input = preg_quote($sota, '~');
                $reg = '~^'. $input .'(.*)$~';
                $result = preg_grep($reg, $lines);
                $json = [];
                $i = 0;
                foreach ($result as &$value) {
                    // Limit to 100 as to not slowdown browser too much
                    if (count($json) <= 100) {
                        $json[] = ["name"=>$value];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /*
	 * Function is used for autocompletion of DOK in the QSO entry form
	 */
    public function get_dok() {
        $json = [];

        if(!empty($this->input->get("query"))) {
            $query = isset($_GET['query']) ? $_GET['query'] : FALSE;
            $dok = strtoupper($query);

            $file = 'assets/json/dok.txt';

            if (is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                $input = preg_quote($dok, '~');
                $reg = '~^'. $input .'(.*)$~';
                $result = preg_grep($reg, $lines);
                $json = [];
                $i = 0;
                foreach ($result as &$value) {
                    // Limit to 100 as to not slowdown browser too much
                    if (count($json) <= 100) {
                        $json[] = ["name"=>$value];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /*
	 * Function is used for autocompletion of Counties in the station profile form
	 */
    public function get_county() {
        $json = [];

        if(!empty($this->input->get("query"))) {
            //$query = isset($_GET['query']) ? $_GET['query'] : FALSE;
            $county = $this->input->get("state");
            $cleanedcounty = explode('(', $county);
            $cleanedcounty = trim($cleanedcounty[0]);

            $file = 'assets/json/US_counties.csv';

            if (is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                $input = preg_quote($cleanedcounty, '~');
                $reg = '~^'. $input .'(.*)$~';
                $result = preg_grep($reg, $lines);
                $json = [];
                $i = 0;
                foreach ($result as &$value) {
                    $county = explode(',', $value);
                    // Limit to 100 as to not slowdown browser too much
                    if (count($json) <= 300) {
                        $json[] = ["name"=>$county[1]];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function get_sota_info() {
		$sota = xss_clean($this->input->post('sota'));
		$url = 'https://api2.sota.org.uk/api/summits/' . $sota;

		// Let's use cURL instead of file_get_contents
		// begin script
		$ch = curl_init();

		// basic curl options for all requests
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		// use the URL we built
		curl_setopt($ch, CURLOPT_URL, $url);

		$input = curl_exec($ch);
		$chi = curl_getinfo($ch);

		// Close cURL handle
		curl_close($ch);

		header('Content-Type: application/json');
		echo $input;
	}
}
