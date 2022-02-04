<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends CI_Model
{
	public function get($id)
	{
		$this->db->select("*");
		$this->db->from("files");
		$this->db->where("id", $id);

		return $this->db->get()->result()[0];
	}

	public function add(int $manager_id, string $filename, string $url)
	{
		$data = array(
			"manager_id" => $manager_id,
			"filename" => $filename,
			"url" => $url,
		);
		$this->db->insert('files', $data);
		return $this->db->insert_id();
	}

	public function delete(int $file_id)
	{
		$this->db->delete('files', array('id' => $file_id));
	}
}

