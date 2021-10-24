<?php
class Contesting_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }

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
        header('Content-Type: application/json');
        echo json_encode($data->result());
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

	function checkIfWorkedBefore($call, $band, $mode, $contest, $qso) {
		$CI =& get_instance();
		$CI->load->model('Stations');
		$station_id = $CI->Stations->find_active();

		$qsoarray = explode(',', $qso);

        $date = DateTime::createFromFormat('d-m-Y H:i:s', $qsoarray[0]);
        $date = $date->format('Y-m-d H:i:s');

		$this->db->where('STATION_ID', $station_id);
		$this->db->where('COL_CALL', xss_clean($call));
	    $this->db->where("COL_BAND", xss_clean($band));
		$this->db->where("COL_CONTEST_ID", xss_clean($contest));
		$this->db->where("COL_TIME_ON >=", $date);
	    $this->db->group_start();
		$this->db->where("COL_MODE", xss_clean($mode));
	    $this->db->or_where("COL_SUBMODE", xss_clean($mode));
		$this->db->group_end();
	    $query = $this->db->get($this->config->item('table_name'));

	    return $query;
	}
}
