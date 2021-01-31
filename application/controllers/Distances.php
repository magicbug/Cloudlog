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

        $this->load->model('Distances_model');
        $data['bands_available'] = $this->Distances_model->get_worked_bands();
        $data['sats_available'] = $this->Distances_model->get_worked_sats();

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
}