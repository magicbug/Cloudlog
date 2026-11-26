<script type="text/javascript">
	var Bands = <?php echo json_encode($bands); ?>;
	var user_id = <?php echo $this->session->userdata('user_id'); ?>;
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

<main class="container-fluid sfle-page">
	<div class="sfle-shell mx-auto">
		<?php if ($this->session->flashdata('message')) { ?>
			<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
				<?php echo $this->session->flashdata('message'); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php } ?>

		<!-- 1. Unified Cockpit Topbar -->
		<div class="card sfle-topbar mb-3" id="simpleFleInfo">
			<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
				<div class="d-flex align-items-center gap-2">
					<span class="sfle-brand-icon d-inline-flex align-items-center justify-content-center">
						<i class="fas fa-edit"></i>
					</span>
					<div>
						<h1 class="h5 mb-0 fw-semibold"><?php echo $page_title; ?></h1>
						<div class="small text-muted">Type QSOs in shorthand FLE syntax and preview parsed contacts live</div>
					</div>
				</div>

				<div class="d-flex flex-wrap align-items-center gap-2">
					<div class="sfle-clock-pill d-inline-flex align-items-center gap-2">
						<i class="far fa-clock text-primary"></i>
						<span class="fw-bold sfle-clock-val" id="utc-time">--:--:--</span>
						<span class="sfle-utc-tag">UTC</span>
					</div>
					<button type="button" class="btn btn-sm btn-outline-secondary" id="js-syntax">
						<i class="fas fa-book-open me-1"></i><?php echo lang('qso_simplefle_syntax_help_button'); ?>
					</button>
					<button type="button" class="btn btn-sm btn-outline-secondary" id="simpleFleInfoButton">
						<i class="fas fa-info-circle me-1"></i><?php echo lang('qso_simplefle_info'); ?>
					</button>
					<button type="button" class="btn btn-sm btn-primary js-save-to-log">
						<i class="fas fa-save me-1"></i><?php echo lang('qso_simplefle_save'); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- 2. Compact Session Defaults Strip -->
		<div class="card sfle-defaults-card mb-3">
			<div class="card-body py-2 px-3">
				<div class="row g-3 align-items-end">
					<div class="col-12 col-sm-6 col-lg-3">
						<label for="qsodate" class="sfle-field-label"><?php echo lang('qso_simplefle_qso_date'); ?></label>
						<input type="date" class="form-control form-control-sm" id="qsodate" title="<?php echo htmlspecialchars(lang('qso_simplefle_qso_date_hint'), ENT_QUOTES, 'UTF-8'); ?>">
					</div>
					<div class="col-12 col-sm-6 col-lg-4">
						<label for="station-call" class="sfle-field-label"><?php echo lang('qso_simplefle_station_call_location'); ?></label>
						<select name="station_profile" class="station_id form-select form-select-sm" id="station-call" title="<?php echo htmlspecialchars(lang('qso_simplefle_station_call_location_hint'), ENT_QUOTES, 'UTF-8'); ?>">
							<option value="-">-</option>
							<?php foreach ($station_profile->result() as $station) { ?>
								<option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) { echo 'selected'; } ?>>
									<?php echo lang('gen_hamradio_callsign') . ": " . $station->station_callsign . " (" . $station->station_profile_name . ")"; ?>
								</option>
							<?php } ?>
						</select>
					</div>
					<div class="col-12 col-sm-6 col-lg-3">
						<label for="operator" class="sfle-field-label"><?php echo lang('qso_simplefle_operator'); ?></label>
						<input type="text" class="form-control form-control-sm text-uppercase fw-semibold" id="operator" value="<?php echo $this->session->userdata('operator_callsign'); ?>" placeholder="<?php echo htmlspecialchars(lang('qso_simplefle_operator_hint'), ENT_QUOTES, 'UTF-8'); ?>">
					</div>
					<div class="col-12 col-sm-6 col-lg-2 text-lg-end">
						<div class="small text-muted pb-1">
							<i class="fas fa-keyboard me-1"></i>Auto-saved in session
						</div>
					</div>
				</div>
				<div class="alert alert-danger py-2 px-3 mt-2 mb-0 small" role="alert" id="warningStationCall" style="display: none"></div>
				<div class="alert alert-danger py-2 px-3 mt-2 mb-0 small" role="alert" id="warningOperatorField" style="display: none"></div>
			</div>
		</div>

		<!-- 3. Two-Column Fast-Entry Workspace -->
		<div class="row g-3 align-items-stretch">
			<!-- Left: Monospace FLE Editor -->
			<div class="col-12 col-lg-5">
				<div class="card sfle-panel-card h-100 d-flex flex-column">
					<div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
						<span class="sfle-section-title">
							<i class="fas fa-terminal me-1 text-primary"></i><?php echo lang('qso_simplefle_enter_the_data'); ?>
						</span>
						<div class="d-flex align-items-center gap-1">
							<button type="button" class="btn btn-sm btn-outline-secondary sfle-hdr-btn js-reload-qso" title="<?php echo htmlspecialchars(lang('qso_simplefle_reload'), ENT_QUOTES, 'UTF-8'); ?>">
								<i class="fas fa-sync-alt me-1"></i><?php echo lang('qso_simplefle_reload'); ?>
							</button>
							<button type="button" class="btn btn-sm btn-outline-danger sfle-hdr-btn js-empty-qso" title="<?php echo htmlspecialchars(lang('qso_simplefle_clear'), ENT_QUOTES, 'UTF-8'); ?>">
								<i class="fas fa-trash me-1"></i><?php echo lang('qso_simplefle_clear'); ?>
							</button>
						</div>
					</div>

					<!-- Quick-Insert Snippet Bar -->
					<div class="sfle-snippet-bar px-3 py-2 d-flex flex-wrap align-items-center gap-1">
						<span class="small text-muted me-1">Insert:</span>
						<button type="button" class="sfle-chip" data-sfle-insert="20m cw&#10;">20m cw</button>
						<button type="button" class="sfle-chip" data-sfle-insert="40m ssb&#10;">40m ssb</button>
						<button type="button" class="sfle-chip" data-sfle-insert="2m fm&#10;">2m fm</button>
						<button type="button" class="sfle-chip" data-sfle-insert="sat&#10;">sat</button>
						<button type="button" class="sfle-chip" data-sfle-insert="day ++&#10;">day ++</button>
						<button type="button" class="sfle-chip" data-sfle-insert="date <?php echo gmdate('Y-m-d'); ?>&#10;">date</button>
					</div>

					<!-- Satellite Feedback Banner -->
					<div id="satellite-feedback" class="px-3 pt-2" style="display: none;">
						<div class="alert alert-success mb-0 py-2" role="alert" style="border-left: 4px solid #28a745;">
							<div class="d-flex align-items-center">
								<i class="fas fa-satellite me-2"></i>
								<strong id="sat-name-display"></strong>
							</div>
							<div id="sat-modes-display" class="mt-1 ms-4"></div>
						</div>
					</div>

					<!-- FLE Monospace Textarea -->
					<div class="card-body p-3 flex-grow-1 d-flex flex-column">
						<textarea name="qso" class="form-control qso-area sfle-textarea flex-grow-1" rows="15" id="textarea" spellcheck="false" placeholder="20m cw&#10;1215 g3xyz 599 599 #io91&#10;18 dl6kva &lt;nice sig&gt;"></textarea>
					</div>

					<!-- Inline Syntax Cheat-Sheet Footer -->
					<div class="card-footer sfle-cheatsheet py-2 px-3 d-flex flex-wrap gap-2 small text-muted">
						<span><code>20m cw</code> band/mode</span>
						<span><code>1015 G3XYZ 59 59</code> QSO</span>
						<span><code>#IO85</code> grid</span>
						<span><code>&lt;note&gt;</code> comment</span>
						<span><code>G-01234</code> POTA/SOTA/WWFF</span>
					</div>
				</div>
			</div>

			<!-- Right: Live Parsed QSO Preview -->
			<div class="col-12 col-lg-7">
				<div class="card sfle-panel-card h-100 d-flex flex-column">
					<div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
						<span class="sfle-section-title">
							<i class="fas fa-list-ul me-1 text-primary"></i><?php echo lang('qso_simplefle_qso_list'); ?>
						</span>
						<span class="badge rounded-pill sfle-count-badge js-qso-count">0 QSOs</span>
					</div>

					<div class="card-body p-0 flex-grow-1">
						<div class="qsoList table-responsive sfle-table-wrap">
							<table class="table contacttable table-striped table-hover align-middle mb-0 sfletable" id="qsoTable" style="table-layout: fixed; min-width: 970px;">
								<thead>
									<tr>
										<th style="width: 110px;"><?php echo lang('general_word_date'); ?></th>
										<th style="width: 70px;"><?php echo lang('general_word_time'); ?></th>
										<th style="width: 95px;"><?php echo lang('gen_hamradio_callsign'); ?></th>
										<th style="width: 135px;"><?php echo lang('gen_hamradio_band'); ?></th>
										<th style="width: 70px;"><?php echo lang('gen_hamradio_mode'); ?></th>
										<th style="width: 70px;"><?php echo lang('gen_hamradio_rsts'); ?></th>
										<th style="width: 70px;"><?php echo lang('gen_hamradio_rstr'); ?></th>
										<th style="width: 90px;"><?php echo lang('gen_hamradio_operator'); ?></th>
										<th style="width: 110px;"><?php echo lang('gen_hamradio_refs'); ?>*</th>
										<th style="width: 80px;">Grid</th>
										<th style="width: 150px;">Comment</th>
									</tr>
								</thead>
								<tbody id="qsoTableBody">
								</tbody>
							</table>
						</div>
					</div>

					<div class="card-footer sfle-card-footer py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
						<div class="text-muted small">
							<?php echo lang('qso_simplefle_refs_hint'); ?>
						</div>
						<div class="d-flex flex-wrap align-items-center gap-2">
							<button type="button" class="btn btn-sm btn-outline-secondary js-reload-qso">
								<i class="fas fa-sync-alt me-1"></i><?php echo lang('qso_simplefle_reload'); ?>
							</button>
							<button type="button" class="btn btn-sm btn-primary js-save-to-log">
								<i class="fas fa-save me-1"></i><?php echo lang('qso_simplefle_save'); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var ta = document.getElementById('textarea');
	if (!ta) return;
	document.querySelectorAll('[data-sfle-insert]').forEach(function(btn) {
		btn.addEventListener('click', function() {
			var snippet = btn.getAttribute('data-sfle-insert') || '';
			var start = ta.selectionStart || 0;
			var end = ta.selectionEnd || 0;
			var prefix = (start > 0 && ta.value.charAt(start - 1) !== '\n') ? '\n' : '';
			ta.value = ta.value.slice(0, start) + prefix + snippet + ta.value.slice(end);
			var nextPos = start + prefix.length + snippet.length;
			ta.focus();
			ta.selectionStart = ta.selectionEnd = nextPos;
			if (typeof handleInput === 'function') {
				handleInput();
			} else {
				ta.dispatchEvent(new Event('input', { bubbles: true }));
			}
		});
	});
});
</script>
