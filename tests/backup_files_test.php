<?php
// Run with: php tests/backup_files_test.php
// Uses connection-local temporary tables and a temporary image directory.
error_reporting(E_ALL & ~E_DEPRECATED);
define('ENVIRONMENT', 'testing');
define('BASEPATH', dirname(__DIR__).'/system/');
define('APPPATH', dirname(__DIR__).'/application/');
define('VIEWPATH', APPPATH.'views/');
define('FCPATH', sys_get_temp_dir().'/cloudlog_backup_test_'.bin2hex(random_bytes(8)).'/');
mkdir(FCPATH, 0700);
function cleanup_backup_test() {
	if (!is_dir(FCPATH)) return;
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(FCPATH, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
	foreach ($iterator as $file) { if ($file->isDir() && !$file->isLink()) rmdir($file->getPathname()); else unlink($file->getPathname()); }
	rmdir(FCPATH);
}
register_shutdown_function('cleanup_backup_test');
require BASEPATH.'core/Common.php';
require BASEPATH.'database/DB.php';
require APPPATH.'config/database.php';
$test_db = DB($db[$active_group]);
$test_db->db_debug = false;
require BASEPATH.'core/Model.php';
require BASEPATH.'core/Security.php';
require APPPATH.'models/Backup_files.php';

function check($condition, $message) {
	if (!$condition) throw new RuntimeException($message);
}
function rejects($callback, $message) {
	try { $callback(); } catch (RuntimeException $e) { return; }
	throw new RuntimeException($message);
}
function refill_archive($path, $payloads) {
	$zip = new ZipArchive();
	check($zip->open($path, ZipArchive::CREATE) === true, 'Could not rebuild backup archive');
	foreach ($payloads as $entry => $bytes) {
		check($bytes !== false && $zip->addFromString($entry, $bytes), 'Could not rebuild backup entry '.$entry);
	}
	check($zip->close(), 'Could not finish rebuilt backup archive');
}
class BackupTestConfig {
	public function item($key) { return $key === 'table_name' ? 'TABLE_HRD_CONTACTS_V01' : null; }
}
class BackupTestSession {
	public $values = array('user_id' => 9);
	public function userdata($key) { return $this->values[$key] ?? null; }
	public function set_userdata($key, $value) { $this->values[$key] = $value; }
	public function unset_userdata($key) { unset($this->values[$key]); }
}
class BackupTestInput {
	public $values = array('import_stations' => array(10), 'import_logbooks' => array(50), 'import_diary' => '1');
	public function post($key) { return $this->values[$key] ?? null; }
}
class BackupTestUser {
	public function validate_session() { return 1; }
	public function authorize($level) { return true; }
}
class BackupTestLoader {
	public $result;
	public function model($name) {
		$ci = get_instance();
		if ($name === 'user_model') $ci->user_model = new BackupTestUser();
		if ($name === 'Backup_files') $ci->Backup_files = new Backup_files();
	}
	public function library($name) {}
	public function dbutil() {}
	public function view($name, $data) { $this->result = $data; }
}
#[AllowDynamicProperties]
class CI_Controller {
	public function __construct() {
		$GLOBALS['backup_test_ci'] = $this;
		$this->db = $GLOBALS['test_db'];
		$this->config = new BackupTestConfig();
		$this->session = new BackupTestSession();
		$this->input = new BackupTestInput();
		$this->load = new BackupTestLoader();
		$this->security = new CI_Security('UTF-8');
	}
}
function &get_instance() { return $GLOBALS['backup_test_ci']; }
require APPPATH.'controllers/Backup.php';
$controller = new Backup();
$files = new Backup_files();

try {
	foreach (array('station_profile', 'station_logbooks', 'station_logbooks_relationship', 'TABLE_HRD_CONTACTS_V01', 'qsl_images', 'eQSL_images', 'sstv_images', 'notes', 'diary_images') as $table) {
		$definition = $test_db->query('SHOW CREATE TABLE `'.$table.'`')->row_array();
		$create = str_replace('CREATE TABLE ', 'CREATE TEMPORARY TABLE ', $definition['Create Table']);
		check($test_db->query($create) !== false, 'Could not create temporary fixture table '.$table);
	}
	check($test_db->query('ALTER TABLE `TABLE_HRD_CONTACTS_V01` ENGINE=MyISAM') !== false, 'Could not set the QSO table to MyISAM');
	check($test_db->query('ALTER TABLE `qsl_images` ENGINE=MyISAM') !== false, 'Could not set qsl_images to MyISAM');
	$station = array('station_id' => 10, 'user_id' => 7, 'station_callsign' => 'TEST1', 'station_profile_name' => 'Backup test', 'station_active' => 0);
	check($test_db->insert('station_profile', $station), 'Station fixture failed');
	$test_db->insert('station_profile', array('station_id' => 20, 'user_id' => 8, 'station_callsign' => 'OTHER', 'station_profile_name' => 'Other user', 'station_active' => 0));
	$qso = array('COL_PRIMARY_KEY' => 100, 'station_id' => 10, 'COL_CALL' => 'TEST2', 'COL_TIME_ON' => '2026-01-01 12:00:00');
	check($test_db->insert('TABLE_HRD_CONTACTS_V01', $qso), 'QSO fixture failed');
	$test_db->insert('TABLE_HRD_CONTACTS_V01', array('COL_PRIMARY_KEY' => 200, 'station_id' => 20, 'COL_CALL' => 'OTHER', 'COL_TIME_ON' => '2026-01-01 12:00:00'));
	$logbook = array('logbook_id' => 50, 'user_id' => 7, 'logbook_name' => 'Backup test');
	$test_db->insert('station_logbooks', $logbook);
	$test_db->insert('station_logbooks_relationship', array('station_logbook_id' => 50, 'station_location_id' => 10));
	$test_db->insert('notes', array('id' => 30, 'user_id' => 7, 'title' => 'Diary test', 'cat' => 'STATION DIARY', 'note' => 'Test image', 'created_at' => '2026-01-01 12:00:00', 'is_public' => 1, 'logbook_id' => 50));
	$test_db->insert('notes', array('id' => 40, 'user_id' => 7, 'title' => 'Duplicate diary', 'cat' => 'STATION DIARY', 'note' => 'Same body', 'created_at' => '2026-01-02 12:00:00', 'is_public' => 1, 'logbook_id' => 50));
	$test_db->insert('notes', array('id' => 41, 'user_id' => 7, 'title' => 'Duplicate diary', 'cat' => 'STATION DIARY', 'note' => 'Same body', 'created_at' => '2026-01-02 12:00:00', 'is_public' => 1, 'logbook_id' => 50));
	$image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aF3sAAAAASUVORK5CYII=');
	$bmp = base64_decode('Qk06AAAAAAAAADYAAAAoAAAAAQAAAAEAAAABABgAAAAAAAQAAAAAAAAAAAAAAAAAAAAA/wAA');
	foreach (array('assets/qslcard', 'assets/sstvimages', 'images/eqsl_card_images', 'uploads/diary/7') as $directory) {
		mkdir(FCPATH.$directory, 0755, true);
		file_put_contents(FCPATH.$directory.'/test.png', $image);
	}
	file_put_contents(FCPATH.'assets/sstvimages/test.bmp', $bmp);
	file_put_contents(FCPATH.'uploads/diary/7/duplicate-a.png', $image);
	file_put_contents(FCPATH.'uploads/diary/7/duplicate-b.png', $image);
	$bmp_info = @getimagesize(FCPATH.'assets/sstvimages/test.bmp');
	check($bmp_info && $bmp_info[2] === IMAGETYPE_BMP, 'Fixture BMP was not recognized');
	$test_db->insert('qsl_images', array('qsoid' => 100, 'filename' => 'test.png'));
	$test_db->insert('qsl_images', array('qsoid' => 200, 'filename' => 'other-user.png'));
	$test_db->insert('qsl_images', array('qsoid' => 100, 'filename' => 'missing.png'));
	$test_db->insert('eQSL_images', array('qso_id' => 100, 'image_file' => 'test.png'));
	$test_db->insert('sstv_images', array('qsoid' => 100, 'filename' => 'test.png'));
	$test_db->insert('sstv_images', array('qsoid' => 100, 'filename' => 'test.bmp'));
	$test_db->insert('diary_images', array('diary_id' => 30, 'filename' => 'uploads/diary/7/test.png', 'caption' => 'Caption', 'sort_order' => 2));
	$source_diary_image_id = (int)$test_db->insert_id();
	$test_db->where('id', 30)->update('notes', array('note' => 'Test image [image:'.$source_diary_image_id.']'));
	$test_db->insert('diary_images', array('diary_id' => 40, 'filename' => 'uploads/diary/7/duplicate-a.png', 'caption' => 'A', 'sort_order' => 1));
	$test_db->insert('diary_images', array('diary_id' => 41, 'filename' => 'uploads/diary/7/duplicate-b.png', 'caption' => 'B', 'sort_order' => 1));
	if (isset($argv[1]) && $argv[1] === '--export') {
		$controller->session->set_userdata('user_id', 7);
		$controller->user_export();
	}
	$archive_path = FCPATH.'backup.zip';
	$zip = new ZipArchive();
	check($zip->open($archive_path, ZipArchive::CREATE) === true, 'ZIP creation failed');
	$manifest = $files->export($zip, 7);
	check(count($manifest['files']) === 7, 'Expected QSL, eQSL, SSTV, BMP and diary images for the exporting user');
	check(count($manifest['notices']) === 1, 'Missing source image must be reported');
	check(count($manifest['diary_entries']) === 3, 'Diary metadata not exported');
	check(in_array('bmp', array_map(function ($file) { return pathinfo($file['entry'], PATHINFO_EXTENSION); }, $manifest['files']), true), 'BMP attachment was rejected during export');
	foreach ($manifest['files'] as $exported_file) check(!empty($exported_file['image_id']), 'Exported image is missing its source id');
	check($zip->close(), 'ZIP close failed');
	check($zip->open($archive_path) === true, 'ZIP reopen failed');
	$payloads = array();
	foreach ($manifest['files'] as $record) $payloads[$record['entry']] = $zip->getFromName($record['entry']);
	$files->validate($zip, $manifest);
	$bad = $manifest; $bad['files'][0]['entry'] = '../../application/config/database.php';
	rejects(function() use ($files, $zip, $bad) { $files->validate($zip, $bad); }, 'Traversal accepted');
	$bad = $manifest; $bad['files'][0]['owner_id'] = -1;
	rejects(function() use ($files, $zip, $bad) { $files->validate($zip, $bad); }, 'Invalid owner accepted');
	$bad = $manifest; $bad['files'][0]['sha256'] = str_repeat('0', 64); $bad['files'][0]['entry'] = 'files/qsl/'.str_repeat('0', 64).'.png';
	rejects(function() use ($files, $zip, $bad) { $files->validate($zip, $bad); }, 'Missing ZIP image accepted');
	check($files->restore($zip, $manifest, array(), 9, array(), false) === 0, 'Unselected attachments restored');
	$zip->close();
	$export_path = FCPATH.'exported.zip';
	$process = proc_open(array(PHP_BINARY, __FILE__, '--export'), array(0 => array('pipe', 'r'), 1 => array('file', $export_path, 'w'), 2 => array('pipe', 'w')), $pipes);
	fclose($pipes[0]); $errors = stream_get_contents($pipes[2]); fclose($pipes[2]);
	check(proc_close($process) === 0 && $errors === '', 'Controller ZIP export failed: '.$errors);
	$export_zip = new ZipArchive();
	check($export_zip->open($export_path) === true, 'Controller did not produce a valid ZIP');
	$exported = json_decode($export_zip->getFromName('cloudlog_backup.json'), true);
	check($exported['schema_version'] === '1.1' && count($exported['attachments']['files']) === 7 && count($exported['logbook_relationships']) === 1, 'Controller ZIP metadata incomplete');
	$files->validate($export_zip, $exported['attachments']); $export_zip->close();
	$_FILES['backup_file'] = array('tmp_name' => $export_path, 'error' => UPLOAD_ERR_OK);
	$controller->user_import();
	check($controller->load->result['images_count'] === 7 && $controller->load->result['diary_count'] === 3, 'Upload preview omitted images or diary');
	unlink($controller->session->userdata('import_backup_file'));
	unlink($controller->session->userdata('import_backup_zip'));
	$controller->session->unset_userdata('import_backup_file');
	$controller->session->unset_userdata('import_backup_zip');
	echo "PASS: controller generates a valid ZIP with images and relationships; upload preview stages the archive and counts attachments.\n";
	$data = array('schema_version' => '1.1', 'stations' => array($station), 'logbooks' => array($logbook), 'station_qsos' => array(10 => array($qso)), 'attachments' => $manifest,
		'logbook_relationships' => array(array('station_logbook_id' => 50, 'station_location_id' => 10)));
	$json_path = FCPATH.'backup.json';
	file_put_contents($json_path, json_encode($data));
	$controller->session->set_userdata('user_id', 7);
	$controller->session->set_userdata('import_backup_file', $json_path);
	$controller->session->set_userdata('import_backup_zip', $archive_path);
	$notes_before = $test_db->where('user_id', 7)->count_all_results('notes');
	$qsl_before = $test_db->where('qsoid', 100)->count_all_results('qsl_images');
	$controller->user_do_import();
	check($controller->load->result['images'] === 0 && $controller->load->result['qsos'] === 0, 'Import into the original account duplicated attachments or QSOs');
	check($test_db->where('user_id', 7)->count_all_results('notes') === $notes_before, 'Import into the original account duplicated diary entries');
	check($test_db->where('qsoid', 100)->count_all_results('qsl_images') === $qsl_before, 'Import into the original account duplicated an existing QSL image');
	$controller->session->set_userdata('user_id', 9);
	$data['attachments']['diary_entries'][] = array('id' => 99, 'user_id' => 7, 'title' => '<script>alert(1)</script>', 'cat' => 'STATION DIARY', 'note' => '<img src=x onerror=alert(1)>', 'created_at' => '2026-01-03 12:00:00', 'is_public' => 1, 'logbook_id' => 50);
	refill_archive($archive_path, $payloads);
	file_put_contents($json_path, json_encode($data));
	$controller->session->set_userdata('import_backup_file', $json_path);
	$controller->session->set_userdata('import_backup_zip', $archive_path);
	$controller->user_do_import();
	$result = $controller->load->result;
	check(isset($result['images']) && $result['images'] === 7, 'Controller did not restore every attachment');
	check($result['qsos'] === 1 && $result['stations'] === 1 && $result['logbooks'] === 1, 'Controller data restore failed');
	$dest_station = $test_db->where('user_id', 9)->get('station_profile')->row_array();
	$dest_qso = $test_db->where('station_id', $dest_station['station_id'])->get('TABLE_HRD_CONTACTS_V01')->row_array();
	check($dest_qso['COL_PRIMARY_KEY'] != 100, 'Test did not exercise QSO ID remapping');
	foreach (array('qsl_images' => 'qsoid', 'eQSL_images' => 'qso_id') as $table => $field) {
		check($test_db->where($field, $dest_qso['COL_PRIMARY_KEY'])->count_all_results($table) === 1, 'Image linked to wrong QSO: '.$table);
	}
	check($test_db->where('qsoid', $dest_qso['COL_PRIMARY_KEY'])->count_all_results('sstv_images') === 2, 'SSTV images were not both restored');
	$dest_logbook = $test_db->where('user_id', 9)->get('station_logbooks')->row_array();
	check($test_db->where('station_logbook_id', $dest_logbook['logbook_id'])->where('station_location_id', $dest_station['station_id'])->count_all_results('station_logbooks_relationship') === 1, 'Logbook relationships not restored');
	$dest_note = $test_db->where('user_id', 9)->where('title', 'Diary test')->get('notes')->row_array();
	check($dest_note['is_public'] == 0 && $dest_note['logbook_id'] == $dest_logbook['logbook_id'], 'Diary privacy or ID remapping failed');
	$diary_image = $test_db->where('diary_id', $dest_note['id'])->get('diary_images')->row_array();
	check(strpos($diary_image['filename'], 'uploads/diary/9/') === 0 && file_get_contents(FCPATH.$diary_image['filename']) === $image, 'Diary image content or user path wrong');
	check(preg_match('/(?:\[image:|&#0*91;image:)'.preg_quote($diary_image['id'], '/').'\b/', $dest_note['note']) === 1, 'Diary shortcode was not remapped to the restored image');
	$duplicate_notes = $test_db->where('user_id', 9)->where('title', 'Duplicate diary')->order_by('id', 'ASC')->get('notes')->result_array();
	check(count($duplicate_notes) === 2, 'Identical diary entries were merged');
	$duplicate_names = array();
	foreach ($duplicate_notes as $duplicate_note) {
		$duplicate_image = $test_db->where('diary_id', $duplicate_note['id'])->get('diary_images')->row_array();
		check($duplicate_image && is_file(FCPATH.$duplicate_image['filename']), 'Duplicate diary image was not restored');
		$duplicate_names[] = $duplicate_image['filename'];
	}
	check($duplicate_names[0] !== $duplicate_names[1], 'Identical restored images share one file');
	$restored_xss = $test_db->where('user_id', 9)->where('created_at', '2026-01-03 12:00:00')->get('notes')->row_array();
	check($restored_xss && stripos($restored_xss['title'], '<script') === false && stripos($restored_xss['note'], 'onerror') === false, 'Imported diary HTML was not sanitized');
	// Repeat with the same archive content: reuse QSOs and do not duplicate images.
	refill_archive($archive_path, $payloads);
	file_put_contents($json_path, json_encode($data));
	$controller->session->set_userdata('import_backup_file', $json_path);
	$controller->session->set_userdata('import_backup_zip', $archive_path);
	$controller->user_do_import();
	check($controller->load->result['images'] === 0 && $controller->load->result['qsos'] === 0, 'Repeated import duplicated attachments or QSOs');
	check($test_db->where('user_id', 9)->where('title', 'Duplicate diary')->count_all_results('notes') === 2, 'Repeated import merged or duplicated identical diary entries');
	// Original schema remains importable without an archive.
	unset($data['attachments'], $data['logbook_relationships']);
	$data['schema_version'] = '1.0';
	file_put_contents($json_path, json_encode($data));
	$controller->session->set_userdata('import_backup_file', $json_path);
	$controller->user_do_import();
	check($controller->load->result['images'] === 0, 'Legacy JSON restore failed');
	$legacy = $data;
	$legacy['schema_version'] = '1.1';
	$legacy['attachments'] = $manifest;
	$legacy['logbook_relationships'] = array(array('station_logbook_id' => 50, 'station_location_id' => 10));
	$legacy['logbooks'][0]['qsos'] = array($qso);
	unset($legacy['station_qsos']);
	refill_archive($archive_path, $payloads);
	file_put_contents($json_path, json_encode($legacy));
	$controller->input->values = array('import_stations' => array(), 'import_logbooks' => array(50), 'import_diary' => '0');
	$controller->session->set_userdata('user_id', 12);
	$controller->session->set_userdata('import_backup_file', $json_path);
	$controller->session->set_userdata('import_backup_zip', $archive_path);
	$qso_count_before_skip = $test_db->count_all('TABLE_HRD_CONTACTS_V01');
	$controller->user_do_import();
	$skipped = $controller->load->result;
	check($skipped['qsos'] === 0 && $test_db->count_all('TABLE_HRD_CONTACTS_V01') === $qso_count_before_skip, 'QSOs were imported without their station');
	check(isset($skipped['conflicts']) && preg_grep('/station was not selected/', $skipped['conflicts']), 'Missing station dependency was not reported');
	$malformed = $legacy;
	$malformed['logbook_relationships'] = array(array('station_logbook_id' => array(50), 'station_location_id' => 10));
	refill_archive($archive_path, $payloads);
	file_put_contents($json_path, json_encode($malformed));
	$controller->input->values = array('import_stations' => array(10), 'import_logbooks' => array(50), 'import_diary' => '0');
	$controller->session->set_userdata('user_id', 13);
	$controller->session->set_userdata('import_backup_file', $json_path);
	$controller->session->set_userdata('import_backup_zip', $archive_path);
	ob_start(); $controller->user_do_import(); $malformed_failure = ob_get_clean();
	check(strpos($malformed_failure, 'Import failed') !== false, 'Malformed relationship did not fail the import');
	check($test_db->where('user_id', 13)->count_all_results('station_profile') === 0, 'Malformed relationship left a restored station');
	$controller->input->values = array('import_stations' => array(10), 'import_logbooks' => array(50), 'import_diary' => '1');
	// A write conflict must roll back stations, MyISAM QSO/image rows and newly created files.
	$data['schema_version'] = '1.1';
	$data['attachments'] = $manifest;
	$data['logbook_relationships'] = array(array('station_logbook_id' => 50, 'station_location_id' => 10));
	refill_archive($archive_path, $payloads);
	$sstv = $test_db->where('qsoid', 100)->where('filename', 'test.png')->get('sstv_images')->row_array();
	$test_db->insert('TABLE_HRD_CONTACTS_V01', array('station_id' => 10, 'COL_CALL' => 'PROBE', 'COL_TIME_ON' => '2026-03-03 00:00:00'));
	$probe_id = (int)$test_db->insert_id();
	$test_db->where('COL_PRIMARY_KEY', $probe_id)->delete('TABLE_HRD_CONTACTS_V01');
	$conflicting = FCPATH.'assets/sstvimages/backup_'.($probe_id + 1).'_'.(int)$sstv['id'].'_'.hash('sha256', $image).'.png';
	file_put_contents($conflicting, 'existing file must survive');
	file_put_contents($json_path, json_encode($data));
	$qsl_rows_before_failure = $test_db->count_all('qsl_images');
	$qso_rows_before_failure = $test_db->count_all('TABLE_HRD_CONTACTS_V01');
	$qsl_files_before_failure = glob(FCPATH.'assets/qslcard/backup_*');
	if ($qsl_files_before_failure === false) $qsl_files_before_failure = array();
	$controller->db->db_debug = true;
	$controller->session->set_userdata('user_id', 11);
	$controller->session->set_userdata('import_backup_file', $json_path);
	$controller->session->set_userdata('import_backup_zip', $archive_path);
	ob_start(); $controller->user_do_import(); $failure = ob_get_clean();
	check($controller->db->db_debug === true, 'Database debug setting was not restored');
	$controller->db->db_debug = false;
	check(strpos($failure, 'Import failed') !== false, 'Write conflict was not reported');
	check($test_db->where('user_id', 11)->count_all_results('station_profile') === 0, 'Failed import left partial database records');
	check($test_db->count_all('TABLE_HRD_CONTACTS_V01') === $qso_rows_before_failure, 'Failed import left MyISAM QSO rows');
	check($test_db->count_all('qsl_images') === $qsl_rows_before_failure, 'Failed import left MyISAM image rows');
	check(file_get_contents($conflicting) === 'existing file must survive', 'Existing file overwritten');
	$qsl_files_after_failure = glob(FCPATH.'assets/qslcard/backup_*');
	if ($qsl_files_after_failure === false) $qsl_files_after_failure = array();
	check(count($qsl_files_after_failure) === count($qsl_files_before_failure), 'Failed import left restored QSL files');
	$zip->open($archive_path);
	$bad = $manifest; $bad['files'][0]['type'] = array('qsl');
	rejects(function() use ($files, $zip, $bad) { $files->validate($zip, $bad); }, 'Non-string type accepted');
	$zip->close();
	$zip->open($archive_path, ZipArchive::OVERWRITE);
	foreach ($manifest['files'] as $record) $zip->addFromString($record['entry'], 'corrupted image');
	$zip->close(); $zip->open($archive_path);
	rejects(function() use ($files, $zip, $manifest) { $files->validate($zip, $manifest); }, 'Corrupted image accepted');
	$zip->close();
	echo "PASS: image export ownership, BMP and standard formats, missing-file notices, ZIP validation, selected imports, controller restore, ID remapping, diary privacy, shortcodes, repeated imports and legacy JSON.\n";
	echo "PASS: corrupt archives and malformed manifests rejected; failed restore removes MyISAM rows and new files without overwriting existing files.\n";
} finally {
	$test_db->close();
	cleanup_backup_test();
}
