<script type="text/javascript">
	var Bands = <?php echo json_encode($bands); ?>;
	var user_id = <?php echo $this->session->userdata('user_id'); ?>;
</script>

<div class="container">

	<br>
	<div id="simpleFleInfo">
		<script>
			var lang_qso_simplefle_info_ln1 = "<?php echo lang('qso_simplefle_info_ln1'); ?>";
			var lang_qso_simplefle_info_ln2 = "<?php echo lang('qso_simplefle_info_ln2'); ?>";
			var lang_qso_simplefle_info_ln3 = "<?php echo lang('qso_simplefle_info_ln3'); ?>";
			var lang_qso_simplefle_info_ln4 = "<?php echo lang('qso_simplefle_info_ln4'); ?>";
			var lang_qso_simplefle_syntax_help = "<?php echo lang('qso_simplefle_syntax_help_button'); ?>";
			var lang_qso_simplefle_syntax_help_title = "<?php echo lang('qso_simplefle_syntax_help_title'); ?>";
			var lang_qso_simplefle_syntax_help_close_w_sample = "<?php echo lang('qso_simplefle_syntax_help_close_w_sample'); ?>";
			var lang_admin_close = "<?php echo lang('admin_close'); ?>";
			var lang_qso_simplefle_error_band = "<?php echo lang('qso_simplefle_error_band'); ?>";
			var lang_qso_simplefle_error_mode = "<?php echo lang('qso_simplefle_error_mode'); ?>";
			var lang_qso_simplefle_error_time = "<?php echo lang('qso_simplefle_error_time'); ?>";
			var lang_qso_simplefle_error_date = "<?php echo lang('qso_simplefle_error_date'); ?>";
			var lang_qso_simplefle_qso_list_total = "<?php echo lang('qso_simplefle_qso_list_total'); ?>";
			var lang_gen_hamradio_qso = "<?php echo lang('gen_hamradio_qso'); ?>";
			var lang_qso_simplefle_error_stationcall = "<?php echo lang('qso_simplefle_error_stationcall'); ?>";
			var lang_qso_simplefle_error_operator = "<?php echo lang('qso_simplefle_error_operator'); ?>";
			var lang_qso_simplefle_warning_reset = "<?php echo lang('qso_simplefle_warning_reset'); ?>";
			var lang_qso_simplefle_warning_missing_band_mode = "<?php echo lang('qso_simplefle_warning_missing_band_mode'); ?>";
			var lang_qso_simplefle_warning_missing_time = "<?php echo lang('qso_simplefle_warning_missing_time'); ?>";
			var lang_qso_simplefle_warning_example_data = "<?php echo lang('qso_simplefle_warning_example_data'); ?>";
			var lang_qso_simplefle_confirm_save_to_log = "<?php echo lang('qso_simplefle_confirm_save_to_log'); ?>";
			var lang_qso_simplefle_success_save_to_log_header = "<?php echo lang('qso_simplefle_success_save_to_log_header'); ?>";
			var lang_qso_simplefle_success_save_to_log = "<?php echo lang('qso_simplefle_success_save_to_log'); ?>";
		</script>
		<h2><?php echo $page_title; ?></h2>
		<button type="button" class="btn btn-sm btn-primary me-1" id="simpleFleInfoButton"><?php echo lang('qso_simplefle_info'); ?></button>
	</div>

	<?php if ($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert alert-danger">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>
</div>
<div class="container-fluid">
	<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
		<div class="col-md-3 mb-2 mb-md-0">

		</div>

		<div class="col-md-3 justify-content-end d-flex">
		</div>
	</header>
	<div class="row mt-4">
		<!-- START BASIC QSO DATA -->
		<div class="card col-xs-12 col-md-4 simplefle" style="border: none">
			
			<div class="card-header">
				<?php echo lang('qso_simplefle_qso_data'); ?>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xs-12 col-lg-12 col-xl-6">
						<div class="mb-3">
							<label for="qsodate"><?php echo lang('qso_simplefle_qso_date'); ?></label>
							<input type="date" class="form-control" id="qsodate">
							<small class="form-text text-muted"><?php echo lang('qso_simplefle_qso_date_hint'); ?></small>
						</div>
					</div>
					<div class="col-xs-12 col-lg-12 col-xl-6">
						<p><?php echo lang('qso_simplefle_utc_time'); ?></p>
						<h4 class="fw-bold" id="utc-time"></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-lg-6">
						<div class="mb-3">
							<label for="station-call">
								<?php echo lang('qso_simplefle_station_call_location'); ?>
							</label>
							<select name="station_profile" class="station_id form-select" id="station-call">
								<option value="-">-</option>
								<?php foreach ($station_profile->result() as $station) { ?>
									<option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) {
																							echo 'selected';
																						} ?>>
										<?php echo lang('gen_hamradio_callsign') . ": " . $station->station_callsign . " (" . $station->station_profile_name . ")"; ?>
									</option>
								<?php } ?>
							</select>
							<div class="alert alert-danger" role="alert" id="warningStationCall" style="display: none"> </div>
							<small class="form-text text-muted"><?php echo lang('qso_simplefle_station_call_location_hint'); ?></small>
						</div>
					</div>
					<div class="col-xs-12 col-lg-6">
						<div class="mb-3">
							<label for="operator"><?php echo lang('qso_simplefle_operator'); ?> <span class="text-muted input-example"><?php echo lang('qso_simplefle_operator_hint'); ?></span></label>
							<input type="text" class="form-control text-uppercase" id="operator" value="<?php echo $this->session->userdata('operator_callsign'); ?>">
							<div class="alert alert-danger" role="alert" id="warningOperatorField" style="display: none"> </div>
						</div>
					</div>
				</div>
			</div>

			<!-- END BASIC QSO DATA -->
			<div class="card-body">
				<div class="row">
					<div class="col">
						<p><?php echo lang('qso_simplefle_enter_the_data'); ?></p>
						<textarea name="qso" class="form-control qso-area" cols="auto" rows="11" id="textarea"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="card col-xs-12 col-md-8 simplefle" style="border: none">
			<div class="card-header">
				<?php echo lang('qso_simplefle_qso_list'); ?>
			</div>
			<div class="card-body">
				<div class="qsoList">
					<table class="table contacttable table-striped table-hover sfletable" id="qsoTable">
						<thead>
							<tr>
								<th><?php echo lang('general_word_date'); ?></th>
								<th><?php echo lang('general_word_time'); ?></th>
								<th><?php echo lang('gen_hamradio_callsign'); ?></th>
								<th><?php echo lang('gen_hamradio_band'); ?></th>
								<th><?php echo lang('gen_hamradio_mode'); ?></th>
								<th><?php echo lang('gen_hamradio_rsts'); ?></th>
								<th><?php echo lang('gen_hamradio_rstr'); ?></th>
								<th><?php echo lang('gen_hamradio_operator'); ?></th>
								<th><?php echo lang('gen_hamradio_refs'); ?>*</th>
							</tr>
						</thead>
						<tbody id="qsoTableBody">
						</tbody>
					</table>
				</div>
				<div class="row mt-2">
					<div class="col-6 col-sm-6">
						<span class="js-qso-count"></span>
					</div>
					<div class="col-6 col-sm-6 text-end">
						<?php echo lang('qso_simplefle_refs_hint'); ?>
					</div>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-3 col-sm-3">
					<button class="btn btn-primary js-reload-qso"><?php echo lang('qso_simplefle_reload'); ?></button>
				</div>
				<div class="col-3 col-sm-3">
					<button class="btn btn-warning js-save-to-log"><?php echo lang('qso_simplefle_save'); ?></button>
				</div>
				<div class="col-3 col-sm-3">
					<button class="btn btn-danger js-empty-qso"><?php echo lang('qso_simplefle_clear'); ?></button>
				</div>
				<div class="col-3 col-sm-3">
					<button class="btn btn-success" id="js-syntax"><?php echo lang('qso_simplefle_syntax_help_button'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>