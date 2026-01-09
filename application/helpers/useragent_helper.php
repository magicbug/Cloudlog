<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('cloudlog_user_agent')) {
    function cloudlog_user_agent(): string {
        $CI = &get_instance();
        if (!isset($CI->optionslib)) {
            $CI->load->library('optionslib');
        }
        $version = $CI->optionslib->get_option('version');
        $ua = 'Cloudlog';
        if (!empty($version)) {
            $ua .= '/' . $version;
        }
        return $ua;
    }
}
