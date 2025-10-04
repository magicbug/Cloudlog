<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dxcluster extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    function index()
    {
        $data['page_title'] = "DX Cluster Spots";

        /// Load layout

        $this->load->view('interface_assets/header', $data);
		$this->load->view('dxcluster/index', $data);
		$this->load->view('interface_assets/footer');
    }

    function bandmap()
    {
        $this->load->model('bands');
        
        $data['page_title'] = "DX Cluster Bandmap";
        $data['bands'] = $this->bands->get_user_bands_for_bandmap($includeall = true);

        $this->load->view('dxcluster/bandmap', $data);

    }

    /*
     * QSY radio to frequency from bandmap spot
     * POST /dxcluster/qsy
     */
    public function qsy() {
        header('Content-Type: application/json');
        
        $this->load->model('cat');
        
        // Get JSON input
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['radio_id']) || !isset($input['frequency'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing radio_id or frequency']);
            return;
        }
        
        $radio_id = $input['radio_id'];
        $frequency = $input['frequency'];
        $mode = isset($input['mode']) ? $input['mode'] : null;
        
        // Validate that the radio belongs to the current user
        $radios = $this->cat->radios()->result();
        $radio_found = false;
        foreach ($radios as $radio) {
            if ($radio->id == $radio_id) {
                $radio_found = true;
                break;
            }
        }
        
        if (!$radio_found) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Invalid radio selected']);
            return;
        }
        
        // Convert MHz to Hz for storage
        $frequency_hz = floatval($frequency) * 1000000;
        
        // Prepare command data
        $command_data = array(
            'radio_id' => $radio_id,
            'user_id' => $this->session->userdata('user_id'),
            'station_id' => $this->session->userdata('active_station_profile'),
            'command_type' => 'SET_FREQ',
            'frequency' => $frequency_hz,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
        );
        
        // Add mode command if provided
        $freq_command_id = $this->cat->queue_command($command_data);
        $mode_command_id = null;
        
        // Debug logging
        log_message('debug', "QSY: Mode parameter received: '" . var_export($mode, true) . "'");
        log_message('debug', "QSY: Mode empty check: " . (empty($mode) ? 'true' : 'false'));
        
        if (!empty($mode)) {
            $mode_command = array(
                'radio_id' => $radio_id,
                'user_id' => $this->session->userdata('user_id'),
                'station_id' => $this->session->userdata('active_station_profile'),
                'command_type' => 'SET_MODE',
                'mode' => $mode,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
            );
            
            log_message('debug', "QSY: Creating SET_MODE command: " . json_encode($mode_command));
            $mode_command_id = $this->cat->queue_command($mode_command);
            log_message('debug', "QSY: SET_MODE command ID: " . var_export($mode_command_id, true));
        } else {
            log_message('debug', "QSY: No mode provided or mode is empty");
        }
        
        if ($freq_command_id) {
            echo json_encode([
                'success' => true, 
                'message' => 'QSY command sent to radio',
                'frequency' => $frequency,
                'frequency_hz' => $frequency_hz,
                'mode' => $mode,
                'freq_command_id' => $freq_command_id,
                'mode_command_id' => $mode_command_id
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to queue QSY command']);
        }
    }
}