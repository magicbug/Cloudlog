<?php
if ($user_hamsat_workable_only && $user_hamsat_key == '') { ?>
	<div class="card hamsat-card">
		<div class="card-body text-center py-5">
			<div class="text-warning mb-2" style="font-size: 2rem;"><i class="fas fa-key"></i></div>
			<h2 class="h5 fw-semibold mb-1">Hamsat Private Feed Key Required</h2>
			<p class="text-muted mb-3">You have enabled &ldquo;Workable passes only&rdquo;, which requires your private Hamsat feed key.</p>
			<a href="<?php echo site_url('user/edit/' . $this->session->userdata('user_id')); ?>" class="btn btn-sm btn-primary">
				<i class="fas fa-cog me-1"></i>Configure in Account Settings
			</a>
		</div>
	</div>
<?php
	return;
} elseif ($error) { ?>
	<div class="card hamsat-card">
		<div class="card-body text-center py-5">
			<div class="text-danger mb-2" style="font-size: 2rem;"><i class="fas fa-exclamation-triangle"></i></div>
			<h2 class="h5 fw-semibold mb-1">Connection Error</h2>
			<p class="text-muted mb-0"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
		</div>
	</div>
<?php
	return;
} elseif ($rovedata === null || !isset($rovedata['data'])) { ?>
	<div class="card hamsat-card">
		<div class="card-body text-center py-5">
			<div class="text-danger mb-2" style="font-size: 2rem;"><i class="fas fa-satellite-dish"></i></div>
			<h2 class="h5 fw-semibold mb-1">Data Error</h2>
			<p class="text-muted mb-0">Unable to retrieve satellite rover data from hams.at. Please try refreshing or check back later.</p>
		</div>
	</div>
<?php
	return;
}

$CI = &get_instance();
$CI->load->model('logbooks_model');
$CI->load->model('logbook_model');
$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));

if ($this->session->userdata('user_date_format')) {
	$custom_date_format = $this->session->userdata('user_date_format');
} else {
	$custom_date_format = $this->config->item('qso_date_format');
}

$now_ts = time();
$rows = array();
$live_count = 0;
$needed_grid_count = 0;
$workable_count = 0;
$has_key = ($user_hamsat_key != '');

