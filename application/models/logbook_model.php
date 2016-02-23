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
      'COL_FREQ' => $this->input->post('freq_display'),
      'COL_MODE' => $this->input->post('mode'),
      'COL_RST_RCVD' => $this->input->post('rst_recv'),
      'COL_RST_SENT' => $this->input->post('rst_sent'),
      'COL_NAME' => $this->input->post('name'),
      'COL_COMMENT' => $this->input->post('comment'),
      'COL_SAT_NAME' => strtoupper($this->input->post('sat_name')),
      'COL_SAT_MODE' => strtoupper($this->input->post('sat_mode')),
      'COL_GRIDSQUARE' => strtoupper(trim($this->input->post('locator'))),
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
      'COL_FREQ_RX' => 0,
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
    );

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

    // Create array with QSO Data
    $data = array(
            'COL_TIME_ON' => $datetime,
            'COL_TIME_OFF' => $datetime,
            'COL_CALL' => strtoupper(trim($this->input->post('callsign'))),
            'COL_BAND' => $this->input->post('band'),
            'COL_FREQ' => $this->input->post('freq_display'),
            'COL_MODE' => $this->input->post('mode'),
            'COL_RST_RCVD' => $this->input->post('rst_recv'),
            'COL_RST_SENT' => $this->input->post('rst_sent'),
            'COL_NAME' => $this->input->post('name'),
            'COL_COMMENT' => $this->input->post('comment'),
            'COL_SAT_NAME' => strtoupper($this->input->post('sat_name')),
            'COL_SAT_MODE' => strtoupper($this->input->post('sat_mode')),
            'COL_GRIDSQUARE' => strtoupper(trim($this->input->post('locator'))),
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
            'COL_QTH' => $this->input->post('qth'),
            'COL_PROP_MODE' => $prop_mode,
            'COL_IOTA' => trim($this->input->post('iota_ref')),
            'COL_MY_GRIDSQUARE' => $locator,
            'COL_DISTANCE' => "0",
            'COL_FREQ_RX' => 0,
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
    );

    // if eQSL username set, default SENT & RCVD to 'N' else leave as null
    if ($this->session->userdata('user_eqsl_name')){
        $data['COL_EQSL_QSL_SENT'] = 'N';
        $data['COL_EQSL_QSL_RCVD'] = 'N';
    }

    $this->add_qso($data);
  }

  function add_qso($data) {
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
       'COL_FREQ' => $this->input->post('freq'),
       'COL_MODE' => $this->input->post('mode'),
       'COL_RST_RCVD' => $this->input->post('rst_recv'),
       'COL_RST_SENT' => $this->input->post('rst_sent'),
       'COL_GRIDSQUARE' => strtoupper(trim($this->input->post('locator'))),
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
       'COL_QTH' => $this->input->post('qth'),
       'COL_PROP_MODE' => $this->input->post('prop_mode'),
       'COL_FREQ_RX' => '0',
       'COL_STX_STRING' => $this->input->post('stx_string'),
                   'COL_SRX_STRING' => $this->input->post('srx_string')
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

  /* Return QSO Info */
  function qso_info($id) {
    $this->db->where('COL_PRIMARY_KEY', $id);

    return $this->db->get($this->config->item('table_name'));
  }


  function get_qsos($num, $offset) {
    $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME, COL_GRIDSQUARE, COL_QSL_RCVD, COL_EQSL_QSL_RCVD, COL_EQSL_QSL_SENT, COL_QSL_SENT, COL_STX_STRING, COL_SRX_STRING, COL_OPERATOR, COL_STATION_CALLSIGN, COL_LOTW_QSL_SENT, COL_LOTW_QSL_RCVD');
    $this->db->order_by("COL_TIME_ON", "desc");

    $query = $this->db->get($this->config->item('table_name'), $num, $offset);

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
		$this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -5 MINUTE )');
		$this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 5 MINUTE )');
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

		$this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -5 MINUTE )');
		$this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 5 MINUTE )');
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
    	$this->db->where('COL_TIME_ON >= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL -5 MINUTE )');
		$this->db->where('COL_TIME_ON <= DATE_ADD(DATE_FORMAT("'.$datetime.'", \'%Y-%m-%d %H:%i\' ), INTERVAL 5 MINUTE )');
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
  		//$this->db->select("COL_PRIMARY_KEY, DATE_FORMAT(COL_TIME_ON,\'%Y%m%d\') AS COL_QSO_DATE, DATE_FORMAT(COL_TIME_ON,\'%H%i\') AS TIME_ON, COL_CALL, COL_MODE, COL_BAND");
  		$this->db->select("COL_PRIMARY_KEY, COL_TIME_ON, COL_CALL, COL_MODE, COL_BAND, COL_COMMENT, COL_RST_SENT");
  		$this->db->where('COL_EQSL_QSL_SENT', 'N');

  		return $this->db->get($this->config->item('table_name'));
  	}

    function import($record) {
        $CI =& get_instance();
        $CI->load->library('frequency');
        // Join date+time
        //$datetime = date('Y-m-d') ." ". $this->input->post('start_time');
        //$myDate = date('Y-m-d', $record['qso_date']);
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
            $freq = $cleansedstring."000";
        } elseif($freqlng >= 7) {
            $cleansedstring = preg_replace('#\W#', '', $record['freq']);
            $freq = $cleansedstring;
        } else {
            $freq = "0";
        }

        // Store Name
        if(isset($record['name'])) {
            $name = $record['name'];
        } else {
            $name = "";
        }

        // Store Notes
        if(isset($record['notes'])) {
            $comment = $record['notes'];
        } else {
            $comment = "";
        }

        // Store Sat Name
        if(isset($record['sat_name'])) {
            $sat_name = $record['sat_name'];
        } else {
            $sat_name = "";
        }

        // Store Satellite Mode
        if(isset($record['sat_mode'])) {
            $sat_mode = $record['sat_mode'];
        } else {
            $sat_mode = "";
        }

        // Store Gridsquare
        if(isset($record['gridsquare'])) {
            $gridsquare = $record['gridsquare'];
        } else {
            $gridsquare = "";
        }

        // Store or find country name
        if(isset($record['country'])) {
            $country = $record['country'];
        } else {
            $this->load->model('dxcc');

            $dxccinfo = $this->dxcc->info($record['call']);

            if ($dxccinfo->num_rows() > 0)
            {
                foreach ($dxccinfo->result() as $row1)
                {
                    $country = ucfirst(strtolower($row1->name));
                }
            } else {
                $country = "";
            }
        }

        // Store QTH
        if(isset($record['qth'])) {
            $qth = $record['qth'];
        } else {
            $qth = "";
        }

        // Store Propagation Mode
        if(isset($record['prop_mode'])) {
            $prop_mode = $record['prop_mode'];
        } else {
            $prop_mode = "";
        }

        // RST recevied
        if(isset($record['rst_rcvd'])) {
                $rst_rx = $record['rst_rcvd'];
        } else {
                $rst_rx = "59"  ;
        }

        // RST Sent
        if(isset($record['rst_sent'])) {
                $rst_tx = $record['rst_sent'];
        } else {
                $rst_tx = "59"  ;
        }

        // Store Band
        if(isset($record['band'])) {
                $band = $record['band'];
        } else {
                $myfreq = str_replace(array('.', ','), '' , $record['freq'].'0');

                $band = $CI->frequency->GetBand($myfreq);
        }

        // Store IOTA Ref if available
        if(isset($record['iota'])) {
                $iota = $record['iota'];
        } else {
                $iota = null;
        }


        // QSL Recv date
        if(isset($record['qslrdate'])) {
                $QSLRDATE = $record['qslrdate'];
        } else {
                $QSLRDATE = null;
        }

        // QSL Recv Status
        if(isset($record['qsl_rcvd'])) {
                $QSLRCVD = $record['qsl_rcvd'];
        } else {
                $QSLRCVD = null;
        }

        // QSL Sent date
        if(isset($record['qslsdate'])) {
                $QSLSDATE = $record['qslsdate'];
        } else {
                $QSLSDATE = null;
        }

        // QSL Sent Status
        if(isset($record['qsl_sent'])) {
                $QSLSENT = $record['qsl_sent'];
        } else {
                $QSLSENT = null;
        }

        //LOTW QSL Recv
        if(isset($record['lotw_qsl_rcvd'])) {
                $LOTWQSLRCVD = $record['lotw_qsl_rcvd'];
        } else {
                $LOTWQSLRCVD = null;
        }

        //LOTW QSL Sent
        if(isset($record['lotw_qsl_sent'])) {
                $LOTWQSLSENT = $record['lotw_qsl_sent'];
        } else {
                $LOTWQSLSENT = null;
        }

        if(isset($record['stx'])) {
                $stx = $record['stx'];
        } else {
                $stx = null;
        }

        if(isset($record['srx'])) {
                $srx = $record['srx'];
        } else {
                $srx = null;
        }

        // Filter Modes if not apart of ADIF spec
        if($record['mode'] == "RTTY75") {
            // Set RTTY75 to just RTTY
                $mode = "RTTY";
        } else {
            // If no other rules just plain mode that adif includes
                $mode = $record['mode'];
        }


        $this->db->where('COL_CALL', $record['call']);
        $this->db->where('COL_TIME_ON', $time_on);
        $check = $this->db->get($this->config->item('table_name'));

        if ($check->num_rows() <= 0)
        {
            // Create array with QSO Data
            $data = array(
               'COL_TIME_ON' => $time_on,
               'COL_TIME_OFF' => $time_off,
               'COL_CALL' => strtoupper($record['call']),
               'COL_BAND' => $band,
               'COL_FREQ' => $freq,
               'COL_MODE' => $mode,
               'COL_RST_RCVD' => $rst_rx,
               'COL_RST_SENT' => $rst_tx,
               'COL_NAME' => $name,
               'COL_COMMENT' => $comment,
               'COL_SAT_NAME' => $sat_name,
               'COL_SAT_MODE' => $sat_mode,
               'COL_GRIDSQUARE' => $gridsquare,
               'COL_COUNTRY' => $country,
               'COL_QTH' =>$qth,
               'COL_PROP_MODE' => $prop_mode,
               'COL_DISTANCE' => 0,
               'COL_FREQ_RX' => 0,
               'COL_BAND_RX' => 0,
               'COL_ANT_AZ' => 0,
               'COL_ANT_EL' => 0,
               'COL_STX_STRING' => $stx,
               'COL_SRX_STRING' => $srx,
               'COL_IOTA' => $iota,
               'COL_QSLRDATE' => $QSLRDATE,
               'COL_QSL_RCVD' => $QSLRCVD,
               'COL_QSLSDATE' => $QSLSDATE,
               'COL_QSL_SENT' => $QSLSENT,
               'COL_LOTW_QSL_SENT' => $LOTWQSLSENT,
               'COL_LOTW_QSL_RCVD' => $LOTWQSLRCVD
            );

            // if eQSL username set, default SENT & RCVD to 'N' else leave as null
            if ($this->session->userdata('user_eqsl_name')){
                $data['COL_EQSL_QSL_SENT'] = 'N';
                $data['COL_EQSL_QSL_RCVD'] = 'N';
            }

            $this->add_qso($data);
        }
    }


    private function check_dxcc_table($call, $date){
        $len = strlen($call);

        // query the table, removing a character from the right until a match
        for ($i = $len; $i > 0; $i--){
            //printf("searching for %s\n", substr($call, 0, $i));
            $dxcc_result = $this->db->select('`call`, `entity`, `adif`')
                                    ->where('call', substr($call, 0, $i))
                                    ->where('(start <= ', $date)
                                    ->or_where("start = '0000-00-00')", NULL, false)
                                    ->where('(end >= ', $date)
                                    ->or_where("end = '0000-00-00')", NULL, false)
                                    ->get('dxcc_prefixes');

            //$dxcc_result = $this->db->query("select `call`, `entity`, `adif` from dxcc_prefixes where `call` = '".substr($call, 0, $i) ."'");
            //print $this->db->last_query();

            if ($dxcc_result->num_rows() > 0){
                $row = $dxcc_result->row_array();
                return array($row['adif'], $row['entity']);
            }
        }

        return array("Not Found", "Not Found");
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

                $d = $this->check_dxcc_table($row['COL_CALL'], $qso_date);

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

}

?>
