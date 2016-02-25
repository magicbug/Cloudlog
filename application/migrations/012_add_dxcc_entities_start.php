<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dxcc_entities_start extends CI_Migration {

  public function up(){
    $fields = (array(
      'start' => array(
        'type' => 'date',
        'Null' => TRUE
      ),
    ));

    $this->dbforge->add_column('dxcc_entities', $fields);
  }

  public function down(){
    $this->dbforge->drop_table('dxcc_entities');
  }
}
