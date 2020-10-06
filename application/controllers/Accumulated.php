<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accumulated extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        // Render Page
        $data['page_title'] = "Accumulated statistics";

        $this->load->model('Accumulate_model');

        $data['worked_bands'] = $this->Accumulate_model->get_worked_bands();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('accumulate/index');
        $this->load->view('interface_assets/footer');
    }

    /*
     * Used for ajax-call in javascript to fetch the data and insert into table and chart
     */
    public function get_accumulated_data(){
        //load model
        $this->load->model('accumulate_model');
        $band = $this->input->post('Band');
        $award = $this->input->post('Award');

        // get data
        $data = $this->accumulate_model->get_accumulated_data($band, $award);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}