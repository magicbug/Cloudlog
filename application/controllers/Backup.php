<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}

	/* ===== User-level JSON+ZIP backup (Stations, Logbooks, QSOs) ===== */
	public function user_export() {
		$this->load->model('user_model');
		if ($this->user_model->validate_session() == 0) { redirect('user/login'); }
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		ini_set('memory_limit', '512M');
		$this->load->model('Stations');
		$this->load->model('Logbook_model');
		$this->load->dbutil();

		$tmp_json = tempnam(sys_get_temp_dir(), 'cloudlog_backup_') . '.json';
		$tmp_zip = tempnam(sys_get_temp_dir(), 'cloudlog_backup_') . '.zip';

		try {
			$user_id = $this->session->userdata('user_id');
			if (!$user_id) { show_error('User not authenticated.', 401); return; }

			while (ob_get_level()) { ob_end_clean(); }

			$result = array();

			// Stations for user
			$this->db->where('user_id', $user_id);
			$stations = $this->db->get('station_profile')->result_array();
			$result['stations'] = $stations;

			// QSOs per station
			$station_qsos = array();
			foreach ($stations as $station) {
				if (!isset($station['station_id'])) continue;
				$station_id = $station['station_id'];
				$qso_chunk_size = 10000; $qso_offset = 0; $qsos = array();
				do {
					$this->db->where('station_id', $station_id);
					$this->db->limit($qso_chunk_size, $qso_offset);
					$chunk = $this->db->get($this->config->item('table_name'))->result_array();
					$qsos = array_merge($qsos, $chunk); $qso_offset += $qso_chunk_size;
				} while (count($chunk) === $qso_chunk_size);
				$station_qsos[$station_id] = $qsos;
			}
			$result['station_qsos'] = $station_qsos;

			// Logbooks for user (include QSOs snapshot for backwards compatibility)
			$logbooks = array();
			$this->db->where('user_id', $user_id);
			foreach ($this->db->get('station_logbooks')->result_array() as $logbook) {
				$station_id = isset($logbook['station_id']) ? $logbook['station_id'] : null;
				$qso_chunk_size = 10000; $qso_offset = 0; $qsos = array();
				if ($station_id) {
					do {
						$this->db->where('station_id', $station_id);
						$this->db->limit($qso_chunk_size, $qso_offset);
						$chunk = $this->db->get($this->config->item('table_name'))->result_array();
						$qsos = array_merge($qsos, $chunk); $qso_offset += $qso_chunk_size;
					} while (count($chunk) === $qso_chunk_size);
				}
				$logbook['qsos'] = $qsos; $logbooks[] = $logbook;
			}
			$result['logbooks'] = $logbooks;

			// Relationships if present
			$tables = $this->db->list_tables();
			if (in_array('station_logbooks_entity', $tables)) {
				$this->db->where('user_id', $user_id);
				$result['logbooks_entity'] = $this->db->get('station_logbooks_entity')->result_array();
			} else { $result['logbooks_entity'] = []; }

			$result['schema_version'] = '1.0';
			$result['exported_at'] = date('c');

			file_put_contents($tmp_json, json_encode($result, JSON_PRETTY_PRINT));
			$zip = new ZipArchive();
			if ($zip->open($tmp_zip, ZipArchive::CREATE) !== TRUE) { throw new Exception('Could not create ZIP file'); }
			$zip->addFile($tmp_json, 'cloudlog_backup.json');
			$zip->close();

			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="cloudlog_backup_'.date('Ymd_His').'.zip"');
			header('Content-Length: ' . filesize($tmp_zip));
			header('Cache-Control: no-store, no-cache');
			readfile($tmp_zip);
			@unlink($tmp_json); @unlink($tmp_zip); exit;
		} catch (Exception $e) { log_message('error', 'User export error: '.$e->getMessage()); show_error('Export failed: '.$e->getMessage(), 500); }
	}

	public function user_import() {
		$this->load->model('user_model');
		if ($this->user_model->validate_session() == 0) { redirect('user/login'); }
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		ini_set('memory_limit', '512M');
		$this->load->model('Stations');
		$this->load->model('Logbook_model');
		$this->load->library('session');

		if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) { $this->session->set_flashdata('notice', 'No file uploaded or upload error.'); redirect('backup'); return; }

		$uploaded_file = $_FILES['backup_file']['tmp_name'];
		$temp_json = tempnam(sys_get_temp_dir(), 'cloudlog_import_') . '.json';

		$finfo = finfo_open(FILEINFO_MIME_TYPE); $mime_type = finfo_file($finfo, $uploaded_file); finfo_close($finfo);
		if ($mime_type === 'application/zip') {
			$zip = new ZipArchive();
			if ($zip->open($uploaded_file) === TRUE) {
				$json_content = $zip->getFromName('cloudlog_backup.json'); $zip->close();
				if ($json_content === false) { $this->session->set_flashdata('notice', 'Invalid backup file: JSON not found in ZIP.'); redirect('backup'); return; }
				file_put_contents($temp_json, $json_content); unset($json_content);
			} else { $this->session->set_flashdata('notice', 'Could not open ZIP file.'); redirect('backup'); return; }
		} else { copy($uploaded_file, $temp_json); }

		$json = file_get_contents($temp_json); $data = json_decode($json, true); unset($json);
		if (!$data || !isset($data['stations']) || !isset($data['logbooks'])) { @unlink($temp_json); $this->session->set_flashdata('notice', 'Invalid backup file.'); redirect('backup'); return; }

		$station_qsos = isset($data['station_qsos']) ? $data['station_qsos'] : array();
		$import_preview = array(
			'stations' => array_map(function($station) use ($station_qsos) {
				$station_id = isset($station['station_id']) ? $station['station_id'] : null;
				$qso_count = ($station_id && isset($station_qsos[$station_id])) ? count($station_qsos[$station_id]) : 0;
				return array(
					'station_id' => $station_id,
					'station_callsign' => $station['station_callsign'],
					'station_profile_name' => $station['station_profile_name'],
					'qsos_count' => $qso_count,
				);
			}, $data['stations']),
			'logbooks' => array_map(function($logbook) {
				$qso_count = isset($logbook['qsos']) ? count($logbook['qsos']) : 0;
				return array(
					'logbook_id' => $logbook['logbook_id'],
					'logbook_name' => $logbook['logbook_name'],
					'station_id' => isset($logbook['station_id']) ? $logbook['station_id'] : null,
					'qsos_count' => $qso_count,
				);
			}, $data['logbooks']),
		);

		$this->session->set_userdata('import_backup_file', $temp_json);
		// HTMX partial
		$this->load->view('backup/import_preview', $import_preview);
	}

	public function user_do_import() {
		$this->load->model('user_model');
		if ($this->user_model->validate_session() == 0) { redirect('user/login'); }
		if(!$this->user_model->authorize(2)) { echo '<div class="alert alert-danger">Not authorized</div>'; return; }

		ini_set('memory_limit', '512M');
		$this->load->model('Stations');
		$this->load->model('Logbook_model');
		$this->load->library('session');

		$temp_file = $this->session->userdata('import_backup_file');
		if (!$temp_file || !file_exists($temp_file)) { echo '<div class="alert alert-danger">No import data found.</div>'; return; }
		$json = file_get_contents($temp_file); $data = json_decode($json, true); unset($json);
		if (!$data) { echo '<div class="alert alert-danger">Failed to parse backup data.</div>'; @unlink($temp_file); $this->session->unset_userdata('import_backup_file'); return; }

		$import_stations = $this->input->post('import_stations');
		$import_logbooks = $this->input->post('import_logbooks');
		if (!is_array($import_stations)) $import_stations = array();
		if (!is_array($import_logbooks)) $import_logbooks = array();
		if (empty($import_stations) && empty($import_logbooks)) { echo '<div class="alert alert-warning">Please select at least one station or logbook to import.</div>'; return; }

		$current_user_id = $this->session->userdata('user_id');
		if (!$current_user_id) { echo '<div class="alert alert-danger">User not authenticated.</div>'; return; }

		$total_stations = count($import_stations); $total_logbooks = count($import_logbooks); $total_qsos = 0;
		foreach ($data['logbooks'] as $logbook) { if (in_array($logbook['logbook_id'], $import_logbooks) && isset($logbook['qsos'])) { $total_qsos += count($logbook['qsos']); } }
		if (isset($data['station_qsos'])) { foreach ($data['station_qsos'] as $sid => $qsosArr) { if (in_array($sid, $import_stations)) { $total_qsos += count($qsosArr); } } }

		$imported = array('stations'=>0,'logbooks'=>0,'qsos'=>0,'conflicts'=>array(),'step'=>'','total_stations'=>$total_stations,'total_logbooks'=>$total_logbooks,'total_qsos'=>$total_qsos);
		$station_id_map = array();

		// Stations
		$imported['step'] = 'Importing stations';
		foreach ($data['stations'] as $station) {
			if (!in_array($station['station_id'], $import_stations)) continue; $old_station_id = $station['station_id'];
			$this->db->where('station_callsign', $station['station_callsign']);
			$this->db->where('station_profile_name', $station['station_profile_name']);
			$this->db->where('user_id', $current_user_id);
			$conflict = $this->db->get('station_profile')->row_array();
			if ($conflict) { $imported['conflicts'][] = 'Station exists (reusing): '.$station['station_callsign'].' - '.$station['station_profile_name'].' (ID '.$conflict['station_id'].')'; $station_id_map[$old_station_id] = $conflict['station_id']; continue; }
			unset($station['station_id']); $station['user_id'] = $current_user_id; $station['station_active'] = 0; // never active on import
			$this->db->insert('station_profile', $station); $new_station_id = $this->db->insert_id(); if (!$new_station_id) { $imported['conflicts'][] = 'Failed to create station: '.$station['station_callsign'].' - '.$station['station_profile_name']; continue; }
			$station_id_map[$old_station_id] = $new_station_id; $imported['stations']++;
		}

		// Logbooks
		$imported['step'] = 'Importing logbooks';
		foreach ($data['logbooks'] as $logbook) {
			if (!in_array($logbook['logbook_id'], $import_logbooks)) continue;
			$this->db->where('logbook_name', $logbook['logbook_name']); $this->db->where('user_id', $current_user_id); $conflict = $this->db->get('station_logbooks')->row_array();
			if ($conflict) { $imported['conflicts'][] = 'Logbook exists (skipping): '.$logbook['logbook_name']; continue; }
			$logbook_copy = $logbook; unset($logbook_copy['qsos']); unset($logbook_copy['logbook_id']); if (isset($logbook_copy['active_logbook'])) unset($logbook_copy['active_logbook']); $logbook_copy['user_id'] = $current_user_id; if (isset($logbook_copy['station_id']) && isset($station_id_map[$logbook_copy['station_id']])) { $logbook_copy['station_id'] = $station_id_map[$logbook_copy['station_id']]; }
			$this->db->insert('station_logbooks', $logbook_copy); $imported['logbooks']++;
		}

		// QSOs from station_qsos
		$imported['step'] = 'Importing QSOs';
		if (isset($data['station_qsos'])) {
			foreach ($data['station_qsos'] as $old_station_id => $qsos) {
				if (!in_array($old_station_id, $import_stations)) continue; if (!isset($station_id_map[$old_station_id])) continue; $new_station_id = $station_id_map[$old_station_id];
				foreach (array_chunk($qsos, 1000) as $qso_chunk) {
					foreach ($qso_chunk as $qso) {
						unset($qso['COL_PRIMARY_KEY']); $qso['station_id'] = $new_station_id;
						$this->db->where('COL_TIME_ON', $qso['COL_TIME_ON']); $this->db->where('COL_CALL', $qso['COL_CALL']); $this->db->where('station_id', $qso['station_id']);
						$conflict = $this->db->get($this->config->item('table_name'))->row_array(); if ($conflict) { $imported['conflicts'][] = 'QSO conflict: '.$qso['COL_CALL'].' @ '.$qso['COL_TIME_ON']; continue; }
						$this->db->insert($this->config->item('table_name'), $qso); $imported['qsos']++;
					}
				}
			}
		}
		// QSOs from logbooks (legacy)
		foreach ($data['logbooks'] as $logbook) {
			if (!in_array($logbook['logbook_id'], $import_logbooks)) continue; if (!isset($logbook['qsos'])) continue;
			foreach (array_chunk($logbook['qsos'], 1000) as $qso_chunk) {
				foreach ($qso_chunk as $qso) {
					unset($qso['COL_PRIMARY_KEY']); if (isset($qso['station_id']) && isset($station_id_map[$qso['station_id']])) { $qso['station_id'] = $station_id_map[$qso['station_id']]; }
					$this->db->where('COL_TIME_ON', $qso['COL_TIME_ON']); $this->db->where('COL_CALL', $qso['COL_CALL']); $this->db->where('station_id', $qso['station_id']);
					$conflict = $this->db->get($this->config->item('table_name'))->row_array(); if ($conflict) { $imported['conflicts'][] = 'QSO conflict: '.$qso['COL_CALL'].' @ '.$qso['COL_TIME_ON']; continue; }
					$this->db->insert($this->config->item('table_name'), $qso); $imported['qsos']++;
				}
			}
		}

		$temp_file = $this->session->userdata('import_backup_file'); if ($temp_file && file_exists($temp_file)) { @unlink($temp_file); }
		$this->session->unset_userdata('import_backup_file');

		$this->load->view('backup/import_progress', $imported);
	}
	
	/* User Facing Links to Backup URLs */
	public function index()
	{
		$this->load->model('user_model');
		// Allow all authenticated operators (level 2+) instead of only admins
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$data['page_title'] = "Backup";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('backup/main');
		$this->load->view('interface_assets/footer');
	}

	/* Gets all QSOs and Dumps them to logbook.adi */
	public function adif($key = null){ 
		if ($key == null) {
			$this->load->model('user_model');
			if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		}

		$this->load->helper('file');
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');
		
		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_all($key);

		$data['filename'] = 'backup/logbook'. date('_Y_m_d_H_i_s') .'.adi';
		
		if ( ! write_file($data['filename'], $this->load->view('backup/exportall', $data, true)))
		{
			$data['status'] = false;
		}
		else
		{
			$data['status'] = true;
		}

		$data['page_title'] = "ADIF - Backup";
		

		$this->load->view('interface_assets/header', $data);
		$this->load->view('backup/adif_view');
		$this->load->view('interface_assets/footer');

	}

	/* Export the notes to XML */
	public function notes($key = null) {
		if ($key == null) {
			$this->load->model('user_model');
			if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		}

		$this->load->helper('file');
		$this->load->model('note');

		$data['list_note'] = $this->note->list_all($key);

		$data['filename'] = 'backup/notes'. date('_Y_m_d_H_i_s') .'.xml';

		if ( ! write_file($data['filename'], $this->load->view('backup/notes', $data, true)))
		{
			$data['status'] = false;
		}
		else
		{
			$data['status'] = true;
		}

		$data['page_title'] = "Notes - Backup";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('backup/notes_view');
		$this->load->view('interface_assets/footer');

	}
}

/* End of file Backup.php */
