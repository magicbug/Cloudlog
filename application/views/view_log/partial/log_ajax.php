<?php
if (!function_exists('echo_table_header_col')) {
	function echo_table_header_col($ctx, $name, $class = '') {
		$classAttr = $class !== '' ? ' class="' . $class . '"' : '';
		switch ($name) {
			case 'Mode': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_mode') . '</th>'; break;
			case 'RSTS': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_rsts') . '</th>'; break;
			case 'RSTR': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_rstr') . '</th>'; break;
			case 'Country': echo '<th' . $classAttr . '>' . $ctx->lang->line('general_word_country') . '</th>'; break;
			case 'IOTA': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_iota') . '</th>'; break;
			case 'SOTA': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_sota') . '</th>'; break;
			case 'WWFF': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_wwff') . '</th>'; break;
			case 'POTA': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_pota') . '</th>'; break;
			case 'State': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_state') . '</th>'; break;
			case 'Grid': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_gridsquare') . '</th>'; break;
			case 'Distance': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_distance') . '</th>'; break;
			case 'Band': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_band') . '</th>'; break;
			case 'Frequency': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_frequency') . '</th>'; break;
			case 'Operator': echo '<th' . $classAttr . '>' . $ctx->lang->line('gen_hamradio_operator') . '</th>'; break;
			case 'Location': echo '<th' . $classAttr . '>' . $ctx->lang->line('cloudlog_station_profile') . '</th>'; break;
			case 'Name': echo '<th' . $classAttr . '>' . $ctx->lang->line('general_word_name') . '</th>'; break;
			case 'Flag': echo '<th' . $classAttr . '>&nbsp;</th>'; break;
		}
	}
}

if (!function_exists('echo_table_col')) {
	function echo_table_col($row, $name, $class = '') {
		$classAttr = $class !== '' ? ' class="' . $class . '"' : '';
		$ci =& get_instance();
		$dxccName = property_exists($row, 'name') ? $row->name : null;
		$dxccEnd = property_exists($row, 'end') ? $row->end : null;
		switch ($name) {
			case 'Mode':
				echo '<td' . $classAttr . '>' . ($row->COL_SUBMODE == null ? $row->COL_MODE : $row->COL_SUBMODE) . '</td>';
				break;
			case 'RSTS':
				echo '<td' . $classAttr . '>' . $row->COL_RST_SENT;
				if ($row->COL_STX) {
					echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light border">';
					printf("%03d", $row->COL_STX);
					echo '</span>';
				}
				if ($row->COL_STX_STRING) {
					echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light border">' . $row->COL_STX_STRING . '</span>';
				}
				echo '</td>';
				break;
			case 'RSTR':
				echo '<td' . $classAttr . '>' . $row->COL_RST_RCVD;
				if ($row->COL_SRX) {
					echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light border">';
					printf("%03d", $row->COL_SRX);
					echo '</span>';
				}
				if ($row->COL_SRX_STRING) {
					echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light border">' . $row->COL_SRX_STRING . '</span>';
				}
				echo '</td>';
				break;
			case 'Country':
				echo '<td' . $classAttr . '>' . ucwords(strtolower(($dxccName == null ? "- NONE -" : $dxccName)));
				if ($dxccEnd != null) {
					echo ' <span class="badge text-bg-danger">' . $ci->lang->line('gen_hamradio_deleted_dxcc') . '</span>';
				}
				echo '</td>';
				break;
			case 'IOTA': echo '<td' . $classAttr . '>' . ($row->COL_IOTA) . '</td>'; break;
			case 'SOTA': echo '<td' . $classAttr . '>' . ($row->COL_SOTA_REF) . '</td>'; break;
			case 'WWFF': echo '<td' . $classAttr . '>' . ($row->COL_WWFF_REF) . '</td>'; break;
			case 'POTA': echo '<td' . $classAttr . '>' . ($row->COL_POTA_REF) . '</td>'; break;
			case 'Grid':
				echo '<td' . $classAttr . '>';
				echoQrbCalcLink($row->station_gridsquare, $row->COL_VUCC_GRIDS, $row->COL_GRIDSQUARE);
				echo '</td>';
				break;
			case 'Distance':
				echo '<td' . $classAttr . '><span data-bs-toggle="tooltip" title="' . htmlspecialchars(qso_distance_tooltip($row), ENT_QUOTES, 'UTF-8') . '">' . qso_format_distance($row) . '</span></td>';
				break;
			case 'Band':
				echo '<td' . $classAttr . '>';
				if ($row->COL_SAT_NAME != null) {
					echo '<a href="https://db.satnogs.org/search/?q=' . $row->COL_SAT_NAME . '" target="_blank" rel="noopener"><span data-bs-toggle="tooltip" title="' . $row->COL_BAND . '">' . $row->COL_SAT_NAME . '</span></a>';
				} else {
					if ($row->COL_FREQ != null) {
						echo '<span data-bs-toggle="tooltip" title="' . $ci->frequency->hz_to_mhz($row->COL_FREQ) . '">' . strtolower($row->COL_BAND) . '</span>';
					} else {
						echo strtolower($row->COL_BAND);
					}
				}
				echo '</td>';
				break;
			case 'Frequency':
				echo '<td' . $classAttr . '>';
				if ($row->COL_SAT_NAME != null) {
					echo '<a href="https://db.satnogs.org/search/?q=' . $row->COL_SAT_NAME . '" target="_blank" rel="noopener">';
					if ($row->COL_FREQ != null) {
						echo '<span data-bs-toggle="tooltip" title="' . $ci->frequency->hz_to_mhz($row->COL_FREQ) . '">' . $row->COL_SAT_NAME . '</span>';
					} else {
						echo $row->COL_SAT_NAME;
					}
					echo '</a>';
				} else {
					if ($row->COL_FREQ != null) {
						echo '<span data-bs-toggle="tooltip" title="' . $row->COL_BAND . '">' . $ci->frequency->hz_to_mhz($row->COL_FREQ) . '</span>';
					} else {
						echo strtolower($row->COL_BAND);
					}
				}
				echo '</td>';
				break;
			case 'State': echo '<td' . $classAttr . '>' . ($row->COL_STATE) . '</td>'; break;
			case 'Operator': echo '<td' . $classAttr . '>' . ($row->COL_OPERATOR) . '</td>'; break;
			case 'Location': echo '<td' . $classAttr . '>' . ($row->station_profile_name) . '</td>'; break;
			case 'Name': echo '<td' . $classAttr . '>' . ($row->COL_NAME) . '</td>'; break;
			case 'Flag':
				$ci->load->library('DxccFlag');
				$flag = strtolower($ci->dxccflag->getISO($row->COL_DXCC));
				echo '<td' . $classAttr . '><span data-bs-toggle="tooltip" title="' . ucwords(strtolower(($dxccName == null ? "- NONE -" : $dxccName))) . '"><span class="fi fi-' . $flag . '"></span></span></td>';
				break;
		}
	}
}

