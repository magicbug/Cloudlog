<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dxcc_enddate extends CI_Migration {

	public function up()
	{
		
		$fields = array(
			'end_date' => array('type' => 'datetime')
		);
		
		$this->dbforge->add_column('dxcc', $fields);
		
	}
      

	public function down()
	{
		$this->dbforge->drop_column('dxcc', 'end_date');
	}
}
?>