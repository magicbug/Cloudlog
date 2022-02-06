<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API extends CI_Controller {

	// Do absolutely nothing
	function index()
	{
	}

	/*
		TODOs
		- Search Callsign (Return Json)
		- Add QSO return json
	*/


	function search_callsign($callsign) {
		$this->db->select('COL_PRIMARY_KEY, COL_CALL, COL_MODE, COL_SUBMODE, COL_BAND, COL_COUNTRY, COL_FREQ, COL_GRIDSQUARE, COL_RST_RCVD, COL_RST_SENT, COL_SAT_MODE, COL_SAT_NAME');
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

	function help()
	{
		$this->load->model('user_model');

		// Check if users logged in

		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}

		$this->load->model('api_model');

		$data['api_keys'] = $this->api_model->keys();

		$data['page_title'] = "API";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('api/help');
		$this->load->view('interface_assets/footer');
	}


	function edit($key) {
		$this->load->model('user_model');

		// Check if users logged in

		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}

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
			$this->load->view('api/description');
			$this->load->view('interface_assets/footer');
		}
		else
		{
			// Success!

			$this->api_model->update_key_description($this->input->post('api_key'), $this->input->post('api_desc'));

			$this->session->set_flashdata('notice', 'API Key <b>'.$this->input->post('api_key')."</b> description has been updated.");

			redirect('api/help');
		}

	}

	function generate($rights) {
		$this->load->model('user_model');

		// Check if users logged in

		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}


		$this->load->model('api_model');

		$data['api_keys'] = $this->api_model->generate_key($rights);

		redirect('api/help');
	}

	function delete($key) {
		$this->load->model('user_model');

		// Check if users logged in

		if($this->user_model->validate_session() == 0) {
			// user is not logged in
			redirect('user/login');
		}


		$this->load->model('api_model');

		$this->api_model->delete_key($key);

		$this->session->set_flashdata('notice', 'API Key <b>'.$key."</b> has been deleted");

		redirect('api/help');
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

	// FUNCTION: search()
	// Handle search requests
	/*
		Okay, so here's how it works in a nutshell...

		*******************************************************************
		Because this is effectively just a filter between the query string
		and a MySQL statement, if done wrong we're just asking for pain.

		DO NOT alter any of the filtering statements without fully
		understanding what you're doing. CodeIgniter provides some
		protection against unwanted characters in the query string, but
		this should in no way be relied upon for safety.
		*******************************************************************

		Example query:-
		.../search/query[Call~M0*(and)(Locator~I*(or)Locator~J*)]/limit[10]/fields[distinct(Call),Locator]/order[Call(asc)]

		There's four parts to this query, separated with forward slashes. It's effectively a heavily-sanitised
		MySQL query, hence the hideous search and replace code blocks below.

		FIELDS
		------
		Straightforward - input is sanitised and passed on - in the example, this ends up as "DISTINCT (Call),Locator",
		which is then the first argument to 'SELECT'

		QUERY
		-----
		This forms the 'WHERE' clause.

		* '(and)' and '(or)' are expanded out to ' AND ' and ' OR '
		* Parentheses are preserved
		* '~' is expanded out to ' LIKE '
		* '*' is translated to '%'
		* Values are encapsulated in quote marks

		So in the example, this translates to "WHERE Call LIKE 'M0%' AND (Locator LIKE 'I%' OR Locator LIKE 'J%')"

		ORDER
		-----
		Sanitised, so our example ends up as "ORDER BY Call ASC".

		LIMIT
		-----
		Straightforward - what's between the square brackets is passed as an argument to 'LIMIT'

		Finally, once this has been done, each field name is translated to the MySQL column name.
	*/
	function search()
	{
		// Load the API and Logbook models
		$this->load->model('api_model');
		$this->load->model('logbook_model');
		$this->load->model('user_model');

		$arguments = $this->_retrieve();
		print_r($arguments);
		return;

		if((!$this->user_model->authorize(3)) && ($this->api_model->authorize($arguments['key']) == 0)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard');
        }

		// Retrieve the arguments from the query string
        $data['data']['format'] = $arguments['format'];

		// Call the parser within the API model to build the query
		$query = $this->api_model->select_parse($arguments);

		// Execute the query, and retrieve the results
		$s = $this->logbook_model->api_search_query($query);
		$a = 0;

        // Print query results using original column names and exit
        if ($arguments['format'] == 'original'){
            $results = array();
            foreach($s['results']->result() as $row){
                //print_r($row);
                array_push($results,  $row);
            }

            print json_encode($results);
            return;
		}

        if(isset($s['results'])) {
            $results = $s['results'];

            // Cycle through the results, and translate between MySQL column names
            // and more friendly, descriptive names
            if($results->num_rows() != 0)
            {
                foreach ($results->result() as $row) {
                    $record = (array)$row;
                    $r[$a]['rid'] = $a;
                    while (list($key, $val) = each($record)) {
                        $r[$a][$this->api_model->name($key)] = $val;
                    }
                    $a++;
                }
                // Add the result record to the main results array
                $data['data']['search_Result']['results'] = $r;
            }
            else
            {
                // We've got no results, so make this empty for completeness
            $data['data']['search_Result']['results'] = "";
            }
        } else {
            $data['data']['error'] = $s['error'];
            $data['data']['search_Result']['results'] = "";
        }

		// Add some debugging information to the XML output
		$data['data']['queryInfo']['call'] = "search";
		$data['data']['queryInfo']['dbQuery'] = $s['query'];
		$data['data']['queryInfo']['numResults'] = $a;
		$data['data']['queryInfo']['executionTime'] = $s['time'];

		// Load the XML output view
		$this->load->view('api/index', $data);
	}

	/*
	 * version of search that is callable internally
	 * $arguments is an array of columns to query
	 */
	function api_search($arguments){
		// Load the API and Logbook models
		$this->load->model('api_model');
		$this->load->model('logbook_model');
		$this->load->model('user_model');

		if((!$this->user_model->authorize(3)) && ($this->api_model->authorize($arguments['key']) == 0)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard');
        }

		// Retrieve the arguments from the query string
        $data['data']['format'] = $arguments['format'];

		// Call the parser within the API model to build the query
		$query = $this->api_model->select_parse($arguments);

		// Execute the query, and retrieve the results
		$s = $this->logbook_model->api_search_query($query);
		return $s;
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

	function add()
	{
		// Load the API and Logbook models
		$this->load->model('api_model');
		$this->load->model('logbook_model');
		$this->load->model('user_model');
		if(!$this->user_model->authorize(3)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Retrieve the arguments from the query string
		$arguments = $this->_retrieve();

		// Call the parser within the API model to build the query
		$query = $this->api_model->insert_parse($arguments);

		# Check for guessable fields
		if(!isset($query['COL_TIME_ON']))
		{
			$query['COL_TIME_ON'] = date("Y-m-d H:i:s", time());
		}
		if(!isset($query['COL_TIME_OFF']))
		{
			$query['COL_TIME_OFF'] = date("Y-m-d H:i:s", time());
		}

		$data['data']['queryInfo']['dbQuery'] = "";
		$data['data']['queryInfo']['executionTime'] = 0;

		if(!isset($query['COL_CALL'])) {
			$data['data']['add_Result']['results'] = array(0 => array('Result' => 'EMISSINGCALL'));
		} else {
			$s = $this->logbook_model->api_insert_query($query);
			$data['data']['queryInfo']['dbQuery'] = $s['query'];
			$data['data']['queryInfo']['executionTime'] = $s['time'];

			$data['data']['add_Result']['results'] = array(0 => array('Result' => $s['result_string']));
		}

		// Add some debugging information to the XML output
		$data['data']['queryInfo']['call'] = "add";
		$data['data']['queryInfo']['numResults'] = 0;

		$this->load->view('api/index', $data);
	}

	// FUNCTION: _retrieve()
	// Pull the search query arguments from the query string
	private function _retrieve()
	{
		// This whole function could probably have been done in one line... if this was Perl.
		$arguments = array();

		// Retrieve each arguments
		$query = preg_grep("/^query=(.*)$/", $this->uri->segments);
		$limit = preg_grep("/^limit=(.*)$/", $this->uri->segments);
		$order = preg_grep("/^order=(.*)$/", $this->uri->segments);
		$fields = preg_grep("/^fields=(.*)$/", $this->uri->segments);
		$format = preg_grep("/^format=(.*)$/", $this->uri->segments);
		$key = preg_grep("/^key=(.*)$/", $this->uri->segments);

		// Strip each argument
		$arguments['query'] = substr(array_pop($query), 6);
		$arguments['query'] = substr($arguments['query'], 0, strlen($arguments['query']));
		$arguments['limit'] = substr(array_pop($limit), 6);
		$arguments['limit'] = substr($arguments['limit'], 0, strlen($arguments['limit']));
		$arguments['order'] = substr(array_pop($order), 6);
		$arguments['order'] = substr($arguments['order'], 0, strlen($arguments['order']));
		$arguments['fields'] = substr(array_pop($fields), 7);
		$arguments['fields'] = substr($arguments['fields'], 0, strlen($arguments['fields']));
		$arguments['format'] = substr(array_pop($format), 7);
		$arguments['format'] = substr($arguments['format'], 0, strlen($arguments['format']));
		$arguments['key'] = substr(array_pop($key), 4);
		$arguments['key'] = substr($arguments['key'], 0, strlen($arguments['key']));

    // By default, assume XML for the format if not otherwise set
    if($arguments['format'] == "") {
      $arguments['format'] = "xml";
    }

		// Return the arguments
		return $arguments;
	}

	/*
	*
	*	Function: QSO
	*	Task: allows passing of ADIF data to Cloudlog
	*/
	function qso() {
		header('Content-type: application/json');

		$this->load->model('api_model');

		// Decode JSON and store
		$obj = json_decode(file_get_contents("php://input"), true);
		if ($obj === NULL) {
		    echo json_encode(['status' => 'failed', 'reason' => "wrong JSON"]);
		    die();
		}

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


				if(isset($obj['station_profile_id'])) {
					$this->logbook_model->import($record, $obj['station_profile_id'], NULL, NULL, NULL, NULL, false, false, true);
				} else {
					$this->logbook_model->import($record, 0, NULL, NULL, NULL, NULL, false, false, true);
				}

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

		$user_id = $this->api_model->key_userid($obj['key']);

		// Store Result to Database
		$this->cat->update($obj, $user_id);

		// Return Message

		$arr = array('status' => 'success');

		echo json_encode($arr);

	}

	/*
	*
	*	Stats API function calls
	*
	*/

	function statistics() {
		header('Content-type: application/json');
		$this->load->model('logbook_model');

		$data['todays_qsos'] = $this->logbook_model->todays_qsos();
		$data['total_qsos'] = $this->logbook_model->total_qsos();
		$data['month_qsos'] = $this->logbook_model->month_qsos();
		$data['year_qsos'] = $this->logbook_model->year_qsos();

		http_response_code(201);
		echo json_encode(['Today' => $data['todays_qsos'], 'total_qsos' => $data['total_qsos'], 'month_qsos' => $data['month_qsos'], 'year_qsos' => $data['year_qsos']]);

	}

	function lookup() {
		// start benchmarking
		$this->output->enable_profiler(TRUE);
		/*
		*
		*	Callsign lookup function for Cloudlogs logging page or thirdparty systems
		*	which want to show previous QSO data on their system.
		*
		*	TODO
		*	- Local data make one database call ONLY
		*	- Add eQSL status
		*	- Add Callbook returned data
		*	- Add QSO before data array
		*	- Add options for checking based on band/mode/sat
		*
		*/


		// Make sure users logged in
		$this->load->model('user_model');
		if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }


		$this->load->model("logbook_model");
		$date = date("Y-m-d");

		// Return Array
		$return = [
			"callsign" => "",
			"dxcc" => false,
			"dxcc_lat" => "",
			"dxcc_long" => "",
			"dxcc_cqz" => "",
			"name" => "",
			"gridsquare"  => "",
			"location"  => "",
			"iota_ref" => "",
			"state" => "",
			"us_county" => "",
			"qsl_manager" => "",
			"bearing" 		=> "",
			"workedBefore" => false,
			"lotw_member" => false,
			"suffix_slash" => "", // Suffix Slash aka Portable
		];


		/*
		*
		*	Handle POST data being sent to check lookups
		*
		*/
			$raw_input = json_decode(file_get_contents("php://input"), true);

			$lookup_callsign = strtoupper($raw_input['callsign']);


		/*
		*
		*	Handle Callsign field
		*
		*/
			$return['callsign'] = $lookup_callsign;

		/*
		*
		*	Lookup DXCC and Suffix information
		*
		*/

			$callsign_dxcc_lookup = $this->logbook_model->dxcc_lookup($lookup_callsign, $date);

			$last_slash_pos = strrpos($lookup_callsign, '/');

			if(isset($last_slash_pos) && $last_slash_pos > 4) {
				$suffix_slash = $last_slash_pos === false ? $lookup_callsign : substr($lookup_callsign, $last_slash_pos + 1);
				switch ($suffix_slash) {
				    case "P":
				        $suffix_slash_item = "Portable";
				        break;
				    case "M":
				        $suffix_slash_item = "Mobile";
				    case "MM":
				        $suffix_slash_item =  "Maritime Mobile";
				        break;
				    default:
				    	// If its not one of the above suffix slashes its likely dxcc
				    	$ans2 = $this->logbook_model->dxcc_lookup($suffix_slash, $date);
				    	$suffix_slash_item = null;
				}

				$return['suffix_slash'] = $suffix_slash_item;
			}

			// If the final slash is a DXCC then find it!
			if (isset($ans2['call'])) {
				$return['dxcc'] = $ans2['entity'];
				$return['dxcc_lat'] = $ans2['lat'];
				$return['dxcc_long'] = $ans2['long'];
				$return['dxcc_cqz'] = $ans2['cqz'];
			} else {
				$return['dxcc'] = $callsign_dxcc_lookup['entity'];
				$return['dxcc_lat'] = $callsign_dxcc_lookup['lat'];
				$return['dxcc_long'] = $callsign_dxcc_lookup['long'];
				$return['dxcc_cqz'] = $callsign_dxcc_lookup['cqz'];
			}

		/*
		*
		*	Pool any local data we have for a callsign
		*
		*/
			$call_lookup_results = $this->logbook_model->call_lookup_result($lookup_callsign);

			if($call_lookup_results != null)
			{
				$return['name'] = $call_lookup_results->COL_NAME;
				$return['gridsquare'] = $call_lookup_results->COL_GRIDSQUARE;
				$return['location'] = $call_lookup_results->COL_QTH;
				$return['iota_ref'] = $call_lookup_results->COL_IOTA;
				$return['qsl_manager'] = $call_lookup_results->COL_QSL_VIA;
				$return['state'] = $call_lookup_results->COL_STATE;
				$return['us_county'] = $call_lookup_results->COL_CNTY;

				if ($return['gridsquare'] != "") {
					$return['latlng'] = $this->qralatlng($return['gridsquare']);
				}

			}


		/*
		*
		*	Check if callsign is active on LOTW
		*
		*/


		/*
		*
		*	Output Returned data
		*
		*/
		echo json_encode($return, JSON_PRETTY_PRINT);
		return;

		// End benchmarking
		$this->output->enable_profiler(FALSE);
	}

	function qralatlng($qra) {
		$this->load->library('Qra');
		$latlng = $this->qra->qra2latlong($qra);
		return $latlng;
	}
}
