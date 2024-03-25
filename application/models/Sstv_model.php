<?php
class Sstv_model extends CI_Model
{

    function saveSstvImages($qsoid, $filename)
    {
        // Clean ID
        $clean_id = $this->security->xss_clean($qsoid);

        // be sure that QSO belongs to user
        $CI = &get_instance();
        $CI->load->model('logbook_model');
        if (!$CI->logbook_model->check_qso_is_accessible($clean_id)) {
            return;
        }

        $data = array(
            'qsoid' => $clean_id,
            'filename' => $filename
        );

        $this->db->insert('sstv_images', $data);

        return $this->db->insert_id();
    }
}
