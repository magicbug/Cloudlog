<?php

class Logbook_model extends CI_Model {

  function __construct()
  {
      // Call the Model constructor
      parent::__construct();
  }

  /* Add QSO to Logbook */
  function add() {
    // Join date+time
    $datetime = date('Y-m-d') ." ". $this->input->post('start_time');

    if ($this->input->post('prop_mode') != null) {
      $prop_mode = $this->input->post('prop_mode');
    } else {
      $prop_mode = "";
    }

        if($this->input->post('sat_name')) {
            $prop_mode = "SAT";
        }

        if($this->session->userdata('user_locator')){
            $locator = $this->session->userdata('user_locator');
        } else {
            $locator = $this->config->item('locator');
        }

    // Create array with QSO Data

    $data = array(
      'COL_TIME_ON' => $datetime,
      'COL_TIME_OFF' => $datetime,
      'COL_CALL' => strtoupper(trim($this->input->post('callsign'))),
      'COL_BAND' => trim($this->input->post('band')),
      'COL_FREQ' => $this->parse_frequency($this->input->post('freq_display')),
      'COL_MODE' => $this->input->post('mode'),
      'COL_RST_RCVD' => $this->input->post('rst_recv'),
      'COL_RST_SENT' => $this->input->post('rst_sent'),
      'COL_NAME' => $this->input->post('name'),
      'COL_COMMENT' => $this->input->post('comment'),
      'COL_SAT_NAME' => strtoupper($this->input->post('sat_name')),
      'COL_SAT_MODE' => strtoupper($this->input->post('sat_mode')),
      'COL_COUNTRY' => $this->input->post('country'),
      'COL_MY_RIG' => $this->input->post('equipment'),
      'COL_QSLSDATE' => date('Y-m-d'),
      'COL_QSLRDATE' => date('Y-m-d'),
      'COL_QSL_SENT' => $this->input->post('qsl_sent'),
      'COL_QSL_RCVD' => $this->input->post('qsl_recv'),
      'COL_QSL_SENT_VIA' => $this->input->post('qsl_sent_method'),
      'COL_QSL_RCVD_VIA' => $this->input->post('qsl_recv_method'),
      'COL_QSL_VIA' => $this->input->post('qsl_via'),
      'COL_OPERATOR' => $this->session->userdata('user_callsign'),
      'COL_STATION_CALLSIGN' => $this->session->userdata('user_callsign'),
      'COL_QTH' => $this->input->post('qth'),
      'COL_PROP_MODE' => $prop_mode,
      'COL_IOTA' => trim($this->input->post('iota_ref')),
      'COL_MY_GRIDSQUARE' => strtoupper($locator),
      'COL_DISTANCE' => "0",
      'COL_FREQ_RX' => $this->parse_frequency($this->input->post('freq_display_rx')),
      'COL_BAND_RX' => null,
      'COL_ANT_AZ' => null,
      'COL_ANT_EL' => null,
      'COL_A_INDEX' => null,
      'COL_AGE' => null,
      'COL_TEN_TEN' => null,
      'COL_TX_PWR' => null,
      'COL_STX' => null,
      'COL_SRX' => null,
      'COL_NR_BURSTS' => null,
      'COL_NR_PINGS' => null,
      'COL_MAX_BURSTS' => null,
      'COL_K_INDEX' => null,
      'COL_SFI' => null,
      'COL_RX_PWR' => null,
      'COL_LAT' => null,
      'COL_LON' => null,
      'COL_DXCC' => $this->input->post('dxcc_id'),
      'COL_CQZ' => $this->input->post('cqz'),
      'COL_SOTA_REF' => trim($this->input->post('sota_ref')),
      'COL_DARC_DOK' => trim($this->input->post('darc_dok')),
    );

    if (strpos(trim($this->input->post('locator')), ',') !== false) {
      $data['COL_VUCC_GRIDS'] = strtoupper(trim($this->input->post('locator')));
    } else {
      $data['COL_GRIDSQUARE'] = strtoupper(trim($this->input->post('locator')));
    }


    $this->add_qso($data);
  }

  /* Add QSO to Logbook */
  function create_qso() {
    // Join date+time
    $datetime = date("Y-m-d",strtotime($this->input->post('start_date')))." ". $this->input->post('start_time');

    if ($this->input->post('prop_mode') != null) {
            $prop_mode = $this->input->post('prop_mode');
    } else {
            $prop_mode = "";
    }

    if($this->input->post('sat_name')) {
        $prop_mode = "SAT";
    }

    if($this->session->userdata('user_locator')){
        $locator = $this->session->userdata('user_locator');
    } else {
        $locator = $this->config->item('locator');
    }

    if($this->input->post('country') == "") {
      $dxcc = $this->check_dxcc_table(strtoupper(trim($this->input->post('callsign'))), $datetime);
      $country = ucwords(strtolower($dxcc[1]));
    } else {
      $country = $this->input->post('country');
    }

    if($this->input->post('cqz') == "") {
      $dxcc = $this->check_dxcc_table(strtoupper(trim($this->input->post('callsign'))), $datetime);
      if (empty($dxcc[2])) {
        $cqz = null;
      } else {
        $cqz = $dxcc[2];
      }
    } else {
      $cqz = $this->input->post('cqz');
    }

    if($this->input->post('dxcc_id') == "") {
      
      $dxcc = $this->check_dxcc_table(strtoupper(trim($this->input->post('callsign'))), $datetime);
      if (empty($dxcc[0])) {
        $dxcc_id = null;
      } else {
       $dxcc_id = $dxcc[0];
      }

    } else {
      $dxcc_id = $this->input->post('dxcc_id');
    }

    // Create array with QSO Data
    $data = array(
            'COL_TIME_ON' => $datetime,
            'COL_TIME_OFF' => $datetime,
            'COL_CALL' => strtoupper(trim($this->input->post('callsign'))),
            'COL_BAND' => $this->input->post('band'),
            'COL_FREQ' => $this->parse_frequency($this->input->post('freq_display')),
            'COL_MODE' => $this->input->post('mode'),
            'COL_RST_RCVD' => $this->input->post('rst_recv'),
            'COL_RST_SENT' => $this->input->post('rst_sent'),
            'COL_NAME' => $this->input->post('name'),
            'COL_COMMENT' => $this->input->post('comment'),
            'COL_SAT_NAME' => strtoupper($this->input->post('sat_name')),
            'COL_SAT_MODE' => strtoupper($this->input->post('sat_mode')),
            'COL_COUNTRY' => $country,
            'COL_MY_RIG' => $this->input->post('equipment'),
            'COL_QSLSDATE' => date('Y-m-d'),
            'COL_QSLRDATE' => date('Y-m-d'),
            'COL_QSL_SENT' => $this->input->post('qsl_sent'),
            'COL_QSL_RCVD' => $this->input->post('qsl_recv'),
            'COL_QSL_SENT_VIA' => $this->input->post('qsl_sent_method'),
            'COL_QSL_RCVD_VIA' => $this->input->post('qsl_recv_method'),
            'COL_QSL_VIA' => $this->input->post('qsl_via'),
            'COL_OPERATOR' => $this->session->userdata('user_callsign'),
            'COL_QTH' => $this->input->post('qth'),
            'COL_PROP_MODE' => $prop_mode,
            'COL_IOTA' => trim($this->input->post('iota_ref')),
            'COL_MY_GRIDSQUARE' => $locator,
            'COL_DISTANCE' => "0",
            'COL_FREQ_RX' => $this->parse_frequency($this->input->post('freq_display_rx')),
            'COL_BAND_RX' => null,
            'COL_ANT_AZ' => null,
            'COL_ANT_EL' => null,
            'COL_A_INDEX' => null,
            'COL_AGE' => null,
            'COL_TEN_TEN' => null,
            'COL_TX_PWR' => null,
            'COL_STX' => null,
            'COL_SRX' => null,
            'COL_NR_BURSTS' => null,
            'COL_NR_PINGS' => null,
            'COL_MAX_BURSTS' => null,
            'COL_K_INDEX' => null,
            'COL_SFI' => null,
            'COL_RX_PWR' => null,
            'COL_LAT' => null,
            'COL_LON' => null,
            'COL_DXCC' => $dxcc_id,
            'COL_CQZ' => $cqz,
            'COL_SOTA_REF' => trim($this->input->post('sota_ref')),
			      'COL_DARC_DOK' => trim($this->input->post('darc_dok')),
    );

    // If station profile has been provided fill in the fields
    if($this->input->post('station_profile') != "0") {
      $station = $this->check_station($this->input->post('station_profile'));

      if (strpos(trim($station['station_gridsquare']), ',') !== false) {
        $data['COL_MY_VUCC_GRIDS'] = strtoupper(trim($station['station_gridsquare']));
      } else {
        $data['COL_MY_GRIDSQUARE'] = strtoupper(trim($station['station_gridsquare']));
      }

      $data['COL_MY_CITY'] = strtoupper(trim($station['station_city']));
      $data['COL_MY_IOTA'] = strtoupper(trim($station['station_iota']));
      $data['COL_MY_SOTA_REF'] = strtoupper(trim($station['station_sota']));
      
      $data['COL_STATION_CALLSIGN'] = strtoupper(trim($station['station_callsign']));
      $data['COL_MY_DXCC'] = strtoupper(trim($station['station_dxcc']));
      $data['COL_MY_COUNTRY'] = strtoupper(trim($station['station_country']));
      $data['COL_MY_CNTY'] = strtoupper(trim($station['station_cnty']));
      $data['COL_MY_CQ_ZONE'] = strtoupper(trim($station['station_cq']));
      $data['COL_MY_ITU_ZONE'] = strtoupper(trim($station['station_itu']));
    }

    // Decide whether its single gridsquare or a multi which makes it vucc_grids
    if (strpos(trim($this->input->post('locator')), ',') !== false) {
      $data['COL_VUCC_GRIDS'] = strtoupper(trim($this->input->post('locator')));
    } else {
      $data['COL_GRIDSQUARE'] = strtoupper(trim($this->input->post('locator')));
    }

    // if eQSL username set, default SENT & RCVD to 'N' else leave as null
    if ($this->session->userdata('user_eqsl_name')){
        $data['COL_EQSL_QSL_SENT'] = 'N';
        $data['COL_EQSL_QSL_RCVD'] = 'N';
    }

    // if LoTW username set, default SENT & RCVD to 'N' else leave as null
    if ($this->session->userdata('user_lotw_name')){
        $data['COL_LOTW_QSL_SENT'] = 'N';
        $data['COL_LOTW_QSL_RCVD'] = 'N';
    }

    $this->add_qso($data);
  }

