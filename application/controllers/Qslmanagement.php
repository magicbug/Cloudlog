<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This controller will contain features for managing incoming QSL cards
*/

class Qslmanagement extends CI_Controller {

        public function index()
        {
			$data['page_title'] = "QSL Card Management";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('qslmanagement/index');
			$this->load->view('interface_assets/footer');
        }
}