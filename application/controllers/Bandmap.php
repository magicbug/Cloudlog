<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bandmap extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->load->model('bands');
	}

	function index() {
		$this->load->model('cat');
		$this->load->model('bands');
		$data['radios'] = $this->cat->radios();
		$data['bands'] = $this->bands->get_user_bands_for_qso_entry();

        $footerData = [];
		$footerData['scripts'] = [
			'assets/js/highcharts/highcharts.js',
			'assets/js/highcharts/timeline.js',
			'assets/js/highcharts/exporting.js',
			'assets/js/highcharts/accessibility.js',
			'assets/js/sections/bandmap.js',
		];

		$data['page_title'] = "DXCluster";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('bandmap/index');
		$this->load->view('interface_assets/footer', $footerData);
	}

	function list() {
		$this->load->model('cat');
		$this->load->model('bands');
		$data['radios'] = $this->cat->radios();
		$data['bands'] = $this->bands->get_user_bands_for_qso_entry();

		$footerData = [];
		$footerData['scripts'] = [
			'assets/js/moment.min.js',
			'assets/js/datetime-moment.js',
			'assets/js/sections/bandmap_list.js'
		];

		$CI =& get_instance();
		// Get Date format
		if($CI->session->userdata('user_date_format')) {
			// If Logged in and session exists
			$pageData['custom_date_format'] = $CI->session->userdata('user_date_format');
		} else {
			// Get Default date format from /config/cloudlog.php
			$pageData['custom_date_format'] = $CI->config->item('qso_date_format');
		}

		switch ($pageData['custom_date_format']) {
		case "d/m/y": $pageData['custom_date_format'] = 'DD/MM/YY'; break;
		case "d/m/Y": $pageData['custom_date_format'] = 'DD/MM/YYYY'; break;
		case "m/d/y": $pageData['custom_date_format'] = 'MM/DD/YY'; break;
		case "m/d/Y": $pageData['custom_date_format'] = 'MM/DD/YYYY'; break;
		case "d.m.Y": $pageData['custom_date_format'] = 'DD.MM.YYYY'; break;
		case "y/m/d": $pageData['custom_date_format'] = 'YY/MM/DD'; break;
		case "Y-m-d": $pageData['custom_date_format'] = 'YYYY-MM-DD'; break;
		case "M d, Y": $pageData['custom_date_format'] = 'MMM DD, YYYY'; break;
		case "M d, y": $pageData['custom_date_format'] = 'MMM DD, YY'; break;
		default: $pageData['custom_date_format'] = 'DD/MM/YYYY';
		}

		$data['page_title'] = "DXCluster";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('bandmap/list',$pageData);
		$this->load->view('interface_assets/footer', $footerData);
	}
}
