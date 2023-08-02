<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LanguageSwitch {

    public function changeLanguage () {
        $site_lang = 'english';

        $ci =& get_instance();
        $ci->load->helper('language');

        $site_lang = $ci->session->userdata('language');
        if ($site_lang) {
            $files = $this->find($site_lang);
        } else {
            $files = $this->find('english');
        }
        if(isset($ci->lang->is_loaded)){
            for($i=0; $i<=sizeof($ci->lang->is_loaded); $i++){
                unset($ci->lang->is_loaded[$i]);
            }
        }

        foreach ($files as $file) {
            $langfile = str_replace('_lang.php', '', $file);
            $ci->lang->load($langfile, $site_lang);
        }
    }

    function find($language) {
        $existing_langs = array();
        $lang_path = APPPATH.'language'.'/'. $language;

        $results = scandir($lang_path);

        foreach ($results as $result) {
            if ($result === '.' or $result === '..' or $result === 'index.html') continue;
            $files[] = $result;
        }
        return $files;
    }

}
