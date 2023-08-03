<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_label_paper_types_table extends CI_Migration {

	public function up() {
		if (!$this->db->table_exists('paper_types')) {
			$this->dbforge->add_field(array(
				'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				),

				'user_id' => array(
					'type' => 'INT',
					'constraint' => 5,
				),

				'paper_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '250',
				),

				'metric' => array(
					'type' => 'VARCHAR',
					'constraint' => '10',
				),

				'width' => array(
					'type' => 'DECIMAL',
					'constraint' => '6,3',
					'null' => TRUE,
				),

				'orientation' => array(
					'type' => 'VARCHAR',
					'constraint' => '1',
					'null' => TRUE,
				),

				'height' => array(
					'type' => 'DECIMAL',
					'constraint' => '6,3',
					'null' => TRUE,
				),

				'last_modified' => array(
					'type' => 'timestamp',
					'null' => TRUE,
				),
			));

			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->add_key('user_id', TRUE);

			$this->dbforge->create_table('paper_types');
			$this->db->query("CREATE UNIQUE INDEX idx_paper_types_user_id_paper_name ON paper_types (user_id, paper_name) ALGORITHM DEFAULT LOCK DEFAULT;");
			$this->db->query("insert into paper_types (id,user_id,paper_name,metric,width,orientation,height) values ('1','-1','A4','mm','210.000','P','297.000');");
			$this->db->query("insert into paper_types (id,user_id,paper_name,metric,width,orientation,height) values ('2','-1','A5','mm','148.000','P','210.000');");
			$this->db->query("insert into paper_types (id,user_id,paper_name,metric,width,orientation,height) values ('3','-1','letter','mm','215.900','P','279.400');");
			$this->db->query("insert ignore paper_types (user_id,paper_name,metric,width,orientation,height) SELECT u.user_id, pt.paper_name, pt.metric, pt.width, pt.orientation,pt.height FROM paper_types pt inner join users u where pt.user_id = -1;");
		}

	}

	public function down(){
        if ($this->db->table_exists('paper_types')) {
		    $this->dbforge->drop_table('paper_types');
        }
	}
}
