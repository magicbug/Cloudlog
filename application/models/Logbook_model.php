<?php

class Logbook_model extends CI_Model {

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

    // Contest exchange, need to separate between serial and other type of exchange
    if($this->input->post('exchangetype')) {
      $srx_string = $this->input->post('exch_recv') == '' ? null : $this->input->post('exch_recv');
      $stx_string = $this->input->post('exch_sent') == '' ? null : $this->input->post('exch_sent');
      $srx = $this->input->post('exch_serial_r') == '' ? null : $this->input->post('exch_serial_r');
      $stx = $this->input->post('exch_serial_s') == '' ? null : $this->input->post('exch_serial_s');
    } else {
      $srx_string = null;
      $stx_string = null;
      $srx = null;
      $stx = null;
    }

    if($this->input->post('contestname')) {
        $contestid = $this->input->post('contestname');
    } else {
        $contestid = null;
    }

    if($this->session->userdata('user_locator')){
        $locator = $this->session->userdata('user_locator');
    } else {
        $locator = $this->config->item('locator');
    }

    if($this->input->post('transmit_power')) {
        $tx_power = $this->input->post('transmit_power');
    } else {
        $tx_power = null;
    }

    if($this->input->post('country') == "") {
      $dxcc = $this->check_dxcc_table(strtoupper(trim($this->input->post('callsign'))), $datetime);
      $country = ucwords(strtolower($dxcc[1]), "- (/");
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

    $mode = $this->get_main_mode_if_submode($this->input->post('mode'));
    if ($mode == null) {
        $mode = $this->input->post('mode');
        $submode = null;
    } else {
        $submode = $this->input->post('mode');
    }

    if($this->input->post('county') && $this->input->post('usa_state')) {
      $clean_county_input = trim($this->input->post('usa_state')) . "," . trim($this->input->post('county'));
    } else {
      $clean_county_input = null;
    }

    if($this->input->post('copyexchangetodok')) {
      $darc_dok = $this->input->post('exch_recv');
    } else {
      $darc_dok = $this->input->post('darc_dok');
    }

    if ($this->input->post('qsl_sent')) {
        $qsl_sent = $this->input->post('qsl_sent');
    } else {
        $qsl_sent = 'N';
    }

    if ($this->input->post('qsl_recv')) {
        $qsl_recv = $this->input->post('qsl_recv');
    } else {
        $qsl_recv = 'N';
    }

    if ($qsl_sent == 'N') {
        $qslsdate = null;
    } else {
        $qslsdate = date('Y-m-d H:i:s');
    }

    if ($qsl_recv == 'N') {
        $qslrdate = null;
    } else {
        $qslrdate = date('Y-m-d H:i:s');
    }

    // Create array with QSO Data
    $data = array(
            'COL_TIME_ON' => $datetime,
            'COL_TIME_OFF' => $datetime,
            'COL_CALL' => strtoupper(trim($this->input->post('callsign'))),
            'COL_BAND' => $this->input->post('band'),
            'COL_BAND_RX' => $this->input->post('band_rx'),
            'COL_FREQ' => $this->parse_frequency($this->input->post('freq_display')),
            'COL_MODE' => $mode,
            'COL_SUBMODE' => $submode,
            'COL_RST_RCVD' => $this->input->post('rst_recv'),
            'COL_RST_SENT' => $this->input->post('rst_sent'),
            'COL_NAME' => $this->input->post('name'),
            'COL_COMMENT' => $this->input->post('comment'),
            'COL_SAT_NAME' => strtoupper($this->input->post('sat_name')),
            'COL_SAT_MODE' => strtoupper($this->input->post('sat_mode')),
            'COL_COUNTRY' => $country,
            'COL_QSLSDATE' => $qslsdate,
            'COL_QSLRDATE' => $qslrdate,
            'COL_QSL_SENT' => $qsl_sent,
            'COL_QSL_RCVD' => $qsl_recv,
            'COL_QSL_SENT_VIA' => $this->input->post('qsl_sent_method'),
            'COL_QSL_RCVD_VIA' => $this->input->post('qsl_recv_method'),
            'COL_QSL_VIA' => $this->input->post('qsl_via'),
            'COL_QSLMSG' => $this->input->post('qslmsg'),
            'COL_OPERATOR' => $this->session->userdata('user_callsign'),
            'COL_QTH' => $this->input->post('qth'),
            'COL_PROP_MODE' => $prop_mode,
            'COL_IOTA' => trim($this->input->post('iota_ref')),
            'COL_DISTANCE' => "0",
            'COL_FREQ_RX' => $this->parse_frequency($this->input->post('freq_display_rx')),
            'COL_ANT_AZ' => null,
            'COL_ANT_EL' => null,
            'COL_A_INDEX' => null,
            'COL_AGE' => null,
            'COL_TEN_TEN' => null,
            'COL_TX_PWR' => $tx_power,
            'COL_STX' => $stx,
            'COL_SRX' => $srx,
            'COL_STX_STRING' => $stx_string,
            'COL_SRX_STRING' => $srx_string,
            'COL_CONTEST_ID' => $contestid,
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
            'COL_STATE' => trim($this->input->post('usa_state')),
            'COL_CNTY' => $clean_county_input,
            'COL_SOTA_REF' => trim($this->input->post('sota_ref')),
            'COL_WWFF_REF' => trim($this->input->post('wwff_ref')),
            'COL_SIG' => trim($this->input->post('sig')),
            'COL_SIG_INFO' => trim($this->input->post('sig_info')),
            'COL_DARC_DOK' => strtoupper(trim($darc_dok)),
			'COL_NOTES' => $this->input->post('notes'),
    );

    $station_id = $this->input->post('station_profile');

    if($station_id == "" || $station_id == "0") {
      $CI =& get_instance();
      $CI->load->model('Stations');
      $station_id = $CI->Stations->find_active();
    }

    // If station profile has been provided fill in the fields
    if($station_id != "0") {
      $station = $this->check_station($station_id);
        $data['station_id'] = $station_id;

      if (strpos(trim($station['station_gridsquare']), ',') !== false) {
        $data['COL_MY_VUCC_GRIDS'] = strtoupper(trim($station['station_gridsquare']));
      } else {
        $data['COL_MY_GRIDSQUARE'] = strtoupper(trim($station['station_gridsquare']));
      }

    if ($this->exists_qrz_api_key($station_id)) {
        $data['COL_QRZCOM_QSO_UPLOAD_STATUS'] = 'N';
    }

      $data['COL_MY_CITY'] = strtoupper(trim($station['station_city']));
      $data['COL_MY_IOTA'] = strtoupper(trim($station['station_iota']));
      $data['COL_MY_SOTA_REF'] = strtoupper(trim($station['station_sota']));
      $data['COL_MY_WWFF_REF'] = strtoupper(trim($station['station_wwff']));

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

    $this->add_qso($data, $skipexport = false);
  }

  public function check_station($id){

    $this->db->where('station_id', $id);
    $query = $this->db->get('station_profile');

    if ($query->num_rows() > 0) {
      $row = $query->row_array();
        return($row);
    }
  }
	/*
	 * Used to fetch QSOs from the logbook in the awards
	 */
	public function qso_details($searchphrase, $band, $mode, $type){
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		switch ($type) {
			case 'DXCC':
				$this->db->where('COL_COUNTRY', $searchphrase);
				break;
			case 'DXCC2':
				$this->db->where('COL_DXCC', $searchphrase);
				break;
			case 'IOTA':
				$this->db->where('COL_IOTA', $searchphrase);
				break;
			case 'VUCC':
				$this->db->where("(COL_GRIDSQUARE like '%" . $searchphrase . "%' OR COL_VUCC_GRIDS like'%" . $searchphrase ."%')");
				break;
			case 'CQZone':
				$this->db->where('COL_CQZ', $searchphrase);
				break;
			case 'WAS':
				$this->db->where('COL_STATE', $searchphrase);
				$this->db->where_in('COL_DXCC', ['291', '6', '110']);
				break;
			case 'SOTA':
				$this->db->where('COL_SOTA_REF', $searchphrase);
				break;
			case 'WWFF':
				$this->db->where('COL_WWFF_REF', $searchphrase);
				break;
		}

    $this->db->where_in($this->config->item('table_name').'.station_id', $logbooks_locations_array);

		if ($band != 'All') {
			if($band != "SAT") {
				$this->db->where('COL_PROP_MODE !=', 'SAT');
				$this->db->where('COL_BAND', $band);
			} else {
				$this->db->where('COL_PROP_MODE', "SAT");
			}
		}

		if ($mode != 'All') {
			$this->db->where("(COL_MODE='" . $mode . "' OR COL_SUBMODE='" . $mode ."')");
		}
		$this->db->order_by("COL_TIME_ON", "desc");

		return $this->db->get($this->config->item('table_name'));
	}

	public function activated_grids_qso_details($searchphrase, $band, $mode){
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$sql =  'SELECT COL_SOTA_REF, COL_OPERATOR, COL_IOTA, COL_VUCC_GRIDS, COL_STATE, COL_GRIDSQUARE, COL_PRIMARY_KEY, COL_CALL, COL_TIME_ON, COL_BAND, COL_SAT_NAME, COL_MODE, COL_SUBMODE, COL_RST_SENT, ';
		$sql .= 'COL_RST_RCVD, COL_STX, COL_SRX, COL_STX_STRING, COL_SRX_STRING, COL_COUNTRY, COL_QSL_SENT, COL_QSL_SENT_VIA, ';
		$sql .= 'COL_QSLSDATE, COL_QSL_RCVD, COL_QSL_RCVD_VIA, COL_QSLRDATE, COL_EQSL_QSL_SENT, COL_EQSL_QSLSDATE, COL_EQSL_QSLRDATE, ';
		$sql .= 'COL_EQSL_QSL_RCVD, COL_LOTW_QSL_SENT, COL_LOTW_QSLSDATE, COL_LOTW_QSL_RCVD, COL_LOTW_QSLRDATE, COL_CONTEST_ID, station_gridsquare ';
		$sql .= 'FROM '.$this->config->item('table_name').' JOIN `station_profile` ON station_profile.station_id = '.$this->config->item('table_name').'.station_id ';
		$sql .= 'WHERE '.$this->config->item('table_name').'.station_id IN (SELECT station_id from station_profile ';
		$sql .= 'WHERE station_gridsquare LIKE "%'.$searchphrase.'%") ';

		if ($band != 'All') {
			if($band != "SAT") {
				$sql .= 'AND COL_PROP_MODE != "SAT" AND ';
				$sql .= 'COL_BAND = "'.$band.'" ';
			} else {
				$sql .= 'AND COL_PROP_MODE = "SAT"';
			}
		}

		if ($mode != 'All') {
			$sql .= ' AND COL_MODE = "'.$mode.'" OR COL_SUBMODE="'.$mode.'"';
		}
		$sql .= ' ORDER BY COL_TIME_ON DESC LIMIT 500';

		return $this->db->query($sql);
	}

    public function vucc_qso_details($gridsquare, $band) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

        $sql = "select * from " . $this->config->item('table_name') .
                " where station_id in (" . $location_list . ")" .
                " and (col_gridsquare like '" . $gridsquare. "%'
                    or col_vucc_grids like '%" . $gridsquare. "%')";

