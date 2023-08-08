<?php

/* api_model.php
 *
 * Provides API functions to the web frontend
 *
 */

class API_Model extends CI_Model {

    // GET API Keys
    function keys() {
		$this->db->where('user_id', $this->session->userdata('user_id'));
    	return $this->db->get('api');
    }

	function CountKeysWithNoUserID() {
		$this->db->where('user_id =', NULL);
		$query = $this->db->get('api');
		return $query->num_rows();
    }

	function ClaimAllAPIKeys($id = NULL) {
		// if $id is empty then use session user_id
		if (empty($id)) {
			// Get the first USER ID from user table in the database
			$id = $this->db->get("users")->row()->user_id;
		}

		$data = array(
				'user_id' => $id,
		);
			
		$this->db->update('api', $data);
	}

    function key_description($key) {
		$this->db->where('user_id', $this->session->userdata('user_id'));
    	$this->db->where('key', $key);
    	$query = $this->db->get('api');

    	return $query->result_array()[0];
    }

	function key_userid($key) {
    	$this->db->where('key', $key);
    	$query = $this->db->get('api');

    	return $query->result_array()[0]['user_id'];
    }

    function update_key_description($key, $description) {

    	$data = array(
        'description' => xss_clean($description),
		);

		$this->db->where('key', xss_clean($key));
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->update('api', xss_clean($data));

    }


    function delete_key($key) {
		$this->db->where('user_id', $this->session->userdata('user_id'));
    	$this->db->where('key', xss_clean($key));
		$this->db->delete('api');
    }
    // Generate API Key
    function generate_key($rights) {

    	// Expects either rw (Read, Write) or r (read only)

    	// Generate Unique Key
    	$data['key'] = uniqid("cl");

    	$data['rights'] = $rights;

    	// Set API key to active
    	$data['status'] = "active";

		$data['user_id'] = $this->session->userdata('user_id');

    	$this->db->insert('api', $data);

    }

    function access($key) {

      // No key = no access, mate
      if(!$key) {
        return $status = "No Key Found";
      }

    	// Check that the key is valid
    	$this->db->where('key', $key);
     	$query = $this->db->get('api');

		  if ($query->num_rows() > 0)
  		{
        foreach ($query->result() as $row)
	      {
	     	  if($row->status == "active") {
	   	  	  return $status = $row->rights;
	   		  } else {
 		   		 	return $status = "Key Disabled";
  	   		}
	      }
		  } else {
			  return $status = "No Key Found";
  		}
    }

  function authorize($key) {
    $r = $this->access($key);
    if($r == "rw") {
      return 2;
    } else if($r == "r") {
      return 1;
    } else {
      return 0;
    }
  }

  function update_last_used($key) {
    $this->db->set('last_used', 'NOW()', FALSE);
    $this->db->where('key', xss_clean($key));
    $this->db->update('api');
  }

	// FUNCTION: string name(string $column)
	// Converts a MySQL column name to a more friendly name
	function name($col)
	{
		if($this->_columnName[$col])
		{
			return $this->_columnName[$col]['Name'];
		}
		else
		{
			return 0;
		}
	}

	// FUNCTION: string description(string $column)
	// Returns the description for a MySQL column name
	function description($col)
	{
		if($this->_columnName[$col])
		{
			if($this->_columnName[$col]['Description'] != "")
			{
				return $this->_columnName[$col]['Description'];
			}
			else
			{
				return "No description available";
			}
		}
		else
		{
			return 0;
		}
	}