if (!function_exists('echoQrbCalcLink')) {
	function echoQrbCalcLink($mygrid, $grid, $vucc) {
		if (!empty($grid)) {
			echo $grid . ' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $grid . '\')"><i class="fas fa-globe"></i></a>';
		} else if (!empty($vucc)) {
			echo $vucc . ' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $vucc . '\')"><i class="fas fa-globe"></i></a>';
		}
	}
}

$this->load->library('DxccFlag');
$can_edit = (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2));
$show_time = (($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time')));
$custom_date_format = $this->session->userdata('user_date_format')
	? $this->session->userdata('user_date_format')
	: $this->config->item('qso_date_format');

$col1 = $this->session->userdata('user_column1') == "" ? 'Mode' : $this->session->userdata('user_column1');
$col2 = $this->session->userdata('user_column2') == "" ? 'RSTS' : $this->session->userdata('user_column2');
$col3 = $this->session->userdata('user_column3') == "" ? 'RSTR' : $this->session->userdata('user_column3');
$col4 = $this->session->userdata('user_column4') == "" ? 'Band' : $this->session->userdata('user_column4');
$col5 = $this->session->userdata('user_column5');
?>

<style>
.contacttable {
	font-size: 0.88rem;
	font-variant-numeric: tabular-nums;
	margin-bottom: 0;
}

.contacttable thead th {
	font-size: 0.74rem;
	text-transform: uppercase;
	letter-spacing: 0.03em;
	font-weight: 600;
	padding: 0.55rem 0.65rem;
	white-space: nowrap;
	vertical-align: middle;
}

.contacttable tbody td {
	padding: 0.44rem 0.65rem;
	vertical-align: middle;
	white-space: nowrap;
}

@media (max-width: 575.98px) {
	.contacttable thead th,
	.contacttable tbody td {
		padding-left: 0.45rem;
		padding-right: 0.45rem;
	}
}

.contacttable .logbook-call-cell {
	display: inline-flex;
	align-items: center;
	gap: 0.4rem;
	flex-wrap: nowrap;
}

.contacttable .logbook-callsign-link {
	font-weight: 600;
	letter-spacing: 0.02em;
}

.contacttable .lotw-user-badge {
	font-size: 0.68rem;
	padding: 0.18em 0.42em;
	line-height: 1;
	vertical-align: middle;
}

.contacttable td.qsl,
.contacttable td.eqsl,
.contacttable td.lotw,
.contacttable td.qrz {
	width: auto;
	white-space: nowrap;
	letter-spacing: 0.08em;
}

.contacttable .qsl-red,
.contacttable .eqsl-red,
.contacttable .lotw-red,
.contacttable .qrz-red {
	color: rgba(127, 127, 127, 0.34) !important;
	font-size: 0.92em;
}

.contacttable .qsl-green,
.contacttable .eqsl-green,
.contacttable .lotw-green,
.contacttable .qrz-green,
.contacttable .qsl-yellow,
.contacttable .qsl-grey {
	font-size: 0.96em;
}

.contacttable .logbook-station-badge {
	display: inline-block;
	font-size: 0.75rem;
	font-weight: 600;
	padding: 0.2em 0.55em;
	border-radius: 0.35rem;
	border: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.3));
	background-color: rgba(127, 127, 127, 0.08);
	color: inherit;
}

