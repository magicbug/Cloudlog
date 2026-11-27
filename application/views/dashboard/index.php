<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$can_log = (($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE);
$dashboard_map_opt = $this->optionslib->get_option('dashboard_map');
$show_top_map = ($dashboard_map_opt != "false" && $dashboard_map_opt != "map_at_right");
$show_right_map = ($dashboard_map_opt == "map_at_right");
$show_banner = ($this->optionslib->get_option('dashboard_banner') != "false");

$station_callsign = !empty($active_station_profile->station_callsign)
	? strtoupper($active_station_profile->station_callsign)
	: strtoupper((string) $this->session->userdata('user_callsign'));
$station_name = !empty($active_station_profile->station_profile_name)
	? $active_station_profile->station_profile_name
	: '';
$station_grid = !empty($active_station_profile->station_gridsquare)
	? strtoupper($active_station_profile->station_gridsquare)
	: strtoupper((string) $this->session->userdata('user_locator'));
$logbook_label = (!empty($active_logbook_name) && $active_logbook_name !== 'n/a') ? $active_logbook_name : '';

$dxcc_total = !empty($total_dxcc_count) ? (int) $total_dxcc_count : max(1, (int) $total_countries + (int) $total_countries_needed);
$dxcc_current = isset($current_countries) ? (int) $current_countries : (int) $total_countries;
$dxcc_pct = min(100, round(($dxcc_current / max(1, $dxcc_total)) * 100, 1));

$qsl_services = [];
if ($can_log) {
	if (!empty($dashboard_lotw_card) && ($total_lotw_sent != 0 || $total_lotw_rcvd != 0)) {
		$qsl_services[] = [
			'label' => lang('general_word_lotw'),
			'icon' => 'fas fa-certificate',
			'sent' => (int) $total_lotw_sent,
			'rcvd' => (int) $total_lotw_rcvd,
			'sent_today' => (int) $lotw_sent_today,
			'rcvd_today' => (int) $lotw_rcvd_today,
			'sent_fn' => "javascript:displayContacts('','all','all','LOTWSDATE','');",
			'rcvd_fn' => "javascript:displayContacts('','all','all','LOTWRDATE','');",
			'requested' => null,
			'requested_today' => null,
		];
	}
	if ($total_qrz_sent != 0 || $total_qrz_rcvd != 0) {
		$qsl_services[] = [
			'label' => 'QRZ.com',
			'icon' => 'fas fa-address-book',
			'sent' => (int) $total_qrz_sent,
			'rcvd' => (int) $total_qrz_rcvd,
			'sent_today' => (int) $qrz_sent_today,
			'rcvd_today' => (int) $qrz_rcvd_today,
			'sent_fn' => "javascript:displayContacts('','all','all','QRZSDATE','');",
			'rcvd_fn' => "javascript:displayContacts('','all','all','QRZRDATE','');",
			'requested' => null,
			'requested_today' => null,
		];
	}
	if (!empty($dashboard_eqslcard_card) && ($total_eqsl_sent != 0 || $total_eqsl_rcvd != 0)) {
		$qsl_services[] = [
			'label' => lang('general_word_eqslcards'),
			'icon' => 'fas fa-at',
			'sent' => (int) $total_eqsl_sent,
			'rcvd' => (int) $total_eqsl_rcvd,
			'sent_today' => (int) $eqsl_sent_today,
			'rcvd_today' => (int) $eqsl_rcvd_today,
			'sent_fn' => "javascript:displayContacts('','All','All','EQSLSDATE','');",
			'rcvd_fn' => "javascript:displayContacts('','All','All','EQSLRDATE','');",
			'requested' => null,
			'requested_today' => null,
		];
	}
	if (!empty($dashboard_qslcard_card) && ($total_qsl_sent != 0 || $total_qsl_rcvd != 0 || $total_qsl_requested != 0)) {
		$qsl_services[] = [
			'label' => lang('general_word_qslcards'),
			'icon' => 'fas fa-envelope',
			'sent' => (int) $total_qsl_sent,
			'rcvd' => (int) $total_qsl_rcvd,
			'sent_today' => (int) $qsl_sent_today,
			'rcvd_today' => (int) $qsl_rcvd_today,
			'sent_fn' => "javascript:displayContacts('','All','All','QSLSDATE','');",
			'rcvd_fn' => "javascript:displayContacts('','All','All','QSLRDATE','');",
			'requested' => (int) $total_qsl_requested,
			'requested_today' => (int) $qsl_requested_today,
		];
	}
}

$show_vucc_kpi = ($can_log && !empty($dashboard_vuccgrids_card) && isset($vucc));
$primary_qsl = !empty($qsl_services) ? $qsl_services[0] : null;
$kpi_count = 2 + ($show_vucc_kpi ? 1 : 0) + ($primary_qsl ? 1 : 0);
$kpi_col_class = ($kpi_count === 4) ? 'col-6 col-xl-3' : (($kpi_count === 3) ? 'col-6 col-md-4' : 'col-6');
?>

<style>
	.dashboard-page {
		padding-top: 1.25rem;
		padding-bottom: 2.75rem;
	}

	.dashboard-page .htmx-indicator {
		display: none !important;
	}

	.dashboard-page .htmx-request.htmx-indicator,
	.dashboard-page .htmx-request .htmx-indicator {
		display: inline-flex !important;
	}

	.dashboard-header {
		padding-bottom: 0.9rem;
		margin-bottom: 1.1rem;
		border-bottom: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.22));
	}

	.dashboard-station-meta {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 0.45rem;
		font-size: 0.84rem;
	}

	.dashboard-meta-pill {
		display: inline-flex;
		align-items: center;
		gap: 0.35rem;
		padding: 0.2rem 0.6rem;
		border-radius: 999px;
		border: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.28));
		color: inherit;
		font-weight: 500;
		transition: border-color 0.15s ease, background-color 0.15s ease;
	}

	a.dashboard-meta-pill:hover {
		border-color: var(--bs-primary, #0d6efd);
		background-color: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.08);
	}

	.dashboard-today-pill {
		display: inline-flex;
		align-items: center;
		gap: 0.4rem;
		padding: 0.22rem 0.65rem;
		border-radius: 999px;
		font-size: 0.82rem;
		font-weight: 500;
	}

	.dashboard-today-pill.has-qsos {
		background-color: rgba(25, 135, 84, 0.14);
		color: var(--bs-success, #198754);
		border: 1px solid rgba(25, 135, 84, 0.32);
	}

	.dashboard-today-pill.no-qsos {
		background-color: rgba(var(--bs-warning-rgb, 255, 193, 7), 0.16);
		color: inherit;
		border: 1px solid rgba(var(--bs-warning-rgb, 255, 193, 7), 0.45);
	}

	.dashboard-page .card {
		border-radius: 0.5rem;
		margin-bottom: 0;
	}

	.dashboard-page .card-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 0.5rem;
		font-weight: 600;
		font-size: 0.92rem;
		padding: 0.65rem 1rem;
	}

	.dashboard-page .card-header-title {
		display: inline-flex;
		align-items: center;
		gap: 0.45rem;
	}

	.dashboard-page .card-header-title i {
		opacity: 0.72;
		font-size: 0.88rem;
	}

	.dashboard-map-card #map {
		width: 100%;
		height: 330px;
	}

	@media (max-width: 991.98px) {
		.dashboard-map-card #map {
			height: 260px;
		}
	}

	@media (max-width: 575.98px) {
		.dashboard-map-card #map {
			height: 215px;
		}
	}

	.kpi-card {
		height: 100%;
	}

	.kpi-card .card-body {
		padding: 0.9rem 1rem;
		display: flex;
		flex-direction: column;
		justify-content: flex-start;
		gap: 0.4rem;
	}

	#radio_display:empty {
		display: none;
	}

	.kpi-label {
		font-size: 0.76rem;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.04em;
		opacity: 0.7;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 0.4rem;
	}

	.kpi-value {
		font-size: 1.65rem;
		font-weight: 700;
		line-height: 1.15;
		font-variant-numeric: tabular-nums;
	}

	@media (max-width: 575.98px) {
		.kpi-value {
			font-size: 1.35rem;
		}
	}

	.kpi-sub {
		font-size: 0.79rem;
		font-variant-numeric: tabular-nums;
		display: flex;
		flex-wrap: wrap;
		gap: 0.25rem 0.65rem;
		margin-top: auto;
		padding-top: 0.15rem;
	}

	.kpi-progress {
		height: 5px;
		border-radius: 999px;
	}

	.dashboard-qso-table {
		font-size: 0.89rem;
		font-variant-numeric: tabular-nums;
	}

	.dashboard-qso-table thead th {
		font-size: 0.76rem;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		font-weight: 600;
		padding: 0.55rem 0.75rem;
		white-space: nowrap;
	}

	.dashboard-qso-table tbody td {
		padding: 0.48rem 0.75rem;
		vertical-align: middle;
		white-space: nowrap;
	}

	.dashboard-qso-table .qso-callsign-link {
		font-weight: 600;
		letter-spacing: 0.02em;
	}

	.dashboard-stat-table {
		font-size: 0.86rem;
		font-variant-numeric: tabular-nums;
	}

	.dashboard-stat-table th {
		font-size: 0.73rem;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		font-weight: 600;
		padding: 0.45rem 0.75rem;
	}

	.dashboard-stat-table td {
		padding: 0.5rem 0.75rem;
		vertical-align: middle;
	}

	.radio-live-dot {
		width: 0.5rem;
		height: 0.5rem;
		border-radius: 50%;
		background-color: var(--bs-success, #198754);
		display: inline-block;
		box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.55);
		animation: radioPulse 2s infinite;
	}

	@keyframes radioPulse {
		0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.55); }
		70% { box-shadow: 0 0 0 6px rgba(25, 135, 84, 0); }
		100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
	}
