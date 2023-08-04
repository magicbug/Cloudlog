<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_mastodon_url
 *
 * Creates a varchar column for the Mastodon-URL of the User
*/

class Migration_mastodon_url extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('user_mastodon_url', 'users')) {
                        $fields = array(
				'user_mastodon_url varchar(32) default NULL',
                        );
                        $this->dbforge->add_column('users', $fields, 'user_column5');
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('users', 'user_mastodon_url');
        }
}


 
