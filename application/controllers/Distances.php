<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distances extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        // Render Page
        $data['page_title'] = "Distances Worked";

        $this->load->model('bands');
        $this->load->model('gridmap_model');

        $data['bands_available'] = $this->bands->get_worked_bands_distances();
        $data['sats_available'] = $this->bands->get_worked_sats();
        $data['modes'] = $this->gridmap_model->get_worked_modes();
        $data['powers'] = $this->bands->get_worked_powers();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('distances/index');
        $this->load->view('interface_assets/footer');
    }

    public function get_distances(){
        // POST data
        $postData = $this->input->post();

        //load model
        $this->load->model('Distances_model');

        if ($this->session->userdata('user_measurement_base') == NULL) {
            $measurement_base = $this->config->item('measurement_base');
        }
        else {
            $measurement_base = $this->session->userdata('user_measurement_base');
        }

        // get data
        $data = $this->Distances_model->get_distances($postData, $measurement_base);

        return json_encode($data);
    }

    public function test_distance(){
        // POST data
        $postdata['band'] = "sat";
        $postdata['sat'] = "All";

        //load model
        $this->load->model('Distances_model');

        if ($this->session->userdata('user_measurement_base') == NULL) {
            $measurement_base = $this->config->item('measurement_base');
        }
        else {
            $measurement_base = $this->session->userdata('user_measurement_base');
        }

        // get data
        $data = $this->Distances_model->get_distances($postdata, $measurement_base);

        return json_encode($data);
    }

	public function getDistanceQsos(){
		$this->load->model('distances_model');

		$distance = $this->security->xss_clean($this->input->post('distance'));
		$band = $this->security->xss_clean($this->input->post('band'));
		$sat = $this->security->xss_clean($this->input->post('sat'));
		$mode = $this->security->xss_clean($this->input->post('mode'));
		$power = $this->security->xss_clean($this->input->post('pwr'));

		$data['results'] = $this->distances_model->qso_details($distance, $band, $sat, $mode, $power);

        // Render Page
        if (strtolower($band) == 'all') $band = lang('statistics_distances_bands_all');
        (strtolower($mode) == 'all') ? $mode = lang('statistics_distances_modes_all') : $mode = strtoupper($mode);
        switch (strtolower($power)) {
            case 'all':
                $power = lang('statistics_distances_bands_all');
                break;
            case '':
                $power = lang('general_word_undefined');
                break;
            default:
                $power .= 'W';
        }

		$data['page_title'] = "Log View - " . $distance;
		$data['filter'] = lang('statistics_distances_qsos_with') . " " . $distance . lang('statistics_distances_and_band') . " " . $band . lang('statistics_distances_and_mode') . $mode . lang('statistics_distances_and_power') . $power;
		$this->load->view('awards/details', $data);
	}
}
