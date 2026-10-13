<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Migration_update_contests_adif318 extends CI_Migration
{
	private $contests = array(
		array('name' => 'SKCC Straight Key Sprint', 'adifname' => 'SKCC-SKS', 'active' => 1),
		array('name' => 'SKCC Straight Key Sprint Asia', 'adifname' => 'SKCC-SKSA', 'active' => 1),
		array('name' => 'SKCC Straight Key Sprint Europe', 'adifname' => 'SKCC-SKSE', 'active' => 1),
		array('name' => 'SKCC Weekend Sprintathon', 'adifname' => 'SKCC-WES', 'active' => 1),
	);

	public function up()
	{
		// Add new contests from ADIF 3.1.8 specification
		foreach ($this->contests as $contest) {
			$query = $this->db->get_where('contest', array('adifname' => $contest['adifname']));
			if ($query->num_rows() == 0) {
				$this->db->insert('contest', $contest);
			}
		}
	}

	public function down()
	{
		$this->db->where_in('adifname', array_column($this->contests, 'adifname'));
		$this->db->delete('contest');
	}
}
