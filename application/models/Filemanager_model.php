<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filemanager_model extends CI_Model
{
	public function get_qsl_default_id()
	{
		$CI =& get_instance();
		$CI->load->library('OptionsLib');
		$default_id = $CI->optionslib->get_option("default_file_manager_id_for_qsl");
		return $default_id;
	}

	public function set_qsl_default_id($id)
	{
		$CI =& get_instance();
		$CI->load->model('Options_model');
		$CI->Options_model->update("default_file_manager_id_for_qsl", $id);
	}

	public function get_all()
	{
		$this->db->select("*");
		$this->db->from("file_manager");

		return $this->db->get()->result_array();
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

	public function update($raw)
	{
		$this->db->where("id", $raw["id"]);
		$this->db->update("file_manager", $raw);
	}

	public function delete($id)
	{
		$clean_id = $this->security->xss_clean($id);
		if ($this->get_qsl_default_id() == $clean_id)
		{
			throw new Exception("File manager in use as default qsl file manager");
		}
		$this->db->delete('file_manager', array('id' => $clean_id));
	}
}
