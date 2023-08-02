<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        // Global Lang File
        $this->lang->load('menu', $this->session->userdata('language'));
    }
}