<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_add_wwff_columns
 *
 * Add wWFF columnds to database
 * 
 */

class Migration_add_wwff_columns extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('COL_WWFF_REF', 'TABLE_HRD_CONTACTS_V01')) {
                        $fields = array(
                                'COL_WWFF_REF VARCHAR(30) DEFAULT NULL',
                        );
                        $this->dbforge->add_column('TABLE_HRD_CONTACTS_V01', $fields, 'COL_VUCC_GRIDS');
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('TABLE_HRD_CONTACTS_V01', 'COL_WWFF_REF');
        }
}
