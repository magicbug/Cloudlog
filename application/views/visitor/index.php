<?php
function visitor_display_callsign($call) {
	return str_replace('0', '&Oslash;', htmlspecialchars(strtoupper((string) $call), ENT_QUOTES, 'UTF-8'));
}

function visitor_mode_category($mode, $sat_name) {
	if (!empty($sat_name)) {
		return 'sat';
	}
	$m = strtoupper(trim((string) $mode));
	if ($m === 'CW') {
		return 'cw';
	}
	if (in_array($m, array('SSB', 'USB', 'LSB', 'FM', 'AM', 'PHONE', 'DIGITALVOICE', 'C4FM', 'DSTAR', 'DMR'), true)) {
		return 'ssb';
	}
	return 'digi';
}

$brand_name = isset($brand_name) ? $brand_name : '';
$public_name = isset($public_name) ? trim((string) $public_name) : '';
$station_callsigns = isset($station_callsigns) && is_array($station_callsigns) ? $station_callsigns : array();
$slug = isset($slug) ? $slug : '';
$date_format = isset($date_format) && $date_format !== '' ? $date_format : 'Y-m-d';
$on_air = !empty($public_radio_status) && isset($radio_status) && is_object($radio_status) && method_exists($radio_status, 'num_rows') && $radio_status->num_rows();
$on_air_detail = '';
if ($on_air) {
	$first_radio = $radio_status->row();
	if ($first_radio) {
		$parts = array();
		if (!empty($first_radio->frequency) && is_numeric($first_radio->frequency)) {
			$parts[] = number_format(((float) $first_radio->frequency) / 1000000, 3) . ' MHz';
		}
		if (!empty($first_radio->mode)) {
			$parts[] = strtoupper($first_radio->mode);
		}
		$on_air_detail = implode(' ', $parts);
	}
}
$public_search_enabled = !empty($public_search_enabled);
$oqrs_enabled = !empty($oqrs_enabled);
$show_public_subtitle = ($public_name !== '' && strtoupper($public_name) !== strtoupper($brand_name));

$total_qsos_int = (int) $total_qsos;
$year_qsos_int = (int) $year_qsos;
$month_qsos_int = (int) $month_qsos;
$todays_qsos_int = (int) $todays_qsos;
$worked_dxcc = (int) $total_countries;
$conf_paper = (int) $total_countries_confirmed_paper;
$conf_eqsl = (int) $total_countries_confirmed_eqsl;
$conf_lotw = (int) $total_countries_confirmed_lotw;
$conf_any = isset($total_countries_confirmed_any) ? (int) $total_countries_confirmed_any : max($conf_paper, $conf_eqsl, $conf_lotw);
$dxcc_pct = min(100, (int) round(($worked_dxcc / 340) * 100));
$lotw_pct = $worked_dxcc > 0 ? min(100, (int) round(($conf_lotw / $worked_dxcc) * 100)) : 0;
$eqsl_pct = $worked_dxcc > 0 ? min(100, (int) round(($conf_eqsl / $worked_dxcc) * 100)) : 0;
$paper_pct = $worked_dxcc > 0 ? min(100, (int) round(($conf_paper / $worked_dxcc) * 100)) : 0;

$top_bands = isset($top_bands) && is_array($top_bands) ? $top_bands : array();
$active_bands_count = isset($active_bands_count) ? (int) $active_bands_count : count($top_bands);
$max_band_cnt = !empty($top_bands) ? max(1, (int) $top_bands[0]['cnt']) : 1;

