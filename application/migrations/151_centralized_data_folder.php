<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
*   Create data folder
*/

class Migration_centralized_data_folder extends CI_Migration {

    public function up()
    {
        $_deprecied_path_eqsl = realpath(APPPATH.'../').'/images/eqsl_card_images';
        $_deprecied_path_qslcard = realpath(APPPATH.'../').'/assets/qslcard';
        $_centralized_folder_data = "storage";
        $eqsl_images_folder = "eqsl_card";
        $qsl_images_folder = "qsl_card";

        log_message('info','[centralized_data_folder][UP] Starting...');
        $this->load->model('Eqsl_images');
        $this->load->model('Qsl_model');

        $_centralized_data_path = realpath(APPPATH.'../').'/'.$_centralized_folder_data;
        $_centralized_folder_eqsl = $_centralized_data_path.'/'.$eqsl_images_folder;
        $_centralized_folder_qslcard = $_centralized_data_path.'/'.$qsl_images_folder;
        $_configphp_file = realpath(APPPATH).'/config/config.php';

        // -------------------------- create main/eqsl/qsl folder & add to config file //
        if (!is_dir($_centralized_data_path)) { 
            mkdir($_centralized_data_path, 0775, true); 
            log_message('debug','[centralized_data_folder] new centralized data folder "'.$_centralized_data_path.'" created.');
        } else log_message('debug','[centralized_data_folder] "'.$_centralized_data_path.'" exist.');
        if (!is_dir($_centralized_folder_eqsl)) { 
            mkdir($_centralized_folder_eqsl, 0775, true); 
            log_message('debug','[centralized_data_folder] new eQsl folder "'.$_centralized_folder_eqsl.'" created.');
        } else log_message('debug','[centralized_data_folder] "'.$_centralized_folder_eqsl.'" exist.');
        if (!is_dir($_centralized_folder_qslcard)) { 
            mkdir($_centralized_folder_qslcard, 0775, true); 
            log_message('debug','[centralized_data_folder] new Qsl Card folder "'.$_centralized_folder_qslcard.'" created.');
        } else log_message('debug','[centralized_data_folder] "'.$_centralized_folder_qslcard.'" exist.');

        $_configphp_content = '/*
|--------------------------------------------------------------------------
| Centralized data folder 
|--------------------------------------------------------------------------
|
| Define the data folder for save eqsl, card image ...  
| Root is $config[\'directory\']
*/
$config[\'centralized_data_folder\'] = "'.$_centralized_folder_data.'"; 

';
        file_put_contents($_configphp_file, $_configphp_content, FILE_APPEND);

        // -------------------------- move data from deprecied folder (eqsl/qsl) //
        $_data = array(
            'eQsl'=>array( 'k'=>'eQsl', 'path_new'=>$_centralized_folder_eqsl, 'path_deprecied'=>$_deprecied_path_eqsl ),
            'qslc'=>array( 'k'=>'qslc', 'path_new'=>$_centralized_folder_qslcard, 'path_deprecied'=>$_deprecied_path_qslcard )
        );
        foreach($_data as $k=>$_what) {
            log_message('debug','[centralized_data_folder]['.$_what['k'].'] Move starting ...');
            $count=0;
            if (is_dir($_what['path_deprecied'])) {
                $dir_array = array_diff(scandir($_what['path_deprecied']), array('..', '.'));
                log_message('debug','[centralized_data_folder]['.$_what['k'].'] '.count($dir_array).' files found in "'.$_what['path_deprecied'].'"');
                if (!rename($_what['path_deprecied'], $_what['path_new'])) { 
                    log_message('error','[centralized_data_folder]['.$_what['k'].'] ERROR during moving files between "'.$_what['path_deprecied'].'" and "'.$_what['path_new'].'"');
                } else {
                    $dir_array = array_diff(scandir($_what['path_new']), array('..', '.'));
                    log_message('debug','[centralized_data_folder]['.$_what['k'].'] --> Move is finished. '.count($dir_array).' files was moved in "'.$_what['path_new'].'"');
                }
            } else log_message('error','[centralized_data_folder]['.$_what['k'].'] ERROR folder "'.$_what['path_deprecied'].'" NOT exist');
        }
        log_message('info','[centralized_data_folder][UP] End');
    }

    public function down()
    {
        log_message('debug','[centralized_data_folder][DOWN]');
    }


}