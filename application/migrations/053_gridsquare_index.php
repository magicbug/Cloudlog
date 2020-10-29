<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_gridsquare_index extends CI_Migration {

    public function up()
    {
        $sql = "CREATE INDEX HRD_IDX_COL_GRIDSQUARE USING BTREE ON " . $this->config->item('table_name') . " (COL_GRIDSQUARE)";
        $this->db->query($sql);

        $sql = "CREATE INDEX HRD_IDX_COL_VUCC_GRIDS USING BTREE ON " . $this->config->item('table_name') ." (COL_VUCC_GRIDS)";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "ALTER TABLE " . $this->config->item('table_name') . " DROP INDEX HRD_IDX_COL_GRIDSQUARE";
        $this->db->query($sql);

        $sql = "ALTER TABLE " . $this->config->item('table_name') . " DROP INDEX HRD_IDX_COL_VUCC_GRIDS";
        $this->db->query($sql);
    }
}