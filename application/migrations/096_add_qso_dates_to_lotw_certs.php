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

                // Extract QSO start and end date from x509 certs and insert into
                // newly created columns

                $query = $this->db->query("SELECT `lotw_cert_id`, `cert` FROM `lotw_certs` WHERE 1");
                foreach ($query->result() as $cert) {
                   $certdata = openssl_x509_parse($cert->cert,0);
                   $data = array(
                      'qso_start_date' => $certdata['extensions']['1.3.6.1.4.1.12348.1.2'],
                      'qso_end_date' => $certdata['extensions']['1.3.6.1.4.1.12348.1.3'],
                   );
                   $this->db->where('lotw_cert_id', $cert->lotw_cert_id);
                   $this->db->update('lotw_certs', $data);
                }
        }

        public function down()
        {
                $this->dbforge->drop_column('lotw_certs', 'qso_start_date');
                $this->dbforge->drop_column('lotw_certs', 'qso_end_date');
        }
}
