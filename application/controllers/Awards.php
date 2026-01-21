<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Handles Displaying of information for awards.

	These are taken from comments fields or ADIF fields
*/

class Awards extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if (!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
            redirect('dashboard');
        }
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

    public function dok()
    {

        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->load->model('dok');
        $this->load->model('bands');
        $this->load->model('modes');

        if ($this->input->method() === 'post') {
            $postdata['doks'] = $this->security->xss_clean($this->input->post('doks'));
        } else {
            $postdata['doks'] = 'both';
        }

        $data['worked_bands'] = $this->bands->get_worked_bands('dok');
        $data['modes'] = $this->modes->active();

        if ($this->input->post('band') != NULL) {
            if ($this->input->post('band') == 'All') {
                $bands = $data['worked_bands'];
            } else {
                $bands[] = $this->security->xss_clean($this->input->post('band'));
            }
        } else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands;

        if ($this->input->method() === 'post') {
            $postdata['qsl'] = $this->security->xss_clean($this->input->post('qsl'));
            $postdata['lotw'] = $this->security->xss_clean($this->input->post('lotw'));
            $postdata['eqsl'] = $this->security->xss_clean($this->input->post('eqsl'));
            $postdata['worked'] = $this->security->xss_clean($this->input->post('worked'));
            $postdata['confirmed'] = $this->security->xss_clean($this->input->post('confirmed'));
            $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
            $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));
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
            $location_list = "'" . implode("','", $logbooks_locations_array) . "'";
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

    public function dxcc()
    {
        $this->load->model('dxcc');
        $this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('dxcc'); // Used in the view for band select
        $data['modes'] = $this->modes->active(); // Used in the view for mode select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            } else {
                $bands[] = $this->security->xss_clean($this->input->post('band'));
            }
        } else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        if ($this->input->method() === 'post') {
            $postdata['qsl'] = $this->security->xss_clean($this->input->post('qsl'));
            $postdata['lotw'] = $this->security->xss_clean($this->input->post('lotw'));
            $postdata['eqsl'] = $this->security->xss_clean($this->input->post('eqsl'));
            $postdata['worked'] = $this->security->xss_clean($this->input->post('worked'));
            $postdata['confirmed'] = $this->security->xss_clean($this->input->post('confirmed'));
            $postdata['notworked'] = $this->security->xss_clean($this->input->post('notworked'));
            $postdata['includedeleted'] = $this->security->xss_clean($this->input->post('includedeleted'));
            $postdata['Africa'] = $this->security->xss_clean($this->input->post('Africa'));
            $postdata['Asia'] = $this->security->xss_clean($this->input->post('Asia'));
            $postdata['Europe'] = $this->security->xss_clean($this->input->post('Europe'));
            $postdata['NorthAmerica'] = $this->security->xss_clean($this->input->post('NorthAmerica'));
            $postdata['SouthAmerica'] = $this->security->xss_clean($this->input->post('SouthAmerica'));
            $postdata['Oceania'] = $this->security->xss_clean($this->input->post('Oceania'));
            $postdata['Antarctica'] = $this->security->xss_clean($this->input->post('Antarctica'));
            $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
            $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));
        } else { // Setting default values at first load of page
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
        
        // Calculate continent breakdown and totals
        $data['continent_breakdown'] = $this->dxcc->get_continent_breakdown($dxcclist);
        $data['dxcc_statistics'] = $this->dxcc->get_dxcc_statistics($data['dxcc_array'], $postdata);

        // Render Page
        $data['page_title'] = "Awards - DXCC";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/dxcc/index');
        $this->load->view('interface_assets/footer');
    }

    public function waja()
    {
        $footerData = [];
        $footerData['scripts'] = [
            'assets/js/sections/wajamap.js?' . filemtime(realpath(__DIR__ . "/../../assets/js/sections/wajamap.js"))
        ];

        $this->load->model('waja');
        $this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('waja');
        $data['modes'] = $this->modes->active();

        if ($this->input->post('band') != NULL) {               // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            } else {
                $bands[] = $this->security->xss_clean($this->input->post('band'));
            }
        } else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        if ($this->input->method() === 'post') {
            $postdata['qsl'] = $this->security->xss_clean($this->input->post('qsl'));
            $postdata['lotw'] = $this->security->xss_clean($this->input->post('lotw'));
            $postdata['eqsl'] = $this->security->xss_clean($this->input->post('eqsl'));
            $postdata['worked'] = $this->security->xss_clean($this->input->post('worked'));
            $postdata['confirmed'] = $this->security->xss_clean($this->input->post('confirmed'));
            $postdata['notworked'] = $this->security->xss_clean($this->input->post('notworked'));
            $postdata['includedeleted'] = $this->security->xss_clean($this->input->post('includedeleted'));
            $postdata['Africa'] = $this->security->xss_clean($this->input->post('Africa'));
            $postdata['Asia'] = $this->security->xss_clean($this->input->post('Asia'));
            $postdata['Europe'] = $this->security->xss_clean($this->input->post('Europe'));
            $postdata['NorthAmerica'] = $this->security->xss_clean($this->input->post('NorthAmerica'));
            $postdata['SouthAmerica'] = $this->security->xss_clean($this->input->post('SouthAmerica'));
            $postdata['Oceania'] = $this->security->xss_clean($this->input->post('Oceania'));
            $postdata['Antarctica'] = $this->security->xss_clean($this->input->post('Antarctica'));
            $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
            $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));
        } else { // Setting default values at first load of page
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

        $data['waja_array'] = $this->waja->get_waja_array($bands, $postdata);
        $data['waja_summary'] = $this->waja->get_waja_summary($bands, $postdata);

        // Render Page
        $data['page_title'] = "Awards - WAJA";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/waja/index');
        $this->load->view('interface_assets/footer', $footerData);
    }

    public function vucc()
    {
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

    public function vucc_band()
    {
        $this->load->model('vucc');
        $band = str_replace('"', "", $this->security->xss_clean($this->input->get("Band")));
        $type = str_replace('"', "", $this->security->xss_clean($this->input->get("Type")));
        $data['vucc_array'] = $this->vucc->vucc_details($band, $type);
        $data['type'] = $type;

        // Render Page
        $data['page_title'] = "VUCC - " . $band . " Band";
        $data['filter'] = "band " . $band;
        $data['band'] = $band;
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/vucc/band');
        $this->load->view('interface_assets/footer');
    }

    public function vucc_details_ajax()
    {
        $this->load->model('logbook_model');

        // Validate and sanitize gridsquare input - should be alphanumeric only, max 6 characters
        $gridsquare_raw = $this->input->post("Gridsquare");
        $band_raw = $this->input->post("Band");
        
        // Validate gridsquare format - VUCC allows multiple gridsquares separated by commas
        if (!$gridsquare_raw) {
            show_error('Gridsquare parameter is required', 400);
            return;
        }
        
        // Split by comma and validate each gridsquare
        $gridsquares = explode(',', $gridsquare_raw);
        foreach ($gridsquares as $grid) {
            $grid = trim($grid);
            // Each grid should be 4 or 6 characters: AA00 or AA00aa format
            if (!preg_match('/^[A-Ra-r]{2}[0-9]{2}[A-Xa-x]{0,2}$/', $grid)) {
                show_error('Invalid gridsquare format: ' . htmlspecialchars($grid), 400);
                return;
            }
        }
        
        // Validate band - use Cloudlog's band system for validation
        $this->load->model('bands');
        $valid_bands = array_keys($this->bands->bandslots);
        $valid_bands[] = 'All'; // Add 'All' as a valid option
        
        if (!$band_raw || !in_array($band_raw, $valid_bands)) {
            show_error('Invalid band specified', 400);
            return;
        }
        
        $gridsquare = strtoupper($gridsquare_raw);
        $band = $band_raw;
        $data['results'] = $this->logbook_model->vucc_qso_details($gridsquare, $band);

        // Render Page
        $data['page_title'] = "Log View - VUCC";
        $data['filter'] = "vucc " . $gridsquare . " and band " . $band;
        $this->load->view('awards/details', $data);
    }

    /*
	 * Used to fetch QSOs from the logbook in the awards
	 */
    public function qso_details_ajax()
    {
        $this->load->model('logbook_model');

        $searchphrase = str_replace('"', "", $this->security->xss_clean($this->input->post("Searchphrase")));
        $band = str_replace('"', "", $this->security->xss_clean($this->input->post("Band")));
        $mode = str_replace('"', "", $this->security->xss_clean($this->input->post("Mode")));
        $type = $this->security->xss_clean($this->input->post('Type'));
        $qsl = $this->input->post('QSL') == null ? '' : $this->security->xss_clean($this->input->post('QSL'));
        $searchmode = $this->input->post('searchmode') == null ? '' : $this->security->xss_clean($this->input->post('searchmode'));
        $data['results'] = $this->logbook_model->qso_details($searchphrase, $band, $mode, $type, $qsl, $searchmode);

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
            $qsltype[] = "LoTW";
        }
        if (strpos($qsl, "E") !== false) {
            $qsltype[] = "eQSL";
        }

        // Render Page
        $data['page_title'] = "Log View - " . $type;
        $data['filter'] = $type . " " . $searchphrase . " and band " . $band . " and mode " . $mode;
        if (!empty($qsltype)) {
            $data['filter'] .= " and " . implode('/', $qsltype);
        }
        $this->load->view('awards/details', $data);
    }

    /*
		Handles showing worked SOTAs
		Comment field - SOTA:#
	*/
    public function sota()
    {
        $this->ensure_sota_dataset();

        $this->load->model('sota_model');
        $this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('sota');
        $data['modes'] = $this->modes->active();

        $refs = $this->sota_model->get_unique_refs();
        $meta = $this->sota_model->get_summits_meta($refs);
        $data['associations'] = $this->build_sota_options($meta, 'association');
        $data['regions'] = $this->build_sota_options($meta, 'region');

        $data['page_title'] = "Awards - SOTA";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/sota/index', $data);
        $this->load->view('interface_assets/footer');
    }

    // HTMX: table fragment
    public function sota_table() {
        $filters = $this->sota_filters_from_request();
        $this->ensure_sota_dataset();
        $this->load->model('sota_model');
        $rows = $this->sota_model->fetch_qsos($filters);
        $meta = $this->sota_model->get_summits_meta($this->extract_sota_refs($rows));
        $rows = $this->filter_rows_by_meta($rows, $meta, $filters);
        $filteredMeta = array_intersect_key($meta, array_fill_keys($this->extract_sota_refs($rows), true));
        $data = [
            'rows' => $rows,
            'meta' => $filteredMeta,
            'filters' => $filters,
            'confirmed_refs' => $this->confirmed_sota_refs($rows),
            'custom_date_format' => $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format'),
        ];
        $this->load->view('awards/sota/components/table', $data);
    }

    // HTMX: stats fragment
    public function sota_stats() {
        $filters = $this->sota_filters_from_request();
        $this->ensure_sota_dataset();
        $this->load->model('sota_model');
        $data['total_uniques'] = $this->sota_model->get_uniques($filters);
        $data['confirmed_uniques'] = $this->sota_model->get_confirmations($filters);
        $data['first_last'] = $this->sota_model->get_first_last($filters);
        $data['by_band'] = $this->sota_model->get_uniques($filters, 'band');
        $data['by_mode'] = $this->sota_model->get_uniques($filters, 'mode');
        $data['filters'] = $filters;
        $this->load->view('awards/sota/components/stats', $data);
    }

    // HTMX: map fragment
    public function sota_map() {
        $filters = $this->sota_filters_from_request();
        $this->ensure_sota_dataset();
        $this->load->model('sota_model');
        $rows = $this->sota_model->fetch_qsos($filters);
        $meta = $this->sota_model->get_summits_meta($this->extract_sota_refs($rows));
        $rows = $this->filter_rows_by_meta($rows, $meta, $filters);
        $refs = $this->extract_sota_refs($rows);
        
        // Group QSOs by SOTA ref for modal display
        $qsos_by_ref = [];
        foreach ($rows as $row) {
            $ref = $this->normalize_sota_ref($row->COL_SOTA_REF ?? null);
            if (!empty($ref)) {
                if (!isset($qsos_by_ref[$ref])) {
                    $qsos_by_ref[$ref] = [];
                }
                $qsos_by_ref[$ref][] = $row;
            }
        }
        
        $data = [
            'summits' => array_intersect_key($meta, array_fill_keys($refs, true)),
            'confirmed_refs' => $this->confirmed_sota_refs($rows),
            'qsos_by_ref' => $qsos_by_ref,
            'custom_date_format' => $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format'),
        ];
        $this->load->view('awards/sota/components/map', $data);
    }

    private function ensure_sota_dataset() {
        $fullPath = FCPATH . 'assets/json/sota_summits.csv';
        $autoPath = FCPATH . 'assets/json/sota.txt';
        if (is_readable($fullPath) && is_readable($autoPath)) {
            return;
        }
        $this->load->library('sota', null, 'sotaLib');
        $result = $this->sotaLib->refreshFiles(true);
        if (!$result['ok']) {
            log_message('error', 'Unable to refresh SOTA dataset: ' . $result['message']);
        }
    }

    private function sota_filters_from_request() {
        $payload = $this->input->method() === 'post' ? $this->input->post() : $this->input->get();
        $filters = [];
        $filters['from'] = $this->security->xss_clean($payload['from'] ?? null);
        $filters['to'] = $this->security->xss_clean($payload['to'] ?? null);
        $filters['band'] = $this->security->xss_clean($payload['band'] ?? 'All') ?: 'All';
        $filters['mode'] = $this->security->xss_clean($payload['mode'] ?? 'All') ?: 'All';
        $filters['association'] = $this->security->xss_clean($payload['association'] ?? null);
        $filters['region'] = $this->security->xss_clean($payload['region'] ?? null);
        $filters['confirmed'] = !empty($payload['confirmed']);
        return $filters;
    }

    private function filter_rows_by_meta($rows, $meta, $filters) {
        if (empty($filters['association']) && empty($filters['region'])) {
            return $rows;
        }

        $out = [];
        foreach ($rows as $row) {
            $ref = $this->normalize_sota_ref($row->COL_SOTA_REF ?? null);
            $info = $ref && isset($meta[$ref]) ? $meta[$ref] : null;
            if (!$info) {
                continue;
            }
            if (!empty($filters['association']) && strcasecmp($info['association'] ?? '', $filters['association']) !== 0) {
                continue;
            }
            if (!empty($filters['region']) && strcasecmp($info['region'] ?? '', $filters['region']) !== 0) {
                continue;
            }
            $out[] = $row;
        }
        return $out;
    }

    private function extract_sota_refs($rows) {
        $refs = [];
        foreach ($rows as $r) {
            $ref = $this->normalize_sota_ref($r->COL_SOTA_REF ?? null);
            if (!empty($ref)) {
                $refs[$ref] = true;
            }
        }
        return array_keys($refs);
    }

    private function confirmed_sota_refs($rows) {
        $refs = [];
        foreach ($rows as $r) {
            $ref = $this->normalize_sota_ref($r->COL_SOTA_REF ?? null);
            if (!empty($ref) && (($r->col_qsl_rcvd ?? '') === 'Y' || ($r->col_lotw_qsl_rcvd ?? '') === 'Y' || ($r->COL_QSL_RCVD ?? '') === 'Y' || ($r->COL_LOTW_QSL_RCVD ?? '') === 'Y')) {
                $refs[$ref] = true;
            }
        }
        return array_keys($refs);
    }

    private function normalize_sota_ref($ref) {
        return strtoupper(trim((string)$ref));
    }

    private function build_sota_options($meta, $field) {
        $values = [];
        foreach ($meta as $info) {
            if (!empty($info[$field])) {
                $values[] = $info[$field];
            }
        }
        $values = array_values(array_unique($values));
        sort($values, SORT_NATURAL | SORT_FLAG_CASE);
        return $values;
    }

    /*
		Handles showing worked WWFFs
		Comment field - WWFF:#
	*/
    public function wwff()
    {

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
    public function pota()
    {
        // Render the POTA dashboard shell; content loaded via HTMX
        $this->load->model('modes');
        $this->load->model('bands');

        $data['page_title'] = "Awards - POTA";
        $data['worked_bands'] = $this->bands->get_worked_bands('pota');
        $data['modes'] = $this->modes->active();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/pota/index', $data);
        $this->load->view('interface_assets/footer');
    }

    // HTMX: table fragment
    public function pota_table() {
        $filters = $this->pota_filters_from_request();
        $this->load->model('pota');
        $data['rows'] = $this->pota->fetch_qsos($filters);
        $data['filters'] = $filters;
        $this->load->view('awards/pota/components/table', $data);
    }

    // HTMX: stats fragment (totals, first/last)
    public function pota_stats() {
        $filters = $this->pota_filters_from_request();
        $this->load->model('pota');
        $data['total_uniques'] = $this->pota->get_uniques($filters);
        $data['confirmed_uniques'] = $this->pota->get_confirmations($filters);
        $data['first_last'] = $this->pota->get_first_last($filters);
        $data['filters'] = $filters;
        $this->load->view('awards/pota/components/stats', $data);
    }

    // HTMX: progress fragment (threshold bars)
    public function pota_progress() {
        $filters = $this->pota_filters_from_request();
        $this->load->model('pota');
        $worked = $this->pota->get_uniques($filters);
        $thresholds = [10,25,50,100];
        $data = [
            'worked' => $worked,
            'thresholds' => $thresholds,
        ];
        $this->load->view('awards/pota/components/progress', $data);
    }

    // HTMX: map fragment (Leaflet markers)
    public function pota_map() {
        $filters = $this->pota_filters_from_request();
        $this->load->model('pota');
        $rows = $this->pota->fetch_qsos($filters);
        $refs = [];
        foreach ($rows as $r) { $refs[$r->COL_POTA_REF] = true; }
        $refs = array_keys($refs);
        $data['parks'] = $this->pota->get_parks_meta($refs);
        $this->load->view('awards/pota/components/map', $data);
    }

    private function pota_filters_from_request() {
        $filters = [];
        $filters['from'] = $this->security->xss_clean($this->input->get('from'));
        $filters['to'] = $this->security->xss_clean($this->input->get('to'));
        $filters['band'] = $this->security->xss_clean($this->input->get('band')) ?: 'All';
        $filters['mode'] = $this->security->xss_clean($this->input->get('mode')) ?: 'All';
        $filters['confirmed'] = $this->input->get('confirmed') ? true : false;
        return $filters;
    }

    public function cq()
    {
        $CI = &get_instance();
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
            } else {
                $bands[] = $this->input->post('band');
            }
        } else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        if ($this->input->method() === 'post') {
            $postdata['qsl'] = $this->security->xss_clean($this->input->post('qsl'));
            $postdata['lotw'] = $this->security->xss_clean($this->input->post('lotw'));
            $postdata['eqsl'] = $this->security->xss_clean($this->input->post('eqsl'));
            $postdata['worked'] = $this->security->xss_clean($this->input->post('worked'));
            $postdata['confirmed'] = $this->security->xss_clean($this->input->post('confirmed'));
            $postdata['notworked'] = $this->security->xss_clean($this->input->post('notworked'));
            $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
            $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));
        } else { // Setting default values at first load of page
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
            $location_list = "'" . implode("','", $logbooks_locations_array) . "'";
            $data['cq_array'] = $this->cq->get_cq_array($bands, $postdata, $location_list);
            $data['cq_summary'] = $this->cq->get_cq_summary($bands, $postdata, $location_list);
        } else {
            $location_list = null;
            $data['cq_array'] = null;
            $data['cq_summary'] = null;
        }

        // Render page
        $data['page_title'] = "Awards - CQ Magazine WAZ";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/cq/index');
        $this->load->view('interface_assets/footer');
    }

    public function was()
    {
        $footerData = [];
        $footerData['scripts'] = [
            'assets/js/sections/wasmap.js?' . filemtime(realpath(__DIR__ . "/../../assets/js/sections/wasmap.js"))
        ];

        $this->load->model('was');
        $this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('was');
        $data['modes'] = $this->modes->active(); // Used in the view for mode select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            } else {
                $bands[] = $this->security->xss_clean($this->input->post('band'));
            }
        } else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view

        if ($this->input->method() === 'post') {
            $postdata['qsl'] = $this->security->xss_clean($this->input->post('qsl'));
            $postdata['lotw'] = $this->security->xss_clean($this->input->post('lotw'));
            $postdata['eqsl'] = $this->security->xss_clean($this->input->post('eqsl'));
            $postdata['worked'] = $this->security->xss_clean($this->input->post('worked'));
            $postdata['confirmed'] = $this->security->xss_clean($this->input->post('confirmed'));
            $postdata['notworked'] = $this->security->xss_clean($this->input->post('notworked'));
            $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
            $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));
        } else { // Setting default values at first load of page
            $postdata['qsl'] = 1;
            $postdata['lotw'] = 1;
            $postdata['eqsl'] = 0;
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
        $this->load->view('interface_assets/footer', $footerData);
    }
    public function wab()
    {
        $footerData = [];
        $footerData['scripts'] = [
            'assets/js/sections/wab.js?' . filemtime(realpath(__DIR__ . "/../../assets/js/sections/wab.js")),
        ];

        $this->load->model('worked_all_britain_model');
        $this->load->model('bands');
        $this->load->model('modes');

        $data['worked_bands'] = $this->bands->get_worked_bands('wab') ?? [];
        $data['modes'] = $this->modes->active();

        $filters = [];
        $filters['band'] = $this->input->post('band') ? $this->security->xss_clean($this->input->post('band')) : 'All';
        $filters['band'] = $filters['band'] ?: 'All';
        $filters['mode'] = $this->input->post('mode') ? $this->security->xss_clean($this->input->post('mode')) : 'All';
        $filters['mode'] = $filters['mode'] ?: 'All';
        $filters['confirmed_only'] = $this->input->post('confirmed_only') ? true : false;

        $data['filters'] = $filters;

        $data['worked_squares'] = array_filter($this->worked_all_britain_model->get_worked_squares($filters) ?? []);
        $data['confirmed_squares'] = array_filter($this->worked_all_britain_model->get_confirmed_squares($filters) ?? []);
        $data['worked_count'] = count($data['worked_squares']);
        $data['confirmed_count'] = count($data['confirmed_squares']);

        $data['wab_qsos'] = $this->worked_all_britain_model->get_wab_qsos($filters);
        $data['filter_summary'] = $this->wab_filter_summary($filters);

        // Render page
        $data['page_title'] = "Awards - Worked All Britain";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/wab/index');
        $this->load->view('interface_assets/footer', $footerData);
    }


    public function gmdxsummer()
    {
        // Load the GMDX Summer Challenge model
        $this->load->model('gmdxsummer_model');

        // Get Week 1
        $data['week1_6m_cw'] = $this->gmdxsummer_model->get_week('2024-05-26 18:00:00', '6m', 'CW');
        $data['week1_6m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-05-26 18:00:00', '6m');
        $data['week1_6m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-05-26 18:00:00', '6m');
        $data['week1_6m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-05-26 18:00:00', '6m');


        $data['week1_4m_cw'] = $this->gmdxsummer_model->get_week('2024-05-26 18:00:00', '4m', 'CW');
        $data['week1_4m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-05-26 18:00:00', '4m');
        $data['week1_4m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-05-26 18:00:00', '4m');
        $data['week1_4m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-05-26 18:00:00', '4m');

        // Get Week 2
        $data['week2_6m_cw'] = $this->gmdxsummer_model->get_week('2024-06-09 18:00:00', '6m', 'CW');
        $data['week2_6m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-06-09 18:00:00', '6m');
        $data['week2_6m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-06-09 18:00:00', '6m');
        $data['week2_6m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-06-09 18:00:00', '6m');


        $data['week2_4m_cw'] = $this->gmdxsummer_model->get_week('2024-06-09 18:00:00', '4m', 'CW');
        $data['week2_4m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-06-09 18:00:00', '4m');
        $data['week2_4m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-06-09 18:00:00', '4m');
        $data['week2_4m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-06-09 18:00:00', '4m');


        // Get Week 3
        $data['week3_6m_cw'] = $this->gmdxsummer_model->get_week('2024-06-23 18:00:00', '6m', 'CW');
        $data['week3_6m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-06-23 18:00:00', '6m');
        $data['week3_6m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-06-23 18:00:00', '6m');
        $data['week3_6m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-06-23 18:00:00', '6m');

        $data['week3_4m_cw'] = $this->gmdxsummer_model->get_week('2024-06-23 18:00:00', '4m', 'CW');
        $data['week3_4m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-06-23 18:00:00', '4m');
        $data['week3_4m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-06-23 18:00:00', '4m');
        $data['week3_4m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-06-23 18:00:00', '4m');

        // Get Week 4
        $data['week4_6m_cw'] = $this->gmdxsummer_model->get_week('2024-07-01 18:00:00', '6m', 'CW');
        $data['week4_6m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-07-01 18:00:00', '6m');
        $data['week4_6m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-07-01 18:00:00', '6m');
        $data['week4_6m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-07-01 18:00:00', '6m');

        $data['week4_4m_cw'] = $this->gmdxsummer_model->get_week('2024-07-01 18:00:00', '4m', 'CW');
        $data['week4_4m_ssb'] = $this->gmdxsummer_model->get_week_voice('2024-07-01 18:00:00', '4m');
        $data['week4_4m_digital'] = $this->gmdxsummer_model->get_week_digital('2024-07-01 18:00:00', '4m');
        $data['week4_4m_combined'] = $this->gmdxsummer_model->get_week_combined('2024-07-01 18:00:00', '4m');


        // Render page
        $data['page_title'] = "Awards - GMDX Summer Challenge";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/gmdxsummer/index');
        $this->load->view('interface_assets/footer');
    }

    public function wab_details_ajax()
    {
        $wab = str_replace('"', "", $this->security->xss_clean($this->input->post("Wab")));

        $wab = str_replace(["Small Square ", " Boundry Box"], "", $wab);
        $filters = [];
        $filters['band'] = $this->input->post('Band') ? $this->security->xss_clean($this->input->post('Band')) : 'All';
        $filters['band'] = $filters['band'] ?: 'All';
        $filters['mode'] = $this->input->post('Mode') ? $this->security->xss_clean($this->input->post('Mode')) : 'All';
        $filters['mode'] = $filters['mode'] ?: 'All';
        $filters['confirmed_only'] = $this->input->post('ConfirmedOnly') ? true : false;
        $filters['square'] = $wab;

        $this->load->model('worked_all_britain_model');
        $data['results'] = $this->worked_all_britain_model->get_wab_qsos($filters);

        // Render Page
        $data['page_title'] = "Log View - WAB";
        $data['filter'] = "WAB " . $wab . ' · ' . $this->wab_filter_summary($filters);
        $this->load->view('awards/wab/details', $data);
    }

    private function wab_filter_summary($filters)
    {
        $parts = [];
        $parts[] = ($filters['band'] ?? 'All') === 'All' ? 'All bands' : ($filters['band'] . ' band');
        $parts[] = ($filters['mode'] ?? 'All') === 'All' ? 'All modes' : ($filters['mode'] . ' mode');
        if (!empty($filters['confirmed_only'])) {
            $parts[] = 'Confirmed only';
        }
        return implode(' · ', $parts);
    }


    public function iota()
    {
        $this->load->model('iota');
        $this->load->model('modes');
        $this->load->model('bands');

        $data['worked_bands'] = $this->bands->get_worked_bands('iota'); // Used in the view for band select

        if ($this->input->post('band') != NULL) {   // Band is not set when page first loads.
            if ($this->input->post('band') == 'All') {         // Did the user specify a band? If not, use all bands
                $bands = $data['worked_bands'];
            } else {
                $bands[] = $this->security->xss_clean($this->input->post('band'));
            }
        } else {
            $bands = $data['worked_bands'];
        }

        $data['bands'] = $bands; // Used for displaying selected band(s) in the table in the view
        $data['modes'] = $this->modes->active(); // Used in the view for mode select

        if ($this->input->method() === 'post') {
            $postdata['worked'] = $this->security->xss_clean($this->input->post('worked'));
            $postdata['confirmed'] = $this->security->xss_clean($this->input->post('confirmed'));
            $postdata['notworked'] = $this->security->xss_clean($this->input->post('notworked'));
            $postdata['includedeleted'] = $this->security->xss_clean($this->input->post('includedeleted'));
            $postdata['Africa'] = $this->security->xss_clean($this->input->post('Africa'));
            $postdata['Asia'] = $this->security->xss_clean($this->input->post('Asia'));
            $postdata['Europe'] = $this->security->xss_clean($this->input->post('Europe'));
            $postdata['NorthAmerica'] = $this->security->xss_clean($this->input->post('NorthAmerica'));
            $postdata['SouthAmerica'] = $this->security->xss_clean($this->input->post('SouthAmerica'));
            $postdata['Oceania'] = $this->security->xss_clean($this->input->post('Oceania'));
            $postdata['Antarctica'] = $this->security->xss_clean($this->input->post('Antarctica'));
            $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
            $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));
        } else { // Setting default values at first load of page
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

    public function counties()
    {
        $this->load->model('counties');
        $data['counties_array'] = $this->counties->get_counties_array();

        // Render Page
        $data['page_title'] = "Awards - US Counties";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/counties/index');
        $this->load->view('interface_assets/footer');
    }

    public function counties_details()
    {
        $this->load->model('counties');
        $state = str_replace('"', "", $this->security->xss_clean($this->input->get("State")));
        $type = str_replace('"', "", $this->security->xss_clean($this->input->get("Type")));
        $data['counties_array'] = $this->counties->counties_details($state, $type);
        $data['type'] = $type;

        // Render Page
        $data['page_title'] = "US Counties";
        $data['filter'] = $type . " counties in state " . $state;
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/counties/details');
        $this->load->view('interface_assets/footer');
    }

    public function counties_details_ajax()
    {
        $this->load->model('logbook_model');

        $state = str_replace('"', "", $this->security->xss_clean($this->input->post("State")));
        $county = str_replace('"', "", $this->security->xss_clean($this->input->post("County")));
        $data['results'] = $this->logbook_model->county_qso_details($state, $county);

        // Render Page
        $data['page_title'] = "Log View - Counties";
        $data['filter'] = "county " . $state;
        $this->load->view('awards/details', $data);
    }

    public function gridmaster($dxcc)
    {
        $dxcc = $this->security->xss_clean($dxcc);
        $data['page_title'] = "Awards - " . strtoupper($dxcc) . " Gridmaster";

        $this->load->model('bands');
        $this->load->model('gridmap_model');
        $this->load->model('stations');

        $data['homegrid'] = explode(',', $this->stations->find_gridsquare());

        $data['modes'] = $this->gridmap_model->get_worked_modes();
        $data['bands'] = $this->bands->get_worked_bands();
        $data['sats_available'] = $this->bands->get_worked_sats();

        $data['layer'] = $this->optionslib->get_option('option_map_tile_server');

        $data['attribution'] = $this->optionslib->get_option('option_map_tile_server_copyright');

        $data['gridsquares_gridsquares'] = lang('gridsquares_gridsquares');
        $data['gridsquares_gridsquares_worked'] = lang('gridsquares_gridsquares_worked');
        $data['gridsquares_gridsquares_lotw'] = lang('gridsquares_gridsquares_lotw');
        $data['gridsquares_gridsquares_paper'] = lang('gridsquares_gridsquares_paper');

        $indexData['dxcc'] = $dxcc;

        $footerData = [];
        $footerData['scripts'] = [
            'assets/js/leaflet/geocoding.js',
            'assets/js/leaflet/L.MaidenheadColouredGridmasterMap.js',
            'assets/js/sections/gridmaster.js'
        ];

        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/gridmaster/index', $indexData);
        $this->load->view('interface_assets/footer', $footerData);
    }

    public function ffma()
    {
        $data['page_title'] = "Awards - Fred Fish Memorial Award (FFMA)";

        $this->load->model('bands');
        $this->load->model('ffma_model');
        $this->load->model('stations');

        $data['homegrid'] = explode(',', $this->stations->find_gridsquare());

        $data['layer'] = $this->optionslib->get_option('option_map_tile_server');

        $data['attribution'] = $this->optionslib->get_option('option_map_tile_server_copyright');

        $data['gridsquares_gridsquares'] = lang('gridsquares_gridsquares');
        $data['gridsquares_gridsquares_worked'] = lang('gridsquares_gridsquares_worked');
        $data['gridsquares_gridsquares_lotw'] = lang('gridsquares_gridsquares_lotw');
        $data['gridsquares_gridsquares_paper'] = lang('gridsquares_gridsquares_paper');
        $data['grid_count'] = $this->ffma_model->get_grid_count();
        $data['grids'] = $this->ffma_model->get_grids();

        $footerData = [];
        $footerData['scripts'] = [
            'assets/js/leaflet/geocoding.js',
            'assets/js/leaflet/L.MaidenheadColouredGridmasterMap.js',
            'assets/js/sections/ffma.js'
        ];

        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/ffma/index');
        $this->load->view('interface_assets/footer', $footerData);
    }

    public function getFfmaGridsjs()
    {
        $this->load->model('ffma_model');

        $array_grid_4char = array();
        $array_grid_4char_lotw = array();
        $array_grid_4char_paper = array();

        $grid_4char = "";
        $grid_4char_lotw = "";

        $query = $this->ffma_model->get_lotw();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grid_4char_lotw = strtoupper(substr($row->GRID_SQUARES, 0, 4));
                if (!in_array($grid_4char_lotw, $array_grid_4char_lotw)) {
                    array_push($array_grid_4char_lotw, $grid_4char_lotw);
                }
            }
        }

        $query = $this->ffma_model->get_paper();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grid_4char_paper = strtoupper(substr($row->GRID_SQUARES, 0, 4));
                if (!in_array($grid_4char_paper, $array_grid_4char_paper)) {
                    array_push($array_grid_4char_paper, $grid_4char_paper);
                }
            }
        }

        $query = $this->ffma_model->get_worked();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grid_four = strtoupper(substr($row->GRID_SQUARES, 0, 4));
                if (!in_array($grid_four, $array_grid_4char)) {
                    array_push($array_grid_4char, $grid_four);
                }
            }
        }

        $vucc_grids = $this->ffma_model->get_vucc_lotw();
        foreach ($vucc_grids as $key) {
            $grid_four_lotw = strtoupper(substr($key, 0, 4));
            if (!in_array($grid_four_lotw, $array_grid_4char_lotw)) {
                array_push($array_grid_4char_lotw, $grid_four_lotw);
            }
        }

        $vucc_grids = $this->ffma_model->get_vucc_paper();
        foreach ($vucc_grids as $key) {
            $grid_four_paper = strtoupper(substr($key, 0, 4));
            if (!in_array($grid_four_paper, $array_grid_4char_paper)) {
                array_push($array_grid_4char_paper, $grid_four_paper);
            }
        }

        $vucc_grids = $this->ffma_model->get_vucc_worked();
        foreach ($vucc_grids as $key) {
            $grid_four = strtoupper(substr($key, 0, 4));
            if (!in_array($grid_four, $array_grid_4char)) {
                array_push($array_grid_4char, $grid_four);
            }
        }

        $data['grid_4char_lotw'] = ($array_grid_4char_lotw);
        $data['grid_4char_paper'] = ($array_grid_4char_paper);
        $data['grid_4char'] = ($array_grid_4char);
        $data['grid_count'] = $this->ffma_model->get_grid_count();
        $data['grids'] = $this->ffma_model->get_grids();

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getGridmasterGridsjs($dxcc)
    {
        $this->load->model('gridmaster_model');

        $dxcc = $this->security->xss_clean($dxcc);

        $array_grid_4char = array();
        $array_grid_4char_lotw = array();
        $array_grid_4char_paper = array();

        $grid_4char = "";
        $grid_4char_lotw = "";

        $query = $this->gridmaster_model->get_lotw($dxcc);
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grid_4char_lotw = strtoupper(substr($row->GRID_SQUARES, 0, 4));
                if (!in_array($grid_4char_lotw, $array_grid_4char_lotw)) {
                    array_push($array_grid_4char_lotw, $grid_4char_lotw);
                }
            }
        }

        $query = $this->gridmaster_model->get_paper($dxcc);
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grid_4char_paper = strtoupper(substr($row->GRID_SQUARES, 0, 4));
                if (!in_array($grid_4char_paper, $array_grid_4char_paper)) {
                    array_push($array_grid_4char_paper, $grid_4char_paper);
                }
            }
        }

        $query = $this->gridmaster_model->get_worked($dxcc);
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $grid_four = strtoupper(substr($row->GRID_SQUARES, 0, 4));
                if (!in_array($grid_four, $array_grid_4char)) {
                    array_push($array_grid_4char, $grid_four);
                }
            }
        }

        $vucc_grids = $this->gridmaster_model->get_vucc_lotw($dxcc);
        foreach ($vucc_grids as $key) {
            $grid_four_lotw = strtoupper(substr($key, 0, 4));
            if (!in_array($grid_four_lotw, $array_grid_4char_lotw)) {
                array_push($array_grid_4char_lotw, $grid_four_lotw);
            }
        }

        $vucc_grids = $this->gridmaster_model->get_vucc_paper($dxcc);
        foreach ($vucc_grids as $key) {
            $grid_four_paper = strtoupper(substr($key, 0, 4));
            if (!in_array($grid_four_paper, $array_grid_4char_paper)) {
                array_push($array_grid_4char_paper, $grid_four_paper);
            }
        }

        $vucc_grids = $this->gridmaster_model->get_vucc_worked($dxcc);
        foreach ($vucc_grids as $key) {
            $grid_four = strtoupper(substr($key, 0, 4));
            if (!in_array($grid_four, $array_grid_4char)) {
                array_push($array_grid_4char, $grid_four);
            }
        }

        $data['grid_4char_lotw'] = ($array_grid_4char_lotw);
        $data['grid_4char_paper'] = ($array_grid_4char_paper);
        $data['grid_4char'] = ($array_grid_4char);
        $data['grid_count'] = $this->gridmaster_model->get_grid_count($dxcc);
        $data['grids'] = $this->gridmaster_model->get_grids($dxcc);
        $data['lat'] = $this->gridmaster_model->get_lat($dxcc);
        $data['lon'] = $this->gridmaster_model->get_lon($dxcc);
        $data['zoom'] = $this->gridmaster_model->get_zoom($dxcc);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /*
		Handles showing worked Sigs
		Adif fields: my_sig
	*/
    public function sig()
    {
        // Grab all worked sig stations
        $this->load->model('sig');
        $this->load->model('bands');
        $this->load->model('modes');

        // Parse filters from POST
        $filters = array();
        if ($this->input->method() === 'post') {
            $filters['band'] = $this->security->xss_clean($this->input->post('band')) ?: 'all';
            $filters['mode'] = $this->security->xss_clean($this->input->post('mode')) ?: 'all';
            $filters['confirmed_only'] = $this->security->xss_clean($this->input->post('confirmed_only'));
        } else {
            $filters['band'] = 'all';
            $filters['mode'] = 'all';
            $filters['confirmed_only'] = false;
        }

        $data['sig_types'] = $this->sig->get_all_sig_types($filters);

        // Get available bands and modes
        $data['bands'] = $this->bands->get_worked_bands('sig');
        $data['modes'] = $this->sig->get_worked_modes();

        // Pass filters to view
        $data['active_filters'] = $filters;
        $data['filter_summary'] = $this->_sig_filter_summary($filters);

        // Render page
        $data['page_title'] = "Awards - SIG";
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/sig/index');
        $this->load->view('interface_assets/footer');
    }

    /*
	Handles showing worked Sigs
	*/
    public function sig_details()
    {

        // Grab all worked sig stations
        $this->load->model('sig');
        $this->load->model('bands');
        $this->load->model('modes');

        $type = str_replace('"', "", $this->security->xss_clean($this->input->get("type")));

        // Parse filters
        $filters = array();
        if ($this->input->method() === 'post') {
            $filters['band'] = $this->security->xss_clean($this->input->post('band')) ?: 'all';
            $filters['mode'] = $this->security->xss_clean($this->input->post('mode')) ?: 'all';
            $filters['confirmed_only'] = $this->security->xss_clean($this->input->post('confirmed_only'));
        } else {
            $filters['band'] = 'all';
            $filters['mode'] = 'all';
            $filters['confirmed_only'] = false;
        }

        $data['sig_all'] = $this->sig->get_all($type, $filters);
        $data['type'] = $type;
        $data['filters'] = $filters;

        // Get stats for this SIG type
        $data['worked_refs'] = $this->sig->get_worked_sig_refs($type, $filters);
        $data['confirmed_refs'] = $this->sig->get_confirmed_sig_refs($type, $filters);

        // Get available bands and modes
        $data['bands'] = $this->bands->get_worked_bands('sig');
        $data['modes'] = $this->sig->get_worked_modes();

        $data['filter_summary'] = $this->_sig_filter_summary($filters);

        // Render page
        $data['page_title'] = "Awards - SIG - " . $type;
        $this->load->view('interface_assets/header', $data);
        $this->load->view('awards/sig/qso_list');
        $this->load->view('interface_assets/footer');
    }

    /**
     * Helper: Generate human-readable filter summary for SIG
     */
    private function _sig_filter_summary($filters) {
        $parts = array();
        if (!empty($filters['band']) && $filters['band'] !== 'all') {
            $parts[] = $filters['band'] . " band";
        }
        if (!empty($filters['mode']) && $filters['mode'] !== 'all') {
            $parts[] = $filters['mode'] . " mode";
        }
        if (!empty($filters['confirmed_only']) && ($filters['confirmed_only'] === true || $filters['confirmed_only'] === 'true')) {
            $parts[] = "Confirmed only";
        }
        return !empty($parts) ? implode(" · ", $parts) : "";
    }

    /*
	Handles exporting SIGS to ADIF
	*/
    public function sigexportadif()
    {
        // Set memory limit to unlimited to allow heavy usage
        ini_set('memory_limit', '-1');

        $this->load->model('adif_data');

        $type = $this->security->xss_clean($this->uri->segment(3));
        $data['qsos'] = $this->adif_data->sig_all($type);

        $this->load->view('adif/data/exportall', $data);
    }

    public function sigexportcsv()
    {
        $this->load->model('sig');

        $type = urldecode($this->security->xss_clean($this->uri->segment(3)));
        $qsos = $this->sig->get_all($type)->result();

        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=SIG_' . $type . '_' . date('Y-m-d') . '.csv');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write header row
        fputcsv($output, array(
            'Reference',
            'Date/Time',
            'Callsign',
            'Mode',
            'Band',
            'RST Sent',
            'RST Received',
            'QSL Status'
        ));

        // Write data rows
        foreach ($qsos as $row) {
            $is_confirmed = ($row->COL_QSL_RCVD == 'Y' || $row->COL_EQSL_QSL_RCVD == 'Y' || $row->COL_LOTW_QSL_RCVD == 'Y');
            
            $qsl_status = '';
            if ($row->COL_LOTW_QSL_RCVD == 'Y') {
                $qsl_status = 'LoTW';
            } elseif ($row->COL_EQSL_QSL_RCVD == 'Y') {
                $qsl_status = 'eQSL';
            } elseif ($row->COL_QSL_RCVD == 'Y') {
                $qsl_status = 'QSL';
            } else {
                $qsl_status = 'Unconfirmed';
            }

            fputcsv($output, array(
                $row->COL_SIG_INFO,
                date('d/m/y H:i', strtotime($row->COL_TIME_ON)),
                $row->COL_CALL,
                $row->COL_MODE,
                $row->COL_BAND,
                $row->COL_RST_SENT,
                $row->COL_RST_RCVD,
                $qsl_status
            ));
        }

        fclose($output);
        exit;
    }

    /*
        function was_map

        This displays the WAS map and requires the $band_type and $mode_type
    */
    public function was_map()
    {
        $stateString = 'AK,AL,AR,AZ,CA,CO,CT,DE,FL,GA,HI,IA,ID,IL,IN,KS,KY,LA,MA,MD,ME,MI,MN,MO,MS,MT,NC,ND,NE,NH,NJ,NM,NV,NY,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VA,VT,WA,WI,WV,WY';
        $wasArray = explode(',', $stateString);

        $this->load->model('was');

        $bands[] = $this->security->xss_clean($this->input->post('band'));

        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL : 1;
        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL : 1;
        $postdata['eqsl'] = $this->input->post('eqsl') == 0 ? NULL : 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL : 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL : 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL : 1;
        $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
        $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));

        $was_array = $this->was->get_was_array($bands, $postdata);

        $states = array();

        foreach ($wasArray as $state) {                       // Generating array for use in the table
            $states[$state] = '-';                   // Inits each state's count
        }


        foreach ($was_array as $was => $value) {
            foreach ($value  as $key) {
                if ($key != "") {
                    if (strpos($key, '>W<') !== false) {
                        $states[$was] = 'W';
                        break;
                    }
                    if (strpos($key, '>C<') !== false) {
                        $states[$was] = 'C';
                        break;
                    }
                    if (strpos($key, '-') !== false) {
                        $states[$was] = '-';
                        break;
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($states);
    }

    /*
        function cq_map
        This displays the CQ Zone map and requires the $band_type and $mode_type
    */
    public function cq_map()
    {
        $CI = &get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->load->model('cq');

        $bands[] = $this->input->post('band');

        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL : 1;
        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL : 1;
        $postdata['eqsl'] = $this->input->post('eqsl') == 0 ? NULL : 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL : 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL : 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL : 1;
        $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
        $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));

        if ($logbooks_locations_array) {
            $location_list = "'" . implode("','", $logbooks_locations_array) . "'";
            $cq_array = $this->cq->get_cq_array($bands, $postdata, $location_list);
        } else {
            $location_list = null;
            $cq_array = null;
        }

        $zones = array();

        foreach ($cq_array as $cq => $value) {
            foreach ($value  as $key) {
                if ($key != "") {
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
        function waja_map
    */
    public function waja_map()
    {
        $prefectureString = '01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47';
        $wajaArray = explode(',', $prefectureString);

        $this->load->model('waja');
        $this->load->model('bands');

        $bands[] = $this->security->xss_clean($this->input->post('band'));

        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL : 1;
        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL : 1;
        $postdata['eqsl'] = $this->input->post('eqsl') == 0 ? NULL : 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL : 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL : 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL : 1;
        $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
        $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));


        $waja_array = $this->waja->get_waja_array($bands, $postdata);

        $prefectures = array();

        foreach ($wajaArray as $state) {                       // Generating array for use in the table
            $prefectures[$state] = '-';                   // Inits each state's count
        }


        foreach ($waja_array as $waja => $value) {
            foreach ($value  as $key) {
                if ($key != "") {
                    if (strpos($key, '>W<') !== false) {
                        $prefectures[$waja] = 'W';
                        break;
                    }
                    if (strpos($key, '>C<') !== false) {
                        $prefectures[$waja] = 'C';
                        break;
                    }
                    if (strpos($key, '-') !== false) {
                        $prefectures[$waja] = '-';
                        break;
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($prefectures);
    }

    /*
        function dxcc_map
        This displays the DXCC map
    */
    public function dxcc_map()
    {
        $this->load->model('dxcc');
        $this->load->model('bands');

        $bands[] = $this->security->xss_clean($this->input->post('band'));

        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL : 1;
        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL : 1;
        $postdata['eqsl'] = $this->input->post('eqsl') == 0 ? NULL : 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL : 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL : 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL : 1;
        $postdata['band'] = $this->security->xss_clean($this->input->post('band'));
        $postdata['mode'] = $this->security->xss_clean($this->input->post('mode'));
        $postdata['includedeleted'] = $this->input->post('includedeleted') == 0 ? NULL : 1;
        $postdata['Africa'] = $this->input->post('Africa') == 0 ? NULL : 1;
        $postdata['Asia'] = $this->input->post('Asia') == 0 ? NULL : 1;
        $postdata['Europe'] = $this->input->post('Europe') == 0 ? NULL : 1;
        $postdata['NorthAmerica'] = $this->input->post('NorthAmerica') == 0 ? NULL : 1;
        $postdata['SouthAmerica'] = $this->input->post('SouthAmerica') == 0 ? NULL : 1;
        $postdata['Oceania'] = $this->input->post('Oceania') == 0 ? NULL : 1;
        $postdata['Antarctica'] = $this->input->post('Antarctica') == 0 ? NULL : 1;

        $dxcclist = $this->dxcc->fetchdxcc($postdata);

        $dxcc_array = $this->dxcc->get_dxcc_array($dxcclist, $bands, $postdata);

        $i = 0;

        foreach ($dxcclist as $dxcc) {
            $newdxcc[$i]['adif'] = $dxcc->adif;
            $newdxcc[$i]['prefix'] = $dxcc->prefix;
            $newdxcc[$i]['name'] = ucwords(strtolower($dxcc->name), "- (/");
            if ($dxcc->Enddate != null) {
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
    public function iota_map()
    {
        $this->load->model('iota');
        $this->load->model('bands');

        $bands[] = $this->security->xss_clean($this->input->post('band'));

        $postdata['lotw'] = $this->input->post('lotw') == 0 ? NULL : 1;
        $postdata['qsl'] = $this->input->post('qsl') == 0 ? NULL : 1;
        $postdata['worked'] = $this->input->post('worked') == 0 ? NULL : 1;
        $postdata['confirmed'] = $this->input->post('confirmed')  == 0 ? NULL : 1;
        $postdata['notworked'] = $this->input->post('notworked')  == 0 ? NULL : 1;
        $postdata['band'] = $this->input->post('band');
        $postdata['mode'] = $this->input->post('mode');
        $postdata['includedeleted'] = $this->input->post('includedeleted') == 0 ? NULL : 1;
        $postdata['Africa'] = $this->input->post('Africa') == 0 ? NULL : 1;
        $postdata['Asia'] = $this->input->post('Asia') == 0 ? NULL : 1;
        $postdata['Europe'] = $this->input->post('Europe') == 0 ? NULL : 1;
        $postdata['NorthAmerica'] = $this->input->post('NorthAmerica') == 0 ? NULL : 1;
        $postdata['SouthAmerica'] = $this->input->post('SouthAmerica') == 0 ? NULL : 1;
        $postdata['Oceania'] = $this->input->post('Oceania') == 0 ? NULL : 1;
        $postdata['Antarctica'] = $this->input->post('Antarctica') == 0 ? NULL : 1;

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

    function returnStatus($string)
    {
        foreach ($string  as $key) {
            if ($key != "") {
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

    /*
    * Get QSOs for a specific DXCC entity
    */
    public function get_dxcc_qsos()
    {
        $this->load->model('logbooks_model');

        $dxcc_id = $this->security->xss_clean($this->input->post('dxcc_id'));
        $limit = $this->security->xss_clean($this->input->post('limit')) ?: 20;

        if (!$dxcc_id || !is_numeric($dxcc_id)) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Invalid DXCC ID'));
            return;
        }

        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'No logbook data'));
            return;
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        try {
            // Get QSOs for this DXCC
            $query = $this->db->query("
                SELECT 
                    col_time_on,
                    col_call,
                    col_band,
                    col_mode,
                    col_rst_sent,
                    col_rst_rcvd,
                    col_qsl_sent,
                    col_qsl_rcvd,
                    COL_LOTW_QSL_SENT,
                    COL_LOTW_QSL_RCVD
                FROM " . $this->config->item('table_name') . "
                WHERE station_id IN (" . $location_list . ")
                AND col_dxcc = " . $dxcc_id . "
                ORDER BY col_time_on DESC
                LIMIT " . intval($limit)
            );

            if ($query->num_rows() > 0) {
                $qsos = $query->result_array();
                header('Content-Type: application/json');
                echo json_encode(array('qsos' => $qsos, 'count' => $query->num_rows()));
            } else {
                header('Content-Type: application/json');
                echo json_encode(array('qsos' => array(), 'count' => 0));
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => $e->getMessage()));
        }
    }

    /*
    * Get QSOs for a specific DXCC entity filtered by status (Confirmed or Worked)
    */
    public function get_dxcc_qsos_by_status()
    {
        $this->load->model('logbooks_model');

        $dxcc_id = $this->security->xss_clean($this->input->post('dxcc_id'));
        $status = $this->security->xss_clean($this->input->post('status'));
        $limit = $this->security->xss_clean($this->input->post('limit')) ?: 100;

        if (!$dxcc_id || !is_numeric($dxcc_id) || !$status) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Invalid parameters', 'count' => 0, 'qsos' => array()));
            return;
        }

        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'No logbook data', 'count' => 0, 'qsos' => array()));
            return;
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        try {
            // Build WHERE clause for status filter
            $status_where = '';
            if ($status === 'C') {
                // Confirmed: either QSL received or LoTW received
                $status_where = " AND (col_qsl_rcvd = 1 OR COL_LOTW_QSL_RCVD = 'Y')";
            } elseif ($status === 'W') {
                // Worked but not confirmed: neither QSL nor LoTW received
                $status_where = " AND (col_qsl_rcvd IS NULL OR col_qsl_rcvd != 1) AND (COL_LOTW_QSL_RCVD IS NULL OR COL_LOTW_QSL_RCVD != 'Y')";
            }

            // Get QSOs for this DXCC filtered by status
            $query = $this->db->query("
                SELECT 
                    col_time_on,
                    col_call,
                    col_band,
                    col_mode,
                    col_rst_sent,
                    col_rst_rcvd,
                    col_qsl_sent,
                    col_qsl_rcvd,
                    COL_LOTW_QSL_SENT,
                    COL_LOTW_QSL_RCVD
                FROM " . $this->config->item('table_name') . "
                WHERE station_id IN (" . $location_list . ")
                AND col_dxcc = " . $dxcc_id . $status_where . "
                ORDER BY col_time_on DESC
                LIMIT " . intval($limit)
            );

            if ($query->num_rows() > 0) {
                $qsos = $query->result_array();
                header('Content-Type: application/json');
                echo json_encode(array('qsos' => $qsos, 'count' => $query->num_rows()));
            } else {
                header('Content-Type: application/json');
                echo json_encode(array('qsos' => array(), 'count' => 0));
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => $e->getMessage(), 'count' => 0, 'qsos' => array()));
        }
    }

    /*
    * Get DXCC entities for a specific continent with their status
    */
    public function get_continent_qsos()
    {
        $this->load->model('logbooks_model');

        $continent_code = $this->security->xss_clean($this->input->post('continent_code'));

        if (!$continent_code) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Invalid continent code', 'count' => 0, 'entities' => array()));
            return;
        }

        $logbooks_locations_array = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if (!$logbooks_locations_array) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'No logbook data', 'count' => 0, 'entities' => array()));
            return;
        }

        $location_list = "'" . implode("','", $logbooks_locations_array) . "'";

        try {
            // Get all DXCC entities for this continent with their status in a single query
            $query = $this->db->query("
                SELECT 
                    d.adif,
                    d.name,
                    d.prefix,
                    d.cont,
                    CASE 
                        WHEN MAX(CASE WHEN (c.col_qsl_rcvd = 1 OR c.COL_LOTW_QSL_RCVD = 'Y') THEN 1 ELSE 0 END) = 1 THEN 'confirmed'
                        WHEN COUNT(c.col_dxcc) > 0 THEN 'worked'
                        ELSE 'unworked'
                    END as status
                FROM dxcc_entities d
                LEFT JOIN " . $this->config->item('table_name') . " c ON d.adif = c.col_dxcc AND c.station_id IN (" . $location_list . ")
                WHERE d.cont = '" . $this->db->escape_like_str($continent_code) . "'
                GROUP BY d.adif, d.name, d.prefix, d.cont
                ORDER BY d.name ASC
            ");

            $entities = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $entity) {
                    $entities[] = array(
                        'adif' => $entity['adif'],
                        'name' => $entity['name'],
                        'prefix' => $entity['prefix'],
                        'status' => $entity['status']
                    );
                }
            }

            header('Content-Type: application/json');
            echo json_encode(array('entities' => $entities, 'count' => count($entities)));
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => $e->getMessage(), 'count' => 0, 'entities' => array()));
        }
    }
}