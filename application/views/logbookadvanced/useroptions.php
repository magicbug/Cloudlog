<?php
$column_items = [
	['name' => 'datetime', 'label' => lang('general_word_datetime')],
	['name' => 'de', 'label' => lang('gen_hamradio_de')],
	['name' => 'stationLocation', 'label' => lang('cloudlog_station_profile')],
	['name' => 'dx', 'label' => lang('gen_hamradio_dx')],
	['name' => 'mode', 'label' => lang('gen_hamradio_mode')],
	['name' => 'rsts', 'label' => lang('gen_hamradio_rsts')],
	['name' => 'rstr', 'label' => lang('gen_hamradio_rstr')],
	['name' => 'band', 'label' => lang('gen_hamradio_band')],
	['name' => 'myrefs', 'label' => lang('gen_hamradio_myrefs')],
	['name' => 'refs', 'label' => lang('gen_hamradio_refs')],
	['name' => 'name', 'label' => lang('general_word_name')],
	['name' => 'qslvia', 'label' => lang('gen_hamradio_qslvia')],
	['name' => 'qsl', 'label' => lang('gen_hamradio_qsl')],
	['name' => 'lotw', 'label' => lang('lotw_short')],
	['name' => 'eqsl', 'label' => lang('eqsl_short')],
	['name' => 'qslmsg', 'label' => lang('gen_hamradio_qslmsg')],
	['name' => 'dxcc', 'label' => lang('gen_hamradio_dxcc')],
	['name' => 'state', 'label' => lang('gen_hamradio_state')],
	['name' => 'cqzone', 'label' => lang('gen_hamradio_cq_zone')],
	['name' => 'sota', 'label' => lang('gen_hamradio_sota')],
	['name' => 'iota', 'label' => lang('gen_hamradio_iota')],
	['name' => 'pota', 'label' => lang('gen_hamradio_pota')],
	['name' => 'operator', 'label' => lang('gen_hamradio_operator')],
];
?>
<div id="useroptions">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<span class="small text-muted">Choose which columns appear in the Advanced Logbook table.</span>
		<div class="btn-group btn-group-sm">
			<button type="button" class="btn btn-outline-secondary" onclick="$('#useroptions input[type=checkbox]').prop('checked', true);">All</button>
			<button type="button" class="btn btn-outline-secondary" onclick="$('#useroptions input[type=checkbox]').prop('checked', false);">None</button>
		</div>
	</div>
	<div class="row g-2">
		<?php foreach ($column_items as $col) {
			$checked = (($options->{$col['name']}->show ?? "true") == "true");
			$input_id = 'opt_col_' . $col['name'];
		?>
			<div class="col-6">
				<div class="form-check form-switch py-1">
					<input class="form-check-input" id="<?php echo $input_id; ?>" name="<?php echo $col['name']; ?>" type="checkbox" role="switch" <?php if ($checked) echo 'checked'; ?>>
					<label class="form-check-label" for="<?php echo $input_id; ?>"><?php echo $col['label']; ?></label>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
