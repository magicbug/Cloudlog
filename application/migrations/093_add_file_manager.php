<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_file_manager extends CI_Migration
{
	public function up()
	{
		$url_prefix = $this->config->item('base_url').'/assets/qslcard/';
		$this->db->query("CREATE TABLE if not exists files (id INT NOT NULL AUTO_INCREMENT, filename VARCHAR(255) NOT NULL, url VARCHAR(1024) NOT NULL, manager_id INT NOT NULL, PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;");
		$this->db->query("CREATE TABLE if not exists file_manager (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, driver VARCHAR(16) NOT NULL, url_prefix VARCHAR(255) NOT NULL, cfg_1 VARCHAR(255) NULL DEFAULT NULL, cfg_2 VARCHAR(255) NULL DEFAULT NULL, cfg_3 VARCHAR(255) NULL DEFAULT NULL, cfg_4 VARCHAR(255) NULL DEFAULT NULL, cfg_5 VARCHAR(255) NULL DEFAULT NULL, cfg_6 VARCHAR(255) NULL DEFAULT NULL, PRIMARY KEY (id)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;");
		$this->db->query("INSERT INTO file_manager (id, name, driver, url_prefix, cfg_1, cfg_2, cfg_3, cfg_4, cfg_5, cfg_6) VALUES (1, 'qslcard_local', 'local', ?, './assets/qslcard/', NULL, NULL, NULL, NULL, NULL);", array($url_prefix));
		$this->db->query("INSERT INTO `options` (option_id, option_name, option_value, autoload) VALUES (NULL, 'default_file_manager_id_for_qsl', '1', 'yes');");
		$this->db->query("ALTER TABLE qsl_images ENGINE = InnoDB;");
		$this->db->query("INSERT INTO files (id, filename, url, manager_id) SELECT id, filename, CONCAT(?, filename), 1 FROM qsl_images;", array($url_prefix));
		$this->db->query("ALTER TABLE qsl_images CHANGE filename file_id INT NULL DEFAULT NULL;");
		$this->db->query("UPDATE qsl_images SET file_id = id");
	}

	public function down()
	{
		echo "not possible";
	}
}
