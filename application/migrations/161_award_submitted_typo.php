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
                // if COL_AWARD_SUMMITED column exists, change it to COL_AWARD_SUBMITTED
                $fields = $this->db->field_data($this->config->item('table_name'));
                foreach ($fields as $field)
                {
                        if ($field->name == 'COL_AWARD_SUMMITED')
                        {
                                $this->db->query(
                                        'ALTER TABLE ' .
                                        $this->db->escape_identifiers($this->config->item('table_name')) .
                                        ' CHANGE COL_AWARD_SUMMITED COL_AWARD_SUBMITTED VARCHAR(255)'
                                );
                                return;
                        }
                }
        }

        public function down()
        {
                $this->db->query(
                        'ALTER TABLE ' .
                        $this->db->escape_identifiers($this->config->item('table_name')) .
                        ' CHANGE COL_AWARD_SUBMITTED COL_AWARD_SUMMITED VARCHAR(255)'
                );
        }
}
