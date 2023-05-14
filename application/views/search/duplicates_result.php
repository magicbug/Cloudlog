<?php
if ($qsos->result() != NULL) {
	Echo 'The following QSOs were found on the same band, mode, date and time difference 30 minutes:';
	echo '<table style="width:100%" class="qsolist table-sm table-bordered table-hover table-striped table-condensed">
	<thead>
	<tr>
	<th style=\'text-align: center\'>Occurrence</th>
	<th style=\'text-align: center\'>'.lang('gen_hamradio_callsign').'</th>
	<th style=\'text-align: center\'>Min date</th>
	<th style=\'text-align: center\'>Min time</th>
	<th style=\'text-align: center\'>Max date</th>
	<th style=\'text-align: center\'>Max time</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_mode') . '</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_band') . '</th>
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
		echo '<td style=\'text-align: center\'>' . $qso->occurence . '</td>';
		echo '<td style=\'text-align: center\'><form id="searchcall_'.$i.'" method="POST" action="'.site_url('search'). '"><input type="hidden" value="'. strtoupper($qso->COL_CALL) .'" name="callsign"><a href="javascript:$(\'#searchcall_'.$i++.'\').submit()">' . $qso->COL_CALL . '</a></form></td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qso->Mintime); echo date($custom_date_format, $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qso->Mintime); echo date('H:i', $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qso->Maxtime); echo date($custom_date_format, $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qso->Maxtime); echo date('H:i', $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; echo $qso->COL_SUBMODE==null?$qso->COL_MODE:$qso->COL_SUBMODE; echo '</td>';
		echo '<td style=\'text-align: center\'>'; if($qso->COL_SAT_NAME != null) { echo $qso->COL_SAT_NAME; } else { echo strtolower($qso->COL_BAND); }; echo '</td>';
		echo '<td style=\'text-align: center\'><span class="badge badge-light">' . $qso->station_callsign . '</span></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	?>

	<?php
} else {
	echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No duplicate QSO\'s were found.</div>';
}
?>