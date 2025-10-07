<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Commands extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('user_model');
        $this->load->model('cat');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        
        // Check if user is logged in
        if(!$this->user_model->validate_session()) {
            redirect('user/login'); // redirect if not logged in
        }
    }

    /*
     * Display the radio commands interface
     */
    public function index() {
        $data['page_title'] = "Radio Commands";
        $data['radios'] = $this->cat->radios()->result();
        $data['recent_commands'] = $this->get_recent_commands_for_user();
        
        $this->load->view('interface_assets/header', $data);
        $this->load->view('commands/index', $data);
        $this->load->view('interface_assets/footer', $data);
    }

    /*
     * Send a command to a radio
     * POST /commands/send
     */
    public function send() {
        header('Content-Type: application/json');
        
        // Validate input
        $this->form_validation->set_rules('radio_id', 'Radio', 'required|numeric');
        $this->form_validation->set_rules('command_type', 'Command Type', 'required|in_list[SET_FREQ,SET_MODE,SET_VFO,SET_POWER]');
        
        if (!$this->form_validation->run()) {
            echo json_encode(array(
                'success' => false, 
                'message' => validation_errors()
            ));
            return;
        }

        $radio_id = $this->input->post('radio_id');
        $command_type = $this->input->post('command_type');
        
        // Validate that the radio belongs to the current user
        if (!$this->validate_radio_ownership($radio_id)) {
            echo json_encode(array(
                'success' => false, 
                'message' => 'Invalid radio selected'
            ));
            return;
        }

        // Prepare command data
        $command_data = array(
            'radio_id' => $radio_id,
            'user_id' => $this->session->userdata('user_id'),
            'station_id' => $this->session->userdata('active_station_profile'),
            'command_type' => $command_type,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
        );

        // Add specific command parameters
        switch ($command_type) {
            case 'SET_FREQ':
                $frequency = $this->input->post('frequency');
                if (empty($frequency) || !is_numeric($frequency)) {
                    echo json_encode(array('success' => false, 'message' => 'Valid frequency required'));
                    return;
                }
                $command_data['frequency'] = floatval($frequency) * 1000000; // Convert MHz to Hz
                break;
                
            case 'SET_MODE':
                $mode = $this->input->post('mode');
                if (empty($mode)) {
                    echo json_encode(array('success' => false, 'message' => 'Mode required'));
                    return;
                }
                $command_data['mode'] = $mode;
                break;
                
            case 'SET_VFO':
                $vfo = $this->input->post('vfo');
                if (empty($vfo)) {
                    echo json_encode(array('success' => false, 'message' => 'VFO required'));
                    return;
                }
                $command_data['vfo'] = $vfo;
                break;
                
            case 'SET_POWER':
                $power = $this->input->post('power');
                if (empty($power) || !is_numeric($power)) {
                    echo json_encode(array('success' => false, 'message' => 'Valid power level required'));
                    return;
                }
                $command_data['power'] = intval($power);
                break;
        }

        // Queue the command
        $command_id = $this->cat->queue_command($command_data);
        
        if ($command_id) {
            echo json_encode(array(
                'success' => true, 
                'message' => 'Command queued successfully',
                'command_id' => $command_id
            ));
        } else {
            echo json_encode(array(
                'success' => false, 
                'message' => 'Failed to queue command'
            ));
        }
    }

    /*
     * Get pending commands for a specific radio (for desktop app polling)
     * GET /commands/pending/{radio_id}
     */
    public function pending($radio_id = null) {
        header('Content-Type: application/json');
        
        if (!$radio_id || !is_numeric($radio_id)) {
            echo json_encode(array('error' => 'Invalid radio ID'));
            return;
        }

        // Validate radio ownership
        if (!$this->validate_radio_ownership($radio_id)) {
            echo json_encode(array('error' => 'Unauthorized'));
            return;
        }

        $commands = $this->cat->get_pending_commands($radio_id);
        echo json_encode($commands);
    }

    /*
     * Get all pending commands for all user's radios (for desktop app polling)
     * GET /commands/pending_all
     */
    public function pending_all() {
        header('Content-Type: application/json');
        
        $user_id = $this->session->userdata('user_id');
        $commands = $this->cat->get_all_pending_commands($user_id);
        echo json_encode($commands);
    }

    /*
     * Update command status (called by desktop app after processing)
     * POST /commands/update_status
     */
    public function update_status() {
        header('Content-Type: application/json');
        
        $this->form_validation->set_rules('command_id', 'Command ID', 'required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[PROCESSING,COMPLETED,FAILED]');
        
        if (!$this->form_validation->run()) {
            echo json_encode(array('success' => false, 'message' => validation_errors()));
            return;
        }

        $command_id = $this->input->post('command_id');
        $status = $this->input->post('status');
        $error_message = $this->input->post('error_message');

        // Validate command ownership
        $command = $this->cat->get_command($command_id);
        if (!$command || $command['user_id'] != $this->session->userdata('user_id')) {
            echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
            return;
        }

        $result = $this->cat->update_command_status($command_id, $status, $error_message);
        
        echo json_encode(array('success' => $result));
    }

    /*
     * Get command history for a radio
     * GET /commands/history/{radio_id}
     */
    public function history($radio_id = null) {
        header('Content-Type: application/json');
        
        if (!$radio_id || !is_numeric($radio_id)) {
            echo json_encode(array('error' => 'Invalid radio ID'));
            return;
        }

        if (!$this->validate_radio_ownership($radio_id)) {
            echo json_encode(array('error' => 'Unauthorized'));
            return;
        }

        $history = $this->cat->get_command_history($radio_id, null, 20);
        echo json_encode($history);
    }

    /*
     * Cancel a pending command
     * POST /commands/cancel
     */
    public function cancel() {
        header('Content-Type: application/json');
        
        $command_id = $this->input->post('command_id');
        
        if (!$command_id || !is_numeric($command_id)) {
            echo json_encode(array('success' => false, 'message' => 'Invalid command ID'));
            return;
        }

        $result = $this->cat->cancel_command($command_id);
        
        echo json_encode(array('success' => $result));
    }

    /*
     * Send frequency and mode command (convenience method for QSO interface)
     * POST /commands/send_freq_mode
     */
    public function send_freq_mode() {
        header('Content-Type: application/json');
        
        $radio_id = $this->input->post('radio_id');
        $frequency = $this->input->post('frequency');
        $mode = $this->input->post('mode');
        
        if (!$radio_id || !$frequency) {
            echo json_encode(array('success' => false, 'message' => 'Radio and frequency required'));
            return;
        }

        if (!$this->validate_radio_ownership($radio_id)) {
            echo json_encode(array('success' => false, 'message' => 'Invalid radio'));
            return;
        }

        // Queue frequency command
        $freq_command = array(
            'radio_id' => $radio_id,
            'user_id' => $this->session->userdata('user_id'),
            'station_id' => $this->session->userdata('active_station_profile'),
            'command_type' => 'SET_FREQ',
            'frequency' => floatval($frequency) * 1000000, // Convert MHz to Hz
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
        );
        
        $freq_command_id = $this->cat->queue_command($freq_command);
        
        $mode_command_id = null;
        if (!empty($mode)) {
            // Queue mode command
            $mode_command = array(
                'radio_id' => $radio_id,
                'user_id' => $this->session->userdata('user_id'),
                'station_id' => $this->session->userdata('active_station_profile'),
                'command_type' => 'SET_MODE',
                'mode' => $mode,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
            );
            
            $mode_command_id = $this->cat->queue_command($mode_command);
        }
        
        if ($freq_command_id) {
            echo json_encode(array(
                'success' => true, 
                'message' => 'Commands queued successfully',
                'freq_command_id' => $freq_command_id,
                'mode_command_id' => $mode_command_id
            ));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to queue commands'));
        }
    }

    /*
     * Cleanup expired commands (maintenance function)
     * GET /commands/cleanup
     */
    public function cleanup() {
        if (!$this->user_model->validate_session() || !$this->user_model->authorize(99)) {
            show_404();
            return;
        }
        
        $deleted_count = $this->cat->cleanup_expired_commands();
        
        echo json_encode(array(
            'success' => true,
            'deleted_commands' => $deleted_count,
            'message' => "Cleaned up {$deleted_count} expired commands"
        ));
    }

    /*
     * Helper function to validate radio ownership
     */
    private function validate_radio_ownership($radio_id) {
        $radios = $this->cat->radios()->result();
        foreach ($radios as $radio) {
            if ($radio->id == $radio_id) {
                return true;
            }
        }
        return false;
    }

    /*
     * Get recent commands for the current user
     */
    private function get_recent_commands_for_user() {
        $user_id = $this->session->userdata('user_id');
        $radios = $this->cat->radios()->result();
        $recent_commands = array();
        
        foreach ($radios as $radio) {
            $commands = $this->cat->get_command_history($radio->id, $user_id, 5);
            $recent_commands = array_merge($recent_commands, $commands);
        }
        
        // Sort by created_at descending
        usort($recent_commands, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return array_slice($recent_commands, 0, 10);
    }
}