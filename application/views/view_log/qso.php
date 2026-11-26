<?php if ($query->num_rows() > 0) { foreach ($query->result() as $row) {
	if ($this->session->userdata('user_date_format')) {
		$custom_date_format = $this->session->userdata('user_date_format');
	} else {
		$custom_date_format = $this->config->item('qso_date_format');
	}

	$can_edit = ($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE;
	$show_time = $can_edit || ($this->config->item('show_time'));
	$call_upper = strtoupper($row->COL_CALL);
	$call_display = str_replace("0", "&Oslash;", htmlspecialchars($call_upper, ENT_QUOTES, 'UTF-8'));
	$mode_display = $row->COL_SUBMODE == null ? $row->COL_MODE : $row->COL_SUBMODE;

	$ts_on = strtotime($row->COL_TIME_ON);
	$ts_off = strtotime($row->COL_TIME_OFF);
	$date_str = date($custom_date_format, $ts_on);
	$time_on = date('H:i', $ts_on);
	$time_off = date('H:i', $ts_off);
	$time_range = $show_time ? ($time_on . ($time_on != $time_off ? ' &ndash; ' . $time_off : '') . ' UTC') : '';

	// Country flag + continent
	$country_name = '';
	$flag_iso = '';
	if ($row->name != null) {
		$ci =& get_instance();
		$ci->load->library('DxccFlag');
		$flag_iso = strtolower($ci->dxccflag->getISO($row->COL_DXCC));
		$country_name = ucwords(strtolower($row->name), "- (/");
	}
	$continent_map = array(
		'AF' => lang('africa'),
		'AN' => lang('antarctica'),
		'AS' => lang('asia'),
		'EU' => lang('europe'),
		'NA' => lang('northamerica'),
		'OC' => lang('oceania'),
		'SA' => lang('southamerica'),
	);
	$continent_label = ($row->COL_CONT != null && isset($continent_map[$row->COL_CONT])) ? $continent_map[$row->COL_CONT] : '';

	// Distance calculation
	$distance = null;
	$grid_for_dist = null;
	if ($row->COL_GRIDSQUARE != null && strlen($row->COL_GRIDSQUARE) >= 4) {
		$grid_for_dist = $row->COL_GRIDSQUARE;
	} elseif ($row->COL_VUCC_GRIDS != null) {
		$grid_for_dist = $row->COL_VUCC_GRIDS;
	}
	if ($grid_for_dist !== null && !empty($row->station_gridsquare)) {
		$dist_val = $this->qra->distance($row->station_gridsquare, $grid_for_dist, $measurement_base);
		switch ($measurement_base) {
			case 'M': $distance = $dist_val . ' mi'; break;
			case 'K': $distance = $dist_val . ' km'; break;
			case 'N': $distance = $dist_val . ' nmi'; break;
			default:  $distance = $dist_val; break;
		}
	}

	// Confirmation states
	$lotw_confirmed = ($row->COL_LOTW_QSL_RCVD == "Y");
	$eqsl_confirmed = ($row->COL_EQSL_QSL_RCVD == "Y");
	$clublog_confirmed = (($row->COL_CLUBLOG_QSO_DOWNLOAD_STATUS ?? '') == "Y");
	$qsl_rcvd = ($row->COL_QSL_RCVD == "Y");
	$qsl_sent = ($row->COL_QSL_SENT == "Y");

	$via_labels = array('B' => 'Bureau', 'D' => 'Direct', 'E' => 'Electronic', 'M' => 'Manager');
	$qsl_status_text = '&mdash;';
	$qsl_sub_text = 'Not confirmed';
	$qsl_tile_class = '';
	if ($qsl_rcvd) {
		$qsl_tile_class = 'is-confirmed';
		$qsl_status_text = '<i class="fas fa-check-circle me-1"></i>Confirmed';
		$via = $via_labels[$row->COL_QSL_RCVD_VIA] ?? '';
		$dt = $row->COL_QSLRDATE != null ? date($custom_date_format, strtotime($row->COL_QSLRDATE)) : '';
		$qsl_sub_text = trim($via . ($via && $dt ? ' · ' : '') . $dt) ?: 'Received';
	} elseif ($qsl_sent) {
		$qsl_tile_class = 'is-sent';
		$qsl_status_text = '<i class="fas fa-paper-plane me-1"></i>Sent';
		$via = $via_labels[$row->COL_QSL_SENT_VIA] ?? '';
		$dt = $row->COL_QSLSDATE != null ? date($custom_date_format, strtotime($row->COL_QSLSDATE)) : '';
		$qsl_sub_text = trim($via . ($via && $dt ? ' · ' : '') . $dt) ?: 'Sent';
	}

	// Social share string
	if ($row->COL_SAT_NAME != null) {
		$twitter_band_sat = $row->COL_SAT_NAME . " \u{1F6F0}\u{FE0F}";
		$hashtags = "#hamr #cloudlog #amsat";
	} else {
		$twitter_band_sat = $row->COL_BAND;
		$hashtags = "#hamr #cloudlog";
	}
	if ($row->COL_IOTA != null) { $hashtags .= " #IOTA " . $row->COL_IOTA; }
	if ($row->COL_SOTA_REF != null) { $hashtags .= " #SOTA " . $row->COL_SOTA_REF; }
	if ($row->COL_POTA_REF != null) {
		$pota_refs_share = array_filter(array_map('trim', explode(',', $row->COL_POTA_REF)));
		foreach ($pota_refs_share as $pota_ref) {
			$hashtags .= " #POTA " . $pota_ref;
		}
	}
	if ($row->COL_WWFF_REF != null) { $hashtags .= " #WWFF " . $row->COL_WWFF_REF; }
	if ($row->COL_SIG != null && $row->COL_SIG_INFO != null) { $hashtags .= " #" . $row->COL_SIG . " " . $row->COL_SIG_INFO; }

	if ($distance === null) {
		$twitter_string = urlencode("Just worked " . $row->COL_CALL . " ");
		if ($row->COL_DXCC != 0) {
			$twitter_string .= urlencode("in " . ucwords(strtolower($row->COL_COUNTRY)) . " ");
		}
		$twitter_string .= urlencode("on " . $twitter_band_sat . " using " . $mode_display . " " . $hashtags);
	} else {
		$twitter_string = urlencode("Just worked " . $row->COL_CALL . " ");
		if ($row->COL_DXCC != 0) {
			$twitter_string .= urlencode("in " . ucwords(strtolower($row->COL_COUNTRY)) . " ");
			if (isset($dxccFlag)) {
				$twitter_string .= $dxccFlag . " ";
			}
		}
		$distancestring = '';
		if ($row->COL_VUCC_GRIDS == null) {
			$distancestring = "(Gridsquare: " . $row->COL_GRIDSQUARE . " / distance: " . $distance . ")";
		} else {
			if (substr_count($row->COL_VUCC_GRIDS, ',') == 1) {
				$distancestring = "(Gridline: " . preg_replace('/\s+/', '', $row->COL_VUCC_GRIDS) . " / distance: " . $distance . ")";
			} elseif (substr_count($row->COL_VUCC_GRIDS, ',') == 3) {
				$distancestring = "(Gridcorner: " . preg_replace('/\s+/', '', $row->COL_VUCC_GRIDS) . " / distance: " . $distance . ")";
			} else {
				$distancestring = "(Grids: " . $row->COL_VUCC_GRIDS . ")";
			}
		}
		$twitter_string .= urlencode($distancestring . " on " . $twitter_band_sat . " using " . $mode_display . " " . $hashtags);
	}

	// Check if any award refs exist
	$has_award_refs = ($row->COL_IOTA != null || $row->COL_SOTA_REF != null || $row->COL_WWFF_REF != null || $row->COL_POTA_REF != null || $row->COL_SIG != null || $row->COL_SIG_INFO != null || $row->COL_DARC_DOK != null);
?>
<div class="qso-modal-wrap">
	<!-- 1. Hero QSO Summary Banner -->
	<div class="qso-modal-hero d-flex flex-wrap align-items-start justify-content-between gap-3">
		<div>
			<div class="d-flex flex-wrap align-items-center gap-2">
				<span class="qso-modal-callsign"><?php echo $call_display; ?></span>
				<div class="d-inline-flex flex-wrap gap-1">
					<a class="qso-lookup-pill" target="_blank" href="https://www.qrz.com/db/<?php echo rawurlencode($call_upper); ?>" title="Lookup <?php echo htmlspecialchars($call_upper, ENT_QUOTES, 'UTF-8'); ?> on QRZ.com">QRZ <i class="fas fa-external-link-alt fa-xs"></i></a>
					<a class="qso-lookup-pill" target="_blank" href="https://www.hamqth.com/<?php echo rawurlencode($call_upper); ?>" title="Lookup <?php echo htmlspecialchars($call_upper, ENT_QUOTES, 'UTF-8'); ?> on HamQTH">HamQTH</a>
					<a class="qso-lookup-pill" target="_blank" href="http://www.eqsl.cc/Member.cfm?<?php echo rawurlencode($call_upper); ?>" title="Lookup <?php echo htmlspecialchars($call_upper, ENT_QUOTES, 'UTF-8'); ?> on eQSL.cc">eQSL</a>
				</div>
			</div>
			<div class="qso-modal-submeta d-flex flex-wrap align-items-center gap-2 mt-1">
				<?php if ($country_name !== '') { ?>
					<span>
						<?php if ($flag_iso !== '') { ?><span class="fi fi-<?php echo $flag_iso; ?> me-1"></span><?php } ?>
						<strong><?php echo htmlspecialchars($country_name, ENT_QUOTES, 'UTF-8'); ?></strong>
						<?php if ($row->end != null) { ?><span class="badge text-bg-danger ms-1"><?php echo lang('gen_hamradio_deleted_dxcc'); ?></span><?php } ?>
						<?php if ($continent_label !== '') { ?> &middot; <?php echo $continent_label; ?><?php } ?>
					</span>
				<?php } ?>
				<?php if ($row->COL_NAME != null) { ?>
					<?php if ($country_name !== '') { ?><span class="opacity-50">&bull;</span><?php } ?>
					<span><i class="far fa-user me-1"></i><strong><?php echo htmlspecialchars($row->COL_NAME, ENT_QUOTES, 'UTF-8'); ?></strong></span>
				<?php } ?>
			</div>
		</div>

		<div class="qso-modal-hero-right d-flex flex-column align-items-md-end gap-1">
			<div class="d-flex flex-wrap gap-1 justify-content-md-end">
				<span class="qso-sig-pill is-primary"><?php echo htmlspecialchars($row->COL_SAT_NAME != null ? $row->COL_SAT_NAME : $row->COL_BAND, ENT_QUOTES, 'UTF-8'); ?></span>
				<span class="qso-sig-pill is-primary"><?php echo htmlspecialchars($mode_display, ENT_QUOTES, 'UTF-8'); ?></span>
				<?php if ($this->config->item('display_freq') == true && !empty($row->COL_FREQ)) { ?>
					<span class="qso-sig-pill"><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ); ?></span>
				<?php } ?>
				<span class="qso-sig-pill">RST <?php echo htmlspecialchars($row->COL_RST_SENT, ENT_QUOTES, 'UTF-8'); ?> / <?php echo htmlspecialchars($row->COL_RST_RCVD, ENT_QUOTES, 'UTF-8'); ?></span>
			</div>
			<div class="small text-muted qso-modal-datetime">
				<i class="far fa-calendar-alt me-1"></i><?php echo $date_str; ?><?php if ($time_range !== '') { ?> &middot; <?php echo $time_range; ?><?php } ?>
			</div>
		</div>
	</div>

	<!-- 2. Modern Underline Tabs -->
	<ul class="nav nav-tabs qso-modal-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="table-tab" data-bs-toggle="tab" href="#qsodetails" role="tab" aria-controls="qsodetails" aria-selected="true">
				<i class="fas fa-info-circle me-1"></i><?php echo lang('qso_details'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a id="station-tab" class="nav-link" data-bs-toggle="tab" href="#stationdetails" role="tab" aria-controls="stationdetails" aria-selected="false">
				<i class="fas fa-home me-1"></i><?php echo lang('cloudlog_station_profile'); ?>
			</a>
		</li>
		<?php if ($row->COL_NOTES != null) { ?>
		<li class="nav-item">
			<a id="notes-tab" class="nav-link" data-bs-toggle="tab" href="#notesdetails" role="tab" aria-controls="notesdetails" aria-selected="false">
				<i class="fas fa-sticky-note me-1"></i>Notes
			</a>
		</li>
		<?php } ?>
		<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<li <?php if (count($qslimages) == 0) { echo 'hidden '; } ?>class="qslcardtab nav-item">
				<a class="nav-link" id="qsltab" data-bs-toggle="tab" href="#qslcard" role="tab" aria-controls="qslcard" aria-selected="false">
					<i class="fas fa-image me-1"></i><?php echo lang('general_word_qslcard'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="qslmanagementtab" data-bs-toggle="tab" href="#qslupload" role="tab" aria-controls="qslupload" aria-selected="false">
					<i class="fas fa-images me-1"></i><?php echo lang('general_word_qslcard_management'); ?>
				</a>
			</li>
		<?php } ?>
		<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2) && ($row->COL_MODE == 'SSTV')) { ?>
			<li <?php if (count($sstvimages) == 0) { echo 'hidden '; } ?>class="sstvimagetab nav-item">
				<a class="nav-link" id="sstvtab" data-bs-toggle="tab" href="#sstvimage" role="tab" aria-controls="sstvimage" aria-selected="false">
					<i class="fas fa-tv me-1"></i><?php echo lang('general_word_sstvimages'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="sstvmanagementtab" data-bs-toggle="tab" href="#sstvupload" role="tab" aria-controls="sstvupload" aria-selected="false">
					<i class="fas fa-upload me-1"></i><?php echo lang('general_word_sstv_management'); ?>
				</a>
			</li>
		<?php } ?>
		<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<li <?php if ($row->eqsl_image_file == null) { echo 'hidden '; } ?>class="eqslcardtab nav-item">
				<a class="nav-link" id="eqsltab" data-bs-toggle="tab" href="#eqslcard" role="tab" aria-controls="eqslcard" aria-selected="false">
					<i class="fas fa-envelope-open-text me-1"></i><?php echo $this->lang->line('general_word_eqslcard'); ?>
				</a>
			</li>
		<?php } ?>
	</ul>

	<div class="tab-content qso-modal-body" id="myTabContent">
		<!-- Tab 1: QSO Details -->
		<div class="tab-pane fade show active" id="qsodetails" role="tabpanel" aria-labelledby="table-tab">
			<div class="row g-3 align-items-start">
				<!-- Left Column: Confirmation Strip + Metadata -->
				<div class="col-12 col-lg-6">
					<!-- 4-Tile Confirmation Strip -->
					<div class="qso-qsl-strip mb-3">
						<div class="qso-qsl-tile <?php echo $lotw_confirmed ? 'is-confirmed' : ''; ?>">
							<div class="qso-qsl-tile-name"><?php echo lang('lotw_short'); ?></div>
							<div class="qso-qsl-tile-status">
								<?php echo $lotw_confirmed ? '<i class="fas fa-check-circle me-1"></i>Confirmed' : '&mdash;'; ?>
							</div>
							<div class="qso-qsl-tile-date">
								<?php echo ($lotw_confirmed && $row->COL_LOTW_QSLRDATE != null) ? date($custom_date_format, strtotime($row->COL_LOTW_QSLRDATE)) : ($lotw_confirmed ? 'Confirmed' : 'Not matched'); ?>
							</div>
						</div>

						<div class="qso-qsl-tile <?php echo $qsl_tile_class; ?>">
							<div class="qso-qsl-tile-name">Paper QSL</div>
							<div class="qso-qsl-tile-status"><?php echo $qsl_status_text; ?></div>
							<div class="qso-qsl-tile-date"><?php echo htmlspecialchars($qsl_sub_text, ENT_QUOTES, 'UTF-8'); ?></div>
						</div>

						<div class="qso-qsl-tile <?php echo $eqsl_confirmed ? 'is-confirmed' : ''; ?>">
							<div class="qso-qsl-tile-name">eQSL</div>
							<div class="qso-qsl-tile-status">
								<?php echo $eqsl_confirmed ? '<i class="fas fa-check-circle me-1"></i>Confirmed' : '&mdash;'; ?>
							</div>
							<div class="qso-qsl-tile-date">
								<?php echo ($eqsl_confirmed && $row->COL_EQSL_QSLRDATE != null) ? date($custom_date_format, strtotime($row->COL_EQSL_QSLRDATE)) : ($eqsl_confirmed ? 'Confirmed' : 'Not matched'); ?>
							</div>
						</div>

						<div class="qso-qsl-tile <?php echo $clublog_confirmed ? 'is-confirmed' : ''; ?>">
							<div class="qso-qsl-tile-name">Club Log</div>
							<div class="qso-qsl-tile-status">
								<?php echo $clublog_confirmed ? '<i class="fas fa-check-circle me-1"></i>Confirmed' : '&mdash;'; ?>
							</div>
							<div class="qso-qsl-tile-date">
								<?php echo ($clublog_confirmed && $row->COL_CLUBLOG_QSO_DOWNLOAD_DATE != null) ? date($custom_date_format, strtotime($row->COL_CLUBLOG_QSO_DOWNLOAD_DATE)) : ($clublog_confirmed ? 'Confirmed' : 'Not matched'); ?>
							</div>
						</div>
					</div>

					<!-- Structured Metadata Card -->
					<div class="qso-meta-box">
						<?php if ($this->config->item('display_freq') == true && $row->COL_FREQ_RX != 0) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_frequency_rx'); ?></span>
							<span class="qso-meta-val font-monospace"><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ_RX); ?></span>
						</div>
						<?php } ?>

						<?php if ($row->COL_GRIDSQUARE != null) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key">Gridsquare</span>
							<span class="qso-meta-val d-flex flex-wrap align-items-center gap-2">
								<span class="qso-grid-chip"><?php echo htmlspecialchars(strtoupper($row->COL_GRIDSQUARE), ENT_QUOTES, 'UTF-8'); ?></span>
								<?php if ($distance !== null) { ?><span class="text-muted small"><?php echo htmlspecialchars($distance, ENT_QUOTES, 'UTF-8'); ?></span><?php } ?>
								<a href="javascript:spawnQrbCalculator('<?php echo htmlspecialchars($row->station_gridsquare, ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row->COL_GRIDSQUARE, ENT_QUOTES, 'UTF-8'); ?>')" class="text-primary" title="QRB Calculator"><i class="fas fa-globe"></i></a>
							</span>
						</div>
						<?php } ?>

						<?php if ($row->COL_VUCC_GRIDS != null) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key">Grids (Multi)</span>
							<span class="qso-meta-val d-flex flex-wrap align-items-center gap-2">
								<span class="qso-grid-chip"><?php echo htmlspecialchars(strtoupper($row->COL_VUCC_GRIDS), ENT_QUOTES, 'UTF-8'); ?></span>
								<?php if ($distance !== null && $row->COL_GRIDSQUARE == null) { ?><span class="text-muted small"><?php echo htmlspecialchars($distance, ENT_QUOTES, 'UTF-8'); ?></span><?php } ?>
								<a href="javascript:spawnQrbCalculator('<?php echo htmlspecialchars($row->station_gridsquare, ENT_QUOTES, 'UTF-8') . '\',\'' . htmlspecialchars($row->COL_VUCC_GRIDS, ENT_QUOTES, 'UTF-8'); ?>')" class="text-primary" title="QRB Calculator"><i class="fas fa-globe"></i></a>
							</span>
						</div>
						<?php } ?>

						<?php if ($row->COL_STATE != null || ($row->COL_CNTY != null && $row->COL_CNTY != ",")) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo htmlspecialchars($primary_subdivision, ENT_QUOTES, 'UTF-8'); ?></span>
							<span class="qso-meta-val">
								<?php echo htmlspecialchars((string)$row->COL_STATE, ENT_QUOTES, 'UTF-8'); ?>
								<?php if ($row->COL_CNTY != null && $row->COL_CNTY != ",") { ?>
									<?php if ($row->COL_STATE != null) { ?> &middot; <?php } ?>
									<span class="text-muted"><?php echo htmlspecialchars($row->COL_CNTY, ENT_QUOTES, 'UTF-8'); ?></span>
								<?php } ?>
							</span>
						</div>
						<?php } ?>

						<?php if ($row->COL_SAT_NAME != null || $row->COL_SAT_MODE != null) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_satellite_name'); ?></span>
							<span class="qso-meta-val">
								<?php if ($row->COL_SAT_NAME != null) { ?>
									<a href="https://db.satnogs.org/search/?q=<?php echo urlencode($row->COL_SAT_NAME); ?>" target="_blank" class="fw-semibold text-decoration-none"><?php echo htmlspecialchars($row->COL_SAT_NAME, ENT_QUOTES, 'UTF-8'); ?></a>
								<?php } ?>
								<?php if ($row->COL_SAT_MODE != null) { ?>
									<span class="badge text-bg-secondary ms-1"><?php echo (strlen($row->COL_SAT_MODE) == 2 ? (strtoupper($row->COL_SAT_MODE[0]).'/'.strtoupper($row->COL_SAT_MODE[1])) : strtoupper($row->COL_SAT_MODE)); ?></span>
								<?php } ?>
							</span>
						</div>
						<?php } ?>

						<?php if ($row->COL_ANT_AZ != null || $row->COL_ANT_EL != null) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key">Antenna Az / El</span>
							<span class="qso-meta-val d-flex flex-wrap gap-3">
								<?php if ($row->COL_ANT_AZ != null) { ?>
									<span><?php echo $row->COL_ANT_AZ; ?>&deg; <span style="margin-left: 2px; display: inline-block; transform: rotate(<?php echo (-45+$row->COL_ANT_AZ); ?>deg);"><i class="fas fa-location-arrow fa-xs"></i></span></span>
								<?php } ?>
								<?php if ($row->COL_ANT_EL != null) { ?>
									<span>El <?php echo $row->COL_ANT_EL; ?>&deg; <span style="margin-left: 2px; display: inline-block; transform: rotate(<?php echo (-$row->COL_ANT_EL); ?>deg);"><i class="fas fa-arrow-right fa-xs"></i></span></span>
								<?php } ?>
							</span>
						</div>
						<?php } ?>

						<?php if ($row->COL_CONTEST_ID != null || $row->COL_STX || $row->COL_SRX || $row->COL_STX_STRING || $row->COL_SRX_STRING) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('contesting_contest_name'); ?></span>
							<span class="qso-meta-val">
								<?php if ($row->COL_CONTEST_ID != null) { ?><strong><?php echo htmlspecialchars($row->COL_CONTEST_ID, ENT_QUOTES, 'UTF-8'); ?></strong><?php } ?>
								<?php
									$exch_parts = array();
									if ($row->COL_STX) { $exch_parts[] = 'STX ' . sprintf("%03d", $row->COL_STX); }
									if ($row->COL_STX_STRING) { $exch_parts[] = $row->COL_STX_STRING; }
									if ($row->COL_SRX) { $exch_parts[] = 'SRX ' . sprintf("%03d", $row->COL_SRX); }
									if ($row->COL_SRX_STRING) { $exch_parts[] = $row->COL_SRX_STRING; }
									if (!empty($exch_parts)) {
										echo ' <span class="text-muted small">(' . htmlspecialchars(implode(' / ', $exch_parts), ENT_QUOTES, 'UTF-8') . ')</span>';
									}
								?>
							</span>
						</div>
						<?php } ?>

						<?php if ($has_award_refs) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key">Awards / Refs</span>
							<span class="qso-meta-val d-flex flex-wrap gap-1">
								<?php if ($row->COL_IOTA != null) { ?>
									<a class="qso-ref-chip" href="https://www.iota-world.org/iotamaps/?grpref=<?php echo urlencode($row->COL_IOTA); ?>" target="_blank"><i class="fas fa-water"></i> <?php echo htmlspecialchars($row->COL_IOTA, ENT_QUOTES, 'UTF-8'); ?></a>
								<?php } ?>
								<?php if ($row->COL_SOTA_REF != null) { ?>
									<a class="qso-ref-chip" href="https://summits.sota.org.uk/summit/<?php echo urlencode($row->COL_SOTA_REF); ?>" target="_blank"><i class="fas fa-mountain"></i> <?php echo htmlspecialchars($row->COL_SOTA_REF, ENT_QUOTES, 'UTF-8'); ?></a>
								<?php } ?>
								<?php if ($row->COL_WWFF_REF != null) { ?>
									<a class="qso-ref-chip" href="https://www.cqgma.org/zinfo.php?ref=<?php echo urlencode($row->COL_WWFF_REF); ?>" target="_blank"><i class="fas fa-leaf"></i> <?php echo htmlspecialchars($row->COL_WWFF_REF, ENT_QUOTES, 'UTF-8'); ?></a>
								<?php } ?>
								<?php if ($row->COL_POTA_REF != null) {
									$pota_refs = array_filter(array_map('trim', explode(',', $row->COL_POTA_REF)));
									foreach ($pota_refs as $pota_ref) { ?>
										<a class="qso-ref-chip" href="https://pota.app/#/park/<?php echo urlencode($pota_ref); ?>" target="_blank"><i class="fas fa-tree"></i> <?php echo htmlspecialchars($pota_ref, ENT_QUOTES, 'UTF-8'); ?></a>
								<?php } } ?>
								<?php if ($row->COL_DARC_DOK != null) {
									$dok_url = null;
									if (preg_match('/^[A-Y]\d{2}$/', $row->COL_DARC_DOK)) {
										$dok_url = 'https://www.darc.de/' . $row->COL_DARC_DOK;
									} elseif (preg_match('/^DV[ABCDEFGHIKLMNOPQRSTUVWXY]$/', $row->COL_DARC_DOK)) {
										$dok_url = 'https://www.darc.de/der-club/distrikte/' . strtolower(substr($row->COL_DARC_DOK, 2, 1));
									} elseif (preg_match('/^Z\d{2}$/', $row->COL_DARC_DOK)) {
										$dok_url = 'https://' . $row->COL_DARC_DOK . '.vfdb.org';
									}
									if ($dok_url) { ?>
										<a class="qso-ref-chip" href="<?php echo $dok_url; ?>" target="_blank">DOK <?php echo htmlspecialchars($row->COL_DARC_DOK, ENT_QUOTES, 'UTF-8'); ?></a>
									<?php } else { ?>
										<span class="qso-ref-chip">DOK <?php echo htmlspecialchars($row->COL_DARC_DOK, ENT_QUOTES, 'UTF-8'); ?></span>
								<?php } } ?>
								<?php if ($row->COL_SIG != null || $row->COL_SIG_INFO != null) {
									$sig_label = trim(($row->COL_SIG ? $row->COL_SIG . ' ' : '') . ($row->COL_SIG_INFO ?? ''));
									if ($row->COL_SIG == 'GMA' && $row->COL_SIG_INFO) { ?>
										<a class="qso-ref-chip" href="https://www.cqgma.org/zinfo.php?ref=<?php echo urlencode($row->COL_SIG_INFO); ?>" target="_blank"><?php echo htmlspecialchars($sig_label, ENT_QUOTES, 'UTF-8'); ?></a>
									<?php } elseif ($row->COL_SIG == 'MQC' && $row->COL_SIG_INFO) { ?>
										<a class="qso-ref-chip" href="https://www.mountainqrp.it/awards/referenza.php?ref=<?php echo urlencode($row->COL_SIG_INFO); ?>" target="_blank"><?php echo htmlspecialchars($sig_label, ENT_QUOTES, 'UTF-8'); ?></a>
									<?php } else { ?>
										<span class="qso-ref-chip"><?php echo htmlspecialchars($sig_label, ENT_QUOTES, 'UTF-8'); ?></span>
								<?php } } ?>
							</span>
						</div>
						<?php } ?>

						<?php if ($can_edit && $row->COL_COMMENT != null) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('general_word_comment'); ?></span>
							<span class="qso-meta-val"><?php echo htmlspecialchars($row->COL_COMMENT, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<?php } ?>
					</div>

					<?php if ($row->lotwuser != null) { ?>
					<div class="small text-muted mt-2 d-flex align-items-center gap-1">
						<i class="fas fa-cloud-upload-alt text-primary"></i>
						<span><?php echo lang('lotw_user'); ?> <a href="https://lotw.arrl.org/lotwuser/act?act=<?php echo urlencode($row->COL_CALL); ?>" target="_blank"><?php echo lang('lotw_last_upload'); ?></a>: <?php echo date($custom_date_format, strtotime($row->lastupload)) . ' ' . date('H:i', strtotime($row->lastupload)); ?> UTC</span>
					</div>
					<?php } ?>
				</div>

				<!-- Right Column: Framed Map + Action Bar -->
				<div class="col-12 col-lg-6">
					<div class="qso-map-frame mb-3">
						<div id="mapqso" class="map-leaflet" style="width: 100%; height: 270px;"></div>
						<?php if ($grid_for_dist !== null) { ?>
						<div class="qso-map-pill">
							<i class="fas fa-location-dot text-danger me-1"></i><?php echo htmlspecialchars(strtoupper($grid_for_dist), ENT_QUOTES, 'UTF-8'); ?><?php if ($distance !== null) { ?> &middot; <?php echo htmlspecialchars($distance, ENT_QUOTES, 'UTF-8'); ?><?php } ?>
						</div>
						<?php } ?>
					</div>

					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
						<?php if ($can_edit) { ?>
						<div class="d-flex flex-wrap align-items-center gap-2">
							<span class="editButton"><a class="btn btn-sm btn-primary" href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>"><i class="fas fa-edit me-1"></i><?php echo lang('qso_btn_edit_qso'); ?></a></span>
							<form method="POST" action="<?php echo site_url('search'); ?>" class="m-0">
								<input type="hidden" value="<?php echo htmlspecialchars($call_upper, ENT_QUOTES, 'UTF-8'); ?>" name="callsign">
								<button class="btn btn-sm btn-outline-secondary" type="submit"><i class="fas fa-search me-1"></i><?php echo lang('general_more_qso'); ?></button>
							</form>
						</div>
						<?php } ?>

						<div class="d-inline-flex align-items-center gap-1" title="Share QSO">
							<a class="btn btn-sm btn-outline-secondary twitter-share-button" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $twitter_string; ?>" title="Share on X"><i class="fa-brands fa-x-twitter"></i></a>
							<?php if ($this->session->userdata('user_mastodon_url') != null) { ?>
								<a class="btn btn-sm btn-outline-secondary twitter-share-button" target="_blank" href="<?php echo $this->session->userdata('user_mastodon_url') . '/share?text=' . $twitter_string; ?>" title="Share on Mastodon"><i class="fa-brands fa-mastodon"></i></a>
							<?php } ?>
							<a class="btn btn-sm btn-outline-secondary twitter-share-button" target="_blank" href="https://bsky.app/intent/compose?text=<?php echo $twitter_string; ?>" title="Share on Bluesky"><i class="fa-brands fa-bluesky"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab 2: Station Profile -->
		<div class="tab-pane fade" id="stationdetails" role="tabpanel" aria-labelledby="station-tab">
			<div class="row g-3">
				<div class="col-12 col-md-6">
					<div class="qso-meta-box">
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_callsign'); ?></span>
							<span class="qso-meta-val font-monospace fw-bold"><?php echo htmlspecialchars((string)$row->station_callsign, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('general_word_name'); ?></span>
							<span class="qso-meta-val"><?php echo htmlspecialchars((string)$row->station_profile_name, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_gridsquare'); ?></span>
							<span class="qso-meta-val"><span class="qso-grid-chip"><?php echo htmlspecialchars(strtoupper((string)$row->station_gridsquare), ENT_QUOTES, 'UTF-8'); ?></span></span>
						</div>
						<?php if ($row->station_city) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('general_word_city'); ?></span>
							<span class="qso-meta-val"><?php echo htmlspecialchars($row->station_city, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<?php } ?>
						<?php if ($row->station_country) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('general_word_country'); ?></span>
							<span class="qso-meta-val">
								<?php echo ucwords(strtolower($row->station_country), "- (/"); ?>
								<?php if ($row->station_end != null) { echo ' <span class="badge text-bg-danger">' . lang('gen_hamradio_deleted_dxcc') . '</span>'; } ?>
							</span>
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="col-12 col-md-6">
					<div class="qso-meta-box">
						<?php if ($row->COL_OPERATOR) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_operator'); ?></span>
							<span class="qso-meta-val font-monospace fw-semibold"><?php echo htmlspecialchars($row->COL_OPERATOR, ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<?php } ?>
						<?php if ($row->COL_TX_PWR) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_transmit_power'); ?></span>
							<span class="qso-meta-val"><?php echo htmlspecialchars($row->COL_TX_PWR, ENT_QUOTES, 'UTF-8'); ?> W</span>
						</div>
						<?php } ?>
						<?php if ($row->COL_MY_IOTA) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_iota_reference'); ?></span>
							<span class="qso-meta-val"><span class="qso-ref-chip"><?php echo htmlspecialchars($row->COL_MY_IOTA, ENT_QUOTES, 'UTF-8'); ?></span></span>
						</div>
						<?php } ?>
						<?php if ($row->COL_MY_SOTA_REF) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_sota_reference'); ?></span>
							<span class="qso-meta-val"><span class="qso-ref-chip"><?php echo htmlspecialchars($row->COL_MY_SOTA_REF, ENT_QUOTES, 'UTF-8'); ?></span></span>
						</div>
						<?php } ?>
						<?php if ($row->COL_MY_WWFF_REF) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_wwff_reference'); ?></span>
							<span class="qso-meta-val"><span class="qso-ref-chip"><?php echo htmlspecialchars($row->COL_MY_WWFF_REF, ENT_QUOTES, 'UTF-8'); ?></span></span>
						</div>
						<?php } ?>
						<?php if ($row->COL_MY_POTA_REF) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key"><?php echo lang('gen_hamradio_pota_reference'); ?></span>
							<span class="qso-meta-val d-flex flex-wrap gap-1">
								<?php $my_pota_refs = array_filter(array_map('trim', explode(',', $row->COL_MY_POTA_REF))); ?>
								<?php foreach ($my_pota_refs as $pota_ref) { ?>
									<a class="qso-ref-chip" href="https://pota.app/#/park/<?php echo urlencode($pota_ref); ?>" target="_blank"><?php echo htmlspecialchars($pota_ref, ENT_QUOTES, 'UTF-8'); ?></a>
								<?php } ?>
							</span>
						</div>
						<?php } ?>
						<?php if ($row->station_wab) { ?>
						<div class="qso-meta-row">
							<span class="qso-meta-key">WAB Reference</span>
							<span class="qso-meta-val"><span class="qso-ref-chip"><?php echo htmlspecialchars($row->station_wab, ENT_QUOTES, 'UTF-8'); ?></span></span>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab 3: Notes -->
		<div class="tab-pane fade" id="notesdetails" role="tabpanel" aria-labelledby="notes-tab">
			<div class="qso-meta-box p-3">
				<div class="qso-meta-key mb-2"><?php echo lang('general_word_notes'); ?></div>
				<div><?php if (isset($row->COL_NOTES)) { echo nl2br($row->COL_NOTES); } ?></div>
			</div>
		</div>

		<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
		<!-- Tab: SSTV Management -->
		<div class="tab-pane fade" id="sstvupload" role="tabpanel" aria-labelledby="sstvmanagementtab">
			<?php
				if (count($sstvimages) > 0) {
				echo '<table style="width:100%" class="sstvtable table table-sm table-bordered table-hover table-striped table-condensed">
					<thead>
					<tr>
						<th style=\'text-align: center\'>SSTV image file</th>
						<th style=\'text-align: center\'></th>
						<th style=\'text-align: center\'></th>
					</tr>
					</thead><tbody>';

					foreach ($sstvimages as $sstv) {
					echo '<tr>';
						echo '<td style=\'text-align: center\'>' . $sstv->filename . '</td>';
						echo '<td id="'.$sstv->id.'" style=\'text-align: center\'><button onclick="deleteSstv('.$sstv->id.')" class="btn btn-sm btn-danger">Delete</button></td>';
						echo '<td style=\'text-align: center\'><button onclick="viewSstv(\''.$sstv->filename.'\')" class="btn btn-sm btn-success">View</button></td>';
						echo '</tr>';
					}

					echo '</tbody></table>';
				}
			?>
			<div class="alert alert-warning py-2 small" role="alert"><span class="badge text-bg-warning me-1"><?php echo lang('general_word_warning'); ?></span><?php echo lang('gen_max_file_upload_size'); ?> <?php echo $max_upload; ?>B.</div>
			<form class="form" id="sstvinfo" name="sstvinfo" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md">
						<fieldset>
							<div class="mb-3">
								<label for="sstvimages" class="form-label"><?php echo lang('general_sstv_upload'); ?></label>
								<input class="form-control form-control-sm" type="file" id="sstvimages" name="sstvimages[]" accept="image/*" multiple>
							</div>
							<input type="hidden" class="form-control" id="qsoinputid" name="qsoid" value="<?php echo $row->COL_PRIMARY_KEY; ?>">
							<button type="button" onclick="uploadSSTV();" id="button2id" name="button2id" class="btn btn-sm btn-primary"><?php echo lang('general_sstv_upload_button'); ?></button>
						</fieldset>
					</div>
				</div>
			</form>
		</div>

		<!-- Tab: QSL Management -->
		<div class="tab-pane fade" id="qslupload" role="tabpanel" aria-labelledby="qslmanagementtab">
			<?php
			if (count($qslimages) > 0) {
			echo '<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed mb-3">
				<thead>
				<tr>
					<th style=\'text-align: center\'>QSL image file</th>
					<th style=\'text-align: center\'></th>
					<th style=\'text-align: center\'></th>
				</tr>
				</thead><tbody>';

				foreach ($qslimages as $qsl) {
				echo '<tr>';
					echo '<td style=\'text-align: center\'>' . $qsl->filename . '</td>';
					echo '<td id="'.$qsl->id.'" style=\'text-align: center\'><button onclick="deleteQsl('.$qsl->id.')" class="btn btn-sm btn-danger">Delete</button></td>';
					echo '<td style=\'text-align: center\'><button onclick="viewQsl(\''.$qsl->filename.'\')" class="btn btn-sm btn-success">View</button></td>';
					echo '</tr>';
				}

				echo '</tbody></table>';
			}
			?>

			<div class="alert alert-warning py-2 small" role="alert"><span class="badge text-bg-warning me-1"><?php echo lang('general_word_warning'); ?></span> <?php echo lang('gen_max_file_upload_size'); ?> <?php echo $max_upload; ?>B.</div>

			<form class="form mb-3" id="fileinfo" name="fileinfo" enctype="multipart/form-data">
				<div class="row g-3">
					<div class="col-md-6">
						<label for="qslcardfront" class="form-label small fw-semibold"><?php echo lang('qslcard_upload_front'); ?></label>
						<input class="form-control form-control-sm" type="file" id="qslcardfront" name="qslcardfront" accept="image/*">
					</div>
					<div class="col-md-6">
						<label for="qslcardback" class="form-label small fw-semibold"><?php echo lang('qslcard_upload_back'); ?></label>
						<input class="form-control form-control-sm" type="file" id="qslcardback" name="qslcardback" accept="image/*">
					</div>
				</div>
				<input type="hidden" class="form-control" id="qsoinputid" name="qsoid" value="<?php echo $row->COL_PRIMARY_KEY; ?>">
				<button type="button" onclick="uploadQsl();" id="button1id" name="button1id" class="btn btn-sm btn-primary mt-2"><?php echo lang('qslcard_upload_button'); ?></button>
			</form>

			<div class="d-flex flex-wrap gap-2 mb-2">
				<button type="button" onclick="qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B');" id="qslrxb" name="qslrxb" class="btn btn-sm btn-success ld-ext-right ld-ext-right-r-B"><i class="fas fa-envelope me-1"></i><?php echo lang('general_mark_qsl_rx_bureau'); ?> <div class="ld ld-ring ld-spin"></div></button>
				<button type="button" onclick="qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D');" id="qslrxd" name="qslrxd" class="btn btn-sm btn-success ld-ext-right ld-ext-right-r-D"><i class="fas fa-envelope me-1"></i><?php echo lang('general_mark_qsl_rx_direct'); ?> <div class="ld ld-ring ld-spin"></div></button>
				<button type="button" onclick="qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'E');" id="qslrxe" name="qslrxe" class="btn btn-sm btn-success ld-ext-right ld-ext-right-r-E"><i class="fas fa-envelope me-1"></i><?php echo lang('general_mark_qsl_rx_electronic'); ?> <div class="ld ld-ring ld-spin"></div></button>
			</div>
			<div class="d-flex flex-wrap gap-2">
				<button type="button" onclick="qsl_requested(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B');" id="qsltxb" name="qsltxb" class="btn btn-sm btn-warning ld-ext-right ld-ext-right-t-B"><i class="fas fa-envelope me-1"></i><?php echo lang('general_mark_qsl_requested_bureau'); ?> <div class="ld ld-ring ld-spin"></div></button>
				<button type="button" onclick="qsl_requested(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D');" id="qsltxd" name="qsltxd" class="btn btn-sm btn-warning ld-ext-right ld-ext-right-t-D"><i class="fas fa-envelope me-1"></i><?php echo lang('general_mark_qsl_requested_direct'); ?> <div class="ld ld-ring ld-spin"></div></button>
				<button type="button" onclick="qsl_ignore(<?php echo $row->COL_PRIMARY_KEY; ?>, 'I');" id="qsltxi" name="qsltxi" class="btn btn-sm btn-warning ld-ext-right ld-ext-right-ignore"><i class="fas fa-envelope me-1"></i><?php echo lang('general_mark_qsl_not_required'); ?> <div class="ld ld-ring ld-spin"></div></button>
			</div>
		</div>

		<!-- Tab: QSL Card Carousel -->
		<div class="tab-pane fade" id="qslcard" role="tabpanel" aria-labelledby="qsltab">
			<?php $this->load->view('qslcard/qslcarousel', $qslimages); ?>
		</div>

		<!-- Tab: SSTV Carousel -->
		<div class="tab-pane fade" id="sstvimage" role="tabpanel" aria-labelledby="sstvtab">
			<?php $this->load->view('sstv/sstvcarousel', $sstvimages); ?>
		</div>

		<!-- Tab: eQSL Card -->
		<div class="tab-pane fade" id="eqslcard" role="tabpanel" aria-labelledby="eqsltab">
		<?php
			if ($row->eqsl_image_file != null) {
				echo '<img class="d-block img-fluid rounded" src="' . base_url() . '/images/eqsl_card_images/' . $row->eqsl_image_file . '" alt="eQSL picture">';
			}
		?>
		</div>
		<?php } ?>
	</div>
