<?php

class Note extends CI_Model {

	private function is_public_station_diary_enabled() {
		$CI =& get_instance();
		$configValue = $CI->config->item('public_station_diary_enabled');
		if ($configValue === NULL) {
			$configValue = TRUE;
		}

		if (isset($CI->optionslib)) {
			$optionValue = $CI->optionslib->get_option('public_station_diary_enabled');
			if ($optionValue !== NULL && $optionValue !== '') {
				return ($optionValue === '1' || $optionValue === 'true' || $optionValue === 1 || $optionValue === TRUE);
			}
		}

		return (bool)$configValue;
	}

	private function touch_public_diary_cache_version($user_id) {
		$this->load->helper('file');
		$cacheVersionFile = APPPATH . 'cache/station_diary_' . (int)$user_id . '_version.txt';
		$versionValue = (string)microtime(TRUE) . '_' . mt_rand(1000, 9999);

		$written = @write_file($cacheVersionFile, $versionValue);
		if ($written !== FALSE) {
			return;
		}

		$written = @file_put_contents($cacheVersionFile, $versionValue, LOCK_EX);
		if ($written !== FALSE) {
			return;
		}

		$CI =& get_instance();
		if (!isset($CI->cache)) {
			$CI->load->driver('cache', array('adapter' => 'file'));
		}
		if (isset($CI->cache)) {
			@($CI->cache->clean());
		}
	}

	public function invalidate_public_diary_cache_for_note($note_id, $user_id = NULL) {
		$this->db->select('id, user_id, cat, is_public');
		$this->db->from('notes');
		$this->db->where('id', (int)$note_id);
		if ($user_id !== NULL) {
			$this->db->where('user_id', (int)$user_id);
		}
		$note = $this->db->get()->row();

		if ($note && $this->is_station_diary_category($note->cat) && (int)$note->is_public === 1) {
			$this->touch_public_diary_cache_version((int)$note->user_id);
		}
	}

	public function get_public_diary_cache_version($user_id) {
		$this->load->helper('file');
		$cacheVersionFile = APPPATH . 'cache/station_diary_' . (int)$user_id . '_version.txt';
		if (!file_exists($cacheVersionFile)) {
			return '1';
		}

		$value = @file_get_contents($cacheVersionFile);
		if ($value === FALSE || trim($value) === '') {
			return '1';
		}

		return trim($value);
	}

	private function is_station_diary_category($category) {
		return strtoupper(trim((string)$category)) === 'STATION DIARY';
	}

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
		$isStationDiary = $this->is_station_diary_category($chosen_category);

		$is_public = $this->input->post('is_public') ? 1 : 0;
		$include_qso_summary = $this->input->post('include_qso_summary') ? 1 : 0;
		$logbook_id = $this->input->post('logbook_id');

		if (!$this->is_public_station_diary_enabled() || !$isStationDiary) {
			$is_public = 0;
			$include_qso_summary = 0;
		}

 		$data = array(
			'cat' => xss_clean($chosen_category),
			'title' => xss_clean($this->input->post('title')),
			'note' => xss_clean($this->input->post('content')),
			'user_id' => $this->session->userdata('user_id'),
			'is_public' => $is_public,
			'include_qso_summary' => $include_qso_summary,
			'logbook_id' => $logbook_id ? xss_clean($logbook_id) : NULL,
			'qso_date_start' => $this->input->post('qso_date_start') ? xss_clean($this->input->post('qso_date_start')) : NULL,
			'qso_date_end' => $this->input->post('qso_date_end') ? xss_clean($this->input->post('qso_date_end')) : NULL,
			'qso_satellite_only' => $this->input->post('qso_satellite_only') ? 1 : 0,
		);

		$this->db->insert('notes', $data);
		$note_id = $this->db->insert_id();

		if ($isStationDiary && $is_public == 1) {
			$this->touch_public_diary_cache_version($this->session->userdata('user_id'));
		}

