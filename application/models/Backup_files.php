<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Backup_files extends CI_Model {
	private $types = array(
		'qsl' => array('table' => 'qsl_images', 'id' => 'qsoid', 'file' => 'filename', 'directory' => 'assets/qslcard/'),
		'eqsl' => array('table' => 'eQSL_images', 'id' => 'qso_id', 'file' => 'image_file', 'directory' => 'images/eqsl_card_images/'),
		'sstv' => array('table' => 'sstv_images', 'id' => 'qsoid', 'file' => 'filename', 'directory' => 'assets/sstvimages/'),
		'diary' => array('table' => 'diary_images', 'id' => 'diary_id', 'file' => 'filename', 'directory' => 'uploads/diary/'),
	);
	private $created_files = array();

	public function export($zip, $user_id) {
		$manifest = array('files' => array(), 'diary_entries' => array(), 'notices' => array());
		$added = array();
		foreach ($this->types as $type => $settings) {
			if (!$this->db->table_exists($settings['table'])) continue;
			$this->db->select('attachment.*');
			$this->db->from($settings['table'].' attachment');
			if ($type === 'diary') {
				$this->db->join('notes owner', 'owner.id = attachment.diary_id');
			} else {
				$this->db->join($this->config->item('table_name').' qso', 'qso.COL_PRIMARY_KEY = attachment.'.$settings['id']);
				$this->db->join('station_profile owner', 'owner.station_id = qso.station_id');
			}
			$this->db->where('owner.user_id', $user_id);
			foreach ($this->db->get()->result_array() as $row) {
				$directory = FCPATH.$settings['directory'].($type === 'diary' ? $user_id.'/' : '');
				$filename = $row[$settings['file']];
				$source = $type === 'diary' ? FCPATH.$filename : $directory.$filename;
				$root = realpath($directory);
				$path = realpath($source);
				if (!$root || !$path || strpos($path, $root.DIRECTORY_SEPARATOR) !== 0 || !is_file($path) || !is_readable($path)) {
					$manifest['notices'][] = 'Missing or inaccessible '.$type.' image (record '.(int)$row['id'].').';
					continue;
				}
				$extension = $this->image_extension($path);
				$hash = hash_file('sha256', $path);
				$entry = 'files/'.$type.'/'.$hash.'.'.$extension;
				if (!isset($added[$entry])) {
					if (!$zip->addFile($path, $entry)) throw new RuntimeException('Could not add an image to the backup.');
					$added[$entry] = true;
				}
				$manifest['files'][] = array('type' => $type, 'owner_id' => $row[$settings['id']], 'entry' => $entry, 'sha256' => $hash,
					'caption' => isset($row['caption']) ? $row['caption'] : null, 'sort_order' => isset($row['sort_order']) ? (int)$row['sort_order'] : 0);
				if ($type === 'diary' && !isset($manifest['diary_entries'][$row['diary_id']])) {
					$manifest['diary_entries'][$row['diary_id']] = $this->db->where('id', $row['diary_id'])->where('user_id', $user_id)->get('notes')->row_array();
				}
			}
		}
		$manifest['diary_entries'] = array_values($manifest['diary_entries']);
		return $manifest;
	}

	// Only read manifest entries; never extract ZIP paths into the application.
	public function validate($zip, $manifest) {
		if (!is_array($manifest) || !isset($manifest['files']) || !is_array($manifest['files']) || count($manifest['files']) > 100000) {
			throw new RuntimeException('Invalid image manifest.');
		}
		if (isset($manifest['diary_entries']) && !is_array($manifest['diary_entries'])) throw new RuntimeException('Invalid diary entries.');
		if (isset($manifest['notices']) && !is_array($manifest['notices'])) throw new RuntimeException('Invalid backup notices.');
		foreach ($manifest['notices'] ?? array() as $notice) {
			if (!is_string($notice)) throw new RuntimeException('Invalid backup notice.');
		}
		foreach ($manifest['diary_entries'] ?? array() as $note) {
			if (!is_array($note) || !isset($note['id'], $note['title'], $note['cat'], $note['note'], $note['created_at']) ||
				!is_scalar($note['id']) || !ctype_digit((string)$note['id']) || !is_string($note['title']) || !is_string($note['cat']) || !is_string($note['note']) || !is_string($note['created_at']) || strtoupper(trim($note['cat'])) !== 'STATION DIARY') {
				throw new RuntimeException('Invalid diary entry.');
			}
		}
		$checked = array();
		$total_size = 0;
		foreach ($manifest['files'] as $file) {
			$this->check_record($file);
			if (isset($checked[$file['entry']])) continue;
			$stat = $zip->statName($file['entry']);
			if (!$stat || $stat['size'] > 100 * 1024 * 1024) throw new RuntimeException('Missing or oversized backup image.');
			$total_size += $stat['size'];
			if ($total_size > 2 * 1024 * 1024 * 1024) throw new RuntimeException('Backup images exceed the 2 GiB import limit.');
			$temp = tempnam(sys_get_temp_dir(), 'cloudlog_image_');
			try {
				$this->copy_entry($zip, $file['entry'], $temp);
				if (hash_file('sha256', $temp) !== $file['sha256'] || pathinfo($file['entry'], PATHINFO_EXTENSION) !== $this->image_extension($temp)) {
					throw new RuntimeException('Backup image checksum or format mismatch.');
				}
			} finally {
				if ($temp) @unlink($temp);
			}
			$checked[$file['entry']] = true;
		}
	}

	private function check_record($file) {
		if (!is_array($file) || !isset($file['type'], $file['owner_id'], $file['entry'], $file['sha256']) || !is_string($file['type']) || !isset($this->types[$file['type']]) ||
			!is_scalar($file['owner_id']) || !is_string($file['entry']) || !is_string($file['sha256']) || !ctype_digit((string)$file['owner_id']) || (int)$file['owner_id'] < 1 || !preg_match('/^[a-f0-9]{64}$/D', $file['sha256']) ||
			!preg_match('#^files/'.preg_quote($file['type'], '#').'/'.$file['sha256'].'\.(jpg|png|gif|webp)$#D', $file['entry'])) {
			throw new RuntimeException('Invalid backup image reference.');
		}
	}

	private function image_extension($path) {
		$info = @getimagesize($path);
		$extensions = array(IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp');
		if (!$info || !isset($extensions[$info[2]])) throw new RuntimeException('Unsupported or invalid backup image.');
		return $extensions[$info[2]];
	}

	private function copy_entry($zip, $entry, $destination) {
		$input = $zip->getStream($entry);
		$output = fopen($destination, 'wb');
		if (!$input || !$output) {
			if (is_resource($input)) fclose($input);
			if (is_resource($output)) fclose($output);
			throw new RuntimeException('Could not read or write a backup image.');
		}
		try {
			$size = stream_copy_to_stream($input, $output, 100 * 1024 * 1024 + 1);
			if ($size === false || $size > 100 * 1024 * 1024) throw new RuntimeException('Could not copy backup image.');
		} finally {
			fclose($input);
			fclose($output);
		}
	}

	public function restore($zip, $manifest, $qso_map, $user_id, $logbook_map, $include_diary) {
		$diary_map = array();
		if ($include_diary) {
			foreach (isset($manifest['diary_entries']) ? $manifest['diary_entries'] : array() as $note) {
				$old_id = $note['id'];
				// Reuse an existing entry belonging to this user on repeated imports.
				$existing = $this->db->where('user_id', $user_id)->where('title', $note['title'])->where('cat', $note['cat'])->where('created_at', $note['created_at'])->where('note', $note['note'])->get('notes')->row_array();
				if ($existing) { $diary_map[$old_id] = $existing['id']; continue; }
				$note = array_intersect_key($note, array_flip($this->db->list_fields('notes')));
				unset($note['id']);
				$note['user_id'] = $user_id;
				$note['is_public'] = 0;
				$note['logbook_id'] = isset($logbook_map[$note['logbook_id'] ?? '']) ? $logbook_map[$note['logbook_id']] : null;
				$this->db->insert('notes', $note);
				$diary_map[$old_id] = $this->db->insert_id();
			}
		}
		$count = 0;
		foreach ($manifest['files'] as $file) {
			$this->check_record($file);
			$settings = $this->types[$file['type']];
			$map = $file['type'] === 'diary' ? $diary_map : $qso_map;
			if (empty($map[$file['owner_id']])) continue;
			$directory = FCPATH.$settings['directory'].($file['type'] === 'diary' ? $user_id.'/' : '');
			if (!is_dir($directory) && !mkdir($directory, 0755, true)) throw new RuntimeException('Could not create image directory.');
			$filename = 'backup_'.$file['sha256'].'.'.pathinfo($file['entry'], PATHINFO_EXTENSION);
			$destination = $directory.$filename;
			if (is_link($destination)) throw new RuntimeException('Image destination is a symbolic link.');
			if (file_exists($destination)) {
				if (hash_file('sha256', $destination) !== $file['sha256']) throw new RuntimeException('Image destination conflicts with an existing file.');
			} else {
				$temp = tempnam($directory, 'restore_');
				try {
					$this->copy_entry($zip, $file['entry'], $temp);
					if (hash_file('sha256', $temp) !== $file['sha256']) throw new RuntimeException('Backup image checksum mismatch.');
					chmod($temp, 0644);
					// An exclusive hard link prevents overwriting a concurrent upload.
					if (!link($temp, $destination)) throw new RuntimeException('Could not restore image file.');
					$this->created_files[] = $destination;
				} finally { if ($temp) @unlink($temp); }
			}
			$stored_name = $file['type'] === 'diary' ? $settings['directory'].$user_id.'/'.$filename : $filename;
			$record = array($settings['id'] => $map[$file['owner_id']], $settings['file'] => $stored_name);
			if ($this->db->where($record)->count_all_results($settings['table']) > 0) continue;
			if ($file['type'] === 'diary') {
				$record['caption'] = isset($file['caption']) ? $file['caption'] : null;
				$record['sort_order'] = isset($file['sort_order']) ? (int)$file['sort_order'] : 0;
			}
			$this->db->insert($settings['table'], $record);
			$count++;
		}
		return $count;
	}

	public function rollback_files() {
		foreach ($this->created_files as $file) @unlink($file);
		$this->created_files = array();
	}
}
