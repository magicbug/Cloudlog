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


    function getSstvForQsoId($id)
    {
        // Clean ID
        $clean_id = $this->security->xss_clean($id);

        // be sure that QSO belongs to user
        $CI = &get_instance();
        $CI->load->model('logbook_model');
        if (!$CI->logbook_model->check_qso_is_accessible($clean_id)) {
            return;
        }

        $this->db->select('*');
        $this->db->from('sstv_images');
        $this->db->where('qsoid', $clean_id);

        return $this->db->get()->result();
    }

    function getQsoWithSstvImageList()
    {
        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (is_array($logbooks_locations_array) && !empty($logbooks_locations_array)) {
            $this->db->select('*');
            $this->db->from($this->config->item('table_name'));
            $this->db->join('sstv_images', 'sstv_images.qsoid = ' . $this->config->item('table_name') . '.col_primary_key');
            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->order_by("id", "desc");

            return $this->db->get();
        } else {
            return false;
        }
    }

}
