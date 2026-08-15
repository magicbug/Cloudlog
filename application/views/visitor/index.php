<?php
function visitor_display_callsign($call) {
	return str_replace('0', '&Oslash;', htmlspecialchars(strtoupper((string) $call), ENT_QUOTES, 'UTF-8'));
}

$brand_name = isset($brand_name) ? $brand_name : '';
$public_name = isset($public_name) ? trim((string) $public_name) : '';
$station_callsigns = isset($station_callsigns) && is_array($station_callsigns) ? $station_callsigns : array();
$slug = isset($slug) ? $slug : '';
$date_format = isset($date_format) && $date_format !== '' ? $date_format : 'Y-m-d';
$on_air = !empty($public_radio_status) && isset($radio_status) && is_object($radio_status) && method_exists($radio_status, 'num_rows') && $radio_status->num_rows();
$public_search_enabled = !empty($public_search_enabled);
$show_public_subtitle = ($public_name !== '' && strtoupper($public_name) !== strtoupper($brand_name));
?>
<noscript><style> #map { display: none } </style></noscript>
<div class="container visitor-page">
	<div class="visitor-showcase">
		<div class="visitor-hero">
			<div class="visitor-hero-identity">
				<span class="visitor-kicker"><?php echo lang('visitor_public_logbook'); ?></span>
				<h1 class="visitor-callsign"><?php echo visitor_display_callsign($brand_name); ?></h1>
				<?php if ($show_public_subtitle) { ?>
					<p class="visitor-logbook-name"><?php echo htmlspecialchars($public_name, ENT_QUOTES, 'UTF-8'); ?></p>
				<?php } ?>
				<?php if (count($station_callsigns) > 1) { ?>
					<p class="visitor-callsigns">
						<?php
						$shown = array();
						foreach ($station_callsigns as $cs) {
							$shown[] = visitor_display_callsign($cs);
						}
						echo implode(' · ', $shown);
						?>
					</p>
				<?php } ?>
				<?php if ($on_air) { ?>
					<span class="badge text-bg-success visitor-onair"><i class="fas fa-broadcast-tower"></i> <?php echo lang('visitor_on_air'); ?></span>
				<?php } ?>
			</div>
			<?php if ($public_search_enabled) { ?>
			<form method="post" name="searchForm" action="<?php echo site_url('visitor/search'); ?>" onsubmit="return validateForm()" class="visitor-find-form">
				<label class="visitor-find-label" for="searchcall"><?php echo lang('visitor_find_in_log'); ?></label>
				<p class="visitor-find-hint"><?php echo lang('visitor_find_in_log_hint'); ?></p>
				<div class="input-group visitor-find-group">
					<input class="form-control visitor-find-input" id="searchcall" type="search" name="callsign" placeholder="<?php echo lang('visitor_search_callsign'); ?>" aria-label="<?php echo lang('visitor_search_callsign'); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo lang('visitor_search_callsign'); ?>">
					<input type="hidden" name="public_slug" value="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">
					<button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> <?php echo lang('menu_search_button'); ?></button>
				</div>
			</form>
			<?php } ?>
		</div>
		<div class="visitor-map-card">
			<div id="map" class="map-leaflet visitor-map" style="width: 100%; height: 380px"></div>
		</div>
	</div>

	<div class="row logdata">
		<div class="col-lg-8">
			<h2 class="visitor-section-title"><?php echo lang('visitor_recent_contacts'); ?></h2>
			<div class="table-responsive">
				<table class="table table-striped table-hover visitor-log-table">
					<thead>
						<tr class="titles">
							<th><?php echo lang('general_word_date'); ?></th>
							<th><?php echo lang('general_word_time'); ?></th>
							<th>&nbsp;</th>
							<th><?php echo lang('gen_hamradio_call'); ?></th>
							<th><?php echo lang('general_word_country'); ?></th>
							<th><?php echo lang('gen_hamradio_mode'); ?></th>
							<th><?php echo lang('gen_hamradio_band'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$i = 0;
					if (!empty($results)) {
						$ci = &get_instance();
						$ci->load->library('DxccFlag');
						foreach ($results->result() as $row) {
							$timestamp = strtotime($row->COL_TIME_ON);
							$flag = strtolower($ci->dxccflag->getISO($row->COL_DXCC));
							$country = !empty($row->name) ? $row->name : $row->COL_COUNTRY;
							$country_label = ucwords(strtolower($country == null ? '- NONE -' : $country));
							$mode = $row->COL_SUBMODE == null ? $row->COL_MODE : $row->COL_SUBMODE;
							echo '<tr class="tr'.($i & 1).'">';
					?>
							<td><?php echo date($date_format, $timestamp); ?></td>
							<td><?php echo date('H:i', $timestamp); ?></td>
							<td><span data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($country_label, ENT_QUOTES, 'UTF-8'); ?>"><span class="fi fi-<?php echo htmlspecialchars($flag, ENT_QUOTES, 'UTF-8'); ?>"></span></span></td>
							<td><?php echo visitor_display_callsign($row->COL_CALL); ?></td>
							<td><?php echo htmlspecialchars($country_label, ENT_QUOTES, 'UTF-8'); ?></td>
							<td><?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?></td>
							<td><?php
								if ($row->COL_SAT_NAME != null) {
									echo '<a href="https://db.satnogs.org/search/?q='.urlencode($row->COL_SAT_NAME).'" target="_blank" rel="noopener">'.htmlspecialchars($row->COL_SAT_NAME, ENT_QUOTES, 'UTF-8').'</a>';
								} else {
									echo htmlspecialchars(strtolower($row->COL_BAND), ENT_QUOTES, 'UTF-8');
								}
							?></td>
						</tr>
					<?php
							$i++;
						}
					}
					?>
					</tbody>
				</table>
				<div class="pagination-links">
					<?php echo $this->pagination->create_links(); ?>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<?php if (!empty($public_radio_status)) { ?>
			<div id="radio_display" hx-get="<?php echo site_url('visitor/radio_display_component/'.$slug); ?>" hx-trigger="load, every 30s"></div>
			<?php } ?>

			<div class="card visitor-stats-card mb-3">
				<div class="card-header"><i class="fas fa-chart-bar"></i> <?php echo lang('visitor_activity'); ?></div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item d-flex justify-content-between"><span><?php echo lang('general_word_total'); ?></span><strong><?php echo (int) $total_qsos; ?></strong></li>
					<li class="list-group-item d-flex justify-content-between"><span><?php echo lang('general_word_year'); ?></span><strong><?php echo (int) $year_qsos; ?></strong></li>
					<li class="list-group-item d-flex justify-content-between"><span><?php echo lang('general_word_month'); ?></span><strong><?php echo (int) $month_qsos; ?></strong></li>
				</ul>
			</div>

			<div class="card visitor-stats-card mb-3">
				<div class="card-header"><i class="fas fa-globe-europe"></i> <?php echo lang('dashboard_countries_breakdown'); ?></div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item d-flex justify-content-between"><span><?php echo lang('general_word_worked'); ?></span><strong><?php echo (int) $total_countries; ?></strong></li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<span><a href="#" onclick="return false" title="<?php echo lang('visitor_confirmed_via'); ?>" data-bs-toggle="tooltip"><?php echo lang('general_word_confirmed'); ?></a></span>
						<strong><?php echo (int) $total_countries_confirmed_paper; ?> / <?php echo (int) $total_countries_confirmed_eqsl; ?> / <?php echo (int) $total_countries_confirmed_lotw; ?></strong>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div id="partial_view"></div>
