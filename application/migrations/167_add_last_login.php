<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Adding a column to users table for the timestamp of the last login

class Migration_add_last_login extends CI_Migration
{

  public function up()
  {
    if (!$this->db->field_exists('last_login_date', 'users')) {
      $fields = array(
        'last_login_date TIMESTAMP NULL DEFAULT NULL AFTER `reset_password_date`',
      );
      $this->dbforge->add_column('users', $fields);
    }
  }

  public function down()
  {
    $this->dbforge->drop_column('users', 'last_login_date');
  }
}
