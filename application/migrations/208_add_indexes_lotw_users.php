<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_add_indexes_lotw_users extends CI_Migration
{
    public function up()
    {
        if ($this->db->table_exists('lotw_users')) {
            // add an index on callsign if no index exists on that column
            $callsignIndex = $this->db->query("SHOW INDEX FROM lotw_users WHERE Column_name = 'callsign'");
            if ($callsignIndex->num_rows() == 0) {
                $this->db->query("ALTER TABLE lotw_users ADD INDEX `callsign` (`callsign`)");
            }

            // add an index on lastupload if it doesn't exist
            $lastuploadIndex = $this->db->query("SHOW INDEX FROM lotw_users WHERE Column_name = 'lastupload'");
            if ($lastuploadIndex->num_rows() == 0) {
                $this->db->query("ALTER TABLE lotw_users ADD INDEX `lastupload` (`lastupload`)");
            }
        }
    }

    public function down()
    {
        if ($this->db->table_exists('lotw_users')) {
            // drop the indexes we might have created (only if the index name matches)
            $li = $this->db->query("SHOW INDEX FROM lotw_users WHERE Key_name = 'lastupload'");
            if ($li->num_rows() > 0) {
                $this->db->query("ALTER TABLE lotw_users DROP INDEX `lastupload`");
            }

            $ci = $this->db->query("SHOW INDEX FROM lotw_users WHERE Key_name = 'callsign'");
            if ($ci->num_rows() > 0) {
                $this->db->query("ALTER TABLE lotw_users DROP INDEX `callsign`");
            }
        }
    }
}
