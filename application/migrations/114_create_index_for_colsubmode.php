<?php

// Create migration that makes the submode column in the logbook table an index
class Migration_Create_index_for_colsubmode extends CI_Migration {

    public function up() {
        $this->db->query("ALTER TABLE `".$this->config->item('table_name')."` ADD INDEX (`COL_SUBMODE`)");
    }

    public function down() {
        $this->db->query("ALTER TABLE `".$this->config->item('table_name')."` DROP INDEX (`COL_SUBMODE`)");
    }
}

?>