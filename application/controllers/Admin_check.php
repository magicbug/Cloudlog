<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_check extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        log_message('debug','['.get_called_class().'] Load class');
	}

	public function language_check()
	{
		if($this->user_model->validate_session() == 0) { redirect('user/login'); }
		$_language_folder = APPPATH.'language';
		$_language_reference = "english";
		$_language_full_array = array('language_ref'=>$_language_reference,$_language_reference=>null,'langs'=>null,'files'=>null);
		$_excluded_folders = array('..','.','.DS_Store','@eaDir',$_language_reference);

		// create reference //
		if (is_dir($_language_folder.'/'.$_language_reference)) {
			$dir_reference = array_diff(scandir($_language_folder.'/'.$_language_reference), $_excluded_folders);
			foreach($dir_reference as $_file) {
				$_ofile = fopen($_language_folder.'/'.$_language_reference.'/'.$_file,'r');
				while (!feof($_ofile)) {
				    $ligne = fgets($_ofile);
				    if (substr($ligne,0,6)=='$lang[') {
				    	preg_match('/\$lang\[\'(.*)\'] = [\'|"](.*)[\'|"];/', $ligne, $output_array); 
				    	if (isset($output_array[1]) && isset($output_array[2])) { 
				    		$_language_full_array[$_language_reference][$_file][$output_array[1]] = $output_array[2];
				    	}
				    }
				}
				fclose($_ofile);
			}
		}
		// create all directory //
		if (is_dir($_language_folder)) {
			$dir_langs = array_diff(scandir($_language_folder), $_excluded_folders);
			foreach($dir_langs as $_lang) {
				$_language_full_array['langs'][] = $_lang;
				$dir_files = array_diff(scandir($_language_folder.'/'.$_lang), $_excluded_folders);
				foreach($dir_files as $file) {
					if (file_exists($_language_folder.'/'.$_lang.'/'.$file)) {
						$_language_full_array['files'][$file]['langs'][] = $_lang;
						$_ofile = fopen($_language_folder.'/'.$_lang.'/'.$file,'r');
						while (!feof($_ofile)) {
						    $ligne = fgets($_ofile);
						    if (substr($ligne,0,6)=='$lang[') {
						    	preg_match('/\$lang\[\'(.*)\'] = [\'|"](.*)[\'|"];/', $ligne, $output_array); 
						    	if (isset($output_array[1]) && isset($output_array[2])) { 
						    		$_language_full_array['files'][$file]['translate'][$output_array[1]][$_lang] = $output_array[2];
						    	} 
						    }
						}
						fclose($_ofile);
					}
				}
			}
		}
		$data['language_full_array'] = $_language_full_array;
		$data['table_nb_td'] = count($_language_full_array['langs'])+2;
		$data['page_title'] = "Admin check language";
		$this->load->view('interface_assets/mini_header', $data);
		$this->load->view('admin/language_check');
		//$this->load->view('interface_assets/footer');
	}

	public function language_update() {
		$_post = $this->input->post();
		$_result = array();
        $_language_file = APPPATH.'language/'.$_post['admin_language_lang'].'/'.$_post['admin_language_file'];
        if ((!empty($_post['admin_language_tag'])) && (!empty($_post['admin_language_value']))) {
        	$_post['admin_language_value'] = xss_clean(addslashes(stripslashes($_post['admin_language_value'])));
	        if (file_exists($_language_file)) {
		        $_file_content = file_get_contents($_language_file);
		        if ($_post['admin_language_iscreate']==="1") {
					preg_match('/\$lang\[\''.$_post['admin_language_tag'].'\'] = ([)\'|"])(.*)[\'|"];/', $_file_content, $output_array);
			        if (isset($output_array[2])) {
						$_result['error'] = 'Tag "'.$_post['admin_language_tag'].'" not created, already exist ?!';
			    		log_message('error', 'Admin Check - Language : TTag "'.$_post['admin_language_tag'].'" not updaged, error during writing file "'.$_language_file.'"');	
			        } else {
						$_tag_new = '$lang[\''.$_post['admin_language_tag'].'\'] = "'.$_post['admin_language_value'].'";'.chr(13);
						if (file_put_contents($_language_file, $_tag_new, FILE_APPEND)===false) { 
				    		$_result['error'] = 'Tag "'.$_post['admin_language_tag'].'" not created, error during writing file !';
			    			log_message('error', 'Admin Check - Language : TTag "'.$_post['admin_language_tag'].'" not created, error during writing file "'.$_language_file.'"');		
						} else {
							$_result['ok'] = true;
						}
			        }
		        } else {
			        preg_match('/\$lang\[\''.$_post['admin_language_tag'].'\'] = ([)\'|"])(.*)[\'|"];/', $_file_content, $output_array);
			        if (isset($output_array[2])) {
			        	$_tag_exist = '$lang[\''.$_post['admin_language_tag'].'\'] = '.$output_array[1].$output_array[2].$output_array[1].';';
			        	$_tag_new = '$lang[\''.$_post['admin_language_tag'].'\'] = '.$output_array[1].$_post['admin_language_value'].$output_array[1].';';
			        	$_file_content = str_replace($_tag_exist, $_tag_new, $_file_content);
				        if (file_put_contents($_language_file, $_file_content)===false) { 
				    		$_result['error'] = 'Tag "'.$_post['admin_language_tag'].'" not updaged, error during writing file !';
			    			log_message('error', 'Admin Check - Language : TTag "'.$_post['admin_language_tag'].'" not updated, error during writing file "'.$_language_file.'"');		
				        } else {
				        	$_result['ok'] = true;
				        } 
			        } else {
			    		$_result['error'] = 'Tag "'.$_post['admin_language_tag'].'" not found on file !';
			    		log_message('error', 'Admin Check - Language : Tag "'.$_post['admin_language_tag'].'" not found on "'.$_language_file.'"');		        	
			        }		        	
		        }
	        } else {
		    	$_result['error'] = 'Language file not exist !';
		    	log_message('error', 'Admin Check - Language : Language file not exist : "'.$_language_file.'"');
	        }
	    } else {
	    	$_result['error'] = 'Tag or Value is empty !';
	    	log_message('error', 'Admin Check - Language : param is empty - admin_language_tag:"'.$_post['admin_language_tag'].'" - admin_language_value:"'.$_post['admin_language_value'].'"');
	    }
		header('Content-Type: application/json');
        echo json_encode($_result);
	}

	public function language_show_file() {
		$_post = $this->input->post();
		$_result = "";
        $_language_file = APPPATH.'language/'.$_post['admin_file_name'];
	    if (file_exists($_language_file)) {
			$_file_content = file_get_contents($_language_file);
			$_result = nl2br(htmlspecialchars($_file_content));
	    } else {
	    	$_result = 'Language file not exist !';
	    	log_message('error', 'Admin Check - Language : Language file not exist : "'.$_language_file.'"');
		}
		header('Content-Type: application/json');
        echo json_encode($_result);
	}
}
