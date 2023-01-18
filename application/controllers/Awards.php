<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for awards.

	These are taken from comments fields or ADIF fields
*/

class Awards extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
		$this->lang->load(array(
			'lotw',
			'eqsl'
		));
	}

	public function index()
	{
		// Render Page
		$data['page_title'] = "Awards";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/index');
		$this->load->view('interface_assets/footer');
	}

	public function dok ()
	{

		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$this->load->model('dok');
		$this->load->model('bands');
		$this->load->model('modes');

		if($this->input->method() === 'post') {
			$postdata['doks'] = $this->input->post('doks');
		} else {
			$postdata['doks'] = 'both';
		}

		$data['worked_bands'] = $this->bands->get_worked_bands('dok');
		$data['modes'] = $this->modes->active();

		if ($this->input->post('band') != NULL) {
			if ($this->input->post('band') == 'All') {
				$bands = $data['worked_bands'];
			} else {
				$bands[] = $this->input->post('band');
			}
		} else {
			$bands = $data['worked_bands'];
		}

		$data['bands'] = $bands;

		if($this->input->method() === 'post') {
			$postdata['qsl'] = $this->input->post('qsl');
			$postdata['lotw'] = $this->input->post('lotw');
			$postdata['eqsl'] = $this->input->post('eqsl');
			$postdata['worked'] = $this->input->post('worked');
			$postdata['confirmed'] = $this->input->post('confirmed');
			$postdata['band'] = $this->input->post('band');
			$postdata['mode'] = $this->input->post('mode');
		} else {
			$postdata['qsl'] = 1;
			$postdata['lotw'] = 1;
			$postdata['eqsl'] = 0;
			$postdata['worked'] = 1;
			$postdata['confirmed'] = 1;
			$postdata['band'] = 'All';
			$postdata['mode'] = 'All';
		}

		if ($logbooks_locations_array) {
			$location_list = "'".implode("','",$logbooks_locations_array)."'";
			$data['dok_array'] = $this->dok->get_dok_array($bands, $postdata, $location_list);
			$data['dok_summary'] = $this->dok->get_dok_summary($bands, $postdata, $location_list);
		} else {
			$location_list = null;
			$data['dok_array'] = null;
			$data['dok_summary'] = null;
		}

		// Render Page
		$data['page_title'] = "Awards - DOK";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/dok/index');
		$this->load->view('interface_assets/footer');

	}

    public function dok_details_ajax(){
        $a = $this->input->post();
        $q = "";
        foreach ($a as $key => $value) {
            $q .= $key."=".$value.("&#40;and&#41;");
        }
        $q = substr($q, 0, strlen($q)-13);

        $arguments["query"] = $q;
        $arguments["fields"] = '';
        $arguments["format"] = "json";
        $arguments["limit"] = '';
        $arguments["order"] = '';
        $arguments["join_station_profile"] = true;

        // Load the API and Logbook models
        $this->load->model('api_model');
        $this->load->model('logbook_model');

        // Call the parser within the API model to build the query
        $query = $this->api_model->select_parse($arguments);

        // Execute the query, and retrieve the results
        $data = $this->logbook_model->api_search_query($query);

        // Render Page
        $data['page_title'] = "Log View - DOK";
        $data['filter'] = str_replace("&#40;and&#41;", ", ", $q);
        $this->load->view('awards/details', $data);
    }

	public function dxcc ()	{
		$this->load->model('dxcc');
        $this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('dxcc'); // Used in the view for band select
        $data['modes'] = $this->modes->active(); // Used in the view for mode select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            }
            else {
                $bands[] = $this->input->post('band');
            }
        }
        else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        if($this->input->method() === 'post') {
            $postdata['qsl'] = $this->input->post('qsl');
            $postdata['lotw'] = $this->input->post('lotw');
            $postdata['eqsl'] = $this->input->post('eqsl');
            $postdata['worked'] = $this->input->post('worked');
            $postdata['confirmed'] = $this->input->post('confirmed');
            $postdata['notworked'] = $this->input->post('notworked');
            $postdata['includedeleted'] = $this->input->post('includedeleted');
            $postdata['Africa'] = $this->input->post('Africa');
            $postdata['Asia'] = $this->input->post('Asia');
            $postdata['Europe'] = $this->input->post('Europe');
            $postdata['NorthAmerica'] = $this->input->post('NorthAmerica');
            $postdata['SouthAmerica'] = $this->input->post('SouthAmerica');
            $postdata['Oceania'] = $this->input->post('Oceania');
            $postdata['Antarctica'] = $this->input->post('Antarctica');
            $postdata['band'] = $this->input->post('band');
            $postdata['mode'] = $this->input->post('mode');
        }
        else { // Setting default values at first load of page
            $postdata['qsl'] = 1;
            $postdata['lotw'] = 1;
            $postdata['eqsl'] = 0;
            $postdata['worked'] = 1;
            $postdata['confirmed'] = 1;
            $postdata['notworked'] = 1;
            $postdata['includedeleted'] = 0;
            $postdata['Africa'] = 1;
            $postdata['Asia'] = 1;
            $postdata['Europe'] = 1;
            $postdata['NorthAmerica'] = 1;
            $postdata['SouthAmerica'] = 1;
            $postdata['Oceania'] = 1;
            $postdata['Antarctica'] = 1;
            $postdata['band'] = 'All';
            $postdata['mode'] = 'All';
        }

		$dxcclist = $this->dxcc->fetchdxcc($postdata);
        $data['dxcc_array'] = $this->dxcc->get_dxcc_array($dxcclist, $bands, $postdata);
        $data['dxcc_summary'] = $this->dxcc->get_dxcc_summary($bands, $postdata);

		// Render Page
		$data['page_title'] = "Awards - DXCC";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/dxcc/index');
		$this->load->view('interface_assets/footer');
	}

    public function vucc()	{
        $this->load->model('vucc');
        $this->load->model('bands');
        $data['worked_bands'] = $this->bands->get_worked_bands('vucc');

        $data['vucc_array'] = $this->vucc->get_vucc_array($data);

        // Render Page
        $data['page_title'] = "Awards - VUCC";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/vucc/index');
        $this->load->view('interface_assets/footer');
    }

    public function vucc_band(){
        $this->load->model('vucc');
        $band = str_replace('"', "", $this->input->get("Band"));
        $type = str_replace('"', "", $this->input->get("Type"));
        $data['vucc_array'] = $this->vucc->vucc_details($band, $type);
        $data['type'] = $type;

        // Render Page
        $data['page_title'] = "VUCC - " .$band . " Band";
        $data['filter'] = "band ".$band;
        $data['band'] = $band;
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/vucc/band');
        $this->load->view('interface_assets/footer');
    }

    public function vucc_details_ajax(){
        $this->load->model('logbook_model');

        $gridsquare = str_replace('"', "", $this->input->post("Gridsquare"));
        $band = str_replace('"', "", $this->input->post("Band"));
        $data['results'] = $this->logbook_model->vucc_qso_details($gridsquare, $band);

        // Render Page
        $data['page_title'] = "Log View - VUCC";
        $data['filter'] = "vucc " . $gridsquare . " and band ".$band;
        $this->load->view('awards/details', $data);
    }

	/*
	 * Used to fetch QSOs from the logbook in the awards
	 */
	public function qso_details_ajax(){
		$this->load->model('logbook_model');

		$searchphrase = str_replace('"', "", $this->input->post("Searchphrase"));
		$band = str_replace('"', "", $this->input->post("Band"));
		$mode = str_replace('"', "", $this->input->post("Mode"));
		$type = $this->input->post('Type');
		$qsl = $this->input->post('QSL') == null ? '' : $this->input->post('QSL');

		$data['results'] = $this->logbook_model->qso_details($searchphrase, $band, $mode, $type, $qsl);

		// This is done because we have two different ways to get dxcc info in Cloudlog. Once is using the name (in awards), and the other one is using the ADIF DXCC.
		// We replace the values to make it look a bit nicer
		if ($type == 'DXCC2') {
			$type = 'DXCC';
			$dxccname = $this->logbook_model->get_entity($searchphrase);
			$searchphrase = $dxccname['name'];
		}

		$qsltype = [];
		if (strpos($qsl, "Q") !== false) {
			$qsltype[] = "QSL";
		}
		if (strpos($qsl, "L") !== false) {
			$qsltype[] = "LotW";
		}
		if (strpos($qsl, "E") !== false) {
			$qsltype[] = "eQSL";
		}

		// Render Page
		$data['page_title'] = "Log View - " . $type;
		$data['filter'] = $type . " " . $searchphrase . " and band ".$band . " and mode ".$mode;
		if (!empty($qsltype)) {
			$data['filter'] .= " and ".implode('/', $qsltype);
		}
		$this->load->view('awards/details', $data);
	}

	/*
		Handles showing worked SOTAs
		Comment field - SOTA:#
	*/
	public function sota() {

		// Grab all worked sota stations
		$this->load->model('sota');
		$data['sota_all'] = $this->sota->get_all();

		// Render page
		$data['page_title'] = "Awards - SOTA";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/sota/index');
		$this->load->view('interface_assets/footer');
	}

	/*
		Handles showing worked WWFFs
		Comment field - WWFF:#
	*/
	public function wwff() {

		// Grab all worked wwff stations
		$this->load->model('wwff');
		$data['wwff_all'] = $this->wwff->get_all();

		// Render page
		$data['page_title'] = "Awards - WWFF";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/wwff/index');
		$this->load->view('interface_assets/footer');
	}

	/*
		Handles showing worked POTAs
		Comment field - POTA:#
	*/
	public function pota() {

		// Grab all worked pota stations
		$this->load->model('pota');
		$data['pota_all'] = $this->pota->get_all();

		// Render page
		$data['page_title'] = "Awards - POTA";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/pota/index');
		$this->load->view('interface_assets/footer');
	}

	public function cq() {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->load->model('cq');
		$this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('cq');
		$data['modes'] = $this->modes->active(); // Used in the view for mode select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            }
            else {
                $bands[] = $this->input->post('band');
            }
        }
        else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        if($this->input->method() === 'post') {
            $postdata['qsl'] = $this->input->post('qsl');
            $postdata['lotw'] = $this->input->post('lotw');
            $postdata['eqsl'] = $this->input->post('eqsl');
            $postdata['worked'] = $this->input->post('worked');
            $postdata['confirmed'] = $this->input->post('confirmed');
            $postdata['notworked'] = $this->input->post('notworked');
            $postdata['band'] = $this->input->post('band');
			$postdata['mode'] = $this->input->post('mode');
        }
        else { // Setting default values at first load of page
            $postdata['qsl'] = 1;
            $postdata['lotw'] = 1;
            $postdata['eqsl'] = 0;
            $postdata['worked'] = 1;
            $postdata['confirmed'] = 1;
            $postdata['notworked'] = 1;
            $postdata['band'] = 'All';
			$postdata['mode'] = 'All';
        }

        if ($logbooks_locations_array) {
			$location_list = "'".implode("','",$logbooks_locations_array)."'";
            $data['cq_array'] = $this->cq->get_cq_array($bands, $postdata, $location_list);
            $data['cq_summary'] = $this->cq->get_cq_summary($bands, $postdata, $location_list);
		} else {
            $location_list = null;
            $data['cq_array'] = null;
            $data['cq_summary'] = null;
        }

        // Render page
        $data['page_title'] = "Awards - CQ Magazine";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/cq/index');
		$this->load->view('interface_assets/footer');
	}

    public function was() {
        $this->load->model('was');
		$this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('was');
		$data['modes'] = $this->modes->active(); // Used in the view for mode select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            }
            else {
                $bands[] = $this->input->post('band');
            }
        }
        else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        if($this->input->method() === 'post') {
            $postdata['lotw'] = $this->input->post('lotw');
            $postdata['qsl'] = $this->input->post('qsl');
            $postdata['worked'] = $this->input->post('worked');
            $postdata['confirmed'] = $this->input->post('confirmed');
            $postdata['notworked'] = $this->input->post('notworked');
            $postdata['band'] = $this->input->post('band');
			$postdata['mode'] = $this->input->post('mode');
        }
        else { // Setting default values at first load of page
            $postdata['lotw'] = 1;
            $postdata['qsl'] = 1;
            $postdata['worked'] = 1;
            $postdata['confirmed'] = 1;
            $postdata['notworked'] = 1;
            $postdata['band'] = 'All';
			$postdata['mode'] = 'All';
        }

        $data['was_array'] = $this->was->get_was_array($bands, $postdata);
        $data['was_summary'] = $this->was->get_was_summary($bands, $postdata);

        // Render Page
        $data['page_title'] = "Awards - WAS (Worked All States)";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/was/index');
        $this->load->view('interface_assets/footer');
    }

    public function iota ()	{
        $this->load->model('iota');
		$this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('iota'); // Used in the view for band select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            }
            else {
                $bands[] = $this->input->post('band');
            }
        }
        else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view
		$data['modes'] = $this->modes->active(); // Used in the view for mode select

        if($this->input->method() === 'post') {
            $postdata['worked'] = $this->input->post('worked');
            $postdata['confirmed'] = $this->input->post('confirmed');
            $postdata['notworked'] = $this->input->post('notworked');
            $postdata['includedeleted'] = $this->input->post('includedeleted');
            $postdata['Africa'] = $this->input->post('Africa');
            $postdata['Asia'] = $this->input->post('Asia');
            $postdata['Europe'] = $this->input->post('Europe');
            $postdata['NorthAmerica'] = $this->input->post('NorthAmerica');
            $postdata['SouthAmerica'] = $this->input->post('SouthAmerica');
            $postdata['Oceania'] = $this->input->post('Oceania');
            $postdata['Antarctica'] = $this->input->post('Antarctica');
            $postdata['band'] = $this->input->post('band');
			$postdata['mode'] = $this->input->post('mode');
        }
        else { // Setting default values at first load of page
            $postdata['worked'] = 1;
            $postdata['confirmed'] = 1;
            $postdata['notworked'] = 1;
            $postdata['includedeleted'] = 0;
            $postdata['Africa'] = 1;
            $postdata['Asia'] = 1;
            $postdata['Europe'] = 1;
            $postdata['NorthAmerica'] = 1;
            $postdata['SouthAmerica'] = 1;
            $postdata['Oceania'] = 1;
            $postdata['Antarctica'] = 1;
            $postdata['band'] = 'All';
			$postdata['mode'] = 'All';
        }

        $iotalist = $this->iota->fetchIota($postdata);
        $data['iota_array'] = $this->iota->get_iota_array($iotalist, $bands, $postdata);
        $data['iota_summary'] = $this->iota->get_iota_summary($bands, $postdata);

        // Render Page
        $data['page_title'] = "Awards - IOTA (Islands On The Air)";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/iota/index');
        $this->load->view('interface_assets/footer');
    }

    public function counties()	{
        $this->load->model('counties');
        $data['counties_array'] = $this->counties->get_counties_array();

        // Render Page
        $data['page_title'] = "Awards - US Counties";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/counties/index');
        $this->load->view('interface_assets/footer');
    }

    public function counties_details() {
        $this->load->model('counties');
        $state = str_replace('"', "", $this->input->get("State"));
        $type = str_replace('"', "", $this->input->get("Type"));
        $data['counties_array'] = $this->counties->counties_details($state, $type);
        $data['type'] = $type;

        // Render Page
        $data['page_title'] = "US Counties";
        $data['filter'] = $type . " counties in state ".$state;
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/counties/details');
        $this->load->view('interface_assets/footer');
    }

    public function counties_details_ajax(){
        $this->load->model('logbook_model');

        $state = str_replace('"', "", $this->input->post("State"));
        $county = str_replace('"', "", $this->input->post("County"));
        $data['results'] = $this->logbook_model->county_qso_details($state, $county);

        // Render Page
        $data['page_title'] = "Log View - Counties";
        $data['filter'] = "county " . $state;
        $this->load->view('awards/details', $data);
    }

	/*
		Handles showing worked Sigs
		Adif fields: my_sig
	*/
	public function sig() {
		// Grab all worked sig stations
		$this->load->model('sig');

		$data['sig_types'] = $this->sig->get_all_sig_types();

		// Render page
		$data['page_title'] = "Awards - SIG";
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/sig/index');
		$this->load->view('interface_assets/footer');
	}

	/*
	Handles showing worked Sigs
	*/
	public function sig_details() {

		// Grab all worked sig stations
		$this->load->model('sig');
		$type = str_replace('"', "", $this->input->get("type"));
		$data['sig_all'] = $this->sig->get_all($type);
		$data['type'] = $type;

		// Render page
		$data['page_title'] = "Awards - SIG - " . $type;
		$this->load->view('interface_assets/header', $data);
		$this->load->view('awards/sig/qso_list');
		$this->load->view('interface_assets/footer');
	}

	/*
	Handles exporting SIGS to ADIF
	*/
	public function sigexportadif() {
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('adif_data');

		$type = $this->uri->segment(3);
		$data['qsos'] = $this->adif_data->sig_all($type);

		$this->load->view('adif/data/exportall', $data);
	}

    /*
        function was_map

        This displays the WAS map and requires the $band_type and $mode_type
    */
    public function was_map($band_type, $mode_type) {

        $this->load->model('was');

		$data['mode'] = $mode_type;

        $bands[] = $band_type;

        $postdata['lotw'] = 1;
        $postdata['qsl'] = 1;
        $postdata['worked'] = 1;
        $postdata['confirmed'] = 1;
        $postdata['notworked'] = 1;
        $postdata['band'] = $band_type;
		$postdata['mode'] = $mode_type;

        $data['was_array'] = $this->was->get_was_array($bands, $postdata);

        $data['page_title'] = "";

        $this->load->view('awards/was/map', $data);
    }

    /*
        function cq_map
        This displays the CQ Zone map and requires the $band_type and $mode_type
    */
    public function cq_map() {
        $CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->load->model('cq');

        $bands[] = $this->input->post('band');

        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL: 1;
        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL: 1;
        $postdata['eqsl'] = $this->input->post('eqsl') == 0 ? NULL: 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL: 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL: 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL: 1;
        $postdata['band'] = $this->input->post('band');
		$postdata['mode'] = $this->input->post('mode');

        if ($logbooks_locations_array) {
			$location_list = "'".implode("','",$logbooks_locations_array)."'";
            $cq_array = $this->cq->get_cq_array($bands, $postdata, $location_list);
		} else {
            $location_list = null;
            $cq_array = null;
        }

        foreach ($cq_array as $cq => $value) {
            foreach ($value  as $key) {
                if($key != "") {
                    if (strpos($key, '>W<') !== false) {
                        $zones[] = 'W';
                        break;
                    }
                    if (strpos($key, '>C<') !== false) {
                        $zones[] = 'C';
                        break;
                    }
                    if (strpos($key, '-') !== false) {
                        $zones[] = '-';
                        break;
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($zones);
    }
    
    /*
        function dxcc_map
        This displays the DXCC map
    */
    public function dxcc_map() {
        $this->load->model('dxcc');
        $this->load->model('bands');

        $bands[] = $this->input->post('band');

        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL: 1;
        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL: 1;
        $postdata['eqsl'] = $this->input->post('eqsl') == 0 ? NULL: 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL: 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL: 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL: 1;
        $postdata['band'] = $this->input->post('band');
		    $postdata['mode'] = $this->input->post('mode');
        $postdata['includedeleted'] = $this->input->post('includedeleted') == 0 ? NULL: 1;
        $postdata['Africa'] = $this->input->post('Africa') == 0 ? NULL: 1;
        $postdata['Asia'] = $this->input->post('Asia') == 0 ? NULL: 1;
        $postdata['Europe'] = $this->input->post('Europe') == 0 ? NULL: 1;
        $postdata['NorthAmerica'] = $this->input->post('NorthAmerica') == 0 ? NULL: 1;
        $postdata['SouthAmerica'] = $this->input->post('SouthAmerica') == 0 ? NULL: 1;
        $postdata['Oceania'] = $this->input->post('Oceania') == 0 ? NULL: 1;
        $postdata['Antarctica'] = $this->input->post('Antarctica') == 0 ? NULL: 1;

        $dxcclist = $this->dxcc->fetchdxcc($postdata);

        $dxcc_array = $this->dxcc->get_dxcc_array($dxcclist, $bands, $postdata);

        $i = 0;

        foreach ($dxcclist as $dxcc) {
            $newdxcc[$i]['adif'] = $dxcc->adif;
            $newdxcc[$i]['prefix'] = $dxcc->prefix;
            $newdxcc[$i]['name'] = ucwords(strtolower($dxcc->name), "- (/");
            if ($dxcc->Enddate!=null) {
                $newdxcc[$i]['name'] .= ' (deleted)';
            }
            $newdxcc[$i]['lat'] = $dxcc->lat;
            $newdxcc[$i]['long'] = $dxcc->long;
            $newdxcc[$i++]['status'] = isset($dxcc_array[$dxcc->adif]) ? $this->returnStatus($dxcc_array[$dxcc->adif]) : 'x';
        }

        header('Content-Type: application/json');
        echo json_encode($newdxcc);
    }

    /*
        function iota
        This displays the IOTA map
    */
    public function iota_map() {
        $this->load->model('iota');
        $this->load->model('bands');

        $bands[] = $this->input->post('band');

        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL: 1;
        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL: 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL: 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL: 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL: 1;
        $postdata['band'] = $this->input->post('band');
		    $postdata['mode'] = $this->input->post('mode');
        $postdata['includedeleted'] = $this->input->post('includedeleted') == 0 ? NULL: 1;
        $postdata['Africa'] = $this->input->post('Africa') == 0 ? NULL: 1;
        $postdata['Asia'] = $this->input->post('Asia') == 0 ? NULL: 1;
        $postdata['Europe'] = $this->input->post('Europe') == 0 ? NULL: 1;
        $postdata['NorthAmerica'] = $this->input->post('NorthAmerica') == 0 ? NULL: 1;
        $postdata['SouthAmerica'] = $this->input->post('SouthAmerica') == 0 ? NULL: 1;
        $postdata['Oceania'] = $this->input->post('Oceania') == 0 ? NULL: 1;
        $postdata['Antarctica'] = $this->input->post('Antarctica') == 0 ? NULL: 1;

        $iotalist = $this->iota->fetchIota($postdata);

        $iota_array = $this->iota->get_iota_array($iotalist, $bands, $postdata);

        $i = 0;

        foreach ($iotalist as $iota) {
            $newiota[$i]['tag'] = $iota->tag;
            $newiota[$i]['prefix'] = $iota->prefix;
            $newiota[$i]['name'] = ucwords(strtolower($iota->name), "- (/");
            if ($iota->status == 'D') {
                $newiota[$i]['name'] .= ' (deleted)';
            }
            $newiota[$i]['lat1'] = $iota->lat1;
            $newiota[$i]['lon1'] = $iota->lon1;
            $newiota[$i]['lat2'] = $iota->lat2;
            $newiota[$i]['lon2'] = $iota->lon2;
            $newiota[$i++]['status'] = isset($iota_array[$iota->tag]) ? $this->returnStatus($iota_array[$iota->tag]) : 'x';
        }

        header('Content-Type: application/json');
        echo json_encode($newiota);
    }

    function returnStatus($string) {
        foreach ($string  as $key) {
            if($key != "") {
                if (strpos($key, '>W<') !== false) {
                    return 'W';
                }
                if (strpos($key, '>C<') !== false) {
                    return 'C';
                }
                if ($key == '-') {
                    return '-';
                }
            }
        }
    }
}