</div>

<?php
	if ($row->COL_GRIDSQUARE != null && strlen($row->COL_GRIDSQUARE) >= 4) {
		$stn_loc = $this->qra->qra2latlong(trim($row->COL_GRIDSQUARE));
		if ($stn_loc[0] != 0) {
			$lat = $stn_loc[0];
			$lng = $stn_loc[1];
		}
	} elseif ($row->COL_VUCC_GRIDS != null) {
		$grids = explode(",", $row->COL_VUCC_GRIDS);
		if (count($grids) == 2) {
			$grid1 = $this->qra->qra2latlong(trim($grids[0]));
			$grid2 = $this->qra->qra2latlong(trim($grids[1]));

			$coords[] = array('lat' => $grid1[0], 'lng' => $grid1[1]);
			$coords[] = array('lat' => $grid2[0], 'lng' => $grid2[1]);

			$midpoint = $this->qra->get_midpoint($coords);
			$lat = $midpoint[0];
			$lng = $midpoint[1];
		}
		if (count($grids) == 4) {
			$grid1 = $this->qra->qra2latlong(trim($grids[0]));
			$grid2 = $this->qra->qra2latlong(trim($grids[1]));
			$grid3 = $this->qra->qra2latlong(trim($grids[2]));
			$grid4 = $this->qra->qra2latlong(trim($grids[3]));

			$coords[] = array('lat' => $grid1[0], 'lng' => $grid1[1]);
			$coords[] = array('lat' => $grid2[0], 'lng' => $grid2[1]);
			$coords[] = array('lat' => $grid3[0], 'lng' => $grid3[1]);
			$coords[] = array('lat' => $grid4[0], 'lng' => $grid4[1]);

			$midpoint = $this->qra->get_midpoint($coords);
			$lat = $midpoint[0];
			$lng = $midpoint[1];
		}
	} else {
		if (isset($row->lat)) {
			$lat = $row->lat;
		} else {
			$lat = 0;
		}

		if (isset($row->long)) {
			$lng = $row->long;
		} else {
			$lng = 0;
		}
	}
?>

<script>
var lat = <?php echo $lat; ?>;
var long = <?php echo $lng; ?>;
var callsign = "<?php echo $row->COL_CALL; ?>";
</script>
	<div hidden id='lat'><?php echo $lat; ?></div>
	<div hidden id='long'><?php echo $lng; ?></div>
	<div hidden id='callsign'><?php echo $row->COL_CALL; ?></div>
	<div hidden id='qsoid'><?php echo $row->COL_PRIMARY_KEY; ?></div>
	<div hidden id='qso_map_grids'><?php echo !empty($row->COL_GRIDSQUARE) ? $row->COL_GRIDSQUARE : ($row->COL_VUCC_GRIDS ?? ''); ?></div>

<?php } } ?>