  public function check_station($id){

    $this->db->where('station_id', $id); 
    $query = $this->db->get('station_profile');

    if ($query->num_rows() > 0) {
      $row = $query->row_array();
        return($row);
    }
  }

  public function get_callsigns($callsign){
    $this->db->select('COL_CALL');
    $this->db->distinct();
    $this->db->like('COL_CALL', $callsign);

    $query = $this->db->get($this->config->item('table_name'));

    $result = "";

    foreach ($query->result() as $row)
    {

      $result = $result." ".$row->COL_CALL;

    }

    return $result;

  }

  function add_qso($data) {

    if ($data['COL_DXCC'] == "Not Found"){
      $data['COL_DXCC'] = NULL;
    }

    if (!is_null($data['COL_RX_PWR'])) {
      $data['COL_RX_PWR'] = str_replace("W", "", $data['COL_RX_PWR']);
    }

    // Add QSO to database
    $this->db->insert($this->config->item('table_name'), $data);
  }

  /* Edit QSO */
  function edit() {

    $data = array(
       'COL_TIME_ON' => $this->input->post('time_on'),
       'COL_TIME_OFF' => $this->input->post('time_off'),
       'COL_CALL' => strtoupper(trim($this->input->post('callsign'))),
       'COL_BAND' => $this->input->post('band'),
       'COL_FREQ' => $this->parse_frequency($this->input->post('freq')),
       'COL_MODE' => $this->input->post('mode'),
       'COL_RST_RCVD' => $this->input->post('rst_recv'),
       'COL_RST_SENT' => $this->input->post('rst_sent'),
       'COL_GRIDSQUARE' => strtoupper(trim($this->input->post('locator'))),
       'COL_VUCC_GRIDS' => strtoupper(trim($this->input->post('vucc_grids'))),
       'COL_COMMENT' => $this->input->post('comment'),
       'COL_NAME' => $this->input->post('name'),
       'COL_COUNTRY' => $this->input->post('country'),
       'COL_SAT_NAME' => $this->input->post('sat_name'),
       'COL_SAT_MODE' => $this->input->post('sat_mode'),
       'COL_QSLSDATE' => date('Y-m-d'),
       'COL_QSLRDATE' => date('Y-m-d'),
       'COL_QSL_SENT' => $this->input->post('qsl_sent'),
       'COL_QSL_RCVD' => $this->input->post('qsl_recv'),
       'COL_QSL_SENT_VIA' => $this->input->post('qsl_sent_method'),
       'COL_QSL_RCVD_VIA' => $this->input->post('qsl_recv_method'),
       'COL_EQSL_QSL_SENT' => $this->input->post('eqsl_sent'),
       'COL_EQSL_QSL_RCVD' => $this->input->post('eqsl_recv'),
       'COL_LOTW_QSL_SENT' => $this->input->post('lotw_sent'),
       'COL_LOTW_QSL_RCVD' => $this->input->post('lotw_recv'),
       'COL_IOTA' => $this->input->post('iota_ref'),
       'COL_SOTA_REF' => $this->input->post('sota_ref'),
       'COL_DARC_DOK' => $this->input->post('darc_dok'),
       'COL_QTH' => $this->input->post('qth'),
       'COL_PROP_MODE' => $this->input->post('prop_mode'),
       'COL_FREQ_RX' => $this->parse_frequency($this->input->post('freq_display_rx')),
       'COL_STX_STRING' => $this->input->post('stx_string'),
       'COL_SRX_STRING' => $this->input->post('srx_string'),
       'COL_QSL_VIA' => $this->input->post('qsl_via_callsign'),
       'COL_MY_COUNTRY' => (!empty($this->input->post('station_country'))) ? $this->input->post('station_country') : null,
       'COL_MY_GRIDSQUARE' => (!empty($this->input->post('station_gridsquare'))) ? $this->input->post('station_gridsquare') : null,
       'COL_MY_CITY' => (!empty($this->input->post('station_city'))) ? $this->input->post('station_city') : null,
       'COL_MY_IOTA' => (!empty($this->input->post('station_iota'))) ? $this->input->post('station_iota') : null,
       'COL_MY_SOTA_REF' => (!empty($this->input->post('station_sota'))) ? $this->input->post('station_sota') : null,
       'COL_MY_CNTY' => (!empty($this->input->post('station_cnty'))) ? $this->input->post('station_cnty') : null,
       'COL_MY_CQ_ZONE' => (!empty($this->input->post('station_cq'))) ? $this->input->post('station_cq') : null,
       'COL_MY_ITU_ZONE' => (!empty($this->input->post('station_itu'))) ? $this->input->post('station_itu') : null,
    );

    $this->db->where('COL_PRIMARY_KEY', $this->input->post('id'));
    $this->db->update($this->config->item('table_name'), $data);

  }