foreach ($rovedata['data'] as $rove) {
	if ($user_hamsat_workable_only && empty($rove['is_workable'])) {
		continue;
	}

	$aos_ts = strtotime($rove['aos_at']);
	$los_ts = strtotime($rove['los_at']);
	$is_live = ($aos_ts !== false && $los_ts !== false && $now_ts >= $aos_ts && $now_ts <= $los_ts);

	$timing_label = '';
	$timing_class = 'is-upcoming';
	if ($is_live) {
		$mins_left = max(1, (int)ceil(($los_ts - $now_ts) / 60));
		$timing_label = 'Live · ' . $mins_left . 'm left';
		$timing_class = 'is-live';
		$live_count++;
	} elseif ($aos_ts !== false && $aos_ts > $now_ts) {
		$diff_mins = (int)floor(($aos_ts - $now_ts) / 60);
		if ($diff_mins < 60) {
			$timing_label = 'In ' . max(1, $diff_mins) . 'm';
			$timing_class = 'is-soon';
		} elseif ($diff_mins < 1440) {
			$hrs = (int)floor($diff_mins / 60);
			$rem = $diff_mins % 60;
			$timing_label = 'In ' . $hrs . 'h' . ($rem > 0 ? ' ' . $rem . 'm' : '');
			if ($hrs < 3) $timing_class = 'is-soon';
		} else {
			$days = (int)floor($diff_mins / 1440);
			$timing_label = $days === 1 ? 'Tomorrow' : 'In ' . $days . 'd';
		}
	}

	$call_raw = (string)($rove['callsign'] ?? '');
	$call_worked = $CI->logbook_model->check_if_callsign_worked_in_logbook($call_raw, $logbooks_locations_array, "SAT") != 0;

	$grids_info = array();
	$has_needed_grid = false;
	$grids_list = isset($rove['grids']) && is_array($rove['grids']) ? $rove['grids'] : array();
	foreach ($grids_list as $grid) {
		$g_worked = $CI->logbook_model->check_if_grid_4char_worked_in_logbook($grid, null, "SAT") != 0;
		if (!$g_worked) {
			$has_needed_grid = true;
		}
		$grids_info[] = array('grid' => strtoupper($grid), 'worked' => $g_worked);
	}
	if ($has_needed_grid) {
		$needed_grid_count++;
	}

	$is_workable = !empty($rove['is_workable']);
	if ($has_key && $is_workable) {
		$workable_count++;
	}

	$mode_raw = (string)($rove['mode'] ?? '');
	$mode_cat = 'other';
	$mode_badge_class = 'hamsat-mode-other';
	if ($mode_raw === 'SSB' || $mode_raw === 'CW') {
		$mode_cat = 'linear';
		$mode_badge_class = 'hamsat-mode-linear';
	} elseif ($mode_raw === 'FM') {
		$mode_cat = 'fm';
		$mode_badge_class = 'hamsat-mode-fm';
	} elseif ($mode_raw === 'Data') {
		$mode_cat = 'data';
		$mode_badge_class = 'hamsat-mode-data';
	}

	$sat_name = (string)($rove['satellite']['name'] ?? '');
	$sked_sat = strtoupper($sat_name) === 'GREENCUBE' ? 'IO-117' : $sat_name;

	$direction = '';
	if (($rove['mhz_direction'] ?? '') === 'up') {
		$direction = '&uarr;';
	} elseif (($rove['mhz_direction'] ?? '') === 'down') {
		$direction = '&darr;';
	}
	$freq_label = !empty($rove['mhz']) ? sprintf('%.3f', $rove['mhz']) . ' MHz' . ($direction ? ' ' . $direction : '') : '';

	$rows[] = array(
		'rove'             => $rove,
		'aos_ts'           => $aos_ts,
		'los_ts'           => $los_ts,
		'is_live'          => $is_live,
		'timing_label'     => $timing_label,
		'timing_class'     => $timing_class,
		'call'             => $call_raw,
		'call_worked'      => $call_worked,
		'grids'            => $grids_info,
		'has_needed_grid'  => $has_needed_grid,
		'is_workable'      => $is_workable,
		'mode'             => $mode_raw,
		'mode_cat'         => $mode_cat,
		'mode_badge_class' => $mode_badge_class,
		'sat_name'         => $sat_name,
		'sked_sat'         => $sked_sat,
		'freq_label'       => $freq_label,
		'search'           => strtolower($call_raw . ' ' . $sat_name . ' ' . $mode_raw . ' ' . implode(' ', $grids_list) . ' ' . ($rove['comment'] ?? '')),
	);
}

$total_count = count($rows);
?>

<?php if ($total_count === 0) { ?>
	<div class="card hamsat-card">
		<div class="card-body text-center py-5">
			<div class="text-primary mb-2" style="font-size: 2rem;"><i class="fas fa-satellite"></i></div>
			<h2 class="h5 fw-semibold mb-1"><?php echo lang('hams_at_no_activations_found'); ?></h2>
			<p class="text-muted small mb-0">
				<?php echo $user_hamsat_workable_only ? 'No workable satellite rover passes match your station right now.' : 'No upcoming satellite rover activations are currently listed on hams.at.'; ?>
			</p>
		</div>
	</div>
<?php return; } ?>

<?php if (!$has_key) { ?>
	<div class="alert alert-info py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 small">
		<span><i class="fas fa-info-circle me-1"></i>Add your <strong>hams.at private feed key</strong> in Account settings to unlock pass workability windows for your gridsquare.</span>
		<a href="<?php echo site_url('user/edit/' . $this->session->userdata('user_id')); ?>" class="btn btn-sm btn-outline-primary py-0 px-2">Configure Key</a>
	</div>
<?php } ?>

