<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Controller to interact with the Cloudlog DXPed Aggregator
*/

class Workabledxcc extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if (!$this->user_model->authorize(2)) {
			$this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
			redirect('dashboard');
		}
	}

	public function index()
	{
		// Load public view
		$data['page_title'] = "Upcoming DXPeditions";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('/workabledxcc/index');
		$this->load->view('interface_assets/footer');
	}

	public function dxcclist()
	{

		$json = file_get_contents($this->optionslib->get_option('dxped_url'));

		// Decode the JSON data into a PHP array
		$dataResult = json_decode($json, true);

		// Initialize an empty array to store the required data
		$requiredData = array();

		// Get Date format
		if ($this->session->userdata('user_date_format')) {
			// If Logged in and session exists
			$custom_date_format = $this->session->userdata('user_date_format');
		} else {
			// Get Default date format from /config/cloudlog.php
			$custom_date_format = $this->config->item('qso_date_format');
		}

		// Iterate through the decoded JSON data
		foreach ($dataResult as $item) {
			// Create a new array with the required fields and add it to the main array
			$oldStartDate = DateTime::createFromFormat('Y-m-d', $item['0']);

			$StartDate = $oldStartDate->format($custom_date_format);

			$oldEndDate = DateTime::createFromFormat('Y-m-d', $item['1']);

			$EndDate = $oldEndDate->format($custom_date_format);

			$oldStartDate1 = DateTime::createFromFormat('Y-m-d', $item['0']);

			$StartDate1 = $oldStartDate1->format('Y-m-d');


			$this->load->model('logbook_model');
			$dxccInfo = $this->logbook_model->dxcc_lookup($item['callsign'], $StartDate1);

			// Call DXCC Worked function to check if the DXCC has been worked before
			if (isset($dxccInfo['entity'])) {
				$dxccWorked = $this->dxccWorked($dxccInfo['entity']);
			} else {
				// Handle the case where 'entity' is not set in $dxccInfo
				$dxccWorked = array(
					'workedBefore' => false,
					'confirmed' => false,
				);
			}

			$requiredData[] = array(
				'clean_date' => $item['0'],
				'start_date' => $StartDate,
				'end_date' => $EndDate,
				'country' => $item['2'],
				'notes' => $item['6'],
				'callsign' => $item['callsign'],
				'workedBefore' => $dxccWorked['workedBefore'],
				'confirmed' => $dxccWorked['confirmed'],
			);
		}

		$data['dxcclist'] = $requiredData;

		// Return the array with the required data

		$this->load->view('/workabledxcc/components/dxcclist', $data);
	}

	function dxccWorked($country)
	{

		$return = [
			"workedBefore" => false,
			"confirmed" => false,
		];

		$user_default_confirmation = $this->session->userdata('user_default_confirmation');
		$this->load->model('logbooks_model');
		$logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
		$this->load->model('logbook_model');

		if (!empty($logbooks_locations_array)) {
			$this->db->where('COL_PROP_MODE !=', 'SAT');

			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->where('COL_COUNTRY', urldecode($country));

			$query = $this->db->get($this->config->item('table_name'), 1, 0);
			foreach ($query->result() as $workedBeforeRow) {
				$return['workedBefore'] = true;
			}

			$extrawhere = '';
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
				$extrawhere = "COL_QSL_RCVD='Y'";
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
				if ($extrawhere != '') {
					$extrawhere .= " OR";
				}
				$extrawhere .= " COL_LOTW_QSL_RCVD='Y'";
			}
			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
				if ($extrawhere != '') {
					$extrawhere .= " OR";
				}
				$extrawhere .= " COL_EQSL_QSL_RCVD='Y'";
			}

			if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Z') !== false) {
				if ($extrawhere != '') {
					$extrawhere .= " OR";
				}
				$extrawhere .= " COL_QRZCOM_QSO_DOWNLOAD_STATUS='Y'";
			}


			$this->load->model('logbook_model');
			$this->db->where('COL_PROP_MODE !=', 'SAT');
			if ($extrawhere != '') {
				$this->db->where('(' . $extrawhere . ')');
			} else {
				$this->db->where("1=0");
			}


			$this->db->where_in('station_id', $logbooks_locations_array);
			$this->db->where('COL_COUNTRY', urldecode($country));

			$query = $this->db->get($this->config->item('table_name'), 1, 0);
			foreach ($query->result() as $workedBeforeRow) {
				$return['confirmed'] = true;
			}

			return $return;
		} else {
			$return['workedBefore'] = false;
			$return['confirmed'] = false;


			return $return;;
		}
	}

}
