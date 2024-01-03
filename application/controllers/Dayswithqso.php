<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dayswithqso extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    public function index()
    {
        $this->load->model('dayswithqso_model');
        // Render Page
        $data['page_title'] = lang('statistics_days_with_qso');

        $data['result'] = $this->dayswithqso_model->getDaysWithQso();
        $data['streaks'] = $this->dayswithqso_model->getLongestStreak();
        $data['currentstreak'] = $this->dayswithqso_model->getCurrentStreak();
        $data['almostcurrentstreak'] = $this->dayswithqso_model->getAlmostCurrentStreak();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('dayswithqso/index');
        $this->load->view('interface_assets/footer');
    }

    public function get_days(){

        //load model
        $this->load->model('dayswithqso_model');

        // get data
        $data = $this->dayswithqso_model->getDaysWithQso();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}