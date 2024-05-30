<?php
function echo_table_header_col($ctx, $name)
{
	switch ($name) {
		case 'Mode':
			echo '<th>' . $ctx->lang->line('gen_hamradio_mode') . '</th>';
			break;
		case 'RSTS':
			echo '<th class="d-none d-sm-table-cell">' . $ctx->lang->line('gen_hamradio_rsts') . '</th>';
			break;
		case 'RSTR':
			echo '<th class="d-none d-sm-table-cell">' . $ctx->lang->line('gen_hamradio_rstr') . '</th>';
			break;
		case 'Country':
			echo '<th>' . $ctx->lang->line('general_word_country') . '</th>';
			break;
		case 'IOTA':
			echo '<th>' . $ctx->lang->line('gen_hamradio_iota') . '</th>';
			break;
		case 'SOTA':
			echo '<th>' . $ctx->lang->line('gen_hamradio_sota') . '</th>';
			break;
		case 'WWFF':
			echo '<th>' . $ctx->lang->line('gen_hamradio_wwff') . '</th>';
			break;
		case 'POTA':
			echo '<th>' . $ctx->lang->line('gen_hamradio_pota') . '</th>';
			break;
		case 'State':
			echo '<th>' . $ctx->lang->line('gen_hamradio_state') . '</th>';
			break;
		case 'Grid':
			echo '<th>' . $ctx->lang->line('gen_hamradio_gridsquare') . '</th>';
			break;
		case 'Distance':
			echo '<th>' . $ctx->lang->line('gen_hamradio_distance') . '</th>';
			break;
		case 'Band':
			echo '<th>' . $ctx->lang->line('gen_hamradio_band') . '</th>';
			break;
		case 'Frequency':
			echo '<th>' . $ctx->lang->line('gen_hamradio_frequency') . '</th>';
			break;
		case 'Operator':
			echo '<th>' . $ctx->lang->line('gen_hamradio_operator') . '</th>';
			break;
		case 'Name':
			echo '<th>' . $ctx->lang->line('general_word_name') . '</th>';
			break;
		case 'Flag':
			echo '<th>&nbsp;</th>';
			break;
	}
}

function echo_table_col($row, $name)
{
	$ci = &get_instance();
	switch ($name) {
		case 'Mode':
			echo '<td>';
			echo $row->COL_SUBMODE == null ? $row->COL_MODE : $row->COL_SUBMODE . '</td>';
			break;
		case 'RSTS':
			echo '<td class="d-none d-sm-table-cell">' . $row->COL_RST_SENT;
			if ($row->COL_STX) {
				echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light">';
				printf("%03d", $row->COL_STX);
				echo '</span>';
			}
			if ($row->COL_STX_STRING) {
				echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light">' . $row->COL_STX_STRING . '</span>';
			}
			echo '</td>';
			break;
		case 'RSTR':
			echo '<td class="d-none d-sm-table-cell">' . $row->COL_RST_RCVD;
			if ($row->COL_SRX) {
				echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light">';
				printf("%03d", $row->COL_SRX);
				echo '</span>';
			}
			if ($row->COL_SRX_STRING) {
				echo ' <span data-bs-toggle="tooltip" title="' . ($row->COL_CONTEST_ID != "" ? $row->COL_CONTEST_ID : "n/a") . '" class="badge text-bg-light">' . $row->COL_SRX_STRING . '</span>';
			}
			echo '</td>';
			break;
		case 'Country':
			echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));
			if ($row->end != NULL) echo ' <span class="badge text-bg-danger">' . $ci->lang->line('gen_hamradio_deleted_dxcc') . '</span>'  . '</td>';
			break;
		case 'IOTA':
			echo '<td>' . ($row->COL_IOTA) . '</td>';
			break;
		case 'SOTA':
			echo '<td>' . ($row->COL_SOTA_REF) . '</td>';
			break;
		case 'WWFF':
			echo '<td>' . ($row->COL_WWFF_REF) . '</td>';
			break;
		case 'POTA':
			echo '<td>' . ($row->COL_POTA_REF) . '</td>';
			break;
		case 'Grid':
			echo '<td>';
			echoQrbCalcLink($row->station_gridsquare, $row->COL_VUCC_GRIDS, $row->COL_GRIDSQUARE);
			echo '</td>';
			break;
		case 'Distance':
			echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : '') . '</td>';
			break;
		case 'Band':
			echo '<td>';
			if ($row->COL_SAT_NAME != null) {
				echo '<a href="https://db.satnogs.org/search/?q=' . $row->COL_SAT_NAME . '" target="_blank">' . $row->COL_SAT_NAME . '</a></td>';
			} else {
				echo strtolower($row->COL_BAND);
			}
			echo '</td>';
			break;
		case 'Frequency':
			echo '<td>';
			if ($row->COL_SAT_NAME != null) {
				echo '<a href="https://db.satnogs.org/search/?q=' . $row->COL_SAT_NAME . '" target="_blank">' . $row->COL_SAT_NAME . '</a></td>';
			} else {
				if ($row->COL_FREQ != null) {
					echo $ci->frequency->hz_to_mhz($row->COL_FREQ);
				} else {
					echo strtolower($row->COL_BAND);
				}
			}
			echo '</td>';
			break;
		case 'State':
			echo '<td>' . ($row->COL_STATE) . '</td>';
			break;
		case 'Operator':
			echo '<td>' . ($row->COL_OPERATOR) . '</td>';
			break;
		case 'Name':
			echo '<td>' . ($row->COL_NAME) . '</td>';
			break;
		case 'Flag':
			$ci->load->library('DxccFlag');
			$flag = strtolower($ci->dxccflag->getISO($row->COL_DXCC));
			echo '<td><span data-bs-toggle="tooltip" title="' . ucwords(strtolower(($row->name == null ? "- NONE -" : $row->name))) . '"><span class="fi fi-' . $flag . '"></span></span></td>';
			break;
	}
}

