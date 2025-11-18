<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dxcluster extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        // Validate session first to restore from cookie if needed
        $this->user_model->validate_session();
        if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
    }

    function index()
    {
        $data['page_title'] = "DX Cluster Spots";
        
        // Load radio data for CAT control
        $this->load->model('cat');
        $data['radios'] = $this->cat->radios();

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

    /*
     * Check if callsigns have been worked
     * POST /dxcluster/check_worked
     */
    public function check_worked() {
        header('Content-Type: application/json');
        
        $this->load->model('logbook_model');
        $this->load->model('logbooks_model');
        
        // Get JSON input
        $input = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($input['callsigns']) || !is_array($input['callsigns'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing or invalid callsigns array']);
            return;
        }
        
        // Limit batch size to prevent excessive load
        $max_batch_size = 50;
        if (count($input['callsigns']) > $max_batch_size) {
            $input['callsigns'] = array_slice($input['callsigns'], 0, $max_batch_size);
        }
        
        // Get logbook locations for the active logbook
        $logbook_id = $this->session->userdata('active_station_logbook');
        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($logbook_id);
        
        if (!$logbooks_locations_array) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No logbook locations found']);
            return;
        }
        
        $results = [];
        
        foreach ($input['callsigns'] as $item) {
            $callsign = $item['callsign'];
            $band = isset($item['band']) ? $item['band'] : null;
            
            // Get DXCC entity for this callsign
            $dxcc_info = $this->logbook_model->dxcc_lookup($callsign, date('Ymd'));
            $dxcc = isset($dxcc_info['adif']) ? $dxcc_info['adif'] : null;
            
            // Check if worked on this band
            $worked_on_band = false;
            if ($band) {
                $worked_on_band = $this->logbook_model->check_if_callsign_worked_in_logbook($callsign, $logbooks_locations_array, $band) > 0;
            }
            
            // Check if worked on any band
            $worked_overall = $this->logbook_model->check_if_callsign_worked_in_logbook($callsign, $logbooks_locations_array, null) > 0;
            
            // Check if DXCC entity is worked on this band
            $dxcc_worked_on_band = false;
            if ($dxcc && $band) {
                $this->db->select('COL_DXCC');
                $this->db->where_in('station_id', $logbooks_locations_array);
                $this->db->where('COL_DXCC', $dxcc);
                $this->db->where('COL_BAND', $band);
                $this->db->limit(1);
                $query = $this->db->get($this->config->item('table_name'));
                $dxcc_worked_on_band = $query->num_rows() > 0;
            }
            
            // Check if DXCC entity is worked on any band
            $dxcc_worked_overall = false;
            if ($dxcc) {
                $this->db->select('COL_DXCC');
                $this->db->where_in('station_id', $logbooks_locations_array);
                $this->db->where('COL_DXCC', $dxcc);
                $this->db->limit(1);
                $query = $this->db->get($this->config->item('table_name'));
                $dxcc_worked_overall = $query->num_rows() > 0;
            }
            
            $results[$callsign] = [
                'worked_on_band' => $worked_on_band,
                'worked_overall' => $worked_overall,
                'band' => $band,
                'dxcc' => $dxcc,
                'dxcc_worked_on_band' => $dxcc_worked_on_band,
                'dxcc_worked_overall' => $dxcc_worked_overall,
                'country' => isset($dxcc_info['entity']) ? $dxcc_info['entity'] : null
            ];
        }
        
        echo json_encode(['success' => true, 'results' => $results]);
    }
}