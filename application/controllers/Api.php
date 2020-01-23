<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends CI_Controller {

	/*
		TODOs
		- Search Callsign (Return Json)
		- Add QSO return json
	*/


	function search_callsign($callsign) {
		$this->db->select('COL_PRIMARY_KEY, COL_CALL, COL_MODE, COL_BAND, COL_COUNTRY, COL_FREQ, COL_GRIDSQUARE, COL_RST_RCVD, COL_RST_SENT, COL_SAT_MODE, COL_SAT_NAME');
		//$this->db->select("DATE_FORMAT(COL_TIME_ON, '%H:%i') AS time_on", FALSE );
		//$this->db->select("DATE_FORMAT(COL_TIME_ON, '%d/%c/%Y') AS date_on", FALSE );
		$this->db->like('COL_CALL', $callsign);
		$this->db->or_like('COL_GRIDSQUARE', $callsign);
		$query = $this->db->get($this->config->item('table_name'));


		$results = array();

		foreach ($query->result() as $result)
		{
			$results[] = $result;
		}

		header('Content-type: application/json');

		//$arr = array ('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
		echo $_GET['jsoncallback'].'('.json_encode($results).')'; //assign resulting code to $_GET['jsoncallback].

		//echo json_encode($results);

	}

	function index()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(1)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }


		$this->load->model('api_model');

		$data['api_keys'] = $this->api_model->keys();

		$data['page_title'] = "API Keys";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('settings/api_panel');
		$this->load->view('interface_assets/footer');
	}


	function edit($key) {
		$this->load->model('user_model');
		
		if(!$this->user_model->authorize(1)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		$this->load->model('api_model');

		$this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->form_validation->set_rules('api_desc', 'API Description', 'required');
        $this->form_validation->set_rules('api_key', 'API Key is required do not change this field', 'required');

        $data['api_info'] = $this->api_model->key_description($key);

        if ($this->form_validation->run() == FALSE)
        {
  	      	$data['page_title'] = "Edit API Description";

			$this->load->view('interface_assets/header', $data);
			$this->load->view('settings/api_description_panel');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			// Success!

			$this->api_model->update_key_description($this->input->post('api_key'), $this->input->post('api_desc'));

			$this->session->set_flashdata('notice', 'API Key <b>'.$this->input->post('api_key')."</b> description has been updated.");

			redirect('api');
		}

	}

	function generate($rights) {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(1)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }


		$this->load->model('api_model');

		$data['api_keys'] = $this->api_model->generate_key($rights);

		redirect('api');
	}

	function delete($key) {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(1)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }


		$this->load->model('api_model');

		$this->api_model->delete_key($key);

		$this->session->set_flashdata('notice', 'API Key <b>'.$key."</b> has been deleted");

		redirect('api');
	}

	// Example of authing
	function auth($key) {
		$this->load->model('api_model');
			header("Content-type: text/xml");
		if($this->api_model->access($key) == "No Key Found" || $this->api_model->access($key) == "Key Disabled") {
			echo "<auth>";
			echo "<message>Key Invalid - either not found or disabled</message>";
			echo "</auth>";
		} else {
			echo "<auth>";
			echo "<status>Valid</status>";
			echo "<rights>".$this->api_model->access($key)."</rights>";
			echo "</auth>";
		}
	}

	function validate()
	{
			// Load the API and Logbook models
			$this->load->model('api_model');
			$this->load->model('logbook_model');

			// Retrieve the arguments from the query string
			$arguments = $this->_retrieve();

			// Add some debugging information to the XML output
	    $data['data'] = $arguments;
			$data['data']['queryInfo']['call'] = "validate";
			$data['data']['queryInfo']['dbQuery'] = "";
			$data['data']['queryInfo']['numResults'] = 1;
			$data['data']['queryInfo']['executionTime'] = 0;

	    $data['data']['validate_Result']['results'] = array(0 => array('Result' => $this->api_model->authorize($arguments['key'])));

	    $this->load->view('api/index', $data);
	}

	function qso() {
		header('Content-type: application/json');

		$this->load->model('api_model');

		// Decode JSON and store
		$obj = json_decode(file_get_contents("php://input"), true);

		if(!isset($obj['key']) || $this->api_model->authorize($obj['key']) == 0) {
		   http_response_code(401);
		   echo json_encode(['status' => 'failed', 'reason' => "missing api key"]);
		   die();
		}

		if($obj['type'] == "adif" && $obj['string'] != "") {
			// Load the logbook model for adding QSO records
			$this->load->model('logbook_model');

			// Load ADIF Parser
			$this->load->library('adif_parser');

			// Feed in the ADIF string
			$this->adif_parser->feed($obj['string']);

			// Create QSO Record
			while($record = $this->adif_parser->get_record())
			{
				if(count($record) == 0)
				{
					break;
				};

				$this->logbook_model->import($record);

			};
			http_response_code(201);
			echo json_encode(['status' => 'created', 'type' => $obj['type'], 'string' => $obj['string']]);

		}

	}

	function country_worked($dxcc_num, $band, $mode = NULL) {
		$this->load->model('api_model');

		echo $this->api_model->country_worked($dxcc_num, $band, $mode);
	}

	function gridsquare_worked($gridsquare, $band, $mode = NULL) {
		$this->load->model('api_model');

		echo $this->api_model->gridsquare_worked($gridsquare, $band, $mode);
	}


	/* ENDPOINT for Rig Control */

	function radio() {
		header('Content-type: application/json');

		$this->load->model('api_model');

		//$json = '{"radio":"FT-950","frequency":14075,"mode":"SSB","timestamp":"2012/04/07 16:47"}';

		$this->load->model('cat');

		//var_dump(file_get_contents("php://input"), true);

		// Decode JSON and store
		$obj = json_decode(file_get_contents("php://input"), true);

		if(!isset($obj['key']) || $this->api_model->authorize($obj['key']) == 0) {
		   echo json_encode(['status' => 'failed', 'reason' => "missing api key"]);
		   die();
		}

		// Store Result to Database
		$this->cat->update($obj);

		// Return Message

		$arr = array('status' => 'success');

		echo json_encode($arr);

	}
}
