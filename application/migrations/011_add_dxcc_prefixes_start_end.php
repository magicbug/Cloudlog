<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dxcc_prefixes_start_end extends CI_Migration {

  public function up(){
    $fields = (array(
      'start' => array(
        'type' => 'date',
        'Null' => TRUE
      ),
      'end' => array(
        'type' => 'date',
        'Null' => TRUE
      ),
    ));

    $this->dbforge->add_column('dxcc_prefixes', $fields);
  }

  public function down(){
    $this->dbforge->drop_table('dxcc_prefixes');
  }
}
