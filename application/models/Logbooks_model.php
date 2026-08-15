<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logbooks_model extends CI_Model {

	// Cache for list_logbook_relationships to avoid redundant queries
	private $relationship_cache = array();
	private $slp_permission_column = null;

	private function get_slp_permission_column() {
		if ($this->slp_permission_column !== null) {
			return $this->slp_permission_column;
		}

		if ($this->db->field_exists('permission_level', 'station_logbooks_permissions')) {
			$this->slp_permission_column = 'permission_level';
			return $this->slp_permission_column;
		}

		if ($this->db->field_exists('access_level', 'station_logbooks_permissions')) {
			$this->slp_permission_column = 'access_level';
			return $this->slp_permission_column;
		}

		// Legacy fallback to avoid hard failures on partially migrated instances.
		$this->slp_permission_column = 'permission';
		return $this->slp_permission_column;
	}

    function show_all() {
		// Get owned logbooks and shared logbooks with access level
		$user_id = $this->session->userdata('user_id');
		$permission_column = $this->get_slp_permission_column();
		
		// If no user is logged in, return empty result
		if ($user_id === NULL || $user_id === FALSE) {
			$this->db->from('station_logbooks');
			$this->db->where('1 = 0'); // Always false condition
			return $this->db->get();
		}
		
		$this->db->select('station_logbooks.*, 
			CASE 
				WHEN station_logbooks.user_id = '.$this->db->escape($user_id).' THEN "owner" 
				ELSE slp.'.$permission_column.' 
			END as access_level', FALSE);
		$this->db->from('station_logbooks');
		$this->db->join('station_logbooks_permissions slp', 
			'slp.logbook_id = station_logbooks.logbook_id AND slp.user_id = '.$this->db->escape($user_id), 
			'left');
		$this->db->group_start();
			$this->db->where('station_logbooks.user_id', $user_id);
			$this->db->or_where('slp.user_id', $user_id);
		$this->db->group_end();
		$this->db->order_by('station_logbooks.logbook_name', 'ASC');
		
		return $this->db->get();
	}

	function CountAllStationLogbooks() {
		// count all logbooks
		$this->db->where('user_id =', NULL);
		$query = $this->db->get('station_logbooks');
		return $query->num_rows();
	}

	function add() {
		// Create data array with field values
		$data = array(
			'user_id' => $this->session->userdata('user_id'),
			'logbook_name' =>  xss_clean($this->input->post('stationLogbook_Name', true)),
		);

		// Insert Records
		$this->db->insert('station_logbooks', $data);
		$logbook_id = $this->db->insert_id();

		// check if user has no active logbook yet
		if ($this->session->userdata('active_station_logbook') === null) {
			// set logbook active
			$this->set_logbook_active($logbook_id);

			// update user session data
			$CI =& get_instance();
			$CI->load->model('user_model');
			$CI->user_model->update_session($this->session->userdata('user_id'));
		}

		return $logbook_id;
	}

	function CreateDefaultLogbook() {
		// Get the first USER ID from user table in the database
		$id = $this->db->get("users")->row()->user_id;
			
		$data = array(
			'user_id' => $id,
			'logbook_name' => "Default Logbook",
		);
				
		$this->db->insert('station_logbooks', $data);
		$logbook_id = $this->db->insert_id();

		$this->set_logbook_active($logbook_id, $id);
	}

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		// do not delete active logbook
		if ($this->session->userdata('active_station_logbook') === $clean_id) {
			return;
		}

		// Delete logbook
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', $id);
		$this->db->delete('station_logbooks'); 
	}

	function edit() {
		$data = array(
			'logbook_name' => xss_clean($this->input->post('station_logbook_name', true)),
		);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', xss_clean($this->input->post('logbook_id', true)));
		$this->db->update('station_logbooks', $data); 
	}

	function set_logbook_active($id, $user_id = null) {
		// Clean input
		$cleanId = xss_clean($id);

		// check if user_id is set
		if ($user_id === null) {
			$user_id = $this->session->userdata('user_id');
		} else {
			$user_id = xss_clean($user_id);
		}

		// be sure that logbook belongs to user
		if (!$this->check_logbook_is_accessible($cleanId)) {
			return;
		}

		$data = array(
			'active_station_logbook' => $cleanId,
		);

		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data); 
	}

	function logbook($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);
		$user_id = $this->session->userdata('user_id');

		// If no user is logged in, return empty result
		if ($user_id === NULL || $user_id === FALSE) {
			$this->db->from('station_logbooks');
			$this->db->where('1 = 0'); // Always false condition
			return $this->db->get();
		}

		// Get logbook if user owns it OR has shared access
		$this->db->select('station_logbooks.*');
		$this->db->from('station_logbooks');
		$this->db->join('station_logbooks_permissions slp', 
			'slp.logbook_id = station_logbooks.logbook_id AND slp.user_id = '.$this->db->escape($user_id), 
			'left');
		$this->db->where('station_logbooks.logbook_id', $clean_id);
		$this->db->group_start();
			$this->db->where('station_logbooks.user_id', $user_id);
			$this->db->or_where('slp.user_id', $user_id);
		$this->db->group_end();
		
		return $this->db->get();
	}

	function find_name($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', $clean_id);
		$query = $this->db->get('station_logbooks');
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
			{
				return $row->logbook_name;
			}
		}
		else{
			return "n/a";
		}
	}

	// Creates relationship between a logbook and a station location
	function create_logbook_location_link($logbook_id, $location_id) {
		// Clean ID
		$clean_logbook_id = $this->security->xss_clean($logbook_id);
		$clean_location_id = $this->security->xss_clean($location_id);

		// be sure that logbook belongs to user
		if (!$this->check_logbook_is_accessible($clean_logbook_id)) {
			return;
		}

		// be sure that station belongs to user
		$CI =& get_instance();
		$CI->load->model('Stations');
		if (!$CI->Stations->check_station_is_accessible($clean_location_id)) {
			return;
		}

		// Create data array with field values
		$data = array(
			'station_logbook_id' => $clean_logbook_id,
			'station_location_id' =>  $clean_location_id,
		);

		// Insert Record
		$this->db->insert('station_logbooks_relationship', $data); 
	}

	function relationship_exists($logbook_id, $location_id) {
		$this->db->where('station_logbook_id', $logbook_id);
		$this->db->where('station_location_id', $location_id);
		$query = $this->db->get('station_logbooks_relationship');
		
		if ($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	function public_slug_exists($slug) {
		$this->db->where('public_slug', $this->security->xss_clean($slug));
		$query = $this->db->get('station_logbooks');

		if ($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	function public_slug_exists_logbook_id($slug) {
		$this->db->where('public_slug', $this->security->xss_clean($slug));
		$query = $this->db->get('station_logbooks');

		if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
			{
				return $row->logbook_id;
			}
		}
		else{
			return false;
		}
	}

	/**
	 * Session-free lookup of a public logbook by slug.
	 */
	function public_logbook_by_slug($slug) {
		$clean_slug = $this->security->xss_clean($slug);
		if ($clean_slug === '' || $clean_slug === FALSE) {
			return false;
		}

		$select = 'logbook_id, logbook_name, user_id, public_slug, public_search, public_radio_status';
		if ($this->db->field_exists('public_name', 'station_logbooks')) {
			$select .= ', public_name';
		}

		$this->db->select($select);
		$this->db->where('public_slug', $clean_slug);
		$query = $this->db->get('station_logbooks');

		if ($query->num_rows() > 0) {
			return $query->row();
		}

		return false;
	}

	function public_station_callsigns($logbook_id) {
		$locations = $this->list_logbook_relationships($logbook_id);
		if (empty($locations)) {
			return array();
		}

		$this->db->distinct();
		$this->db->select('station_callsign');
		$this->db->from('station_profile');
		$this->db->where_in('station_id', $locations);
		$this->db->order_by('station_callsign', 'ASC');
		$query = $this->db->get();

		$callsigns = array();
		foreach ($query->result() as $row) {
			if (!empty($row->station_callsign)) {
				$callsigns[] = strtoupper($row->station_callsign);
			}
		}

		return $callsigns;
	}

	/**
	 * Identity payload for public visitor/OQRS pages (no session required).
	 */
	function public_identity_data($slug) {
		$logbook = $this->public_logbook_by_slug($slug);
		if (!$logbook) {
			return false;
		}

		$locations = $this->list_logbook_relationships($logbook->logbook_id);
		if (empty($locations)) {
			return false;
		}

		$callsigns = $this->public_station_callsigns($logbook->logbook_id);
		$primary = (count($callsigns) === 1) ? $callsigns[0] : '';
		$public_name = isset($logbook->public_name) ? trim((string) $logbook->public_name) : '';
		// Prefer callsign branding; fall back to public name, then internal logbook name
		if ($primary !== '') {
			$brand = $primary;
		} elseif ($public_name !== '') {
			$brand = $public_name;
		} else {
			$brand = $logbook->logbook_name;
		}

		return array(
			'slug' => $logbook->public_slug,
			'logbook_id' => $logbook->logbook_id,
			'logbook_name' => $logbook->logbook_name,
			'public_name' => $public_name,
			'logbook_settings' => $logbook,
			'logbooks_locations_array' => $locations,
			'station_callsigns' => $callsigns,
			'primary_callsign' => $primary,
			'brand_name' => $brand,
			'public_search_enabled' => (isset($logbook->public_search) && (int) $logbook->public_search === 1),
			'public_radio_status' => (isset($logbook->public_radio_status) && (int) $logbook->public_radio_status === 1),
			'owner_user_id' => $logbook->user_id,
		);
	}

	function public_slugs_by_user($user_id) {
		$clean_user_id = $this->security->xss_clean($user_id);

		$this->db->select('logbook_id, logbook_name, public_slug');
		$this->db->from('station_logbooks');
		$this->db->where('user_id', $clean_user_id);
		$this->db->where('public_slug IS NOT NULL', null, false);
		$this->db->where("public_slug != ''", null, false);
		$this->db->order_by('logbook_name', 'ASC');

		return $this->db->get();
	}

	function public_slugs_accessible_by_user($user_id) {
		$clean_user_id = $this->security->xss_clean($user_id);
		$permission_column = $this->get_slp_permission_column();

		$this->db->select('station_logbooks.logbook_id, station_logbooks.logbook_name, station_logbooks.public_slug,
			CASE
				WHEN station_logbooks.user_id = '.$this->db->escape($clean_user_id).' THEN "owner"
				ELSE slp.'.$permission_column.'
			END as access_level', FALSE);
		$this->db->from('station_logbooks');
		$this->db->join('station_logbooks_permissions slp',
			'slp.logbook_id = station_logbooks.logbook_id AND slp.user_id = '.$this->db->escape($clean_user_id),
			'left');
		$this->db->group_start();
			$this->db->where('station_logbooks.user_id', $clean_user_id);
			$this->db->or_where('slp.user_id', $clean_user_id);
		$this->db->group_end();
		$this->db->where('station_logbooks.public_slug IS NOT NULL', null, false);
		$this->db->where("station_logbooks.public_slug != ''", null, false);
		$this->db->order_by('station_logbooks.logbook_name', 'ASC');

		return $this->db->get();
	}

	function is_public_slug_available($slug) {
		// Clean public_slug
		$clean_slug = $this->security->xss_clean($slug);
		$this->db->where('public_slug', $clean_slug);
		$query = $this->db->get('station_logbooks');

		if ($query->num_rows() > 0){
			return false;
		}
		else{
			return true;
		}
	}

	function save_public_search($public_search, $logbook_id) {
		$data = array(
			'public_search' => xss_clean($public_search),
		);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', xss_clean($logbook_id));
		$this->db->update('station_logbooks', $data);
	}

	function save_public_name($public_name, $logbook_id) {
		if (!$this->db->field_exists('public_name', 'station_logbooks')) {
			return false;
		}

		$clean_name = trim(xss_clean($public_name));
		if ($clean_name === '') {
			$clean_name = null;
		} else {
			$clean_name = mb_substr($clean_name, 0, 100);
		}

		$data = array(
			'public_name' => $clean_name,
		);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', xss_clean($logbook_id));
		$this->db->update('station_logbooks', $data);
		return true;
	}

	function save_public_radio_status($public_radio_status, $logbook_id) {
		$data = array(
			'public_radio_status' => xss_clean($public_radio_status),
		);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', xss_clean($logbook_id));
		$this->db->update('station_logbooks', $data);
	}

	function save_public_slug($public_slug, $logbook_id) {
		$data = array(
			'public_slug' => xss_clean($public_slug),
		);

		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', xss_clean($logbook_id));
		$this->db->update('station_logbooks', $data);
	}

	function remove_public_slug($logbook_id) {

		$this->db->set('public_slug', null);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', xss_clean($logbook_id));
		$this->db->update('station_logbooks');
	}

	function list_logbook_relationships($logbook_id) {

		// Check cache first to avoid redundant queries
		if (isset($this->relationship_cache[$logbook_id])) {
			return $this->relationship_cache[$logbook_id];
		}

		$relationships_array = array();

		$this->db->where('station_logbook_id', $logbook_id);
		$query = $this->db->get('station_logbooks_relationship');
		
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
			{
				array_push($relationships_array, $row->station_location_id);
			}

			// Cache the result
			$this->relationship_cache[$logbook_id] = $relationships_array;
			return $relationships_array;
		}
		else{
			// Cache empty result
			$this->relationship_cache[$logbook_id] = array();
			return array();
		}
	}

	function list_logbooks_linked($logbook_id) {

		$relationships_array = array();

		$this->db->where('station_logbook_id', $logbook_id);
		$query = $this->db->get('station_logbooks_relationship');
		

		if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
			{
				array_push($relationships_array, $row->station_location_id);
			}

			$current_user_id = $this->session->userdata('user_id');
			
			// Handle NULL user_id to prevent SQL errors
			if ($current_user_id === NULL || $current_user_id === FALSE) {
				$is_shared_case = '1 as is_shared'; // All are shared if no user logged in
			} else {
				$is_shared_case = 'CASE WHEN station_profile.user_id = '.$this->db->escape($current_user_id).' THEN 0 ELSE 1 END as is_shared';
			}
			
			$this->db->select('station_profile.*, 
				dxcc_entities.name as station_country, 
				dxcc_entities.end as end,
				users.user_callsign as owner_callsign,
				'.$is_shared_case, FALSE);
			$this->db->where_in('station_id', $relationships_array);
			$this->db->join('dxcc_entities','station_profile.station_dxcc = dxcc_entities.adif','left outer');
			$this->db->join('users','station_profile.user_id = users.user_id','left');
			$query = $this->db->get('station_profile');
			
			return $query;
		}
		else{
			return false;
		}
	}

	function delete_relationship($logbook_id, $station_id) {
		// Clean ID
		$clean_logbook_id = $this->security->xss_clean($logbook_id);
		$clean_station_id = $this->security->xss_clean($station_id);

		// be sure that logbook belongs to user
		if (!$this->check_logbook_is_accessible($clean_logbook_id)) {
			return;
		}

		// be sure that station belongs to user
		$CI =& get_instance();
		$CI->load->model('Stations');
		if (!$CI->Stations->check_station_is_accessible($clean_station_id)) {
			return;
		}

		// Delete relationship
		$this->db->where('station_logbook_id', $clean_logbook_id);
		$this->db->where('station_location_id', $clean_station_id);
		$this->db->delete('station_logbooks_relationship'); 
	}

	public function check_logbook_is_accessible($id, $min_level = 'read') {
	    // First check if user is the owner (existing behavior - highest priority)
		$permission_column = $this->get_slp_permission_column();
	    $this->db->select('logbook_id');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', $id);
		$query = $this->db->get('station_logbooks');
		if ($query->num_rows() == 1) {
			return true; // Owner always has full access
		}
		
		// Check if user has shared access via permissions table
		$this->db->select($permission_column.' as permission_level', FALSE);
		$this->db->where('logbook_id', $id);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$query = $this->db->get('station_logbooks_permissions');
		
		if ($query->num_rows() == 1) {
			$permission = $query->row()->permission_level;
			
			// Map permission levels to hierarchy
			$levels = array('read' => 1, 'write' => 2, 'admin' => 3);
			$user_level = isset($levels[$permission]) ? $levels[$permission] : 0;
			$required_level = isset($levels[$min_level]) ? $levels[$min_level] : 1;
			
			return ($user_level >= $required_level);
		}
		
		return false;
	}

	public function is_logbook_owner($id) {
		// Check if current user is the owner of the logbook
		$this->db->select('logbook_id');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('logbook_id', $id);
		$query = $this->db->get('station_logbooks');
		return ($query->num_rows() == 1);
	}

	public function get_user_permission($logbook_id, $user_id) {
		// Get the permission level for a specific user on a specific logbook
		// Returns 'owner', permission level, or null
		$permission_column = $this->get_slp_permission_column();
		
		// Check if user is owner
		$this->db->select('logbook_id');
		$this->db->where('user_id', $user_id);
		$this->db->where('logbook_id', $logbook_id);
		$query = $this->db->get('station_logbooks');
		if ($query->num_rows() == 1) {
			return 'owner';
		}
		
		// Check permissions table
		$this->db->select($permission_column.' as permission_level', FALSE);
		$this->db->where('logbook_id', $logbook_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('station_logbooks_permissions');
		
		if ($query->num_rows() == 1) {
			return $query->row()->permission_level;
		}
		
		return null;
	}

	public function add_logbook_permission($logbook_id, $user_id, $permission_level = 'read') {
		// Add a user to a logbook with specified permission level
		// Only owner or admin can add users
		// Returns array with 'success' and 'is_new' keys
		$permission_column = $this->get_slp_permission_column();
		
		$clean_logbook_id = $this->security->xss_clean($logbook_id);
		$clean_user_id = $this->security->xss_clean($user_id);
		$clean_permission = $this->security->xss_clean($permission_level);
		
		// Validate permission level
		$valid_permissions = array('read', 'write', 'admin');
		if (!in_array($clean_permission, $valid_permissions)) {
			return array('success' => false, 'is_new' => false);
		}
		
		// Check if current user has admin rights or is owner
		if (!$this->is_logbook_owner($clean_logbook_id) && 
			!$this->check_logbook_is_accessible($clean_logbook_id, 'admin')) {
			return array('success' => false, 'is_new' => false);
		}
		
		// Check if permission already exists
		$this->db->where('logbook_id', $clean_logbook_id);
		$this->db->where('user_id', $clean_user_id);
		$existing = $this->db->get('station_logbooks_permissions');
		
		$is_new = ($existing->num_rows() == 0);
		
		if ($existing->num_rows() > 0) {
			// Update existing permission
			$data = array(
				$permission_column => $clean_permission,
				'modified' => date('Y-m-d H:i:s'),
			);
			$this->db->where('logbook_id', $clean_logbook_id);
			$this->db->where('user_id', $clean_user_id);
			$this->db->update('station_logbooks_permissions', $data);
		} else {
			// Insert new permission
			$data = array(
				'logbook_id' => $clean_logbook_id,
				'user_id' => $clean_user_id,
				$permission_column => $clean_permission,
			);
			$this->db->insert('station_logbooks_permissions', $data);
		}
		
		return array('success' => true, 'is_new' => $is_new);
	}

	public function remove_logbook_permission($logbook_id, $user_id) {
		// Remove a user's access to a logbook
		// Only owner or admin can remove users
		
		$clean_logbook_id = $this->security->xss_clean($logbook_id);
		$clean_user_id = $this->security->xss_clean($user_id);
		
		// Check if current user has admin rights or is owner
		if (!$this->is_logbook_owner($clean_logbook_id) && 
			!$this->check_logbook_is_accessible($clean_logbook_id, 'admin')) {
			return false;
		}
		
		$this->db->where('logbook_id', $clean_logbook_id);
		$this->db->where('user_id', $clean_user_id);
		$this->db->delete('station_logbooks_permissions');
		
		return true;
	}

	public function list_logbook_collaborators($logbook_id) {
		// Get all users with access to a logbook (excluding owner)
		// Returns array of users with their permission levels
		$permission_column = $this->get_slp_permission_column();
		
		$clean_logbook_id = $this->security->xss_clean($logbook_id);
		
		// Get owner information
		$this->db->select('station_logbooks.user_id, users.user_callsign, users.user_email, "owner" as permission_level, "" as created_at');
		$this->db->from('station_logbooks');
		$this->db->join('users', 'users.user_id = station_logbooks.user_id');
		$this->db->where('station_logbooks.logbook_id', $clean_logbook_id);
		$owner_query = $this->db->get();
		
		// Get shared users
		$this->db->select('slp.user_id, users.user_callsign, users.user_email, slp.'.$permission_column.' as permission_level, slp.created_at', FALSE);
		$this->db->from('station_logbooks_permissions slp');
		$this->db->join('users', 'users.user_id = slp.user_id');
		$this->db->where('slp.logbook_id', $clean_logbook_id);
		$this->db->order_by('slp.created_at', 'DESC');
		$shared_query = $this->db->get();
		
		$results = array();
		
		// Add owner first
		if ($owner_query->num_rows() > 0) {
			$results[] = $owner_query->row();
		}
		
		// Add shared users
		if ($shared_query->num_rows() > 0) {
			foreach ($shared_query->result() as $row) {
				$results[] = $row;
			}
		}
		
		return $results;
	}

	public function find_active_station_logbook_from_userid($userid) {
		$this->db->select('active_station_logbook');
		$this->db->where('user_id', $userid);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0){
			foreach ($query->result() as $row)
			{
				return $row->active_station_logbook;
			}
		} else {
			return 0;
		}
	}

	function public_search_enabled($logbook_id) {
		$this->db->select('public_search');
		$this->db->where('logbook_id', $logbook_id);

		$query = $this->db->get('station_logbooks');

      return $query->result_array()[0]['public_search'];
	}

	/**
	 * Persist the last-used station location for a logbook (per user).
	 */
	public function save_last_location($logbook_id, $station_id) {
		$clean_logbook_id = $this->security->xss_clean($logbook_id);
		$clean_station_id = $this->security->xss_clean($station_id);

		if (empty($clean_logbook_id) || empty($clean_station_id) || $clean_station_id === '0') {
			return false;
		}

		if (!$this->check_logbook_is_accessible($clean_logbook_id)) {
			return false;
		}

		$CI =& get_instance();
		$CI->load->model('Stations');
		if (!$CI->Stations->check_station_is_accessible($clean_station_id)) {
			return false;
		}

		$CI->load->model('user_options_model');
		$CI->user_options_model->set_option(
			'logbook',
			'last_location',
			array((string) $clean_logbook_id => (string) $clean_station_id)
		);

		return true;
	}

	/**
	 * Read the last-used station location for a logbook (per user).
	 */
	public function get_last_location($logbook_id) {
		$clean_logbook_id = $this->security->xss_clean($logbook_id);

		if (empty($clean_logbook_id)) {
			return null;
		}

		$CI =& get_instance();
		$CI->load->model('user_options_model');
		$query = $CI->user_options_model->get_options(
			'logbook',
			array(
				'option_name' => 'last_location',
				'option_key' => (string) $clean_logbook_id,
			)
		);

		if ($query->num_rows() > 0) {
			return $query->row()->option_value;
		}

		return null;
	}

	/**
	 * Activate a stored location for the given logbook when valid.
	 */
	public function restore_last_location($logbook_id) {
		$last_location = $this->get_last_location($logbook_id);

		if ($last_location === null || $last_location === '' || $last_location === '0') {
			return array('changed' => false);
		}

		$CI =& get_instance();
		$CI->load->model('Stations');

		if (!$CI->Stations->check_station_is_accessible($last_location)) {
			return array('changed' => false);
		}

		$current_active = $CI->Stations->find_active();
		if ((string) $current_active === (string) $last_location) {
			return array(
				'changed' => false,
				'station_id' => $last_location,
			);
		}

		$CI->Stations->set_active($current_active, $last_location);

		$station = $CI->Stations->profile_clean($last_location);
		$station_name = ($station && isset($station->station_profile_name))
			? $station->station_profile_name
			: '';

		return array(
			'changed' => true,
			'station_id' => $last_location,
			'station_name' => $station_name,
		);
	}

	/**
	 * Switch logbook and restore or seed the last-used location.
	 */
	public function switch_logbook($new_logbook_id) {
		$clean_new = xss_clean($new_logbook_id);

		if (!$this->check_logbook_is_accessible($clean_new)) {
			return array('success' => false);
		}

		$CI =& get_instance();
		$CI->load->model('Stations');

		$old_logbook_id = $CI->session->userdata('active_station_logbook');
		$current_station_id = $CI->Stations->find_active();

		if ($old_logbook_id && $current_station_id && $current_station_id !== '0') {
			$this->save_last_location($old_logbook_id, $current_station_id);
		}

		$this->set_logbook_active($clean_new);

		$had_stored_location = $this->get_last_location($clean_new) !== null;
		$restore_result = $this->restore_last_location($clean_new);

		if (!$had_stored_location) {
			$current_station_id = $CI->Stations->find_active();
			if ($current_station_id && $current_station_id !== '0') {
				$this->save_last_location($clean_new, $current_station_id);
			}
		}

		return array(
			'success' => true,
			'location_changed' => !empty($restore_result['changed']),
			'station_name' => $restore_result['station_name'] ?? null,
		);
	}

	/**
	 * Remove last-location preferences pointing at a deleted station.
	 */
	public function clear_last_location_for_station($station_id) {
		$clean_station_id = $this->security->xss_clean($station_id);
		$user_id = $this->session->userdata('user_id');

		if (empty($clean_station_id) || empty($user_id)) {
			return;
		}

		$this->db->where('user_id', $user_id);
		$this->db->where('option_type', 'logbook');
		$this->db->where('option_name', 'last_location');
		$this->db->where('option_value', (string) $clean_station_id);
		$this->db->delete('user_options');
	}
}
?>
