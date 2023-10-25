<?php

class Eqslmethods_model extends CI_Model {

	function mark_all_as_sent() {
		$data = array(
            'COL_EQSL_QSL_SENT' => 'Y',
            'COL_EQSL_QSLSDATE'  => date('Y-m-d')." 00:00:00",
        );

        $this->db->group_start();
		$this->db->where('COL_EQSL_QSL_SENT', 'N');
        $this->db->or_where('COL_EQSL_QSL_SENT', 'R');
        $this->db->or_where('COL_EQSL_QSL_SENT', 'Q');
        $this->db->or_where('COL_EQSL_QSL_SENT', null);
		$this->db->group_end();
       
        $this->db->update($this->config->item('table_name'), $data);
	}

    function get_eqsl_users() {
        $this->db->select('user_eqsl_name, user_eqsl_password, user_id');
        $this->db->where('coalesce(user_eqsl_name, "") != ""');
        $this->db->where('coalesce(user_eqsl_password, "") != ""');
        $query = $this->db->get($this->config->item('auth_table'));
        return $query->result();
    }

    /*
     * Gets all station location for user, for use in cron where we don't have any login
     */
    function get_all_user_locations($userid) {
		$this->db->select('station_profile.*, dxcc_entities.name as station_country, dxcc_entities.end as dxcc_end');
		$this->db->where('user_id', $userid);
		$this->db->join('dxcc_entities','station_profile.station_dxcc = dxcc_entities.adif','left outer');
		return $this->db->get('station_profile');
	}

    // Show all QSOs we need to send to eQSL
    function eqsl_not_yet_sent($userid = null) {
        $CI =& get_instance();
        if ($userid == null) {
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $stations = $this->get_all_user_locations($userid);
            $logbooks_locations_array = array();
            foreach ($stations->result() as $row) {
                array_push($logbooks_locations_array, $row->station_id);
            }
	    array_push($logbooks_locations_array, -9999);
        }

        $this->db->select('station_profile.*, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_COMMENT, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_PROP_MODE, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_SAT_MODE, '.$this->config->item('table_name').'.COL_QSLMSG');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->where("coalesce(station_profile.eqslqthnickname, '') <> ''");
        $this->db->where($this->config->item('table_name').'.COL_CALL !=', '');
        $this->db->group_start();
        $this->db->where($this->config->item('table_name').'.COL_EQSL_QSL_SENT is null');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', '');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'R');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'Q');
        $this->db->or_where($this->config->item('table_name').'.COL_EQSL_QSL_SENT', 'N');
        $this->db->group_end();
        $this->db->where_in('station_profile.station_id', $logbooks_locations_array);

        return $this->db->get();
    }

    // Show all QSOs whose eQSL card images we did not download yet
    function eqsl_not_yet_downloaded($userid = null) {
        $CI =& get_instance();
        if ($userid == null) {
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        } else {
            $stations = $this->get_all_user_locations($userid);
            $logbooks_locations_array = array();
            foreach ($stations->result() as $row) {
                array_push($logbooks_locations_array, $row->station_id);
            }
	array_push($logbooks_locations_array, -9999);
        }

        $this->db->select('station_profile.station_id, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_PROP_MODE, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_SAT_MODE, '.$this->config->item('table_name').'.COL_QSLMSG, eQSL_images.qso_id');
        $this->db->from('station_profile');
        $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->join('eQSL_images','eQSL_images.qso_id = '.$this->config->item('table_name').'.COL_PRIMARY_KEY','left outer');
        //$this->db->where("coalesce(station_profile.eqslqthnickname, '') <> ''");
        $this->db->where($this->config->item('table_name').'.COL_CALL !=', '');
        $this->db->where($this->config->item('table_name').'.COL_EQSL_QSL_RCVD', 'Y');
        $this->db->where('qso_id', NULL);
        $this->db->where_in('station_profile.station_id', $logbooks_locations_array);
        $this->db->order_by("COL_TIME_ON", "desc");

        return $this->db->get();
    }

