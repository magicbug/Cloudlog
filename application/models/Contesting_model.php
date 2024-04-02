<?php
class Contesting_model extends CI_Model {

    /*
     * This function gets the QSOs to fill the "Contest Logbook" under the contesting form.
     */
    function getSessionQsos($qso) {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $qsoarray = explode(',', $qso);

        $contestid = $qsoarray[2];
        $date = DateTime::createFromFormat('d-m-Y H:i:s', $qsoarray[0]);
        if ($date == false) $date = DateTime::createFromFormat('d-m-Y H:i', $qsoarray[0]);
        $date = $date->format('Y-m-d H:i:s');

        $sql = "SELECT date_format(col_time_on, '%d-%m-%Y %H:%i:%s') as col_time_on, col_call, col_band, col_mode,
       		col_submode, col_rst_sent, col_rst_rcvd, coalesce(col_srx, '') col_srx, coalesce(col_srx_string, '') col_srx_string,
       		coalesce(col_stx, '') col_stx, coalesce(col_stx_string, '') col_stx_string, coalesce(col_gridsquare, '') col_gridsquare,
       		coalesce(col_vucc_grids, '') col_vucc_grids FROM " .
            $this->config->item('table_name') .
            " WHERE station_id = " . $station_id .
            " AND COL_TIME_ON >= '" . $date . "'" .
            " AND COL_CONTEST_ID = '" . $contestid . "'" .
            " ORDER BY COL_PRIMARY_KEY ASC";

        $data = $this->db->query($sql);
        return $data->result();
    }

	function getSessionFreshQsos($contest_id) {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $contestid = $contest_id;

		// save contestid to debug
		// Get current date and time
		$now = new DateTime();
		$now->modify('-1 minute');
		$date = $now->format('Y-m-d H:i:s');

        $sql = "SELECT date_format(col_time_on, '%d-%m-%Y %H:%i:%s') as col_time_on, col_call, col_band, col_mode,
       		col_submode, col_rst_sent, col_rst_rcvd, coalesce(col_srx, '') col_srx, coalesce(col_srx_string, '') col_srx_string,
       		coalesce(col_stx, '') col_stx, coalesce(col_stx_string, '') col_stx_string, coalesce(col_gridsquare, '') col_gridsquare,
       		coalesce(col_vucc_grids, '') col_vucc_grids FROM " .
            $this->config->item('table_name') .
            " WHERE station_id = " . $station_id .
            " AND COL_TIME_ON >= '" . $date . "'" .
            " AND COL_CONTEST_ID = '" . $contestid . "'" .
            " ORDER BY COL_PRIMARY_KEY ASC";

        $data = $this->db->query($sql);

        return $data->result();
    }

	function getSession() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $sql = "SELECT * from contest_session where station_id = " . $station_id;

        $data = $this->db->query($sql);

