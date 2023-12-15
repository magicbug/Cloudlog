<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_award_submitted_typo
 *
 * Fix typo in COL_AWARD_SUMMITED column
 */

class Migration_award_submitted_typo extends CI_Migration {

        public function up()
        {
                $this->db->query(
                        'ALTER TABLE ' .
                        $this->db->escape_identifiers($this->config->item('table_name')) .
                        ' RENAME COLUMN COL_AWARD_SUMMITED TO COL_AWARD_SUBMITTED'
                );
        }

        public function down()
        {
                $this->db->query(
                        'ALTER TABLE ' .
                        $this->db->escape_identifiers($this->config->item('table_name')) .
                        ' RENAME COLUMN COL_AWARD_SUBMITTED TO COL_AWARD_SUMMITED'
                );
        }
}
