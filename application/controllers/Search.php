<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {


    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        if($this->config->item('public_search') != TRUE) {
            $this->load->model('user_model');
            if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
        }
    }

	public function index()
	{
		$data['page_title'] = "Search";

        $this->load->view('interface_assets/header', $data);
		$this->load->view('search/main');
        $this->load->view('interface_assets/footer');
	}

    // Filter is for advanced searching and filtering of the logbook
    public function filter() {
        $data['page_title'] = "Search & Filter Logbook";

        $this->load->library('form_validation');

        $this->load->model('Search_filter');

        $data['get_table_names'] = $this->Search_filter->get_table_columns();

        //print_r($this->Search_filter->get_table_columns());

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('interface_assets/header', $data);
            $this->load->view('search/filter');
            $this->load->view('interface_assets/footer');
        }
        else
        {
            $this->load->view('interface_assets/header', $data);
            $this->load->view('search/filter');
            $this->load->view('interface_assets/footer');
        }
    }
}
