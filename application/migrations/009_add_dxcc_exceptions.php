<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dxcc_exceptions extends CI_Migration {

  public function up(){
    $this->dbforge->add_field(array(
      'record' => array(
        'type' => 'int',
        'Null' => FALSE
      ),
      'call' => array(
        'type' => 'varchar(10)',
        'null' => TRUE
      ),
      'entity' => array(
        'type' => 'varchar(255)',
        'null' => FALSE
      ),
      'adif' => array(
        'type' => 'smallint',
        'null' => FALSE
      ),
      'cqz' => array(
        'type' => 'smallint',
        'null' => FALSE
      ),
      'cont' => array(
        'type' => 'varchar(5)',
      ),
      'long' => array(
        'type' => 'float',
      ),
      'lat' => array(
        'type' => 'float',
      ),
      'start' => array(
        'type' => 'date',
        'null' => TRUE
      ),
      'end' => array(
        'type' => 'date',
        'null' => TRUE
      )
    ));

    $this->dbforge->add_key('record', TRUE);
    $this->dbforge->create_table('dxcc_exceptions');
  }

  public function down(){
    $this->dbforge->drop_table('dxcc_exceptions');
  }
}
