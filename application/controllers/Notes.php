<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Load language files
		$this->lang->load('notes');
	}

	private function is_public_station_diary_enabled() {
		$configValue = $this->config->item('public_station_diary_enabled');
		if ($configValue === NULL) {
			$configValue = TRUE;
		}

		$optionValue = $this->optionslib->get_option('public_station_diary_enabled');
		if ($optionValue !== NULL && $optionValue !== '') {
			return ($optionValue === '1' || $optionValue === 'true' || $optionValue === 1 || $optionValue === TRUE);
		}

		return (bool)$configValue;
	}

	private function handle_diary_images_upload($note_id) {
		$hasFilesKey = isset($_FILES['diary_images']) && isset($_FILES['diary_images']['name']) && is_array($_FILES['diary_images']['name']);
		$contentType = isset($_SERVER['CONTENT_TYPE']) ? (string)$_SERVER['CONTENT_TYPE'] : '';
		$contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int)$_SERVER['CONTENT_LENGTH'] : 0;

		if (!$hasFilesKey) {
			// Classic PHP behaviour: request larger than post_max_size arrives with empty POST/FILES
			if ($contentLength > 0 && stripos($contentType, 'multipart/form-data') !== FALSE && empty($_POST) && empty($_FILES)) {
				log_message('error', 'Diary image upload: empty POST/FILES with CONTENT_LENGTH=' . $contentLength . '. Increase PHP post_max_size / upload_max_filesize on production.');
				return 'Server rejected the upload (request too large for PHP post_max_size). Ask hosting to raise post_max_size and upload_max_filesize.';
			}
			log_message('info', 'Diary image upload: no diary_images in request for note ' . (int)$note_id . ' (content-type=' . $contentType . ', content-length=' . $contentLength . ')');
			return null;
		}

		$names = array_filter($_FILES['diary_images']['name']);
		if (empty($names)) {
			log_message('info', 'Diary image upload: diary_images present but empty for note ' . (int)$note_id);
			return null;
		}

		log_message('info', 'Diary image upload starting for note ' . (int)$note_id . ' with ' . count($names) . ' file(s)');

		if (!$this->db->table_exists('diary_images')) {
			log_message('error', 'Diary image upload failed: diary_images table missing. Run migrations.');
			return 'Image uploads require a database update (diary_images table). Please run Cloudlog migrations on this server.';
		}

		$noteResult = $this->note->view($note_id);
		if ($noteResult->num_rows() === 0) {
			return 'Unable to attach images: note not found.';
		}

		$noteRow = $noteResult->row();
		if (strtoupper(trim((string)$noteRow->cat)) !== 'STATION DIARY') {
			return 'Images are only supported for Station Diary entries.';
		}

		$user_id = (int)$this->session->userdata('user_id');
		$uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'diary' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
		if (!is_dir($uploadDir)) {
			if (!@mkdir($uploadDir, 0755, TRUE)) {
				$parent = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'diary';
				$parentWritable = is_dir(dirname($parent)) && is_writable(FCPATH . 'uploads');
				log_message('error', 'Diary image upload failed: cannot create ' . $uploadDir . ' (uploads writable: ' . ($parentWritable ? 'yes' : 'no') . ')');
				return 'Unable to create upload directory. On the server, ensure uploads/ (and uploads/diary/) is writable by the web server user.';
			}
		}

		if (!is_writable($uploadDir)) {
			log_message('error', 'Diary image upload failed: directory not writable ' . $uploadDir);
			return 'Upload directory is not writable. On the server, fix permissions for uploads/diary/' . $user_id . '/.';
		}

		$this->load->library('upload');
		$hasImageLib = function_exists('imagecreatefromstring') || extension_loaded('gd');
		if ($hasImageLib) {
			$this->load->library('image_lib');
		} else {
			log_message('error', 'Diary image upload: PHP GD extension missing; images will be stored without resize.');
		}

		$errors = array();
		$savedImages = array();
		$totalFiles = count($_FILES['diary_images']['name']);

		for ($i = 0; $i < $totalFiles; $i++) {
			if (empty($_FILES['diary_images']['name'][$i])) {
				continue;
			}

			$phpError = isset($_FILES['diary_images']['error'][$i]) ? (int)$_FILES['diary_images']['error'][$i] : UPLOAD_ERR_OK;
			if ($phpError !== UPLOAD_ERR_OK) {
				$errors[] = $this->diary_upload_php_error_message($phpError, $_FILES['diary_images']['name'][$i]);
				continue;
			}

			$_FILES['single_diary_image']['name'] = $_FILES['diary_images']['name'][$i];
			$_FILES['single_diary_image']['type'] = $_FILES['diary_images']['type'][$i];
			$_FILES['single_diary_image']['tmp_name'] = $_FILES['diary_images']['tmp_name'][$i];
			$_FILES['single_diary_image']['error'] = $_FILES['diary_images']['error'][$i];
			$_FILES['single_diary_image']['size'] = $_FILES['diary_images']['size'][$i];

			$uploadConfig = array(
				'upload_path' => $uploadDir,
				'allowed_types' => 'jpg|jpeg|png|gif|webp',
				'max_size' => 8192,
				'encrypt_name' => TRUE,
				'detect_mime' => TRUE,
				'mod_mime_fix' => TRUE,
			);

			$this->upload->initialize($uploadConfig);

			if (!$this->upload->do_upload('single_diary_image')) {
				$uploadError = trim(strip_tags($this->upload->display_errors('', '')));
				log_message('error', 'Diary image upload rejected: ' . $uploadError . ' file=' . $_FILES['diary_images']['name'][$i]);
				$errors[] = $uploadError;
				continue;
			}

			$uploadData = $this->upload->data();

			if ($hasImageLib) {
				$imageConfig = array(
					'image_library' => 'gd2',
					'source_image' => $uploadData['full_path'],
					'maintain_ratio' => TRUE,
					'quality' => '70%',
					'master_dim' => 'auto',
					'width' => 1080,
					'height' => 1080,
				);

				$this->image_lib->clear();
				$this->image_lib->initialize($imageConfig);
				if (!$this->image_lib->resize()) {
					log_message('error', 'Diary image resize failed: ' . strip_tags($this->image_lib->display_errors('', '')) . ' file=' . $uploadData['file_name']);
				}
			}

			$savedImages[] = array(
				'filename' => 'uploads/diary/' . $user_id . '/' . $uploadData['file_name'],
			);
		}

		if (!empty($savedImages)) {
			try {
				$this->note->add_diary_images($note_id, $savedImages);
				log_message('info', 'Diary image upload saved ' . count($savedImages) . ' image(s) for note ' . (int)$note_id);
			} catch (Exception $e) {
				log_message('error', 'Diary image DB insert failed: ' . $e->getMessage());
				return 'Images uploaded but could not be saved to the database. Check that migrations have been run.';
			}
		}

		if (!empty($errors)) {
			return implode(' ', $errors);
		}

		return null;
	}

	private function diary_upload_php_error_message($errorCode, $filename = '') {
		$label = $filename !== '' ? ' (' . $filename . ')' : '';
		switch ($errorCode) {
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'Image' . $label . ' exceeds the server upload size limit (check PHP upload_max_filesize / post_max_size).';
			case UPLOAD_ERR_PARTIAL:
				return 'Image' . $label . ' was only partially uploaded.';
			case UPLOAD_ERR_NO_FILE:
				return 'No image file was uploaded' . $label . '.';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Server missing a temporary folder for uploads.';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Server failed to write the uploaded image to disk.';
			case UPLOAD_ERR_EXTENSION:
				return 'A PHP extension blocked the image upload' . $label . '.';
			default:
				return 'Image upload failed' . $label . ' (error code ' . $errorCode . ').';
		}
	}


	/* Displays all notes in a list */
	public function index()
	{
		$this->load->model('note');
		$filters = array(
			'search' => $this->input->get('q', TRUE),
			'category' => $this->input->get('category', TRUE),
			'date_from' => $this->input->get('date_from', TRUE),
			'date_to' => $this->input->get('date_to', TRUE)
		);
		$data['filters'] = $filters;
		$data['categories'] = $this->note->list_categories();
		$data['notes'] = $this->note->list_all(null, $filters);
		$data['public_station_diary_enabled'] = $this->is_public_station_diary_enabled();
		$data['public_diary_url'] = site_url('station-diary/' . rawurlencode((string)$this->session->userdata('user_callsign')));
		
		// Check if there are any Station Diary entries
		$diary_filter = array('category' => 'Station Diary');
		$diary_entries = $this->note->list_all(null, $diary_filter);
		$data['has_diary_entries'] = $diary_entries->num_rows() > 0;
		
		$data['page_title'] = "Notes";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('notes/main');
		$this->load->view('interface_assets/footer');
	}
	
	/* Provides function for adding notes to the system. */
	function add() {
	
		$this->load->model('note');
		$this->load->model('logbooks_model');
		$data['categories'] = $this->note->list_categories();
		$data['public_station_diary_enabled'] = $this->is_public_station_diary_enabled();
		$data['user_logbooks'] = $this->logbooks_model->show_all();
	
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Note Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');


		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Add Notes";
			$this->load->view('interface_assets/header', $data);
			$this->load->view('notes/add');
			$this->load->view('interface_assets/footer');
		}
		else
		{	
			$note_id = $this->note->add();
			$upload_error = $this->handle_diary_images_upload($note_id);
			if (!empty($upload_error)) {
				$this->session->set_flashdata('notice', $upload_error);
			}
			
			redirect('notes');
		}
	}
	
	/* Quick add note via HTMX (for Station Diary modal) */
	function quick_add() {
		$this->load->model('note');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Note Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE) {
			echo '<div class="alert alert-danger">' . validation_errors() . '</div>';
		} else {
			$note_id = $this->note->add();
			$upload_error = $this->handle_diary_images_upload($note_id);
			
			if (!empty($upload_error)) {
				echo '<div class="alert alert-warning">Note saved, but image upload failed: ' . htmlspecialchars($upload_error, ENT_QUOTES, 'UTF-8') . ' <a href="' . site_url('notes') . '">View notes</a></div>';
			} else {
				echo '<div class="alert alert-success">Note saved successfully! <a href="' . site_url('notes') . '">View all notes</a></div>';
			}
			// Reset form via JavaScript
			echo '<script>setTimeout(function(){ document.getElementById("stationDiaryForm").reset(); if (typeof htmx !== "undefined") { htmx.trigger("#stationDiaryForm", "reset"); } }, 1500);</script>';
		}
	}
	
	/* View Notes */
	function view($id) {
		$this->load->model('note');
		
		$data['note'] = $this->note->view($id);
		$data['diary_images'] = $this->note->get_diary_images(array((int)$id));
		
		// Display
		$data['page_title'] = "Note";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('notes/view');
		$this->load->view('interface_assets/footer');
	}
	
	/* Edit Notes */
	function edit($id) {
		$this->load->model('note');
		$this->load->model('logbooks_model');
		$data['id'] = $id;
		
		$data['note'] = $this->note->view($id);
		$data['categories'] = $this->note->list_categories();
		$data['diary_images'] = $this->note->get_diary_images(array((int)$id));
		$data['public_station_diary_enabled'] = $this->is_public_station_diary_enabled();
		$data['user_logbooks'] = $this->logbooks_model->show_all();
			
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Note Title', 'required');
		$this->form_validation->set_rules('content', 'Content', 'required');


		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Edit Note";
			$this->load->view('interface_assets/header', $data);
			$this->load->view('notes/edit');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			$note_id = $this->note->edit();
			$upload_error = $this->handle_diary_images_upload($note_id);
			if (!empty($upload_error)) {
				$this->session->set_flashdata('notice', $upload_error);
			}
			
			redirect('notes');
		}
	}
	
	/* Delete Note */
	function delete() {
		// Enforce POST for destructive action
		if (strtolower($this->input->method()) !== 'post') {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': invalid request method.');
			redirect('notes');
			return;
		}

		$id = $this->input->post('id', TRUE);
		if (empty($id)) {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': missing note id.');
			redirect('notes');
			return;
		}

		$this->load->model('note');
		$this->note->delete($id);
		$this->session->set_flashdata('notice', $this->lang->line('admin_delete') ?: 'Deleted');
		redirect('notes');
	}

	/* Delete Diary Image (AJAX) */
	function delete_diary_image() {
		// Enforce POST for destructive action
		if (strtolower($this->input->method()) !== 'post') {
			header('Content-Type: application/json');
			echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
			return;
		}

		$image_id = $this->input->post('image_id', TRUE);
		if (empty($image_id)) {
			header('Content-Type: application/json');
			echo json_encode(array('success' => false, 'message' => 'Missing image ID'));
			return;
		}

		$this->load->model('note');
		$user_id = $this->session->userdata('user_id');
		$result = $this->note->delete_diary_image_by_id($image_id, $user_id);

		header('Content-Type: application/json');
		if ($result) {
			echo json_encode(array('success' => true, 'message' => 'Image deleted successfully'));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Image not found or permission denied'));
		}
	}

	/* Print Station Diary */
	public function station_diary() {
		$this->load->model('note');
		
		$filters = array(
			'category' => 'Station Diary'
		);
		
		$data['diary_entries'] = $this->note->list_all(null, $filters);
		$data['page_title'] = "Station Diary";
		
		// Load diary images for all entries
		$entry_ids = array();
		foreach ($data['diary_entries']->result() as $entry) {
			$entry_ids[] = (int)$entry->id;
		}
		if (!empty($entry_ids)) {
			$data['diary_images'] = $this->note->get_diary_images($entry_ids);
		} else {
			$data['diary_images'] = array();
		}
		
		// Load without header/footer for print formatting
		$this->load->view('notes/station_diary_print', $data);
	}

	/* Delete/Merge Category */
	function delete_category() {
		if (strtolower($this->input->method()) !== 'post') {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': invalid request method.');
			redirect('notes');
			return;
		}

		$source = trim($this->input->post('source_category', TRUE));
		$target = trim($this->input->post('target_category', TRUE));

		if ($source === '') {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': missing source category.');
			redirect('notes');
			return;
		}

		if ($target === '') {
			$target = 'General';
		}

		if ($source === $target) {
			$this->session->set_flashdata('notice', $this->lang->line('general_word_warning') . ': choose a different target category.');
			redirect('notes');
			return;
		}

		$this->load->model('note');
		$affected = $this->note->replace_category($source, $target);
		$this->session->set_flashdata('notice', sprintf('%s → %s (%d)', $source, $target, $affected));
		redirect('notes');
	}

	public function update_image_caption() {
		header('Content-Type: application/json');
		
		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
			return;
		}

		$imageId = (int)$this->input->post('image_id');
		$caption = trim($this->input->post('caption', TRUE));

		if ($imageId <= 0) {
			echo json_encode(array('success' => false, 'message' => 'Invalid image ID'));
			return;
		}

		$this->load->model('note');
		
		// Verify ownership through the diary entry
		$this->db->select('diary_images.id, diary_images.diary_id');
		$this->db->from('diary_images');
		$this->db->join('notes', 'notes.id = diary_images.diary_id');
		$this->db->where('diary_images.id', $imageId);
		$this->db->where('notes.user_id', (int)$this->session->userdata('user_id'));
		$query = $this->db->get();

		if ($query->num_rows() === 0) {
			echo json_encode(array('success' => false, 'message' => 'Image not found or access denied'));
			return;
		}

		$imageRow = $query->row();

		// Update caption
		$this->db->where('id', $imageId);
		$this->db->update('diary_images', array('caption' => $caption));

		$this->note->invalidate_public_diary_cache_for_note((int)$imageRow->diary_id, (int)$this->session->userdata('user_id'));

		echo json_encode(array('success' => true));
	}
}