<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_config_table extends CI_Migration {

        public function up()
        {
				$this->dbforge->add_field('id');
				
                $this->dbforge->add_field(array(
                        'lotw_download_url' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                        ),
                        'lotw_upload_url' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                        ),
                       'lotw_rcvd_mark' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 1,
                        ),
                ));
				
				
                $this->dbforge->create_table('config');
				
				$data = array(
   					'lotw_download_url' => 'https://p1k.arrl.org/lotwuser/lotwreport.adi',
   					'lotw_upload_url' => 'https://p1k.arrl.org/lotwuser/upload',
   					'lotw_rcvd_mark' => 'Y'
				);

				$this->db->insert('config', $data); 
        }

        public function down()
        {
                $this->dbforge->drop_table('config');
        }
}
?>