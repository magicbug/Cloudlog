<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Controls the interaction with the QRZ.com Subscription based XML API.
*/


class OptionsLib {

    function __construct()
	{
        // Make Codeigniter functions available to library
        $CI =& get_instance();

        //Load the options model
        $CI->load->model('options_model');

        // Store returned array of autoload options
        $options_result = $CI->options_model->get_autoloads();

        // If results are greater than one
        if($options_result->num_rows() > 0) {
            // Loop through the array
            foreach ($options_result->result() as $item)
            {
                /*
                * Add option to the config system dynamicly option_name is prefixed by option_
                * you can then call $this->config->item('option_<option_name>') to get the item.
                */

                $CI->config->set_item('option_'.$item->option_name, $item->option_value);
            }
        }
    }

    // This returns a options value based on its name
    function get_option($option_name) {
        // Make Codeigniter functions available to library
        $CI =& get_instance();

        //Load the options model
        $CI->load->model('options_model');
        
        // call library function to get options value
        $options_result = $CI->options_model->item($option_name);

        // return option_value as a string
        return $options_result;
    }

    // This returns the global theme or the theme stored in the logged in users session data.
    function get_theme() {
        // Make Codeigniter functions available to library
        $CI =& get_instance();

        // If session data for stylesheet is set return choice
        if($CI->session->userdata('user_stylesheet')) {
            return $CI->session->userdata('user_stylesheet');
        } else {
            // Return the global choice.
            return $CI->config->item('option_theme');
        }

    }
}