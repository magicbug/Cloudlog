<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Backup_files extends CI_Model {
	private $types = array(
		'qsl' => array('table' => 'qsl_images', 'id' => 'qsoid', 'file' => 'filename', 'directory' => 'assets/qslcard/'),
		'eqsl' => array('table' => 'eQSL_images', 'id' => 'qso_id', 'file' => 'image_file', 'directory' => 'images/eqsl_card_images/'),
		'sstv' => array('table' => 'sstv_images', 'id' => 'qsoid', 'file' => 'filename', 'directory' => 'assets/sstvimages/'),
		'diary' => array('table' => 'diary_images', 'id' => 'diary_id', 'file' => 'filename', 'directory' => 'uploads/diary/'),
	);
	private $created_files = array();
	private $created_rows = array();
	private $claimed_attachments = array();
	private $attachment_rows = array();

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
				$path = $this->contained_file($directory, $source);
				if (!$path) {
					$manifest['notices'][] = 'Missing or inaccessible '.$type.' image (record '.(int)$row['id'].').';
					continue;
				}
				try {
					$extension = $this->image_extension($path);
				} catch (RuntimeException $e) {
					$manifest['notices'][] = 'Unsupported '.$type.' image (record '.(int)$row['id'].').';
					continue;
				}
				$hash = hash_file('sha256', $path);
				if ($hash === false) {
					$manifest['notices'][] = 'Unreadable '.$type.' image (record '.(int)$row['id'].').';
					continue;
				}
				$entry = 'files/'.$type.'/'.$hash.'.'.$extension;
				if (!isset($added[$entry])) {
					if (!$zip->addFile($path, $entry)) throw new RuntimeException('Could not add an image to the backup.');
					$added[$entry] = true;
				}
				$manifest['files'][] = array(
					'type' => $type,
					'owner_id' => $row[$settings['id']],
					'image_id' => (int)$row['id'],
					'entry' => $entry,
					'sha256' => $hash,
					'caption' => isset($row['caption']) ? $row['caption'] : null,
					'sort_order' => isset($row['sort_order']) ? (int)$row['sort_order'] : 0,
				);
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
			!preg_match('#^files/'.preg_quote($file['type'], '#').'/'.$file['sha256'].'\.(jpg|png|gif|webp|bmp)$#D', $file['entry'])) {
			throw new RuntimeException('Invalid backup image reference.');
		}
		if (isset($file['image_id']) && (!is_scalar($file['image_id']) || !ctype_digit((string)$file['image_id']) || (int)$file['image_id'] < 1)) {
			throw new RuntimeException('Invalid backup image reference.');
		}
		if (isset($file['caption']) && $file['caption'] !== null && !is_string($file['caption'])) {
			throw new RuntimeException('Invalid backup image reference.');
		}
		if (isset($file['sort_order']) && !is_numeric($file['sort_order'])) {
			throw new RuntimeException('Invalid backup image reference.');
		}
	}

	private function image_extension($path) {
		$info = @getimagesize($path);
		$extensions = array(IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp');
		if (defined('IMAGETYPE_BMP')) $extensions[IMAGETYPE_BMP] = 'bmp';
		if (!$info || !isset($extensions[$info[2]])) throw new RuntimeException('Unsupported or invalid backup image.');
		return $extensions[$info[2]];
	}

	private function contained_file($directory, $source) {
		$root = realpath($directory);
		$path = realpath($source);
		if (!$root || !$path || !is_file($path) || !is_readable($path)) return null;
		$prefix = rtrim($root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		if (strpos($path, $prefix) !== 0) return null;
		return $path;
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

	private function insert_tracked($table, $record) {
		if ($this->db->insert($table, $record) === false) throw new RuntimeException('Could not save a restored record.');
		$id = (int)$this->db->insert_id();
		if ($id < 1) throw new RuntimeException('Could not save a restored record.');
		$this->created_rows[$table][] = $id;
		return $id;
	}

	private function clean_text($value) {
		return $this->security->xss_clean($value);
	}

	private function diary_identity($note) {
		$note = preg_replace('/\[\s*image\s*:\s*\d+/i', '[image:#', $note);
		return preg_replace('/&#0*91;\s*image\s*:\s*\d+/i', '&#91;image:#', $note);
	}

	private function remap_shortcodes($note, $image_map) {
		$replace = function ($matches) use ($image_map) {
			$id = (int)$matches[2];
			return $matches[1].(isset($image_map[$id]) ? $image_map[$id] : $matches[2]);
		};
		$note = preg_replace_callback('/(\[\s*image\s*:\s*)(\d+)/i', $replace, $note);
		return preg_replace_callback('/(&#0*91;\s*image\s*:\s*)(\d+)/i', $replace, $note);
	}

	private function find_matching_attachment($settings, $type, $owner_id, $hash, $user_id) {
		$cache_key = $settings['table'].':'.$owner_id;
		if (!isset($this->attachment_rows[$cache_key])) {
			$query = $this->db->where($settings['id'], $owner_id)->get($settings['table']);
			if ($query === false) throw new RuntimeException('Could not read existing images.');
			$this->attachment_rows[$cache_key] = $query->result_array();
		}
		$directory = FCPATH.$settings['directory'].($type === 'diary' ? $user_id.'/' : '');
		foreach ($this->attachment_rows[$cache_key] as $row) {
			$row_id = (int)$row['id'];
			if (isset($this->claimed_attachments[$settings['table']][$row_id])) continue;
			$filename = isset($row[$settings['file']]) ? $row[$settings['file']] : '';
			if (!is_string($filename) || $filename === '') continue;
			$source = $type === 'diary' ? FCPATH.$filename : $directory.$filename;
			$path = $this->contained_file($directory, $source);
			if (!$path) continue;
			$existing_hash = hash_file('sha256', $path);
			if ($existing_hash === false || !hash_equals($existing_hash, $hash)) continue;
			$this->claimed_attachments[$settings['table']][$row_id] = true;
			return $row;
		}
		return null;
	}

	public function restore($zip, $manifest, $qso_map, $user_id, $logbook_map, $include_diary) {
		$diary_map = array();
		$diary_bodies = array();
		$used_diary_ids = array();
		$diary_image_map = array();
		if ($include_diary) {
			foreach (isset($manifest['diary_entries']) ? $manifest['diary_entries'] : array() as $note) {
				$old_id = $note['id'];
				$title = $this->clean_text($note['title']);
				$body = $this->clean_text($note['note']);
				$identity = $this->diary_identity($body);
				$candidates = $this->db->where('user_id', $user_id)->where('title', $title)->where('cat', $note['cat'])->where('created_at', $note['created_at'])->order_by('id', 'ASC')->get('notes');
				if ($candidates === false) throw new RuntimeException('Could not read existing diary entries.');
				$existing_id = null;
				foreach ($candidates->result_array() as $existing) {
					if (isset($used_diary_ids[$existing['id']])) continue;
					if ($this->diary_identity($existing['note']) === $identity) {
						$existing_id = $existing['id'];
						break;
					}
				}
				if ($existing_id !== null) {
					$diary_map[$old_id] = $existing_id;
					$used_diary_ids[$existing_id] = true;
					$diary_bodies[$old_id] = $body;
					continue;
				}
				$record = array_intersect_key($note, array_flip($this->db->list_fields('notes')));
				unset($record['id']);
				$record['title'] = $title;
				$record['note'] = $body;
				$record['user_id'] = $user_id;
				$record['is_public'] = 0;
				$source_logbook = $record['logbook_id'] ?? null;
				$record['logbook_id'] = (is_scalar($source_logbook) && !is_bool($source_logbook) && isset($logbook_map[$source_logbook])) ? $logbook_map[$source_logbook] : null;
				$new_id = $this->insert_tracked('notes', $record);
				$diary_map[$old_id] = $new_id;
				$used_diary_ids[$new_id] = true;
				$diary_bodies[$old_id] = $body;
			}
		}
		$count = 0;
		foreach ($manifest['files'] as $file) {
			$this->check_record($file);
			$settings = $this->types[$file['type']];
			$map = $file['type'] === 'diary' ? $diary_map : $qso_map;
			if (empty($map[$file['owner_id']])) continue;
			$destination_owner = $map[$file['owner_id']];
			$existing = $this->find_matching_attachment($settings, $file['type'], $destination_owner, $file['sha256'], $user_id);
			if ($existing) {
				if ($file['type'] === 'diary' && isset($file['image_id'])) $diary_image_map[(int)$file['image_id']] = (int)$existing['id'];
				continue;
			}
			$directory = FCPATH.$settings['directory'].($file['type'] === 'diary' ? $user_id.'/' : '');
			if (!is_dir($directory) && !mkdir($directory, 0755, true)) throw new RuntimeException('Could not create image directory.');
			$image_id = isset($file['image_id']) ? (int)$file['image_id'] : 0;
			$filename = 'backup_'.$destination_owner.'_'.$image_id.'_'.$file['sha256'].'.'.pathinfo($file['entry'], PATHINFO_EXTENSION);
			$destination = $directory.$filename;
			if (is_link($destination) || is_dir($destination)) throw new RuntimeException('Image destination conflicts with an existing file.');
			if (is_file($destination)) {
				$existing_hash = hash_file('sha256', $destination);
				if ($existing_hash === false || !hash_equals($existing_hash, $file['sha256'])) throw new RuntimeException('Image destination conflicts with an existing file.');
			} else {
				$temp = tempnam($directory, 'restore_');
				try {
					$this->copy_entry($zip, $file['entry'], $temp);
					$copied_hash = hash_file('sha256', $temp);
					if ($copied_hash === false || !hash_equals($copied_hash, $file['sha256'])) throw new RuntimeException('Backup image checksum mismatch.');
					chmod($temp, 0644);
					// An exclusive hard link prevents overwriting a concurrent upload.
					if (!link($temp, $destination)) throw new RuntimeException('Could not restore image file.');
					$this->created_files[] = $destination;
				} finally { if ($temp) @unlink($temp); }
			}
			$stored_name = $file['type'] === 'diary' ? $settings['directory'].$user_id.'/'.$filename : $filename;
			$record = array($settings['id'] => $destination_owner, $settings['file'] => $stored_name);
			if ($this->db->where($record)->count_all_results($settings['table']) > 0) continue;
			if ($file['type'] === 'diary') {
				$record['caption'] = isset($file['caption']) && is_string($file['caption']) ? $this->clean_text($file['caption']) : null;
				$record['sort_order'] = isset($file['sort_order']) ? (int)$file['sort_order'] : 0;
			}
			$new_image_id = $this->insert_tracked($settings['table'], $record);
			if ($file['type'] === 'diary' && isset($file['image_id'])) $diary_image_map[(int)$file['image_id']] = $new_image_id;
			$count++;
		}
		foreach ($diary_bodies as $old_id => $body) {
			$remapped = $this->remap_shortcodes($body, $diary_image_map);
			if ($remapped === $body) continue;
			$note_id = $diary_map[$old_id];
			if ($this->db->where('id', $note_id)->where('user_id', $user_id)->update('notes', array('note' => $remapped)) === false) {
				throw new RuntimeException('Could not update restored diary images.');
			}
		}
		return $count;
	}

	public function rollback_files() {
		foreach (array('diary_images', 'qsl_images', 'eQSL_images', 'sstv_images', 'notes') as $table) {
			if (empty($this->created_rows[$table])) continue;
			$this->db->where_in('id', $this->created_rows[$table])->delete($table);
		}
		foreach ($this->created_files as $file) @unlink($file);
		$this->created_files = array();
		$this->created_rows = array();
	}
}