  /* QSL received */
  function qsl_rcvd() {

    $data = array(
       'COL_QSLRDATE' => date('Y-m-d'),
       'COL_QSL_RCVD' => "Y"
    );

    $this->db->where('COL_PRIMARY_KEY', $this->input->post('id'));
    $this->db->update($this->config->item('table_name'), $data);

  }

  /* Return last 10 QSOs */
  function last_ten() {
    $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
    $this->db->order_by("COL_TIME_ON", "desc");
    $this->db->limit(10);

    return $this->db->get($this->config->item('table_name'));
  }

  /* Show custom number of qsos */
  function last_custom($num) {
    $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
    $this->db->order_by("COL_TIME_ON", "desc");
    $this->db->limit($num);

    return $this->db->get($this->config->item('table_name'));
  }

    /* Callsign QRA */

	function call_qra($callsign) {
		$this->db->select('COL_CALL, COL_GRIDSQUARE, COL_TIME_ON');
		$this->db->where('COL_CALL', $callsign);
		$where = "COL_GRIDSQUARE != \"\"";

		$this->db->where($where);

		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit(1);
		$query = $this->db->get($this->config->item('table_name'));
		$callsign = "";
		if ($query->num_rows() > 0)
		{
			$data = $query->row();
			$callsign = strtoupper($data->COL_GRIDSQUARE);
		}

		return $callsign;
	}

	function call_name($callsign) {
		$this->db->select('COL_CALL, COL_NAME, COL_TIME_ON');
		$this->db->where('COL_CALL', $callsign);
		$where = "COL_NAME != \"\"";

		$this->db->where($where);

		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit(1);
		$query = $this->db->get($this->config->item('table_name'));
		$name = "";
		if ($query->num_rows() > 0)
		{
			$data = $query->row();
			$name = $data->COL_NAME;
		}

		return $name;
	}

  function call_qslvia($callsign) {
    $this->db->select('COL_CALL, COL_QSL_VIA, COL_TIME_ON');
    $this->db->where('COL_CALL', $callsign);
    $where = "COL_NAME != \"\"";

    $this->db->where($where);

    $this->db->order_by("COL_TIME_ON", "desc");
    $this->db->limit(1);
    $query = $this->db->get($this->config->item('table_name'));
    $name = "";
    if ($query->num_rows() > 0)
    {
      $data = $query->row();
      $qsl_via = $data->COL_QSL_VIA;
    }

    return $qsl_via;
  }

	function call_qth($callsign) {
		$this->db->select('COL_CALL, COL_QTH, COL_TIME_ON');
		$this->db->where('COL_CALL', $callsign);
		$where = "COL_QTH != \"\"";

		$this->db->where($where);

		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit(1);
		$query = $this->db->get($this->config->item('table_name'));
		$name = "";
		if ($query->num_rows() > 0)
		{
			$data = $query->row();
			$name = $data->COL_QTH;
		}

		return $name;
	}

	function call_iota($callsign) {
		$this->db->select('COL_CALL, COL_IOTA, COL_TIME_ON');
		$this->db->where('COL_CALL', $callsign);
		$where = "COL_IOTA != \"\"";

		$this->db->where($where);

		$this->db->order_by("COL_TIME_ON", "desc");
		$this->db->limit(1);
		$query = $this->db->get($this->config->item('table_name'));
		$name = "";
		if ($query->num_rows() > 0)
		{
			$data = $query->row();
			$name = $data->COL_IOTA;
		}

		return $name;
	}
  /* Return QSO Info */
  function qso_info($id) {
    $this->db->where('COL_PRIMARY_KEY', $id);

    return $this->db->get($this->config->item('table_name'));
  }


  // Set Paper to recived
  function paperqsl_update($qso_id, $method) {
    $data = array(
         'COL_QSLRDATE' => date('Y-m-d'),
         'COL_QSL_RCVD' => 'Y',
         'COL_QSL_RCVD_VIA' => $method
    );

    $this->db->where('COL_PRIMARY_KEY', $qso_id);

    $this->db->update($this->config->item('table_name'), $data);
  }

  function get_qsos_for_printing() {
    $query = $this->db->query('SELECT COL_PRIMARY_KEY, COL_CALL, COL_QSL_VIA, COL_TIME_ON, COL_MODE, COL_FREQ, UPPER(COL_BAND) as COL_BAND, COL_RST_SENT, COL_SAT_NAME, COL_SAT_MODE, COL_QSL_RCVD, (CASE WHEN COL_QSL_VIA != \'\' THEN COL_QSL_VIA ELSE COL_CALL END) AS COL_ROUTING, ADIF, ENTITY FROM '.$this->config->item('table_name').', dxcc_prefixes WHERE COL_QSL_SENT LIKE \'R\' and (CASE WHEN COL_QSL_VIA != \'\' THEN COL_QSL_VIA ELSE COL_CALL END) like CONCAT(dxcc_prefixes.call,\'%\') and (end is null or end > now()) ORDER BY adif, col_routing');
    return $query;
  }

  function get_qsos($num, $offset) {
    $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME, COL_GRIDSQUARE, COL_QSL_RCVD, COL_EQSL_QSL_RCVD, COL_EQSL_QSL_SENT, COL_QSL_SENT, COL_STX, COL_STX_STRING, COL_SRX, COL_SRX_STRING, COL_OPERATOR, COL_STATION_CALLSIGN, COL_LOTW_QSL_SENT, COL_LOTW_QSL_RCVD, COL_VUCC_GRIDS');
    $this->db->order_by("COL_TIME_ON", "desc");

    $query = $this->db->get($this->config->item('table_name'), $num, $offset);

    return $query;
  }

  function get_clublog_qsos(){
    $this->db->where("COL_CLUBLOG_QSO_UPLOAD_STATUS", null);
    $this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "");
    $this->db->or_where("COL_CLUBLOG_QSO_UPLOAD_STATUS", "N");

    $query = $this->db->get($this->config->item('table_name'));

