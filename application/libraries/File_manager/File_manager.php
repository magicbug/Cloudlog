<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function driver_cfg_mapper($raw): array
{
	$ret = array();
	$ret["id"] = $raw["id"];
	$ret["driver"] = $raw["driver"];
	$ret["name"] = $raw["name"];
	$ret["url_prefix"] = $raw["url_prefix"];
	switch ($raw["driver"])
	{
		case "aws_s3":
			$ret["access_key_id"] = $raw["cfg_1"];
			$ret["access_key_secret"] = $raw["cfg_2"];
			$ret["region"] = $raw["cfg_3"];
			$ret["bucket_name"] = $raw["cfg_4"];
			$ret["hostname"] = $raw["cfg_5"];
			break;
		case "local":
			$ret["dir_path"] = $raw["cfg_1"];
			break;
		default:
			throw new InvalidArgumentException("unknown file manager type");
	}
	return $ret;
}

function raw_data_mapper($cfg): array
{
	$ret = array();
	if (array_key_exists("id", $cfg)) {
		$ret["id"] = $cfg["id"];
	}
	$ret["driver"] = $cfg["driver"];
	$ret["name"] = $cfg["name"];
	$ret["url_prefix"] = $cfg["url_prefix"];
	switch ($cfg["driver"])
	{
		case "aws_s3":
			if (array_key_exists("access_key_id", $cfg)) {
				$ret["cfg_1"] = $cfg["access_key_id"];
			}
			if (array_key_exists("access_key_secret", $cfg)) {
				$ret["cfg_2"] = $cfg["access_key_secret"];
			}
			$ret["cfg_3"] = $cfg["region"];
			if (array_key_exists("bucket_name", $cfg)) {
				$ret["cfg_4"] = $cfg["bucket_name"];
			} else {
				$ret["cfg_4"] = "";
			}
			$ret["cfg_5"] = $cfg["hostname"];
			break;
		case "local":
			$ret["cfg_1"] = $cfg["dir_path"];
			break;
		default:
			throw new InvalidArgumentException("unknown file manager type");
	}
	return $ret;
}

class File_manager extends CI_Driver_Library
{
	protected $valid_drivers = array(
		'aws_s3',
		'local'
	);

	protected $CI = null;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('Filemanager_model');
	}

	// TODO: add default file manager engine to avoid duplicate sql queries in several situation
	public function get_manager($id)
	{
		$cfg = driver_cfg_mapper($this->CI->Filemanager_model->get($id));
		return $cfg;
	}

	public function upload_file_from_field($file_field, $filename, $manager): array
	{
		$cfg = $this->get_manager($manager);

		$ret = $this->{$cfg["driver"]}->upload_file_from_field($file_field, $filename, $cfg);
		$this->CI->load->model("File_model");
		$ret["file_id"] = $this->CI->File_model->add($cfg["id"], $ret["filename"], $ret["url"]);
		return $ret;
	}

	public function delete_file($file_id)
	{
		$this->CI->load->model("File_model");
		$file = $this->CI->File_model->get($file_id);
		$cfg = driver_cfg_mapper($this->CI->Filemanager_model->get($file->manager_id));
		$this->{$cfg["driver"]}->delete_file($file->filename, $cfg);
		$this->CI->File_model->delete($file_id);
	}

	public function is_support_get_size($manager): bool
	{
		$cfg = $this->get_manager($manager);
		return $this->{$cfg["driver"]}->is_support_get_size($cfg);
	}

	public function get_size($manager): int
	{
		$cfg = $this->get_manager($manager);
		return $this->{$cfg["driver"]}->get_size($cfg);
	}

	public function test_configure($cfg): bool
	{
		return $this->{$cfg["driver"]}->test_configure($cfg);
	}

	public function create_manager($cfg)
	{
		return $this->CI->Filemanager_model->add(raw_data_mapper($cfg));
	}

	public function update_manager($cfg)
	{
		return $this->CI->Filemanager_model->update(raw_data_mapper($cfg));
	}
}
