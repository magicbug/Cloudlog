<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thirdpartylogin extends CI_Model {

    public $table = 'thirdparty_logins';

    // Get Thirdparty Login from DB 
    public function details($service_id) {
        $this->db->where('service_id', $service_id);
        $query = $this->db->get($this->table);
        
        if (!$query->row()) {
            // Array Empty
            return false;
        } else {
            // Array Not Empty
            $data = array(
                'service_id' => $query->row()->service_id,
                'user_id' => $query->row()->user_id,
                'service_name' => $query->row()->service_name,
                'service_username' =>  $this->encryption->decrypt($query->row()->service_username),
                'service_password' =>  $this->encryption->decrypt($query->row()->service_password),
                'active' => $query->row()->active,
            );
            return $data;
        }
    }

    // Save Thirdparty Login to DB
    function save($dataentry) {
        // Save user's third party login credentials
        $data = array(
            'user_id' => $dataentry['user_id'],
            'service_name' => $dataentry['service_name'],
            'service_username' => $dataentry['service_username'],
            'service_password' => $dataentry['service_password'],
            'active' => 1,
            'modified' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->table, $data)) {
            return true;
        } else {
            log_message('error', 'Failed to save third party login.');
            return false;
        }
    }

    // Update Thirdparty Login to DB
    function update($dataentry) {
        // Save user's third party login credentials
        $data = array(
            'service_name' => $dataentry['service_name'],
            'service_username' => $dataentry['service_username'],
            'service_password' => $dataentry['service_password'],
            'modified' => date('Y-m-d H:i:s')
        );

        $this->db->where('service_id', $dataentry['service_id']);
        if($this->db->update($this->table, $data)) {
            return true;
        } else {
            log_message('error', 'Updated '.$dataentry['service_id'].' third party login.');
            return false;
        }
    }

    // Delete Thirdparty Login from DB
    function delete($service_id) {
        // Delete third party login
        $this->db->where('service_id', $service_id);

        if($this->db->delete($this->table)) {
            return true;
        } else {
            log_message('error', 'Deleted '.$service_id.' third party login.');
            return false;
        }
    }

    // Activate Thirdparty Login
    function activate($service_id) {
        // Activate third party login
        $data = array(
            'active' => 1,
            'modified' => date('Y-m-d H:i:s')
        );

        $this->db->where('service_id', $service_id);
        if($this->db->update($this->table, $data)) {
            return true;
        } else {
            log_message('error', 'Unable to activate '.$service_id.' third party login.');
            return false;
        }
    }

    // Deactivate Thirdparty Login
    function deactivate($service_id) {
        // Deactivate third party login
        $data = array(
            'active' => 0,
            'modified' => date('Y-m-d H:i:s')
        );

        $this->db->where('service_id', $service_id);
        if($this->db->update($this->table, $data)) {
            return true;
        } else {
            log_message('error', 'Deactivate '.$service_id.' third party login.');
            return false;
        }
    }

    // List all third party logins for a user
    function list_all($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->table);
        
        if (!$query->row()) {
            // Array Empty
            return false;
        } else {
            // Array Not Empty
            $data = array();
            foreach ($query->result() as $row) {
                $data[] = array(
                    'service_id' => $row->service_id,
                    'service_name' => $row->service_name,
                    'service_username' =>  $this->encryption->decrypt($row->service_username),
                    'active' => $row->active,
                );
            }
            return $data;
        }
    }
}
?>