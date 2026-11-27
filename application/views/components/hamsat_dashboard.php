<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (!empty($error)) { ?>
	<div class="card">
		<div class="card-header">
			<span class="card-header-title">
				<i class="fas fa-satellite"></i>
				<span>Hamsat Rovers</span>
			</span>
			<a href="<?php echo site_url('hamsat'); ?>" class="btn btn-outline-secondary btn-sm py-0 px-2">Full List</a>
		</div>
		<div class="card-body py-2">
			<div class="alert alert-warning py-2 mb-0 small" role="alert">
				<i class="fas fa-exclamation-triangle me-1"></i> <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
			</div>
		</div>
	</div>
<?php } else {
	$pageSize = 6;
	$recordCount = isset($passes) && is_array($passes) ? count($passes) : 0;
	$totalPages = max(1, (int) ceil($recordCount / $pageSize));
	$hasKey = !empty($has_key);
	$workableDefault = !empty($workable_only_default);
	$workableCount = 0;
	if ($recordCount > 0) {
		foreach ($passes as $p) {
			if (!empty($p['is_workable'])) {
				$workableCount++;
			}
		}
	}
?>
	<div id="hamsat-dashboard-card" class="card" data-pagesize="<?php echo $pageSize; ?>" data-has-key="<?php echo $hasKey ? '1' : '0'; ?>" data-workable-default="<?php echo $workableDefault ? '1' : '0'; ?>">
		<style>
			#hamsat-dashboard-card table {
				--bs-table-striped-bg: transparent;
				--bs-table-accent-bg: transparent;
				font-size: 0.85rem;
			}
			#hamsat-dashboard-card .hamsat-dash-row > td {
				box-shadow: none !important;
				padding: 0.45rem 0.65rem;
				vertical-align: middle;
			}
			#hamsat-dashboard-card .hamsat-dash-row.hamsat-worked > td {
				background-color: rgba(25, 135, 84, 0.14) !important;
			}
			#hamsat-dashboard-card .hamsat-dash-row.hamsat-needed > td {
				background-color: rgba(220, 53, 69, 0.12) !important;
			}
			#hamsat-dashboard-card .hamsat-legend-dot {
				width: 0.55rem;
				height: 0.55rem;
				border-radius: 50%;
				display: inline-block;
			}
			#hamsat-dashboard-card .hamsat-legend-worked {
				background-color: rgba(25, 135, 84, 0.75);
			}
			#hamsat-dashboard-card .hamsat-legend-needed {
				background-color: rgba(220, 53, 69, 0.7);
			}
			#hamsat-dashboard-card .hamsat-dash-grid {
				display: inline-block;
				font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
				font-size: 0.72rem;
				font-weight: 700;
				padding: 0.08rem 0.35rem;
				border-radius: 0.3rem;
				line-height: 1.3;
			}
			#hamsat-dashboard-card .hamsat-dash-grid.is-worked {
				background: rgba(25, 135, 84, 0.2);
				color: #198754;
			}
			#hamsat-dashboard-card .hamsat-dash-grid.is-needed {
				background: rgba(220, 53, 69, 0.18);
				color: #dc3545;
			}
			#hamsat-dashboard-card .hamsat-dash-live {
				display: inline-flex;
				align-items: center;
				gap: 0.25rem;
				font-size: 0.7rem;
				font-weight: 700;
				color: #198754;
			}
			#hamsat-dashboard-card .hamsat-dash-live-dot {
				width: 0.45rem;
				height: 0.45rem;
				border-radius: 50%;
				background: #198754;
				display: inline-block;
			}
			#hamsat-dashboard-card .hamsat-dash-workable-badge {
				display: inline-block;
				font-size: 0.65rem;
				font-weight: 600;
				padding: 0.02rem 0.3rem;
				border-radius: 0.25rem;
				background: rgba(13, 110, 253, 0.15);
				color: #0d6efd;
				vertical-align: middle;
				margin-left: 0.25rem;
			}
			#hamsat-dash-workable-btn.is-active {
				background-color: var(--bs-primary, #0d6efd);
				border-color: var(--bs-primary, #0d6efd);
				color: #fff;
			}
		</style>

		<div class="card-header flex-wrap gap-1">
			<span class="card-header-title">
				<i class="fas fa-satellite"></i>
				<span>Hamsat Rovers</span>
			</span>
			<div class="d-flex align-items-center gap-2">
				<span class="d-none d-xl-inline-flex align-items-center gap-2 small fw-normal text-muted" style="font-size: 0.74rem;">
					<span class="d-inline-flex align-items-center gap-1"><span class="hamsat-legend-dot hamsat-legend-worked"></span>Worked</span>
					<span class="d-inline-flex align-items-center gap-1"><span class="hamsat-legend-dot hamsat-legend-needed"></span>Needed</span>
				</span>
				<?php if ($hasKey) { ?>
					<button type="button" id="hamsat-dash-workable-btn" class="btn btn-outline-secondary btn-sm py-0 px-2<?php echo $workableDefault ? ' is-active' : ''; ?>" title="Toggle workable passes only from your QTH" aria-pressed="<?php echo $workableDefault ? 'true' : 'false'; ?>">
						<i class="fas fa-check-circle me-1"></i>Workable<?php if ($workableCount > 0) { ?> (<?php echo $workableCount; ?>)<?php } ?>
					</button>
				<?php } else { ?>
					<a href="<?php echo site_url('user/edit/' . $this->session->userdata('user_id')); ?>" class="btn btn-outline-secondary btn-sm py-0 px-2 opacity-75" title="Add your Hamsat private feed key in Account settings to filter workable passes">
						<i class="fas fa-key me-1"></i>Workable
					</a>
				<?php } ?>
				<a href="<?php echo site_url('hamsat'); ?>" class="btn btn-outline-secondary btn-sm py-0 px-2">Full List</a>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table mb-0">
				<tbody>
					<?php if ($recordCount === 0) { ?>
						<tr>
							<td colspan="3" class="text-muted text-center py-3">No upcoming Hamsat activations found.</td>
						</tr>
					<?php } else { ?>
						<?php foreach ($passes as $index => $pass) {
							$rowClass = !empty($pass['grids']) ? ($pass['has_needed'] ? 'hamsat-needed' : 'hamsat-worked') : '';
							$page = (int) floor($index / $pageSize) + 1;
							$tooltip = trim(($pass['aos_utc'] !== '' ? $pass['aos_utc'] : '') . ($pass['comment'] !== '' ? ' — ' . $pass['comment'] : ''));
						?>
							<tr class="hamsat-dash-row <?php echo $rowClass; ?>" data-workable="<?php echo !empty($pass['is_workable']) ? '1' : '0'; ?>" <?php echo $page > 1 ? 'style="display:none;"' : ''; ?>>
								<td class="text-nowrap small">
									<?php if ($pass['is_live']) { ?>
										<span class="hamsat-dash-live" title="<?php echo htmlspecialchars($tooltip, ENT_QUOTES, 'UTF-8'); ?>">
											<span class="hamsat-dash-live-dot"></span><?php echo htmlspecialchars($pass['time_label'], ENT_QUOTES, 'UTF-8'); ?>
										</span>
									<?php } else { ?>
										<span class="text-muted" title="<?php echo htmlspecialchars($tooltip, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($pass['time_label'], ENT_QUOTES, 'UTF-8'); ?></span>
									<?php } ?>
								</td>
								<td class="text-nowrap">
									<a target="_blank" rel="noopener" href="<?php echo htmlspecialchars($pass['url'], ENT_QUOTES, 'UTF-8'); ?>" class="fw-semibold text-decoration-none" title="<?php echo htmlspecialchars($tooltip, ENT_QUOTES, 'UTF-8'); ?>">
										<?php echo htmlspecialchars($pass['callsign'], ENT_QUOTES, 'UTF-8'); ?>
									</a>
									<?php if ($hasKey && !empty($pass['is_workable'])) { ?>
										<span class="hamsat-dash-workable-badge" title="Workable from your QTH">Workable</span>
									<?php } ?>
									<div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">
										<?php echo htmlspecialchars($pass['sat'], ENT_QUOTES, 'UTF-8'); ?>
										<?php if ($pass['mode'] !== '') { ?>
											· <?php echo htmlspecialchars($pass['mode'], ENT_QUOTES, 'UTF-8'); ?>
										<?php } ?>
									</div>
								</td>
								<td class="text-end text-nowrap">
									<?php foreach ($pass['grids'] as $g) { ?>
										<span class="hamsat-dash-grid <?php echo $g['worked'] ? 'is-worked' : 'is-needed'; ?>" title="<?php echo $g['worked'] ? 'Worked on SAT' : 'Needed on SAT'; ?>">
											<?php echo htmlspecialchars($g['grid'], ENT_QUOTES, 'UTF-8'); ?>
										</span>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						<tr id="hamsat-dash-empty-workable" style="display:none;">
							<td colspan="3" class="text-muted text-center py-3">No workable passes right now.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

		<?php if ($recordCount > 0) { ?>
			<div id="hamsat-dash-footer" class="card-footer d-flex justify-content-between align-items-center py-2 px-3" <?php echo $recordCount <= $pageSize ? 'style="display:none !important;"' : ''; ?>>
				<small class="text-muted">Showing <span id="hamsat-dash-range">1-<?php echo min($pageSize, $recordCount); ?></span> of <span id="hamsat-dash-total"><?php echo $recordCount; ?></span></small>
				<div class="btn-group btn-group-sm" role="group" aria-label="Hamsat pagination">
					<button type="button" class="btn btn-outline-secondary" id="hamsat-dash-prev" disabled>Prev</button>
					<button type="button" class="btn btn-outline-secondary" id="hamsat-dash-page-indicator" disabled>1 / <?php echo $totalPages; ?></button>
					<button type="button" class="btn btn-outline-secondary" id="hamsat-dash-next">Next</button>
				</div>
			</div>

			<script>
				(function() {
					var card = document.getElementById('hamsat-dashboard-card');
					if (!card) return;

					var allRows = Array.prototype.slice.call(card.querySelectorAll('.hamsat-dash-row'));
					var emptyRow = card.querySelector('#hamsat-dash-empty-workable');
					var footer = card.querySelector('#hamsat-dash-footer');
					var prevBtn = card.querySelector('#hamsat-dash-prev');
					var nextBtn = card.querySelector('#hamsat-dash-next');
					var pageInd = card.querySelector('#hamsat-dash-page-indicator');
					var range = card.querySelector('#hamsat-dash-range');
					var totalEl = card.querySelector('#hamsat-dash-total');
					var workableBtn = card.querySelector('#hamsat-dash-workable-btn');
					var pageSize = parseInt(card.getAttribute('data-pagesize'), 10) || 6;
					var hasKey = card.getAttribute('data-has-key') === '1';
					var storageKey = 'cloudlog_dash_hamsat_workable';

					var workableOnly = card.getAttribute('data-workable-default') === '1';
					if (hasKey) {
						try {
							var saved = localStorage.getItem(storageKey);
							if (saved === '1') workableOnly = true;
							else if (saved === '0') workableOnly = false;
						} catch (e) {}
					} else {
						workableOnly = false;
					}

					var currentPage = 1;

					function render() {
						var visibleRows = allRows.filter(function(row) {
							return !workableOnly || row.getAttribute('data-workable') === '1';
						});

						var totalRows = visibleRows.length;
						var totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
						if (currentPage > totalPages) currentPage = totalPages;

						var startIdx = (currentPage - 1) * pageSize;
						var endIdx = startIdx + pageSize;

						allRows.forEach(function(row) {
							row.style.display = 'none';
						});

						visibleRows.forEach(function(row, idx) {
							row.style.display = (idx >= startIdx && idx < endIdx) ? '' : 'none';
						});

						if (emptyRow) {
							emptyRow.style.display = (totalRows === 0) ? '' : 'none';
						}

						if (workableBtn) {
							workableBtn.classList.toggle('is-active', workableOnly);
							workableBtn.setAttribute('aria-pressed', workableOnly ? 'true' : 'false');
						}

						if (footer) {
							if (totalRows > pageSize) {
								footer.style.removeProperty('display');
								if (range) range.textContent = (startIdx + 1) + '-' + Math.min(endIdx, totalRows);
								if (totalEl) totalEl.textContent = String(totalRows);
								if (pageInd) pageInd.textContent = currentPage + ' / ' + totalPages;
								if (prevBtn) prevBtn.disabled = currentPage === 1;
								if (nextBtn) nextBtn.disabled = currentPage === totalPages;
							} else {
								footer.style.setProperty('display', 'none', 'important');
							}
						}
					}

					if (workableBtn) {
						workableBtn.addEventListener('click', function() {
							workableOnly = !workableOnly;
							currentPage = 1;
							try {
								localStorage.setItem(storageKey, workableOnly ? '1' : '0');
							} catch (e) {}
							render();
						});
					}

					if (prevBtn) {
						prevBtn.addEventListener('click', function() {
							if (currentPage > 1) { currentPage--; render(); }
						});
					}
					if (nextBtn) {
						nextBtn.addEventListener('click', function() {
							var visibleCount = allRows.filter(function(r) {
								return !workableOnly || r.getAttribute('data-workable') === '1';
							}).length;
							var totalPages = Math.max(1, Math.ceil(visibleCount / pageSize));
							if (currentPage < totalPages) { currentPage++; render(); }
						});
					}

					render();
				})();
			</script>
		<?php } ?>
	</div>
<?php } ?>
