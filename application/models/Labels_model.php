<?php

class Labels_model extends CI_Model {
    function addLabel() {
		$data = array(
			'user_id' 		=> $this->session->userdata('user_id'),
            'label_name' 	=> xss_clean($this->input->post('label_name', true)),
            'paper_type' 	=> xss_clean($this->input->post('paper_type', true)),
            'metric' 		=> xss_clean($this->input->post('measurementType', true)),
            'marginleft' 	=> xss_clean($this->input->post('marginLeft', true)),
            'margintop' 	=> xss_clean($this->input->post('marginTop', true)),
            'nx' 		    => xss_clean($this->input->post('NX', true)),
            'ny' 		    => xss_clean($this->input->post('NY', true)),
            'spacex' 		=> xss_clean($this->input->post('SpaceX', true)),
            'spacey' 		=> xss_clean($this->input->post('SpaceY', true)),
            'width' 		=> xss_clean($this->input->post('width', true)),
            'height' 		=> xss_clean($this->input->post('height', true)),
            'font_size' 	=> xss_clean($this->input->post('font_size', true)),
            'qsos' 		    => xss_clean($this->input->post('label_qsos', true)),
            'font' 		    => xss_clean($this->input->post('font', true)),
            'last_modified' => date('Y-m-d H:i:s'),
		);

	   $this->db->insert('label_types', $data);

	}

    function getLabel($id) {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where('id', $id);
		$query = $this->db->get('label_types');
		
        return $query->row();
    }

    function updateLabel($id) {
        $data = array(
			'user_id' 		=> $this->session->userdata('user_id'),
            'label_name' 	=> xss_clean($this->input->post('label_name', true)),
            'paper_type' 	=> xss_clean($this->input->post('paper_type', true)),
            'metric' 		=> xss_clean($this->input->post('measurementType', true)),
            'marginleft' 	=> xss_clean($this->input->post('marginLeft', true)),
            'margintop' 	=> xss_clean($this->input->post('marginTop', true)),
            'nx' 		    => xss_clean($this->input->post('NX', true)),
            'ny' 		    => xss_clean($this->input->post('NY', true)),
            'spacex' 		=> xss_clean($this->input->post('SpaceX', true)),
            'spacey' 		=> xss_clean($this->input->post('SpaceY', true)),
            'width' 		=> xss_clean($this->input->post('width', true)),
            'height' 		=> xss_clean($this->input->post('height', true)),
            'font_size' 	=> xss_clean($this->input->post('font_size', true)),
            'qsos' 		    => xss_clean($this->input->post('label_qsos', true)),
            'font' 		    => xss_clean($this->input->post('font', true)),
            'last_modified' => date('Y-m-d H:i:s'),
		);

        $cleanid = $this->security->xss_clean($id);

        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where('id', $cleanid);
        $this->db->update('label_types', $data);
    }

    function deleteLabel($id) {
        $cleanid = xss_clean($id);

        $this->db->delete('label_types', array('id' => $cleanid, 'user_id' => $this->session->userdata('user_id'))); 
    }

    function fetchLabels($user_id) {
        $this->db->where('user_id', $user_id);
		$query = $this->db->get('label_types');
		
        return $query->result();
	}

 	function fetchQsos($user_id) {

		$qsl = "select count(*) count, station_profile.station_profile_name, station_profile.station_callsign, station_profile.station_id, station_profile.station_gridsquare
        from ". $this->config->item('table_name') . " as l 
        join station_profile on l.station_id = station_profile.station_id
        where l.COL_QSL_SENT in ('R', 'Q')
        and station_profile.user_id = " . $user_id .
        " group by station_profile.station_profile_name, station_profile.station_callsign, station_profile.station_id, station_profile.station_gridsquare
        order by station_profile.station_callsign";
        
        $query = $this->db->query($qsl);

		return $query->result();
	}

    function getDefaultLabel() {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where('useforprint', '1');
		$query = $this->db->get('label_types');
		
        return $query->row();
    }

    function saveDefaultLabel($id) {
        $sql = 'update label_types set useforprint = 0 where user_id = ' . $this->session->userdata('user_id');
        $this->db->query($sql);

        $cleanid = xss_clean($id);
        $sql = 'update label_types set useforprint = 1 where user_id = ' . $this->session->userdata('user_id') . ' and id = ' . $cleanid;
        $this->db->query($sql);
    }

    function export_printrequested($station_id = NULL) {
        $this->load->model('stations');
        $active_station_id = $this->stations->find_active();

        $this->db->select($this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');

		if ($station_id == NULL) {
			$this->db->where($this->config->item('table_name').'.station_id', $active_station_id);
		} else if ($station_id != 'All') {
			$this->db->where($this->config->item('table_name').'.station_id', $station_id);
		}

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif');
        // always filter user. this ensures that even if the station_id is from another user no inaccesible QSOs will be returned
        $this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
        $this->db->where_in('COL_QSL_SENT', array('R', 'Q'));
        $this->db->order_by("COL_DXCC", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    function export_printrequestedids($ids) {
        $this->db->select($this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country');
        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('dxcc_entities', 'station_profile.station_dxcc = dxcc_entities.adif');
        $this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
        $this->db->where_in('COL_PRIMARY_KEY', $ids);
        $this->db->order_by("COL_DXCC", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }
}