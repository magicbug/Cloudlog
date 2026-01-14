<?php

class Note extends CI_Model {

	function list_all($api_key = null, $filters = array()) {
        if ($api_key == null) {
			$user_id = $this->session->userdata('user_id');
		} else {
			$CI =& get_instance();
			$CI->load->model('api_model');
			if (strpos($this->api_model->access($api_key), 'r') !== false) {
				$this->api_model->update_last_used($api_key);
				$user_id = $this->api_model->key_userid($api_key);
			}
		}
		
		$this->db->where('user_id', $user_id);

		$category = isset($filters['category']) ? trim($filters['category']) : '';
		$search = isset($filters['search']) ? trim($filters['search']) : '';
		$date_from = isset($filters['date_from']) ? trim($filters['date_from']) : '';
		$date_to = isset($filters['date_to']) ? trim($filters['date_to']) : '';

		if ($category !== '') {
			$this->db->where('cat', xss_clean($category));
		}

		if ($search !== '') {
			$search_clean = xss_clean($search);
			$this->db->group_start();
			$this->db->like('title', $search_clean);
			$this->db->or_like('note', $search_clean);
			$this->db->group_end();
		}

		if ($date_from !== '') {
			$date_from_clean = xss_clean($date_from);
			$this->db->where('DATE(created_at) >=', $date_from_clean);
		}

		if ($date_to !== '') {
			$date_to_clean = xss_clean($date_to);
			$this->db->where('DATE(created_at) <=', $date_to_clean);
		}

		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('notes');
	}

	function list_categories() {
		$user_id = $this->session->userdata('user_id');
		$this->db->distinct();
		$this->db->select('cat');
		$this->db->where('user_id', $user_id);
		$this->db->where('cat IS NOT NULL');
		$this->db->where('cat !=', '');
		$this->db->order_by('cat', 'ASC');
		$query = $this->db->get('notes');
		return array_map(function($row) { return $row->cat; }, $query->result());
	}

	function add() {
 		$chosen_category = trim($this->input->post('new_category')); 
 		if ($chosen_category === '') {
 			$chosen_category = $this->input->post('category');
 		}
 		$chosen_category = $chosen_category === '' ? 'General' : $chosen_category;

 		$data = array(
			'cat' => xss_clean($chosen_category),
			'title' => xss_clean($this->input->post('title')),
			'note' => xss_clean($this->input->post('content')),
			'user_id' => $this->session->userdata('user_id')
		);

		$this->db->insert('notes', $data);
	}

	function edit() {
		$chosen_category = trim($this->input->post('new_category'));
		if ($chosen_category === '') {
			$chosen_category = $this->input->post('category');
		}
		$chosen_category = $chosen_category === '' ? 'General' : $chosen_category;

		$data = array(
			'cat' => xss_clean($chosen_category),
			'title' => xss_clean($this->input->post('title')),
			'note' => xss_clean($this->input->post('content'))
		);

		$created_at = trim($this->input->post('created_at'));
		if ($created_at !== '') {
			$data['created_at'] = xss_clean($created_at);
		}

		$this->db->where('id', xss_clean($this->input->post('id')));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->update('notes', $data);
	}

	function delete($id) {
		$this->db->delete('notes', array('id' => xss_clean($id), 'user_id' =>$this->session->userdata('user_id')));
	}

	function view($id) {
		// Get Note
		$this->db->where('id', xss_clean($id));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->get('notes');
	}

	function ClaimAllNotes($id = NULL) {
		// if $id is empty then use session user_id
		if (empty($id)) {
			// Get the first USER ID from user table in the database
			$id = $this->db->get("users")->row()->user_id;
		}

		$data = array(
				'user_id' => $id,
		);
			
		$this->db->update('notes', $data);
	}

	function CountAllNotes() {
		// count all notes
		$this->db->where('user_id =', NULL);
		$query = $this->db->get('notes');
		return $query->num_rows();
	}

	function replace_category($from, $to) {
		$user_id = $this->session->userdata('user_id');
		$from_clean = xss_clean(trim($from));
		$to_clean = xss_clean(trim($to));
		if ($from_clean === '') {
			return 0;
		}
		if ($to_clean === '') {
			$to_clean = 'General';
		}
		$this->db->where('user_id', $user_id);
		$this->db->where('cat', $from_clean);
		$this->db->update('notes', array('cat' => $to_clean));
		return $this->db->affected_rows();
	}

}

?>
