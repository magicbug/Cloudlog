<?php
if ($qsos->result() != NULL) {
	echo '<table style="width:100%" class="table table-sm table-bordered table-hover table-striped table-condensed">
	<thead>
	<tr>
	<th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_callsign').'</th>
	<th style=\'text-align: center\'>' . $this->lang->line('general_word_date') . '</th>
	<th style=\'text-align: center\'>'. $this->lang->line('general_word_time') .'</th>
	<th style=\'text-align: center\'>' . $this->lang->line('gen_hamradio_mode') . '</th>
	<th style=\'text-align: center\'>' . $this->lang->line('gen_hamradio_band') . '</th>
	<th style=\'text-align: center\'>' . $this->lang->line('gen_hamradio_qsl') . ' ' . $this->lang->line('general_word_qslcard_via') . '</th>
	<th style=\'text-align: center\'>' . $this->lang->line('gen_hamradio_station') . '</th>
	<th style=\'text-align: center\'></th>
	<th style=\'text-align: center\'></th>
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

	foreach ($qsos->result() as $qsl) {
		echo '<tr id="qslprint_'.$qsl->COL_PRIMARY_KEY.'">';
		echo '<td style=\'text-align: center\'>' . $qsl->COL_CALL . '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qsl->COL_TIME_ON); echo date($custom_date_format, $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qsl->COL_TIME_ON); echo date('H:i', $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; echo $qsl->COL_SUBMODE==null?$qsl->COL_MODE:$qsl->COL_SUBMODE; echo '</td>';
		echo '<td style=\'text-align: center\'>'; if($qsl->COL_SAT_NAME != null) { echo $qsl->COL_SAT_NAME; } else { echo strtolower($qsl->COL_BAND); }; echo '</td>';
		echo '<td style=\'text-align: center\'>' . $qsl->COL_QSL_VIA . '</td>';
		echo '<td style=\'text-align: center\'><span class="badge badge-light">' . $qsl->station_callsign . '</span></td>';
		echo '<td style=\'text-align: center\'><button onclick="deleteFromQslQueue(\''.$qsl->COL_PRIMARY_KEY.'\')" class="btn btn-sm btn-danger">Delete from queue</button></td>';
		echo '<td style=\'text-align: center\'><button onclick="openQsoList(\''.$qsl->COL_CALL.'\')" class="btn btn-sm btn-success">Open QSO list</button></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	?>

	<p><a href="<?php echo site_url('qslprint/exportcsv/' . $station_id); ?>" title="Export CSV-file" target="_blank" class="btn btn-primary">Export requested QSLs to CSV-file</a></p>

	<p><a href="<?php echo site_url('qslprint/exportadif/' . $station_id); ?>" title="Export ADIF" target="_blank" class="btn btn-primary">Export requested QSLs to ADIF-file</a></p>

	<p><a href="<?php echo site_url('qslprint/qsl_printed/' . $station_id); ?>" title="Mark QSLs as printed" target="_blank" class="btn btn-primary">Mark requested QSLs as sent</a></p>

<?php
} else {
	echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No QSL\'s to print were found!</div>';
}
?>