	// FUNCTION: string name(string $name)
	// Converts a friendly name to a MySQL column name
	function column($name)
	{
		while ($column = current($this->_columnName))
		{
			if($this->_columnName[key($this->_columnName)]['Name'] == $name)
			{
				$a = key($this->_columnName);
				reset($this->_columnName);
				return $a;
			}
			next($this->_columnName);
		}

		reset($this->_columnName);
		return 0;
	}

	
	// ARRAY: $_columnName
	// An array matching MySQL column names to friendly names, descriptions and types
	private $_columnName = array(
		'COL_PRIMARY_KEY'				=> array('Name' => 'ID', 'Description' => 'Unique QSO ID', 'Type' => 'I'),
		'COL_ADDRESS'					=> array('Name' => 'Address', 'Description' => 'Operator\'s address', 'Type' => 'S'),
		'COL_AGE'						=> array('Name' => 'Age', 'Description' => 'Operator\'s age', 'Type' => 'I'),
		'COL_A_INDEX'					=> array('Name' => 'AIndex', 'Description' => 'Solar A Index', 'Type' => 'I'),
		'COL_ANT_AZ'					=> array('Name' => 'AntennaAzimuth', 'Description' => 'Antenna azimuth', 'Type' => 'I'),
		'COL_ANT_EL'					=> array('Name' => 'AntennaElevation', 'Description' => 'Antenna elevation', 'Type' => 'I'),
		'COL_ANT_PATH'					=> array('Name' => 'AntennaPath', 'Description' => 'Antenna path', 'Type' => ''),
		'COL_ARRL_SECT'					=> array('Name' => 'ARRLSection', 'Description' => 'ARRL Section', 'Type' => ''),
		'COL_BAND'						=> array('Name' => 'Band', 'Description' => 'Band', 'Type' => ''),
		'COL_BAND_RX'					=> array('Name' => 'BandRX', 'Description' => '', 'Type' => ''),
		'COL_BIOGRAPHY'					=> array('Name' => 'Biography', 'Description' => '', 'Type' => ''),
		'COL_CALL'						=> array('Name' => 'Call', 'Description' => '', 'Type' => ''),
		'COL_CHECK'						=> array('Name' => 'UNK_CHECK', 'Description' => '', 'Type' => ''),
		'COL_CLASS'						=> array('Name' => 'Class', 'Description' => '', 'Type' => ''),
		'COL_CNTY'						=> array('Name' => 'County', 'Description' => '', 'Type' => ''),
		'COL_COMMENT'					=> array('Name' => 'Comment', 'Description' => '', 'Type' => ''),
		'COL_CONT'						=> array('Name' => 'Continent', 'Description' => '', 'Type' => ''),
		'COL_CONTACTED_OP'				=> array('Name' => 'UNK_CONTACTED_OP', 'Description' => '', 'Type' => ''),
		'COL_CONTEST_ID'				=> array('Name' => 'ContestID', 'Description' => '', 'Type' => ''),
		'COL_COUNTRY'					=> array('Name' => 'Country', 'Description' => '', 'Type' => ''),
		'COL_CQZ'						=> array('Name' => 'CQZone', 'Description' => '', 'Type' => ''),
		'COL_DARC_DOK'					=> array('Name' => 'DOK', 'Description' => '', 'Type' => ''),
		'COL_DISTANCE'					=> array('Name' => 'Distance', 'Description' => '', 'Type' => ''),
		'COL_DXCC'						=> array('Name' => 'DXCC', 'Description' => '', 'Type' => ''),
		'COL_EMAIL'						=> array('Name' => 'EMail', 'Description' => '', 'Type' => ''),
		'COL_EQ_CALL'					=> array('Name' => 'UNK_EQ_CALL', 'Description' => '', 'Type' => ''),
		'COL_EQSL_QSLRDATE'				=> array('Name' => 'EQSLRecievedDate', 'Description' => '', 'Type' => ''),
		'COL_EQSL_QSLSDATE'				=> array('Name' => 'EQSLSentDate', 'Description' => '', 'Type' => ''),
		'COL_EQSL_QSL_RCVD'				=> array('Name' => 'EQSLRecieved', 'Description' => '', 'Type' => ''),
		'COL_EQSL_QSL_SENT'				=> array('Name' => 'EQSLSent', 'Description' => '', 'Type' => ''),
		'COL_EQSL_STATUS'				=> array('Name' => 'EQSLStatus', 'Description' => '', 'Type' => ''),
		'COL_FORCE_INIT'				=> array('Name' => 'UNK_FORCE_INIT', 'Description' => '', 'Type' => ''),
		'COL_FREQ'						=> array('Name' => 'Frequency', 'Description' => '', 'Type' => ''),
		'COL_FREQ_RX'					=> array('Name' => 'FrequencyRX', 'Description' => '', 'Type' => ''),
		'COL_GRIDSQUARE'				=> array('Name' => 'Locator', 'Description' => '', 'Type' => ''),
		'COL_HEADING'					=> array('Name' => 'Heading', 'Description' => '', 'Type' => ''),
		'COL_IOTA'						=> array('Name' => 'IOTA', 'Description' => '', 'Type' => ''),
		'COL_ITUZ'						=> array('Name' => 'ITUZone', 'Description' => '', 'Type' => ''),
		'COL_K_INDEX'					=> array('Name' => 'KIndex', 'Description' => '', 'Type' => ''),
		'COL_LAT'						=> array('Name' => 'Latitude', 'Description' => '', 'Type' => ''),
		'COL_LON'						=> array('Name' => 'Longitude', 'Description' => '', 'Type' => ''),
		'COL_LOTW_QSLRDATE'				=> array('Name' => 'LOTWRecievedDate', 'Description' => '', 'Type' => ''),
		'COL_LOTW_QSLSDATE'				=> array('Name' => 'LOTWSentDate', 'Description' => '', 'Type' => ''),
		'COL_LOTW_QSL_RCVD'				=> array('Name' => 'LOTWRecieved', 'Description' => '', 'Type' => ''),
		'COL_LOTW_QSL_SENT'				=> array('Name' => 'LOTWSent', 'Description' => '', 'Type' => ''),
		'COL_LOTW_STATUS'				=> array('Name' => 'LOTWStatus', 'Description' => '', 'Type' => ''),
		'COL_MAX_BURSTS'				=> array('Name' => 'MaxBursts', 'Description' => '', 'Type' => ''),
		'COL_MODE'						=> array('Name' => 'Mode', 'Description' => '', 'Type' => ''),
		'COL_MS_SHOWER'					=> array('Name' => 'MSShower', 'Description' => '', 'Type' => ''),
		'COL_MY_CITY'					=> array('Name' => 'MyCity', 'Description' => '', 'Type' => ''),
		'COL_MY_CNTY'					=> array('Name' => 'MyCounty', 'Description' => '', 'Type' => ''),
		'COL_MY_COUNTRY'				=> array('Name' => 'MyCountry', 'Description' => '', 'Type' => ''),
		'COL_MY_CQ_ZONE'				=> array('Name' => 'MyCQZone', 'Description' => '', 'Type' => ''),
		'COL_MY_GRIDSQUARE'				=> array('Name' => 'MyLocator', 'Description' => '', 'Type' => ''),
		'COL_MY_IOTA'					=> array('Name' => 'MyIOTA', 'Description' => '', 'Type' => ''),
		'COL_MY_ITU_ZONE'				=> array('Name' => 'MyITUZone', 'Description' => '', 'Type' => ''),
		'COL_MY_LAT'					=> array('Name' => 'MyLatitude', 'Description' => '', 'Type' => ''),
		'COL_MY_LON'					=> array('Name' => 'MyLongitude', 'Description' => '', 'Type' => ''),
		'COL_MY_NAME'					=> array('Name' => 'MyName', 'Description' => '', 'Type' => ''),
		'COL_MY_POSTAL_CODE'			=> array('Name' => 'MyPostalCode', 'Description' => '', 'Type' => ''),
		'COL_MY_RIG'					=> array('Name' => 'MyRig', 'Description' => '', 'Type' => ''),
		'COL_MY_SIG'					=> array('Name' => 'MySig', 'Description' => '', 'Type' => ''),
		'COL_MY_SIG_INFO'				=> array('Name' => 'MySigInfo', 'Description' => '', 'Type' => ''),
		'COL_MY_STATE'					=> array('Name' => 'MyState', 'Description' => '', 'Type' => ''),
		'COL_MY_STREET'					=> array('Name' => 'MyStreet', 'Description' => '', 'Type' => ''),
		'COL_NAME'						=> array('Name' => 'Name', 'Description' => '', 'Type' => ''),
		'COL_NOTES'						=> array('Name' => 'Notes', 'Description' => '', 'Type' => ''),
		'COL_NR_BURSTS'					=> array('Name' => 'NumBursts', 'Description' => '', 'Type' => ''),
		'COL_NR_PINGS'					=> array('Name' => 'NumPings', 'Description' => '', 'Type' => ''),
		'COL_OPERATOR'					=> array('Name' => 'Operator', 'Description' => '', 'Type' => ''),
		'COL_OWNER_CALLSIGN'			=> array('Name' => 'OwnerCallsign', 'Description' => '', 'Type' => ''),
		'COL_PFX'						=> array('Name' => 'Prefix', 'Description' => '', 'Type' => ''),
		'COL_PRECEDENCE'				=> array('Name' => 'Precedence', 'Description' => '', 'Type' => ''),
		'COL_PROP_MODE'					=> array('Name' => 'PropMode', 'Description' => '', 'Type' => ''),
		'COL_PUBLIC_KEY'				=> array('Name' => 'PublicKey', 'Description' => '', 'Type' => ''),
		'COL_QSLMSG'					=> array('Name' => 'QSLMessage', 'Description' => '', 'Type' => ''),
		'COL_QSLRDATE'					=> array('Name' => 'QSLRecievedDate', 'Description' => '', 'Type' => ''),
		'COL_QSLSDATE'					=> array('Name' => 'QSLSentDate', 'Description' => '', 'Type' => ''),
		'COL_QSL_RCVD'					=> array('Name' => 'QSLRecieved', 'Description' => '', 'Type' => ''),
		'COL_QSL_RCVD_VIA'				=> array('Name' => 'QSLRecievedVia', 'Description' => '', 'Type' => ''),
		'COL_QSL_SENT'					=> array('Name' => 'QSLSent', 'Description' => '', 'Type' => ''),
		'COL_QSL_SENT_VIA'				=> array('Name' => 'QSLSentVia', 'Description' => '', 'Type' => ''),
		'COL_QSL_VIA'					=> array('Name' => 'QSLVia', 'Description' => '', 'Type' => ''),
		'COL_QSO_COMPLETE'				=> array('Name' => 'QSOComplete', 'Description' => '', 'Type' => ''),
		'COL_QSO_RANDOM'				=> array('Name' => 'QSORandom', 'Description' => '', 'Type' => ''),
		'COL_QTH'						=> array('Name' => 'QTH', 'Description' => '', 'Type' => ''),
		'COL_RIG'						=> array('Name' => 'Rig', 'Description' => '', 'Type' => ''),
		'COL_RST_RCVD'					=> array('Name' => 'ReportRecieved', 'Description' => '', 'Type' => ''),
		'COL_RST_SENT'					=> array('Name' => 'ReportSent', 'Description' => '', 'Type' => ''),
		'COL_RX_PWR'					=> array('Name' => 'RXPower', 'Description' => '', 'Type' => ''),
		'COL_SAT_MODE'					=> array('Name' => 'SatMode', 'Description' => '', 'Type' => ''),
		'COL_SAT_NAME'					=> array('Name' => 'SatName', 'Description' => '', 'Type' => ''),
		'COL_SFI'						=> array('Name' => 'SFI', 'Description' => '', 'Type' => ''),
		'COL_SIG'						=> array('Name' => 'Sig', 'Description' => '', 'Type' => ''),
		'COL_SIG_INFO'					=> array('Name' => 'SigInfo', 'Description' => '', 'Type' => ''),
		'COL_SRX'						=> array('Name' => 'UNK_SRX', 'Description' => '', 'Type' => ''),
		'COL_STX'						=> array('Name' => 'UNK_STX', 'Description' => '', 'Type' => ''),
		'COL_SRX_STRING'				=> array('Name' => 'UNK_SRX_STRING', 'Description' => '', 'Type' => ''),
		'COL_STX_STRING'				=> array('Name' => 'UNK_STX_STRING', 'Description' => '', 'Type' => ''),
		'COL_STATE'						=> array('Name' => 'State', 'Description' => '', 'Type' => ''),
		'COL_STATION_CALLSIGN'			=> array('Name' => 'StationCall', 'Description' => '', 'Type' => ''),
		'COL_SWL'						=> array('Name' => 'SWL', 'Description' => '', 'Type' => ''),
		'COL_TEN_TEN'					=> array('Name' => 'TenTen', 'Description' => '', 'Type' => ''),
		'COL_TIME_OFF'					=> array('Name' => 'TimeOff', 'Description' => '', 'Type' => ''),
		'COL_TIME_ON'					=> array('Name' => 'TimeOn', 'Description' => '', 'Type' => ''),
		'COL_TX_PWR'					=> array('Name' => 'TXPower', 'Description' => '', 'Type' => ''),
		'COL_WEB'						=> array('Name' => 'Website', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_0'			=> array('Name' => 'UNK_USER_DEFINED_0', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_1'			=> array('Name' => 'UNK_USER_DEFINED_1', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_2'			=> array('Name' => 'UNK_USER_DEFINED_2', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_3'			=> array('Name' => 'UNK_USER_DEFINED_3', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_4'			=> array('Name' => 'UNK_USER_DEFINED_4', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_5'			=> array('Name' => 'UNK_USER_DEFINED_5', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_6'			=> array('Name' => 'UNK_USER_DEFINED_6', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_7'			=> array('Name' => 'UNK_USER_DEFINED_7', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_8'			=> array('Name' => 'UNK_USER_DEFINED_8', 'Description' => '', 'Type' => ''),
		'COL_USER_DEFINED_9'			=> array('Name' => 'UNK_USER_DEFINED_9', 'Description' => '', 'Type' => ''),
		'COL_CREDIT_GRANTED'			=> array('Name' => 'UNK_CREDIT_GRANTED', 'Description' => '', 'Type' => ''),
		'COL_CREDIT_SUBMITTED'			=> array('Name' => 'UNK_CREDIT_SUBMITTED', 'Description' => '', 'Type' => ''),
	);

}

?>
