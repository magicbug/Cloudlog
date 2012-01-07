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

	// Do absolutely nothing
	function index()
	{
		header("Location: ".$this->config->item('base_url'));
		//load the model and get results
		/*
		$this->load->model('logbook_model');
		$data['data'] = array();

		// load the view
		//$this->load->view('layout/header');
		$this->load->view('api/index', $data);
		//$this->load->view('layout/footer');
		*/
	}

	function help()
	{
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }


		$this->load->model('api_model');

		$data['api_keys'] = $this->api_model->keys();

		$data['page_title'] = "API Help";

		$this->load->view('layout/header', $data);
		$this->load->view('api/help');
		$this->load->view('layout/footer');
	}

	function generate($rights) {
		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }


		$this->load->model('api_model');

		$data['api_keys'] = $this->api_model->generate_key($rights);

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
		//if(!$this->user_model->authorize(3)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }

		// Retrieve the arguments from the query string
		$arguments = $this->_retrieve();
	
		// Call the parser within the API model to build the query
		$query = $this->api_model->select_parse($arguments);

		// Execute the query, and retrieve the results
		$s = $this->logbook_model->api_search_query($query);
		$results = $s['results'];

		// Cycle through the results, and translate between MySQL column names
		// and more friendly, descriptive names
		$a = 0;
		if($results->num_rows != 0)
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
		
		// Add some debugging information to the XML output
		$data['data']['queryInfo']['call'] = "search";
		$data['data']['queryInfo']['dbQuery'] = $s['query'];
		$data['data']['queryInfo']['numResults'] = $a;
		$data['data']['queryInfo']['executionTime'] = $s['time'];

		// Load the XML output view
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
		$query = preg_grep("/^query\[(.*)\]$/", $this->uri->segments);
		$limit = preg_grep("/^limit\[(.*)\]$/", $this->uri->segments);
		$order = preg_grep("/^order\[(.*)\]$/", $this->uri->segments);
		$fields = preg_grep("/^fields\[(.*)\]$/", $this->uri->segments);

		// Strip each argument
		$arguments['query'] = substr(array_pop($query), 6);
		$arguments['query'] = substr($arguments['query'], 0, strlen($arguments['query']) - 1);
		$arguments['limit'] = substr(array_pop($limit), 6);
		$arguments['limit'] = substr($arguments['limit'], 0, strlen($arguments['limit']) - 1);
		$arguments['order'] = substr(array_pop($order), 6);
		$arguments['order'] = substr($arguments['order'], 0, strlen($arguments['order']) - 1);
		$arguments['fields'] = substr(array_pop($fields), 7);
		$arguments['fields'] = substr($arguments['fields'], 0, strlen($arguments['fields']) - 1);

		// Return the arguments
		return $arguments;
	}
}
