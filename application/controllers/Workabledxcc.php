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
		$dataResult = json_decode($json, true);

		if (empty($dataResult)) {
			$data['dxcclist'] = array();
			$this->load->view('/workabledxcc/components/dxcclist', $data);
			return;
		}

		// Get Date format
		if ($this->session->userdata('user_date_format')) {
			$custom_date_format = $this->session->userdata('user_date_format');
		} else {
			$custom_date_format = $this->config->item('qso_date_format');
		}

		// Load models once
		$this->load->model('logbook_model');
		$this->load->model('Workabledxcc_model');

		// Get all DXCC entities for all callsigns in one batch
		$callsigns = array_column($dataResult, 'callsign');
		$dates = array_column($dataResult, '0');
		$dxccEntities = $this->Workabledxcc_model->batchDxccLookup($callsigns, $dates);

		// Get worked/confirmed status for all entities in batch
		$uniqueEntities = array_unique(array_filter($dxccEntities));
		$dxccStatus = $this->Workabledxcc_model->batchDxccWorkedStatus($uniqueEntities);

		// Process results
		$requiredData = array();
		foreach ($dataResult as $index => $item) {
			$oldStartDate = DateTime::createFromFormat('Y-m-d', $item['0']);
			$StartDate = $oldStartDate->format($custom_date_format);

			$oldEndDate = DateTime::createFromFormat('Y-m-d', $item['1']);
			$EndDate = $oldEndDate->format($custom_date_format);

			// Get DXCC status for this callsign
			$entity = $dxccEntities[$index] ?? null;
			$worked = $entity && isset($dxccStatus[$entity]) ? $dxccStatus[$entity] : ['workedBefore' => false, 'confirmed' => false];

			$requiredData[] = array(
				'clean_date' => $item['0'],
				'start_date' => $StartDate,
				'end_date' => $EndDate,
				'country' => $item['2'],
				'notes' => $item['6'],
				'callsign' => $item['callsign'],
				'workedBefore' => $worked['workedBefore'],
				'confirmed' => $worked['confirmed'],
			);
		}

		$data['dxcclist'] = $requiredData;
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
