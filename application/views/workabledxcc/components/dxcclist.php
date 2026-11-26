<?php
if (isset($dxcclist['error'])) {
?>
<div class="card dxped-card">
	<div class="card-body text-center py-5">
		<div class="text-warning mb-2" style="font-size: 2rem;"><i class="fas fa-exclamation-triangle"></i></div>
		<h2 class="h5 fw-semibold mb-1">Unable to load DXpedition feed</h2>
		<p class="text-muted mb-0"><?php echo htmlspecialchars($dxcclist['error'], ENT_QUOTES, 'UTF-8'); ?></p>
	</div>
</div>
<?php
	return;
}

$today = new DateTime('today', new DateTimeZone('UTC'));
$total_count = 0;
$on_air_count = 0;
$needed_dxcc_count = 0;
$unconfirmed_dxcc_count = 0;
$needed_iota_count = 0;

$on_air_items = array();
$grouped = array();

foreach ($dxcclist as $dxcc) {
	$total_count++;
	$start_dt = DateTime::createFromFormat('Y-m-d', $dxcc['clean_date'], new DateTimeZone('UTC'));
	$end_raw = $dxcc['clean_end_date'] ?? $dxcc['clean_date'];
	$end_dt = DateTime::createFromFormat('Y-m-d', $end_raw, new DateTimeZone('UTC'));

	$is_active = false;
	$timing_label = '';
	$timing_class = 'is-upcoming';

	if ($start_dt && $end_dt) {
		if ($today >= $start_dt && $today <= $end_dt) {
			$is_active = true;
			$on_air_count++;
			$days_left = (int)$today->diff($end_dt)->days;
			$timing_label = $days_left === 0 ? 'Ends today' : ($days_left === 1 ? '1 day left' : $days_left . ' days left');
			$timing_class = 'is-live';
		} elseif ($start_dt > $today) {
			$days_until = (int)$today->diff($start_dt)->days;
			if ($days_until <= 14) {
				$timing_label = $days_until === 1 ? 'Starts tomorrow' : 'In ' . $days_until . ' days';
				$timing_class = 'is-soon';
			} else {
				$timing_label = 'In ' . $days_until . ' days';
			}
		} else {
			$timing_label = 'Ended';
		}
	}

	$is_needed = empty($dxcc['workedBefore']);
	$is_confirmed = !empty($dxcc['confirmed']);
	$is_unconfirmed = !$is_needed && !$is_confirmed;
	$has_iota = !empty($dxcc['iota']);
	$iota_worked = $has_iota && !empty($dxcc['iota_status']['worked']);
	$iota_confirmed = $has_iota && !empty($dxcc['iota_status']['confirmed']);
	$is_needed_iota = $has_iota && !$iota_worked;

	if ($is_needed) {
		$needed_dxcc_count++;
	} elseif ($is_unconfirmed) {
		$unconfirmed_dxcc_count++;
	}
	if ($is_needed_iota) {
		$needed_iota_count++;
	}

	$dxcc['_is_active'] = $is_active;
	$dxcc['_timing_label'] = $timing_label;
	$dxcc['_timing_class'] = $timing_class;
	$dxcc['_is_needed'] = $is_needed;
	$dxcc['_is_confirmed'] = $is_confirmed;
	$dxcc['_is_unconfirmed'] = $is_unconfirmed;
	$dxcc['_has_iota'] = $has_iota;
	$dxcc['_iota_worked'] = $iota_worked;
	$dxcc['_iota_confirmed'] = $iota_confirmed;
	$dxcc['_is_needed_iota'] = $is_needed_iota;

	if ($is_active) {
		$on_air_items[] = $dxcc;
	}

	$month = $start_dt ? $start_dt->format('F Y') : date('F Y', strtotime($dxcc['clean_date']));
	if (!isset($grouped[$month])) {
		$grouped[$month] = array();
	}
	$grouped[$month][] = $dxcc;
}
?>

