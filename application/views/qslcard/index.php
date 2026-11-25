<?php
if ($this->session->userdata('user_date_format')) {
	$custom_date_format = $this->session->userdata('user_date_format');
} else {
	$custom_date_format = $this->config->item('qso_date_format');
}

$qsl_rows = ($qslarray !== FALSE && is_array($qslarray->result())) ? $qslarray->result() : array();
$qsl_count = count($qsl_rows);
?>
<main class="container qsl_management qsl-page">
	<!-- Unified Page Header Card -->
	<div class="card qsl-topbar mb-3">
		<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
			<div class="d-flex align-items-center gap-2">
				<span class="qsl-brand-icon d-inline-flex align-items-center justify-content-center">
					<i class="fas fa-envelope-open-text"></i>
				</span>
				<div>
					<h1 class="h5 mb-0 fw-bold"><?php echo lang('general_word_qslcards'); ?></h1>
					<div class="small text-body-secondary">Browse, preview, and attach scanned QSL card images to your QSOs</div>
				</div>
			</div>

			<div class="d-flex flex-wrap align-items-center gap-2">
				<div class="qsl-meta-pill px-2.5 py-1 d-inline-flex align-items-center gap-2 small">
					<span><i class="fas fa-images text-primary me-1"></i><strong class="font-monospace"><?php echo $qsl_count; ?></strong> <?php echo $qsl_count === 1 ? 'Card' : 'Cards'; ?></span>
					<span class="qsl-pill-sep">|</span>
					<span title="<?php echo htmlspecialchars(lang('qslcard_string_your_are_using') . ' ' . $storage_used . ' ' . lang('qslcard_string_disk_space')); ?>">
						<i class="fas fa-hdd text-body-secondary me-1"></i><strong class="font-monospace"><?php echo htmlspecialchars($storage_used); ?></strong>
					</span>
				</div>

				<?php if ($qsl_count > 0) { ?>
					<div class="btn-group btn-group-sm" role="group" aria-label="QSL view mode">
						<button type="button" class="btn btn-outline-secondary active" id="qslViewTableBtn" onclick="setQslViewMode('table')" title="Table view">
							<i class="fas fa-list me-1"></i>Table
						</button>
						<button type="button" class="btn btn-outline-secondary" id="qslViewGridBtn" onclick="setQslViewMode('grid')" title="Gallery view">
							<i class="fas fa-th-large me-1"></i>Gallery
						</button>
					</div>
				<?php } ?>

				<a href="<?php echo site_url('logbook'); ?>" class="btn btn-sm btn-primary">
					<i class="fas fa-book-open me-1"></i>Open Logbook
				</a>
			</div>
		</div>
	</div>

	<?php if ($qsl_count > 0) { ?>
		<!-- Table View Card -->
		<div class="card qsl-content-card mb-3" id="qsl-table-view">
			<div class="card-body p-3">
				<div class="table-responsive">
					<table style="width:100%" class="qsltable table table-sm table-hover table-striped align-middle mb-0">
						<thead>
							<tr>
								<th><?php echo lang('gen_hamradio_callsign'); ?></th>
								<th><?php echo lang('gen_hamradio_band'); ?></th>
								<th><?php echo lang('gen_hamradio_mode'); ?></th>
								<th><?php echo lang('general_word_date'); ?></th>
								<th><?php echo lang('general_word_time'); ?></th>
								<th><?php echo lang('gen_hamradio_qsl'); ?></th>
								<th class="text-end" data-orderable="false">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($qsl_rows as $qsl) {
								$call_display = str_replace("0", "&Oslash;", htmlspecialchars($qsl->COL_CALL));
								$call_js = htmlspecialchars($qsl->COL_CALL, ENT_QUOTES);
								$file_js = htmlspecialchars($qsl->filename, ENT_QUOTES);
								$mode_display = $qsl->COL_SUBMODE == null ? $qsl->COL_MODE : $qsl->COL_SUBMODE;
								$timestamp = strtotime($qsl->COL_TIME_ON);
								$band_display = $qsl->COL_SAT_NAME != null ? $qsl->COL_SAT_NAME : strtolower($qsl->COL_BAND);
							?>
								<tr>
									<td class="fw-bold font-monospace"><?php echo $call_display; ?></td>
									<td><span class="badge text-bg-primary bg-opacity-75 font-monospace"><?php echo htmlspecialchars($band_display); ?></span></td>
									<td><?php echo htmlspecialchars($mode_display); ?></td>
									<td class="font-monospace"><?php echo date($custom_date_format, $timestamp); ?></td>
									<td class="font-monospace"><?php echo date('H:i', $timestamp); ?></td>
									<td class="small text-body-secondary font-monospace"><?php echo htmlspecialchars($qsl->filename); ?></td>
									<td id="<?php echo (int)$qsl->id; ?>" class="text-end text-nowrap">
										<div class="btn-group btn-group-sm" role="group">
											<button type="button" onclick="viewQsl('<?php echo $file_js; ?>', '<?php echo $call_js; ?>')" class="btn btn-outline-primary" title="View QSL Card">
												<i class="fas fa-eye me-1"></i>View
											</button>
											<button type="button" onclick="addQsosToQsl('<?php echo $file_js; ?>')" class="btn btn-outline-success" title="Attach this card to more QSOs">
												<i class="fas fa-link me-1"></i>Add QSOs
											</button>
											<button type="button" onclick="deleteQsl('<?php echo (int)$qsl->id; ?>')" class="btn btn-outline-danger" title="Delete QSL Card">
												<i class="fas fa-trash-alt"></i>
											</button>
										</div>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- Gallery Grid View -->
		<div class="row g-3 d-none" id="qsl-grid-view">
			<?php foreach ($qsl_rows as $qsl) {
				$call_display = str_replace("0", "&Oslash;", htmlspecialchars($qsl->COL_CALL));
				$call_js = htmlspecialchars($qsl->COL_CALL, ENT_QUOTES);
				$file_js = htmlspecialchars($qsl->filename, ENT_QUOTES);
				$mode_display = $qsl->COL_SUBMODE == null ? $qsl->COL_MODE : $qsl->COL_SUBMODE;
				$timestamp = strtotime($qsl->COL_TIME_ON);
				$band_display = $qsl->COL_SAT_NAME != null ? $qsl->COL_SAT_NAME : strtolower($qsl->COL_BAND);
			?>
				<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
					<div class="card qsl-gallery-card h-100">
						<div class="qsl-thumb-wrap" onclick="viewQsl('<?php echo $file_js; ?>', '<?php echo $call_js; ?>')" role="button" tabindex="0">
							<img src="<?php echo base_url('assets/qslcard/' . rawurlencode($qsl->filename)); ?>" alt="QSL card for <?php echo $call_js; ?>" class="qsl-thumb-img" loading="lazy">
						</div>
						<div class="card-body py-2 px-3">
							<div class="d-flex align-items-center justify-content-between gap-1">
								<span class="fw-bold font-monospace"><?php echo $call_display; ?></span>
								<span class="badge text-bg-primary bg-opacity-75 font-monospace"><?php echo htmlspecialchars($band_display); ?> &middot; <?php echo htmlspecialchars($mode_display); ?></span>
							</div>
							<div class="small text-body-secondary font-monospace mt-1">
								<?php echo date($custom_date_format, $timestamp); ?> <?php echo date('H:i', $timestamp); ?> UTC
							</div>
						</div>
						<div class="card-footer py-2 px-3 d-flex align-items-center justify-content-between gap-1">
							<button type="button" onclick="viewQsl('<?php echo $file_js; ?>', '<?php echo $call_js; ?>')" class="btn btn-sm btn-outline-primary">
								<i class="fas fa-eye me-1"></i>View
							</button>
							<div class="d-flex gap-1">
								<button type="button" onclick="addQsosToQsl('<?php echo $file_js; ?>')" class="btn btn-sm btn-outline-success" title="Add QSOs">
									<i class="fas fa-link"></i>
								</button>
								<button type="button" onclick="deleteQsl('<?php echo (int)$qsl->id; ?>')" class="btn btn-sm btn-outline-danger" title="Delete">
									<i class="fas fa-trash-alt"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<script>
			function setQslViewMode(mode) {
				var tableEl = document.getElementById('qsl-table-view');
				var gridEl = document.getElementById('qsl-grid-view');
				var tableBtn = document.getElementById('qslViewTableBtn');
				var gridBtn = document.getElementById('qslViewGridBtn');
				if (!tableEl || !gridEl) return;
				if (mode === 'grid') {
					tableEl.classList.add('d-none');
					gridEl.classList.remove('d-none');
					tableBtn.classList.remove('active');
					gridBtn.classList.add('active');
				} else {
					gridEl.classList.add('d-none');
					tableEl.classList.remove('d-none');
					gridBtn.classList.remove('active');
					tableBtn.classList.add('active');
					if (window.jQuery && $.fn.DataTable.isDataTable('.qsltable')) {
						$('.qsltable').DataTable().columns.adjust();
					}
				}
			}
		</script>
	<?php } else { ?>
		<!-- Dedicated Empty State Card -->
		<div class="card qsl-empty-card">
			<div class="card-body py-5 px-4 text-center">
				<div class="qsl-empty-icon mx-auto mb-3 d-inline-flex align-items-center justify-content-center">
					<i class="fas fa-envelope-open-text"></i>
				</div>
				<h2 class="h5 fw-bold mb-2">No QSL Cards Uploaded Yet</h2>
				<p class="text-body-secondary mx-auto mb-4" style="max-width: 520px;">
					Attach scanned front and back QSL card images directly to your QSOs. Once uploaded, they&rsquo;ll appear here for quick gallery browsing, searching, and linking across multiple contacts.
				</p>

				<div class="row g-3 justify-content-center text-start mx-auto mb-4" style="max-width: 780px;">
					<div class="col-12 col-md-4">
						<div class="qsl-step-tile p-3 h-100">
							<div class="qsl-step-num mb-2">1</div>
							<div class="fw-semibold small mb-1">Open a QSO</div>
							<div class="small text-body-secondary">Click any contact in your Logbook or Advanced Logbook to open its details.</div>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="qsl-step-tile p-3 h-100">
							<div class="qsl-step-num mb-2">2</div>
							<div class="fw-semibold small mb-1">Upload Front &amp; Back</div>
							<div class="small text-body-secondary">Switch to the <strong>QSL Card</strong> tab and upload JPG, PNG, or GIF scans.</div>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="qsl-step-tile p-3 h-100">
							<div class="qsl-step-num mb-2">3</div>
							<div class="fw-semibold small mb-1">Browse &amp; Link</div>
							<div class="small text-body-secondary">View your collection here in table or gallery view, or attach one card to multiple QSOs.</div>
						</div>
					</div>
				</div>

				<div class="d-flex flex-wrap justify-content-center gap-2">
					<a href="<?php echo site_url('logbook'); ?>" class="btn btn-primary px-4">
						<i class="fas fa-book-open me-1"></i>Go to Logbook
					</a>
					<a href="<?php echo site_url('logbookadvanced'); ?>" class="btn btn-outline-secondary px-4">
						<i class="fas fa-filter me-1"></i>Advanced Logbook
					</a>
				</div>
			</div>
		</div>
	<?php } ?>
</main>