.contacttable .logbook-action-btn {
	padding: 0.2rem 0.45rem;
	line-height: 1.2;
	white-space: nowrap;
	border-color: var(--bs-border-color, rgba(127, 127, 127, 0.35));
}

.table-responsive > table.contacttable .dropdown {
	position: relative !important;
}

.table-responsive > table.contacttable .dropdown > .dropdown-menu {
	left: auto !important;
	right: 0 !important;
	top: 100% !important;
}

.logbook-pagination-bar {
	padding: 0.75rem 1rem;
	border-top: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.2));
}

.logbook-pagination-bar .pagination {
	margin-bottom: 0;
	flex-wrap: wrap;
	gap: 0.15rem;
}
</style>

<?php if ($results) { ?>
<div class="table-responsive">
	<table style="width:100%" class="table contacttable table-striped table-hover align-middle">
		<thead>
			<tr class="titles">
				<th><?php echo lang('general_word_date'); ?></th>
				<?php if ($show_time) { ?>
					<th class="d-none d-sm-table-cell"><?php echo lang('general_word_time'); ?></th>
				<?php } ?>
				<th><?php echo lang('gen_hamradio_call'); ?></th>
				<?php
				echo_table_header_col($this, $col1);
				echo_table_header_col($this, $col2, 'd-none d-lg-table-cell');
				echo_table_header_col($this, $col3, 'd-none d-lg-table-cell');
				echo_table_header_col($this, $col4);
				if ($col5 !== null && $col5 !== '') {
					echo_table_header_col($this, $col5, 'd-none d-xl-table-cell');
				}
				if ($can_edit) { ?>
					<th class="d-none d-md-table-cell">QSL</th>
					<?php if ($this->session->userdata('user_eqsl_name') != "") { ?>
						<th class="d-none d-md-table-cell">eQSL</th>
					<?php } ?>
					<?php if ($this->session->userdata('user_lotw_name') != "") { ?>
						<th class="d-none d-md-table-cell">LoTW</th>
					<?php } ?>
					<?php if ($this->session->userdata('hasQrzKey') != "") { ?>
						<th class="d-none d-md-table-cell">QRZ</th>
					<?php } ?>
				<?php } ?>
				<th class="d-none d-md-table-cell"><?php echo lang('gen_hamradio_station'); ?></th>
				<?php if ($can_edit) { ?>
					<th class="text-end"><span class="visually-hidden">Actions</span></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		$rows = $results->result();
		if (empty($rows)) { ?>
			<tr>
				<td colspan="14" class="text-center text-muted py-4">
					<i class="fas fa-book-open me-1"></i> No QSOs found.
				</td>
			</tr>
		<?php }
		foreach ($rows as $row) {
			$timestamp = strtotime($row->COL_TIME_ON);
			echo '<tr class="tr' . ($i & 1) . '" id="qso_' . $row->COL_PRIMARY_KEY . '">';
		?>
			<td><?php echo date($custom_date_format, $timestamp); ?></td>
			<?php if ($show_time) { ?>
				<td class="d-none d-sm-table-cell text-muted"><?php echo date('H:i', $timestamp); ?></td>
			<?php } ?>
			<td>
				<span class="logbook-call-cell">
					<a class="logbook-callsign-link" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0", "&Oslash;", strtoupper($row->COL_CALL)); ?></a>
					<?php
					if (isset($row->lastupload) && ($row->lastupload) && isset($row->callsign) && $row->callsign != '') {
						$lotw_hint = '';
						$diff = (time() - strtotime($row->lastupload)) / 86400;
						if ($diff > 365) {
							$lotw_hint = ' lotw_info_red';
						} elseif ($diff > 30) {
							$lotw_hint = ' lotw_info_orange';
						} elseif ($diff > 7) {
							$lotw_hint = ' lotw_info_yellow';
						}
						$lotw_ts = strtotime($row->lastupload);
						echo '<a href="https://lotw.arrl.org/lotwuser/act?act=' . urlencode($row->COL_CALL) . '" target="_blank" rel="noopener"><span class="badge text-bg-success lotw-user-badge' . $lotw_hint . '" data-bs-toggle="tooltip" title="LoTW User. Last upload was ' . date($custom_date_format . " H:i", $lotw_ts) . '">L</span></a>';
					}
					?>
				</span>
			</td>
			<?php
			echo_table_col($row, $col1);
			echo_table_col($row, $col2, 'd-none d-lg-table-cell');
			echo_table_col($row, $col3, 'd-none d-lg-table-cell');
			echo_table_col($row, $col4);
			if ($col5 !== null && $col5 !== '') {
				echo_table_col($row, $col5, 'd-none d-xl-table-cell');
			}

			if ($can_edit) {
				// QSL Sent status
				$qsl_sent_class = 'qsl-red';
				$qsl_sent_title = 'QSL Not ' . lang('general_word_sent');
				if ($row->COL_QSL_SENT != "N" && $row->COL_QSL_SENT != "") {
					switch ($row->COL_QSL_SENT) {
						case "Y": $qsl_sent_class = 'qsl-green'; $qsl_sent_title = 'QSL ' . lang('general_word_sent'); break;
						case "Q": $qsl_sent_class = 'qsl-yellow'; $qsl_sent_title = 'QSL ' . lang('general_word_queued'); break;
						case "R": $qsl_sent_class = 'qsl-yellow'; $qsl_sent_title = 'QSL ' . lang('general_word_requested'); break;
						case "I": $qsl_sent_class = 'qsl-grey'; $qsl_sent_title = 'QSL ' . lang('general_word_invalid_ignore'); break;
					}
					if ($row->COL_QSLSDATE != null) {
						$qsl_s_ts = strtotime($row->COL_QSLSDATE);
						if ($qsl_s_ts) {
							$qsl_sent_title .= ' ' . date($custom_date_format, $qsl_s_ts);
						}
					}
				}
				if ($row->COL_QSL_SENT_VIA != "") {
					switch ($row->COL_QSL_SENT_VIA) {
						case "B": $qsl_sent_title .= ' (' . lang('general_word_qslcard_bureau') . ')'; break;
						case "D": $qsl_sent_title .= ' (' . lang('general_word_qslcard_direct') . ')'; break;
						case "M": $qsl_sent_title .= ' (' . lang('general_word_qslcard_via') . ': ' . ($row->COL_QSL_VIA != "" ? $row->COL_QSL_VIA : "n/a") . ')'; break;
						case "E": $qsl_sent_title .= ' (' . lang('general_word_qslcard_electronic') . ')'; break;
					}
				}

				// QSL Rcvd status
				$qsl_rcvd_class = 'qsl-red';
				$qsl_rcvd_title = 'QSL Not ' . lang('general_word_received');
				if ($row->COL_QSL_RCVD != "N" && $row->COL_QSL_RCVD != "") {
					switch ($row->COL_QSL_RCVD) {
						case "Y": $qsl_rcvd_class = 'qsl-green'; $qsl_rcvd_title = 'QSL ' . lang('general_word_received'); break;
						case "Q": $qsl_rcvd_class = 'qsl-yellow'; $qsl_rcvd_title = 'QSL ' . lang('general_word_queued'); break;
						case "R": $qsl_rcvd_class = 'qsl-yellow'; $qsl_rcvd_title = 'QSL ' . lang('general_word_requested'); break;
						case "I": $qsl_rcvd_class = 'qsl-grey'; $qsl_rcvd_title = 'QSL ' . lang('general_word_invalid_ignore'); break;
					}
					if ($row->COL_QSLRDATE != null) {
						$qsl_r_ts = strtotime($row->COL_QSLRDATE);
						if ($qsl_r_ts) {
							$qsl_rcvd_title .= ' ' . date($custom_date_format, $qsl_r_ts);
						}
					}
				}
				if ($row->COL_QSL_RCVD_VIA != "") {
					switch ($row->COL_QSL_RCVD_VIA) {
						case "B": $qsl_rcvd_title .= ' (' . lang('general_word_qslcard_bureau') . ')'; break;
						case "D": $qsl_rcvd_title .= ' (' . lang('general_word_qslcard_direct') . ')'; break;
						case "M": $qsl_rcvd_title .= ' (Manager)'; break;
						case "E": $qsl_rcvd_title .= ' (' . lang('general_word_qslcard_electronic') . ')'; break;
					}
				}
			?>
				<td id="qsl_<?php echo $row->COL_PRIMARY_KEY; ?>" class="qsl d-none d-md-table-cell">
					<span class="<?php echo $qsl_sent_class; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($qsl_sent_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($qsl_sent_title, ENT_QUOTES, 'UTF-8'); ?>">&#9650;</span>
					<span class="<?php echo $qsl_rcvd_class; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($qsl_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($qsl_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>">&#9660;</span>
				</td>

				<?php if ($this->session->userdata('user_eqsl_name') != "") {
					$eqsl_sent_title = lang('eqsl_short') . ' ' . ($row->COL_EQSL_QSL_SENT == 'Y' ? lang('general_word_sent') : 'Not ' . lang('general_word_sent'));
					if ($row->COL_EQSL_QSL_SENT == 'Y' && $row->COL_EQSL_QSLSDATE != null && strtotime($row->COL_EQSL_QSLSDATE)) {
						$eqsl_sent_title .= ' ' . date($custom_date_format, strtotime($row->COL_EQSL_QSLSDATE));
					}
					$eqsl_rcvd_title = lang('eqsl_short') . ' ' . ($row->COL_EQSL_QSL_RCVD == 'Y' ? lang('general_word_received') : 'Not ' . lang('general_word_received'));
					if ($row->COL_EQSL_QSL_RCVD == 'Y' && $row->COL_EQSL_QSLRDATE != null && strtotime($row->COL_EQSL_QSLRDATE)) {
						$eqsl_rcvd_title .= ' ' . date($custom_date_format, strtotime($row->COL_EQSL_QSLRDATE));
					}
				?>
					<td class="eqsl d-none d-md-table-cell">
						<span class="eqsl-<?php echo ($row->COL_EQSL_QSL_SENT == 'Y') ? 'green' : 'red'; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($eqsl_sent_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($eqsl_sent_title, ENT_QUOTES, 'UTF-8'); ?>">&#9650;</span>
						<span class="eqsl-<?php echo ($row->COL_EQSL_QSL_RCVD == 'Y') ? 'green' : 'red'; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($eqsl_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($eqsl_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>">
							<?php if ($row->COL_EQSL_QSL_RCVD == 'Y') { ?>
								<a class="eqsl-green" href="<?php echo site_url("eqsl/image/" . $row->COL_PRIMARY_KEY); ?>" data-fancybox="images" data-width="528" data-height="336">&#9660;</a>
							<?php } else { ?>
								&#9660;
							<?php } ?>
						</span>
					</td>
				<?php } ?>

				<?php if ($this->session->userdata('user_lotw_name') != "") {
					$lotw_sent_title = lang('lotw_short') . ' ' . ($row->COL_LOTW_QSL_SENT == 'Y' ? lang('general_word_sent') : 'Not ' . lang('general_word_sent'));
					if ($row->COL_LOTW_QSL_SENT == 'Y' && $row->COL_LOTW_QSLSDATE != null && strtotime($row->COL_LOTW_QSLSDATE)) {
						$lotw_sent_title .= ' ' . date($custom_date_format, strtotime($row->COL_LOTW_QSLSDATE));
					}
					$lotw_rcvd_title = lang('lotw_short') . ' ' . ($row->COL_LOTW_QSL_RCVD == 'Y' ? lang('general_word_received') : 'Not ' . lang('general_word_received'));
					if ($row->COL_LOTW_QSL_RCVD == 'Y' && $row->COL_LOTW_QSLRDATE != null && strtotime($row->COL_LOTW_QSLRDATE)) {
						$lotw_rcvd_title .= ' ' . date($custom_date_format, strtotime($row->COL_LOTW_QSLRDATE));
					}
				?>
					<td class="lotw d-none d-md-table-cell">
						<span class="lotw-<?php echo ($row->COL_LOTW_QSL_SENT == 'Y') ? 'green' : 'red'; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($lotw_sent_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($lotw_sent_title, ENT_QUOTES, 'UTF-8'); ?>">&#9650;</span>
						<span class="lotw-<?php echo ($row->COL_LOTW_QSL_RCVD == 'Y') ? 'green' : 'red'; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($lotw_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($lotw_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>">&#9660;</span>
					</td>
				<?php } ?>

				<?php if ($this->session->userdata('hasQrzKey') != "") {
					$qrz_sent_title = 'QRZ ' . ($row->COL_QRZCOM_QSO_UPLOAD_STATUS == 'Y' ? lang('general_word_sent') : 'Not ' . lang('general_word_sent'));
					if ($row->COL_QRZCOM_QSO_UPLOAD_STATUS == 'Y' && $row->COL_QRZCOM_QSO_UPLOAD_DATE != null && strtotime($row->COL_QRZCOM_QSO_UPLOAD_DATE)) {
						$qrz_sent_title .= ' ' . date($custom_date_format, strtotime($row->COL_QRZCOM_QSO_UPLOAD_DATE));
					}
					$qrz_rcvd_title = 'QRZ ' . ($row->COL_QRZCOM_QSO_DOWNLOAD_STATUS == 'Y' ? lang('general_word_received') : 'Not ' . lang('general_word_received'));
					if ($row->COL_QRZCOM_QSO_DOWNLOAD_STATUS == 'Y' && $row->COL_QRZCOM_QSO_DOWNLOAD_DATE != null && strtotime($row->COL_QRZCOM_QSO_DOWNLOAD_DATE)) {
						$qrz_rcvd_title .= ' ' . date($custom_date_format, strtotime($row->COL_QRZCOM_QSO_DOWNLOAD_DATE));
					}
				?>
					<td class="qrz d-none d-md-table-cell">
						<span class="qrz-<?php echo ($row->COL_QRZCOM_QSO_UPLOAD_STATUS == 'Y') ? 'green' : 'red'; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($qrz_sent_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($qrz_sent_title, ENT_QUOTES, 'UTF-8'); ?>">&#9650;</span>
						<span class="qrz-<?php echo ($row->COL_QRZCOM_QSO_DOWNLOAD_STATUS == 'Y') ? 'green' : 'red'; ?>" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($qrz_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>" aria-label="<?php echo htmlspecialchars($qrz_rcvd_title, ENT_QUOTES, 'UTF-8'); ?>">&#9660;</span>
					</td>
				<?php } ?>
			<?php } ?>

			<?php if (isset($row->station_callsign)) { ?>
				<td class="d-none d-md-table-cell">
					<span class="logbook-station-badge"><?php echo htmlspecialchars($row->station_callsign, ENT_QUOTES, 'UTF-8'); ?></span>
				</td>
			<?php } ?>

			<?php if ($can_edit) { ?>
				<td class="text-end">
					<div class="dropdown">
						<button class="btn btn-sm btn-outline-secondary dropdown-toggle logbook-action-btn" type="button" aria-expanded="false" aria-label="Actions for <?php echo htmlspecialchars(strtoupper($row->COL_CALL), ENT_QUOTES, 'UTF-8'); ?>">
							<i class="fas fa-cog"></i>
						</button>

						<div class="dropdown-menu dropdown-menu-end menuOnResultTab" data-qsoid="qso_<?php echo $row->COL_PRIMARY_KEY; ?>">
							<a class="dropdown-item" href="javascript:qso_edit(<?php echo $row->COL_PRIMARY_KEY; ?>)"><i class="fas fa-edit me-2"></i><?php echo lang('general_edit_qso'); ?></a>

							<?php if ($row->COL_QSL_SENT != 'Y') { ?>
								<div class="qsl_sent_<?php echo $row->COL_PRIMARY_KEY; ?>">
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="javascript:qsl_sent(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B')"><i class="fas fa-envelope me-2"></i><?php echo lang('general_mark_qsl_tx_bureau'); ?></a>
									<a class="dropdown-item" href="javascript:qsl_sent(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')"><i class="fas fa-envelope me-2"></i><?php echo lang('general_mark_qsl_tx_direct'); ?></a>
									<a class="dropdown-item" href="javascript:qsl_requested(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')"><i class="fas fa-envelope me-2"></i><?php echo lang('general_mark_qsl_requested'); ?></a>
									<a class="dropdown-item" href="javascript:qsl_ignore(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')"><i class="fas fa-envelope me-2"></i><?php echo lang('general_mark_qsl_not_required'); ?></a>
								</div>
							<?php } ?>

							<?php if ($row->COL_QSL_RCVD != 'Y') { ?>
								<div class="qsl_rcvd_<?php echo $row->COL_PRIMARY_KEY; ?>">
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B')"><i class="fas fa-envelope me-2"></i><?php echo lang('general_mark_qsl_rx_bureau'); ?></a>
									<a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')"><i class="fas fa-envelope me-2"></i><?php echo lang('general_mark_qsl_rx_direct'); ?></a>
								</div>
							<?php } ?>

							<div class="dropdown-divider"></div>

							<a class="dropdown-item" href="https://www.qrz.com/db/<?php echo urlencode($row->COL_CALL); ?>" target="_blank" rel="noopener"><i class="fas fa-search me-2"></i><?php echo lang('general_lookup_qrz'); ?></a>
							<a class="dropdown-item" href="https://www.hamqth.com/<?php echo urlencode($row->COL_CALL); ?>" target="_blank" rel="noopener"><i class="fas fa-search me-2"></i><?php echo lang('general_lookup_hamqth'); ?></a>

							<div class="dropdown-divider"></div>

							<a class="dropdown-item text-danger" href="javascript:qso_delete(<?php echo $row->COL_PRIMARY_KEY; ?>, '<?php echo htmlspecialchars($row->COL_CALL, ENT_QUOTES, 'UTF-8'); ?>')"><i class="fas fa-trash-alt me-2"></i><?php echo lang('general_delete_qso'); ?></a>
						</div>
					</div>
				</td>
			<?php } ?>
			</tr>
		<?php $i++; } ?>
		</tbody>
	</table>
</div>
<?php } ?>

<?php if (isset($this->pagination)) {
	$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
	$config['full_tag_close'] = '</ul>';
	$config['attributes'] = ['class' => 'page-link'];
	$config['first_link'] = '&laquo; First';
	$config['last_link'] = 'Last &raquo;';
	$config['first_tag_open'] = '<li class="page-item">';
	$config['first_tag_close'] = '</li>';
	$config['prev_link'] = '&lsaquo;';
	$config['prev_tag_open'] = '<li class="page-item">';
	$config['prev_tag_close'] = '</li>';
	$config['next_link'] = '&rsaquo;';
	$config['next_tag_open'] = '<li class="page-item">';
	$config['next_tag_close'] = '</li>';
	$config['last_tag_open'] = '<li class="page-item">';
	$config['last_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li class="page-item active" aria-current="page"><span class="page-link">';
	$config['cur_tag_close'] = '</span></li>';
	$config['num_tag_open'] = '<li class="page-item">';
	$config['num_tag_close'] = '</li>';
	$this->pagination->initialize($config);
	$pagination_links = $this->pagination->create_links();
	if (!empty($pagination_links)) {
		$has_range = isset($total_qsos) && (int) $total_qsos > 0 && isset($results) && is_object($results) && $results->num_rows() > 0;
		$p_offset = isset($offset) ? (int) $offset : 0;
		$p_rows = $has_range ? (int) $results->num_rows() : 0;
?>
	<div class="logbook-pagination-bar d-flex flex-wrap align-items-center justify-content-between gap-2">
		<?php if ($has_range) { ?>
			<small class="text-muted">Showing <?php echo number_format($p_offset + 1); ?>&ndash;<?php echo number_format($p_offset + $p_rows); ?> of <?php echo number_format((int) $total_qsos); ?> QSOs</small>
		<?php } else { ?>
			<span></span>
		<?php } ?>
		<nav aria-label="Logbook pagination">
			<?php echo $pagination_links; ?>
		</nav>
	</div>
<?php }
} ?>
