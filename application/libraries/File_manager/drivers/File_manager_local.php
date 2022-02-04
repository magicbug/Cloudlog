<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ensure_dir_create(string $dir)
{
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
}

function folderSize($dir){
	$count_size = 0;
	$count = 0;
	$dir_array = scandir($dir);
	foreach($dir_array as $key=>$filename){
		if($filename!=".." && $filename!="."){
			if(is_dir($dir."/".$filename)){
				$new_foldersize = foldersize($dir."/".$filename);
				$count_size = $count_size+ $new_foldersize;
			}else if(is_file($dir."/".$filename)){
				$count_size = $count_size + filesize($dir."/".$filename);
				$count++;
			}
		}
	}
	return $count_size;
}

class File_manager_local extends CI_Driver
{
	/**
	 * @throws Exception
	 */
	public function upload_file_from_field($file_field, $filename, $cfg) : array
	{
		$CI =& get_instance();
		$dir = $cfg["dir_path"];
		ensure_dir_create($dir);
		$config['upload_path'] = $dir;
		$config['file_name'] = $filename;
		// TODO: make allowed_types configurable
		$config['allowed_types'] = 'gif|jpg|png';
		$CI->load->library('upload', $config);
		$res = $CI->upload->do_upload($file_field);

		if (!$res) {
			// Upload of QSL card Failed
			throw new Exception($CI->upload->display_errors());
		}
		$data = $CI->upload->data();

		return array(
			"filename" => $data["file_name"],
			"url" => $cfg["url_prefix"].$data["file_name"]
		);
	}

	public function delete_file($filename, $cfg)
	{
		// TODO: throw exception if unlink fail
		unlink($cfg["dir_path"].$filename);
	}

	public function is_support_get_size($cfg) : bool
	{
		return true;
	}

	public function get_size($cfg) : int
	{
		$dir = $cfg["dir_path"];
		ensure_dir_create($dir);
		return folderSize($dir);
	}

	public function test_configure($cfg): bool
	{
		return true;
	}
}
