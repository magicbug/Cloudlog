<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*
	Controls the interaction with the QRZ.com Subscription based XML API.
*/


class OptionsLib {

    function __construct()
	{
        // Make Codeigniter functions available to library
        $CI =& get_instance();

	// Force Migration to run on every page load
    	$CI->load->library('Migration');
	    $CI->migration->current();

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
                if($item->option_name == "language") {
                    // language is a global internal config item there for we dont want to prefix it as an option
                    //$CI->config->set_item($item->option_name, $item->option_value);
                } else {
                    $CI->config->set_item('option_'.$item->option_name, $item->option_value);
                }
            }
        }
    }

    // This returns a options value based on its name
    function get_option($option_name) {
        // Make Codeigniter functions available to library
        $CI =& get_instance();
        if (strpos($option_name, 'option_') !== false) { 
            if(!$CI->config->item($option_name)) {
                 //Load the options model
                $CI->load->model('options_model');
                $removed_options_tag = trim($option_name, 'option_');
                // call library function to get options value
                $options_result = $CI->options_model->item($removed_options_tag);
                    
                // return option_value as a string
                return $options_result;
            } else {
                return $CI->config->item($option_name);
            }
        } else {
            if(!$CI->config->item($option_name)) {
                //Load the options model
               $CI->load->model('options_model');
               // call library function to get options value
               $options_result = $CI->options_model->item($option_name);
                   
               // return option_value as a string
               return $options_result;
           } else {
                return $CI->config->item($option_name);
           }
        }
    }

    // Function to save new option to options table
    function save($option_name, $option_value, $autoload) {
        // Make Codeigniter functions available to library
        $CI =& get_instance();

        //Load the options model
        $CI->load->model('options_model');
        
        // call library function to save update
        $result = $CI->options_model->save($option_name, $option_value, $autoload);

        // return True or False on whether its completed.
        return $result;
    }

    // Function to update options within the options table
    function update($option_name, $option_value, $auto_load = NULL) {
        // Make Codeigniter functions available to library
        $CI =& get_instance();

        //Load the options model
        $CI->load->model('options_model');
        
        // call library function to save update
        $result = $CI->options_model->update($option_name, $option_value, $auto_load);

        // return True or False on whether its completed.
        return $result;
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
