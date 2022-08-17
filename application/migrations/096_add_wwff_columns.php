<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_add_wwff_columns
 *
 * Add WWFF columnds to database to reflect latest ADIF v3.1.3 spec changes
 * See http://www.adif.org.uk/313/ADIF_313_annotated.htm
 * 
 */

class Migration_add_wwff_columns extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('COL_WWFF_REF', 'TABLE_HRD_CONTACTS_V01')) {
                        $fields = array(
                                'COL_WWFF_REF VARCHAR(30) DEFAULT NULL',
                                'COL_MY_WWFF_REF VARCHAR(50) DEFAULT NULL',
                        );
                        $this->dbforge->add_column('TABLE_HRD_CONTACTS_V01', $fields, 'COL_VUCC_GRIDS');

                        // Now copy over data from SIG_INFO fields and remove COL_SIG and COL_SIG_INFO only if COL_SIG is WWFF
                        // This cannot be reverted on downgrade to prevent overwriting of other COL_SIG information
                        $this->db->set('COL_WWFF_REF', 'COL_SIG_INFO', FALSE);
                        $this->db->set('COL_SIG_INFO', '');
                        $this->db->set('COL_SIG', '');
                        $this->db->where('COL_SIG', 'WWFF');
                        $this->db->update('TABLE_HRD_CONTACTS_V01');

                        // Add MY_WWFF_REF to station profile
                        $fields = array(
                                'station_wwff varchar(50) DEFAULT NULL',
                        );
                        $this->dbforge->add_column('station_profile', $fields);
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('TABLE_HRD_CONTACTS_V01', 'COL_WWFF_REF');
                $this->dbforge->drop_column('TABLE_HRD_CONTACTS_V01', 'COL_MY_WWFF_REF');
                $this->dbforge->drop_column('station_profile', 'station_wwff');
        }
}
