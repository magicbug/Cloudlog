<?php

class Awards_model extends CI_Model {

	/**
	 * Get user's award preferences
	 * @return object|null Award preferences for current user
	 */
	function get_user_awards() {
		$this->db->from('awardxuser');
		$this->db->where('userid', $this->session->userdata('user_id'));
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			return $result->row();
		}
		
		// If no preferences exist, create default ones
		$this->create_default_awards();
		return $this->get_user_awards();
	}

	/**
	 * Create default award preferences for current user
	 */
	function create_default_awards() {
		$data = array(
			'userid' => $this->session->userdata('user_id'),
			'cq' => 1,
			'dok' => 1,
			'dxcc' => 1,
			'ffma' => 1,
			'iota' => 1,
			'gridmaster_dl' => 1,
			'gridmaster_lx' => 1,
			'gridmaster_ja' => 1,
			'gridmaster_us' => 1,
			'gridmaster_uk' => 1,
			'gmdxsummer' => 1,
			'pota' => 1,
			'sig' => 1,
			'sota' => 1,
			'uscounties' => 1,
			'vucc' => 1,
			'wab' => 1,
			'waja' => 1,
			'was' => 1,
			'wwff' => 1,
		);

		$this->db->insert('awardxuser', $data);
	}

	/**
	 * Save a single award preference for current user
	 * @param string $award_type The award type (e.g., 'cq', 'dxcc', 'pota')
	 * @param mixed $value The value (true/false or 1/0)
	 */
	function save_single_award($award_type, $value) {
		// Validate award type to prevent SQL injection
		$valid_awards = array(
			'cq', 'dok', 'dxcc', 'ffma', 'iota',
			'gridmaster_dl', 'gridmaster_lx', 'gridmaster_ja', 'gridmaster_us', 'gridmaster_uk',
			'gmdxsummer', 'pota', 'sig', 'sota', 'uscounties', 'vucc', 'wab', 'waja', 'was', 'wwff'
		);
		
		if (!in_array($award_type, $valid_awards)) {
			return false;
		}
		
		$data = array(
			$award_type => ($value == "true" || $value == "1" || $value === true) ? '1' : '0'
		);

		$this->db->where('userid', $this->session->userdata('user_id'));
		$this->db->update('awardxuser', $data);

		return true;
	}

	/**
	 * Save award preferences for current user
	 * @param array $awards Array of award settings
	 */
	function save_awards($awards) {
		$data = array(
			'cq' 			=> $awards['cq'] 			== "true" ? '1' : '0',
			'dok' 			=> $awards['dok'] 			== "true" ? '1' : '0',
			'dxcc' 			=> $awards['dxcc'] 			== "true" ? '1' : '0',
			'ffma' 			=> $awards['ffma'] 			== "true" ? '1' : '0',
			'iota' 			=> $awards['iota'] 			== "true" ? '1' : '0',
			'gridmaster_dl' => $awards['gridmaster_dl'] == "true" ? '1' : '0',
			'gridmaster_lx' => $awards['gridmaster_lx'] == "true" ? '1' : '0',
			'gridmaster_ja' => $awards['gridmaster_ja'] == "true" ? '1' : '0',
			'gridmaster_us' => $awards['gridmaster_us'] == "true" ? '1' : '0',
			'gridmaster_uk' => $awards['gridmaster_uk'] == "true" ? '1' : '0',
			'gmdxsummer' 	=> $awards['gmdxsummer'] 	== "true" ? '1' : '0',
			'pota' 			=> $awards['pota'] 			== "true" ? '1' : '0',
			'sig' 			=> $awards['sig'] 			== "true" ? '1' : '0',
			'sota' 			=> $awards['sota'] 			== "true" ? '1' : '0',
			'uscounties' 	=> $awards['uscounties'] 	== "true" ? '1' : '0',
			'vucc' 			=> $awards['vucc'] 			== "true" ? '1' : '0',
			'wab' 			=> $awards['wab'] 			== "true" ? '1' : '0',
			'waja' 			=> $awards['waja'] 			== "true" ? '1' : '0',
			'was' 			=> $awards['was'] 			== "true" ? '1' : '0',
			'wwff' 			=> $awards['wwff'] 			== "true" ? '1' : '0',
		);

		$this->db->where('userid', $this->session->userdata('user_id'));
		$this->db->update('awardxuser', $data);

		return true;
	}

	/**
	 * Activate all awards for current user
	 */
	function activateall() {
		$data = array(
			'cq' => 1,
			'dok' => 1,
			'dxcc' => 1,
			'ffma' => 1,
			'iota' => 1,
			'gridmaster_dl' => 1,
			'gridmaster_lx' => 1,
			'gridmaster_ja' => 1,
			'gridmaster_us' => 1,
			'gridmaster_uk' => 1,
			'gmdxsummer' => 1,
			'pota' => 1,
			'sig' => 1,
			'sota' => 1,
			'uscounties' => 1,
			'vucc' => 1,
			'wab' => 1,
			'waja' => 1,
			'was' => 1,
			'wwff' => 1,
		);

		$this->db->where('userid', $this->session->userdata('user_id'));
		$this->db->update('awardxuser', $data);

		return true;
	}

	/**
	 * Deactivate all awards for current user
	 */
	function deactivateall() {
		$data = array(
			'cq' => 0,
			'dok' => 0,
			'dxcc' => 0,
			'ffma' => 0,
			'iota' => 0,
			'gridmaster_dl' => 0,
			'gridmaster_lx' => 0,
			'gridmaster_ja' => 0,
			'gridmaster_us' => 0,
			'gridmaster_uk' => 0,
			'gmdxsummer' => 0,
			'pota' => 0,
			'sig' => 0,
			'sota' => 0,
			'uscounties' => 0,
			'vucc' => 0,
			'wab' => 0,
			'waja' => 0,
			'was' => 0,
			'wwff' => 0,
		);

		$this->db->where('userid', $this->session->userdata('user_id'));
		$this->db->update('awardxuser', $data);

		return true;
	}
}
