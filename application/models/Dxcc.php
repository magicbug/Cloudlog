<?php

class DXCC extends CI_Model {

	/**
	*	Function: mostactive
	*	Information: Returns the most active band
	**/
	function info($callsign)
	{
		$exceptions = $this->db->query('
				SELECT *
				FROM `dxcc_exceptions`
				WHERE `prefix` = \''.$callsign.'\'
				LIMIT 1
			');

		if ($exceptions->num_rows() > 0)
		{
			return $exceptions;
		} else {

			$query = $this->db->query('
					SELECT *
					FROM dxcc_entities
					WHERE prefix = SUBSTRING( \''.$callsign.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1
				');

			return $query;
		}
	}

    function search(){
        print_r($this->input->get());
        return;
    }

	function empty_table($table) {
		$this->db->empty_table($table);
	}

	/*
	 * Fetches a list of all dxcc's, both current and deleted
	 */
	function list() {
		$this->db->order_by('name', 'ASC');
		return $this->db->get('dxcc_entities');
	}

	/*
	 * Fetches a list of all current dxcc's (non-deleted)
	 */
	function list_current() {
		$this->db->where('end', null);
		$this->db->order_by('name', 'ASC');
		return $this->db->get('dxcc_entities');
	}

	function get_dxcc_array($dxccArray, $bands, $postdata) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		foreach ($bands as $band) {             	// Looping through bands and entities to generate the array needed for display
			foreach ($dxccArray as $dxcc) {
				$dxccMatrix[$dxcc->adif]['name'] = ucwords(strtolower($dxcc->name), "- (/");
				$dxccMatrix[$dxcc->adif]['Dxccprefix'] = $dxcc->prefix;
				if ($postdata['includedeleted'])
					$dxccMatrix[$dxcc->adif]['Deleted'] = isset($dxcc->Enddate) ? "<div class='alert-danger'>Y</div>" : '';
				$dxccMatrix[$dxcc->adif][$band] = '-';
			}

			// If worked is checked, we add worked entities to the array
			if ($postdata['worked'] != NULL) {
				$workedDXCC = $this->getDxccBandWorked($location_list, $band, $postdata);
				foreach ($workedDXCC as $wdxcc) {
					//function displayContacts(searchphrase, band, mode, type) {
					$dxccMatrix[$wdxcc->dxcc][$band] = '<div class="alert-danger"><a href=\'javascript:displayContacts("'.str_replace("&", "%26", $wdxcc->name).'","'. $band . '","'. $postdata['mode'] . '","DXCC")\'>W</a></div>';
				}
			}

			// If confirmed is checked, we add confirmed entities to the array
			if ($postdata['confirmed'] != NULL) {
				$confirmedDXCC = $this->getDxccBandConfirmed($location_list, $band, $postdata);
				foreach ($confirmedDXCC as $cdxcc) {
					$dxccMatrix[$cdxcc->dxcc][$band] = '<div class="alert-success"><a href=\'javascript:displayContacts("'.str_replace("&", "%26", $cdxcc->name).'","'. $band . '","'. $postdata['mode'] . '","DXCC")\'>C</a></div>';
				}
			}
		}

		// We want to remove the worked dxcc's in the list, since we do not want to display them
		if ($postdata['worked'] == NULL) {
			$workedDxcc = $this->getDxccWorked($location_list, $postdata);
			foreach ($workedDxcc as $wdxcc) {
				if (array_key_exists($wdxcc->dxcc, $dxccMatrix)) {
					unset($dxccMatrix[$wdxcc->dxcc]);
				}
			}
		}

		// We want to remove the confirmed dxcc's in the list, since we do not want to display them
		if ($postdata['confirmed'] == NULL) {
			$confirmedDxcc = $this->getDxccConfirmed($location_list, $postdata);
			foreach ($confirmedDxcc as $cdxcc) {
				if (array_key_exists($cdxcc->dxcc, $dxccMatrix)) {
					unset($dxccMatrix[$cdxcc->dxcc]);
				}
			}
		}

		if (isset($dxccMatrix)) {
			return $dxccMatrix;
		}
		else {
			return 0;
		}
	}

	function getDxccBandConfirmed($location_list, $band, $postdata) {
		$sql = "select adif as dxcc, name from dxcc_entities
				join (
					select col_dxcc from ".$this->config->item('table_name')." thcv
					where station_id in (" . $location_list .
				  ") and col_dxcc > 0";

		if ($band == 'SAT') {
			$sql .= " and col_prop_mode ='" . $band . "'";
		}
		else {
			$sql .= " and col_prop_mode !='SAT'";
			$sql .= " and col_band ='" . $band . "'";
		}

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

		$sql .= $this->addQslToQuery($postdata);

		$sql .= " group by col_dxcc
				) x on dxcc_entities.adif = x.col_dxcc";

		if ($postdata['includedeleted'] == NULL) {
			$sql .= " and dxcc_entities.end is null";
		}

		$sql .= $this->addContinentsToQuery($postdata);

		$query = $this->db->query($sql);

		return $query->result();
	}

	function getDxccBandWorked($location_list, $band, $postdata) {
		$sql = "select adif as dxcc, name from dxcc_entities
				join (
					select col_dxcc from ".$this->config->item('table_name')." thcv
					where station_id in (" . $location_list .
					") and col_dxcc > 0";

		if ($band == 'SAT') {
			$sql .= " and col_prop_mode ='" . $band . "'";
		}
		else {
			$sql .= " and col_prop_mode !='SAT'";
			$sql .= " and col_band ='" . $band . "'";
		}

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

		$sql .= " group by col_dxcc
				) x on dxcc_entities.adif = x.col_dxcc";;

		if ($postdata['includedeleted'] == NULL) {
			$sql .= " and dxcc_entities.end is null";
		}

		$sql .= $this->addContinentsToQuery($postdata);

		$query = $this->db->query($sql);

		return $query->result();
	}

	function fetchDxcc($postdata) {
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		$sql = "select adif, prefix, name, date(end) Enddate, date(start) Startdate
            from dxcc_entities";

		if ($postdata['notworked'] == NULL) {
			$sql .= " join (select col_dxcc from " . $this->config->item('table_name') . " where station_id in (" . $location_list . ") and col_dxcc > 0";

			if ($postdata['band'] != 'All') {
				if ($postdata['band'] == 'SAT') {
					$sql .= " and col_prop_mode ='" . $postdata['band'] . "'";
				}
				else {
					$sql .= " and col_prop_mode !='SAT'";
					$sql .= " and col_band ='" . $postdata['band'] . "'";
				}
			}

			if ($postdata['mode'] != 'All') {
				$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
			}

			$sql .= ' group by col_dxcc) x on dxcc_entities.adif = x.col_dxcc';
		}

		$sql .= " where 1 = 1";

		if ($postdata['includedeleted'] == NULL) {
			$sql .= " and end is null";
		}

		$sql .= $this->addContinentsToQuery($postdata);

		$sql .= ' order by prefix';
		$query = $this->db->query($sql);

		return $query->result();
	}

	function getDxccWorked($location_list, $postdata) {
		$sql = "SELECT adif as dxcc FROM dxcc_entities
        join (
            select col_dxcc
            from ".$this->config->item('table_name')." thcv
            where station_id in (" . $location_list .
              ") and col_dxcc > 0";

		if ($postdata['band'] != 'All') {
			if ($postdata['band'] == 'SAT') {
				$sql .= " and col_prop_mode ='" . $postdata['band'] . "'";
			}
			else {
				$sql .= " and col_prop_mode !='SAT'";
				$sql .= " and col_band ='" . $postdata['band'] . "'";
			}
		}

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

		$sql .= " and not exists (select 1 from ".$this->config->item('table_name')." where station_id in (". $location_list .") and col_dxcc = thcv.col_dxcc and col_dxcc > 0";

		if ($postdata['band'] != 'All') {
			if ($postdata['band'] == 'SAT') {
				$sql .= " and col_prop_mode ='" . $postdata['band'] . "'";
			}
			else {
				$sql .= " and col_prop_mode !='SAT'";
				$sql .= " and col_band ='" . $postdata['band'] . "'";
			}
		}

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

		$sql .= $this->addQslToQuery($postdata);

		$sql .= ')';

		$sql .= " group by col_dxcc
            ) ll on dxcc_entities.adif = ll.col_dxcc
            where 1=1";

		if ($postdata['includedeleted'] == NULL) {
			$sql .= " and dxcc_entities.end is null";
		}

		$sql .= $this->addContinentsToQuery($postdata);

		$query = $this->db->query($sql);

		return $query->result();
	}

	function getDxccConfirmed($location_list, $postdata) {
		$sql = "SELECT adif as dxcc FROM dxcc_entities
            join (
                select col_dxcc
                from ".$this->config->item('table_name')." thcv
                where station_id in (". $location_list .
                    ") and col_dxcc > 0";

		if ($postdata['band'] != 'All') {
			if ($postdata['band'] == 'SAT') {
				$sql .= " and col_prop_mode ='" . $postdata['band'] . "'";
			}
			else {
				$sql .= " and col_prop_mode !='SAT'";
				$sql .= " and col_band ='" . $postdata['band'] . "'";
			}
		}

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

		$sql .= $this->addQslToQuery($postdata);

		$sql .= " group by col_dxcc
            ) ll on dxcc_entities.adif = ll.col_dxcc
            where 1=1";

		if ($postdata['includedeleted'] == NULL) {
			$sql .= " and dxcc_entities.end is null";
		}

		$sql .= $this->addContinentsToQuery($postdata);

		$query = $this->db->query($sql);

		return $query->result();
	}

	// Made function instead of repeating this several times
	function addQslToQuery($postdata) {
		$sql = '';
		if ($postdata['lotw'] != NULL and $postdata['qsl'] == NULL) {
			$sql .= " and col_lotw_qsl_rcvd = 'Y'";
		}

		if ($postdata['qsl'] != NULL and $postdata['lotw'] == NULL) {
			$sql .= " and col_qsl_rcvd = 'Y'";
		}

		if ($postdata['qsl'] != NULL && $postdata['lotw'] != NULL) {
			$sql .= " and (col_qsl_rcvd = 'Y' or col_lotw_qsl_rcvd = 'Y')";
		}
		return $sql;
	}

	// Made function instead of repeating this several times
	function addContinentsToQuery($postdata) {
		$sql = '';
		if ($postdata['Africa'] == NULL) {
			$sql .= " and cont <> 'AF'";
		}

		if ($postdata['Europe'] == NULL) {
			$sql .= " and cont <> 'EU'";
		}

		if ($postdata['Asia'] == NULL) {
			$sql .= " and cont <> 'AS'";
		}

		if ($postdata['SouthAmerica'] == NULL) {
			$sql .= " and cont <> 'SA'";
		}

		if ($postdata['NorthAmerica'] == NULL) {
			$sql .= " and cont <> 'NA'";
		}

		if ($postdata['Oceania'] == NULL) {
			$sql .= " and cont <> 'OC'";
		}

		if ($postdata['Antarctica'] == NULL) {
			$sql .= " and cont <> 'AN'";
		}
		return $sql;
	}

	/*
     * Function gets worked and confirmed summary on each band on the active stationprofile
     */
	function get_dxcc_summary($bands, $postdata)
	{
		$CI =& get_instance();
		$CI->load->model('logbooks_model');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

		if (!$logbooks_locations_array) {
			return null;
		}

		$location_list = "'".implode("','",$logbooks_locations_array)."'";

		foreach ($bands as $band) {
			$worked = $this->getSummaryByBand($band, $postdata, $location_list);
			$confirmed = $this->getSummaryByBandConfirmed($band, $postdata, $location_list);
			$dxccSummary['worked'][$band] = $worked[0]->count;
			$dxccSummary['confirmed'][$band] = $confirmed[0]->count;
		}

		$workedTotal = $this->getSummaryByBand($postdata['band'], $postdata, $location_list);
		$confirmedTotal = $this->getSummaryByBandConfirmed($postdata['band'], $postdata, $location_list);

		$dxccSummary['worked']['Total'] = $workedTotal[0]->count;
		$dxccSummary['confirmed']['Total'] = $confirmedTotal[0]->count;

		return $dxccSummary;
	}

	function getSummaryByBand($band, $postdata, $location_list)
	{
		$sql = "SELECT count(distinct thcv.col_dxcc) as count FROM " . $this->config->item('table_name') . " thcv";
		$sql .= " join dxcc_entities d on thcv.col_dxcc = d.adif";

		$sql .= " where station_id in (" . $location_list . ") and col_dxcc > 0";

		if ($band == 'SAT') {
			$sql .= " and thcv.col_prop_mode ='" . $band . "'";
		} else if ($band == 'All') {
			$this->load->model('bands');

			$bandslots = $this->bands->get_worked_bands('dxcc');
	
			$bandslots_list = "'".implode("','",$bandslots)."'";
			
			$sql .= " and thcv.col_band in (" . $bandslots_list . ")" .
					" and thcv.col_prop_mode !='SAT'";
		} else {
			$sql .= " and thcv.col_prop_mode !='SAT'";
			$sql .= " and thcv.col_band ='" . $band . "'";
		}

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

		if ($postdata['includedeleted'] == NULL) {
			$sql .= " and d.end is null";
		}

		$sql .= $this->addContinentsToQuery($postdata);

		$query = $this->db->query($sql);

		return $query->result();
	}

	function getSummaryByBandConfirmed($band, $postdata, $location_list)
	{
		$sql = "SELECT count(distinct thcv.col_dxcc) as count FROM " . $this->config->item('table_name') . " thcv";
		$sql .= " join dxcc_entities d on thcv.col_dxcc = d.adif";

		$sql .= " where station_id in (" . $location_list . ")";

		if ($band == 'SAT') {
			$sql .= " and thcv.col_prop_mode ='" . $band . "'";
		} else if ($band == 'All') {
			$this->load->model('bands');

			$bandslots = $this->bands->get_worked_bands('dxcc');
	
			$bandslots_list = "'".implode("','",$bandslots)."'";
			
			$sql .= " and thcv.col_band in (" . $bandslots_list . ")" .
					" and thcv.col_prop_mode !='SAT'";
		} else {
			$sql .= " and thcv.col_prop_mode !='SAT'";
			$sql .= " and thcv.col_band ='" . $band . "'";
		}

		if ($postdata['mode'] != 'All') {
			$sql .= " and (col_mode = '" . $postdata['mode'] . "' or col_submode = '" . $postdata['mode'] . "')";
		}

		$sql .= $this->addQslToQuery($postdata);

		
		if ($postdata['includedeleted'] == NULL) {
			$sql .= " and d.end is null";
		}

		$sql .= $this->addContinentsToQuery($postdata);

		$query = $this->db->query($sql);

		return $query->result();
	}

  function lookup_country($country)
	{
		$query = $this->db->query('
					SELECT *
					FROM dxcc_entities
					WHERE name = "'.$country.'"
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1
				');

		return $query->row();
	}
}
?>
