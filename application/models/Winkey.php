<?php

class Winkey extends CI_Model
{
    public function settings($user_id, $station_location_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('station_location_id', $station_location_id);
        $query = $this->db->get('cwmacros');

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function settings_json($user_id, $station_location_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('station_location_id', $station_location_id);
        $query = $this->db->get('cwmacros');
        
        if ($query->num_rows() > 0) {
            // return $query->row() as json
            return json_encode($query->row());
        } else {
           // return json with status not found
              return json_encode(array('status' => 'not found'));
        }
    }

    public function save($data)
    {
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('station_location_id', $data['station_location_id']);
        $query = $this->db->get('cwmacros');

        if ($query->num_rows() > 0) {
            $this->db->where('user_id', $data['user_id']);
            $this->db->where('station_location_id', $data['station_location_id']);
            $this->db->update('cwmacros', $data);
        } else {
            $this->db->insert('cwmacros', $data);
        }
    }
}

?>