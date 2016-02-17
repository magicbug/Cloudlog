<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_dxcc_entities extends CI_Migration {

  public function up(){
    $this->dbforge->add_field(array(
      'adif' => array(
        'type' => 'smallint',
        'Null' => FALSE
      ),
      'name' => array(
        'type' => 'varchar(150)',
        'null' => TRUE
      ),
      'prefix' => array(
        'type' => 'varchar(10)',
        'null' => FALSE
      ),
      'cqz' => array(
        'type' => 'smallint',
        'null' => FALSE
      ),
      'ituz' => array(
        'type' => 'smallint',
        'null' => FALSE
      ),
      'cont' => array(
        'type' => 'varchar(5)',
        'null' => FALSE
      ),
      'long' => array(
        'type' => 'float',
        'null' => FALSE
      ),
      'lat' => array(
        'type' => 'float',
        'null' => FALSE
      ),
      'end' => array(
        'type' => 'date',
        'null' => TRUE
      )
    ));
    
    $this->dbforge->add_key('adif', TRUE);
    $this->dbforge->create_table('dxcc_entities');
  }

  public function down(){
    $this->dbforge->drop_table('dxcc_entities');
  }
}
