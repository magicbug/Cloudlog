<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container dashboard">
	<style>
		.htmx-indicator {
			display: none !important;
		}
	</style>
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
			<div id="todays_qso_component" hx-get="<?php echo site_url('dashboard/todays_qso_component'); ?>" hx-trigger="load, every 60s [!document.hidden]" data-updated-target="#todays-qso-last-updated" data-updated-wrap-target="#todays-qso-last-updated-wrap" hx-indicator="#todays-qso-loading">
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
			<div class="small text-muted mb-2 d-none d-lg-block">
				<span id="todays-qso-loading" class="htmx-indicator ms-2"><i class="fas fa-spinner fa-spin"></i> Updating...</span>
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

<div class="container dashboard d-lg-none" style="margin-top: 8px;">
	<div class="mb-2 d-flex gap-2">
		<a href="<?php echo site_url('qso?manual=0'); ?>" class="btn btn-primary flex-fill">
			<i class="fas fa-pencil-alt"></i> General Logging
		</a>
		<a href="<?php echo site_url('contesting?manual=0'); ?>" class="btn btn-success flex-fill">
			<i class="fas fa-trophy"></i> Contest Logging
		</a>
	</div>
</div>

<div style="padding-top: 0px; margin-top: 5px;" class="container dashboard">

	<!-- Log Data -->
	<div class="row logdata">
		<div class="col-lg-8">
			<div id="logbook_display_component" hx-get="<?php echo site_url('dashboard/logbook_display_component'); ?>" hx-trigger="load, every 15s [!document.hidden]" data-updated-target="#logbook-last-updated" data-updated-wrap-target="#logbook-last-updated-wrap" hx-indicator="#logbook-loading"></div>
			<div class="small text-muted mb-2 d-none d-lg-block">
				<span id="logbook-last-updated-wrap" class="d-none"><span id="logbook-last-updated"></span></span>
				<span id="logbook-loading" class="htmx-indicator ms-2"><i class="fas fa-spinner fa-spin"></i> Updating...</span>
			</div>
		</div>

		<div class="col-lg-4">
			<?php if ($this->optionslib->get_option('dashboard_map') == "map_at_right") { ?>
				<!-- Map -->
				<div id="map" class="map-leaflet" style="width: 100%; height: 350px;  margin-bottom: 15px;"></div>
			<?php } ?>
			<div class="table-responsive">


				<div id="radio_display" hx-get="<?php echo site_url('dashboard/radio_display_component'); ?>" hx-trigger="load, every 15s [!document.hidden]" data-updated-target="#radio-last-updated" data-updated-wrap-target="#radio-last-updated-wrap" hx-indicator="#radio-loading"></div>
				<div class="small text-muted mb-2 d-none d-lg-block">
					<span id="radio-last-updated-wrap" class="d-none"><span id="radio-last-updated"></span></span>
					<span id="radio-loading" class="htmx-indicator ms-2"><i class="fas fa-spinner fa-spin"></i> Updating...</span>
				</div>
				
				<!-- Quick Logging Links -->
				<div class="mb-3 d-none d-lg-flex gap-2">
					<a href="<?php echo site_url('qso?manual=0'); ?>" class="btn btn-primary flex-fill">
						<i class="fas fa-pencil-alt"></i> General Logging
					</a>
					<a href="<?php echo site_url('contesting?manual=0'); ?>" class="btn btn-success flex-fill">
						<i class="fas fa-trophy"></i> Contest Logging
					</a>
				</div>

				<div>
					<?php if ($dashboard_upcoming_dx_card != false) { ?>
						<div class="d-lg-none mb-1">
							<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-upcoming-dx-card" aria-expanded="false" aria-controls="dashboard-upcoming-dx-card">
								DXPeditions
							</button>
						</div>
						<div id="dashboard-upcoming-dx-card" class="collapse d-lg-block">
						<div id="upcoming_dxccs_component" hx-get="<?php echo site_url('dashboard/upcoming_dxcc_component'); ?>" hx-trigger="load, every 30m [!document.hidden]" data-updated-target="#upcoming-dx-last-updated" data-updated-wrap-target="#upcoming-dx-last-updated-wrap" hx-indicator="#loading_upcoming_dxcc"></div>
						<div class="small text-muted mb-2 d-none d-lg-block">
							<span id="upcoming-dx-last-updated-wrap" class="d-none"><span id="upcoming-dx-last-updated"></span></span>
							<span id="loading_upcoming_dxcc" class="htmx-indicator ms-2"><i class="fas fa-spinner fa-spin"></i> Loading upcoming DXpeditions...</span>
						</div>
						</div>
					<?php } ?>
				</div>
				<div class="d-lg-none mb-1">
					<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-qso-breakdown-card" aria-expanded="false" aria-controls="dashboard-qso-breakdown-card">
						<?php echo lang('dashboard_qso_breakdown'); ?>
					</button>
				</div>
				<div id="dashboard-qso-breakdown-card" class="collapse d-lg-block">
				<table class="table table-striped border-top">
					<tr class="titles">
						<td colspan="2"><i class="fa-solid fa-chart-bar"></i> <?php echo lang('dashboard_qso_breakdown'); ?></td>
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
				</div>

				<div class="d-lg-none mb-1">
					<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-countries-breakdown-card" aria-expanded="false" aria-controls="dashboard-countries-breakdown-card">
						<?php echo lang('dashboard_countries_breakdown'); ?>
					</button>
				</div>
				<div id="dashboard-countries-breakdown-card" class="collapse d-lg-block">
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
				</div>

				<?php if ($dashboard_qslcard_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) && ($total_qsl_sent != 0 || $total_qsl_rcvd != 0 || $total_qsl_requested != 0)) { ?>
						<div class="d-lg-none mb-1">
							<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-qslcards-card" aria-expanded="false" aria-controls="dashboard-qslcards-card">
								<?php echo lang('general_word_qslcards'); ?>
							</button>
						</div>
						<div id="dashboard-qslcards-card" class="collapse d-lg-block">
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
						</div>
					<?php } ?>
				<?php } ?>

				<?php if ($dashboard_eqslcard_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) && ($total_eqsl_sent != 0 || $total_eqsl_rcvd != 0)) { ?>
						<div class="d-lg-none mb-1">
							<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-eqslcards-card" aria-expanded="false" aria-controls="dashboard-eqslcards-card">
								<?php echo lang('general_word_eqslcards'); ?>
							</button>
						</div>
						<div id="dashboard-eqslcards-card" class="collapse d-lg-block">
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
						</div>
					<?php } ?>
				<?php } ?>

				<?php if ($dashboard_lotw_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === false) && ($total_lotw_sent != 0 || $total_lotw_rcvd != 0)) { ?>
						<div class="d-lg-none mb-1">
							<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-lotw-card" aria-expanded="false" aria-controls="dashboard-lotw-card">
								<?php echo lang('general_word_lotw'); ?>
							</button>
						</div>
						<div id="dashboard-lotw-card" class="collapse d-lg-block">
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
						</div>
					<?php } ?>
				<?php } ?>

				<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === false) && ($total_qrz_sent != 0 || $total_qrz_rcvd != 0)) { ?>
					<div class="d-lg-none mb-1">
						<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-qrz-card" aria-expanded="false" aria-controls="dashboard-qrz-card">
							QRZ.com
						</button>
					</div>
					<div id="dashboard-qrz-card" class="collapse d-lg-block">
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
					</div>
				<?php } ?>

				<?php if ($dashboard_vuccgrids_card != false) { ?>
					<?php if ((($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE)) { ?>
						<div class="d-lg-none mb-1">
							<button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#dashboard-vucc-card" aria-expanded="false" aria-controls="dashboard-vucc-card">
								VUCC-Grids
							</button>
						</div>
						<div id="dashboard-vucc-card" class="collapse d-lg-block">
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
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>

</div>

<style>
	@media (max-width: 991.98px) {
		.dashboard .table {
			margin-bottom: 0.5rem;
		}

		.dashboard .btn.btn-sm.w-100 {
			margin-bottom: 0.25rem;
		}
	}
</style>

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
			setText(source.getAttribute('data-updated-target'), 'Updated ' + new Date().toLocaleTimeString());
			showElement(source.getAttribute('data-updated-wrap-target'));
		});
	})();
</script>
