<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
TODO
	- Update Edit
	- Store Radio Information
	- Upload to clublog (request api key)
*/

class QSO extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->lang->load('qso');

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function index()
	{
		$this->load->model('cat');
		$this->load->model('stations');
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		$this->load->model('modes');
        $this->load->model('bands');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['active_station_profile'] = $this->stations->find_active();
        
		$data['notice'] = false;
		$data['stations'] = $this->stations->all_of_user();
		$data['radios'] = $this->cat->radios();
		$data['query'] = $this->logbook_model->last_custom_paginated(5, 0);
		$data['total_rows'] = $this->logbook_model->last_custom_count();
		$data['total_pages'] = ceil($data['total_rows'] / 5);
		$data['current_page'] = 0;
		$data['limit'] = 5;
		$data['dxcc'] = $this->logbook_model->fetchDxcc();
		$data['iota'] = $this->logbook_model->fetchIota();
		$data['modes'] = $this->modes->active();
		$data['bands'] = $this->bands->get_user_bands_for_qso_entry();
		$data['user_default_band'] = $this->session->userdata('user_default_band');
		$data['sat_active'] = array_search("SAT", $this->bands->get_user_bands(), true);

        $remote_operation_option = $this->user_options_model->get_options(
            'remote_operation',
            array('option_name' => 'enabled', 'option_key' => 'value'),
            $this->session->userdata('user_id')
        )->row();
        $data['isRemoteOperationEnabled'] = isset($remote_operation_option->option_value)
            ? ((string)$remote_operation_option->option_value === 'true' || (string)$remote_operation_option->option_value === '1')
            : (bool)$this->session->userdata('isRemoteOperationEnabled');
		
		// Set user's preferred date format
		if($this->session->userdata('user_date_format')) {
			$data['user_date_format'] = $this->session->userdata('user_date_format');
		} else {
			$data['user_date_format'] = $this->config->item('qso_date_format');
		}

		// Measurement base for frontend bearing/distance calculations
		$data['measurement_base'] = $this->session->userdata('user_measurement_base') ?: $this->config->item('measurement_base');

		$this->load->library('form_validation');

		$this->form_validation->set_rules('start_date', 'Date', 'required');
		$this->form_validation->set_rules('start_time', 'Time', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');
		$this->form_validation->set_rules('band', 'Band', 'required');
		$this->form_validation->set_rules('mode', 'Mode', 'required');
		$this->form_validation->set_rules('locator', 'Locator', 'callback_check_locator');

        // [eQSL default msg] GET user options (option_type='eqsl_default_qslmsg'; option_name='key_station_id'; option_key=station_id) //
		$this->load->model('user_options_model');
		$options_object = $this->user_options_model->get_options('eqsl_default_qslmsg',array('option_name'=>'key_station_id','option_key'=>$data['active_station_profile']))->result();
		$data['qslmsg'] = (isset($options_object[0]->option_value))?$options_object[0]->option_value:'';

		// Load QSO form field visibility preferences
		$qso_fields_defaults = [
			'rst' => true, 'name' => true, 'qth' => true, 'locator' => true, 'comment' => true,
			'station_tab' => true, 'freq_tx' => true, 'freq_rx' => true, 'band_rx' => true,
			'transmit_power' => true, 'operator_callsign' => true,
			'general_tab' => true, 'iota' => true, 'sota' => true, 'wwff' => true, 'pota' => true,
			'sig' => true, 'dok' => true, 'usa_state' => true,
			'satellite_tab' => true, 'notes_tab' => true, 'qsl_tab' => true,
			'dxcluster_tab' => true,
		];
		$qso_form_options = $this->user_options_model->get_options('qso_form')->result();
		foreach ($qso_form_options as $qfo_item) {
			if ($qfo_item->option_key == 'visible' && array_key_exists($qfo_item->option_name, $qso_fields_defaults)) {
				$qso_fields_defaults[$qfo_item->option_name] = ($qfo_item->option_value == 'true');
			}
		}
		$data['qso_fields'] = $qso_fields_defaults;

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add QSO";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('qso/index');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			// Store Basic QSO Info for reuse
			// Put data in an array first, then call set_userdata once.
			// This solves the problem of CI dumping out the session
			// cookie each time set_userdata is called.
			// For more info, see http://bizhole.com/codeigniter-nginx-error-502-bad-gateway/
			// $qso_data = [
			// 18-Jan-2016 - make php v5.3 friendly!
			$qso_data = array(
                'start_date' => $this->input->post('start_date'),
                'start_time' => $this->input->post('start_time'),
                'end_time' => $this->input->post('end_time'),
				'time_stamp' => time(),
				'band' => $this->input->post('band'),
				'band_rx' => $this->input->post('band_rx'),
				'freq' => $this->input->post('freq_display'),
				'freq_rx' => $this->input->post('freq_display_rx'),
				'mode' => $this->input->post('mode'),
				'sat_name' => $this->input->post('sat_name'),
				'sat_mode' => $this->input->post('sat_mode'),
				'prop_mode' => $this->input->post('prop_mode'),
				'radio' => $this->input->post('radio'),
				'station_profile_id' => $this->input->post('station_profile'),
				'operator_callsign' => $this->input->post('operator_callsign'),
				'transmit_power' => $this->input->post('transmit_power')
			);

            $propMode = strtoupper(trim((string)($qso_data['prop_mode'] ?? '')));
            if ($propMode !== 'SAT') {
                $qso_data['sat_name'] = '';
                $qso_data['sat_mode'] = '';
            }
			// ];

			setcookie("radio", $qso_data['radio'], time()+3600*24*99);
			setcookie("station_profile_id", $qso_data['station_profile_id'], time()+3600*24*99);

			$this->session->set_userdata($qso_data);

			// If SAT name is set make it session set to sat
			if($this->input->post('sat_name')) {
        		$this->session->set_userdata('prop_mode', 'SAT');
    		}

			// Add QSO
			// $this->logbook_model->add();
			//change to create_qso function as add and create_qso duplicate functionality
			$this->logbook_model->create_qso();

			// Get last 5 qsos
			$data['query'] = $this->logbook_model->last_custom('5');

			// Set Any Notice Messages
			$data['notice'] = "QSO Added";

			// Load view to create another contact
			$data['page_title'] = "Add QSO";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('qso/index');
			$this->load->view('interface_assets/footer');
		}
	}

	/*
	 * This is used for contest-logging and the ajax-call
	 */
	public function saveqso() {
        $this->load->model('logbook_model');
        $this->logbook_model->create_qso();
    }

    /*
     * AJAX endpoint for QSO entry form to avoid full page reload on save.
     */
    public function ajax_saveqso() {
        $this->load->library('form_validation');
        $this->load->model('logbook_model');

        $this->form_validation->set_rules('start_date', 'Date', 'required');
        $this->form_validation->set_rules('start_time', 'Time', 'required');
        $this->form_validation->set_rules('callsign', 'Callsign', 'required');
        $this->form_validation->set_rules('band', 'Band', 'required');
        $this->form_validation->set_rules('mode', 'Mode', 'required');
        $this->form_validation->set_rules('locator', 'Locator', 'callback_check_locator');

        if ($this->form_validation->run() == FALSE) {
            $validation_errors = array();
            $fields = array('start_date', 'start_time', 'callsign', 'band', 'mode', 'locator');
            foreach ($fields as $field) {
                $field_error = form_error($field, '', '');
                if (!empty($field_error)) {
                    $validation_errors[$field] = strip_tags($field_error);
                }
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'error',
                    'message' => 'Please correct the form errors and try again.',
                    'validation_errors' => $validation_errors,
                )));
        }

        $qso_data = array(
            'start_date' => $this->input->post('start_date'),
            'start_time' => $this->input->post('start_time'),
            'end_time' => $this->input->post('end_time'),
            'time_stamp' => time(),
            'band' => $this->input->post('band'),
            'band_rx' => $this->input->post('band_rx'),
            'freq' => $this->input->post('freq_display'),
            'freq_rx' => $this->input->post('freq_display_rx'),
            'mode' => $this->input->post('mode'),
            'sat_name' => $this->input->post('sat_name'),
            'sat_mode' => $this->input->post('sat_mode'),
            'prop_mode' => $this->input->post('prop_mode'),
            'radio' => $this->input->post('radio'),
            'station_profile_id' => $this->input->post('station_profile'),
            'operator_callsign' => $this->input->post('operator_callsign'),
            'transmit_power' => $this->input->post('transmit_power')
        );

        $propMode = strtoupper(trim((string)($qso_data['prop_mode'] ?? '')));
        if ($propMode !== 'SAT') {
            $qso_data['sat_name'] = '';
            $qso_data['sat_mode'] = '';
        }

        setcookie("radio", $qso_data['radio'], time() + 3600 * 24 * 99);
        setcookie("station_profile_id", $qso_data['station_profile_id'], time() + 3600 * 24 * 99);

        $this->session->set_userdata($qso_data);

        if ($this->input->post('sat_name')) {
            $this->session->set_userdata('prop_mode', 'SAT');
        }

        $this->logbook_model->create_qso();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'ok',
                'message' => 'QSO Added',
            )));
    }

	function edit() {

		$this->load->model('logbook_model');
		$this->load->model('user_model');
		$this->load->model('modes');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		
		$qso_id = $this->uri->segment(3);
		
		// Check if user has write permission to this QSO
		if (!$this->logbook_model->check_qso_is_writable($qso_id)) {
			$this->session->set_flashdata('notice', 'You do not have permission to edit this QSO');
			redirect('dashboard');
		}
		
		$query = $this->logbook_model->qso_info($qso_id);

		$this->load->library('form_validation');

		$this->form_validation->set_rules('time_on', 'Start Date', 'required');
		$this->form_validation->set_rules('time_off', 'End Date', 'required');
		$this->form_validation->set_rules('callsign', 'Callsign', 'required');

        $data['qso'] = $query->row();
        $data['dxcc'] = $this->logbook_model->fetchDxcc();
        $data['iota'] = $this->logbook_model->fetchIota();
		$data['modes'] = $this->modes->all();

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('qso/edit', $data);
		}
		else
		{
			$this->logbook_model->edit();
			$this->session->set_flashdata('notice', 'Record Updated');
			$this->load->view('qso/edit_done');
		}
	}

    function winkeysettings() {

        // Load model Winkey
        $this->load->model('winkey');
        $this->load->model('stations');
        
        // Get active station profile
        $active_station_id = $this->stations->find_active();

        // call settings from model winkey
        $data['result'] = $this->winkey->settings($this->session->userdata('user_id'), $active_station_id);

        if ($data['result'] == false) {
            $this->load->view('qso/components/winkeysettings', $data);
        } else {
            $this->load->view('qso/components/winkeysettings_results', $data);
        }
    }

    function remoteoperationsettings() {
        $this->load->view('qso/components/remoteoperationsettings');
    }

    public function cwmacrosave(){
        try {
            // Get the data from the form with proper sanitization
            $function1_name = $this->input->post('function1_name', TRUE);
            $function1_macro = $this->input->post('function1_macro', TRUE);

            $function2_name = $this->input->post('function2_name', TRUE);
            $function2_macro = $this->input->post('function2_macro', TRUE);

            $function3_name = $this->input->post('function3_name', TRUE);
            $function3_macro = $this->input->post('function3_macro', TRUE);

            $function4_name = $this->input->post('function4_name', TRUE);
            $function4_macro = $this->input->post('function4_macro', TRUE);

            $function5_name = $this->input->post('function5_name', TRUE);
            $function5_macro = $this->input->post('function5_macro', TRUE);
            
            // Set empty strings for null values
            $function1_name = $function1_name ? $function1_name : '';
            $function1_macro = $function1_macro ? $function1_macro : '';
            $function2_name = $function2_name ? $function2_name : '';
            $function2_macro = $function2_macro ? $function2_macro : '';
            $function3_name = $function3_name ? $function3_name : '';
            $function3_macro = $function3_macro ? $function3_macro : '';
            $function4_name = $function4_name ? $function4_name : '';
            $function4_macro = $function4_macro ? $function4_macro : '';
            $function5_name = $function5_name ? $function5_name : '';
            $function5_macro = $function5_macro ? $function5_macro : '';
            
            // Basic validation
            if (!$this->session->userdata('user_id')) {
                throw new Exception('User not logged in');
            }
            
            // Load stations model to get active station profile
            $this->load->model('stations');
            $active_station_id = $this->stations->find_active();
            
            if (!$active_station_id || $active_station_id === '0') {
                throw new Exception('No active station profile found. Please set an active station profile.');
            }

            $data = [
            'user_id' => $this->session->userdata('user_id'),
            'station_location_id' => $active_station_id,
			'function1_name'  => $function1_name,
            'function1_macro' => $function1_macro,
            'function2_name'  => $function2_name,
            'function2_macro' => $function2_macro,
            'function3_name'  => $function3_name,
            'function3_macro' => $function3_macro,
            'function4_name'  => $function4_name,
            'function4_macro' => $function4_macro,
            'function5_name'  => $function5_name,
            'function5_macro' => $function5_macro,
		];
		
		// Debug: Log the data being saved
		log_message('debug', 'CW Macros Save Data: ' . print_r($data, true));

            // Load model Winkey
            $this->load->model('winkey');

            // save the data
            $this->winkey->save($data);
            
            // Return a proper success message with proper HTML
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <strong>Success!</strong> CW Macros saved successfully! The button labels will update when you close this dialog.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } catch (Exception $e) {
            // Return error message if save fails
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Error!</strong> Failed to save macros: ' . htmlspecialchars($e->getMessage()) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } catch (Throwable $t) {
            // Catch any other errors
            log_message('error', 'CW Macros Save Error: ' . $t->getMessage() . ' - File: ' . $t->getFile() . ' - Line: ' . $t->getLine());
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Error!</strong> An unexpected error occurred while saving macros.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }

    public function cwmacros_test() {
        try {
            $this->load->model('stations');
            $active_station_id = $this->stations->find_active();
            echo "Test successful - User ID: " . $this->session->userdata('user_id') . " Active Station ID: " . $active_station_id;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function cwmacros_json() {
        try {
            // Load model Winkey
            $this->load->model('winkey');

            header('Content-Type: application/json; charset=utf-8');

            $user_id = $this->session->userdata('user_id');
            
            if (!$user_id) {
                echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
                return;
            }
            
            // Load stations model to get active station profile
            $this->load->model('stations');
            $station_id = $this->stations->find_active();
            
            if (!$station_id || $station_id === '0') {
                echo json_encode(['status' => 'error', 'message' => 'No active station profile']);
                return;
            }

            // Call settings_json from model winkey
            echo $this->winkey->settings_json($user_id, $station_id);
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function winkeyrelaytoken_json() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $this->load->model('user_options_model');
            $result = $this->user_options_model->get_options('winkey_websocket_relay', array('option_name' => 'relay', 'option_key' => 'token'))->result();
            $token = isset($result[0]->option_value) ? (string)$result[0]->option_value : '';

            echo json_encode(array('status' => 'ok', 'token' => $token));
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    }

    public function winkeyrelaytoken_save() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $token = trim((string)$this->security->xss_clean($this->input->post('token', true)));

            if ($token !== '' && strlen($token) < 8) {
                echo json_encode(array('status' => 'error', 'message' => 'Relay token must be at least 8 characters'));
                return;
            }

            $this->load->model('user_options_model');
            $this->user_options_model->set_option('winkey_websocket_relay', 'relay', array('token' => $token));

            echo json_encode(array('status' => 'ok'));
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    }

    public function winkeyrelaysettings_json() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $this->load->model('user_options_model');
            $rows = $this->user_options_model->get_options('winkey_websocket_relay', array('option_name' => 'relay'))->result();

            $settings = array(
                'enabled' => false,
                'url' => 'wss://relay.cloudlog.org/',
                'room' => 'cw_room',
                'token' => '',
            );

            foreach ($rows as $row) {
                if ($row->option_key === 'enabled') {
                    $settings['enabled'] = ((string)$row->option_value === '1');
                } elseif ($row->option_key === 'url' && (string)$row->option_value !== '') {
                    $settings['url'] = (string)$row->option_value;
                } elseif ($row->option_key === 'room' && (string)$row->option_value !== '') {
                    $settings['room'] = (string)$row->option_value;
                } elseif ($row->option_key === 'token') {
                    $settings['token'] = (string)$row->option_value;
                }
            }

            echo json_encode(array('status' => 'ok', 'settings' => $settings));
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    }

    public function winkeyrelaysettings_save() {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $enabled = $this->input->post('enabled', true) === '1';
            $url = trim((string)$this->security->xss_clean($this->input->post('url', true)));
            $room = trim((string)$this->security->xss_clean($this->input->post('room', true)));
            $token = trim((string)$this->security->xss_clean($this->input->post('token', true)));

            if ($url === '') {
                $url = 'wss://relay.cloudlog.org/';
            }

            if ($room === '') {
                $room = 'cw_room';
            }

            if ($enabled) {
                if (!preg_match('/^wss?:\/\//', $url)) {
                    echo json_encode(array('status' => 'error', 'message' => 'Relay URL must start with ws:// or wss://'));
                    return;
                }

                if (strlen($token) < 8) {
                    echo json_encode(array('status' => 'error', 'message' => 'Relay token must be at least 8 characters'));
                    return;
                }
            }

            $this->load->model('user_options_model');
            $this->user_options_model->set_option('winkey_websocket_relay', 'relay', array(
                'enabled' => $enabled ? '1' : '0',
                'url' => $url,
                'room' => $room,
                'token' => $token,
            ));

            echo json_encode(array('status' => 'ok'));
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    }

    public function edit_ajax() {

        $this->load->model('logbook_model');
        $this->load->model('user_model');
        $this->load->model('modes');
        $this->load->model('bands');
		$this->load->model('contesting_model');

        $this->load->library('form_validation');

        if(!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard');
        }

        $id = str_replace('"', "", $this->input->post("id"));
        
        // Check if user has write permission to this QSO
        if (!$this->logbook_model->check_qso_is_writable($id)) {
            $this->session->set_flashdata('notice', 'You do not have permission to edit this QSO');
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'not allowed'));
            return;
        }
        
        $query = $this->logbook_model->qso_info($id);

        $data['qso'] = $query->row();
        $data['dxcc'] = $this->logbook_model->fetchDxcc();
        $data['iota'] = $this->logbook_model->fetchIota();
        $data['modes'] = $this->modes->all();
        $data['bands'] = $this->bands->get_user_bands_for_qso_entry(true);
        $data['contest'] = $this->contesting_model->getActivecontests();

        $this->load->view('qso/edit_ajax', $data);
    }

    function qso_save_ajax() {
        $this->load->model('logbook_model');
        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard');
        }

        $qso_id = $this->input->post('id');
        
        // Check if user has write permission to this QSO
        if (!$this->logbook_model->check_qso_is_writable($qso_id)) {
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'not allowed'));
            return;
        }

        $this->logbook_model->edit();
    }

	function qsl_rcvd($id, $method) {
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

			// Update Logbook to Mark Paper Card Received

			$this->logbook_model->paperqsl_update($id, $method);

			$this->session->set_flashdata('notice', 'QSL Card: Marked as Received');

			redirect('logbook');
	}

    function qsl_rcvd_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));
        $method = str_replace('"', "", $this->input->post("method"));

        $this->load->model('logbook_model');
        $this->load->model('user_model');

        header('Content-Type: application/json');

        if(!$this->user_model->authorize(2)) {
            echo json_encode(array('message' => 'Error'));

        }
        else {
            // Update Logbook to Mark Paper Card Received
            $this->logbook_model->paperqsl_update($id, $method);

            echo json_encode(array('message' => 'OK'));
        }
    }

    function qsl_sent_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));
        $method = str_replace('"', "", $this->input->post("method"));
        
        $this->load->model('logbook_model');
        $this->load->model('user_model');
        
        header('Content-Type: application/json');
        
        if(!$this->user_model->authorize(2)) {
            echo json_encode(array('message' => 'Error'));
            
        }
        else {
            // Update Logbook to Mark Paper Card Sent
            $this->logbook_model->paperqsl_update_sent($id, $method);
            
            echo json_encode(array('message' => 'OK'));
        }
    }

    function qsl_requested_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));
        $method = str_replace('"', "", $this->input->post("method"));

        $this->load->model('logbook_model');
        $this->load->model('user_model');

        header('Content-Type: application/json');

        if(!$this->user_model->authorize(2)) {
            echo json_encode(array('message' => 'Error'));

        }
        else {
            // Update Logbook to Mark Paper Card Received
            $this->logbook_model->paperqsl_requested($id, $method);

            echo json_encode(array('message' => 'OK'));
        }
    }

	public function qsl_ignore_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));
        $method = str_replace('"', "", $this->input->post("method"));

        $this->load->model('logbook_model');
        $this->load->model('user_model');

        header('Content-Type: application/json');

        if(!$this->user_model->authorize(2)) {
            echo json_encode(array('message' => 'Error'));

        }
        else {
            // Update Logbook to Mark Paper Card Received
            $this->logbook_model->paperqsl_ignore($id, $method);

            echo json_encode(array('message' => 'OK'));
        }
    }

	/* Delete QSO */
	public function delete($id) {
		$this->load->model('logbook_model');

		if ($this->logbook_model->check_qso_is_writable($id)) {
			$this->logbook_model->delete($id);
			$this->session->set_flashdata('notice', 'QSO Deleted Successfully');
			$data['message_title'] = "Deleted";
			$data['message_contents'] = "QSO Deleted Successfully";
			$this->load->view('messages/message', $data);
		} else {
			$this->session->set_flashdata('notice', 'You do not have permission to delete this QSO');
			$data['message_title'] = "Permission Denied";
			$data['message_contents'] = "You do not have permission to delete this QSO";
			$this->load->view('messages/message', $data);
		}

		// If deletes from /logbook dropdown redirect
		if (strpos($_SERVER['HTTP_REFERER'], '/logbook') !== false) {
		    redirect($_SERVER['HTTP_REFERER']);
		}
	}

    /* Delete QSO */
    public function delete_ajax() {
        $id = str_replace('"', "", $this->input->post("id"));

        $this->load->model('logbook_model');
	if ($this->logbook_model->check_qso_is_writable($id)) {
        	$this->logbook_model->delete($id);
        	header('Content-Type: application/json');
        	echo json_encode(array('message' => 'OK'));
	} else {
        	header('Content-Type: application/json');
        	echo json_encode(array('message' => 'not allowed'));
	}
    }

	public function band_to_freq($band, $mode) {

		$this->load->library('frequency');

		echo $this->frequency->convert_band($band, $mode);
	}

	/*
	 * Function is used for autocompletion of SOTA in the QSO entry form
	 */
	public function get_sota() {
		$this->load->library('sota');
		$json = [];

		if (!empty($this->input->get("query"))) {
			$query = $_GET['query'] ?? FALSE;
			$json = $this->sota->get($query);
		}

		header('Content-Type: application/json');
		echo json_encode($json);
	}

	public function get_wwff() {
        $json = [];

        if(!empty($this->input->get("query"))) {
            $query = isset($_GET['query']) ? $_GET['query'] : FALSE;
            $wwff = strtoupper($query);

            $file = 'assets/json/wwff.txt';

            if (is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                $input = preg_quote($wwff, '~');
                $reg = '~^'. $input .'(.*)$~';
                $result = preg_grep($reg, $lines);
                $json = [];
                $i = 0;
                foreach ($result as &$value) {
                    // Limit to 100 as to not slowdown browser too much
                    if (count($json) <= 100) {
                        $json[] = ["name"=>$value];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

	public function get_pota() {
        $json = [];

        if(!empty($this->input->get("query"))) {
            $query = isset($_GET['query']) ? $_GET['query'] : FALSE;
            $pota = strtoupper($query);

            $file = 'assets/json/pota.txt';

            if (is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                $input = preg_quote($pota, '~');
                $reg = '~^'. $input .'(.*)$~';
                $result = preg_grep($reg, $lines);
                $json = [];
                $i = 0;
                foreach ($result as &$value) {
                    // Limit to 100 as to not slowdown browser too much
                    if (count($json) <= 100) {
                        $json[] = ["name"=>$value];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /*
	 * Function is used for autocompletion of DOK in the QSO entry form
	 */
    public function get_dok() {
        $json = [];

        if(!empty($this->input->get("query"))) {
            $query = isset($_GET['query']) ? $_GET['query'] : FALSE;
            $dok = strtoupper($query);

            $file = 'assets/json/dok.txt';

            if (is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                $input = preg_quote($dok, '~');
                $reg = '~^'. $input .'(.*)$~';
                $result = preg_grep($reg, $lines);
                $json = [];
                $i = 0;
                foreach ($result as &$value) {
                    // Limit to 100 as to not slowdown browser too much
                    if (count($json) <= 100) {
                        $json[] = ["name"=>$value];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /*
	 * Function is used for autocompletion of Counties in the station profile form
	 */
    public function get_county() {
        $json = [];

        if(!empty($this->input->get("query"))) {
            //$query = isset($_GET['query']) ? $_GET['query'] : FALSE;
            $county = $this->input->get("state");
            $cleanedcounty = explode('(', $county);
            $cleanedcounty = trim($cleanedcounty[0]);

            $file = 'assets/json/US_counties.csv';

            if (is_readable($file)) {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                $input = preg_quote($cleanedcounty, '~');
                $reg = '~^'. $input .'(.*)$~';
                $result = preg_grep($reg, $lines);
                $json = [];
                $i = 0;
                foreach ($result as &$value) {
                    $county = explode(',', $value);
                    // Limit to 100 as to not slowdown browser too much
                    if (count($json) <= 300) {
                        $json[] = ["name"=>$county[1]];
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

   public function get_sota_info() {
      $this->load->library('sota');

      $sota = xss_clean($this->input->post('sota'));

      header('Content-Type: application/json');
      echo $this->sota->info($sota);
   }

   public function get_wwff_info() {
      $this->load->library('wwff');

      $wwff = xss_clean($this->input->post('wwff'));

      header('Content-Type: application/json');
      echo $this->wwff->info($wwff);
   }

   public function get_pota_info() {
      $this->load->library('pota');

      $pota = xss_clean($this->input->post('pota'));

      header('Content-Type: application/json');
      echo $this->pota->info($pota);
   }

   public function get_station_power() {
      $this->load->model('stations');
      $stationProfile = xss_clean($this->input->post('stationProfile'));
      $data = array('station_power' => $this->stations->get_station_power($stationProfile));

      header('Content-Type: application/json');
      echo json_encode($data);
   }

   // Return Previous QSOs Made in the active logbook
   public function component_past_contacts() {
    $this->load->model('logbook_model');
    if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

    $limit = 5;
    $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
    $offset = $page * $limit;

    $data['query'] = $this->logbook_model->last_custom_paginated($limit, $offset);
    $data['total_rows'] = $this->logbook_model->last_custom_count();
    $data['total_pages'] = ceil($data['total_rows'] / $limit);
    $data['current_page'] = $page;
    $data['limit'] = $limit;

        // This endpoint is polled by HTMX and must not be cached.
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');

    // Load view
    $this->load->view('qso/components/previous_contacts', $data);
   }

   function check_locator($grid) {
      $grid = $this->input->post('locator');
      // Allow empty locator
      if (preg_match('/^$/', $grid)) return true;
      // Allow 6-digit locator
      if (preg_match('/^[A-Ra-r]{2}[0-9]{2}[A-Xa-x]{2}$/', $grid)) return true;
      // Allow 4-digit locator
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2}$/', $grid)) return true;
      // Allow 4-digit grid line
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2}$/', $grid)) return true;
      // Allow 4-digit grid corner
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2},[A-Ra-r]{2}[0-9]{2}$/', $grid)) return true;
      // Allow 2-digit locator
      else if (preg_match('/^[A-Ra-r]{2}$/', $grid)) return true;
      // Allow 8-digit locator
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2}[A-Xa-x]{2}[0-9]{2}$/', $grid)) return true;
      // Allow 10-digit locator
      else if (preg_match('/^[A-Ra-r]{2}[0-9]{2}[A-Xa-x]{2}[0-9]{2}[A-Xa-x]{2}$/', $grid)) return true;
      else {
         $this->form_validation->set_message('check_locator', 'Please check value for grid locator ('.strtoupper($grid).').');
         return false;
      }
   }
}
