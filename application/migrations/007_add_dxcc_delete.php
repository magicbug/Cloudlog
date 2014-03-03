<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dxcc_delete extends CI_Migration {

	public function up()
	{
		
		$fields = array(
			'deleted' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			)
		);
		
		$this->dbforge->add_column('dxcc', $fields);
		
	}
      

	public function down()
	{
		$this->dbforge->drop_column('dxcc', 'deleted');
	}
}
?>