<!-- KPI Summary Strip -->
<div class="row g-3 mb-3">
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setHamsatFilter('live')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label d-flex align-items-center gap-2">
					<span class="dxped-live-dot"></span>Live Now
				</div>
				<div class="dxped-kpi-value text-success mt-1"><?php echo number_format($live_count); ?></div>
				<div class="small text-muted mt-1">Passes active right now</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setHamsatFilter('needed')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label">Needed SAT Grids</div>
				<div class="dxped-kpi-value text-danger mt-1"><?php echo number_format($needed_grid_count); ?></div>
				<div class="small text-muted mt-1">Activations with unworked grids</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setHamsatFilter('<?php echo $has_key ? 'workable' : 'all'; ?>')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label"><?php echo $has_key ? 'Workable Passes' : 'Feed Mode'; ?></div>
				<div class="dxped-kpi-value text-primary mt-1"><?php echo $has_key ? number_format($workable_count) : 'Public'; ?></div>
				<div class="small text-muted mt-1"><?php echo $has_key ? 'Reachable from ' . htmlspecialchars($gridsquare, ENT_QUOTES, 'UTF-8') : 'Set key for workability'; ?></div>
			</div>
		</div>
	</div>
	<div class="col-6 col-lg-3">
		<div class="card dxped-kpi-card h-100" role="button" onclick="setHamsatFilter('all')">
			<div class="card-body py-3 px-3">
				<div class="dxped-kpi-label">Total Activations</div>
				<div class="dxped-kpi-value mt-1"><?php echo number_format($total_count); ?></div>
				<div class="small text-muted mt-1">Auto-refreshes every 60s</div>
			</div>
		</div>
	</div>
</div>

<!-- Instant Filter & Search Bar -->
<div class="card dxped-filter-card mb-3">
	<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
		<div class="d-flex flex-wrap align-items-center gap-1" id="hamsatFilterPills">
			<button type="button" class="btn btn-sm dxped-pill is-active" data-hfilter="all" onclick="setHamsatFilter('all')">
				All <span class="ms-1 opacity-75"><?php echo $total_count; ?></span>
			</button>
			<button type="button" class="btn btn-sm dxped-pill" data-hfilter="live" onclick="setHamsatFilter('live')">
				<span class="dxped-live-dot me-1"></span>Live Now <span class="ms-1 opacity-75"><?php echo $live_count; ?></span>
			</button>
			<button type="button" class="btn btn-sm dxped-pill" data-hfilter="needed" onclick="setHamsatFilter('needed')">
				<i class="fas fa-star text-danger me-1"></i>Needed Grid <span class="ms-1 opacity-75"><?php echo $needed_grid_count; ?></span>
			</button>
			<?php if ($has_key) { ?>
			<button type="button" class="btn btn-sm dxped-pill" data-hfilter="workable" onclick="setHamsatFilter('workable')">
				<i class="fas fa-check-circle text-success me-1"></i>Workable <span class="ms-1 opacity-75"><?php echo $workable_count; ?></span>
			</button>
			<?php } ?>
			<button type="button" class="btn btn-sm dxped-pill" data-hfilter="linear" onclick="setHamsatFilter('linear')">SSB / CW</button>
			<button type="button" class="btn btn-sm dxped-pill" data-hfilter="fm" onclick="setHamsatFilter('fm')">FM</button>
			<button type="button" class="btn btn-sm dxped-pill" data-hfilter="data" onclick="setHamsatFilter('data')">Data</button>
		</div>

		<div class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0">
			<div class="input-group input-group-sm" style="min-width: 220px;">
				<span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
				<input type="search" id="hamsatSearchInput" class="form-control" placeholder="Search callsign, sat, grid...">
			</div>
			<span class="small text-muted text-nowrap d-none d-sm-inline" id="hamsatMatchCount"><?php echo $total_count; ?> shown</span>
		</div>
	</div>
</div>