<!-- KPI Summary Strip -->
<div class="row g-3 mb-3">
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setDxpedFilter('live')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label d-flex align-items-center gap-2">
					<span class="dxped-live-dot"></span>On Air Now
				</div>
				<div class="dxped-kpi-value text-success mt-1"><?php echo number_format($on_air_count); ?></div>
				<div class="small text-muted mt-1">Active DXpeditions today</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setDxpedFilter('needed')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label">ATNO &middot; Needed DXCC</div>
				<div class="dxped-kpi-value text-danger mt-1"><?php echo number_format($needed_dxcc_count); ?></div>
				<div class="small text-muted mt-1">Not worked in active logbook</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setDxpedFilter('unconfirmed')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label">Worked &middot; Unconfirmed</div>
				<div class="dxped-kpi-value text-warning mt-1"><?php echo number_format($unconfirmed_dxcc_count); ?></div>
				<div class="small text-muted mt-1">Entities needing confirmation</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setDxpedFilter('all')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label">Total Upcoming</div>
				<div class="dxped-kpi-value mt-1"><?php echo number_format($total_count); ?></div>
				<div class="small text-muted mt-1">
					<?php echo $needed_iota_count > 0 ? number_format($needed_iota_count) . ' needed IOTA refs' : 'Across ' . count($grouped) . ' months'; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Instant Filter & Search Bar -->
<div class="card dxped-filter-card mb-3">
	<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
		<div class="d-flex flex-wrap align-items-center gap-1" id="dxpedFilterPills">
			<button type="button" class="btn btn-sm dxped-pill is-active" data-filter="all" onclick="setDxpedFilter('all')">
				All <span class="ms-1 opacity-75"><?php echo $total_count; ?></span>
			</button>
			<button type="button" class="btn btn-sm dxped-pill" data-filter="live" onclick="setDxpedFilter('live')">
				<span class="dxped-live-dot me-1"></span>On Air Now <span class="ms-1 opacity-75"><?php echo $on_air_count; ?></span>
			</button>
			<button type="button" class="btn btn-sm dxped-pill" data-filter="needed" onclick="setDxpedFilter('needed')">
				<i class="fas fa-star text-danger me-1"></i>Needed DXCC <span class="ms-1 opacity-75"><?php echo $needed_dxcc_count; ?></span>
			</button>
			<button type="button" class="btn btn-sm dxped-pill" data-filter="unconfirmed" onclick="setDxpedFilter('unconfirmed')">
				<i class="fas fa-clock text-warning me-1"></i>Unconfirmed <span class="ms-1 opacity-75"><?php echo $unconfirmed_dxcc_count; ?></span>
			</button>
			<?php if ($needed_iota_count > 0) { ?>
			<button type="button" class="btn btn-sm dxped-pill" data-filter="iota" onclick="setDxpedFilter('iota')">
				<i class="fas fa-water text-info me-1"></i>Needed IOTA <span class="ms-1 opacity-75"><?php echo $needed_iota_count; ?></span>
			</button>
			<?php } ?>
		</div>

		<div class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0">
			<div class="input-group input-group-sm" style="min-width: 220px;">
				<span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
				<input type="search" id="dxpedSearchInput" class="form-control" placeholder="Search callsign, DXCC, IOTA...">
			</div>
			<span class="small text-muted text-nowrap d-none d-sm-inline" id="dxpedMatchCount"><?php echo $total_count; ?> shown</span>
		</div>
	</div>
</div>

