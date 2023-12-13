<?php

class MigrationVersion extends CI_Model {

	function getMigrationVersion() {
        $this->db->select_max('version');
        $query = $this->db->get('migrations');
        $migration_version = $query->row();

        if ($query->num_rows() == 1) {
            $migration_version = $query->row()->version;
            return $migration_version;
        } else {
            return null;
        }
    }

}

?>
