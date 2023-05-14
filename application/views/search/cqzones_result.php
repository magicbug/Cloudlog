<?php
if ($qsos->result() != NULL) {
	Echo 'The following QSOs were found to have an incorrect CQ zone that this DXCC normally has:';
	echo '<table style="width:100%" class="qsolist table-sm table-bordered table-hover table-striped table-condensed">
	<thead>
	<tr>
	<th style=\'text-align: center\'>Date</th>
	<th style=\'text-align: center\'>Time</th>
	<th style=\'text-align: center\'>'.lang('gen_hamradio_callsign').'</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_mode') . '</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_band') . '</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_gridsquare') . '</th>
	<th style=\'text-align: center\'>CQ Zone</th>
	<th style=\'text-align: center\'>DXCC CQ Zone</th>
	<th style=\'text-align: center\'>DXCC</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_station') . '</th>
	</tr>
	</thead><tbody>';

	// Get Date format
	if($this->session->userdata('user_date_format')) {
		// If Logged in and session exists
		$custom_date_format = $this->session->userdata('user_date_format');
	} else {
		// Get Default date format from /config/cloudlog.php
		$custom_date_format = $this->config->item('qso_date_format');
	}

	$i = 0;

	foreach ($qsos->result() as $qso) {
		echo '<tr>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qso->COL_TIME_ON); echo date($custom_date_format, $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qso->COL_TIME_ON); echo date('H:i', $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'><a id="edit_qso" href="javascript:displayQso(' . $qso->COL_PRIMARY_KEY . ')">' . str_replace("0","&Oslash;",strtoupper($qso->COL_CALL)) . '</a></td>';
		echo '<td style=\'text-align: center\'>'; echo $qso->COL_SUBMODE==null?$qso->COL_MODE:$qso->COL_SUBMODE; echo '</td>';
		echo '<td style=\'text-align: center\'>'; if($qso->COL_SAT_NAME != null) { echo $qso->COL_SAT_NAME; } else { echo strtolower($qso->COL_BAND); }; echo '</td>';
		echo '<td style=\'text-align: center\'>'; echo strlen($qso->COL_GRIDSQUARE)==0?$qso->COL_VUCC_GRIDS:$qso->COL_GRIDSQUARE; echo '</td>';
		echo '<td style=\'text-align: center\'>' . $qso->COL_CQZ . '</td>';
		echo '<td style=\'text-align: center\'>' . $qso->correctcqzone . '</td>';
		echo '<td style=\'text-align: center\'>' . ucwords(strtolower($qso->COL_COUNTRY), "- (/") . '</td>';
		echo '<td style=\'text-align: center\'><span class="badge badge-light">' . $qso->station_callsign . '</span></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	?>

	<?php
} else {
	echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No incorrect CQ Zones were found.</div>';
}
?>