<!-- Activations Table Card -->
<div class="card dxped-month-card">
	<div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
		<div class="d-flex align-items-center gap-2">
			<i class="fas fa-satellite text-primary"></i>
			<span class="fw-semibold">Upcoming Satellite Rovers</span>
			<span class="badge rounded-pill dxped-month-count"><?php echo $total_count; ?></span>
		</div>
		<div class="small text-muted">
			Source: <a target="_blank" href="https://hams.at/">hams.at</a> &middot; <?php echo $user_hamsat_workable_only ? 'Workable passes only' : 'All passes'; ?>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-hover align-middle mb-0 dxped-table">
			<thead>
				<tr>
					<th class="ps-3" style="width: 175px;">Pass Window (UTC)</th>
					<th style="width: 135px;">Callsign</th>
					<th style="width: 155px;">Satellite &amp; Mode</th>
					<th style="width: 160px;">Gridsquare(s)</th>
					<th style="width: 125px;">Workable</th>
					<th>Comment</th>
					<th class="text-end pe-3" style="width: 125px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($rows as $item) {
					$rove = $item['rove'];
					$row_classes = array('dxped-row');
					if ($item['is_live']) $row_classes[] = 'is-live-row';
					elseif ($item['has_needed_grid']) $row_classes[] = 'is-needed-row';
					$call_esc = htmlspecialchars($item['call'], ENT_QUOTES, 'UTF-8');
				?>
				<tr class="<?php echo implode(' ', $row_classes); ?>"
					data-hamsat-row
					data-live="<?php echo $item['is_live'] ? '1' : '0'; ?>"
					data-needed="<?php echo $item['has_needed_grid'] ? '1' : '0'; ?>"
					data-workable="<?php echo $item['is_workable'] ? '1' : '0'; ?>"
					data-mode="<?php echo htmlspecialchars($item['mode_cat'], ENT_QUOTES, 'UTF-8'); ?>"
					data-search="<?php echo htmlspecialchars($item['search'], ENT_QUOTES, 'UTF-8'); ?>">
					<td class="ps-3 text-nowrap">
						<div class="small fw-semibold font-monospace">
							<?php echo date($custom_date_format, $item['aos_ts']); ?> &middot; <?php echo date('H:i', $item['aos_ts']); ?>&ndash;<?php echo date('H:i', $item['los_ts']); ?>
						</div>
						<?php if ($item['timing_label'] !== '') { ?>
							<span class="dxped-timing-tag <?php echo $item['timing_class']; ?>">
								<?php if ($item['is_live']) { ?><span class="dxped-live-dot me-1"></span><?php } ?>
								<?php echo htmlspecialchars($item['timing_label'], ENT_QUOTES, 'UTF-8'); ?>
							</span>
						<?php } ?>
					</td>
					<td>
						<a href="https://www.qrz.com/db/<?php echo rawurlencode($item['call']); ?>" target="_blank" class="dxped-call-chip text-decoration-none <?php echo $item['call_worked'] ? 'is-worked-call' : ''; ?>" title="<?php echo $item['call_worked'] ? 'Worked on SAT before · Lookup on QRZ' : 'Not worked on SAT yet · Lookup on QRZ'; ?>">
							<?php echo $call_esc; ?>
						</a>
					</td>
					<td class="text-nowrap">
						<div class="d-flex align-items-center gap-1">
							<span class="fw-semibold"><?php echo htmlspecialchars($item['sat_name'], ENT_QUOTES, 'UTF-8'); ?></span>
							<?php if ($item['mode'] !== '') { ?>
								<span class="badge <?php echo $item['mode_badge_class']; ?>"><?php echo htmlspecialchars($item['mode'], ENT_QUOTES, 'UTF-8'); ?></span>
							<?php } ?>
						</div>
						<?php if ($item['freq_label'] !== '') { ?>
							<div class="small text-muted font-monospace" style="font-size: 0.72rem;"><?php echo $item['freq_label']; ?></div>
						<?php } ?>
					</td>
					<td>
						<div class="d-flex flex-wrap gap-1">
							<?php foreach ($item['grids'] as $g) { ?>
								<span class="badge font-monospace <?php echo $g['worked'] ? 'dxped-status-worked' : 'dxped-status-needed'; ?>" title="<?php echo $g['worked'] ? 'Worked on SAT' : 'Needed SAT Grid'; ?>">
									<?php echo htmlspecialchars($g['grid'], ENT_QUOTES, 'UTF-8'); ?>
								</span>
							<?php } ?>
						</div>
					</td>
					<td class="text-nowrap">
						<?php if ($has_key) {
							if ($item['is_workable']) { ?>
								<span class="badge dxped-status-worked font-monospace">
									<?php echo date('H:i', strtotime($rove['workable_start_at'])) . '&ndash;' . date('H:i', strtotime($rove['workable_end_at'])); ?>
								</span>
							<?php } else { ?>
								<span class="badge dxped-status-needed">No</span>
							<?php }
						} else { ?>
							<span class="badge text-bg-secondary opacity-75">Unknown</span>
						<?php } ?>
					</td>
					<td>
						<div class="small text-muted dxped-notes-cell"><?php echo xss_clean($rove['comment'] ?? '&mdash;'); ?></div>
					</td>
					<td class="text-end pe-3 text-nowrap">
						<a href="<?php echo htmlspecialchars($rove['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn btn-sm btn-outline-secondary dxped-act-btn" title="Track pass on hams.at">
							<i class="fas fa-crosshairs me-1"></i>Track
						</a>
						<?php if (($item['is_workable'] || !$has_key) && !empty($item['grids'][0]['grid'])) { ?>
							<a href="https://sat.fg8oj.com/sked.php?s%5B%5D=<?php echo rawurlencode($item['sked_sat']); ?>&l=<?php echo rawurlencode(strtoupper($gridsquare)); ?>&el1=0&l2=<?php echo rawurlencode($item['grids'][0]['grid']); ?>&el2=0&duration=1&start=0&OK=Search" target="_blank" class="btn btn-sm btn-outline-secondary dxped-act-btn" title="Check mutual pass window on FG8OJ Sked">
								<i class="fas fa-calendar-check"></i>
							</a>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<!-- Empty Filter State -->
<div class="card hamsat-card d-none mt-3" id="hamsatEmptyState">
	<div class="card-body text-center py-5">
		<div class="text-muted mb-2" style="font-size: 1.8rem;"><i class="fas fa-filter"></i></div>
		<h2 class="h6 fw-semibold mb-1">No matching satellite passes</h2>
		<p class="small text-muted mb-3">Try switching filter pills or clearing your search query.</p>
		<button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetHamsatFilters()">Reset Filters</button>
	</div>
</div>

<script>
(function() {
	var activeFilter = 'all';
	var searchInput = document.getElementById('hamsatSearchInput');
	var matchCountEl = document.getElementById('hamsatMatchCount');
	var emptyEl = document.getElementById('hamsatEmptyState');

	function applyHamsatFilters() {
		var q = (searchInput ? searchInput.value : '').trim().toLowerCase();
		var shown = 0;

		document.querySelectorAll('[data-hamsat-row]').forEach(function(row) {
			var okFilter = true;
			if (activeFilter === 'live') okFilter = row.getAttribute('data-live') === '1';
			else if (activeFilter === 'needed') okFilter = row.getAttribute('data-needed') === '1';
			else if (activeFilter === 'workable') okFilter = row.getAttribute('data-workable') === '1';
			else if (activeFilter === 'linear' || activeFilter === 'fm' || activeFilter === 'data') {
				okFilter = row.getAttribute('data-mode') === activeFilter;
			}

			var okSearch = !q || (row.getAttribute('data-search') || '').indexOf(q) !== -1;
			var visible = okFilter && okSearch;
			row.style.display = visible ? '' : 'none';
			if (visible) shown++;
		});

		if (matchCountEl) matchCountEl.textContent = shown + ' shown';
		if (emptyEl) emptyEl.classList.toggle('d-none', shown > 0);
	}

	window.setHamsatFilter = function(filter) {
		activeFilter = filter || 'all';
		document.querySelectorAll('#hamsatFilterPills [data-hfilter]').forEach(function(btn) {
			btn.classList.toggle('is-active', btn.getAttribute('data-hfilter') === activeFilter);
		});
		applyHamsatFilters();
	};

	window.resetHamsatFilters = function() {
		if (searchInput) searchInput.value = '';
		window.setHamsatFilter('all');
	};

	if (searchInput) {
		searchInput.addEventListener('input', applyHamsatFilters);
	}
})();
</script>