</style>

<div class="container dashboard-page">
	<!-- Dashboard Header Bar -->
	<div class="dashboard-header d-flex flex-wrap align-items-center justify-content-between gap-3">
		<div>
			<div class="d-flex flex-wrap align-items-center gap-2 mb-1">
				<h1 class="h4 mb-0 fw-bold"><?php echo $station_callsign !== '' ? htmlspecialchars($station_callsign, ENT_QUOTES, 'UTF-8') : 'Dashboard'; ?></h1>
				<?php if ($can_log && $show_banner) { ?>
					<div id="todays_qso_component" class="d-inline-flex align-items-center" hx-get="<?php echo site_url('dashboard/todays_qso_component'); ?>" hx-trigger="load, every 60s [!document.hidden]" hx-indicator="#todays-qso-loading">
						<?php if ($todays_qsos >= 1) { ?>
							<span class="dashboard-today-pill has-qsos">
								<i class="fas fa-broadcast-tower"></i>
								<span><?php echo lang('dashboard_you_have_had'); ?> <strong><?php echo number_format((int) $todays_qsos); ?></strong> <?php echo $todays_qsos != 1 ? lang('dashboard_qsos_today') : str_replace('QSOs', 'QSO', lang('dashboard_qsos_today')); ?></span>
							</span>
						<?php } else { ?>
							<span class="dashboard-today-pill no-qsos">
								<i class="fas fa-broadcast-tower"></i>
								<span><?php echo lang('notice_turn_the_radio_on'); ?></span>
							</span>
						<?php } ?>
					</div>
					<span id="todays-qso-loading" class="htmx-indicator small text-muted"><i class="fas fa-spinner fa-spin"></i></span>
				<?php } ?>
			</div>

			<div class="dashboard-station-meta text-muted">
				<?php if ($station_name !== '') { ?>
					<a href="<?php echo site_url('station'); ?>" class="dashboard-meta-pill" title="Manage Station Locations">
						<i class="fas fa-map-marker-alt opacity-75"></i>
						<span><?php echo htmlspecialchars($station_name, ENT_QUOTES, 'UTF-8'); ?></span>
						<?php if ($station_grid !== '') { ?>
							<span class="opacity-75">&middot; <?php echo htmlspecialchars($station_grid, ENT_QUOTES, 'UTF-8'); ?></span>
						<?php } ?>
					</a>
				<?php } ?>
				<?php if ($logbook_label !== '') { ?>
					<a href="<?php echo site_url('logbooks'); ?>" class="dashboard-meta-pill" title="Manage Station Logbooks">
						<i class="fas fa-book opacity-75"></i>
						<span><?php echo htmlspecialchars($logbook_label, ENT_QUOTES, 'UTF-8'); ?></span>
					</a>
				<?php } ?>
				<?php if ($station_name !== '' || $logbook_label !== '') { ?>
					<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" data-bs-toggle="modal" data-bs-target="#logbookQuickSwitchModal">
						<i class="fas fa-exchange-alt me-1"></i><?php echo lang('station_logbooks_change'); ?>
					</button>
				<?php } ?>
			</div>
		</div>

		<?php if ($can_log) { ?>
			<div class="d-flex flex-wrap gap-2">
				<a href="<?php echo site_url('qso?manual=0'); ?>" class="btn btn-primary">
					<i class="fas fa-pencil-alt me-1"></i> General Logging
				</a>
				<a href="<?php echo site_url('contesting?manual=0'); ?>" class="btn btn-success">
					<i class="fas fa-trophy me-1"></i> Contest Logging
				</a>
			</div>
		<?php } ?>
	</div>

	<?php if ($this->session->flashdata('notice')) { ?>
		<div class="alert alert-info alert-dismissible fade show py-2 mb-3" role="alert">
			<i class="fas fa-info-circle me-1"></i>
			<?php echo $this->session->flashdata('notice'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- System & Account Alerts -->
	<?php if ($can_log) { ?>
		<?php if (version_compare(PHP_VERSION, '7.4.0') <= 0) { ?>
			<div class="alert alert-danger py-2 mb-3" role="alert">
				<i class="fas fa-exclamation-triangle me-1"></i>
				<?php echo lang('dashboard_php_version_warning') . ' ' . PHP_VERSION . '.'; ?>
			</div>
		<?php } ?>

		<?php if ($countryCount == 0) { ?>
			<div class="alert alert-danger py-2 mb-3" role="alert">
				<i class="fas fa-exclamation-triangle me-1"></i>
				<?php echo lang('dashboard_country_files_warning'); ?>
			</div>
		<?php } ?>

		<?php if ($locationCount == 0) { ?>
			<div class="alert alert-danger py-2 mb-3" role="alert">
				<i class="fas fa-exclamation-triangle me-1"></i>
				<?php echo lang('dashboard_locations_warning'); ?>
			</div>
		<?php } ?>

		<?php if ($logbookCount == 0) { ?>
			<div class="alert alert-danger py-2 mb-3" role="alert">
				<i class="fas fa-exclamation-triangle me-1"></i>
				<?php echo lang('dashboard_logbooks_warning'); ?>
			</div>
		<?php } ?>

		<?php if (!empty($clublog_cron_station_warning)) { ?>
			<div class="alert alert-warning py-2 mb-3" role="alert">
				<i class="fas fa-exclamation-circle me-1"></i>
				Clublog credentials are configured, but no Station Location has Upload to Clublog enabled.
				<a href="<?php echo site_url('station'); ?>" class="alert-link">Enable at least one Station Location</a> to upload via /clublog/upload.
			</div>
		<?php } ?>

		<?php if ($current_active == 0) { ?>
			<div class="alert alert-danger py-2 mb-3" role="alert">
				<i class="fas fa-exclamation-triangle me-1"></i>
				<?php echo lang('error_no_active_station_profile'); ?>
			</div>
		<?php } ?>

		<?php if ($this->session->userdata('user_id')) {
			$current_date = date('Y-m-d H:i:s');
			if ($this->LotwCert->lotw_cert_expired($this->session->userdata('user_id'), $current_date) == true) { ?>
				<div class="alert alert-danger py-2 mb-3" role="alert">
					<i class="fas fa-hourglass-end me-1"></i> <?php echo lang('lotw_cert_expired'); ?>
				</div>
			<?php } ?>

			<?php if ($this->LotwCert->lotw_cert_expiring($this->session->userdata('user_id'), $current_date) == true) { ?>
				<div class="alert alert-warning py-2 mb-3" role="alert">
					<i class="fas fa-hourglass-half me-1"></i> <?php echo lang('lotw_cert_expiring'); ?>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>

	<!-- Framed QSO Map -->
	<?php if ($show_top_map) { ?>
		<div class="card dashboard-map-card overflow-hidden mb-4">
			<div class="card-header py-2 px-3">
				<span class="card-header-title">
					<i class="fas fa-globe-europe"></i>
					<span>Recent QSOs Map</span>
					<?php if (!empty($dashboard_map_greyline)) { ?>
						<span class="badge text-bg-secondary fw-normal ms-1">Greyline</span>
					<?php } ?>
				</span>
				<a href="<?php echo site_url('gridmap'); ?>" class="btn btn-outline-secondary btn-sm py-0 px-2">
					<i class="fas fa-layer-group me-1"></i> Gridmap
				</a>
			</div>
			<div id="map" class="map-leaflet"></div>
		</div>
	<?php } ?>

	<!-- At-a-Glance KPI Strip -->
	<div class="row g-3 mb-4">
		<!-- KPI 1: Total QSOs -->
		<div class="<?php echo $kpi_col_class; ?>">
			<div class="card kpi-card">
				<div class="card-body">
					<div class="kpi-label">
						<span><?php echo lang('dashboard_qso_breakdown'); ?></span>
						<i class="fas fa-chart-bar"></i>
					</div>
					<div class="kpi-value"><?php echo number_format((int) $total_qsos); ?></div>
					<?php
					$cur_month_qsos = (int) $month_qsos;
					$prev_mtd_qsos = isset($prev_month_mtd_qsos) ? (int) $prev_month_mtd_qsos : 0;
					$month_delta = $cur_month_qsos - $prev_mtd_qsos;
					$month_trend_title = number_format($cur_month_qsos) . ' this month vs ' . number_format($prev_mtd_qsos) . ' by this point last month';
					?>
					<div class="kpi-sub text-muted">
						<span><?php echo lang('general_word_year'); ?>: <strong class="text-body"><?php echo number_format((int) $year_qsos); ?></strong></span>
						<span class="d-inline-flex align-items-center gap-1">
							<span><?php echo lang('general_word_month'); ?>: <strong class="text-body"><?php echo number_format($cur_month_qsos); ?></strong></span>
							<?php if ($month_delta > 0) { ?>
								<span class="text-success" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($month_trend_title . ' (+' . number_format($month_delta) . ')', ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($month_trend_title, ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-caret-up"></i></span>
							<?php } elseif ($month_delta < 0) { ?>
								<span class="text-muted opacity-75" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($month_trend_title . ' (' . number_format($month_delta) . ')', ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($month_trend_title, ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-caret-down"></i></span>
							<?php } ?>
						</span>
					</div>
				</div>
			</div>
		</div>

		<!-- KPI 2: DXCC Countries -->
		<div class="<?php echo $kpi_col_class; ?>">
			<div class="card kpi-card">
				<div class="card-body">
					<div class="kpi-label">
						<span><?php echo lang('dashboard_countries_breakdown'); ?></span>
						<i class="fas fa-globe-europe"></i>
					</div>
					<div class="d-flex align-items-baseline justify-content-between gap-2">
						<div class="kpi-value"><?php echo number_format((int) $total_countries); ?></div>
						<small class="text-muted"><?php echo number_format((int) $total_countries_needed); ?> <?php echo strtolower(lang('general_word_needed')); ?></small>
					</div>
					<div class="progress kpi-progress" role="progressbar" aria-label="DXCC countries worked progress" aria-valuenow="<?php echo $dxcc_pct; ?>" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar" style="width: <?php echo $dxcc_pct; ?>%;"></div>
					</div>
					<div class="kpi-sub text-muted">
						<span title="Confirmed via LoTW">LoTW: <strong class="text-body"><?php echo number_format((int) $total_countries_confirmed_lotw); ?></strong></span>
						<span title="Confirmed via eQSL">eQSL: <strong class="text-body"><?php echo number_format((int) $total_countries_confirmed_eqsl); ?></strong></span>
						<span title="Confirmed via QSL Cards">QSL: <strong class="text-body"><?php echo number_format((int) $total_countries_confirmed_paper); ?></strong></span>
					</div>
				</div>
			</div>
		</div>

		<!-- KPI 3: Primary QSL Service (if any enabled with data) -->
		<?php if ($primary_qsl) { ?>
			<div class="<?php echo $kpi_col_class; ?>">
				<div class="card kpi-card">
					<div class="card-body">
						<div class="kpi-label">
							<span><?php echo $primary_qsl['label']; ?></span>
							<i class="<?php echo $primary_qsl['icon']; ?>"></i>
						</div>
						<div class="d-flex align-items-baseline justify-content-between gap-2">
							<div class="kpi-value"><?php echo number_format($primary_qsl['rcvd']); ?></div>
							<small class="text-muted"><?php echo strtolower(lang('general_word_received')); ?></small>
						</div>
						<div class="kpi-sub text-muted">
							<span><?php echo lang('general_word_sent'); ?>: <strong class="text-body"><?php echo number_format($primary_qsl['sent']); ?></strong></span>
							<span><?php echo lang('general_word_today'); ?>: <a href="<?php echo $primary_qsl['rcvd_fn']; ?>"><?php echo number_format($primary_qsl['rcvd_today']); ?></a></span>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<!-- KPI 4: VUCC Grids (if enabled) -->
		<?php if ($show_vucc_kpi) { ?>
			<div class="<?php echo $kpi_col_class; ?>">
				<div class="card kpi-card">
					<div class="card-body">
						<div class="kpi-label">
							<span>VUCC Grids</span>
							<i class="fas fa-th-large"></i>
						</div>
						<div class="d-flex align-items-baseline justify-content-between gap-2">
							<div class="kpi-value"><?php echo number_format((int) ($vucc['All']['worked'] ?? 0)); ?></div>
							<small class="text-muted"><?php echo number_format((int) ($vucc['All']['confirmed'] ?? 0)); ?> conf.</small>
						</div>
						<div class="kpi-sub text-muted">
							<span>SAT: <strong class="text-body"><?php echo number_format((int) ($vuccSAT['SAT']['worked'] ?? 0)); ?></strong> worked &middot; <strong class="text-body"><?php echo number_format((int) ($vuccSAT['SAT']['confirmed'] ?? 0)); ?></strong> conf.</span>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>

	<!-- Main 2-Column Content -->
	<div class="row g-4">
		<!-- Left: Recent QSOs Card -->
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header">
					<span class="card-header-title">
						<i class="fas fa-book-open"></i>
						<span>Recent QSOs</span>
					</span>
					<div class="d-flex align-items-center gap-2">
						<span id="logbook-loading" class="htmx-indicator small text-muted"><i class="fas fa-spinner fa-spin me-1"></i>Updating</span>
						<span id="logbook-last-updated-wrap" class="d-none small text-muted fw-normal"><span id="logbook-last-updated"></span></span>
						<a href="<?php echo site_url('logbook'); ?>" class="btn btn-outline-secondary btn-sm py-0 px-2">View Logbook</a>
					</div>
				</div>
				<div class="card-body p-0">
					<div id="logbook_display_component" hx-get="<?php echo site_url('dashboard/logbook_display_component'); ?>" hx-trigger="load, every 15s [!document.hidden]" data-updated-target="#logbook-last-updated" data-updated-wrap-target="#logbook-last-updated-wrap" hx-indicator="#logbook-loading">
						<div class="p-4 text-center text-muted small">
							<i class="fas fa-spinner fa-spin me-1"></i> Loading recent QSOs...
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Right: Live Radio, DXPeditions & QSL Breakdown -->
		<div class="col-lg-4 d-flex flex-column gap-3">
			<?php if ($show_right_map) { ?>
				<div class="card dashboard-map-card overflow-hidden">
					<div class="card-header py-2 px-3">
						<span class="card-header-title">
							<i class="fas fa-globe-europe"></i>
							<span>Recent QSOs Map</span>
						</span>
						<a href="<?php echo site_url('gridmap'); ?>" class="btn btn-outline-secondary btn-sm py-0 px-2">Gridmap</a>
					</div>
					<div id="map" class="map-leaflet"></div>
				</div>
			<?php } ?>

			<!-- Radio CAT Status (renders card only when a radio is active) -->
			<div id="radio_display" hx-get="<?php echo site_url('dashboard/radio_display_component'); ?>" hx-trigger="load, every 15s [!document.hidden]" data-updated-target="#radio-last-updated" data-updated-wrap-target="#radio-last-updated-wrap"></div>

			<!-- Upcoming DXPeditions -->
			<?php if ($dashboard_upcoming_dx_card != false) { ?>
				<div id="upcoming_dxccs_component" hx-get="<?php echo site_url('dashboard/upcoming_dxcc_component'); ?>" hx-trigger="load, every 30m [!document.hidden]" hx-indicator="#loading_upcoming_dxcc">
					<div class="card">
						<div class="card-header">
							<span class="card-header-title"><i class="fas fa-globe"></i> DXPeditions (This Week)</span>
						</div>
						<div class="card-body py-3 text-center text-muted small">
							<span id="loading_upcoming_dxcc"><i class="fas fa-spinner fa-spin me-1"></i> Loading upcoming DXpeditions...</span>
						</div>
					</div>
				</div>
			<?php } ?>

			<!-- Hamsat Rovers -->
			<?php if (!empty($dashboard_hamsat_card)) { ?>
				<div id="hamsat_dashboard_component" hx-get="<?php echo site_url('dashboard/hamsat_component'); ?>" hx-trigger="load, every 5m [!document.hidden]" hx-indicator="#loading_hamsat_dashboard">
					<div class="card">
						<div class="card-header">
							<span class="card-header-title"><i class="fas fa-satellite"></i> Hamsat Rovers</span>
						</div>
						<div class="card-body py-3 text-center text-muted small">
							<span id="loading_hamsat_dashboard"><i class="fas fa-spinner fa-spin me-1"></i> Loading Hamsat activations...</span>
						</div>
					</div>
				</div>
			<?php } ?>

			<!-- Consolidated QSL Services Card -->
			<?php if (!empty($qsl_services)) { ?>
				<div class="card">
					<div class="card-header">
						<span class="card-header-title">
							<i class="fas fa-sync-alt"></i>
							<span>QSL &amp; Confirmations</span>
						</span>
						<span class="text-muted small fw-normal"><?php echo lang('general_word_today'); ?> in blue</span>
					</div>
					<div class="table-responsive">
						<table class="table table-hover dashboard-stat-table mb-0">
							<thead>
								<tr>
									<th>Service</th>
									<th class="text-end"><?php echo lang('general_word_sent'); ?></th>
									<th class="text-end"><?php echo lang('general_word_received'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($qsl_services as $svc) { ?>
									<tr>
										<td class="fw-semibold">
											<i class="<?php echo $svc['icon']; ?> me-1 opacity-75"></i><?php echo $svc['label']; ?>
											<?php if ($svc['requested'] !== null && $svc['requested'] > 0) { ?>
												<div class="small text-muted fw-normal">
													<?php echo lang('general_word_requested'); ?>: <?php echo number_format($svc['requested']); ?>
													<?php if ($svc['requested_today'] > 0) { ?>
														(+<?php echo number_format($svc['requested_today']); ?>)
													<?php } ?>
												</div>
											<?php } ?>
										</td>
										<td class="text-end">
											<div><?php echo number_format($svc['sent']); ?></div>
											<div class="small">
												<a href="<?php echo $svc['sent_fn']; ?>" title="<?php echo lang('general_word_sent') . ' ' . strtolower(lang('general_word_today')); ?>">+<?php echo number_format($svc['sent_today']); ?></a>
											</div>
										</td>
										<td class="text-end">
											<div><?php echo number_format($svc['rcvd']); ?></div>
											<div class="small">
												<a href="<?php echo $svc['rcvd_fn']; ?>" title="<?php echo lang('general_word_received') . ' ' . strtolower(lang('general_word_today')); ?>">+<?php echo number_format($svc['rcvd_today']); ?></a>
											</div>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<script>
	(function () {
		if (typeof htmx === 'undefined') {
			return;
		}

		function setText(selector, value) {
			if (!selector) {
				return;
			}
			var target = document.querySelector(selector);
			if (target) {
				target.textContent = value;
			}
		}

		function showElement(selector) {
			if (!selector) {
				return;
			}
			var target = document.querySelector(selector);
			if (target) {
				target.classList.remove('d-none');
			}
		}

		document.body.addEventListener('htmx:afterSwap', function (event) {
			var source = event.detail.elt;
			if (!source) {
				return;
			}
			var now = new Date();
			var hhmm = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
			setText(source.getAttribute('data-updated-target'), 'Updated ' + hhmm);
			showElement(source.getAttribute('data-updated-wrap-target'));
		});
	})();
</script>
<?php if ($station_name !== '' || $logbook_label !== '') {
	$this->load->view('logbooks/components/quick_switch_modal', array('quick_switch_return' => 'dashboard'));
	echo '<script src="' . base_url() . 'assets/js/sections/logbook_quick_switch.js"></script>';
} ?>