$page_offset = isset($page_offset) ? (int) $page_offset : 0;
$per_page = isset($per_page) && $per_page > 0 ? (int) $per_page : 25;
$rows_count = (!empty($results) && is_object($results)) ? $results->num_rows() : 0;
$range_start = $rows_count > 0 ? ($page_offset + 1) : 0;
$range_end = $page_offset + $rows_count;
?>
<noscript><style> #map { display: none } </style></noscript>
<div class="container visitor-page">
	<!-- Flagship Showcase Card (Hero + Interactive Map) -->
	<div class="visitor-showcase">
		<div class="visitor-hero">
			<div class="visitor-hero-identity">
				<div class="visitor-kicker-row">
					<span class="visitor-kicker"><?php echo lang('visitor_public_logbook'); ?></span>
					<?php if ($on_air) { ?>
						<span class="visitor-onair-pill">
							<span class="visitor-live-dot"></span>
							<span><?php echo lang('visitor_on_air'); ?><?php echo $on_air_detail !== '' ? ' · ' . htmlspecialchars($on_air_detail, ENT_QUOTES, 'UTF-8') : ''; ?></span>
						</span>
					<?php } ?>
				</div>
				<h1 class="visitor-callsign"><?php echo visitor_display_callsign($brand_name); ?></h1>
				<?php if ($show_public_subtitle || count($station_callsigns) > 1) { ?>
					<div class="visitor-sub-row">
						<?php if ($show_public_subtitle) { ?>
							<span class="visitor-logbook-name"><?php echo htmlspecialchars($public_name, ENT_QUOTES, 'UTF-8'); ?></span>
						<?php } ?>
						<?php if (count($station_callsigns) > 1) { ?>
							<div class="d-flex flex-wrap gap-1">
								<?php foreach ($station_callsigns as $cs) { ?>
									<span class="visitor-call-tag"><?php echo visitor_display_callsign($cs); ?></span>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>

			<?php if ($public_search_enabled) { ?>
			<form method="post" name="searchForm" action="<?php echo site_url('visitor/search'); ?>" onsubmit="return validateForm()" class="visitor-find-form">
				<label class="visitor-find-label" for="searchcall"><i class="fas fa-search me-1"></i><?php echo lang('visitor_find_in_log'); ?></label>
				<p class="visitor-find-hint"><?php echo lang('visitor_find_in_log_hint'); ?></p>
				<div class="input-group input-group-sm visitor-find-group">
					<input class="form-control visitor-find-input" id="searchcall" type="search" name="callsign" placeholder="<?php echo lang('visitor_search_callsign'); ?>" aria-label="<?php echo lang('visitor_search_callsign'); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo lang('visitor_search_callsign'); ?>">
					<input type="hidden" name="public_slug" value="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">
					<button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i><?php echo lang('menu_search_button'); ?></button>
				</div>
			</form>
			<?php } ?>
		</div>

		<div class="visitor-map-card position-relative">
			<div id="map" class="map-leaflet visitor-map" style="width: 100%; height: 340px"></div>
		</div>
	</div>

	<!-- 4 High-Impact KPI Cards -->
	<div class="row g-2 mb-3">
		<div class="col-6 col-lg-3">
			<div class="card visitor-kpi-card h-100">
				<div class="card-body py-2 px-3">
					<div class="visitor-kpi-label"><?php echo lang('general_word_total'); ?> QSOs</div>
					<div class="visitor-kpi-val"><?php echo number_format($total_qsos_int); ?></div>
					<div class="visitor-kpi-sub"><?php echo number_format($todays_qsos_int); ?> today · <?php echo number_format($month_qsos_int); ?> this month</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-lg-3">
			<div class="card visitor-kpi-card h-100">
				<div class="card-body py-2 px-3">
					<div class="visitor-kpi-label"><?php echo lang('general_word_year'); ?></div>
					<div class="visitor-kpi-val text-primary"><?php echo number_format($year_qsos_int); ?></div>
					<div class="visitor-kpi-sub"><?php echo $active_bands_count > 0 ? ('Active across ' . $active_bands_count . ' band' . ($active_bands_count === 1 ? '' : 's')) : 'QSOs this year'; ?></div>
				</div>
			</div>
		</div>
		<div class="col-6 col-lg-3">
			<div class="card visitor-kpi-card h-100">
				<div class="card-body py-2 px-3">
					<div class="visitor-kpi-label">DXCC <?php echo lang('general_word_worked'); ?></div>
					<div class="visitor-kpi-val text-success"><?php echo number_format($worked_dxcc); ?></div>
					<div class="visitor-kpi-sub"><?php echo $dxcc_pct; ?>% of 340 current entities</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-lg-3">
			<div class="card visitor-kpi-card h-100">
				<div class="card-body py-2 px-3">
					<div class="visitor-kpi-label">DXCC <?php echo lang('general_word_confirmed'); ?></div>
					<div class="visitor-kpi-val text-warning"><?php echo number_format($conf_any); ?></div>
					<div class="visitor-kpi-sub">LoTW <?php echo $conf_lotw; ?> · eQSL <?php echo $conf_eqsl; ?> · Paper <?php echo $conf_paper; ?></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Main 2-Column Content -->
	<div class="row g-3 logdata">
		<!-- Left: Recent Contacts Card -->
		<div class="col-lg-8">
			<div class="card visitor-card">
				<div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
					<span class="fw-semibold d-inline-flex align-items-center gap-2">
						<i class="fas fa-list-ul text-primary"></i>
						<span><?php echo lang('visitor_recent_contacts'); ?></span>
					</span>
					<div class="d-flex flex-wrap gap-1" id="visitorModePills" role="group" aria-label="Filter visible contacts by mode">
						<button type="button" class="visitor-filter-pill is-active" data-vfilter="all">All</button>
						<button type="button" class="visitor-filter-pill" data-vfilter="ssb">SSB</button>
						<button type="button" class="visitor-filter-pill" data-vfilter="cw">CW</button>
						<button type="button" class="visitor-filter-pill" data-vfilter="digi">Digital</button>
						<button type="button" class="visitor-filter-pill" data-vfilter="sat">SAT</button>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0 visitor-log-table">
						<thead>
							<tr>
								<th>Date / UTC</th>
								<th><?php echo lang('gen_hamradio_call'); ?></th>
								<th><?php echo lang('general_word_country'); ?></th>
								<th><?php echo lang('gen_hamradio_mode'); ?></th>
								<th class="text-end"><?php echo lang('gen_hamradio_band'); ?></th>
							</tr>
						</thead>
						<tbody id="visitorLogBody">
						<?php
						if ($rows_count > 0) {
							$ci = &get_instance();
							$ci->load->library('DxccFlag');
							foreach ($results->result() as $row) {
								$timestamp = strtotime($row->COL_TIME_ON);
								$flag = strtolower($ci->dxccflag->getISO($row->COL_DXCC));
								$country = !empty($row->name) ? $row->name : $row->COL_COUNTRY;
								$country_label = ucwords(strtolower($country == null ? '- NONE -' : $country));
								$mode = $row->COL_SUBMODE == null ? $row->COL_MODE : $row->COL_SUBMODE;
								$mode_cat = visitor_mode_category($mode, $row->COL_SAT_NAME);
								$mode_badge_class = 'visitor-mode-' . $mode_cat;
						?>
								<tr data-vcat="<?php echo $mode_cat; ?>">
									<td class="text-nowrap font-monospace small">
										<span><?php echo date($date_format, $timestamp); ?></span>
										<span class="text-muted ms-1"><?php echo date('H:i', $timestamp); ?></span>
									</td>
									<td class="text-nowrap">
										<span class="visitor-call-chip"><?php echo visitor_display_callsign($row->COL_CALL); ?></span>
									</td>
									<td class="text-nowrap">
										<span class="fi fi-<?php echo htmlspecialchars($flag, ENT_QUOTES, 'UTF-8'); ?> me-1" title="<?php echo htmlspecialchars($country_label, ENT_QUOTES, 'UTF-8'); ?>"></span>
										<span class="small"><?php echo htmlspecialchars($country_label, ENT_QUOTES, 'UTF-8'); ?></span>
									</td>
									<td class="text-nowrap">
										<span class="visitor-mode-badge <?php echo $mode_badge_class; ?>"><?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?></span>
									</td>
									<td class="text-end text-nowrap">
										<?php if ($row->COL_SAT_NAME != null) { ?>
											<a class="visitor-band-badge is-sat text-decoration-none" href="https://db.satnogs.org/search/?q=<?php echo urlencode($row->COL_SAT_NAME); ?>" target="_blank" rel="noopener">
												<i class="fas fa-satellite me-1"></i><?php echo htmlspecialchars($row->COL_SAT_NAME, ENT_QUOTES, 'UTF-8'); ?>
											</a>
										<?php } else { ?>
											<span class="visitor-band-badge"><?php echo htmlspecialchars(strtolower($row->COL_BAND), ENT_QUOTES, 'UTF-8'); ?></span>
										<?php } ?>
									</td>
								</tr>
						<?php
							}
						} else {
						?>
							<tr>
								<td colspan="5" class="text-center text-muted py-4">No contacts logged yet.</td>
							</tr>
						<?php } ?>
							<tr id="visitorFilterEmpty" style="display:none;">
								<td colspan="5" class="text-center text-muted py-4">No contacts on this page match the selected filter.</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="card-footer d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3 visitor-table-footer">
					<small class="text-muted">
						<?php if ($rows_count > 0) { ?>
							Showing <?php echo number_format($range_start); ?>–<?php echo number_format($range_end); ?> of <?php echo number_format($total_qsos_int); ?> QSOs
						<?php } else { ?>
							0 QSOs
						<?php } ?>
					</small>
					<div class="pagination-links visitor-pagination">
						<?php echo $this->pagination->create_links(); ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Right: Station Breakdown & Explore Sidebar -->
		<div class="col-lg-4">
			<?php if (!empty($public_radio_status)) { ?>
			<div id="radio_display" class="mb-3" hx-get="<?php echo site_url('visitor/radio_display_component/'.$slug); ?>" hx-trigger="load, every 30s"></div>
			<?php } ?>

			<!-- DXCC Confirmations Breakdown -->
			<div class="card visitor-card mb-3">
				<div class="card-header py-2 px-3 fw-semibold">
					<i class="fas fa-globe-europe text-success me-1"></i> <?php echo lang('dashboard_countries_breakdown'); ?>
				</div>
				<div class="card-body py-3 px-3">
					<div class="visitor-bar-row">
						<div class="d-flex justify-content-between small mb-1">
							<span>LoTW Confirmed</span>
							<strong><?php echo $conf_lotw; ?> / <?php echo $worked_dxcc; ?></strong>
						</div>
						<div class="visitor-bar-track">
							<div class="visitor-bar-fill bg-primary" style="width: <?php echo $lotw_pct; ?>%"></div>
						</div>
					</div>
					<div class="visitor-bar-row">
						<div class="d-flex justify-content-between small mb-1">
							<span>eQSL Confirmed</span>
							<strong><?php echo $conf_eqsl; ?> / <?php echo $worked_dxcc; ?></strong>
						</div>
						<div class="visitor-bar-track">
							<div class="visitor-bar-fill bg-info" style="width: <?php echo $eqsl_pct; ?>%"></div>
						</div>
					</div>
					<div class="visitor-bar-row mb-0">
						<div class="d-flex justify-content-between small mb-1">
							<span>Paper QSL Confirmed</span>
							<strong><?php echo $conf_paper; ?> / <?php echo $worked_dxcc; ?></strong>
						</div>
						<div class="visitor-bar-track">
							<div class="visitor-bar-fill bg-success" style="width: <?php echo $paper_pct; ?>%"></div>
						</div>
					</div>
				</div>
			</div>

			<!-- Top Bands Snapshot -->
			<?php if (!empty($top_bands)) { ?>
			<div class="card visitor-card mb-3">
				<div class="card-header py-2 px-3 fw-semibold">
					<i class="fas fa-wave-square text-primary me-1"></i> Top Bands
				</div>
				<div class="card-body py-3 px-3">
					<?php foreach ($top_bands as $idx => $tb) {
						$b_pct = min(100, (int) round(((int) $tb['cnt'] / $max_band_cnt) * 100));
						$is_last = ($idx === count($top_bands) - 1);
					?>
						<div class="visitor-bar-row<?php echo $is_last ? ' mb-0' : ''; ?>">
							<div class="d-flex justify-content-between small mb-1">
								<strong class="font-monospace"><?php echo htmlspecialchars($tb['band'], ENT_QUOTES, 'UTF-8'); ?></strong>
								<span class="text-muted"><?php echo number_format((int) $tb['cnt']); ?> QSOs</span>
							</div>
							<div class="visitor-bar-track">
								<div class="visitor-bar-fill bg-primary" style="width: <?php echo $b_pct; ?>%"></div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>

			<!-- Explore Quick Links -->
			<?php if (!empty($slug)) { ?>
			<div class="card visitor-card">
				<div class="card-header py-2 px-3 fw-semibold">
					<i class="fas fa-compass text-warning me-1"></i> Explore
				</div>
				<div class="card-body py-2 px-3">
					<a href="<?php echo site_url('visitor/gridsquares/'.$slug); ?>" class="visitor-quick-link">
						<span><i class="fas fa-th me-2 text-primary"></i>Interactive Gridsquare Map</span>
						<i class="fas fa-chevron-right small text-muted"></i>
					</a>
					<?php if ($oqrs_enabled) { ?>
					<a href="<?php echo site_url('oqrs/'.$slug); ?>" class="visitor-quick-link mb-0">
						<span><i class="fas fa-envelope-open-text me-2 text-success"></i><?php echo lang('visitor_request_qsl'); ?> (OQRS)</span>
						<i class="fas fa-chevron-right small text-muted"></i>
					</a>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<div id="partial_view"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var pills = document.querySelectorAll('#visitorModePills .visitor-filter-pill');
	var rows = document.querySelectorAll('#visitorLogBody tr[data-vcat]');
	var emptyRow = document.getElementById('visitorFilterEmpty');
	if (!pills.length) return;

	pills.forEach(function(btn) {
		btn.addEventListener('click', function() {
			var filter = this.getAttribute('data-vfilter');
			pills.forEach(function(p) { p.classList.remove('is-active'); });
			this.classList.add('is-active');

			var shown = 0;
			rows.forEach(function(tr) {
				var match = (filter === 'all' || tr.getAttribute('data-vcat') === filter);
				tr.style.display = match ? '' : 'none';
				if (match) shown++;
			});
			if (emptyRow) {
				emptyRow.style.display = (rows.length > 0 && shown === 0) ? '' : 'none';
			}
		});
	});
});
</script>