        if ($band != 'All') {
            if ($band == 'SAT') {
                $sql .= " and col_prop_mode ='" . $band . "'";
            } else {
                $sql .= " and col_prop_mode !='SAT'";
                $sql .= " and col_band ='" . $band . "'";
            }
        }

        return $this->db->query($sql);
    }

    public function timeline_qso_details($querystring, $band, $mode, $type){
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');

        if ($band != 'All') {
            if ($band == 'SAT') {
                $this->db->where('col_prop_mode', $band);
            } else {
                $this->db->where('COL_PROP_MODE !=', 'SAT');
                $this->db->where('col_band', $band);
            }
        }

        if ($mode != 'All') {
            $this->db->where('col_mode', $mode);
        }

        $this->db->where_in('station_profile.station_id', $logbooks_locations_array);

        switch($type) {
            case 'dxcc': $this->db->where('COL_DXCC', $querystring); break;
            case 'was':  $this->db->where('COL_STATE', $querystring); break;
            case 'iota': $this->db->where('COL_IOTA', $querystring); break;
            case 'waz':  $this->db->where('COL_CQZ', $querystring); break;
        }

        return $this->db->get($this->config->item('table_name'));
    }

    public function activator_details($call, $band, $leogeo){
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->where('COL_CALL', $call);
        if ($band != 'All') {
            if ($band == 'SAT') {
                $this->db->where('col_prop_mode', $band);
                switch ($leogeo) {
                case 'leo' :  $this->db->where('COL_SAT_NAME !=', 'QO-100');
                              break;
                case 'geo':   $this->db->where('COL_SAT_NAME', 'QO-100');
                              break;
                }
            } else {
                $this->db->where('COL_PROP_MODE !=', 'SAT');
                $this->db->where('col_band', $band);
            }
        }

        $this->db->where_in('station_profile.station_id', $logbooks_locations_array);

        return $this->db->get($this->config->item('table_name'));
    }

  public function get_callsigns($callsign){
    $this->db->select('COL_CALL');
    $this->db->distinct();
    $this->db->like('COL_CALL', $callsign);

    return $this->db->get($this->config->item('table_name'));

  }

  public function get_dok($callsign){
    $this->db->select('COL_DARC_DOK');
    $this->db->where('COL_CALL', $callsign);
    $this->db->order_by("COL_TIME_ON", "desc");
    $this->db->limit(1);

    return $this->db->get($this->config->item('table_name'));

  }

  function add_qso($data, $skipexport = false) {

    if ($data['COL_DXCC'] == "Not Found"){
      $data['COL_DXCC'] = NULL;
    }

    if (!is_null($data['COL_RX_PWR'])) {
      $data['COL_RX_PWR'] = str_replace("W", "", $data['COL_RX_PWR']);
    }

    // Add QSO to database
    $this->db->insert($this->config->item('table_name'), $data);

    $last_id = $this->db->insert_id();

    // No point in fetching qrz api key and qrzrealtime setting if we're skipping the export
	if (!$skipexport) {

		$result = $this->exists_qrz_api_key($data['station_id']);

		// Push qso to qrz if apikey is set, and realtime upload is enabled, and we're not importing an adif-file
		if (isset($result->qrzapikey) && $result->qrzrealtime == 1) {
			$CI =& get_instance();
			$CI->load->library('AdifHelper');
			$qso = $this->get_qso($last_id)->result();

			$adif = $CI->adifhelper->getAdifLine($qso[0]);
			$result = $this->push_qso_to_qrz($result->qrzapikey, $adif);
			if ($result['status'] == 'OK') {
				$this->mark_qrz_qsos_sent($last_id);
			}
		}
	}
  }

  /*
   * Function checks if a QRZ API Key exists in the table with the given station id
  */
  function exists_qrz_api_key($station_id) {
      $sql = 'select qrzapikey, qrzrealtime from station_profile
            where station_id = ' . $station_id;

      $query = $this->db->query($sql);

      $result = $query->row();

      if ($result) {
          return $result;
      }
      else {
          return false;
      }
  }

  /*
   * Function uploads a QSO to QRZ with the API given.
   * $adif contains a line with the QSO in the ADIF format. QSO ends with an <EOR>
   */
  function push_qso_to_qrz($apikey, $adif, $replaceoption = false) {
      $url = 'http://logbook.qrz.com/api'; // TODO: Move this to database

      $post_data['KEY'] = $apikey;
      $post_data['ACTION'] = 'INSERT';
      $post_data['ADIF'] = $adif;

      if ($replaceoption) {
          $post_data['OPTION'] = 'REPLACE';
      }

      $ch = curl_init( $url );
      curl_setopt( $ch, CURLOPT_POST, true);
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt( $ch, CURLOPT_HEADER, 0);
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);

      $content = curl_exec($ch);
      if ($content){
          if (stristr($content,'RESULT=OK') || stristr($content,'RESULT=REPLACE')) {
              $result['status'] = 'OK';
              return $result;
          }
          else {
              $result['status'] = 'error';
              $result['message'] = $content;
              return $result;
          }
      }
      if(curl_errno($ch)){
          $result['status'] = 'error';
          $result['message'] = 'Curl error: '. curl_errno($ch);
          return $result;
      }
      curl_close($ch);
  }

  /*
   * Function marks QSOs as uploaded to QRZ.
   * $primarykey is the unique id for that QSO in the logbook
   */
    function mark_qrz_qsos_sent($primarykey) {
        $data = array(
         'COL_QRZCOM_QSO_UPLOAD_DATE' => date("Y-m-d H:i:s", strtotime("now")),
         'COL_QRZCOM_QSO_UPLOAD_STATUS' => 'Y',
        );

        $this->db->where('COL_PRIMARY_KEY', $primarykey);

        $this->db->update($this->config->item('table_name'), $data);

        return true;
    }

  /* Edit QSO */
  function edit() {
    $qso = $this->get_qso($this->input->post('id'))->row();

    $entity = $this->get_entity($this->input->post('dxcc_id'));
    $stationId = $this->input->post('station_profile');
    $country = ucwords(strtolower($entity['name']), "- (/");

    // be sure that station belongs to user
    $CI =& get_instance();
    $CI->load->model('Stations');
    if (!$CI->Stations->check_station_is_accessible($stationId)) {
        return;
    }

    $mode = $this->get_main_mode_if_submode($this->input->post('mode'));
    if ($mode == null) {
        $mode = $this->input->post('mode');
        $submode = null;
    } else {
        $submode = $this->input->post('mode');
    }

    if($this->input->post('transmit_power')) {
      $txpower = $this->input->post('transmit_power');
    } else {
      $txpower = null;
    }

    if($this->input->post('stx')) {
      $stx_string = $this->input->post('stx');
    } else {
      $stx_string = null;
    }

    if($this->input->post('srx')) {
      $srx_string = $this->input->post('srx');
    } else {
      $srx_string = null;
    }

	if (stristr($this->input->post('usa_county'), ',')) {
		$uscounty = $this->input->post('usa_county');
	} else {
		$uscounty = $this->input->post('usa_state') .",".$this->input->post('usa_county');
	}

    if ($this->input->post('qsl_sent')) {
        $qsl_sent = $this->input->post('qsl_sent');
    } else {
        $qsl_sent = 'N';
    }

    if ($this->input->post('qsl_recv')) {
        $qsl_recv = $this->input->post('qsl_recv');
    } else {
        $qsl_recv = 'N';
    }

    if ($qsl_sent == 'N') {
        $qslsdate = null;
    } elseif (!$qso->COL_QSLSDATE || $qso->COL_QSL_SENT != $qsl_sent) {
        $qslsdate = date('Y-m-d H:i:s');
    } else {
        $qslsdate = $qso->COL_QSLSDATE;
    }

    if ($qsl_recv == 'N') {
        $qslrdate = null;
    } elseif (!$qso->COL_QSLRDATE || $qso->COL_QSL_RECV != $qsl_recv) {
        $qslrdate = date('Y-m-d H:i:s');
    } else {
        $qslrdate = $qso->COL_QSLRDATE;
    }

    $data = array(
       'COL_TIME_ON' => $this->input->post('time_on'),
       'COL_TIME_OFF' => $this->input->post('time_off'),
       'COL_CALL' => strtoupper(trim($this->input->post('callsign'))),
       'COL_BAND' => $this->input->post('band'),
       'COL_BAND_RX' => $this->input->post('band_rx'),
       'COL_FREQ' => $this->parse_frequency($this->input->post('freq')),
       'COL_MODE' => $mode,
       'COL_SUBMODE' => $submode,
       'COL_RST_RCVD' => $this->input->post('rst_recv'),
       'COL_RST_SENT' => $this->input->post('rst_sent'),
       'COL_GRIDSQUARE' => strtoupper(trim($this->input->post('locator'))),
       'COL_VUCC_GRIDS' => strtoupper(trim($this->input->post('vucc_grids'))),
       'COL_COMMENT' => $this->input->post('comment'),
       'COL_NAME' => $this->input->post('name'),
       'COL_COUNTRY' => $country,
       'COL_DXCC'=> $this->input->post('dxcc_id'),
       'COL_CQZ' => $this->input->post('cqz'),
       'COL_SAT_NAME' => $this->input->post('sat_name'),
       'COL_SAT_MODE' => $this->input->post('sat_mode'),
       'COL_NOTES' => $this->input->post('notes'),
       'COL_QSLSDATE' => $qslsdate,
       'COL_QSLRDATE' => $qslrdate,
       'COL_QSL_SENT' => $qsl_sent,
       'COL_QSL_RCVD' => $qsl_recv,
       'COL_QSL_SENT_VIA' => $this->input->post('qsl_sent_method'),
       'COL_QSL_RCVD_VIA' => $this->input->post('qsl_recv_method'),
       'COL_EQSL_QSL_SENT' => $this->input->post('eqsl_sent'),
       'COL_EQSL_QSL_RCVD' => $this->input->post('eqsl_recv'),
       'COL_QSLMSG' => $this->input->post('qslmsg'),
       'COL_LOTW_QSL_SENT' => $this->input->post('lotw_sent'),
       'COL_LOTW_QSL_RCVD' => $this->input->post('lotw_recv'),
       'COL_IOTA' => $this->input->post('iota_ref'),
       'COL_SOTA_REF' => $this->input->post('sota_ref'),
       'COL_WWFF_REF' => $this->input->post('wwff_ref'),
       'COL_TX_PWR' => $txpower,
       'COL_SIG' => $this->input->post('sig'),
       'COL_SIG_INFO' => $this->input->post('sig_info'),
       'COL_DARC_DOK' => strtoupper($this->input->post('darc_dok')),
       'COL_QTH' => $this->input->post('qth'),
       'COL_PROP_MODE' => $this->input->post('prop_mode'),
       'COL_FREQ_RX' => $this->parse_frequency($this->input->post('freq_display_rx')),
       'COL_STX_STRING' => $this->input->post('stx_string'),
       'COL_SRX_STRING' => $this->input->post('srx_string'),
       'COL_STX' => $stx_string,
       'COL_SRX' => $srx_string,
       'COL_CONTEST_ID' => $this->input->post('contest_name'),
       'COL_QSL_VIA' => $this->input->post('qsl_via_callsign'),
       'station_id' => $stationId,
       'COL_OPERATOR' => $this->input->post('operator_callsign'),
       'COL_STATE' =>$this->input->post('usa_state'),
       'COL_CNTY' => $uscounty
    );

    if ($this->exists_qrz_api_key($data['station_id'])) {
        $data['COL_QRZCOM_QSO_UPLOAD_STATUS'] = 'M';
    }

    $this->db->where('COL_PRIMARY_KEY', $this->input->post('id'));
    $this->db->update($this->config->item('table_name'), $data);

  }

  /* QSL received */
  function qsl_rcvd() {

    $data = array(
       'COL_QSLRDATE' => date('Y-m-d H:i:s'),
       'COL_QSL_RCVD' => "Y"
    );

    $this->db->where('COL_PRIMARY_KEY', $this->input->post('id'));
    $this->db->update($this->config->item('table_name'), $data);

  }

  /* Return last 10 QSOs */
  function last_ten() {
    $CI =& get_instance();
    $CI->load->model('logbooks_model');
    $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

    $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_SUBMODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
    $this->db->where_in('station_id', $logbooks_locations_array);
    $this->db->order_by("COL_TIME_ON", "desc");
    $this->db->limit(10);

    return $this->db->get($this->config->item('table_name'));
  }

  /* Show custom number of qsos */
  function last_custom($num) {
    $CI =& get_instance();
    $CI->load->model('logbooks_model');
    $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

    if(!empty($logbooks_locations_array)) {
      $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_SUBMODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
      $this->db->where_in('station_id', $logbooks_locations_array);
      $this->db->order_by("COL_TIME_ON", "desc");
      $this->db->limit($num);

      return $this->db->get($this->config->item('table_name'));
    } else {
      return false;
    }
  }

  /*
  *
  * Function: call_lookup_result
  *
  * Usage: Callsign lookup data for the QSO panel and API/callsign_lookup
  *
  */
  function call_lookup_result($callsign) {
    $this->db->select('COL_CALL, COL_NAME, COL_QSL_VIA, COL_GRIDSQUARE, COL_QTH, COL_IOTA, COL_TIME_ON, COL_STATE, COL_CNTY');
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
    }

    return $data;
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

    function call_state($callsign) {
        $this->db->select('COL_CALL, COL_STATE');
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
            $qsl_state = $data->COL_STATE;
        }

        return $qsl_state;
    }

    function call_us_county($callsign) {
        $this->db->select('COL_CALL, COL_CNTY');
        $this->db->where('COL_CALL', $callsign);
        $where = "COL_CNTY != \"\"";

        $this->db->where($where);

        $this->db->order_by("COL_TIME_ON", "desc");
        $this->db->limit(1);
        $query = $this->db->get($this->config->item('table_name'));
        if ($query->num_rows() > 0)
        {
            $data = $query->row();
            $qsl_county = $data->COL_CNTY;
            $qsl_county = substr($qsl_county, (strpos($qsl_county, ',')+1));
            return $qsl_county;
        } else {
          return NULL;
        }
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
  
  
  // Set Paper to received
  function paperqsl_update($qso_id, $method) {
      
      $data = array(
          'COL_QSLRDATE' => date('Y-m-d H:i:s'),
          'COL_QSL_RCVD' => 'Y',
          'COL_QSL_RCVD_VIA' => $method
      );
      
      $this->db->where('COL_PRIMARY_KEY', $qso_id);
      
      $this->db->update($this->config->item('table_name'), $data);
  }


  // Set Paper to sent
  function paperqsl_update_sent($qso_id, $method) {
      
      $data = array(
          'COL_QSLSDATE' => date('Y-m-d H:i:s'),
          'COL_QSL_SENT' => 'Y',
          'COL_QSL_SENT_VIA' => $method
      );
      
      $this->db->where('COL_PRIMARY_KEY', $qso_id);
      
      $this->db->update($this->config->item('table_name'), $data);
  }


  // Set Paper to requested
  function paperqsl_requested($qso_id, $method) {

    $data = array(
         'COL_QSLSDATE' => date('Y-m-d H:i:s'),
         'COL_QSL_SENT' => 'R'
    );

    $this->db->where('COL_PRIMARY_KEY', $qso_id);

    $this->db->update($this->config->item('table_name'), $data);
  }


  function paperqsl_ignore($qso_id, $method) {

    $data = array(
         'COL_QSLSDATE' => date('Y-m-d H:i:s'),
         'COL_QSL_SENT' => 'I'
    );

    $this->db->where('COL_PRIMARY_KEY', $qso_id);

    $this->db->update($this->config->item('table_name'), $data);
  }

  function get_qsos_for_printing($station_id2 = null) {

	$CI =& get_instance();
    $CI->load->model('Stations');
    $station_id = $CI->Stations->find_active();

    $sql = 'SELECT
				STATION_CALLSIGN,
				COL_PRIMARY_KEY,
				COL_CALL,
				COL_QSL_VIA,
				COL_TIME_ON,
				COL_MODE,
				COL_SUBMODE,
				COL_FREQ,
				UPPER(COL_BAND) as COL_BAND,
				COL_RST_SENT,
				COL_SAT_NAME,
				COL_SAT_MODE,
				COL_QSL_RCVD,
				COL_COMMENT,
				(select adif from dxcc_prefixes where  (CASE WHEN COL_QSL_VIA != \'\' THEN COL_QSL_VIA ELSE COL_CALL END) like concat(dxcc_prefixes.`call`,\'%\') order by end limit 1) as ADIF,
				(select entity from dxcc_prefixes where  (CASE WHEN COL_QSL_VIA != \'\' THEN COL_QSL_VIA ELSE COL_CALL END) like concat(dxcc_prefixes.`call`,\'%\') order by end limit 1) as ENTITY,
       			(CASE WHEN COL_QSL_VIA != \'\' THEN COL_QSL_VIA ELSE COL_CALL END) AS COL_ROUTING
			FROM '.$this->config->item('table_name').' thcv
				join station_profile on thcv.station_id = station_profile.station_id
			WHERE
				COL_QSL_SENT in (\'R\', \'Q\')';

    if ($station_id2 == NULL) {
    	$sql .= ' and thcv.station_id = ' . $station_id;
	} else if ($station_id2 != 'All') {
		$sql .= ' and thcv.station_id = ' . $station_id2;
	}

	// always filter user. this ensures that even if the station_id is from another user no inaccesible QSOs will be returned
	$sql .= ' and station_profile.user_id = ' . $this->session->userdata('user_id');

	$sql .= ' ORDER BY ADIF, COL_ROUTING';

    $query = $this->db->query($sql);
    return $query;
  }

  function get_qsos($num, $offset, $StationLocationsArray = null) {
    if($StationLocationsArray == null) {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
    } else {
      $logbooks_locations_array = $StationLocationsArray;
    }

    if (empty($logbooks_locations_array)) {
      return array();
    }

    $this->db->from($this->config->item('table_name'));

    $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
    $this->db->where_in('station_profile.station_id', $logbooks_locations_array);
    $this->db->order_by(''.$this->config->item('table_name').'.COL_TIME_ON', "desc");

    $this->db->limit($num);
    $this->db->offset($offset);

    return $this->db->get();
  }

  function get_qso($id) {
    $this->db->from($this->config->item('table_name'));
    $this->db->join('dxcc_entities', $this->config->item('table_name').'.col_dxcc = dxcc_entities.adif', 'left');
    $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
    $this->db->where('COL_PRIMARY_KEY', $id);

    return $this->db->get();
  }

    /*
     * Function returns the QSOs from the logbook, which have not been either marked as uploaded to qrz, or has been modified with an edit
     */
    function get_qrz_qsos($station_id){
        $sql = 'select * from ' . $this->config->item('table_name') . ' thcv ' .
            ' join station_profile on thcv.station_id = station_profile.station_id' .
            ' where thcv.station_id = ' . $station_id .
            ' and (COL_QRZCOM_QSO_UPLOAD_STATUS is NULL
            or COL_QRZCOM_QSO_UPLOAD_STATUS = ""
            or COL_QRZCOM_QSO_UPLOAD_STATUS = "M"
            or COL_QRZCOM_QSO_UPLOAD_STATUS = "N")';

        $query = $this->db->query($sql);
        return $query;
    }

    /*
     * Function returns all the station_id's with QRZ API Key's
     */
    function get_station_id_with_qrz_api() {
        $sql = 'select station_id, qrzapikey from station_profile
            where coalesce(qrzapikey, "") <> ""';

        $query = $this->db->query($sql);

        $result = $query->result();

        if ($result) {
            return $result;
        }
        else {
            return null;
        }
    }

  function get_last_qsos($num, $StationLocationsArray = null) {

    if($StationLocationsArray == null) {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
    } else {
      $logbooks_locations_array = $StationLocationsArray;
    }

    if ($logbooks_locations_array) {
      $this->db->where_in($this->config->item('table_name').'.station_id', $logbooks_locations_array);
      $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
      $this->db->join('dxcc_entities', $this->config->item('table_name').'.col_dxcc = dxcc_entities.adif', 'left');
      $this->db->order_by("COL_TIME_ON", "desc");
      $this->db->limit($num);
      $query = $this->db->get($this->config->item('table_name'));
  
      return $query;
    } else {
      return null;
    }

  }

    /* Get all QSOs with a valid grid for use in the KML export */
    function kml_get_all_qsos($band, $mode, $dxcc, $cqz, $propagation, $fromdate, $todate) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_SUBMODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME, COL_GRIDSQUARE');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where("coalesce(COL_GRIDSQUARE, '') <> ''");

        if ($band != 'All') {
            if ($band == 'SAT') {
                $this->db->where('COL_PROP_MODE = \'' . $band . '\'');
            }
            else {
                $this->db->where('COL_PROP_MODE != \'SAT\'');
                $this->db->where('COL_BAND = \'' . $band .'\'');
            }
        }

        if ($mode != 'All') {
            $this->db->where('COL_MODE = \'' . $mode . '\'');
        }

        if ($dxcc != 'All') {
            $this->db->where('COL_DXCC = ' . $dxcc);
        }

        if ($cqz != 'All') {
            $this->db->where('COL_CQZ = ' . $cqz);
        }

        if ($propagation != 'All') {
            $this->db->where('COL_PROP_MODE = ' . $propagation);
        }

        // If date is set, we format the date and add it to the where-statement
        if ($fromdate != "") {
            $from = DateTime::createFromFormat('d/m/Y', $fromdate);
            $from = $from->format('Y-m-d');
            $this->db->where("date(".$this->config->item('table_name').".COL_TIME_ON) >= '".$from."'");
        }
        if ($todate != "") {
            $to = DateTime::createFromFormat('d/m/Y', $todate);
            $to = $to->format('Y-m-d');
            $this->db->where("date(".$this->config->item('table_name').".COL_TIME_ON) <= '".$to."'");
        }

        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    function get_date_qsos($date) {
        $this->db->select('COL_CALL, COL_BAND, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_SUBMODE, COL_NAME, COL_COUNTRY, COL_PRIMARY_KEY, COL_SAT_NAME');
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

    $CI =& get_instance();
    $CI->load->model('logbooks_model');
    $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

    if (!$logbooks_locations_array) {
      return null;
    }

    $this->db->select('DATE_FORMAT(COL_TIME_ON, \'%Y\') as \'year\',COUNT(COL_PRIMARY_KEY) as \'total\'', FALSE);
    $this->db->where_in('station_id', $logbooks_locations_array);
    $this->db->group_by('DATE_FORMAT(COL_TIME_ON, \'%Y\')');
    $this->db->order_by('year', 'ASC');

    $query = $this->db->get($this->config->item('table_name'));

    return $query;
  }

    /* Return total number of qsos */
    function total_qsos($StationLocationsArray = null) {
      if($StationLocationsArray == null) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
      } else {
        $logbooks_locations_array = $StationLocationsArray;
      }

      if ($logbooks_locations_array) {
        $this->db->select('COUNT( * ) as count', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
        $query = $this->db->get($this->config->item('table_name'));

        if ($query->num_rows() > 0)
        {
          foreach ($query->result() as $row)
          {
            return $row->count;
          }
        }
      } else {
        return null;
      }
    }

    /* Return number of QSOs had today */
    function todays_qsos($StationLocationsArray = null) {
      if($StationLocationsArray == null) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
      } else {
        $logbooks_locations_array = $StationLocationsArray;
      }

      if ($logbooks_locations_array) {
        $morning = date('Y-m-d 00:00:00');
        $night = date('Y-m-d 23:59:59');

        $this->db->select('COUNT( * ) as count', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_TIME_ON >=', $morning);
        $this->db->where('COL_TIME_ON <=', $night);
        $query = $this->db->get($this->config->item('table_name'));

        if ($query->num_rows() > 0)
        {
          foreach ($query->result() as $row)
          {
            return $row->count;
          }
        }
      } else {
        return null;
      }
     
    }

    /* Return QSOs over a period of days */
    function map_week_qsos($start, $end) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->order_by("COL_TIME_ON", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    /* used to return custom qsos requires start, end date plus a band */
    function map_custom_qsos($start, $end, $band, $mode, $propagation) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

    if (!$logbooks_locations_array) {
      return null;
    }

      $this->db->join('dxcc_entities', $this->config->item('table_name').'.col_dxcc = dxcc_entities.adif', 'left');
      $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
      $this->db->where_in("station_id", $logbooks_locations_array);

      if($band != "All" && $band != "SAT") {
        $this->db->where("COL_BAND", $band);
      }

      if ($band == "SAT") {
        $this->db->where("COL_PROP_MODE", "SAT");
      }

      if ($mode != 'All') {
        $this->db->group_start();
        $this->db->where("COL_MODE", $mode);
        $this->db->or_where("COL_SUBMODE", $mode);
				$this->db->group_end();
      }

      if ($propagation != 'All') {
        $this->db->where("COL_PROP_MODE", $propagation);
      }

      $this->db->order_by("COL_TIME_ON", "ASC");
      $query = $this->db->get($this->config->item('table_name'));

      return $query;
  }

    /* Returns QSOs for the date sent eg 2011-09-30 */
    function map_day($date) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $start = $date." 00:00:00";
        $end = $date." 23:59:59";

        $this->db->where("COL_TIME_ON BETWEEN '".$start."' AND '".$end."'");
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->order_by("COL_TIME_ON", "ASC");
        $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    // Return QSOs made during the current month
    function month_qsos($StationLocationsArray = null) {
      if($StationLocationsArray == null) { 
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
      } else {
        $logbooks_locations_array = $StationLocationsArray;
      }

      if ($logbooks_locations_array) {

        $morning = date('Y-m-01 00:00:00');

        $date = new DateTime('now');
        $date->modify('last day of this month');

        $night = $date->format('Y-m-d')." 23:59:59";

        $this->db->select('COUNT( * ) as count', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_TIME_ON >=', $morning);
        $this->db->where('COL_TIME_ON <=', $night);
        $query = $this->db->get($this->config->item('table_name'));

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
      } else {
        return null;
      }
    }

    /* Return QSOs for the year for the active profile */
    function map_all_qsos_for_active_station_profile() {
      $CI =& get_instance();
      $CI->load->model('Stations');
      $station_id = $CI->Stations->find_active();

      $this->db->where("station_id", $station_id);
      $this->db->order_by("COL_TIME_ON", "ASC");
      $query = $this->db->get($this->config->item('table_name'));

      return $query;
    }


    /* Return QSOs made during the current Year */
    function year_qsos($StationLocationsArray = null) {

      if($StationLocationsArray == null) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
      } else {
        $logbooks_locations_array = $StationLocationsArray;
      }

      if ($logbooks_locations_array) {

        $morning = date('Y-01-01 00:00:00');
        $night = date('Y-12-31 23:59:59');

        $this->db->select('COUNT( * ) as count', FALSE);
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_TIME_ON >=', $morning);
        $this->db->where('COL_TIME_ON <=', $night);
        $query = $this->db->get($this->config->item('table_name'));

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                 return $row->count;
            }
        }
      } else {
        return null;
      }
    }

    /* Return total amount of SSB QSOs logged */
    function total_ssb() {

      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if (!$logbooks_locations_array) {
        return null;
      }
      $mode[] = 'SSB';
      $mode[] = 'LSB';
      $mode[] = 'USB';

      $this->db->select('COUNT( * ) as count', FALSE);
      $this->db->where_in('station_id', $logbooks_locations_array);
      $this->db->where_in('COL_MODE', $mode);

      $query = $this->db->get($this->config->item('table_name'));

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

      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if (!$logbooks_locations_array) {
        return null;
      }

      $this->db->select('COL_SAT_NAME, COUNT( * ) as count', FALSE);
      $this->db->where_in('station_id', $logbooks_locations_array);
      $this->db->where('COL_SAT_NAME is not null');
      $this->db->where('COL_SAT_NAME !=', '');
      $this->db->group_by('COL_SAT_NAME');
      $query = $this->db->get($this->config->item('table_name'));

        return $query;
    }

    /* Return total number of CW QSOs */
    function total_cw() {

      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if (!$logbooks_locations_array) {
        return null;
      }

      $this->db->select('COUNT( * ) as count', FALSE);
      $this->db->where_in('station_id', $logbooks_locations_array);
      $this->db->where('COL_MODE', 'CW');
      $query = $this->db->get($this->config->item('table_name'));

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

      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if (!$logbooks_locations_array) {
        return null;
      }

      $this->db->select('COUNT( * ) as count', FALSE);
      $this->db->where_in('station_id', $logbooks_locations_array);
      $this->db->where('COL_MODE', 'FM');
      $query = $this->db->get($this->config->item('table_name'));

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

      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if (!$logbooks_locations_array) {
        return null;
      }

      $this->db->select('COUNT( * ) as count', FALSE);
      $this->db->where_in('station_id', $logbooks_locations_array);
      $this->db->where('COL_MODE !=', 'SSB');
      $this->db->where('COL_MODE !=', 'LSB');
      $this->db->where('COL_MODE !=', 'USB');
      $this->db->where('COL_MODE !=', 'CW');
      $this->db->where('COL_MODE !=', 'FM');
      $this->db->where('COL_MODE !=', 'AM');

      $query = $this->db->get($this->config->item('table_name'));

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
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if (!$logbooks_locations_array) {
        return null;
      }

      $this->db->select('COL_BAND AS band, count( * ) AS count', FALSE);
      $this->db->where_in('station_id', $logbooks_locations_array);
      $this->db->group_by('band');
      $this->db->order_by('count', 'DESC');

      $query = $this->db->get($this->config->item('table_name'));

      return $query;
    }

    function get_QSLStats($StationLocationsArray = null) {
          
      if($StationLocationsArray == null) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
      } else {
        $logbooks_locations_array = $StationLocationsArray;
      }

      if(!empty($logbooks_locations_array)) {
        $this->db->select('
          COUNT(IF(COL_QSL_SENT="Y",COL_QSL_SENT,null)) as QSL_Sent,
          COUNT(IF(COL_QSL_RCVD="Y",COL_QSL_RCVD,null)) as QSL_Received,
          COUNT(IF(COL_QSL_SENT IN("Q", "R") ,COL_QSL_SENT,null)) as QSL_Requested,
          COUNT(IF(COL_EQSL_QSL_SENT="Y",COL_EQSL_QSL_SENT,null)) as eQSL_Sent,
          COUNT(IF(COL_EQSL_QSL_RCVD="Y",COL_EQSL_QSL_RCVD,null)) as eQSL_Received,
          COUNT(IF(COL_LOTW_QSL_SENT="Y",COL_LOTW_QSL_SENT,null)) as LoTW_Sent,
          COUNT(IF(COL_LOTW_QSL_RCVD="Y",COL_LOTW_QSL_RCVD,null)) as LoTW_Received
        ');
        $this->db->where_in('station_id', $logbooks_locations_array);

        if ($query = $this->db->get($this->config->item('table_name')))
        {
          $this->db->last_query();
            foreach ($query->result() as $row)
            {
                    $QSLBreakdown['QSL_Sent'] = $row->QSL_Sent;
                    $QSLBreakdown['QSL_Received'] =  $row->QSL_Received;
                    $QSLBreakdown['QSL_Requested'] =  $row->QSL_Requested;
                    $QSLBreakdown['eQSL_Sent'] =  $row->eQSL_Sent;
                    $QSLBreakdown['eQSL_Received'] =  $row->eQSL_Received;
                    $QSLBreakdown['LoTW_Sent'] =  $row->LoTW_Sent;
                    $QSLBreakdown['LoTW_Received'] =  $row->LoTW_Received;
            }

            return $QSLBreakdown;
        }
        else
        {
            $QSLBreakdown['QSL_Sent'] = 0;
            $QSLBreakdown['QSL_Received'] =  0;
            $QSLBreakdown['QSL_Requested'] =  0;
            $QSLBreakdown['eQSL_Sent'] =  0;
            $QSLBreakdown['eQSL_Received'] =  0;
            $QSLBreakdown['LoTW_Sent'] =  0;
            $QSLBreakdown['LoTW_Received'] = 0;

            return $QSLBreakdown;
        }
      } else {
            $QSLBreakdown['QSL_Sent'] = 0;
            $QSLBreakdown['QSL_Received'] =  0;
            $QSLBreakdown['QSL_Requested'] =  0;
            $QSLBreakdown['eQSL_Sent'] =  0;
            $QSLBreakdown['eQSL_Received'] =  0;
            $QSLBreakdown['LoTW_Sent'] =  0;
            $QSLBreakdown['LoTW_Received'] = 0;

            return $QSLBreakdown;
      }
    }

    /* Return total number of QSL Cards sent */
    function total_qsl_sent() {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if(!empty($logbooks_locations_array)) {
        $this->db->select('count(COL_QSL_SENT) AS count');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_QSL_SENT =', 'Y');

        $query = $this->db->get($this->config->item('table_name'));

        $row = $query->row();

        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
      } else {
        return 0;
      }
    }

    /* Return total number of QSL Cards requested for printing - that means "requested" or "queued" */
    function total_qsl_requested() {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if(!empty($logbooks_locations_array)) {
        $this->db->select('count(COL_QSL_SENT) AS count');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where_in('COL_QSL_SENT', array('Q', 'R'));

        $query = $this->db->get($this->config->item('table_name'));

        $row = $query->row();

        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
      } else {
        return 0;
      }
    }

    /* Return total number of QSL Cards received */
    function total_qsl_recv() {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if(!empty($logbooks_locations_array)) {
        $this->db->select('count(COL_QSL_RCVD) AS count');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_QSL_RCVD =', 'Y');

        $query = $this->db->get($this->config->item('table_name'));

        $row = $query->row();
  
        if($row == null) {
            return 0;
        } else {
            return $row->count;
        }
      } else {
        return 0;
      }
    }

    /* Return total number of eQSL Cards sent */
    function total_eqsl_sent() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if(!empty($logbooks_locations_array)) {
            $this->db->select('count(COL_EQSL_QSL_SENT) AS count');
            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('COL_EQSL_QSL_SENT =', 'Y');

            $query = $this->db->get($this->config->item('table_name'));

            $row = $query->row();

            if($row == null) {
                return 0;
            } else {
                return $row->count;
            }
        } else {
            return 0;
        }
    }

    /* Return total number of eQSL Cards received */
    function total_eqsl_recv() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if(!empty($logbooks_locations_array)) {
            $this->db->select('count(COL_EQSL_QSL_RCVD) AS count');
            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('COL_EQSL_QSL_RCVD =', 'Y');

            $query = $this->db->get($this->config->item('table_name'));

            $row = $query->row();

            if($row == null) {
                return 0;
            } else {
                return $row->count;
            }
        } else {
            return 0;
        }
    }

    /* Return total number of LotW sent */
    function total_lotw_sent() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if(!empty($logbooks_locations_array)) {
            $this->db->select('count(COL_LOTW_QSL_SENT) AS count');
            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('COL_LOTW_QSL_SENT =', 'Y');

            $query = $this->db->get($this->config->item('table_name'));

            $row = $query->row();

            if($row == null) {
                return 0;
            } else {
                return $row->count;
            }
        } else {
            return 0;
        }
    }

    /* Return total number of LotW received */
    function total_lotw_recv() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if(!empty($logbooks_locations_array)) {
            $this->db->select('count(COL_LOTW_QSL_RCVD) AS count');
            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('COL_LOTW_QSL_RCVD =', 'Y');

            $query = $this->db->get($this->config->item('table_name'));

            $row = $query->row();

            if($row == null) {
                return 0;
            } else {
                return $row->count;
            }
        } else {
            return 0;
        }
    }

    /* Return total number of countries worked */
    function total_countries() {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        if(!empty($logbooks_locations_array)) {
          $this->db->select('DISTINCT (COL_COUNTRY)');
          $this->db->where_in('station_id', $logbooks_locations_array);
          $this->db->where('COL_COUNTRY !=', 'Invalid');
          $this->db->where('COL_DXCC >', '0');
          $query = $this->db->get($this->config->item('table_name'));
  
          return $query->num_rows();
        } else {
          return 0;
        }
    }

    /* Return total number of countries worked */
    function total_countries_current($StationLocationsArray = null) {
      if($StationLocationsArray == null) {
        $CI =& get_instance();
        $CI->load->model('logbooks_model');
        $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
      } else {
        $logbooks_locations_array = $StationLocationsArray;
      }

        if(!empty($logbooks_locations_array)) {
          $this->db->select('DISTINCT ('.$this->config->item('table_name').'.COL_COUNTRY)');
          $this->db->join('dxcc_entities', 'dxcc_entities.adif = '.$this->config->item('table_name').'.col_dxcc');
          $this->db->where_in($this->config->item('table_name').'.station_id', $logbooks_locations_array);
          $this->db->where($this->config->item('table_name').'.COL_COUNTRY !=', 'Invalid');
          $this->db->where('dxcc_entities.end is null');
          $query = $this->db->get($this->config->item('table_name'));

          return $query->num_rows();
        } else {
          return 0;
        }
    }

        /* Return total number of countries confirmed with along with qsl types confirmed */
        function total_countries_confirmed($StationLocationsArray = null) {
          
          if($StationLocationsArray == null) {
            $CI =& get_instance();
            $CI->load->model('logbooks_model');
            $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
          } else {
            $logbooks_locations_array = $StationLocationsArray;
          }

          if(!empty($logbooks_locations_array)) {
            $this->db->select('COUNT(DISTINCT COL_COUNTRY) as Countries_Worked,
            COUNT(DISTINCT IF(COL_QSL_RCVD = "Y", COL_COUNTRY, NULL)) as Countries_Worked_QSL,
            COUNT(DISTINCT IF(COL_EQSL_QSL_RCVD = "Y", COL_COUNTRY, NULL)) as Countries_Worked_EQSL,  
            COUNT(DISTINCT IF(COL_LOTW_QSL_RCVD = "Y", COL_COUNTRY, NULL)) as Countries_Worked_LOTW');
            $this->db->where_in('station_id', $logbooks_locations_array);
            $this->db->where('COL_COUNTRY !=', 'Invalid');
            $this->db->where('COL_DXCC >', '0');

            if ($query = $this->db->get($this->config->item('table_name')))
            {
                foreach ($query->result() as $row)
                {
                        $CountriesBreakdown['Countries_Worked'] = $row->Countries_Worked;
                        $CountriesBreakdown['Countries_Worked_QSL'] =  $row->Countries_Worked_QSL;
                        $CountriesBreakdown['Countries_Worked_EQSL'] =  $row->Countries_Worked_EQSL;
                        $CountriesBreakdown['Countries_Worked_LOTW'] =  $row->Countries_Worked_LOTW;
                }

                return $CountriesBreakdown;
            }
            else
            {
              $CountriesBreakdown['Countries_Worked'] = 0;
              $CountriesBreakdown['Countries_Worked_QSL'] = 0;
              $CountriesBreakdown['Countries_Worked_EQSL'] = 0;
              $CountriesBreakdown['Countries_Worked_LOTW'] = 0;
              return $CountriesBreakdown;
            }
          } else {
              $CountriesBreakdown['Countries_Worked'] = 0;
              $CountriesBreakdown['Countries_Worked_QSL'] = 0;
              $CountriesBreakdown['Countries_Worked_EQSL'] = 0;
              $CountriesBreakdown['Countries_Worked_LOTW'] = 0;
              return $CountriesBreakdown;
          }
        }

    /* Return total number of countries confirmed with paper QSL */
    function total_countries_confirmed_paper() {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if(!empty($logbooks_locations_array)) {
        $this->db->select('DISTINCT (COL_COUNTRY)');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_COUNTRY !=', 'Invalid');
        $this->db->where('COL_DXCC >', '0');
        $this->db->where('COL_QSL_RCVD =', 'Y');
        $query = $this->db->get($this->config->item('table_name'));

        return $query->num_rows();
      } else {
        return 0;
      }
    }

    /* Return total number of countries confirmed with eQSL */
    function total_countries_confirmed_eqsl() {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if(!empty($logbooks_locations_array)) {
        $this->db->select('DISTINCT (COL_COUNTRY)');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_COUNTRY !=', 'Invalid');
        $this->db->where('COL_DXCC >', '0');
        $this->db->where('COL_EQSL_QSL_RCVD =', 'Y');
        $query = $this->db->get($this->config->item('table_name'));

        return $query->num_rows();
      } else {
        return 0;
      }
    }

    /* Return total number of countries confirmed with LoTW */
    function total_countries_confirmed_lotw() {
      $CI =& get_instance();
      $CI->load->model('logbooks_model');
      $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

      if(!empty($logbooks_locations_array)) {
        $this->db->select('DISTINCT (COL_COUNTRY)');
        $this->db->where_in('station_id', $logbooks_locations_array);
        $this->db->where('COL_COUNTRY !=', 'Invalid');
        $this->db->where('COL_DXCC >', '0');
        $this->db->where('COL_LOTW_QSL_RCVD =', 'Y');
        $query = $this->db->get($this->config->item('table_name'));

        return $query->num_rows();
      } else {
        return 0;
      }
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

  function lotw_update($datetime, $callsign, $band, $qsl_date, $qsl_status, $state, $qsl_gridsquare, $iota) {

	$data = array(
      'COL_LOTW_QSLRDATE' => $qsl_date,
      'COL_LOTW_QSL_RCVD' => $qsl_status,
      'COL_LOTW_QSL_SENT' => 'Y'
    );
	if($state != "") {
      $data['COL_STATE'] = $state;
	}
	if($iota != "") {
      $data['COL_IOTA'] = $iota;
	}

    $this->db->where('date_format(COL_TIME_ON, \'%Y-%m-%d %H:%i\') = "'.$datetime.'"');
    $this->db->where('COL_CALL', $callsign);
    $this->db->where('COL_BAND', $band);

    $this->db->update($this->config->item('table_name'), $data);
	unset($data);

	if($qsl_gridsquare != "") {
      $data = array(
        'COL_GRIDSQUARE' => $qsl_gridsquare
      );
      $this->db->where('date_format(COL_TIME_ON, \'%Y-%m-%d %H:%i\') = "'.$datetime.'"');
      $this->db->where('COL_CALL', $callsign);
      $this->db->where('COL_BAND', $band);
	  $this->db->group_start();
	  $this->db->where('COL_VUCC_GRIDS');
	  $this->db->or_where('COL_VUCC_GRIDS', '');
	  $this->db->group_end();
      if(strlen($qsl_gridsquare) > 4) {
        $this->db->group_start();
        $this->db->where('COL_GRIDSQUARE', "");
        $this->db->or_where('COL_GRIDSQUARE', substr($qsl_gridsquare, 0, 4));
        if(strlen($qsl_gridsquare) > 6) {
          $this->db->or_where('COL_GRIDSQUARE', substr($qsl_gridsquare, 0, 6));
        }
        $this->db->group_end();
      } else {
        $this->db->where('COL_GRIDSQUARE', "");
      }

      $this->db->update($this->config->item('table_name'), $data);
    }

    return "Updated";
  }

  function lotw_last_qsl_date() {
      $this->db->select('COL_LOTW_QSLRDATE');
      $this->db->where('COL_LOTW_QSLRDATE IS NOT NULL');
      $this->db->order_by("COL_LOTW_QSLRDATE", "desc");
      $this->db->limit(1);

      $query = $this->db->get($this->config->item('table_name'));
      $row = $query->row();

      if (isset($row)) {
        return $row->COL_LOTW_QSLRDATE;
      }

      return '1900-01-01 00:00:00.000';
    }

//////////////////////////////
  // Update a QSO with eQSL QSL info
  // We could also probably use this use this: http://eqsl.cc/qslcard/VerifyQSO.txt
  // http://www.eqsl.cc/qslcard/ImportADIF.txt
  function eqsl_update($datetime, $callsign, $band, $qsl_status) {
    $data = array(
         'COL_EQSL_QSLRDATE' => date('Y-m-d H:i:s'), // eQSL doesn't give us a date, so let's use current
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
         'COL_EQSL_QSLSDATE' => date('Y-m-d H:i:s'), // eQSL doesn't give us a date, so let's use current
         'COL_EQSL_QSL_SENT' => 'Y',
    );

    $this->db->where('COL_PRIMARY_KEY', $primarykey);

    $this->db->update($this->config->item('table_name'), $data);

    return "eQSL Sent";
  }

  // Get the last date we received an eQSL
  function eqsl_last_qsl_rcvd_date($callsign, $nickname) {
	  $qso_table_name = $this->config->item('table_name');
	  $this->db->from($qso_table_name);

	  $this->db->join('station_profile',
		  'station_profile.station_id = '.$qso_table_name.'.station_id AND station_profile.eqslqthnickname != ""');
	  $this->db->where('station_profile.station_callsign', $callsign);
	  $this->db->where('station_profile.eqslqthnickname', $nickname);

      $this->db->select("DATE_FORMAT(COL_EQSL_QSLRDATE,'%Y%m%d') AS COL_EQSL_QSLRDATE", FALSE);
      $this->db->where('COL_EQSL_QSLRDATE IS NOT NULL');
      $this->db->order_by("COL_EQSL_QSLRDATE", "desc");
      $this->db->limit(1);

      $query = $this->db->get();
      $row = $query->row();

      if (isset($row->COL_EQSL_QSLRDATE)){
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
      $this->db->select('station_profile.*, '.$this->config->item('table_name').'.COL_PRIMARY_KEY, '.$this->config->item('table_name').'.COL_TIME_ON, '.$this->config->item('table_name').'.COL_CALL, '.$this->config->item('table_name').'.COL_MODE, '.$this->config->item('table_name').'.COL_SUBMODE, '.$this->config->item('table_name').'.COL_BAND, '.$this->config->item('table_name').'.COL_COMMENT, '.$this->config->item('table_name').'.COL_RST_SENT, '.$this->config->item('table_name').'.COL_PROP_MODE, '.$this->config->item('table_name').'.COL_SAT_NAME, '.$this->config->item('table_name').'.COL_SAT_MODE, '.$this->config->item('table_name').'.COL_QSLMSG');
      $this->db->from('station_profile');
      $this->db->join($this->config->item('table_name'),'station_profile.station_id = '.$this->config->item('table_name').'.station_id AND station_profile.eqslqthnickname != ""','right');
      $this->db->where('station_profile.eqslqthnickname !=', '');
      $this->db->where($this->config->item('table_name').'.COL_CALL !=', '');
      $this->db->where($this->config->item('table_name').'.COL_EQSL_QSL_SENT !=', 'Y');
      $this->db->where($this->config->item('table_name').'.COL_EQSL_QSL_SENT !=', 'I');
      $this->db->or_where(array($this->config->item('table_name').'.COL_EQSL_QSL_SENT' => NULL));
      return $this->db->get();
    }

    /*
     * $skipDuplicate - used in ADIF import to skip duplicate checking when importing QSOs
     * $markLoTW - used in ADIF import to mark QSOs as exported to LoTW when importing QSOs
     * $dxccAdif - used in ADIF import to determine if DXCC From ADIF is used, or if Cloudlog should try to guess
     * $markQrz - used in ADIF import to mark QSOs as exported to QRZ Logbook when importing QSOs
     * $skipexport - used in ADIF import to skip the realtime upload to QRZ Logbook when importing QSOs from ADIF
     */
	function import($record, $station_id = "0", $skipDuplicate = false, $markLotw = false, $dxccAdif = false, $markQrz = false, $skipexport = false, $operatorName = false, $apicall = false) {
        // be sure that station belongs to user
        $CI =& get_instance();
        $CI->load->model('Stations');
        if (!$CI->Stations->check_station_is_accessible($station_id) && $apicall == false ) {
            return 'Station not accessible<br>';
        }

        $CI =& get_instance();
        $CI->load->library('frequency');
        $my_error = "";

        // Join date+time
        $time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i:s', strtotime($record['time_on']));

        if (isset($record['time_off'])) {
            if (isset($record['date_off'])) {
                // date_off and time_off set
                $time_off = date('Y-m-d', strtotime($record['date_off'])) . ' ' . date('H:i:s', strtotime($record['time_off']));
            } elseif (strtotime($record['time_off']) < strtotime($record['time_on'])) {
                // date_off is not set, QSO ends next day
                $time_off = date('Y-m-d', strtotime($record['qso_date'] . ' + 1 day')) . ' ' . date('H:i:s', strtotime($record['time_off']));
            } else {
                // date_off is not set, QSO ends same day
                $time_off = date('Y-m-d', strtotime($record['qso_date'])) . ' ' . date('H:i:s', strtotime($record['time_off']));
            }
        } else {
            // date_off and time_off not set, QSO end == QSO start
            $time_off = $time_on;
        }

        // Store Freq
        // Check if 'freq' is defined in the import?
        if (isset($record['freq'])){ // record[freq] in MHz
          $freq = floatval($record['freq']) * 1E6; // store in Hz
        } else {
          $freq = 0;
        }

        // Check for RX Freq
        // Check if 'freq' is defined in the import?
        if (isset($record['freq_rx'])){ // record[freq] in MHz
          $freqRX = floatval($record['freq_rx']) * 1E6; // store in Hz
        } else {
          $freqRX = NULL;
        }

        // DXCC id
        if (isset($record['call'])){
          if ($dxccAdif != NULL) {
              if (isset($record['dxcc'])) {
                  $entity = $this->get_entity($record['dxcc']);
                  $dxcc = array($record['dxcc'], $entity['name']);
              } else {
                  $dxcc = $this->check_dxcc_table($record['call'], $time_off);
              }
          } else {
            $dxcc = $this->check_dxcc_table($record['call'], $time_off);
          }
      } else {
        $dxcc = NULL;
      }

        // Store or find country name
        // dxcc has higher priority to be consistent with qso create and edit
        if (isset($dxcc[1])) {
            $country = ucwords(strtolower($dxcc[1]), "- (/");
        } else if (isset($record['country'])) {
            $country = $record['country'];
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
                $band = strtolower($record['band']);
        } else {
            if (isset($record['freq'])){
              if($freq != "0") {
                $band = $CI->frequency->GetBand($freq);
              }
            }
        }

        if(isset($record['band_rx'])) {
                $band_rx = strtolower($record['band_rx']);
        } else {
                if (isset($record['freq_rx'])){
                  if($freq != "0") {
                    $band_rx = $CI->frequency->GetBand($freqRX);
                  }
                } else {
                  $band_rx = "";
                }
        }

        if(isset($record['cqz'])) {
          $cq_zone = $record['cqz'];
        } elseif(isset($dxcc[2])) {
          $cq_zone = $dxcc[2];
        } else {
          $cq_zone = NULL;
        }

        // Sanitise lat input to make sure its 11 chars
        if (isset($record['lat'])){
            $input_lat = mb_strimwidth($record['lat'], 0, 11);
        }else{
            $input_lat = NULL;
        }

        // Sanitise lon input to make sure its 11 chars
        if (isset($record['lon'])){
            $input_lon = mb_strimwidth($record['lon'], 0, 11);
        }else{
            $input_lon = NULL;
        }

        // Sanitise my_lat input to make sure its 11 chars
        if (isset($record['my_lat'])){
            $input_my_lat = mb_strimwidth($record['my_lat'], 0, 11);
        }else{
            $input_my_lat = NULL;
        }

        // Sanitise my_lon input to make sure its 11 chars
        if (isset($record['my_lon'])){
            $input_my_lon = mb_strimwidth($record['my_lon'], 0, 11);
        }else{
            $input_my_lon = NULL;
        }

        // Sanitise TX_POWER
        if (isset($record['tx_pwr'])){
            $tx_pwr = filter_var($record['tx_pwr'],FILTER_VALIDATE_FLOAT);
        }else{
            $tx_pwr = NULL;
        }

        // Sanitise RX Power
        if (isset($record['rx_pwr'])){
          // Check if RX_PWR is "K" which N1MM+ uses to indicate 1000W
          if($record['rx_pwr'] == "K") {
            $rx_pwr = 1000;
          } else {
            $rx_pwr = filter_var($record['rx_pwr'],FILTER_VALIDATE_FLOAT);
          }
        }else{
          $rx_pwr = NULL;
         }

        if (isset($record['a_index'])){
            $input_a_index = filter_var($record['a_index'],FILTER_SANITIZE_NUMBER_INT);
        } else {
            $input_a_index = NULL;
        }

        if (isset($record['age'])){
            $input_age = filter_var($record['age'],FILTER_SANITIZE_NUMBER_INT);
        } else {
            $input_age = NULL;
        }

        if (isset($record['ant_az'])){
            $input_ant_az = filter_var($record['ant_az'],FILTER_SANITIZE_NUMBER_INT);
        } else {
            $input_ant_az = NULL;
        }

        if (isset($record['ant_el'])){
            $input_ant_el = filter_var($record['ant_el'],FILTER_SANITIZE_NUMBER_INT);
        } else {
            $input_ant_el = NULL;
        }

        if (isset($record['ant_path'])){
            $input_ant_path = mb_strimwidth($record['ant_path'], 0, 1);
        } else {
            $input_ant_path = NULL;
        }

        /*
          Validate QSL Fields
         qslrdate, qslsdate
        */

        if (isset($record['qslrdate'])){
            if(validateADIFDate($record['qslrdate']) == true){
              $input_qslrdate = $record['qslrdate'];
            } else {
              $input_qslrdate = NULL;
              $my_error .= "Error QSO: Date: ".$time_on." Callsign: ".$record['call']." the qslrdate is invalid (YYYYMMDD): ".$record['qslrdate']."<br>";
            }
        } else {
            $input_qslrdate = NULL;
        }

        if (isset($record['qslsdate'])){
            if(validateADIFDate($record['qslsdate']) == true){
              $input_qslsdate = $record['qslsdate'];
            } else {
              $input_qslsdate = NULL;
              $my_error .= "Error QSO: Date: ".$time_on." Callsign: ".$record['call']." the qslsdate is invalid (YYYYMMDD): ".$record['qslsdate']."<br>";
            }
        } else {
            $input_qslsdate = NULL;
        }

        if (isset($record['qsl_rcvd'])){
            $input_qsl_rcvd = mb_strimwidth($record['qsl_rcvd'], 0, 1);
        } else {
            $input_qsl_rcvd = "N";
        }

        if (isset($record['qsl_rcvd_via'])){
            $input_qsl_rcvd_via = mb_strimwidth($record['qsl_rcvd_via'], 0, 1);
        } else {
            $input_qsl_rcvd_via = "";
        }

        if (isset($record['qsl_sent'])){
            $input_qsl_sent = mb_strimwidth($record['qsl_sent'], 0, 1);
        } else {
            $input_qsl_sent = "N";
        }

        if (isset($record['qsl_sent_via'])){
            $input_qsl_sent_via = mb_strimwidth($record['qsl_sent_via'], 0, 1);
        } else {
            $input_qsl_sent_via = "";
        }

        /*
          Validate LOTW Fields
        */
        if (isset($record['lotw_qsl_rcvd'])){
            $input_lotw_qsl_rcvd = mb_strimwidth($record['lotw_qsl_rcvd'], 0, 1);
        } else {
            $input_lotw_qsl_rcvd = NULL;
        }

        if (isset($record['lotw_qsl_sent'])){
            $input_lotw_qsl_sent = mb_strimwidth($record['lotw_qsl_sent'], 0, 1);
        } else if ($markLotw != NULL) {
            $input_lotw_qsl_sent = "Y";
        } else {
            $input_lotw_qsl_sent = NULL;
        }

        if (isset($record['lotw_qslrdate'])){
            if(validateADIFDate($record['lotw_qslrdate']) == true){
              $input_lotw_qslrdate = $record['lotw_qslrdate'];
            } else {
              $input_lotw_qslrdate = NULL;
              $my_error .= "Error QSO: Date: ".$time_on." Callsign: ".$record['call']." the lotw_qslrdate is invalid (YYYYMMDD): ".$record['lotw_qslrdate']."<br>";
            }
        } else {
            $input_lotw_qslrdate = NULL;
        }

        if (isset($record['lotw_qslsdate'])){
            if(validateADIFDate($record['lotw_qslsdate']) == true){
              $input_lotw_qslsdate = $record['lotw_qslsdate'];
            } else {
              $input_lotw_qslsdate = NULL;
              $my_error .= "Error QSO: Date: ".$time_on." Callsign: ".$record['call']." the lotw_qslsdate is invalid (YYYYMMDD): ".$record['lotw_qslsdate']."<br>";
            }
        } else if ($markLotw != NULL) {
            $input_lotw_qslsdate = $date = date("Y-m-d H:i:s", strtotime("now"));
        } else {
            $input_lotw_qslsdate = NULL;
        }

        if (isset($record['mode'])) {
            $input_mode = $record['mode'];
        } else {
            $input_mode = '';
        }

        $mode = $this->get_main_mode_if_submode($input_mode);
        if ($mode == null) {
            $submode = null;
        } else {
            $submode = $input_mode;
            $input_mode = $mode;
        }

        if (empty($submode)) {
            $input_submode = (!empty($record['submode'])) ? $record['submode'] : '';
        } else {
            $input_submode = $submode;
        }

        // Get active station_id from station profile if one hasn't been provided
        if($station_id == "" || $station_id == "0") {
          $CI =& get_instance();
          $CI->load->model('Stations');
          $station_id = $CI->Stations->find_active();
        }

        // Check if QSO is already in the database
        if ($skipDuplicate != NULL) {
            $skip = false;
        } else {
            if (isset($record['call'])){
                $this->db->where('COL_CALL', $record['call']);
            }
            $this->db->where('COL_TIME_ON', $time_on);
            $this->db->where('COL_BAND', $band);
            $this->db->where('COL_MODE', $input_mode);
            $this->db->where('station_id', $station_id);
            $check = $this->db->get($this->config->item('table_name'));

            // If dupe is not found, set variable to add QSO
            if ($check->num_rows() <= 0) {
                $skip = false;
            } else {
                $skip = true;
            }
        }

        if ($operatorName != false) {
			$operatorName = $this->session->userdata('user_callsign');
		} else {
			$operatorName = (!empty($record['operator'])) ? $record['operator'] : '';
		}

        // If user checked to mark QSOs as uploaded to QRZ Logbook, or else we try to find info in ADIF import.
        if ($markQrz != null) {
            $input_qrzcom_qso_upload_status = 'Y';
            $input_qrzcom_qso_upload_date = $date = date("Y-m-d H:i:s", strtotime("now"));
        }
        else {
            $input_qrzcom_qso_upload_date = (!empty($record['qrzcom_qso_upload_date'])) ? $record['qrzcom_qso_upload_date'] : null;
            $input_qrzcom_qso_upload_status = (!empty($record['qrzcom_qso_upload_status'])) ? $record['qrzcom_qso_upload_status'] : '';
        }

        if (!$skip)
        {
            // Create array with QSO Data use ?:
            $data = array(
                'COL_A_INDEX' => $input_a_index,
                'COL_ADDRESS' => (!empty($record['address'])) ? $record['address'] : '',
                'COL_ADDRESS_INTL' => (!empty($record['address_intl'])) ? $record['address_intl'] : '',
                'COL_AGE' => $input_age,
                'COL_ANT_AZ' => $input_ant_az,
                'COL_ANT_EL' => $input_ant_el,
                'COL_ANT_PATH' => $input_ant_path,
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
                'COL_DARC_DOK' => (!empty($record['darc_dok'])) ? strtoupper($record['darc_dok']) : '',
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
                'COL_LAT' => $input_lat,
                'COL_LON' => $input_lon,
                'COL_LOTW_QSL_RCVD' => $input_lotw_qsl_rcvd,
                'COL_LOTW_QSL_SENT' => $input_lotw_qsl_sent,
                'COL_LOTW_QSLRDATE' => $input_lotw_qslrdate,
                'COL_LOTW_QSLSDATE' => $input_lotw_qslsdate,
                'COL_LOTW_STATUS' => (!empty($record['lotw_status'])) ? $record['lotw_status'] : '',
                'COL_MAX_BURSTS' => (!empty($record['max_bursts'])) ? $record['max_bursts'] : null,
                'COL_MODE' => $input_mode,
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
                'COL_MY_LAT' => $input_my_lat,
                'COL_MY_LON' => $input_my_lon,
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
                'COL_MY_WWFF_REF' => (!empty($record['my_wwff_ref'])) ? $record['my_wwff_ref'] : '',
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
                'COL_OPERATOR' => $operatorName,
                'COL_OWNER_CALLSIGN' => (!empty($record['owner_callsign'])) ? $record['owner_callsign'] : '',
                'COL_PFX' => (!empty($record['pfx'])) ? $record['pfx'] : '',
                'COL_PRECEDENCE' => (!empty($record['precedence'])) ? $record['precedence'] : '',
                'COL_PROP_MODE' => (!empty($record['prop_mode'])) ? $record['prop_mode'] : '',
                'COL_PUBLIC_KEY' => (!empty($record['public_key'])) ? $record['public_key'] : '',
                'COL_QRZCOM_QSO_UPLOAD_DATE' => $input_qrzcom_qso_upload_date,
                'COL_QRZCOM_QSO_UPLOAD_STATUS' => $input_qrzcom_qso_upload_status,
                'COL_QSL_RCVD' => $input_qsl_rcvd,
                'COL_QSL_RCVD_VIA' => $input_qsl_rcvd_via,
                'COL_QSL_SENT' => $input_qsl_sent,
                'COL_QSL_SENT_VIA' => $input_qsl_sent_via,
                'COL_QSL_VIA' => (!empty($record['qsl_via'])) ? $record['qsl_via'] : '',
                'COL_QSLMSG' => (!empty($record['qslmsg'])) ? $record['qslmsg'] : '',
                'COL_QSLRDATE' => $input_qslrdate,
                'COL_QSLSDATE' => $input_qslsdate,
                'COL_QSO_COMPLETE' => (!empty($record['qso_complete'])) ? $record['qso_complete'] : '',
                'COL_QSO_DATE' => (!empty($record['qso_date'])) ? $record['qso_date'] : null,
                'COL_QSO_DATE_OFF' => (!empty($record['qso_date_off'])) ? $record['qso_date_off'] : null,
                'COL_QTH' => (!empty($record['qth'])) ? $record['qth'] : '',
                'COL_QTH_INTL' => (!empty($record['qth_intl'])) ? $record['qth_intl'] : '',
                'COL_REGION' => (!empty($record['region'])) ? $record['region'] : '',
                'COL_RIG' => (!empty($record['rig'])) ? $record['rig'] : '',
                'COL_RIG_INTL' => (!empty($record['rig_intl'])) ? $record['rig_intl'] : '',
                'COL_RST_RCVD' => $rst_rx,
                'COL_RST_SENT' => $rst_tx,
                'COL_RX_PWR' => $rx_pwr,
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
                'COL_WWFF_REF' => (!empty($record['wwff_ref'])) ? $record['wwff_ref'] : '',
                'COL_SRX' => (!empty($record['srx'])) ? (int)$record['srx'] : null,
                			//convert to integer to make sure no invalid entries are imported 
				'COL_SRX_STRING' => (!empty($record['srx_string'])) ? $record['srx_string'] : '',
                'COL_STATE' => (!empty($record['state'])) ? strtoupper($record['state']) : '',
                'COL_STATION_CALLSIGN' => (!empty($record['station_callsign'])) ? $record['station_callsign'] : '',
						 //convert to integer to make sure no invalid entries are imported 
                'COL_STX' => (!empty($record['stx'])) ? (int)$record['stx'] : null,
                'COL_STX_STRING' => (!empty($record['stx_string'])) ? $record['stx_string'] : '',
                'COL_SUBMODE' => $input_submode,
                'COL_SWL' => (!empty($record['swl'])) ? $record['swl'] : null,
                'COL_TEN_TEN' => (!empty($record['ten_ten'])) ? $record['ten_ten'] : null,
                'COL_TIME_ON' => $time_on,
                'COL_TIME_OFF' => $time_off,
                'COL_TX_PWR' => (!empty($tx_pwr)) ? $tx_pwr : null,
                'COL_UKSMG' => (!empty($record['uksmg'])) ? $record['uksmg'] : '',
                'COL_USACA_COUNTIES' => (!empty($record['usaca_counties'])) ? $record['usaca_counties'] : '',
                'COL_VUCC_GRIDS' =>((!empty($record['vucc_grids']))) ? $record['vucc_grids'] : '',
                'COL_WEB' => (!empty($record['web'])) ? $record['web'] : ''
            );

            // Collect field information from the station profile table thats required for the QSO.
            if($station_id != "0") {
              $station_result = $this->db->where('station_id', $station_id)
                                ->get('station_profile');

                if ($station_result->num_rows() > 0){
                    $data['station_id'] = $station_id;

                    $row = $station_result->row_array();

                    if (strpos(trim($row['station_gridsquare']), ',') !== false) {
                      $data['COL_MY_VUCC_GRIDS'] = strtoupper(trim($row['station_gridsquare']));
                    } else {
                      $data['COL_MY_GRIDSQUARE'] = strtoupper(trim($row['station_gridsquare']));
                    }

                    $data['COL_MY_CITY'] = trim($row['station_city']);
                    $data['COL_MY_IOTA'] = strtoupper(trim($row['station_iota']));
                    $data['COL_MY_SOTA_REF'] = strtoupper(trim($row['station_sota']));
                    $data['COL_MY_WWFF_REF'] = strtoupper(trim($row['station_wwff']));

                    $data['COL_STATION_CALLSIGN'] = strtoupper(trim($row['station_callsign']));
                    $data['COL_MY_DXCC'] = strtoupper(trim($row['station_dxcc']));
                    $data['COL_MY_COUNTRY'] = strtoupper(trim($row['station_country']));
                    $data['COL_MY_CNTY'] = strtoupper(trim($row['station_cnty']));
                    $data['COL_MY_CQ_ZONE'] = strtoupper(trim($row['station_cq']));
                    $data['COL_MY_ITU_ZONE'] = strtoupper(trim($row['station_itu']));
                }
            }

            // Save QSO
            $this->add_qso($data, $skipexport);
        } else {
          $my_error .= "Date/Time: ".$time_on." Callsign: ".$record['call']." Band: ".$band."  Duplicate<br>";
        }

        return $my_error;
    }

    function get_main_mode_if_submode($mode) {
		$this->db->select('mode');
        $this->db->where('submode', $mode);

        $query = $this->db->get('adif_modes');
        if ($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['mode'];
        } else {
            return null;
        }
	}

    /*
     * Check the dxxc_prefixes table and return (dxcc, country)
     */
    public function check_dxcc_table($call, $date){

		$dxcc_exceptions = $this->db->select('`entity`, `adif`, `cqz`')
             ->where('call', $call)
             ->where('(start <= ', $date)
             ->or_where('start is null)', NULL, false)
             ->where('(end >= ', $date)
             ->or_where('end is null)', NULL, false)
             ->get('dxcc_exceptions');

		if ($dxcc_exceptions->num_rows() > 0){
			$row = $dxcc_exceptions->row_array();
			return array($row['adif'], $row['entity'], $row['cqz']);
		}

		if (preg_match('/(^KG4)[A-Z09]{3,}/', $call)) { 	// KG4/ and KG4 5 char calls are Guantanamo Bay. If 6 char, it is USA
			$call = "K";
		} elseif (preg_match_all('/^((\d|[A-Z])+\/)?((\d|[A-Z]){3,})(\/(\d|[A-Z])+)?(\/(\d|[A-Z])+)?$/', $call, $matches)) {
			if ($matches[5][0] == '/MM') {
				$row['adif'] = 0;
				$row['entity'] = 'None';
				$row['cqz'] = 0;
				return array($row['adif'], $row['entity'], $row['cqz']);
			}
    	}

		$len = strlen($call);

		// query the table, removing a character from the right until a match
		for ($i = $len; $i > 0; $i--){
            //printf("searching for %s\n", substr($call, 0, $i));
            $dxcc_result = $this->db->select('`call`, `entity`, `adif`, `cqz`')
                                    ->where('call', substr($call, 0, $i))
                                    ->where('(start <= ', $date)
                                    ->or_where("start is null)", NULL, false)
                                    ->where('(end >= ', $date)
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

		$dxcc_exceptions = $this->db->select('`entity`, `adif`, `cqz`')
				->where('call', $call)
				->where('(start <= CURDATE()')
				->or_where('start is null)', NULL, false)
				->where('(end >= CURDATE()')
				->or_where('end is null)', NULL, false)
				->get('dxcc_exceptions');


			if ($dxcc_exceptions->num_rows() > 0){
				$row = $dxcc_exceptions->row_array();
				return $row;
			} else {

				if (preg_match('/(^KG4)[A-Z09]{3,}/', $call)) { 	// KG4/ and KG4 5 char calls are Guantanamo Bay. If 6 char, it is USA
					$call = "K";
				} elseif (preg_match_all('/^((\d|[A-Z])+\/)?((\d|[A-Z]){3,})(\/(\d|[A-Z])+)?(\/(\d|[A-Z])+)?$/', $call, $matches)) {
					if ($matches[5][0] == '/MM') {
						$row['adif'] = 0;
						$row['entity'] = 'None';
						$row['cqz'] = 0;
						$row['long'] = '0';
						$row['lat'] = '0';
						return $row;
					}
    			}

				$len = strlen($call);

				// query the table, removing a character from the right until a match
				for ($i = $len; $i > 0; $i--){
					//printf("searching for %s\n", substr($call, 0, $i));
					$dxcc_result = $this->db->select('*')
										  ->where('call', substr($call, 0, $i))
										  ->where('(start <= ', $date)
										  ->or_where("start is null)", NULL, false)
										  ->where('(end >= ', $date)
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

    public function get_entity($dxcc){
      $sql = "select name, cqz, lat, 'long' from dxcc_entities where adif = " . $dxcc;
      $query = $this->db->query($sql);

      if ($query->result() > 0){
          $row = $query->row_array();
          return $row;
      }
      return '';
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
                                    $this->config->item('table_name'), addslashes(ucwords(strtolower($d[1]), "- (/")), $d[0], $row['COL_PRIMARY_KEY']);
                    $this->db->query($sql);
                    //print($sql."\n");
                    printf("Updating %s to %s and %s\n<br/>", $row['COL_PRIMARY_KEY'], ucwords(strtolower($d[1]), "- (/"), $d[0]);
                    $count++;
                }
            }
        }
        $this->db->trans_complete();

        print("$count updated\n");
    }

	public function check_missing_grid_id($all){
        // get all records with no COL_GRIDSQUARE
        $this->db->select("COL_PRIMARY_KEY, COL_CALL, COL_TIME_ON, COL_TIME_OFF");

        // check which to update - records with no Gridsquare or all records
        $this->db->where("COL_GRIDSQUARE is NULL or COL_GRIDSQUARE = ''");

        $where = "(COL_GRIDSQUARE is NULL or COL_GRIDSQUARE = '') AND (COL_VUCC_GRIDS is NULL or COL_VUCC_GRIDS = '')";
        $this->db->where($where);

        $r = $this->db->get($this->config->item('table_name'));

        $count = 0;
        $this->db->trans_start();
        if ($r->num_rows() > 0){
            foreach($r->result_array() as $row){
		          $callsign = $row['COL_CALL'];
              if ($this->config->item('callbook') == "qrz" && $this->config->item('qrz_username') != null && $this->config->item('qrz_password') != null)
              {
                  // Lookup using QRZ
                  $this->load->library('qrz');

                  if(!$this->session->userdata('qrz_session_key')) {
                      $qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
                      $this->session->set_userdata('qrz_session_key', $qrz_session_key);
                  }

                  $callbook = $this->qrz->search($callsign, $this->session->userdata('qrz_session_key'));
              }

              if ($this->config->item('callbook') == "hamqth" && $this->config->item('hamqth_username') != null && $this->config->item('hamqth_password') != null)
              {
                  // Load the HamQTH library
                  $this->load->library('hamqth');

                  if(!$this->session->userdata('hamqth_session_key')) {
                      $hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
                      $this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
                  }

                  $callbook = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));

                  // If HamQTH session has expired, start a new session and retry the search.
                  if($callbook['error'] == "Session does not exist or expired") {
                      $hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
                      $this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
                      $callbook = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));
                  }
              }
              if (isset($callbook))
              {
                  $return['callsign_qra'] = $callbook['gridsquare'];
              }
              if ($return['callsign_qra'] != ''){
                  $sql = sprintf("update %s set COL_GRIDSQUARE = '%s' where COL_PRIMARY_KEY=%d",
                                  $this->config->item('table_name'), $return['callsign_qra'], $row['COL_PRIMARY_KEY']);
                  $this->db->query($sql);
                  printf("Updating %s to %s\n<br/>", $row['COL_PRIMARY_KEY'], $return['callsign_qra']);
                  $count++;
              }
            }
        }
        $this->db->trans_complete();

        print("$count updated\n");
    }

    public function check_for_station_id() {
      $this->db->where('station_id =', 'NULL');
      $query = $this->db->get($this->config->item('table_name'));
      if($query->num_rows() >= 1) {
        return 1;
      } else {
        return 0;
      }
    }

    public function loadCallBook($callsign, $use_fullname=false)
    {
        $callbook = null;
        try {
            if ($this->config->item('callbook') == "qrz" && $this->config->item('qrz_username') != null && $this->config->item('qrz_password') != null) {
                // Lookup using QRZ
                $this->load->library('qrz');

                if (!$this->session->userdata('qrz_session_key')) {
                    $qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
                    $this->session->set_userdata('qrz_session_key', $qrz_session_key);
                }



                $callbook = $this->qrz->search($callsign, $this->session->userdata('qrz_session_key'), $use_fullname);

                // if we got nothing, it's probably because our session key is invalid, try again
                if (!isset($callbook['callsign']))
                {
                    $qrz_session_key = $this->qrz->session($this->config->item('qrz_username'), $this->config->item('qrz_password'));
                    $this->session->set_userdata('qrz_session_key', $qrz_session_key);
                    $callbook = $this->qrz->search($callsign, $this->session->userdata('qrz_session_key'), $use_fullname);
                }
            }

            if ($this->config->item('callbook') == "hamqth" && $this->config->item('hamqth_username') != null && $this->config->item('hamqth_password') != null) {
                // Load the HamQTH library
                $this->load->library('hamqth');

                if (!$this->session->userdata('hamqth_session_key')) {
                    $hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
                    $this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
                }

                $callbook = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));

                // If HamQTH session has expired, start a new session and retry the search.
                if ($callbook['error'] == "Session does not exist or expired") {
                    $hamqth_session_key = $this->hamqth->session($this->config->item('hamqth_username'), $this->config->item('hamqth_password'));
                    $this->session->set_userdata('hamqth_session_key', $hamqth_session_key);
                    $callbook = $this->hamqth->search($callsign, $this->session->userdata('hamqth_session_key'));
                }
            }
        } finally {
            return $callbook;
        }
    }

    public function update_all_station_ids() {

      $data = array(
        'station_id' => '1',
      );

      $this->db->where(array('station_id' => NULL));
      return $this->db->update($this->config->item('table_name'), $data);
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

    /*
     * This function returns the the whole list of dxcc_entities used in various places
     */
    function fetchDxcc() {
        $sql = "select adif, prefix, name, date(end) Enddate, date(start) Startdate from dxcc_entities";

        $sql .= ' order by prefix';
        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * This function returns the whole list of iotas used in various places
     */
    function fetchIota() {
        $sql = "select tag, name from iota";

        $sql .= ' order by tag';
        $query = $this->db->query($sql);

        return $query->result();
    }

    /*
     * This function tries to locate the correct station_id used for importing QSOs from the downloaded LoTWreport
     * $station_callsign is the call listed for the qso in lotwreport
     * $my_gridsquare is the gridsquare listed for the qso in lotwreport
     * Returns station_id if found
     */
    function find_correct_station_id($station_callsign, $my_gridsquare) {
        $sql = 'select station_id from station_profile
            where station_callsign = "' . $station_callsign . '" and station_gridsquare like "%' . substr($my_gridsquare,0, 4) . '%"';

        $query = $this->db->query($sql);

        $result = $query->row();

        if ($result) {
            return $result->station_id;
        }
        else {
            return null;
        }
    }

  function get_lotw_qsos_to_upload($station_id, $start_date, $end_date) {

    $this->db->select('COL_PRIMARY_KEY,COL_CALL, COL_BAND, COL_BAND_RX, COL_TIME_ON, COL_RST_RCVD, COL_RST_SENT, COL_MODE, COL_SUBMODE, COL_FREQ, COL_FREQ_RX, COL_GRIDSQUARE, COL_SAT_NAME, COL_PROP_MODE, COL_LOTW_QSL_SENT, station_id');

    $this->db->where("station_id", $station_id);
    $this->db->group_start();
    $this->db->where('COL_LOTW_QSL_SENT', NULL);
    $this->db->or_where('COL_LOTW_QSL_SENT !=', "Y");
    $this->db->group_end();
    $this->db->where('COL_PROP_MODE !=', "INTERNET");
    $this->db->where('COL_TIME_ON >=', $start_date);
    $this->db->where('COL_TIME_ON <=', $end_date);
    $this->db->order_by("COL_TIME_ON", "desc");

    $query = $this->db->get($this->config->item('table_name'));

    return $query;
  }

  function mark_lotw_sent($qso_id) {

      $data = array(
           'COL_LOTW_QSLSDATE' => date("Y-m-d H:i:s"),
           'COL_LOTW_QSL_SENT' => 'Y',
      );


    $this->db->where('COL_PRIMARY_KEY', $qso_id);

    $this->db->update($this->config->item('table_name'), $data);

    return "Updated";
  }

    function county_qso_details($state, $county) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

        $this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
        $this->db->where_in($this->config->item('table_name').'.station_id', $logbooks_locations_array);
        $this->db->where('COL_STATE', $state);
        $this->db->where('COL_CNTY', $county);
        $this->db->where('COL_PROP_MODE !=', 'SAT');

        return $this->db->get($this->config->item('table_name'));
    }

    public function check_qso_is_accessible($id) {
        // check if qso belongs to user
        $this->db->select($this->config->item('table_name').'.COL_PRIMARY_KEY');
        $this->db->join('station_profile', $this->config->item('table_name').'.station_id = station_profile.station_id');
        $this->db->where('station_profile.user_id', $this->session->userdata('user_id'));
        $this->db->where($this->config->item('table_name').'.COL_PRIMARY_KEY', $id);
        $query = $this->db->get($this->config->item('table_name'));
        if ($query->num_rows() == 1) {
            return true;
        }
        return false;
    }

}

function validateADIFDate($date, $format = 'Ymd')
{
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) == $date;
}


?>
