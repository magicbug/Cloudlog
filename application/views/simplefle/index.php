<script type="text/javascript">
	var Bands = <?php echo json_encode($bands); ?>;
	var user_id = <?php echo $this->session->userdata('user_id'); ?>;
</script>

<div class="container">

	<div id="simpleFleInfo" class="mt-3 mb-2">
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
		<h2 class="mb-2"><?php echo $page_title; ?></h2>
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

	<div class="row mt-4">
		<!-- START BASIC QSO DATA -->
		<div class="card col-xs-12 col-md-4 simplefle" style="border: none">

			<div class="card-header py-2">
				<?php echo lang('qso_simplefle_qso_data'); ?>
			</div>
			<div class="card-body py-2">
				<div class="row">
					<div class="col-xs-12 col-lg-6 col-xl-6">
						<div class="mb-2">
							<label for="qsodate" class="form-label mb-1"><?php echo lang('qso_simplefle_qso_date'); ?></label>
							<input type="date" class="form-control form-control-sm" id="qsodate">
							<small class="form-text text-muted"><?php echo lang('qso_simplefle_qso_date_hint'); ?></small>
						</div>
					</div>
					<div class="col-xs-12 col-lg-6 col-xl-6">
						<label class="form-label mb-1"><?php echo lang('qso_simplefle_utc_time'); ?></label>
						<div class="badge bg-primary fs-5" id="utc-time"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-lg-6">
						<div class="mb-2">
							<label for="station-call" class="form-label mb-1">
								<?php echo lang('qso_simplefle_station_call_location'); ?>
							</label>
							<select name="station_profile" class="station_id form-select form-select-sm" id="station-call">
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
						<div class="mb-2">
							<label for="operator" class="form-label mb-1"><?php echo lang('qso_simplefle_operator'); ?> <span class="text-muted input-example"><?php echo lang('qso_simplefle_operator_hint'); ?></span></label>
							<input type="text" class="form-control form-control-sm text-uppercase" id="operator" value="<?php echo $this->session->userdata('operator_callsign'); ?>">
							<div class="alert alert-danger" role="alert" id="warningOperatorField" style="display: none"> </div>
						</div>
					</div>
				</div>
			</div>

			<!-- END BASIC QSO DATA -->
			<div class="card-body py-2">
				<!-- Satellite feedback area - moved above textarea for better visibility -->
				<div id="satellite-feedback" class="mb-2" style="display: none;">
					<div class="alert alert-success mb-0 py-2" role="alert" style="border-left: 4px solid #28a745;">
						<div class="d-flex align-items-center">
							<i class="fas fa-satellite me-2"></i>
							<strong id="sat-name-display"></strong>
						</div>
						<div id="sat-modes-display" class="mt-1 ms-4"></div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<label for="textarea" class="form-label"><?php echo lang('qso_simplefle_enter_the_data'); ?></label>
						<textarea name="qso" class="form-control form-control-sm qso-area" cols="auto" rows="12" id="textarea"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="card col-xs-12 col-md-8 simplefle" style="border: none">
			<div class="card-header py-2">
				<?php echo lang('qso_simplefle_qso_list'); ?>
			</div>
			<div class="card-body py-2">
				<style>
					/* Override the sfletable CSS to allow proper horizontal scrolling */
					#qsoTable.sfletable.table thead,
					#qsoTable.sfletable.table tbody tr {
						display: table-row-group !important;
						width: auto !important;
					}

					#qsoTable.sfletable.table thead tr,
					#qsoTable.sfletable.table tbody tr {
						display: table-row !important;
					}

					#qsoTable.sfletable.table tbody {
						display: table-row-group !important;
						position: static !important;
						width: 100% !important;
						overflow-y: visible !important;
						max-height: none !important;
					}

					#qsoTable.sfletable {
						height: auto !important;
					}
				</style>
				<div class="qsoList table-responsive">
					<table class="table contacttable table-striped table-hover sfletable" id="qsoTable" style="table-layout: fixed; min-width: 970px;">
						<thead>
							<tr>
								<th style="padding: 0.5rem; width: 110px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('general_word_date'); ?></th>
								<th style="padding: 0.5rem; width: 70px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('general_word_time'); ?></th>
								<th style="padding: 0.5rem; width: 90px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('gen_hamradio_callsign'); ?></th>
								<th style="padding: 0.5rem; width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('gen_hamradio_band'); ?></th>
								<th style="padding: 0.5rem; width: 70px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('gen_hamradio_mode'); ?></th>
								<th style="padding: 0.5rem; width: 70px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('gen_hamradio_rsts'); ?></th>
								<th style="padding: 0.5rem; width: 70px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('gen_hamradio_rstr'); ?></th>
								<th style="padding: 0.5rem; width: 90px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('gen_hamradio_operator'); ?></th>
								<th style="padding: 0.5rem; width: 110px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo lang('gen_hamradio_refs'); ?>*</th>
								<th style="padding: 0.5rem; width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Comment</th>
							</tr>
						</thead>
						<tbody id="qsoTableBody">
						</tbody>
					</table>
				</div>
				<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mt-3 mb-2 gap-2">
					<div>
						<span class="badge bg-secondary js-qso-count">0 QSOs</span>
					</div>
					<div class="text-muted small">
						<?php echo lang('qso_simplefle_refs_hint'); ?>
					</div>
				</div>
			</div>
			<div class="card-footer bg-white">
				<div class="row g-2">
					<div class="col-12 col-sm-6 col-md-3">
						<button class="btn btn-primary w-100 js-reload-qso">
							<i class="fas fa-sync-alt me-1"></i><?php echo lang('qso_simplefle_reload'); ?>
						</button>
					</div>
					<div class="col-12 col-sm-6 col-md-3">
						<button class="btn btn-warning w-100 js-save-to-log">
							<i class="fas fa-save me-1"></i><?php echo lang('qso_simplefle_save'); ?>
						</button>
					</div>
					<div class="col-12 col-sm-6 col-md-3">
						<button class="btn btn-danger w-100 js-empty-qso">
							<i class="fas fa-trash me-1"></i><?php echo lang('qso_simplefle_clear'); ?>
						</button>
					</div>
					<div class="col-12 col-sm-6 col-md-3">
						<button class="btn btn-success w-100" id="js-syntax">
							<i class="fas fa-question-circle me-1"></i><?php echo lang('qso_simplefle_syntax_help_button'); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>