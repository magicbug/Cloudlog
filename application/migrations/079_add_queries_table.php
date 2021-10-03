<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_queries_table extends CI_Migration
{
	public function up()
	{
		$this->db->query("create table if not exists queries (id integer not null auto_increment, query text, description text, userid integer not null, primary key (id)) ENGINE=myisam DEFAULT CHARSET=utf8;");
	}

	public function down()
	{
		$this->db->query("");
	}
}
