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

    function getSSTVFilename($id)
    {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        // be sure that QSO belongs to user
        $CI = &get_instance();
        $CI->load->model('logbook_model');
        $this->db->select('qsoid');
        $this->db->from('sstv_images');
        $this->db->where('id', $clean_id);
        $qsoid = $this->db->get()->row()->qsoid;
        if (!$CI->logbook_model->check_qso_is_accessible($qsoid)) {
            return;
        }

        $this->db->select('filename');
        $this->db->from('sstv_images');
        $this->db->where('id', $clean_id);

        return $this->db->get();
    }

    
    function deleteSstv($id)
    {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        // be sure that QSO belongs to user
        $CI = &get_instance();
        $CI->load->model('logbook_model');
        $this->db->select('qsoid');
        $this->db->from('sstv_images');
        $this->db->where('id', $clean_id);
        $qsoid = $this->db->get()->row()->qsoid;
        if (!$CI->logbook_model->check_qso_is_accessible($qsoid)) {
            return;
        }

        // Delete Mode
        $this->db->delete('sstv_images', array('id' => $clean_id));
    }

}
