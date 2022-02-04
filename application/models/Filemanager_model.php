<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filemanager_model extends CI_Model
{
	public function get_default()
	{
		$CI =& get_instance();
		$CI->load->library('OptionsLib');
		$default_id = $CI->optionslib->get_option("default_file_manager_id");
		return $this->get($default_id);
	}

	public function set_default($id)
	{
		$CI =& get_instance();
		$CI->load->model('Options_model');
		$CI->Options_model->update("default_file_manager_id", $id);
	}

	public function get($id)
	{
		$this->db->select("*");
		$this->db->from("file_manager");
		$this->db->where("id", $id);

		return $this->db->get()->result_array()[0];
	}

	public function add($raw)
	{
		$this->db->insert("file_manager", $raw);
		return $this->db->insert_id();
	}

	public function update($id, $raw)
	{
		$this->db->where("id", $id);
		$this->db->update("file_manager", $raw);
	}
}
