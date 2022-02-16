<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visitor extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

    function _remap($method) {
        if($method == "config") {
            $this->$method();
        }
        else {
            $this->index($method);
        }
    }

	/*
        This is the default function that is called when the user visits the root of the public controller
    */
	public function index($public_slug = NULL)
	{

        // If environment is set to development then show the debug toolbar
		if(ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE);
        }

        // Check slug passed and is valid
        if ($this->security->xss_clean($public_slug, TRUE) === FALSE)
        {
            // file failed the XSS test#
            log_message('error', '[Visitor] XSS Attack detected on public_slug '. $public_slug);
            show_404('Unknown Public Page.');
        } else {
            // Checked slug passed and clean
            log_message('info', '[Visitor] public_slug '. $public_slug .' loaded');

            echo $public_slug = $this->security->xss_clean($public_slug);

            // Check if the slug is contained in the station_logbooks table
            
        }
	}
	
}