function echoQrbCalcLink($mygrid, $grid, $vucc)
{
	if (!empty($grid)) {
		echo $grid . ' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $grid . '\')"><i class="fas fa-globe"></i></a>';
	} else if (!empty($vucc)) {
		echo $vucc . ' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $vucc . '\')"><i class="fas fa-globe"></i></a>';
	}
}
?>
<div class="container dashboard">
	<?php if (($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>

		<?php if (version_compare(PHP_VERSION, '7.4.0') <= 0) { ?>
			<div class="alert alert-danger" role="alert">
				<?php echo lang('dashboard_php_version_warning') . ' ' . PHP_VERSION . '.'; ?>
			</div>
		<?php } ?>

		<?php if ($countryCount == 0) { ?>
			<div class="alert alert-danger" role="alert">
				<?php echo lang('dashboard_country_files_warning'); ?>
			</div>
		<?php } ?>

		<?php if ($locationCount == 0) { ?>
			<div class="alert alert-danger" role="alert">
				<?php echo lang('dashboard_locations_warning'); ?>
			</div>
		<?php } ?>

		<?php if ($logbookCount == 0) { ?>
			<div class="alert alert-danger" role="alert">
				<?php echo lang('dashboard_logbooks_warning'); ?>
			</div>
		<?php } ?>

		<?php if ($this->optionslib->get_option('dashboard_banner') != "false") { ?>
			<div hx-get="<?php echo site_url('dashboard/todays_qso_component'); ?>" hx-trigger="every 30s">
				<?php if ($todays_qsos >= 1) { ?>
					<div class="alert alert-success" role="alert">
						<?php echo lang('dashboard_you_have_had'); ?> <strong><?php echo $todays_qsos; ?></strong> <?php echo $todays_qsos != 1 ? lang('dashboard_qsos_today') : str_replace('QSOs', 'QSO', lang('dashboard_qsos_today')); ?>
					</div>
				<?php } else { ?>
					<div class="alert alert-warning" role="alert">
						<span class="badge text-bg-info"><?php echo lang('general_word_important'); ?></span> <i class="fas fa-broadcast-tower"></i> <?php echo lang('notice_turn_the_radio_on'); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if ($current_active == 0) { ?>
			<div class="alert alert-danger" role="alert">
				<?php echo lang('error_no_active_station_profile'); ?>
			</div>
		<?php } ?>

		<?php if ($this->session->userdata('user_id')) { ?>
			<?php
			$current_date = date('Y-m-d H:i:s');
			if ($this->LotwCert->lotw_cert_expired($this->session->userdata('user_id'), $current_date) == true) { ?>
				<div class="alert alert-danger" role="alert">
					<span class="badge text-bg-info"><?php echo lang('general_word_important'); ?></span> <i class="fas fa-hourglass-end"></i> <?php echo lang('lotw_cert_expired'); ?>
				</div>
			<?php } ?>

			<?php if ($this->LotwCert->lotw_cert_expiring($this->session->userdata('user_id'), $current_date) == true) { ?>
				<div class="alert alert-warning" role="alert">
					<span class="badge text-bg-info"><?php echo lang('general_word_important'); ?></span> <i class="fas fa-hourglass-half"></i> <?php echo lang('lotw_cert_expiring'); ?>
				</div>
			<?php } ?>
		<?php } ?>

	<?php } ?>
</div>

<?php if (($this->optionslib->get_option('dashboard_map') != "false") && ($this->optionslib->get_option('dashboard_map') != "map_at_right")) { ?>
	<!-- Map -->
	<div id="map" class="map-leaflet" style="width: 100%; height: 350px"></div>
<?php } ?>
<div style="padding-top: 0px; margin-top: 5px;" class="container dashboard">

	<!-- Log Data -->
	<div class="row logdata">
		<div class="col-sm-8">
			<div id="logbook_display_component" hx-get="<?php echo site_url('dashboard/logbook_display_component'); ?>" hx-trigger="load, every 5s"></div>
		</div>

		<div class="col-sm-4">
			<?php if ($this->optionslib->get_option('dashboard_map') == "map_at_right") { ?>
				<!-- Map -->
				<div id="map" class="map-leaflet" style="width: 100%; height: 350px;  margin-bottom: 15px;"></div>
			<?php } ?>
			<div class="table-responsive">


				<div id="radio_display" hx-get="<?php echo site_url('visitor/radio_display_component'); ?>" hx-trigger="load, every 5s"></div>
				<div>
					<?php if ($dashboard_upcoming_dx_card != false) { ?>
						<div id="upcoming_dxccs_component" hx-get="<?php echo site_url('dashboard/upcoming_dxcc_component'); ?>" hx-trigger="load" hx-indicator="#loading_upcoming_dxcc"></div>
						<div id="loading_upcoming_dxcc" style="display: none;">Loading Upcoming DXPeditions.</div>
					<?php } ?>
				</div>
				<table class="table table-striped border-top">
					<tr class="titles">
						<td colspan="2"><i class="fas fa-chart-bar"></i> <?php echo lang('dashboard_qso_breakdown'); ?></td>
					</tr>

					<tr>
						<td width="50%"><?php echo lang('general_word_total'); ?></td>
						<td width="50%"><?php echo $total_qsos; ?></td>
					</tr>

					<tr>
						<td width="50%"><?php echo lang('general_word_year'); ?></td>
						<td width="50%"><?php echo $year_qsos; ?></td>
					</tr>

					<tr>
						<td width="50%"><?php echo lang('general_word_month'); ?></td>
						<td width="50%"><?php echo $month_qsos; ?></td>
					</tr>
				</table>



				<table class="table table-striped border-top">
					<tr class="titles">
						<td colspan="2"><i class="fas fa-globe-europe"></i> <?php echo lang('dashboard_countries_breakdown'); ?></td>
					</tr>

					<tr>
						<td width="50%"><?php echo lang('general_word_worked'); ?></td>
						<td width="50%"><?php echo $total_countries; ?></td>
					</tr>
					<tr>
						<td width="50%"><a href="#" onclick="return false" title="QSL Cards / eQSL / LoTW" data-bs-toggle="tooltip"><?php echo lang('general_word_confirmed'); ?></a></td>
						<td width="50%">
							<?php echo $total_countries_confirmed_paper; ?> /
							<?php echo $total_countries_confirmed_eqsl; ?> /
							<?php echo $total_countries_confirmed_lotw; ?>
						</td>
					</tr>

					<tr>
						<td width="50%"><?php echo lang('general_word_needed'); ?></td>
						<td width="50%"><?php echo $total_countries_needed; ?></td>
					</tr>
				</table>

				<?php if ($dashboard_qslcard_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) && ($total_qsl_sent != 0 || $total_qsl_rcvd != 0 || $total_qsl_requested != 0)) { ?>
						<table class="table table-striped border-top">
							<tr class="titles">
								<td colspan="2"><i class="fas fa-envelope"></i> <?php echo lang('general_word_qslcards'); ?></td>
								<td colspan="1"><?php echo lang('general_word_today'); ?></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_sent'); ?></td>
								<td width="25%"><?php echo $total_qsl_sent; ?></td>
								<td width="25%"><a href="javascript:displayContacts('','All','All','QSLSDATE','');"><?php echo $qsl_sent_today; ?></a></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_received'); ?></td>
								<td width="25%"><?php echo $total_qsl_rcvd; ?></td>
								<td width="25%"><a href="javascript:displayContacts('','All','All','QSLRDATE','');"><?php echo $qsl_rcvd_today; ?></a></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_requested'); ?></td>
								<td width="25%"><?php echo $total_qsl_requested; ?></td>
								<td width="25%"><?php echo $qsl_requested_today; ?></td>
							</tr>
						</table>
					<?php } ?>
				<?php } ?>

				<?php if ($dashboard_eqslcard_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) && ($total_eqsl_sent != 0 || $total_eqsl_rcvd != 0)) { ?>
						<table class="table table-striped border-top">
							<tr class="titles">
								<td colspan="2"><i class="fas fa-address-card"></i> <?php echo lang('general_word_eqslcards'); ?></td>
								<td colspan="1"><?php echo lang('general_word_today'); ?></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_sent'); ?></td>
								<td width="25%"><?php echo $total_eqsl_sent; ?></td>
								<td width="25%"><a href="javascript:displayContacts('','All','All','EQSLSDATE','');"><?php echo $eqsl_sent_today; ?></a></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_received'); ?></td>
								<td width="25%"><?php echo $total_eqsl_rcvd; ?></td>
								<td width="25%"><a href="javascript:displayContacts('','All','All','EQSLRDATE','');"><?php echo $eqsl_rcvd_today; ?></a></td>
							</tr>
						</table>
					<?php } ?>
				<?php } ?>

				<?php if ($dashboard_lotw_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === false) && ($total_lotw_sent != 0 || $total_lotw_rcvd != 0)) { ?>
						<table class="table table-striped border-top">
							<tr class="titles">
								<td colspan="2"><i class="fas fa-list"></i> <?php echo lang('general_word_lotw'); ?></td>
								<td colspan="1"><?php echo lang('general_word_today'); ?></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_sent'); ?></td>
								<td width="25%"><?php echo $total_lotw_sent; ?></td>
								<td width="25%"><a href="javascript:displayContacts('','all','all','LOTWSDATE','');"><?php echo $lotw_sent_today; ?></a></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_received'); ?></td>
								<td width="25%"><?php echo $total_lotw_rcvd; ?></td>
								<td width="25%"><a href="javascript:displayContacts('','all','all','LOTWRDATE','');"><?php echo $lotw_rcvd_today; ?></a></td>
							</tr>
						</table>
					<?php } ?>
				<?php } ?>

				<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === false) && ($total_qrz_sent != 0 || $total_qrz_rcvd != 0)) { ?>
					<table class="table table-striped border-top">
						<tr class="titles">
							<td colspan="2"><i class="fas fa-list"></i> QRZ.com</td>
							<td colspan="1"><?php echo lang('general_word_today'); ?></td>
						</tr>

						<tr>
							<td width="50%"><?php echo lang('general_word_sent'); ?></td>
							<td width="25%"><?php echo $total_qrz_sent; ?></td>
							<td width="25%"><a href="javascript:displayContacts('','all','all','QRZSDATE','');"><?php echo $qrz_sent_today; ?></a></td>
						</tr>

						<tr>
							<td width="50%"><?php echo lang('general_word_received'); ?></td>
							<td width="25%"><?php echo $total_qrz_rcvd; ?></td>
							<td width="25%"><a href="javascript:displayContacts('','all','all','QRZRDATE','');"><?php echo $qrz_rcvd_today; ?></a></td>
						</tr>
					</table>
				<?php } ?>

				<?php if ($dashboard_vuccgrids_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE)) { ?>
						<table class="table table-striped border-top">
							<tr class="titles">
								<td colspan="2"><i class="fas fa-globe-europe"></i> VUCC-Grids</td>
								<td colspan="1">SAT</td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_worked'); ?></td>
								<td width="25%"><?php echo $vucc['All']['worked']; ?></td>
								<td width="25%"><?php echo $vuccSAT['SAT']['worked'] ?? '0'; ?></td>
							</tr>

							<tr>
								<td width="50%"><?php echo lang('general_word_confirmed'); ?></td>
								<td width="25%"><?php echo $vucc['All']['confirmed']; ?></td>
								<td width="25%"><?php echo $vuccSAT['SAT']['confirmed'] ?? '0'; ?></td>
							</tr>

						</table>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>

</div>