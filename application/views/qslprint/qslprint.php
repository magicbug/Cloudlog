<?php

function echo_qsl_sent_via($method) {
	switch($method) {
		case 'B': echo lang('general_word_qslcard_bureau'); break;
		case 'D': echo lang('general_word_qslcard_direct'); break;
		case 'E': echo lang('general_word_qslcard_electronic'); break;
	}
}

if (empty($station_id)) {
	$station_id = 'all';
}

if ($qsos->result() != NULL) {
	echo '<div class="table-responsive mt-3">';
	echo '<table class="table table-hover table-striped mb-0" id="qslprint_table">
<thead class="table-light">
<tr>
<th class="text-center"><div class="form-check d-inline-block"><input class="form-check-input" type="checkbox" id="checkBoxAll" /></div></th>
<th class="text-center">'.lang('gen_hamradio_callsign').'</th>
<th class="text-center">' . lang('general_word_date') . '</th>
<th class="text-center">'. lang('general_word_time') .'</th>
<th class="text-center">' . lang('gen_hamradio_mode') . '</th>
<th class="text-center">' . lang('gen_hamradio_band') . '</th>
<th class="text-center">' . lang('gen_hamradio_rsts') . '</th>
<th class="text-center">' . lang('gen_hamradio_rstr') . '</th>
<th class="text-center">' . lang('gen_hamradio_qsl') . ' ' . lang('general_word_qslcard_via') . '</th>
<th class="text-center">' . lang('gen_hamradio_station') . '</th>
<th class="text-center">' . lang('qslcard_qslprint_send_method') . '</th>
<th class="text-center">' . lang('qslcard_qslprint_mark_as_sent') . '</th>
<th class="text-center">' . lang('admin_remove') . '</th>
<th class="text-center">' . lang('qso_simplefle_qso_list') . '</th>
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
		echo '<td class="text-center"><div class="form-check d-inline-block"><input class="form-check-input qso-checkbox" type="checkbox" value="'.$qsl->COL_PRIMARY_KEY.'" /></div></td>';
                ?><td class='text-center'><span class="qso_call"><a id="edit_qso" href="javascript:displayQso(<?php echo $qsl->COL_PRIMARY_KEY; ?>);"><strong><?php echo str_replace("0","&Oslash;",strtoupper($qsl->COL_CALL)); ?></strong></a> <a target="_blank" href="https://www.qrz.com/db/<?php echo strtoupper($qsl->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/qrz.png" alt="Lookup <?php echo strtoupper($qsl->COL_CALL); ?> on QRZ.com"></a> <a target="_blank" href="https://www.hamqth.com/<?php echo strtoupper($qsl->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/hamqth.png" alt="Lookup <?php echo strtoupper($qsl->COL_CALL); ?> on HamQTH"></a> <a target="_blank" href="http://www.eqsl.cc/Member.cfm?<?php echo strtoupper($qsl->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/eqsl.png" alt="Lookup <?php echo strtoupper($qsl->COL_CALL); ?> on eQSL.cc"></a></td><?php 
		echo '<td class="text-center">'; $timestamp = strtotime($qsl->COL_TIME_ON); echo date($custom_date_format, $timestamp); echo '</td>';
		echo '<td class="text-center">'; $timestamp = strtotime($qsl->COL_TIME_ON); echo date('H:i', $timestamp); echo '</td>';
		echo '<td class="text-center">'; echo $qsl->COL_SUBMODE==null?$qsl->COL_MODE:$qsl->COL_SUBMODE; echo '</td>';
		echo '<td class="text-center">'; if($qsl->COL_SAT_NAME != null) { echo $qsl->COL_SAT_NAME; } else { echo strtolower($qsl->COL_BAND); }; echo '</td>';
		echo '<td class="text-center">' . $qsl->COL_RST_SENT . '</td>';
		echo '<td class="text-center">' . $qsl->COL_RST_RCVD . '</td>';
		echo '<td class="text-center">' . $qsl->COL_QSL_VIA . '</td>';
		echo '<td class="text-center"><span class="badge text-bg-light">' . $qsl->station_callsign . '</span></td>';
		echo '<td class="text-center">'; echo_qsl_sent_via($qsl->COL_QSL_SENT_VIA); echo '</td>';
		echo '<td class="text-center"><button onclick="mark_qsl_sent(\''.$qsl->COL_PRIMARY_KEY.'\', \''. $qsl->COL_QSL_SENT_VIA. '\')" class="btn btn-sm btn-success"><i class="fa fa-check"></i></button></td>';
		echo '<td class="text-center"><button onclick="deleteFromQslQueue(\''.$qsl->COL_PRIMARY_KEY.'\')" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></td>';
		echo '<td class="text-center"><button onclick="openQsoList(\''.$qsl->COL_CALL.'\')" class="btn btn-sm btn-success"><i class="fas fa-search"></i></button></td>';
		echo '</tr>';
	}
	echo '</tbody></table></div>';
	?>

	<div class="mt-3">
		<button onclick="markSelectedQsos();" title="Mark selected QSOs as printed" class="btn btn-success me-2">
			<i class="fas fa-check me-1"></i><?php echo lang('qslcard_qslprint_mark_selected_as_printed'); ?>
		</button>

		<button onclick="removeSelectedQsos();" title="Remove selected QSOs from print queue" class="btn btn-danger me-2">
			<i class="fas fa-trash-alt me-1"></i><?php echo lang('qslcard_qslprint_remove_selected_from_queue'); ?>
		</button>
	</div>

	<div class="mt-3">
		<a href="<?php echo site_url('qslprint/exportcsv/' . $station_id); ?>" title="Export CSV-file" class="btn btn-primary me-2">
			<i class="fas fa-file-csv me-1"></i><?php echo lang('qslcard_qslprint_export_csv'); ?>
		</a>

		<a href="<?php echo site_url('qslprint/exportadif/' . $station_id); ?>" title="Export ADIF" class="btn btn-primary me-2">
			<i class="fas fa-file-export me-1"></i><?php echo lang('qslcard_qslprint_export_adif'); ?>
		</a>

		<a href="<?php echo site_url('qslprint/qsl_printed/' . $station_id); ?>" title="Mark QSLs as printed" class="btn btn-primary">
			<i class="fas fa-print me-1"></i><?php echo lang('qslcard_qslprint_mark_requested_as_sent'); ?>
		</a>
	</div>

<?php
} else {
	echo '<div class="alert alert-danger">' . lang('qslcard_qslprint_no_qsls_found') . '</div>';
}
?>