        return $data->row();
    }



	function deleteSession() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

        $sql = "delete from contest_session where station_id = " . $station_id;

        $this->db->query($sql);
		return;
    }

	function setSession() {
        $CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

		$qso = "";

		if ($this->input->post('callsign')) {
			$qso = xss_clean($this->input->post('start_date', true)) . ' ' . xss_clean($this->input->post('start_time', true)) . ',' . xss_clean($this->input->post('callsign', true)) . ',' . xss_clean($this->input->post('contestname', true));
		}

		$data = array(
			'contestid' 			=> xss_clean($this->input->post('contestname', true)),
			'exchangetype' 			=> xss_clean($this->input->post('exchangetype', true)),
			'exchangesent' 			=> xss_clean($this->input->post('exch_sent', true)),
			'serialsent' 			=> xss_clean($this->input->post('exch_serial_s', true)),
			'copytodok'             => $this->input->post('copyexchangetodok', true) == "" ? 0 : xss_clean($this->input->post('copyexchangetodok', true)),
			'qso' 					=> $qso,
			'station_id' 			=> $station_id,
		);

        if ($this->input->post('copyexchangeto')) {
          $data['copytodok'] = xss_clean($this->input->post('copyexchangeto'));
        }

        $sql = "SELECT * from contest_session where station_id = " . $station_id;

        $querydata = $this->db->query($sql);

		if ($querydata->num_rows() == 0) {
			$this->db->insert('contest_session', $data);
			return;
		}

		$result = $querydata->row();

		if ($result->qso != "") {
			$data['qso'] = $result->qso;
		}

		$this->updateSession($data, $station_id);
		
		return;
    }

	function updateSession($data, $station_id) {
		$this->db->where('station_id', $station_id);

		$this->db->update('contest_session', $data);
    }

    function getActivecontests() {

		$sql = "SELECT name, adifname FROM contest WHERE active = 1 ORDER BY name ASC";

		$data = $this->db->query($sql);

		return($data->result_array());
	}

	function getAllContests() {

		$sql = "SELECT id, name, adifname, active FROM contest ORDER BY name ASC";

		$data = $this->db->query($sql);

		return($data->result_array());
	}

	function delete($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		// Delete Contest
		$this->db->delete('contest', array('id' => $clean_id));
	}

	function activate($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$data = array(
			'active' => '1',
		);

		$this->db->where('id', $clean_id);

		$this->db->update('contest', $data);

		return true;
	}

	function deactivate($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$data = array(
			'active' => '0',
		);

		$this->db->where('id', $clean_id);

		$this->db->update('contest', $data);

		return true;
	}

	function add() {
		$data = array(
			'name' => xss_clean($this->input->post('name', true)),
			'adifname' => xss_clean($this->input->post('adifname', true)),
		);

		$this->db->insert('contest', $data);
	}

	function contest($id) {
		// Clean ID
		$clean_id = $this->security->xss_clean($id);

		$sql = "SELECT id, name, adifname, active FROM contest where id =" . $clean_id;

		$data = $this->db->query($sql);

		return ($data->row());
	}

	function edit($id) {
		$data = array(
			'name' => xss_clean($this->input->post('name', true)),
			'adifname' => xss_clean($this->input->post('adifname', true)),
			'active' =>  xss_clean($this->input->post('active', true)),
		);

		$this->db->where('id', $id);
		$this->db->update('contest', $data);
	}

	function activateall() {
		$data = array(
			'active' => '1',
		);

		$this->db->update('contest', $data);

		return true;
	}

	function deactivateall() {
		$data = array(
			'active' => '0',
		);

		$this->db->update('contest', $data);

		return true;
	}

	function checkIfWorkedBefore($call, $band, $mode, $contest) {
		$CI =& get_instance();
		$CI->load->model('Stations');
		$station_id = $CI->Stations->find_active();

		$contest_session = $this->getSession();
		
		if ($contest_session && $contest_session->qso != "") {
			$qsoarray = explode(',', $contest_session->qso);
	
			$date = DateTime::createFromFormat('d-m-Y H:i:s', $qsoarray[0]);
			if ($date == false) $date = DateTime::createFromFormat('d-m-Y H:i', $qsoarray[0]);
			$date = $date->format('Y-m-d H:i:s');

			$this->db->select('timediff(UTC_TIMESTAMP(),col_time_off) b4, COL_TIME_OFF');
			$this->db->where('STATION_ID', $station_id);
			$this->db->where('COL_CALL', xss_clean($call));
			$this->db->where("COL_BAND", xss_clean($band));
			$this->db->where("COL_CONTEST_ID", xss_clean($contest));
			$this->db->where("COL_TIME_ON >=", $date);
			$this->db->group_start();
			$this->db->where("COL_MODE", xss_clean($mode));
			$this->db->or_where("COL_SUBMODE", xss_clean($mode));
			$this->db->group_end();
        		$this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "DESC");
			$query = $this->db->get($this->config->item('table_name'));
	
			return $query;
		}
		return;
	}

	function export_custom($from, $to, $contest_id, $station_id) {
        $this->db->select(''.$this->config->item('table_name').'.*, station_profile.*');
        $this->db->from($this->config->item('table_name'));
        $this->db->where($this->config->item('table_name').'.station_id', $station_id);

        // If date is set, we format the date and add it to the where-statement
        if ($from != 0) {
            $from = DateTime::createFromFormat('Y-m-d', $from);
            $from = $from->format('Y-m-d');
            $this->db->where("date(".$this->config->item('table_name').".COL_TIME_ON) >= '".$from."'");
        }
        if ($to != 0) {
            $to = DateTime::createFromFormat('Y-m-d', $to);
            $to = $to->format('Y-m-d');
            $this->db->where("date(".$this->config->item('table_name').".COL_TIME_ON) <= '".$to."'");
        }

		$this->db->where($this->config->item('table_name').'.COL_CONTEST_ID', $contest_id);

        $this->db->order_by($this->config->item('table_name').".COL_TIME_ON", "ASC");

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');

        return $this->db->get();
    }

	function get_logged_contests2() {
		$CI =& get_instance();
        $CI->load->model('Stations');
        $station_id = $CI->Stations->find_active();

		$sql = "select col_contest_id, min(date(col_time_on)) mindate, max(date(col_time_on)) maxdate, year(col_time_on) year, month(col_time_on) month
		from " . $this->config->item('table_name') . " 
		where coalesce(COL_CONTEST_ID, '') <> '' 
		and station_id =" . $station_id;

		$sql .= " group by COL_CONTEST_ID , year(col_time_on), month(col_time_on) order by year(col_time_on) desc";

		$data = $this->db->query($sql);

		return ($data->result());
	}

	function get_logged_years($station_id) {

		$sql = "select distinct year(col_time_on) year
		from " . $this->config->item('table_name') . " 
		where coalesce(COL_CONTEST_ID, '') <> '' 
		and station_id =" . $station_id;

		$sql .= " order by year(col_time_on) desc";

		$data = $this->db->query($sql);

		return $data->result();
	}

	function get_logged_contests($station_id, $year) {
		$sql = "select distinct col_contest_id
		from " . $this->config->item('table_name') . " 
		where coalesce(COL_CONTEST_ID, '') <> '' 
		and station_id =" . $station_id .
		" and year(col_time_on) ='" . $year . "'";

		$sql .= " order by COL_CONTEST_ID asc";

		$data = $this->db->query($sql);

        return $data->result();
    }

	function get_contest_dates($station_id, $year, $contestid) {
		$sql = "select distinct (date(col_time_on)) date
		from " . $this->config->item('table_name') . " 
		where coalesce(COL_CONTEST_ID, '') <> '' 
		and station_id =" . $station_id .
		" and year(col_time_on) ='" . $year . "' and col_contest_id ='" . $contestid . "'";

		$data = $this->db->query($sql);

        return $data->result();
	}
}
