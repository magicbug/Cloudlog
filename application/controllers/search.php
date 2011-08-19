<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	public function index()
	{
        $this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) {
            if($this->user_model->validate_session()) {
                $this->user_model->clear_session();
                show_error('Access denied<p>Click <a href="'.site_url('user/login').'">here</a> to log in as another user', 403);
            } else {
                redirect('user/login');
            }
        }
	
		$this->load->view('layout/header');
		$this->load->view('search/main');
		$this->load->view('layout/footer');
	}
}
