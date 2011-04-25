<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logbook extends CI_Controller {


	function index()
	{
	
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/logbook/index/';
		$config['total_rows'] = $this->db->count_all('table_hrd_contacts_v01');
		$config['per_page'] = '25';
		$config['num_links'] = 6;
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
	
		$this->pagination->initialize($config);
	
		//load the model and get results
		$this->load->model('logbook_model');
		$data['results'] = $this->logbook_model->get_qsos($config['per_page'],$this->uri->segment(3));

	
		// load the view
		$this->load->view('layout/header');
		$this->load->view('view_log/index', $data);
		$this->load->view('layout/footer');
		
	}
}