    return $query;
  }

  function get_last_qsos($num) {
    $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME, COL_STX_STRING, COL_SRX_STRING');
    $this->db->order_by("COL_TIME_ON", "desc");
    $this->db->limit($num);
    $query = $this->db->get($this->config->item('table_name'));

    return $query;
  }

    /* Get All QSOs with a Valid Grid */
    function kml_get_all_qsos() {
        $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME, COL_GRIDSQUARE');
        $this->db->where('COL_GRIDSQUARE != \'null\'');
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    function get_date_qsos($date) {
        $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
        $this->db->order_by("COL_TIME_ON", "desc");
        $start = $date." 00:00:00";
        $end = $date." 23:59:59";

        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

  function get_todays_qsos() {
    $morning = date('Y-m-d 00:00:00');
    $night = date('Y-m-d 23:59:59');
    $query = $this->db->query('SELECT * FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');
    return $query;
  }

  function totals_year() {
    $query = $this->db->query('
    SELECT DATE_FORMAT(COL_TIME_ON, \'%Y\') as \'year\',
    COUNT(COL_PRIMARY_KEY) as \'total\'
    FROM '.$this->config->item('table_name').'
    GROUP BY DATE_FORMAT(COL_TIME_ON, \'%Y\')
    ');
    return $query;
  }

    /* Return total number of qsos */
     function total_qsos() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return number of QSOs had today */
    function todays_qsos() {

        $morning = date('Y-m-d 00:00:00');
        $night = date('Y-m-d 23:59:59');
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return QSOs over a period of days */
    function map_week_qsos($start, $end) {

        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $this->db->order_by("COL_TIME_ON", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    /* Returns QSOs for the date sent eg 2011-09-30 */
    function map_day($date) {

        $start = $date." 00:00:00";
        $end = $date." 23:59:59";

        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $this->db->order_by("COL_TIME_ON", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    // Return QSOs made during the current month
    function month_qsos() {

        $morning = date('Y-m-01 00:00:00');
        $night = date('Y-m-30 23:59:59');
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return QSOs made during the current Year */
    function year_qsos() {

        $morning = date('Y-01-01 00:00:00');
        $night = date('Y-12-31 23:59:59');
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_TIME_ON between \''.$morning.'\' AND \''.$night.'\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return total amount of SSB QSOs logged */
    function total_ssb() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE = \'SSB\' OR COL_MODE = \'LSB\' OR COL_MODE = \'USB\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

   /* Return total number of satellite QSOs */
   function total_sat() {
        $query = $this->db->query('SELECT COL_SAT_NAME, COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_SAT_NAME != \'null\' GROUP BY COL_SAT_NAME');

        return $query;
    }

    /* Return total number of CW QSOs */
    function total_cw() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE = \'CW\' ');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return total number of FM QSOs */
    function total_fm() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE = \'FM\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return total number of Digital QSOs */
    function total_digi() {
        $query = $this->db->query('SELECT COUNT( * ) as count FROM '.$this->config->item('table_name').' WHERE COL_MODE != \'SSB\' AND COL_MODE != \'LSB\' AND COL_MODE != \'USB\' AND COL_MODE != \'CW\' AND COL_MODE != \'FM\' AND COL_MODE != \'AM\'');

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
    }

    /* Return the list of modes in the logbook */
    function get_modes(){
        $query = $this->db->query('select distinct(COL_MODE) from '.$this->config->item('table_name').' order by COL_MODE');
        return $query;
    }

    /* Return total number of QSOs per band */
   function total_bands() {
        $query = $this->db->query('SELECT DISTINCT (COL_BAND) AS band, count( * ) AS count FROM '.$this->config->item('table_name').' GROUP BY band ORDER BY count DESC');

        return $query;
    }

    /* Return total number of QSL Cards sent */
    function total_qsl_sent() {
        $query = $this->db->query('SELECT DISTINCT (COL_QSL_SENT) AS band, count(COL_QSL_SENT) AS count FROM '.$this->config->item('table_name').' WHERE COL_QSL_SENT = "Y" GROUP BY band');

        $row = $query->row();

        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
    }

    /* Return total number of QSL Cards requested */
    function total_qsl_requested() {
        $query = $this->db->query('SELECT DISTINCT (COL_QSL_SENT) AS band, count(COL_QSL_SENT) AS count FROM '.$this->config->item('table_name').' WHERE COL_QSL_SENT = "R" GROUP BY band');

        $row = $query->row();

        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
    }

    /* Return total number of QSL Cards received */
    function total_qsl_recv() {
        $query = $this->db->query('SELECT DISTINCT (COL_QSL_RCVD) AS band, count(COL_QSL_RCVD) AS count FROM '.$this->config->item('table_name').' WHERE COL_QSL_RCVD = "Y" GROUP BY band');

        $row = $query->row();

        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
    }

    /* Return total number of countrys worked */
    function total_countrys() {
        $query = $this->db->query('SELECT DISTINCT (COL_COUNTRY) FROM '.$this->config->item('table_name').'');

        return $query->num_rows();
    }

  function api_search_query($query) {
    $time_start = microtime(true);
    $results = $this->db->query($query);
    if(!$results) {
      return array('query' => $query, 'error' => $this->db->_error_number(), 'time' => 0);
    }
    $time_end = microtime(true);
    $time = round($time_end - $time_start, 4);

    return array('query' => $query, 'results' => $results, 'time' => $time);
  }

  function api_insert_query($query) {
    $time_start = microtime(true);
    $results = $this->db->insert($this->config->item('table_name'), $query);
    if(!$results) {
      return array('query' => $query, 'error' => $this->db->_error_number(), 'time' => 0);
    }
    $time_end = microtime(true);
    $time = round($time_end - $time_start, 4);

    return array('query' => $this->db->queries[2], 'result_string' => $results, 'time' => $time);
  }

    /* Delete QSO based on the QSO ID */
    function delete($id) {
        $this->db->where('COL_PRIMARY_KEY', $id);
        $this->db->delete($this->config->item('table_name'));
    }

  /* Used to check if the qso is already in the database */
    function import_check($datetime, $callsign, $band) {

    $this->db->select('COL_TIME_ON, COL_CALL, COL_BAND');
    $this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -15 MINUTE )');
    $this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 15 MINUTE )');
    $this->db->where('COL_CALL', $callsign);
    $this->db->where('COL_BAND', $band);

    $query = $this->db->get($this->config->item('table_name'));

    if ($query->num_rows() > 0)
    {
      return "Found";
    } else {
      return "No Match";
    }
  }

  function lotw_update($datetime, $callsign, $band, $qsl_date, $qsl_status) {
    $data = array(
         'COL_LOTW_QSLRDATE' => $qsl_date,
         'COL_LOTW_QSL_RCVD' => $qsl_status,
         'COL_LOTW_QSL_SENT' => 'Y'
    );

    $this->db->where('date_format(COL_TIME_ON, \'%Y-%m-%d %H:%i\') = "'.$datetime.'"');
    $this->db->where('COL_CALL', $callsign);
    $this->db->where('COL_BAND', $band);

    $this->db->update($this->config->item('table_name'), $data);

    return "Updated";
  }

  function lotw_last_qsl_date() {
      $this->db->select('COL_LOTW_QSLRDATE');
      $this->db->where('COL_LOTW_QSLRDATE IS NOT NULL');
      $this->db->order_by("COL_LOTW_QSLRDATE", "desc");
      $this->db->limit(1);

      $query = $this->db->get($this->config->item('table_name'));
      $row = $query->row();

      return $row->COL_LOTW_QSLRDATE;
    }

//////////////////////////////
  // Update a QSO with eQSL QSL info
  // We could also probably use this use this: http://eqsl.cc/qslcard/VerifyQSO.txt
  // http://www.eqsl.cc/qslcard/ImportADIF.txt
  function eqsl_update($datetime, $callsign, $band, $qsl_status) {
    $data = array(
         'COL_EQSL_QSLRDATE' => date('Y-m-d'), // eQSL doesn't give us a date, so let's use current
         'COL_EQSL_QSL_RCVD' => $qsl_status
    );

    $this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -15 MINUTE )');
    $this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 15 MINUTE )');
    $this->db->where('COL_CALL', $callsign);
    $this->db->where('COL_BAND', $band);

    $this->db->update($this->config->item('table_name'), $data);

    return "Updated";
  }

  // Mark the QSO as sent to eQSL
  function eqsl_mark_sent($primarykey) {
    $data = array(
         'COL_EQSL_QSLSDATE' => date('Y-m-d'), // eQSL doesn't give us a date, so let's use current
         'COL_EQSL_QSL_SENT' => 'Y',
    );

    $this->db->where('COL_PRIMARY_KEY', $primarykey);

    $this->db->update($this->config->item('table_name'), $data);

    return "eQSL Sent";
  }

  // Get the last date we received an eQSL
  function eqsl_last_qsl_rcvd_date() {
      $this->db->select("DATE_FORMAT(COL_EQSL_QSLRDATE,'%Y%m%d') AS COL_EQSL_QSLRDATE", FALSE);
      $this->db->where('COL_EQSL_QSLRDATE IS NOT NULL');
      $this->db->order_by("COL_EQSL_QSLRDATE", "desc");
      $this->db->limit(1);

      $query = $this->db->get($this->config->item('table_name'));
      $row = $query->row();

      if (isset($row->COL_EQSL_QSLDATE)){
          return $row->COL_EQSL_QSLRDATE;
        }else{
            // No previous date (first time import has run?), so choose UNIX EPOCH!
            // Note: date is yyyy/mm/dd format
            return '1970/01/01';
        }
    }

    // Determine if we've already received an eQSL for this QSO
    function eqsl_dupe_check($datetime, $callsign, $band, $qsl_status) {
      $this->db->select('COL_EQSL_QSLRDATE');
      $this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -15 MINUTE )');
    $this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 15 MINUTE )');
      $this->db->where('COL_CALL', $callsign);
      $this->db->where('COL_BAND', $band);
      $this->db->where('COL_EQSL_QSL_RCVD', $qsl_status);
      $this->db->limit(1);

      $query = $this->db->get($this->config->item('table_name'));
      $row = $query->row();

      if ($row != null)
      {
        return true;
      }
      else
      {
        return false;
      }
    }

    // Show all QSOs we need to send to eQSL
    function eqsl_not_yet_sent() {
      $this->db->select("COL_PRIMARY_KEY, COL_TIME_ON, COL_CALL, COL_MODE, COL_BAND, COL_COMMENT, COL_RST_SENT, COL_PROP_MODE");
      $this->db->where('COL_EQSL_QSL_SENT !=', 'Y');
      $this->db->where('COL_EQSL_QSL_SENT !=', 'I');

      return $this->db->get($this->config->item('table_name'));
    }

    function import($record, $station_id = "0") {
        $CI =& get_instance();
        $CI->load->library('frequency');


        // Join date+time

        $time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));

        if (isset($record['time_off'])) {
            $time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_off']));
        } else {
           $time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));
        }

        // Store Freq
        // Check if 'freq' is defined in the import?
        if (isset($record['freq'])){
            $cleanfreq = preg_replace('#\W#', '', $record['freq']);
            $freqlng = strlen($cleanfreq);
        }else{
            $freqlng = 0;
        }

        if(isset($record['freq']) && $freqlng < 7 ) {
            $cleansedstring = preg_replace('#\W#', '', $record['freq']);
            $freq = $cleansedstring."0";
        } elseif($freqlng >= 7) {
            $cleansedstring = preg_replace('#\W#', '', $record['freq']);
            $freq = $cleansedstring;
        } else {
            $freq = "0";
        }


        // Check for RX Freq
        // Check if 'freq' is defined in the import?
        if (isset($record['freq_rx'])){
            $cleanfreqRX = preg_replace('#\W#', '', $record['freq_rx']);
            $freqlngRX = strlen($cleanfreqRX);
        }else{
            $freqlngRX = 0;
        }

        if(isset($record['freq_rx']) && $freqlngRX < 7 ) {
            $cleansedstringRX = preg_replace('#\W#', '', $record['freq_rx']);
            $freqRX = $cleansedstringRX."0";
        } elseif($freqlngRX >= 7) {
            $cleansedstringRX = preg_replace('#\W#', '', $record['freq_rx']);
            $freqRX = $cleansedstringRX;
        } else {
            $freqRX = NULL;
        }

        // DXCC id
        if (isset($record['call'])){
          $dxcc = $this->check_dxcc_table($record['call'], $time_off);
        } else {
          $dxcc = NULL;
        }

        // Store or find country name
        if(isset($record['country'])) {
            $country = $record['country'];
        } else {
            $country = ucwords(strtolower($dxcc[1]));
        }


        // RST recevied
        if(isset($record['rst_rcvd'])) {
                $rst_rx = $record['rst_rcvd'];
        } else {
                $rst_rx = "59";
        }

        // RST Sent
        if(isset($record['rst_sent'])) {
                $rst_tx = $record['rst_sent'];
        } else {
                $rst_tx = "59";
        }

        // Store Band
        if(isset($record['band'])) {
                $band = $record['band'];
        } else {
            if (isset($record['freq'])){
              if($freq != "0") {
                $band = $CI->frequency->GetBand($freq);
              }
            }
        }


        if(isset($record['band_rx'])) {
                $band_rx = $record['band_rx'];
        } else {
                if (isset($record['freq_rx'])){
                  if($freq != "0") {
                    $band_rx = $CI->frequency->GetBand($freqRX);
                  }
                } else {
                  $band_rx = "";
                }
        }

        if(isset($record['CQZ'])) {
          $cq_zone = $record['CQZ'];
        } elseif(isset($dxcc[2])) {
          $cq_zone = $dxcc[2];
        } else {
          //$cq_zone = "";
          $cq_zone = NULL;
        }

        if (isset($record['call'])){
          $this->db->where('COL_CALL', $record['call']);
        }
        $this->db->where('COL_TIME_ON', $time_on);
        $check = $this->db->get($this->config->item('table_name'));
        
        if ($check->num_rows() <= 0)
        {
            // Create array with QSO Data use ?:
            $data = array(
                'COL_A_INDEX' => (!empty($record['a_index'])) ? $record['a_index']: null,
                'COL_ADDRESS' => (!empty($record['address'])) ? $record['address'] : '',
                'COL_ADDRESS_INTL' => (!empty($record['address_intl'])) ? $record['address_intl'] : '',
                'COL_AGE' => (!empty($record['age'])) ? $record['age'] : null,
                'COL_ANT_AZ' => (!empty($record['ant_az'])) ? $record['ant_az'] : null,
                'COL_ANT_EL' => (!empty($record['ant_el'])) ? $record['ant_el'] : null,
                'COL_ANT_PATH' => (!empty($record['ant_path'])) ? $record['ant_path'] : '',
                'COL_ARRL_SECT' => (!empty($record['arrl_sect'])) ? $record['arrl_sect'] : '',
                'COL_AWARD_GRANTED' => (!empty($record['award_granted'])) ? $record['award_granted'] : '',
                'COL_AWARD_SUMMITED' => (!empty($record['award_submitted'])) ? $record['award_submitted'] : '',
                'COL_BAND' => $band,
                'COL_BAND_RX' => $band_rx,
                'COL_BIOGRAPHY' => (!empty($record['biography'])) ? $record['biography'] : '',
                'COL_CALL' => (!empty($record['call'])) ? strtoupper($record['call']) : '',
                'COL_CHECK' => (!empty($record['check'])) ? $record['check'] : '',
                'COL_CLASS' => (!empty($record['class'])) ? $record['class'] : '',
                'COL_CLUBLOG_QSO_UPLOAD_DATE' => (!empty($record['clublog_qso_upload_date'])) ? $record['clublog_qso_upload_date'] : null,
                'COL_CLUBLOG_QSO_UPLOAD_STATUS' => (!empty($record['clublog_qso_upload_status'])) ? $record['clublog_qso_upload_status'] : null,
                'COL_CNTY' => (!empty($record['cnty'])) ? $record['cnty'] : '',
                'COL_COMMENT' => (!empty($record['comment'])) ? $record['comment'] : '',
                'COL_COMMENT_INTL' => (!empty($record['comment_intl'])) ? $record['comment_intl'] : '',
                'COL_CONT' => (!empty($record['cont'])) ? $record['cont'] : '',
                'COL_CONTACTED_OP' => (!empty($record['contacted_op'])) ? $record['contacted_op'] : '',
                'COL_CONTEST_ID' => (!empty($record['contest_id'])) ? $record['contest_id'] : '',
                'COL_COUNTRY' => $country,
                'COL_COUNTRY_INTL' => (!empty($record['country_intl'])) ? $record['country_intl'] : '',
                'COL_CQZ' => $cq_zone,
                'COL_CREDIT_GRANTED' => (!empty($record['credit_granted'])) ? $record['credit_granted'] : '',
                'COL_CREDIT_SUBMITTED' => (!empty($record['credit_submitted'])) ? $record['credit_submitted'] : '',
                'COL_DARC_DOK' => (!empty($record['darc_dok'])) ? $record['darc_dok'] : '',
                'COL_DISTANCE' => (!empty($record['distance'])) ? $record['distance'] : null,
                'COL_DXCC' => $dxcc[0],
                'COL_EMAIL' => (!empty($record['email'])) ? $record['email'] : '',
                'COL_EQ_CALL' => (!empty($record['eq_call'])) ? $record['eq_call'] : '',
                'COL_EQSL_QSL_RCVD' => (!empty($record['eqsl_qsl_rcvd'])) ? $record['eqsl_qsl_rcvd'] : null,
                'COL_EQSL_QSL_SENT' => (!empty($record['eqsl_qsl_sent'])) ? $record['eqsl_qsl_sent'] : null,
                'COL_EQSL_QSLRDATE' => (!empty($record['eqsl_qslrdate'])) ? $record['eqsl_qslrdate'] : null,
                'COL_EQSL_QSLSDATE' => (!empty($record['eqsl_qslsdate'])) ? $record['eqsl_qslsdate'] : null,
                'COL_EQSL_STATUS' => (!empty($record['eqsl_status'])) ? $record['eqsl_status'] : '',
                'COL_FISTS' => (!empty($record['fists'])) ? $record['fists'] : null,
                'COL_FISTS_CC' => (!empty($record['fists_cc'])) ? $record['fists_cc'] : null,
                'COL_FORCE_INIT' => (!empty($record['force_init'])) ? $record['force_init'] : null,
                'COL_FREQ' => $freq,
                'COL_FREQ_RX' => (!empty($record['freq_rx'])) ? $freqRX : null,
                'COL_GRIDSQUARE' => (!empty($record['gridsquare'])) ? $record['gridsquare'] : '',
                'COL_HEADING' => (!empty($record['heading'])) ? $record['heading'] : null,
                'COL_HRDLOG_QSO_UPLOAD_DATE' => (!empty($record['hrdlog_qso_upload_date'])) ? $record['hrdlog_qso_upload_date'] : null,
                'COL_HRDLOG_QSO_UPLOAD_STATUS' => (!empty($record['hrdlog_qso_upload_status'])) ? $record['hrdlog_qso_upload_status'] : '',
                'COL_IOTA' => (!empty($record['iota'])) ? $record['iota'] : '',
                'COL_ITUZ' => (!empty($record['ituz'])) ? $record['ituz'] : null,
                'COL_K_INDEX' => (!empty($record['k_index'])) ? $record['k_index'] : null,
                'COL_LAT' => (!empty($record['lat'])) ? $record['lat'] : null,
                'COL_LON' => (!empty($record['lon'])) ? $record['lon'] : null,
                'COL_LOTW_QSL_RCVD' => (!empty($record['lotw_qsl_rcvd'])) ? $record['lotw_qsl_rcvd'] : '',
                'COL_LOTW_QSL_SENT' => (!empty($record['lotw_qsl_sent'])) ? $record['lotw_qsl_sent'] : '',
                'COL_LOTW_QSLRDATE' => (!empty($record['lotw_qslrdate'])) ? $record['lotw_qslrdate'] : null,
                'COL_LOTW_QSLSDATE' => (!empty($record['lotw_qslsdate'])) ? $record['lotw_qslsdate'] : null,
                'COL_LOTW_STATUS' => (!empty($record['lotw_status'])) ? $record['lotw_status'] : '',
                'COL_MAX_BURSTS' => (!empty($record['max_bursts'])) ? $record['max_bursts'] : null,
                'COL_MODE' => (!empty($record['mode'])) ? $record['mode'] : '',
                'COL_MS_SHOWER' => (!empty($record['ms_shower'])) ? $record['ms_shower'] : '',
                'COL_MY_ANTENNA' => (!empty($record['my_antenna'])) ? $record['my_antenna'] : '',
                'COL_MY_ANTENNA_INTL' => (!empty($record['my_antenna_intl'])) ? $record['my_antenna_intl'] : '',
                'COL_MY_CITY' => (!empty($record['my_city'])) ? $record['my_city'] : '',
                'COL_MY_CITY_INTL' => (!empty($record['my_city_intl'])) ? $record['my_city_intl'] : '',
                'COL_MY_CNTY' => (!empty($record['my_cnty'])) ? $record['my_cnty'] : '',
                'COL_MY_COUNTRY' => (!empty($record['my_country'])) ? $record['my_country'] : '',
                'COL_MY_COUNTRY_INTL' => (!empty($record['my_country_intl'])) ? $record['my_country_intl'] : null,
                'COL_MY_CQ_ZONE' => (!empty($record['my_dxcc'])) ? $record['my_dxcc'] : null,
                'COL_MY_DXCC' => (!empty($record['my_dxcc'])) ? $record['my_dxcc'] : null,
                'COL_MY_FISTS' => (!empty($record['my_fists'])) ? $record['my_fists'] : null,
                'COL_MY_GRIDSQUARE' => (!empty($record['my_gridsquare'])) ? $record['my_gridsquare'] : '',
                'COL_MY_IOTA' => (!empty($record['my_iota'])) ? $record['my_iota'] : '',
                'COL_MY_IOTA_ISLAND_ID' => (!empty($record['my_iota_island_id'])) ? $record['my_iota_island_id'] : '',
                'COL_MY_ITU_ZONE' => (!empty($record['my_itu_zone'])) ? $record['my_itu_zone'] : null,
                'COL_MY_LAT' => (!empty($record['my_lat'])) ? $record['my_lat'] : null,
                'COL_MY_LON' => (!empty($record['my_lon'])) ? $record['my_lon'] : null,
                'COL_MY_NAME' => (!empty($record['my_name'])) ? $record['my_name'] : '',
                'COL_MY_NAME_INTL' => (!empty($record['my_name_intl'])) ? $record['my_name_intl'] : '',
                'COL_MY_POSTAL_CODE' => (!empty($record['my_postal_code'])) ? $record['my_postal_code'] : '',
                'COL_MY_POSTCODE_INTL' => (!empty($record['my_postcode_intl'])) ? $record['my_postcode_intl'] : '',
                'COL_MY_RIG' => (!empty($record['my_rig'])) ? $record['my_rig'] : '',
                'COL_MY_RIG_INTL' => (!empty($record['my_rig_intl'])) ? $record['my_rig_intl'] : '',
                'COL_MY_SIG' => (!empty($record['my_sig'])) ? $record['my_sig'] : '',
                'COL_MY_SIG_INFO' => (!empty($record['my_sig_info'])) ? $record['my_sig_info'] : '',
                'COL_MY_SIG_INFO_INTL' => (!empty($record['my_sig_info_intl'])) ? $record['my_sig_info_intl'] : '',
                'COL_MY_SIG_INTL' => (!empty($record['my_sig_intl'])) ? $record['my_sig_intl'] : '',
                'COL_MY_SOTA_REF' => (!empty($record['my_sota_ref'])) ? $record['my_sota_ref'] : '',
                'COL_MY_STATE' => (!empty($record['my_state'])) ? $record['my_state'] : '',
                'COL_MY_STREET' =>  (!empty($record['my_street'])) ? $record['my_street'] : '',
                'COL_MY_STREET_INTL' => (!empty($record['my_street_intl'])) ? $record['my_street_intl'] : '',
                'COL_MY_USACA_COUNTIES' => (!empty($record['my_usaca_counties'])) ? $record['my_usaca_counties'] : '',
                'COL_MY_VUCC_GRIDS' => (!empty($record['my_vucc_grids'])) ? $record['my_vucc_grids'] : '',
                'COL_NAME' => (!empty($record['name'])) ? $record['name'] : '',
                'COL_NAME_INTL' => (!empty($record['name_intl'])) ? $record['name_intl']: '',
                'COL_NOTES' => (!empty($record['notes'])) ? $record['notes'] : '',
                'COL_NOTES_INTL' => (!empty($record['notes_intl'])) ? $record['notes_intl'] : '',
                'COL_NR_BURSTS' => (!empty($record['nr_bursts'])) ? $record['nr_bursts'] : null,
                'COL_NR_PINGS' => (!empty($record['nr_pings'])) ? $record['nr_pings'] : null,
                'COL_OPERATOR' => (!empty($record['operator'])) ? $record['operator'] : '',
                'COL_OWNER_CALLSIGN' => (!empty($record['owner_callsign'])) ? $record['owner_callsign'] : '',
                'COL_PFX' => (!empty($record['pfx'])) ? $record['pfx'] : '',
                'COL_PRECEDENCE' => (!empty($record['precedence'])) ? $record['precedence'] : '',
                'COL_PROP_MODE' => (!empty($record['prop_mode'])) ? $record['prop_mode'] : '',
                'COL_PUBLIC_KEY' => (!empty($record['public_key'])) ? $record['public_key'] : '',
                'COL_QRZCOM_QSO_UPLOAD_DATE' => (!empty($record['qrzcom_qso_upload_date'])) ? $record['qrzcom_qso_upload_date'] : null,
                'COL_QRZCOM_QSO_UPLOAD_STATUS' => (!empty($record['qrzcom_qso_upload_status'])) ? $record['qrzcom_qso_upload_status'] : '',
                'COL_QSL_RCVD' => (!empty($record['qsl_rcvd'])) ? $record['qsl_rcvd'] : '',
                'COL_QSL_RCVD_VIA' => (!empty($record['qsl_rcvd_via'])) ? $record['qsl_rcvd_via'] : '',
                'COL_QSL_SENT' => (!empty($record['qsl_sent'])) ? $record['qsl_sent'] : '',
                'COL_QSL_SENT_VIA' => (!empty($record['qsl_sent_via'])) ? $record['qsl_sent_via'] : '',
                'COL_QSL_VIA' => (!empty($record['qsl_via'])) ? $record['qsl_via'] : '',
                'COL_QSLMSG' => (!empty($record['qslmsg'])) ? $record['qslmsg'] : '',
                'COL_QSLRDATE' => (!empty($record['qslrdate'])) ? $record['qslrdate']: null,
                'COL_QSLSDATE' => (!empty($record['qslsdate'])) ? $record['qslsdate'] : null,
                'COL_QSO_COMPLETE' => (!empty($record['qso_complete'])) ? $record['qso_complete'] : '',
                'COL_QSO_DATE' => (!empty($record['qso_date'])) ? $record['qso_date'] : null,
                'COL_QSO_DATE_OFF' => (!empty($record['qso_date_off'])) ? $record['qso_date_off'] : null,
                'COL_QSO_RANDOM' => (!empty($record['qso_random'])) ? $record['qso_random'] : null,
                'COL_QTH' => (!empty($record['qth'])) ? $record['qth'] : '',
                'COL_QTH_INTL' => (!empty($record['qth_intl'])) ? $record['qth_intl'] : '',
                'COL_REGION' => (!empty($record['region'])) ? $record['region'] : '',
                'COL_RIG' => (!empty($record['rig'])) ? $record['rig'] : '',
                'COL_RIG_INTL' => (!empty($record['rig_intl'])) ? $record['rig_intl'] : '',
                'COL_RST_RCVD' => $rst_rx,
                'COL_RST_SENT' => $rst_tx,
                'COL_RX_PWR' => (!empty($record['rx_pwr'])) ? $record['rx_pwr'] : null,
                'COL_SAT_MODE' => (!empty($record['sat_mode'])) ? $record['sat_mode'] : '',
                'COL_SAT_NAME' => (!empty($record['sat_name'])) ? $record['sat_name'] : '',
                'COL_SFI' => (!empty($record['sfi'])) ? $record['sfi'] : null,
                'COL_SIG' => (!empty($record['sig'])) ? $record['sig'] : '',
                'COL_SIG_INFO' => (!empty($record['sig_info'])) ? $record['sig_info'] : '',
                'COL_SIG_INFO_INTL' => (!empty($record['sig_info_intl'])) ? $record['sig_info_intl'] : '',
                'COL_SIG_INTL' => (!empty($record['sig_intl'])) ? $record['sig_intl'] : '',
                'COL_SILENT_KEY' => (!empty($record['silent_key'])) ? $record['silent_key'] : '',
                'COL_SKCC' => (!empty($record['skcc'])) ? $record['skcc'] : '',
                'COL_SOTA_REF' => (!empty($record['sota_ref'])) ? $record['sota_ref'] : '',
                'COL_SRX' => (!empty($record['srx'])) ? $record['srx'] : null,
                'COL_SRX_STRING' => (!empty($record['srx_string'])) ? $record['srx_string'] : '',
                'COL_STATE' => (!empty($record['state'])) ? $record['state'] : '',
                'COL_STATION_CALLSIGN' => (!empty($record['station_callsign'])) ? $record['station_callsign'] : '',
                'COL_STX' => (!empty($record['stx'])) ? $record['stx'] : null,
                'COL_STX_STRING' => (!empty($record['stx_string'])) ? $record['stx_string'] : '',
                'COL_SUBMODE' => (!empty($record['submode'])) ? $record['submode'] : '',
                'COL_SWL' => (!empty($record['swl'])) ? $record['swl'] : null,
                'COL_TEN_TEN' => (!empty($record['ten_ten'])) ? $record['ten_ten'] : null,
                'COL_TIME_ON' => $time_on,
                'COL_TIME_OFF' => $time_off,
                'COL_TX_PWR' => (!empty($record['tx_pwr'])) ? $record['tx_pwr'] : null,
                'COL_UKSMG' => (!empty($record['uksmg'])) ? $record['uksmg'] : '',
                'COL_USACA_COUNTIES' => (!empty($record['usaca_counties'])) ? $record['usaca_counties'] : '',
                'COL_VUCC_GRIDS' =>((!empty($record['vucc_grids']))) ? $record['vucc_grids'] : '',
                'COL_WEB' => (!empty($record['web'])) ? $record['web'] : ''
            );

            if($station_id != "0") {
              $station_result = $this->db->where('station_id', $station_id)
                                ->get('station_profile');


                if ($station_result->num_rows() > 0){
                    $row = $station_result->row_array();

                    if (strpos(trim($row['station_gridsquare']), ',') !== false) {
                      $data['COL_MY_VUCC_GRIDS'] = strtoupper(trim($row['station_gridsquare']));
                    } else {
                      $data['COL_MY_GRIDSQUARE'] = strtoupper(trim($row['station_gridsquare']));
                    }

                    $data['COL_MY_CITY'] = strtoupper(trim($row['station_city']));
                    $data['COL_MY_IOTA'] = strtoupper(trim($row['station_iota']));
                    $data['COL_MY_SOTA_REF'] = strtoupper(trim($row['station_sota']));
                    
                    $data['COL_STATION_CALLSIGN'] = strtoupper(trim($row['station_callsign']));
                    $data['COL_MY_DXCC'] = strtoupper(trim($row['station_dxcc']));
                    $data['COL_MY_COUNTRY'] = strtoupper(trim($row['station_country']));
                    $data['COL_MY_CNTY'] = strtoupper(trim($row['station_cnty']));
                    $data['COL_MY_CQ_ZONE'] = strtoupper(trim($row['station_cq']));
                    $data['COL_MY_ITU_ZONE'] = strtoupper(trim($row['station_itu']));
                }
            }


            $this->add_qso($data);

        }
    }


    /*
     * Check the dxxc_prefixes table and return (dxcc, country)
     */
    public function check_dxcc_table($call, $date){
        $len = strlen($call);

        // query the table, removing a character from the right until a match
        for ($i = $len; $i > 0; $i--){
            //printf("searching for %s\n", substr($call, 0, $i));
            $dxcc_result = $this->db->select('`call`, `entity`, `adif`, `cqz`')
                                    ->where('call', substr($call, 0, $i))
                                    ->where('(start <= ', $date)
                                    ->or_where("start = '0000-00-00'", NULL, false)
                                    ->or_where("start is null)", NULL, false)
                                    ->where('(end >= ', $date)
                                    ->or_where("end = '0000-00-00'", NULL, false)
                                    ->or_where("end is null)", NULL, false)
                                    ->get('dxcc_prefixes');

            //$dxcc_result = $this->db->query("select `call`, `entity`, `adif` from dxcc_prefixes where `call` = '".substr($call, 0, $i) ."'");
            //print $this->db->last_query();

            if ($dxcc_result->num_rows() > 0){
                $row = $dxcc_result->row_array();
                return array($row['adif'], $row['entity'], $row['cqz']);
            }
        }

        return array("Not Found", "Not Found");
    }

    public function dxcc_lookup($call, $date){
        $len = strlen($call);

        $this->db->where('call', $call);
        $this->db->where('CURDATE() between start and end');

        $query = $this->db->get('dxcc_exceptions');


        if ($query->num_rows() > 0){

                $row = $query->row_array();

                return $row;
        } else {
          // query the table, removing a character from the right until a match
          for ($i = $len; $i > 0; $i--){
              //printf("searching for %s\n", substr($call, 0, $i));
              $dxcc_result = $this->db->select('*')
                                      ->where('call', substr($call, 0, $i))
                                      ->where('(start <= ', $date)
                                      ->or_where("start = '0000-00-00'", NULL, false)
                                      ->or_where("start is null)", NULL, false)
                                      ->where('(end >= ', $date)
                                      ->or_where("end = '0000-00-00'", NULL, false)
                                      ->or_where("end is null)", NULL, false)
                                      ->get('dxcc_prefixes');

              //$dxcc_result = $this->db->query("select `call`, `entity`, `adif` from dxcc_prefixes where `call` = '".substr($call, 0, $i) ."'");
              //print $this->db->last_query();

              if ($dxcc_result->num_rows() > 0){
                  $row = $dxcc_result->row_array();
                  return $row;
              }
          }
        }

        return array("Not Found", "Not Found");
    }

    /*
     * Same as check_dxcc_table, but the functionality is in 
     * a stored procedure which we call
     */
    public function check_dxcc_stored_proc($call, $date){
        $this->db->query("call find_country('".$call."','".$date."', @country, @adif, @cqz)");
        $res = $this->db->query("select @country as country, @adif as adif, @cqz as cqz");
        $d = $res->result_array();

        // Should only be one result.
        // NOTE: might cause unexpected data if there's an 
        // error with clublog.org data.
        return $d[0];
    }

    public function check_missing_dxcc_id($all){
        // get all records with no COL_DXCC
        $this->db->select("COL_PRIMARY_KEY, COL_CALL, COL_TIME_ON, COL_TIME_OFF");

        // check which to update - records with no dxcc or all records
        if (! isset($all)){
            $this->db->where("COL_DXCC is NULL");
        }

        $r = $this->db->get($this->config->item('table_name'));

        $count = 0;
        $this->db->trans_start();
        //query dxcc_prefixes
        if ($r->num_rows() > 0){
            foreach($r->result_array() as $row){
                $qso_date = $row['COL_TIME_OFF']=='' ? $row['COL_TIME_ON'] : $row['COL_TIME_ON'];
                $qso_date = strftime("%Y-%m-%d", strtotime($qso_date));

                // Manual call
                $d = $this->check_dxcc_table($row['COL_CALL'], $qso_date);

                // Stored procedure call
                //$d = $this->check_dxcc_stored_proc($row["COL_CALL"], $qso_date);

                if ($d[0] != 'Not Found'){
                    $sql = sprintf("update %s set COL_COUNTRY = '%s', COL_DXCC='%s' where COL_PRIMARY_KEY=%d",
                                    $this->config->item('table_name'), addslashes(ucwords(strtolower($d[1]))), $d[0], $row['COL_PRIMARY_KEY']);
                    $this->db->query($sql);
                    //print($sql."\n");
                    printf("Updating %s to %s and %s\n<br/>", $row['COL_PRIMARY_KEY'], ucwords(strtolower($d[1])), $d[0]);
                    $count++;
                }
            }
        }
        $this->db->trans_complete();

        print("$count updated\n");
    }
    public function parse_frequency($frequency)
    {
      if (is_int($frequency))
        return $frequency; 
    
      if (is_string($frequency))
      {
        $frequency = strtoupper($frequency);
        $frequency = str_replace(" ", "", $frequency);
        $frequency = str_replace("HZ", "", $frequency); 
        $frequency = str_replace(["K", "M", "G", "T"], ["E3", "E6", "E9", "E12"], $frequency);
    
        // this double conversion will take a string like "3700e3" and convert it into 3700000
        return (int)(float) $frequency;
      }
    
      return 0;
    }
    
}

?>
