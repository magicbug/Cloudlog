<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function index()
	{
		$this->load->library('qrz');

		$qrz_session_key = $this->qrz->set_session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
		
		echo $this->session->userdata('qrz_session_key');

		$data['callsign'] = $this->qrz->search("m3php", $this->session->userdata('qrz_session_key'));

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */