<?php

class LotwCert extends CI_Model {

	/*
	|--------------------------------------------------------------------------
	| Function: lotw_certs
	|--------------------------------------------------------------------------
	|
	| Returns all lotw_certs for a selected user via the $user_id parameter
	|
	*/
	function lotw_certs($user_id) {

		$this->db->select('lotw_certs.lotw_cert_id as lotw_cert_id, lotw_certs.callsign as callsign, dxcc_entities.name as cert_dxcc, dxcc_entities.end as cert_dxcc_end, lotw_certs.qso_start_date as qso_start_date, lotw_certs.qso_end_date as qso_end_date, lotw_certs.date_created as date_created, lotw_certs.date_expires as date_expires, lotw_certs.last_upload as last_upload, lotw_certs.archived as archived');
		$this->db->where('user_id', $user_id);
		$this->db->join('dxcc_entities','lotw_certs.cert_dxcc_id = dxcc_entities.adif','left');
		$this->db->order_by('cert_dxcc', 'ASC');
		$query = $this->db->get('lotw_certs');

		return $query;
	}

	function lotw_cert_details($callsign, $dxcc) {
		$this->db->where('cert_dxcc_id', $dxcc);
		$this->db->where('callsign', $callsign);
		$query = $this->db->get('lotw_certs');

		return $query->row();
	}

	function find_cert($callsign, $dxcc, $user_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('cert_dxcc_id', $dxcc);
		$this->db->where('callsign', $callsign);
		$query = $this->db->get('lotw_certs');

		return $query->num_rows();
	}

	function store_certificate($user_id, $callsign, $dxcc, $date_created, $date_expires, $qso_start_date, $qso_end_date, $cert_key, $general_cert) {
		$data = array(
		    'user_id' => $user_id,
		    'callsign' => $callsign,
		    'cert_dxcc_id' => $dxcc,
		    'date_created' => $date_created,
		    'date_expires' => $date_expires,
		    'qso_start_date' => $qso_start_date,
		    'qso_end_date' => $qso_end_date . ' 23:59:59',
		    'cert_key' => $cert_key,
		    'cert' => $general_cert,
		);

		$this->db->insert('lotw_certs', $data);
	}

	function update_certificate($user_id, $callsign, $dxcc, $date_created, $date_expires, $qso_start_date, $qso_end_date, $cert_key, $general_cert) {
		$data = array(
		    'cert_dxcc_id' => $dxcc,
		    'date_created' => $date_created,
		    'date_expires' => $date_expires,
		    'qso_start_date' => $qso_start_date,
		    'qso_end_date' => $qso_end_date . ' 23:59:59',
		    'cert_key' => $cert_key,
		    'cert' => $general_cert
		);

		$this->db->where('user_id', $user_id);
		$this->db->where('callsign', $callsign);
		$this->db->where('cert_dxcc_id', $dxcc);
		$this->db->update('lotw_certs', $data);
	}

	function delete_certificate($user_id, $lotw_cert_id) {
		$this->db->where('lotw_cert_id', $lotw_cert_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('lotw_certs');
	}

	function toggle_archive_certificate($user_id, $lotw_cert_id) {
		// Use single UPDATE with calculated toggle instead of SELECT+UPDATE
		// archived = 1 - archived toggles between 0 and 1
		$this->db->set('archived', '1 - archived', FALSE);
		$this->db->where('lotw_cert_id', $lotw_cert_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('lotw_certs');
		
		if ($this->db->affected_rows() == 0) {
			return false;
		}
		
		// Get final status to return
		$this->db->select('archived');
		$this->db->where('lotw_cert_id', $lotw_cert_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('lotw_certs');
		
		if($query->num_rows() > 0) {
			return array('archived' => $query->row()->archived);
		}
		
		return false;
	}

	function last_upload($certID) {

      $data = array(
           'last_upload' => date("Y-m-d H:i:s"),
      );


    $this->db->where('lotw_cert_id', $certID);

    $this->db->update('lotw_certs', $data);

    return "Updated";
  }

	function empty_table($table) {
		$this->db->empty_table($table);
	}

   function lotw_cert_expired($user_id, $date) {
      $array = array('user_id' => $user_id, 'date_expires <' => $date, 'archived' => 0);
      $this->db->where($array);
      $query = $this->db->get('lotw_certs');

      if ($query->num_rows() > 0) {
         return true;
      } else {
         return false;
      }
   }

   function lotw_cert_expiring($user_id, $date) {
      $array = array('user_id' => $user_id, 'DATE_SUB(date_expires, INTERVAL 30 DAY) <' => $date, 'date_expires >' => $date, 'archived' => 0);
      $this->db->where($array);
      $query = $this->db->get('lotw_certs');

      if ($query->num_rows() > 0) {
         return true;
      } else {
         return false;
      }
   }

}
?>
