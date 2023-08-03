<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_label_paper_types_table extends CI_Migration {

	public function up() {
		if (!$this->db->table_exists('paper_types')) {
			$this->dbforge->add_field(array(
				'paper_id' => array(
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

			$this->dbforge->add_key('paper_id', TRUE);
			$this->dbforge->add_key('user_id', TRUE);

			$this->dbforge->create_table('paper_types');
			$this->db->query("ALTER TABLE label_types ADD COLUMN paper_type_id INT(5) NOT NULL;");
			$this->db->query("CREATE UNIQUE INDEX idx_paper_types_user_id_paper_name ON paper_types (user_id, paper_name) ALGORITHM DEFAULT LOCK DEFAULT;");
			$this->db->query("insert into paper_types (paper_id,user_id,paper_name,metric,width,orientation,height) values ('1','-1','A4','mm','210.000','P','297.000');");
			$this->db->query("insert into paper_types (paper_id,user_id,paper_name,metric,width,orientation,height) values ('2','-1','A5','mm','148.000','P','210.000');");
			$this->db->query("insert into paper_types (paper_id,user_id,paper_name,metric,width,orientation,height) values ('3','-1','letter','mm','215.900','P','279.400');");
			$this->db->query("insert ignore paper_types (user_id,paper_name,metric,width,orientation,height) SELECT u.user_id, pt.paper_name, pt.metric, pt.width, pt.orientation,pt.height FROM paper_types pt inner join users u where pt.user_id = -1;");
			$this->db->query("update label_types l set l.paper_type_id=(select p.paper_id from paper_types p where upper(p.paper_name)=upper(l.paper_type) and p.user_id=l.user_id limit 1) where l.paper_type_id=0;");
			$this->db->query("update label_types l set l.paper_type_id = (select p.paper_id from paper_types p where p.user_id = l.user_id limit 1) where l.paper_type_id = 0;");
			$this->db->query("alter table label_types drop column paper_type;");
		}

	}

	public function down(){
        if ($this->db->table_exists('paper_types')) {
		    $this->dbforge->drop_table('paper_types');
        }

		if ($this->db->field_exists('paper_type_id', 'label_types')) {
			$this->dbforge->drop_column('label_types', 'paper_type_id');
		}

		if (!$this->db->field_exists('paper_type', 'label_types')) {
			$fields = array(
				'paper_type varchar(250)',
			);

			$this->dbforge->add_column('label_types', $fields);
			$this->db->query("update label_types set paper_type = 'a4';");
		}
	}
}
