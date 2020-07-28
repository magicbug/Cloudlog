<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
TODO
	- Update Edit
	- Store Radio Information
	- Upload to clublog (request api key)
*/

class QSO extends CI_Controller {

	public function index()
	{

		$this->load->model('cat');
		$this->load->model('stations');
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['active_station_profile'] = $this->stations->find_active();
		$data['notice'] = false;
		$data['stations'] = $this->stations->all();
		$data['radios'] = $this->cat->radios();
		$data['query'] = $this->logbook_model->last_custom('5');
		$data['dxcc'] = $this->logbook_model->fetchDxcc();
        $data['iota'] = $this->logbook_model->fetchIota();

		$this->load->library('form_validation');

		$this->form_validation->set_rules('start_date', 'Start Date', 'required');
		$this->form_validation->set_rules('start_time', 'Start Time', 'required');
		$this->form_validation->set_rules('end_date', 'End Date', 'required');
		$this->form_validation->set_rules('end_time', 'End Time', 'required');
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
			// Add QSO
			// $this->logbook_model->add();
			//change to create_qso function as add and create_qso duplicate functionality
			$this->logbook_model->create_qso();

			// Store Basic QSO Info for reuse
			// Put data in an array first, then call set_userdata once.
			// This solves the problem of CI dumping out the session
			// cookie each time set_userdata is called.
			// For more info, see http://bizhole.com/codeigniter-nginx-error-502-bad-gateway/
			// $qso_data = [
			// 18-Jan-2016 - make php v5.3 friendly!
			$qso_data = array(
				'start_date'         => $this->input->post('start_date'),
				'start_time'         => $this->input->post('start_time'),
				'end_date'           => $this->input->post('end_date'),
				'end_time'           => $this->input->post('end_time'),
				'time_stamp'         => time(),
				'band'               => $this->input->post('band'),
				'freq'               => $this->input->post('freq_display'),
				'freq_rx'            => $this->input->post('freq_display_rx'),
				'mode'               => $this->input->post('mode'),
				'sat_name'           => $this->input->post('sat_name'),
				'sat_mode'           => $this->input->post('sat_mode'),
				'prop_mode'          => $this->input->post('prop_mode'),
				'radio'              => $this->input->post('radio'),
				'station_profile_id' => $this->input->post('station_profile'),
				'transmit_power'     => $this->input->post('transmit_power')
			);
			// ];

			setcookie("radio", $qso_data['radio'], time()+3600*24*99);
			setcookie("station_profile_id", $qso_data['station_profile_id'], time()+3600*24*99);

			$this->session->set_userdata($qso_data);

			// If SAT name is set make it session set to sat
			if($this->input->post('sat_name')) {
        		$this->session->set_userdata('prop_mode', 'SAT');
    		}

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

	function edit() {

		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$query = $this->logbook_model->qso_info($this->uri->segment(3));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('time_on', 'Start Date', 'required');
		$this->form_validation->set_rules('time_off', 'End Date', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

        $data['qso'] = $query->row();
        $data['dxcc'] = $this->logbook_model->fetchDxcc();
        $data['iota'] = $this->logbook_model->fetchIota();

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

	function qsl_rcvd($id, $method) {
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

			// Update Logbook to Mark Paper Card Received

			$this->logbook_model->paperqsl_update($id, $method);

			$this->session->set_flashdata('notice', 'QSL Card: Marked as Received');

			redirect('logbook');
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


	function band_to_freq($band, $mode) {

		$this->load->library('frequency');

		echo $this->frequency->convent_band($band, $mode);
	}
}