    // Mark the QSO as sent to eQSL
    function eqsl_mark_sent($primarykey) {
        $data = array(
            'COL_EQSL_QSLSDATE' => date('Y-m-d H:i:s'), // eQSL doesn't give us a date, so let's use current
            'COL_EQSL_QSL_SENT' => 'Y',
        );

        $this->db->where('COL_PRIMARY_KEY', $primarykey);

        $this->db->update($this->config->item('table_name'), $data);

        return "eQSL Sent";
    }

    // Returns all the distinct callsign, eqsl nick pair for the current user/supplied user
	function all_of_user_with_eqsl_nick_defined($userid = null) {
        if ($userid == null) {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        } else {
            $this->db->where('user_id', $userid);
        }

		$this->db->where('eqslqthnickname IS NOT NULL');
		$this->db->where('eqslqthnickname !=', '');
		$this->db->from('station_profile');
		$this->db->select('station_callsign, eqslqthnickname');
		$this->db->distinct(TRUE);

		return $this->db->get();
	}

    // Get the last date we received an eQSL
    function eqsl_last_qsl_rcvd_date($callsign, $nickname) {
        $qso_table_name = $this->config->item('table_name');
        $this->db->from($qso_table_name);

        $this->db->join('station_profile',
            'station_profile.station_id = '.$qso_table_name.'.station_id AND station_profile.eqslqthnickname != ""');
        $this->db->where('station_profile.station_callsign', $callsign);
        $this->db->where('station_profile.eqslqthnickname', $nickname);

        $this->db->select("DATE_FORMAT(COL_EQSL_QSLRDATE,'%Y%m%d') AS COL_EQSL_QSLRDATE", FALSE);
        $this->db->where('COL_EQSL_QSLRDATE IS NOT NULL');
        $this->db->order_by("COL_EQSL_QSLRDATE", "desc");
        $this->db->limit(1);

        $query = $this->db->get();
        $row = $query->row();

        if (isset($row->COL_EQSL_QSLRDATE)){
            return $row->COL_EQSL_QSLRDATE;
        } else {
            // No previous date (first time import has run?), so choose UNIX EPOCH!
            // Note: date is yyyy/mm/dd format
            return '19700101';
        }
    }

    // Update a QSO with eQSL QSL info
    // We could also probably use this use this: https://eqsl.cc/qslcard/VerifyQSO.txt
    // https://www.eqsl.cc/qslcard/ImportADIF.txt
    function eqsl_update($datetime, $callsign, $band, $mode, $qsl_status,$station_callsign) {
        $data = array(
            'COL_EQSL_QSLRDATE' => date('Y-m-d H:i:s'), // eQSL doesn't give us a date, so let's use current
            'COL_EQSL_QSL_RCVD' => $qsl_status
        );

        $this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -15 MINUTE )');
        $this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 15 MINUTE )');
        $this->db->where('COL_CALL', $callsign);
	$this->db->where('COL_STATION_CALLSIGN', $station_callsign);
        $this->db->where('COL_BAND', $band);
        $this->db->where('COL_MODE', $mode);

        $this->db->update($this->config->item('table_name'), $data);

        return "Updated";
    }

    // Determine if we've already received an eQSL for this QSO
    function eqsl_dupe_check($datetime, $callsign, $band, $mode, $qsl_status,$station_callsign) {
        $this->db->select('COL_EQSL_QSLRDATE');
        $this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -15 MINUTE )');
        $this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 15 MINUTE )');
        $this->db->where('COL_CALL', $callsign);
        $this->db->where('COL_BAND', $band);
        $this->db->where('COL_MODE', $mode);
	$this->db->where('COL_STATION_CALLSIGN', $station_callsign);
        $this->db->where('COL_EQSL_QSL_RCVD', $qsl_status);
        $this->db->limit(1);
    
        $query = $this->db->get($this->config->item('table_name'));
        $row = $query->row();
    
        if ($row != null) {
            return true;
        } 
        return false;
    }

}

?>
