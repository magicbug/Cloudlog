<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_add_qso_dates_to_lotw_certs
 *
 * For validity checks of LotW we need to check qso dates from 
 * cvertificates rather than the cert issue date itself
 * 
 */

class Migration_add_qso_dates_to_lotw_certs extends CI_Migration {

        public function up()
        {
                if (!$this->db->field_exists('qso_start_date', 'lotw_certs')) {
                        $fields = array(
                                'qso_end_date DATETIME NULL DEFAULT NULL AFTER `date_expires`',
                                'qso_start_date DATETIME NULL DEFAULT NULL AFTER `date_expires`',
                        );
                        $this->dbforge->add_column('lotw_certs', $fields);
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('lotw_certs', 'qso_start_date');
                $this->dbforge->drop_column('lotw_certs', 'qso_end_date');
        }
}
