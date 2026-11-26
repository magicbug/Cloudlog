<?php
if ($this->session->userdata('user_date_format')) {
	$custom_date_format = $this->session->userdata('user_date_format');
} else {
	$custom_date_format = $this->config->item('qso_date_format');
}

$eqsl_rows = ($qslarray !== FALSE && is_array($qslarray->result())) ? $qslarray->result() : array();
$eqsl_count = count($eqsl_rows);
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
					<h1 class="h5 mb-0 fw-bold"><?php echo $this->lang->line('general_word_eqslcards'); ?></h1>
					<div class="small text-body-secondary">Browse and preview downloaded electronic QSL cards from eQSL.cc</div>
				</div>
			</div>

			<div class="d-flex flex-wrap align-items-center gap-2">
				<div class="qsl-meta-pill px-2.5 py-1 d-inline-flex align-items-center gap-2 small">
					<span><i class="fas fa-images text-primary me-1"></i><strong class="font-monospace"><?php echo number_format($eqsl_count); ?></strong> <?php echo $eqsl_count === 1 ? 'Card' : 'Cards'; ?></span>
					<span class="qsl-pill-sep">|</span>
					<span title="<?php echo htmlspecialchars($this->lang->line('qslcard_string_your_are_using') . ' ' . $storage_used . ' ' . $this->lang->line('qslcard_string_disk_space')); ?>">
						<i class="fas fa-hdd text-body-secondary me-1"></i><strong class="font-monospace"><?php echo htmlspecialchars($storage_used); ?></strong>
					</span>
				</div>

				<?php if ($eqsl_count > 0) { ?>
					<div class="btn-group btn-group-sm" role="group" aria-label="eQSL view mode">
						<button type="button" class="btn btn-outline-secondary active" id="eqslViewTableBtn" onclick="setEqslViewMode('table')" title="Table view">
							<i class="fas fa-list me-1"></i>Table
						</button>
						<button type="button" class="btn btn-outline-secondary" id="eqslViewGridBtn" onclick="setEqslViewMode('grid')" title="Gallery view">
							<i class="fas fa-th-large me-1"></i>Gallery
						</button>
					</div>
				<?php } ?>

				<a href="<?php echo site_url('eqsl/download'); ?>" class="btn btn-sm btn-primary">
					<i class="fas fa-cloud-download-alt me-1"></i>Download eQSL Cards
				</a>
			</div>
		</div>
	</div>

	<?php if ($eqsl_count > 0) { ?>
		<!-- Table View Card -->
		<div class="card qsl-content-card mb-3" id="eqsl-table-view">
			<div class="card-body p-3">
				<div class="table-responsive">
					<table style="width:100%" class="eqsltable table table-sm table-hover table-striped align-middle mb-0">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('gen_hamradio_callsign'); ?></th>
								<th><?php echo $this->lang->line('gen_hamradio_band'); ?></th>
								<th><?php echo $this->lang->line('gen_hamradio_mode'); ?></th>
								<th><?php echo $this->lang->line('general_word_date'); ?></th>
								<th><?php echo $this->lang->line('general_word_time'); ?></th>
								<th><?php echo $this->lang->line('gen_hamradio_qsl'); ?></th>
								<th class="text-end" data-orderable="false">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($eqsl_rows as $qsl) {
								$call_display = str_replace("0", "&Oslash;", htmlspecialchars($qsl->COL_CALL));
								$call_js = htmlspecialchars($qsl->COL_CALL, ENT_QUOTES);
								$file_js = htmlspecialchars($qsl->image_file, ENT_QUOTES);
								$mode_display = $qsl->COL_SUBMODE == null ? $qsl->COL_MODE : $qsl->COL_SUBMODE;
								$timestamp = strtotime($qsl->COL_TIME_ON);
								$band_display = $qsl->COL_SAT_NAME != null ? $qsl->COL_SAT_NAME : strtolower($qsl->COL_BAND);
							?>
								<tr>
									<td class="fw-bold font-monospace">
										<a href="javascript:displayQso(<?php echo (int)$qsl->COL_PRIMARY_KEY; ?>)" class="text-decoration-none"><?php echo $call_display; ?></a>
									</td>
									<td><span class="badge text-bg-primary bg-opacity-75 font-monospace"><?php echo htmlspecialchars($band_display); ?></span></td>
									<td><?php echo htmlspecialchars($mode_display); ?></td>
									<td class="font-monospace"><?php echo date($custom_date_format, $timestamp); ?></td>
									<td class="font-monospace"><?php echo date('H:i', $timestamp); ?></td>
									<td class="small text-body-secondary font-monospace"><?php echo htmlspecialchars($qsl->image_file); ?></td>
									<td class="text-end text-nowrap">
										<button type="button" onclick="viewEqsl('<?php echo $file_js; ?>', '<?php echo $call_js; ?>')" class="btn btn-sm btn-outline-primary" title="View eQSL Card">
											<i class="fas fa-eye me-1"></i>View
										</button>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- Gallery Grid View -->
		<div class="row g-3 d-none" id="eqsl-grid-view">
			<?php foreach ($eqsl_rows as $qsl) {
				$call_display = str_replace("0", "&Oslash;", htmlspecialchars($qsl->COL_CALL));
				$call_js = htmlspecialchars($qsl->COL_CALL, ENT_QUOTES);
				$file_js = htmlspecialchars($qsl->image_file, ENT_QUOTES);
				$mode_display = $qsl->COL_SUBMODE == null ? $qsl->COL_MODE : $qsl->COL_SUBMODE;
				$timestamp = strtotime($qsl->COL_TIME_ON);
				$band_display = $qsl->COL_SAT_NAME != null ? $qsl->COL_SAT_NAME : strtolower($qsl->COL_BAND);
			?>
				<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
					<div class="card qsl-gallery-card h-100">
						<div class="qsl-thumb-wrap" onclick="viewEqsl('<?php echo $file_js; ?>', '<?php echo $call_js; ?>')" role="button" tabindex="0">
							<img src="<?php echo base_url('images/eqsl_card_images/' . rawurlencode($qsl->image_file)); ?>" alt="eQSL card for <?php echo $call_js; ?>" class="qsl-thumb-img" loading="lazy">
						</div>
						<div class="card-body py-2 px-3">
							<div class="d-flex align-items-center justify-content-between gap-1">
								<a href="javascript:displayQso(<?php echo (int)$qsl->COL_PRIMARY_KEY; ?>)" class="fw-bold font-monospace text-decoration-none"><?php echo $call_display; ?></a>
								<span class="badge text-bg-primary bg-opacity-75 font-monospace"><?php echo htmlspecialchars($band_display); ?> &middot; <?php echo htmlspecialchars($mode_display); ?></span>
							</div>
							<div class="small text-body-secondary font-monospace mt-1">
								<?php echo date($custom_date_format, $timestamp); ?> <?php echo date('H:i', $timestamp); ?> UTC
							</div>
						</div>
						<div class="card-footer py-2 px-3 d-flex align-items-center justify-content-between gap-1">
							<button type="button" onclick="viewEqsl('<?php echo $file_js; ?>', '<?php echo $call_js; ?>')" class="btn btn-sm btn-outline-primary">
								<i class="fas fa-eye me-1"></i>View
							</button>
							<button type="button" onclick="displayQso(<?php echo (int)$qsl->COL_PRIMARY_KEY; ?>)" class="btn btn-sm btn-outline-secondary" title="View QSO details">
								<i class="fas fa-info-circle"></i>
							</button>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<script>
			function setEqslViewMode(mode) {
				var tableEl = document.getElementById('eqsl-table-view');
				var gridEl = document.getElementById('eqsl-grid-view');
				var tableBtn = document.getElementById('eqslViewTableBtn');
				var gridBtn = document.getElementById('eqslViewGridBtn');
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
					if (window.jQuery && $.fn.DataTable.isDataTable('.eqsltable')) {
						$('.eqsltable').DataTable().columns.adjust();
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
				<h2 class="h5 fw-bold mb-2">No eQSL Cards Downloaded Yet</h2>
				<p class="text-body-secondary mx-auto mb-4" style="max-width: 520px;">
					Download electronic QSL card images directly from eQSL.cc for your confirmed contacts. Once downloaded, they&rsquo;ll appear here for browsing in table or gallery view.
				</p>

				<div class="row g-3 justify-content-center text-start mx-auto mb-4" style="max-width: 780px;">
					<div class="col-12 col-md-4">
						<div class="qsl-step-tile p-3 h-100">
							<div class="qsl-step-num mb-2">1</div>
							<div class="fw-semibold small mb-1">Connect eQSL</div>
							<div class="small text-body-secondary">Set your eQSL credentials and QTH nickname in your Account and Station Profile.</div>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="qsl-step-tile p-3 h-100">
							<div class="qsl-step-num mb-2">2</div>
							<div class="fw-semibold small mb-1">Import Confirmations</div>
							<div class="small text-body-secondary">Sync received eQSL confirmations using <strong>eQSL Import / Export</strong>.</div>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="qsl-step-tile p-3 h-100">
							<div class="qsl-step-num mb-2">3</div>
							<div class="fw-semibold small mb-1">Download Cards</div>
							<div class="small text-body-secondary">Fetch card images from <strong>Download eQSL Cards</strong> to browse them here.</div>
						</div>
					</div>
				</div>

				<div class="d-flex flex-wrap justify-content-center gap-2">
					<a href="<?php echo site_url('eqsl/download'); ?>" class="btn btn-primary px-4">
						<i class="fas fa-cloud-download-alt me-1"></i>Download eQSL Cards
					</a>
					<a href="<?php echo site_url('eqsl/import'); ?>" class="btn btn-outline-secondary px-4">
						<i class="fas fa-sync me-1"></i>eQSL Import / Export
					</a>
				</div>
			</div>
		</div>
	<?php } ?>
</main>