<!-- Month-Grouped DXpedition Cards -->
<div id="dxpedGroupsWrap">
<?php foreach ($grouped as $month => $dxccs) {
	$month_needed = 0;
	$month_live = 0;
	foreach ($dxccs as $d) {
		if ($d['_is_needed']) $month_needed++;
		if ($d['_is_active']) $month_live++;
	}
?>
	<div class="card dxped-month-card mb-3" data-dxped-month>
		<div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
			<div class="d-flex align-items-center gap-2">
				<i class="far fa-calendar-alt text-primary"></i>
				<span class="fw-semibold"><?php echo htmlspecialchars($month, ENT_QUOTES, 'UTF-8'); ?></span>
				<span class="badge rounded-pill dxped-month-count"><?php echo count($dxccs); ?></span>
			</div>
			<div class="d-flex align-items-center gap-2 small">
				<?php if ($month_live > 0) { ?>
					<span class="badge rounded-pill text-bg-success"><i class="fas fa-broadcast-tower me-1"></i><?php echo $month_live; ?> on air</span>
				<?php } ?>
				<?php if ($month_needed > 0) { ?>
					<span class="badge rounded-pill text-bg-danger"><?php echo $month_needed; ?> needed</span>
				<?php } ?>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-hover align-middle mb-0 dxped-table">
				<thead>
					<tr>
						<th class="ps-3" style="width: 175px;">Dates</th>
						<th style="width: 185px;">DXCC Entity</th>
						<th style="width: 140px;">Callsign</th>
						<th style="width: 245px;">Logbook &amp; IOTA Status</th>
						<th>Notes</th>
						<th class="text-end pe-3" style="width: 105px;">Quick Links</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($dxccs as $dxcc) {
					$call_raw = trim((string)($dxcc['callsign'] ?? ''));
					$call_esc = htmlspecialchars($call_raw, ENT_QUOTES, 'UTF-8');
					$country_esc = htmlspecialchars((string)($dxcc['country'] ?? ''), ENT_QUOTES, 'UTF-8');
					$notes_esc = htmlspecialchars((string)($dxcc['notes'] ?? ''), ENT_QUOTES, 'UTF-8');
					$iota_raw = trim((string)($dxcc['iota'] ?? ''));
					$search_haystack = strtolower($call_raw . ' ' . ($dxcc['country'] ?? '') . ' ' . $iota_raw . ' ' . ($dxcc['notes'] ?? ''));
					$row_classes = array('dxped-row');
					if ($dxcc['_is_active']) $row_classes[] = 'is-live-row';
					if ($dxcc['_is_needed']) $row_classes[] = 'is-needed-row';
				?>
					<tr class="<?php echo implode(' ', $row_classes); ?>"
						data-dxped-row
						data-live="<?php echo $dxcc['_is_active'] ? '1' : '0'; ?>"
						data-needed="<?php echo $dxcc['_is_needed'] ? '1' : '0'; ?>"
						data-unconfirmed="<?php echo $dxcc['_is_unconfirmed'] ? '1' : '0'; ?>"
						data-iota-needed="<?php echo $dxcc['_is_needed_iota'] ? '1' : '0'; ?>"
						data-search="<?php echo htmlspecialchars($search_haystack, ENT_QUOTES, 'UTF-8'); ?>">
						<td class="ps-3 text-nowrap">
							<div class="small fw-semibold font-monospace"><?php echo htmlspecialchars($dxcc['start_date'], ENT_QUOTES, 'UTF-8'); ?> &ndash; <?php echo htmlspecialchars($dxcc['end_date'], ENT_QUOTES, 'UTF-8'); ?></div>
							<?php if ($dxcc['_timing_label'] !== '') { ?>
								<span class="dxped-timing-tag <?php echo $dxcc['_timing_class']; ?>">
									<?php if ($dxcc['_is_active']) { ?><span class="dxped-live-dot me-1"></span><?php } ?>
									<?php echo htmlspecialchars($dxcc['_timing_label'], ENT_QUOTES, 'UTF-8'); ?>
								</span>
							<?php } ?>
						</td>
						<td>
							<div class="fw-semibold"><?php echo $country_esc; ?></div>
						</td>
						<td>
							<?php if ($call_raw !== '') { ?>
								<a href="https://www.qrz.com/db/<?php echo rawurlencode($call_raw); ?>" target="_blank" class="dxped-call-chip text-decoration-none" title="Lookup <?php echo $call_esc; ?> on QRZ.com">
									<?php echo $call_esc; ?>
								</a>
							<?php } else { ?>
								<span class="text-muted small">TBA</span>
							<?php } ?>
						</td>
						<td>
							<div class="d-flex flex-wrap gap-1">
								<?php if ($dxcc['_is_needed']) { ?>
									<span class="badge dxped-status-needed"><i class="fas fa-star me-1"></i>ATNO &middot; Needed</span>
								<?php } else { ?>
									<span class="badge dxped-status-worked"><i class="fas fa-check me-1"></i>Worked</span>
								<?php } ?>

								<?php if ($dxcc['_is_confirmed']) { ?>
									<span class="badge dxped-status-confirmed"><i class="fas fa-check-double me-1"></i>Confirmed</span>
								<?php } ?>

								<?php if (!empty($dxcc['workedViaSatellite'])) { ?>
									<span class="badge dxped-status-sat"><i class="fas fa-satellite me-1"></i>SAT</span>
								<?php } ?>

								<?php if ($dxcc['_has_iota']) {
									$iota_esc = htmlspecialchars($iota_raw, ENT_QUOTES, 'UTF-8');
									$mapUrl = 'https://www.iota-world.org/iotamaps/?grpref=' . rawurlencode($iota_raw);
									if ($dxcc['_iota_worked']) { ?>
										<a href="<?php echo $mapUrl; ?>" target="_blank" class="badge dxped-status-worked text-decoration-none">IOTA <?php echo $iota_esc; ?><?php echo $dxcc['_iota_confirmed'] ? ' &check;' : ''; ?></a>
									<?php } else { ?>
										<a href="<?php echo $mapUrl; ?>" target="_blank" class="badge dxped-status-needed text-decoration-none"><i class="fas fa-water me-1"></i>IOTA <?php echo $iota_esc; ?> Needed</a>
									<?php } ?>
								<?php } ?>
							</div>
						</td>
						<td>
							<div class="small text-muted dxped-notes-cell"><?php echo $notes_esc !== '' ? $notes_esc : '&mdash;'; ?></div>
						</td>
						<td class="text-end pe-3 text-nowrap">
							<?php if ($call_raw !== '') { ?>
								<a href="<?php echo site_url('search?callsign=' . rawurlencode($call_raw)); ?>" class="btn btn-sm btn-outline-secondary dxped-act-btn" title="Search <?php echo $call_esc; ?> in your logbook">
									<i class="fas fa-search"></i>
								</a>
								<a href="https://www.qrz.com/db/<?php echo rawurlencode($call_raw); ?>" target="_blank" class="btn btn-sm btn-outline-secondary dxped-act-btn" title="Open <?php echo $call_esc; ?> on QRZ.com">
									<i class="fas fa-external-link-alt"></i>
								</a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php } ?>
</div>

<!-- Empty Filter State -->
<div class="card dxped-card d-none" id="dxpedEmptyState">
	<div class="card-body text-center py-5">
		<div class="text-muted mb-2" style="font-size: 1.8rem;"><i class="fas fa-filter"></i></div>
		<h2 class="h6 fw-semibold mb-1">No matching DXpeditions</h2>
		<p class="small text-muted mb-3">Try switching filter pills or clearing your search query.</p>
		<button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetDxpedFilters()">Reset Filters</button>
	</div>
</div>

<div class="small text-muted text-end mt-2">
	<i class="fas fa-database me-1"></i>DXpedition schedule aggregated by Cloudlog from multiple DX sources.
</div>

<script>
(function() {
	var activeFilter = 'all';
	var searchInput = document.getElementById('dxpedSearchInput');
	var matchCountEl = document.getElementById('dxpedMatchCount');
	var emptyEl = document.getElementById('dxpedEmptyState');

	function applyDxpedFilters() {
		var q = (searchInput ? searchInput.value : '').trim().toLowerCase();
		var totalShown = 0;

		document.querySelectorAll('[data-dxped-month]').forEach(function(card) {
			var shownInMonth = 0;
			card.querySelectorAll('[data-dxped-row]').forEach(function(row) {
				var okFilter = true;
				if (activeFilter === 'live') okFilter = row.getAttribute('data-live') === '1';
				else if (activeFilter === 'needed') okFilter = row.getAttribute('data-needed') === '1';
				else if (activeFilter === 'unconfirmed') okFilter = row.getAttribute('data-unconfirmed') === '1';
				else if (activeFilter === 'iota') okFilter = row.getAttribute('data-iota-needed') === '1';

				var okSearch = !q || (row.getAttribute('data-search') || '').indexOf(q) !== -1;
				var visible = okFilter && okSearch;
				row.style.display = visible ? '' : 'none';
				if (visible) {
					shownInMonth++;
					totalShown++;
				}
			});
			card.style.display = shownInMonth > 0 ? '' : 'none';
		});

		if (matchCountEl) {
			matchCountEl.textContent = totalShown + ' shown';
		}
		if (emptyEl) {
			emptyEl.classList.toggle('d-none', totalShown > 0);
		}
	}

	window.setDxpedFilter = function(filter) {
		activeFilter = filter || 'all';
		document.querySelectorAll('#dxpedFilterPills [data-filter]').forEach(function(btn) {
			btn.classList.toggle('is-active', btn.getAttribute('data-filter') === activeFilter);
		});
		applyDxpedFilters();
	};

	window.resetDxpedFilters = function() {
		if (searchInput) searchInput.value = '';
		window.setDxpedFilter('all');
	};

	if (searchInput) {
		searchInput.addEventListener('input', applyDxpedFilters);
	}
})();
</script>