		return $note_id;
	}

	function edit() {
		$chosen_category = trim($this->input->post('new_category'));
		if ($chosen_category === '') {
			$chosen_category = $this->input->post('category');
		}
		$chosen_category = $chosen_category === '' ? 'General' : $chosen_category;
		$isStationDiary = $this->is_station_diary_category($chosen_category);

		$note_id = xss_clean($this->input->post('id'));
		$user_id = $this->session->userdata('user_id');

		$existing = $this->db->get_where('notes', array('id' => $note_id, 'user_id' => $user_id))->row();

		$is_public = $this->input->post('is_public') ? 1 : 0;
		$include_qso_summary = $this->input->post('include_qso_summary') ? 1 : 0;
		$logbook_id = $this->input->post('logbook_id');

		if (!$this->is_public_station_diary_enabled() || !$isStationDiary) {
			$is_public = 0;
			$include_qso_summary = 0;
		}

		$data = array(
			'cat' => xss_clean($chosen_category),
			'title' => xss_clean($this->input->post('title')),
			'note' => xss_clean($this->input->post('content')),
			'is_public' => $is_public,
			'include_qso_summary' => $include_qso_summary,
			'logbook_id' => $logbook_id ? xss_clean($logbook_id) : NULL,
			'qso_date_start' => $this->input->post('qso_date_start') ? xss_clean($this->input->post('qso_date_start')) : NULL,
			'qso_date_end' => $this->input->post('qso_date_end') ? xss_clean($this->input->post('qso_date_end')) : NULL,
			'qso_satellite_only' => $this->input->post('qso_satellite_only') ? 1 : 0,
		);

		$created_at = trim($this->input->post('created_at'));
		if ($created_at !== '') {
			if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $created_at)) {
				$timePart = date('H:i:s');
				if ($existing && !empty($existing->created_at) && $existing->created_at !== '0000-00-00 00:00:00') {
					$existingTimestamp = strtotime($existing->created_at);
					if ($existingTimestamp !== FALSE) {
						$timePart = date('H:i:s', $existingTimestamp);
					}
				}

				$data['created_at'] = xss_clean($created_at . ' ' . $timePart);
			} else {
				$parsedCreatedAt = strtotime($created_at);
				if ($parsedCreatedAt !== FALSE) {
					$data['created_at'] = xss_clean(date('Y-m-d H:i:s', $parsedCreatedAt));
				}
			}
		}

		$this->db->where('id', $note_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('notes', $data);

		$wasPublicDiary = ($existing && $this->is_station_diary_category($existing->cat) && (int)$existing->is_public === 1);
		$isPublicDiary = ($isStationDiary && (int)$is_public === 1);

		if ($wasPublicDiary || $isPublicDiary) {
			$this->touch_public_diary_cache_version($user_id);
		}

		return $note_id;
	}

	function delete($id) {
		$clean_id = xss_clean($id);
		$user_id = $this->session->userdata('user_id');

		$existing = $this->db->get_where('notes', array('id' => $clean_id, 'user_id' => $user_id))->row();

		$this->delete_diary_images_for_entry($clean_id, $user_id);
		$this->db->delete('notes', array('id' => $clean_id, 'user_id' => $user_id));

		if ($existing && $this->is_station_diary_category($existing->cat) && (int)$existing->is_public === 1) {
			$this->touch_public_diary_cache_version($user_id);
		}
	}

	public function add_diary_images($diary_id, $images = array()) {
		if (empty($images)) {
			return;
		}

		$existingCount = $this->db->where('diary_id', (int)$diary_id)->count_all_results('diary_images');
		$batch = array();
		$sort = (int)$existingCount;

		foreach ($images as $image) {
			if (!isset($image['filename'])) {
				continue;
			}

			$batch[] = array(
				'diary_id' => (int)$diary_id,
				'filename' => xss_clean($image['filename']),
				'caption' => isset($image['caption']) ? xss_clean($image['caption']) : NULL,
				'sort_order' => $sort,
			);
			$sort++;
		}

		if (!empty($batch)) {
			if (!$this->db->table_exists('diary_images')) {
				log_message('error', 'add_diary_images: diary_images table does not exist');
				return false;
			}
			$this->db->insert_batch('diary_images', $batch);
		}
		return true;
	}

	public function get_diary_images($diary_ids = array()) {
		if (empty($diary_ids)) {
			return array();
		}

		if (!$this->db->table_exists('diary_images')) {
			return array();
		}

		$this->db->where_in('diary_id', $diary_ids);
		$this->db->order_by('sort_order', 'ASC');
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get('diary_images');

		$mapped = array();
		foreach ($query->result() as $row) {
			if (!isset($mapped[$row->diary_id])) {
				$mapped[$row->diary_id] = array();
			}
			$mapped[$row->diary_id][] = $row;
		}

		return $mapped;
	}

	public function delete_diary_image_by_id($image_id, $user_id) {
		// Get image info and verify ownership through the note
		$this->db->select('diary_images.id, diary_images.filename, diary_images.diary_id');
		$this->db->from('diary_images');
		$this->db->join('notes', 'notes.id = diary_images.diary_id');
		$this->db->where('diary_images.id', (int)$image_id);
		$this->db->where('notes.user_id', (int)$user_id);
		$image = $this->db->get()->row();

		if (!$image) {
			return false;
		}

		// Delete physical file
		$filePath = FCPATH . ltrim($image->filename, '/');
		if (file_exists($filePath)) {
			@unlink($filePath);
		}

		// Delete database record
		$this->db->where('id', (int)$image_id);
		$this->db->delete('diary_images');

		// Touch cache if this is a public diary entry
		$this->db->select('notes.is_public, notes.cat, notes.user_id');
		$this->db->from('notes');
		$this->db->where('notes.id', (int)$image->diary_id);
		$note = $this->db->get()->row();

		if ($note && $this->is_station_diary_category($note->cat) && (int)$note->is_public === 1) {
			$this->touch_public_diary_cache_version($note->user_id);
		}

		return true;
	}

	public function delete_diary_images_for_entry($diary_id, $user_id = NULL) {
		$this->db->select('diary_images.id, diary_images.filename');
		$this->db->from('diary_images');
		$this->db->join('notes', 'notes.id = diary_images.diary_id');
		$this->db->where('diary_images.diary_id', (int)$diary_id);
		if ($user_id !== NULL) {
			$this->db->where('notes.user_id', (int)$user_id);
		}
		$images = $this->db->get()->result();

		foreach ($images as $image) {
			$filePath = FCPATH . ltrim($image->filename, '/');
			if (file_exists($filePath)) {
				@unlink($filePath);
			}
		}

		$this->db->where('diary_id', (int)$diary_id);
		$this->db->delete('diary_images');
	}

	public function resolve_public_user_by_callsign($callsign) {
		if (!$this->is_public_station_diary_enabled()) {
			return array('status' => 'disabled');
		}

		$clean_callsign = strtoupper(trim($this->security->xss_clean($callsign)));
		if ($clean_callsign === '') {
			return array('status' => 'not_found');
		}

		$this->db->select('user_id, user_callsign, user_date_format');
		$this->db->from($this->config->item('auth_table'));
		$this->db->where('UPPER(user_callsign)', $clean_callsign);
		$query = $this->db->get();

		if ($query->num_rows() === 0) {
			return array('status' => 'not_found');
		}

		if ($query->num_rows() > 1) {
			return array('status' => 'duplicate');
		}

		$user = $query->row();
		return array(
			'status' => 'ok',
			'user_id' => (int)$user->user_id,
			'callsign' => $user->user_callsign,
			'user_date_format' => $user->user_date_format ?? null,
		);
	}

	public function count_public_station_diary_entries($user_id) {
		$this->db->from('notes');
		$this->db->where('user_id', (int)$user_id);
		$this->db->where('cat', 'Station Diary');
		$this->db->where('is_public', 1);
		return (int)$this->db->count_all_results();
	}

	public function count_public_station_diary_search_results($user_id, $query) {
		$this->db->from('notes');
		$this->db->where('user_id', (int)$user_id);
		$this->db->where('cat', 'Station Diary');
		$this->db->where('is_public', 1);
		$this->db->group_start();
		$this->db->like('title', $query);
		$this->db->or_like('note', $query);
		$this->db->group_end();
		return (int)$this->db->count_all_results();
	}

	public function search_public_station_diary_entries($user_id, $query, $limit = 10, $offset = 0) {
		$this->db->from('notes');
		$this->db->where('user_id', (int)$user_id);
		$this->db->where('cat', 'Station Diary');
		$this->db->where('is_public', 1);
		$this->db->group_start();
		$this->db->like('title', $query);
		$this->db->or_like('note', $query);
		$this->db->group_end();
		$this->db->order_by('created_at', 'DESC');
		$this->db->order_by('id', 'DESC');
		$this->db->limit((int)$limit, (int)$offset);

		$entries = $this->db->get()->result();

		$ids = array();
		foreach ($entries as $entry) {
			$ids[] = (int)$entry->id;
		}

		$imagesMap = $this->get_diary_images($ids);
		foreach ($entries as $entry) {
			$entry->images = isset($imagesMap[$entry->id]) ? $imagesMap[$entry->id] : array();
			$entry->qso_summary = null;
			$entry->qso_list = array();
		}

		return $entries;
	}

	public function get_public_station_diary_entries($user_id, $limit = 10, $offset = 0, $include_qso_list = TRUE) {
		$this->db->from('notes');
		$this->db->where('user_id', (int)$user_id);
		$this->db->where('cat', 'Station Diary');
		$this->db->where('is_public', 1);
		$this->db->order_by('created_at', 'DESC');
		$this->db->order_by('id', 'DESC');
		$this->db->limit((int)$limit, (int)$offset);

		$query = $this->db->get();
		$entries = $query->result();

		$ids = array();
		foreach ($entries as $entry) {
			$ids[] = (int)$entry->id;
		}

		$imagesMap = $this->get_diary_images($ids);
		$qsoSummaryMemo = array();
		$qsoListMemo = array();
		foreach ($entries as $entry) {
			$entry->images = isset($imagesMap[$entry->id]) ? $imagesMap[$entry->id] : array();
			$entry->qso_summary = null;
			$entry->qso_list = array();
			if ((int)$entry->include_qso_summary === 1) {
				$entryDate = date('Y-m-d', strtotime($entry->created_at));
				
				// Determine date range for filtering
				$dateStart = !empty($entry->qso_date_start) ? $entry->qso_date_start : $entryDate;
				$dateEnd = !empty($entry->qso_date_end) ? $entry->qso_date_end : $entryDate;
				$satOnly = (int)$entry->qso_satellite_only === 1;
				$memoKey = implode('|', array(
					(int)$user_id,
					(string)$dateStart,
					(string)$dateEnd,
					(string)($entry->logbook_id ?? ''),
					$satOnly ? '1' : '0',
				));
				
				// Use date range filtering if dates are set, otherwise fall back to single-day filtering
				if (!empty($entry->qso_date_start) || !empty($entry->qso_date_end)) {
					if (!array_key_exists($memoKey, $qsoSummaryMemo)) {
						$qsoSummaryMemo[$memoKey] = $this->get_qso_summary_for_date_range($user_id, $dateStart, $dateEnd, $entry->logbook_id, $satOnly);
					}
					$entry->qso_summary = $qsoSummaryMemo[$memoKey];
					if ($include_qso_list) {
						if (!array_key_exists($memoKey, $qsoListMemo)) {
							$qsoListMemo[$memoKey] = $this->get_qso_list_for_date_range($user_id, $dateStart, $dateEnd, $entry->logbook_id, $satOnly);
						}
						$entry->qso_list = $qsoListMemo[$memoKey];
					}
				} else {
					if (!array_key_exists($memoKey, $qsoSummaryMemo)) {
						$qsoSummaryMemo[$memoKey] = $this->get_qso_summary_for_date($user_id, $entryDate, $entry->logbook_id);
					}
					$entry->qso_summary = $qsoSummaryMemo[$memoKey];
					if ($include_qso_list) {
						if (!array_key_exists($memoKey, $qsoListMemo)) {
							$qsoListMemo[$memoKey] = $this->get_qso_list_for_date($user_id, $entryDate, $entry->logbook_id);
						}
						$entry->qso_list = $qsoListMemo[$memoKey];
					}
				}
			}
		}

		return $entries;
	}

	public function get_public_station_diary_entry($user_id, $entry_id) {
		$this->db->from('notes');
		$this->db->where('id', (int)$entry_id);
		$this->db->where('user_id', (int)$user_id);
		$this->db->where('cat', 'Station Diary');
		$this->db->where('is_public', 1);
		$query = $this->db->get();

		$entry = $query->row();
		if (!$entry) {
			return null;
		}

		$imagesMap = $this->get_diary_images(array((int)$entry->id));
		$entry->images = isset($imagesMap[$entry->id]) ? $imagesMap[$entry->id] : array();
		$entry->qso_summary = null;
		$entry->qso_list = array();

		if ((int)$entry->include_qso_summary === 1) {
			$entryDate = date('Y-m-d', strtotime($entry->created_at));
			$dateStart = !empty($entry->qso_date_start) ? $entry->qso_date_start : $entryDate;
			$dateEnd = !empty($entry->qso_date_end) ? $entry->qso_date_end : $entryDate;
			$satOnly = (int)$entry->qso_satellite_only === 1;

			if (!empty($entry->qso_date_start) || !empty($entry->qso_date_end)) {
				$entry->qso_summary = $this->get_qso_summary_for_date_range($user_id, $dateStart, $dateEnd, $entry->logbook_id, $satOnly);
				$entry->qso_list = $this->get_qso_list_for_date_range($user_id, $dateStart, $dateEnd, $entry->logbook_id, $satOnly);
			} else {
				$entry->qso_summary = $this->get_qso_summary_for_date($user_id, $entryDate, $entry->logbook_id);
				$entry->qso_list = $this->get_qso_list_for_date($user_id, $entryDate, $entry->logbook_id);
			}
		}

		return $entry;
	}

	public function get_station_diary_reaction_totals($entry_id) {
		$totals = array(
			'like' => 0,
			'love' => 0,
			'fire' => 0,
		);

		if (!$this->db->table_exists('station_diary_reactions')) {
			return $totals;
		}

		$this->db->select('reaction, COUNT(*) AS total');
		$this->db->from('station_diary_reactions');
		$this->db->where('diary_id', (int)$entry_id);
		$this->db->group_by('reaction');
		$query = $this->db->get();

		foreach ($query->result() as $row) {
			$key = strtolower((string)$row->reaction);
			if (array_key_exists($key, $totals)) {
				$totals[$key] = (int)$row->total;
			}
		}

		return $totals;
	}

	public function get_station_diary_visitor_reaction($entry_id, $visitor_hash) {
		if (empty($visitor_hash) || !$this->db->table_exists('station_diary_reactions')) {
			return null;
		}

		$this->db->select('reaction');
		$this->db->from('station_diary_reactions');
		$this->db->where('diary_id', (int)$entry_id);
		$this->db->where('visitor_hash', $visitor_hash);
		$row = $this->db->get()->row();

		if (!$row) {
			return null;
		}

		$reaction = strtolower((string)$row->reaction);
		return in_array($reaction, array('like', 'love', 'fire'), TRUE) ? $reaction : null;
	}

	public function save_station_diary_reaction($entry_id, $reaction, $visitor_hash) {
		$reaction = strtolower(trim((string)$reaction));
		if (!in_array($reaction, array('like', 'love', 'fire'), TRUE)) {
			return false;
		}

		if (empty($visitor_hash) || !$this->db->table_exists('station_diary_reactions')) {
			return false;
		}

		$now = date('Y-m-d H:i:s');

		$this->db->select('id');
		$this->db->from('station_diary_reactions');
		$this->db->where('diary_id', (int)$entry_id);
		$this->db->where('visitor_hash', $visitor_hash);
		$existing = $this->db->get()->row();

		if ($existing) {
			$this->db->where('id', (int)$existing->id);
			return $this->db->update('station_diary_reactions', array(
				'reaction' => $reaction,
				'updated_at' => $now,
			));
		}

		return $this->db->insert('station_diary_reactions', array(
			'diary_id' => (int)$entry_id,
			'reaction' => $reaction,
			'visitor_hash' => $visitor_hash,
			'created_at' => $now,
			'updated_at' => $now,
		));
	}

	private function get_user_station_ids($user_id) {
		$this->db->select('station_id');
		$this->db->from('station_profile');
		$this->db->where('user_id', (int)$user_id);
		$query = $this->db->get();

		$station_ids = array();
		foreach ($query->result() as $row) {
			$station_ids[] = (int)$row->station_id;
		}

		return $station_ids;
	}

	private function get_station_ids_for_summary($user_id, $logbook_id = NULL) {
		static $memoized_station_ids = array();
		$cacheKey = (int)$user_id . '|' . (string)($logbook_id === NULL ? 'null' : $logbook_id);
		if (array_key_exists($cacheKey, $memoized_station_ids)) {
			return $memoized_station_ids[$cacheKey];
		}

		if ($logbook_id === NULL || $logbook_id === '' || $logbook_id === 0 || $logbook_id === '0') {
			// No logbook specified, use all user stations
			$memoized_station_ids[$cacheKey] = $this->get_user_station_ids($user_id);
			return $memoized_station_ids[$cacheKey];
		}

		// Get stations associated with the specified logbook
		$this->db->select('station_location_id');
		$this->db->from('station_logbooks_relationship');
		$this->db->where('station_logbook_id', (int)$logbook_id);
		$query = $this->db->get();

		$station_ids = array();
		foreach ($query->result() as $row) {
			$station_ids[] = (int)$row->station_location_id;
		}

		// Verify at least one station belongs to user for security
		if (!empty($station_ids)) {
			$this->db->select('station_id');
			$this->db->from('station_profile');
			$this->db->where('user_id', (int)$user_id);
			$this->db->where_in('station_id', $station_ids);
			$verify_query = $this->db->get();

			$verified_ids = array();
			foreach ($verify_query->result() as $row) {
				$verified_ids[] = (int)$row->station_id;
			}
			$memoized_station_ids[$cacheKey] = $verified_ids;
			return $memoized_station_ids[$cacheKey];
		}

		$memoized_station_ids[$cacheKey] = $station_ids;
		return $memoized_station_ids[$cacheKey];
	}

	private function get_day_bounds($date) {
		$start = date('Y-m-d 00:00:00', strtotime($date));
		$end = date('Y-m-d 00:00:00', strtotime($date . ' +1 day'));
		return array($start, $end);
	}

	private function get_date_range_bounds($start_date, $end_date) {
		$start = date('Y-m-d 00:00:00', strtotime($start_date));
		$end = date('Y-m-d 00:00:00', strtotime($end_date . ' +1 day'));
		return array($start, $end);
	}

	private function get_primary_qso_grid($candidate) {
		$grid = strtoupper(trim((string)($candidate->COL_GRIDSQUARE ?? '')));
		if ($grid !== '') {
			return $grid;
		}

		$vuccGrids = strtoupper(trim((string)($candidate->COL_VUCC_GRIDS ?? '')));
		if ($vuccGrids === '') {
			return '';
		}

		$parts = explode(',', $vuccGrids);
		foreach ($parts as $part) {
			$part = trim($part);
			if ($part !== '') {
				return $part;
			}
		}

		return '';
	}

	private function get_dxcc_coordinates_for_candidate($candidate, &$dxccLookupCache) {
		$dxccLat = $candidate->dxcc_lat ?? null;
		$dxccLong = $candidate->dxcc_long ?? null;

		if (is_numeric($dxccLat) && is_numeric($dxccLong)) {
			return array((float)$dxccLat, (float)$dxccLong);
		}

		$call = strtoupper(trim((string)($candidate->COL_CALL ?? '')));
		if ($call === '') {
			return array(null, null);
		}

		$date = date('Ymd');
		if (!empty($candidate->COL_TIME_ON)) {
			$timestamp = strtotime($candidate->COL_TIME_ON);
			if ($timestamp !== FALSE) {
				$date = date('Ymd', $timestamp);
			}
		}

		$cacheKey = $call . '|' . $date;
		if (!array_key_exists($cacheKey, $dxccLookupCache)) {
			$this->load->model('logbook_model');
			$lookup = $this->logbook_model->dxcc_lookup($call, $date);
			if (is_array($lookup) && isset($lookup['lat'], $lookup['long']) && is_numeric($lookup['lat']) && is_numeric($lookup['long'])) {
				$dxccLookupCache[$cacheKey] = array((float)$lookup['lat'], (float)$lookup['long']);
			} else {
				$dxccLookupCache[$cacheKey] = array(null, null);
			}
		}

		return $dxccLookupCache[$cacheKey];
	}

	private function resolve_highlight_candidate_distance_km($candidate, &$dxccLookupCache) {
		$stationGrid = trim((string)($candidate->station_gridsquare ?? ''));

		if ($stationGrid !== '') {
			$workedGrid = $this->get_primary_qso_grid($candidate);
			if ($workedGrid !== '') {
				return (float)$this->qra->distance($stationGrid, $workedGrid, 'K');
			}

			list($dxccLat, $dxccLong) = $this->get_dxcc_coordinates_for_candidate($candidate, $dxccLookupCache);
			if ($dxccLat !== null && $dxccLong !== null) {
				$stationCoords = $this->qra->qra2latlong($stationGrid);
				if (is_array($stationCoords) && isset($stationCoords[0], $stationCoords[1]) && is_numeric($stationCoords[0]) && is_numeric($stationCoords[1])) {
					return (float)distance((float)$stationCoords[0], (float)$stationCoords[1], $dxccLat, $dxccLong, 'K');
				}
			}
		}

		return (float)($candidate->COL_DISTANCE ?? 0);
	}

	private function build_highlight_dx_from_candidates($highlight_candidates) {
		$highlight = null;
		if (!empty($highlight_candidates)) {
			$this->load->library('Qra');
			$best_distance = 0;
			$dxccLookupCache = array();
			foreach ($highlight_candidates as $candidate) {
				$dist = $this->resolve_highlight_candidate_distance_km($candidate, $dxccLookupCache);
				if ($dist > $best_distance) {
					$best_distance = $dist;
					$highlight = $candidate;
					$highlight->COL_DISTANCE = (int)round($dist);
				}
			}
		}

		return $highlight;
	}

	public function get_qso_list_for_date($user_id, $date, $logbook_id = NULL) {
		$station_ids = $this->get_station_ids_for_summary($user_id, $logbook_id);
		if (empty($station_ids)) {
			return array();
		}

		$table = $this->config->item('table_name');
		list($dayStart, $dayEnd) = $this->get_day_bounds($date);

		$this->db->select('COL_CALL, COL_TIME_ON, COL_BAND, COL_MODE, COL_SUBMODE, COL_COUNTRY, COL_GRIDSQUARE, COL_VUCC_GRIDS, COL_RST_SENT, COL_RST_RCVD, COL_FREQ, COL_DXCC, COL_DISTANCE, COL_PROP_MODE, COL_SAT_NAME, COL_SAT_MODE');
		$this->db->from($table);
		$this->db->where_in('station_id', $station_ids);
		$this->db->where('COL_TIME_ON >=', $dayStart);
		$this->db->where('COL_TIME_ON <', $dayEnd);
		$this->db->order_by('COL_TIME_ON', 'ASC');
		$this->db->limit(100); // Reasonable limit for public display

		$query = $this->db->get();
		return $query->result();
	}

	public function get_qso_summary_for_date($user_id, $date, $logbook_id = NULL) {
		$station_ids = $this->get_station_ids_for_summary($user_id, $logbook_id);
		if (empty($station_ids)) {
			return null;
		}

		$table = $this->config->item('table_name');
		list($dayStart, $dayEnd) = $this->get_day_bounds($date);

		$this->db->select('COUNT(*) AS total_qsos, COUNT(DISTINCT COL_DXCC) AS dxcc_worked');
		$this->db->from($table);
		$this->db->where_in('station_id', $station_ids);
		$this->db->where('COL_TIME_ON >=', $dayStart);
		$this->db->where('COL_TIME_ON <', $dayEnd);
		$overview = $this->db->get()->row();

		$this->db->distinct();
	$this->db->select('LOWER(COL_BAND) AS band, COL_BAND+0 AS band_num', FALSE);
	$this->db->from($table);
	$this->db->where_in('station_id', $station_ids);
	$this->db->where('COL_TIME_ON >=', $dayStart);
	$this->db->where('COL_TIME_ON <', $dayEnd);
	$this->db->where('COL_BAND IS NOT NULL', null, FALSE);
	$this->db->where('COL_BAND !=', '');
	$this->db->order_by('band_num', 'ASC');
		$bandsResult = $this->db->get()->result();

		$this->db->distinct();
		$this->db->select('(CASE WHEN COL_SUBMODE IS NOT NULL AND COL_SUBMODE != "" THEN UPPER(COL_SUBMODE) ELSE UPPER(COL_MODE) END) AS mode_label', FALSE);
		$this->db->from($table);
		$this->db->where_in('station_id', $station_ids);
		$this->db->where('COL_TIME_ON >=', $dayStart);
		$this->db->where('COL_TIME_ON <', $dayEnd);
		$this->db->where('COL_MODE IS NOT NULL', null, FALSE);
		$this->db->where('COL_MODE !=', '');
		$this->db->order_by('mode_label', 'ASC');
		$modesResult = $this->db->get()->result();

		// Get highlight DX candidates. Distance is re-calculated in PHP using grid first,
		// and a DXCC location fallback when worked grid is missing.
		$this->db->select('t.COL_CALL, t.COL_COUNTRY, t.COL_TIME_ON, t.COL_DXCC, t.COL_DISTANCE, t.COL_GRIDSQUARE, t.COL_VUCC_GRIDS, sp.station_gridsquare, de.lat AS dxcc_lat, de.`long` AS dxcc_long', FALSE);
		$this->db->from($table . ' t');
		$this->db->join('station_profile sp', 'sp.station_id = t.station_id', 'left');
		$this->db->join('dxcc_entities de', 'de.adif = t.COL_DXCC', 'left');
		$this->db->where_in('t.station_id', $station_ids);
		$this->db->where('t.COL_TIME_ON >=', $dayStart);
		$this->db->where('t.COL_TIME_ON <', $dayEnd);
		$this->db->group_start();
			$this->db->where('t.COL_DISTANCE >', 0);
			$this->db->or_group_start();
				$this->db->group_start();
					$this->db->where('t.COL_GRIDSQUARE IS NOT NULL', null, FALSE);
					$this->db->where('t.COL_GRIDSQUARE !=', '');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('t.COL_VUCC_GRIDS IS NOT NULL', null, FALSE);
					$this->db->where('t.COL_VUCC_GRIDS !=', '');
				$this->db->group_end();
				$this->db->where('sp.station_gridsquare IS NOT NULL', null, FALSE);
				$this->db->where('sp.station_gridsquare !=', '');
			$this->db->group_end();
		$this->db->group_end();
		$this->db->order_by('t.COL_DISTANCE+0', 'DESC', FALSE);
		$this->db->limit(50);
		$highlight_candidates = $this->db->get()->result();

		$highlight = $this->build_highlight_dx_from_candidates($highlight_candidates);

		$bands = array();
		foreach ($bandsResult as $band) {
			$bands[] = $band->band;
		}

		$modes = array();
		foreach ($modesResult as $mode) {
			$modes[] = $mode->mode_label;
		}

		// Don't show summary if there are no QSOs
		$total_qsos = (int)($overview->total_qsos ?? 0);
		if ($total_qsos === 0) {
			return null;
		}

		return array(
			'total_qsos' => $total_qsos,
			'dxcc_worked' => (int)($overview->dxcc_worked ?? 0),
			'bands' => $bands,
			'modes' => $modes,
			'highlight_dx' => $highlight,
		);
	}

	function view($id) {
		// Get Note
		$this->db->where('id', xss_clean($id));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->get('notes');
	}

	public function get_public_entry_user_id($entry_id) {
		// Get user_id from a public station diary entry without requiring session
		$this->db->select('user_id');
		$this->db->from('notes');
		$this->db->where('id', (int)$entry_id);
		$this->db->where('cat', 'Station Diary');
		$this->db->where('is_public', 1);
		$row = $this->db->get()->row();
		
		return $row ? (int)$row->user_id : null;
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

	public function get_qso_list_for_date_range($user_id, $start_date, $end_date, $logbook_id = NULL, $sat_only = FALSE) {
		$station_ids = $this->get_station_ids_for_summary($user_id, $logbook_id);
		if (empty($station_ids)) {
			return array();
		}

		$table = $this->config->item('table_name');
		list($rangeStart, $rangeEnd) = $this->get_date_range_bounds($start_date, $end_date);

		$this->db->select('COL_CALL, COL_TIME_ON, COL_BAND, COL_MODE, COL_SUBMODE, COL_COUNTRY, COL_GRIDSQUARE, COL_VUCC_GRIDS, COL_RST_SENT, COL_RST_RCVD, COL_FREQ, COL_DXCC, COL_DISTANCE, COL_PROP_MODE, COL_SAT_NAME, COL_SAT_MODE');
		$this->db->from($table);
		$this->db->where_in('station_id', $station_ids);
		$this->db->where('COL_TIME_ON >=', $rangeStart);
		$this->db->where('COL_TIME_ON <', $rangeEnd);
		
		if ($sat_only) {
			$this->db->where('COL_PROP_MODE', 'SAT');
		}

		$this->db->order_by('COL_TIME_ON', 'ASC');
		$this->db->limit(100);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_qso_summary_for_date_range($user_id, $start_date, $end_date, $logbook_id = NULL, $sat_only = FALSE) {
		$station_ids = $this->get_station_ids_for_summary($user_id, $logbook_id);
		if (empty($station_ids)) {
			return null;
		}

		$table = $this->config->item('table_name');
		list($rangeStart, $rangeEnd) = $this->get_date_range_bounds($start_date, $end_date);

		$this->db->select('COUNT(*) AS total_qsos, COUNT(DISTINCT COL_DXCC) AS dxcc_worked');
		$this->db->from($table);
		$this->db->where_in('station_id', $station_ids);
		$this->db->where('COL_TIME_ON >=', $rangeStart);
		$this->db->where('COL_TIME_ON <', $rangeEnd);
		
		if ($sat_only) {
			$this->db->where('COL_PROP_MODE', 'SAT');
		}

		$overview = $this->db->get()->row();

		// Get bands with date range
		$this->db->distinct();
		$this->db->select('LOWER(COL_BAND) AS band, COL_BAND+0 AS band_num', FALSE);
		$this->db->from($table);
		$this->db->where_in('station_id', $station_ids);
		$this->db->where('COL_TIME_ON >=', $rangeStart);
		$this->db->where('COL_TIME_ON <', $rangeEnd);
		$this->db->where('COL_BAND IS NOT NULL', null, FALSE);
		$this->db->where('COL_BAND !=', '');
		
		if ($sat_only) {
			$this->db->where('COL_PROP_MODE', 'SAT');
		}
		
		$this->db->order_by('band_num', 'ASC');
		$bandsResult = $this->db->get()->result();

		// Get modes with date range
		$this->db->distinct();
		$this->db->select('(CASE WHEN COL_SUBMODE IS NOT NULL AND COL_SUBMODE != "" THEN UPPER(COL_SUBMODE) ELSE UPPER(COL_MODE) END) AS mode_label', FALSE);
		$this->db->from($table);
		$this->db->where_in('station_id', $station_ids);
		$this->db->where('COL_TIME_ON >=', $rangeStart);
		$this->db->where('COL_TIME_ON <', $rangeEnd);
		$this->db->where('COL_MODE IS NOT NULL', null, FALSE);
		$this->db->where('COL_MODE !=', '');
		
		if ($sat_only) {
			$this->db->where('COL_PROP_MODE', 'SAT');
		}
		
		$this->db->order_by('mode_label', 'ASC');
		$modesResult = $this->db->get()->result();

		// Get highlight DX candidates. Distance is re-calculated in PHP using grid first,
		// and a DXCC location fallback when worked grid is missing.
		$this->db->select('t.COL_CALL, t.COL_COUNTRY, t.COL_TIME_ON, t.COL_DXCC, t.COL_DISTANCE, t.COL_GRIDSQUARE, t.COL_VUCC_GRIDS, sp.station_gridsquare, de.lat AS dxcc_lat, de.`long` AS dxcc_long', FALSE);
		$this->db->from($table . ' t');
		$this->db->join('station_profile sp', 'sp.station_id = t.station_id', 'left');
		$this->db->join('dxcc_entities de', 'de.adif = t.COL_DXCC', 'left');
		$this->db->where_in('t.station_id', $station_ids);
		$this->db->where('t.COL_TIME_ON >=', $rangeStart);
		$this->db->where('t.COL_TIME_ON <', $rangeEnd);

		if ($sat_only) {
			$this->db->where('t.COL_PROP_MODE', 'SAT');
		}

		$this->db->group_start();
			$this->db->where('t.COL_DISTANCE >', 0);
			$this->db->or_group_start();
				$this->db->group_start();
					$this->db->where('t.COL_GRIDSQUARE IS NOT NULL', null, FALSE);
					$this->db->where('t.COL_GRIDSQUARE !=', '');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('t.COL_VUCC_GRIDS IS NOT NULL', null, FALSE);
					$this->db->where('t.COL_VUCC_GRIDS !=', '');
				$this->db->group_end();
				$this->db->where('sp.station_gridsquare IS NOT NULL', null, FALSE);
				$this->db->where('sp.station_gridsquare !=', '');
			$this->db->group_end();
		$this->db->group_end();
		$this->db->order_by('t.COL_DISTANCE+0', 'DESC', FALSE);
		$this->db->limit(50);
		$highlight_candidates = $this->db->get()->result();

		$highlight = $this->build_highlight_dx_from_candidates($highlight_candidates);

		$bands = array();
		foreach ($bandsResult as $band) {
			$bands[] = $band->band;
		}

		$modes = array();
		foreach ($modesResult as $mode) {
			$modes[] = $mode->mode_label;
		}

		return array(
			'total_qsos' => (int)($overview->total_qsos ?? 0),
			'dxcc_worked' => (int)($overview->dxcc_worked ?? 0),
			'bands' => $bands,
			'modes' => $modes,
			'highlight_dx' => $highlight
		);
	}

/**
 * Process image shortcodes in diary note content
 * Supports: [image:ID], [image:caption], [image:ID:modifier], [image:ID:modifier:modifier]
 * Modifiers: left, right, center, small, medium, large
 * 
 * @param string $content Note content with potential shortcodes
 * @param array $images Array of image objects for this entry
 * @return array ['content' => processed HTML, 'used_image_ids' => array of IDs used inline]
 */
public function process_image_shortcodes($content, $images = array()) {
	if (empty($images) || empty($content)) {
		return array('content' => $content, 'used_image_ids' => array());
	}
	
	$usedImageIds = array();
	
	// Normalize encoded square brackets in case content sanitizer encoded shortcode delimiters
	$content = str_ireplace('&#91;image:', '[image:', $content);
	$content = str_ireplace('&#93;', ']', $content);

	// Match [image:identifier] or [image:identifier:modifier[:modifier...]] (spaces tolerated)
	$pattern = '/\[\s*image\s*:\s*([^\]:]+?)\s*(?::\s*([^\]]+))?\]/i';
	
	$content = preg_replace_callback($pattern, function($matches) use ($images, &$usedImageIds) {
		$identifier = trim($matches[1]);
		$modifierString = isset($matches[2]) ? trim($matches[2]) : '';
		
		// Find the image by ID or caption
		$image = null;
		if (is_numeric($identifier)) {
			// Lookup by ID
			foreach ($images as $img) {
				if ((int)$img->id === (int)$identifier) {
					$image = $img;
					break;
				}
			}

			// Fallback: allow 1-based image position per entry (e.g. [image:1] = first image)
			if (!$image) {
				$position = (int)$identifier;
				if ($position >= 1 && $position <= count($images)) {
					$image = $images[$position - 1];
				}
			}
		} else {
			// Lookup by caption (case-insensitive)
			foreach ($images as $img) {
				if (!empty($img->caption) && strcasecmp($img->caption, $identifier) === 0) {
					$image = $img;
					break;
				}
			}
		}
		
		// If image not found, return empty string (silent fail)
		if (!$image) {
			return '';
		}
		
		// Track this image as used
		$usedImageIds[] = (int)$image->id;
		
		// Determine CSS classes based on modifiers
		$wrapperClass = 'diary-inline-image mb-3';
		$imgClass = 'img-fluid rounded';
		$styles = array();
		$align = '';
		$size = '';
		
		if (!empty($modifierString)) {
			$modifiers = array_filter(array_map('trim', explode(':', strtolower($modifierString))));
			
			foreach ($modifiers as $mod) {
				if ($mod === 'left') {
					$align = 'left';
				} elseif ($mod === 'right') {
					$align = 'right';
				} elseif ($mod === 'center') {
					$align = 'center';
				}
			}

			foreach ($modifiers as $mod) {
				if ($mod === 'small') {
					$size = 'small';
				} elseif ($mod === 'medium') {
					$size = 'medium';
				} elseif ($mod === 'large') {
					$size = 'large';
				}
			}
		}

		// Apply alignment classes/styles
		if ($align === 'left') {
			$wrapperClass .= ' float-start me-3';
			$styles[] = 'max-width: 400px';
		} elseif ($align === 'right') {
			$wrapperClass .= ' float-end ms-3';
			$styles[] = 'max-width: 400px';
		} elseif ($align === 'center') {
			$wrapperClass .= ' text-center';
			$styles[] = 'margin-left: auto';
			$styles[] = 'margin-right: auto';
		}

		// Apply size after alignment defaults so explicit size wins
		if ($size === 'small') {
			$styles = array_values(array_filter($styles, function($s) { return stripos($s, 'max-width:') !== 0; }));
			$styles[] = 'max-width: 300px';
		} elseif ($size === 'medium') {
			$styles = array_values(array_filter($styles, function($s) { return stripos($s, 'max-width:') !== 0; }));
			$styles[] = 'max-width: 500px';
		} elseif ($size === 'large') {
			$styles = array_values(array_filter($styles, function($s) { return stripos($s, 'max-width:') !== 0; }));
			$styles[] = 'max-width: 800px';
		}
		
		// Build the HTML
		$html = '<div class="' . $wrapperClass . '"' . (!empty($styles) ? ' style="' . implode('; ', $styles) . '"' : '') . '>';
		$html .= '<img src="' . base_url() . ltrim($image->filename, '/') . '" alt="' . htmlspecialchars($image->caption ?? 'Diary image', ENT_QUOTES) . '" class="' . $imgClass . '">';
		if (!empty($image->caption)) {
			$html .= '<div class="small text-muted mt-1">' . htmlspecialchars($image->caption, ENT_QUOTES) . '</div>';
		}
		$html .= '</div>';
		
		return $html;
	}, $content);
	
	return array(
		'content' => $content,
		'used_image_ids' => array_unique($usedImageIds)
	);
}

}

