<?php

class Bands extends CI_Model {

	public $bandslots = array(
		// LF/MF Bands
		"2190m"=>0,
		"630m"=>0,
		"560m"=>0,
		// HF Bands
		"160m"=>0,
		"80m"=>0,
		"60m"=>0,
		"40m"=>0,
		"30m"=>0,
		"20m"=>0,
		"17m"=>0,
		"15m"=>0,
		"12m"=>0,
		"10m"=>0,
		// VHF Bands
		"6m"=>0,
		"4m"=>0,
		"2m"=>0,
		"1.25m"=>0,
		// UHF Bands
		"70cm"=>0,
		"33cm"=>0,
		// SHF Bands (Microwave)
		"23cm"=>0,
		"13cm"=>0,
		"9cm"=>0,
		"6cm"=>0,
		"3cm"=>0,
		"1.2cm"=>0,
		// EHF Bands (Millimeter wave)
		"6mm"=>0,
		"4mm"=>0,
		"2.5mm"=>0,
		"2mm"=>0,
		"1mm"=>0,
		// Special
		"SAT"=>0,
	);

	function get_user_bands($award = 'None') {
		$this->db->from('bands');
		$this->db->join('bandxuser', 'bandxuser.bandid = bands.id');
		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));
		$this->db->where('bandxuser.active', 1);

		if ($award != 'None') {
			$this->db->where('bandxuser.'.$award, 1);
		}

		$result = $this->db->get()->result();

		$results = array();

		foreach($result as $band) {
			array_push($results, $band->band);
		}

		return $results;
	}

	function get_all_bands() {
		$this->db->from('bands');

		$result = $this->db->get()->result();

		$results = array();

		foreach($result as $band) {
			$results['b'.strtoupper($band->band)] = array('CW' => $band->cw, 'SSB' => $band->ssb, 'DIGI' => $band->data);
		}

		return $results;
	}

	function get_user_bands_for_qso_entry($includeall = false) {
		$this->db->from('bands');
		$this->db->join('bandxuser', 'bandxuser.bandid = bands.id');
		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));
		if (!$includeall) {
			$this->db->where('bandxuser.active', 1);
		}
		$this->db->where('bands.bandgroup != "sat"');

		$result = $this->db->get()->result();

		$results = array();

		foreach($result as $band) {
			$results[$band->bandgroup][] = $band->band;
		}

		return $results;
	}

	function get_all_bands_for_user() {
		$this->db->from('bands');
		$this->db->join('bandxuser', 'bandxuser.bandid = bands.id');
		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));

		return $this->db->get()->result();
	}

	function get_user_bands_for_bandmap($includeall = false) {
		$this->db->from('bands');
		$this->db->join('bandxuser', 'bandxuser.bandid = bands.id');
		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));
		if (!$includeall) {
			$this->db->where('bandxuser.active', 1);
		}
		$this->db->where('bands.bandgroup != "sat"');

		$result = $this->db->get()->result();

		// Define typical band ranges (in kHz for bandmap display) in frequency order
		$band_ranges = array(
			// LF/MF Bands
			'2190m' => array('start' => 135700, 'end' => 137800, 'order' => 1),
			'630m' => array('start' => 472000, 'end' => 479000, 'order' => 2),
			'560m' => array('start' => 501000, 'end' => 504000, 'order' => 3),
			// HF Bands
			'160m' => array('start' => 1800, 'end' => 2000, 'order' => 4),
			'80m' => array('start' => 3500, 'end' => 4000, 'order' => 5),
			'60m' => array('start' => 5351500, 'end' => 5366500, 'order' => 6),
			'40m' => array('start' => 7000, 'end' => 7300, 'order' => 7),
			'30m' => array('start' => 10100, 'end' => 10150, 'order' => 8),
			'20m' => array('start' => 14000, 'end' => 14350, 'order' => 9),
			'17m' => array('start' => 18068, 'end' => 18168, 'order' => 10),
			'15m' => array('start' => 21000, 'end' => 21450, 'order' => 11),
			'12m' => array('start' => 24890, 'end' => 24990, 'order' => 12),
			'10m' => array('start' => 28000, 'end' => 29700, 'order' => 13),
			// VHF Bands
			'6m' => array('start' => 50000, 'end' => 54000, 'order' => 14),
			'4m' => array('start' => 70000, 'end' => 70500, 'order' => 15),
			'2m' => array('start' => 144000, 'end' => 148000, 'order' => 16),
			'1.25m' => array('start' => 222000, 'end' => 225000, 'order' => 17),
			// UHF Bands
			'70cm' => array('start' => 420000, 'end' => 450000, 'order' => 18),
			'33cm' => array('start' => 902000, 'end' => 928000, 'order' => 19),
			// SHF Bands (Microwave)
			'23cm' => array('start' => 1240000, 'end' => 1300000, 'order' => 20),
			'13cm' => array('start' => 2300000, 'end' => 2450000, 'order' => 21),
			'9cm' => array('start' => 3300000, 'end' => 3500000, 'order' => 22),
			'6cm' => array('start' => 5650000, 'end' => 5925000, 'order' => 23),
			'3cm' => array('start' => 10000000, 'end' => 10500000, 'order' => 24),
			'1.2cm' => array('start' => 24000000, 'end' => 24250000, 'order' => 25),
			// EHF Bands (Millimeter wave)
			'6mm' => array('start' => 47000000, 'end' => 47200000, 'order' => 26),
			'4mm' => array('start' => 75500000, 'end' => 81000000, 'order' => 27),
			'2.5mm' => array('start' => 119980000, 'end' => 120020000, 'order' => 28),
			'2mm' => array('start' => 142000000, 'end' => 149000000, 'order' => 29),
			'1mm' => array('start' => 241000000, 'end' => 250000000, 'order' => 30),
		);

		$results = array();

		foreach($result as $band) {
			if (isset($band_ranges[$band->band])) {
				$results[] = array(
					'band' => $band->band,
					'bandgroup' => $band->bandgroup,
					'start' => $band_ranges[$band->band]['start'],
					'end' => $band_ranges[$band->band]['end'],
					'order' => $band_ranges[$band->band]['order']
				);
			}
		}

		// Sort by frequency order
		usort($results, function($a, $b) {
			return $a['order'] - $b['order'];
		});

		return $results;
	}

	function all() {
		return $this->bandslots;
	}

	function get_worked_bands($award = 'None') {

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		// get all worked slots from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $location_list . ") AND COL_PROP_MODE != \"SAT\""
		);
		$worked_slots = array();
		foreach($data->result() as $row){
			array_push($worked_slots, $row->COL_BAND);
		}

		$SAT_data = $this->db->query(
			"SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $location_list . ") AND COL_PROP_MODE = \"SAT\""
		);

		foreach($SAT_data->result() as $row){
			array_push($worked_slots, strtoupper($row->COL_PROP_MODE));
		}

		// bring worked-slots in order of defined $bandslots
		$bandslots = $this->get_user_bands($award);

		$results = array();
		foreach($bandslots as $slot) {
			if(in_array($slot, $worked_slots)) {
				array_push($results, $slot);
			}
		}

		return $results;
	}

	function get_worked_bands_distances() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}
		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        // get all worked slots from database
        $sql = "SELECT distinct LOWER(COL_BAND) as COL_BAND FROM ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ")";

        $data = $this->db->query($sql);
        $worked_slots = array();
        foreach($data->result() as $row){
            array_push($worked_slots, $row->COL_BAND);
        }

        // bring worked-slots in order of defined $bandslots
		$bandslots = $this->get_user_bands();

		$results = array();
		foreach($bandslots as $slot) {
			if(in_array($slot, $worked_slots)) {
				array_push($results, $slot);
			}
		}
        return $results;
    }

	function get_worked_sats() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        // get all worked sats from database
        $sql = "SELECT distinct col_sat_name FROM ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ") and coalesce(col_sat_name, '') <> '' ORDER BY col_sat_name";

        $data = $this->db->query($sql);

        $worked_sats = array();
        foreach($data->result() as $row){
            array_push($worked_sats, $row->col_sat_name);
        }

        return $worked_sats;
    }

	function get_worked_bands_dok() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		// get all worked slots from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $location_list . ") AND COL_DARC_DOK IS NOT NULL AND COL_DARC_DOK != ''  AND COL_DXCC = 230 "
		);
		$worked_slots = array();
		foreach($data->result() as $row){
			array_push($worked_slots, $row->COL_BAND);
		}

		// bring worked-slots in order of defined $bandslots
		$bandslots = $this->get_user_bands('dok');

		$results = array();
		foreach($bandslots as $slot) {
			if(in_array($slot, $worked_slots)) {
				array_push($results, $slot);
			}
		}
		return $results;
	}

	function get_worked_powers() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return array();
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        // get all worked powers from database
        $sql = "SELECT distinct col_tx_pwr FROM ".$this->config->item('table_name')." WHERE station_id in (" . $location_list . ") ORDER BY col_tx_pwr";

        $data = $this->db->query($sql);

        $worked_powers = array();
        foreach($data->result() as $row) array_push($worked_powers, $row->col_tx_pwr);

        return $worked_powers;
    }

	function activateall() {
        $data = array(
            'active' => '1',
        );
		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));

        $this->db->update('bandxuser', $data);

        return true;
    }

    function deactivateall() {
        $data = array(
            'active' => '0',
        );
		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));

        $this->db->update('bandxuser', $data);

        return true;
    }

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		// Delete Mode
		$this->db->delete('bandxuser', array('id' => $clean_id));
	}

	function saveBand($id, $band) {
        $data = array(
			'active' 	 => $band['status']		== "true" ? '1' : '0',
			'cq' 		 => $band['cq'] 		== "true" ? '1' : '0',
			'dok' 		 => $band['dok'] 		== "true" ? '1' : '0',
			'dxcc' 		 => $band['dxcc'] 		== "true" ? '1' : '0',
			'iota' 		 => $band['iota'] 		== "true" ? '1' : '0',
			'pota' 		 => $band['pota'] 		== "true" ? '1' : '0',
			'sig' 		 => $band['sig'] 		== "true" ? '1' : '0',
			'sota'		 => $band['sota'] 		== "true" ? '1' : '0',
			'uscounties' => $band['uscounties'] == "true" ? '1' : '0',
			'was' 		 => $band['was'] 		== "true" ? '1' : '0',
			'wwff' 		 => $band['wwff'] 		== "true" ? '1' : '0',
			'vucc'		 => $band['vucc'] 		== "true" ? '1' : '0'
        );

		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));
        $this->db->where('bandxuser.id', $id);

        $this->db->update('bandxuser', $data);

        return true;
    }

	function saveBandAward($award, $status) {
		$data = array(
			$award 	 => $status == "true" ? '1' : '0',
        );

		$this->db->where('bandxuser.userid', $this->session->userdata('user_id'));

        $this->db->update('bandxuser', $data);

        return true;
    }

	function add() {
		$data = array(
			'band' 		=> xss_clean($this->input->post('band', true)),
			'bandgroup' => xss_clean($this->input->post('bandgroup', true)),
			'ssb'	 	=> xss_clean($this->input->post('ssbqrg', true)),
			'data' 		=> xss_clean($this->input->post('dataqrg', true)),
			'cw' 		=> xss_clean($this->input->post('cwqrg', true)),
		);

		$this->db->where('band', xss_clean($this->input->post('band', true)));
		$result = $this->db->get('bands');

		if ($result->num_rows() == 0) {
		   $this->db->insert('bands', $data);
		}

		$this->db->query("insert into bandxuser (bandid, userid, active, cq, dok, dxcc, iota, pota, sig, sota, uscounties, was, wwff, vucc)
		select bands.id, " . $this->session->userdata('user_id') . ", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1 from bands where band ='".$data['band']."' and not exists (select 1 from bandxuser where userid = " . $this->session->userdata('user_id') . " and bandid = bands.id);");
	}

	function getband($id) {
		$this->db->where('id', $id);
		return $this->db->get('bands');
	}

	function saveupdatedband($id, $band) {
		$data = array(
			'band' 		=> $band['band'],
			'bandgroup' => $band['bandgroup'],
			'ssb'	 	=> $band['ssbqrg'],
			'data' 		=> $band['dataqrg'],
			'cw' 		=> $band['cwqrg'],
        );

        $this->db->where('bands.id', $id);

        $this->db->update('bands', $data);

        return true;
	}

	function get_worked_bands_oqrs($station_id) {

		// get all worked slots from database
		$data = $this->db->query(
			"SELECT distinct LOWER(`COL_BAND`) as `COL_BAND` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $station_id . ") AND COL_PROP_MODE != \"SAT\""
		);
		$worked_slots = array();
		foreach($data->result() as $row){
			array_push($worked_slots, $row->COL_BAND);
		}

		$SAT_data = $this->db->query(
			"SELECT distinct LOWER(`COL_PROP_MODE`) as `COL_PROP_MODE` FROM `".$this->config->item('table_name')."` WHERE station_id in (" . $station_id . ") AND COL_PROP_MODE = \"SAT\""
		);

		foreach($SAT_data->result() as $row){
			array_push($worked_slots, strtoupper($row->COL_PROP_MODE));
		}

		// php5
		usort(
			$worked_slots,
			function($b, $a) {
				sscanf($a, '%f%s', $ac, $ar);
				sscanf($b, '%f%s', $bc, $br);
				if ($ar == $br) {
					return ($ac < $bc) ? -1 : 1;
				}
				return ($ar < $br) ? -1 : 1;
			}
		);

		// Only for php7+
		// usort(
		// 	$worked_slots,
		// 	function($b, $a) {
		// 		sscanf($a, '%f%s', $ac, $ar);
		// 		sscanf($b, '%f%s', $bc, $br);
		// 		return ($ar == $br) ? $ac <=> $bc : $ar <=> $br;
		// 	}
		// );

		return $worked_slots;
	}
}

?>
