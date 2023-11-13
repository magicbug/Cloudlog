<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<link rel="manifest" href="<?php echo base_url(); ?>manifest.json"/>
	
	<!-- Bootstrap CSS -->
	<?php if ($this->optionslib->get_theme()) { ?>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->optionslib->get_theme(); ?>/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/general.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/selectize.bootstrap4.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-dialog.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->optionslib->get_theme(); ?>/overrides.css">
	<?php } ?>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.fancybox.min.css" />

	<!-- Maps -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/Control.FullScreen.css" />

	<?php if ($this->uri->segment(1) == "search" && $this->uri->segment(2) == "filter") { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/query-builder.default.min.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "notes" && ($this->uri->segment(2) == "add" || $this->uri->segment(2) == "edit")) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/quill/quill.snow.css" />
	<?php } ?>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loading.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ldbtn.min.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/buttons.dataTables.min.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datatables.min.css" />

	<?php if ($this->uri->segment(1) == "adif" || (isset($hasDatePicker) && $hasDatePicker)) { ?>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "sattimers") { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sattimers.css" />
	<?php } ?>

	<?php if (file_exists(APPPATH . '../assets/css/custom.css')) {
		echo '<link rel="stylesheet" href="' . base_url() . 'assets/css/custom.css">';
	} ?>

	<link rel="icon" href="<?php echo base_url(); ?>favicon.ico">

	<title><?php if (isset($page_title)) {
				echo $page_title;
			} ?> - Cloudlog</title>
</head>

<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light main-nav">
		<div class="container">
			<a class="navbar-brand" href="<?php echo site_url(); ?>">Cloudlog</a> <?php if (ENVIRONMENT == "development") { ?><span class="badge badge-danger"><?php echo lang('menu_badge_developer_mode'); ?></span><?php } ?>

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

			<div class="collapse navbar-collapse" id="navbarNav">

				<ul class="navbar-nav">
					<li class="nav-item active">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php echo lang('menu_logbook'); ?></a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="<?php echo site_url('logbook'); ?>"><i class="fas fa-book"></i> <?php echo lang('menu_overview'); ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('logbookadvanced'); ?>"><i class="fas fa-book-open"></i> <?php echo lang('menu_advanced'); ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('qsl'); ?>" title="QSL"><i class="fa fa-id-card"></i> <?php echo lang('menu_view_qsl'); ?></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('eqsl'); ?>" title="eQSL"><i class="fa fa-id-card"></i> <?php echo lang('menu_view_eqsl'); ?></a>
						</div>
					</li>

					<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
						<!-- QSO Menu Dropdown -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_qso'); ?></a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?php echo site_url('qso?manual=0'); ?>" title="Log Live QSOs"><i class="fas fa-list"></i> <?php echo lang('menu_live_qso'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('qso?manual=1'); ?>" title="Log QSO made in the past"><i class="fas fa-list"></i> <?php echo lang('menu_post_qso'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('simplefle'); ?>" title="Simple Fast Log Entry"><i class="fas fa-list"></i> <?php echo lang('menu_fast_log_entry'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('contesting?manual=0'); ?>" title="Live contest QSOs"><i class="fas fa-list"></i> <?php echo lang('menu_live_contest_logging'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('contesting?manual=1'); ?>" title="Post contest QSOs"><i class="fas fa-list"></i> <?php echo lang('menu_post_contest_logging'); ?></a>
							</div>
						</li>

						<!-- Notes -->
						<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
							<a class="nav-link" href="<?php echo site_url('notes'); ?>"><?php echo lang('menu_notes'); ?></a>
						<?php } ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_analytics'); ?></a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?php echo site_url('statistics'); ?>" title="Statistics"><i class="fas fa-chart-area"></i> <?php echo lang('menu_statistics'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('gridmap'); ?>" title="Gridmap"><i class="fas fa-globe-europe"></i> <?php echo lang('menu_gridmap'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('activated_gridmap'); ?>" title="Activated Gridsquares"><i class="fas fa-globe-europe"></i> <?php echo lang('menu_activated_gridsquares'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('activators'); ?>" title="Gridsquare Activators"><i class="fas fa-globe-europe"></i> <?php echo lang('menu_gridsquare_activators'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('distances'); ?>" title="Distances"><i class="fas fa-chart-area"></i> <?php echo lang('menu_distances_worked'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('dayswithqso'); ?>" title="Days with QSOs"><i class="fas fa-chart-area"></i> <?php echo lang('menu_days_with_qsos'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('timeline'); ?>" title="Timeline"><i class="fas fa-chart-area"></i> <?php echo lang('menu_timeline'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('accumulated'); ?>" title="Accumulated Statistics"><i class="fas fa-chart-area"></i> <?php echo lang('menu_accumulated_statistics'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('timeplotter'); ?>" title="View time when worked"><i class="fas fa-chart-area"></i> <?php echo lang('menu_timeplotter'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('map/custom'); ?>" title="Custom Maps of QSOs"><i class="fas fa-globe-europe"></i> <?php echo lang('menu_custom_maps'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('continents'); ?>" title="Continents"><i class="fas fa-globe-europe"></i> <?php echo lang('menu_continents'); ?></a>
							</div>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_awards'); ?></a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?php echo site_url('awards/cq'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_cq'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/dok'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_dok'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/dxcc'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_dxcc'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/ffma'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_ffma'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/iota'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_iota'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/ja_gridmaster'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_ja_gridmaster'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/pota'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_pota'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/sig'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_sig'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/sota'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_sota'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/counties'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_us_counties'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/gridmaster'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_us_gridmaster'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/vucc'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_vucc'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/was'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_was'); ?></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('awards/wwff'); ?>"><i class="fas fa-trophy"></i> <?php echo lang('menu_wwff'); ?></a>
							</div>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Tools"><i class="fas fa-tools"></i>
								<div class="d-inline d-lg-none" style="padding-left: 10px">Tools</div>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?php echo site_url('hamsat'); ?>" title="Hams.at"><i class="fas fa-list"></i> Hams.at</a>
								<?php if ($this->optionslib->get_option('dxcache_url') != '') { ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?php echo site_url('bandmap/list'); ?>" title="Bandmap"><i class="fa fa-id-card"></i> <?php echo lang('menu_bandmap'); ?></a>
								<?php } ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo site_url('sattimers'); ?>" title="SAT Timers"><i class="fas fa-satellite"></i> SAT Timers</a>
							</div>
						</li>

						<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') == 99)) { ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo lang('menu_admin'); ?>"><i class="fas fa-users-cog"></i>
									<div class="d-inline d-lg-none" style="padding-left: 10px"><?php echo lang('menu_admin'); ?></div>
								</a>

								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="<?php echo site_url('user'); ?>" title="Manage user accounts"><i class="fas fa-user"></i> <?php echo lang('menu_user_account'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('options'); ?>" title="Manage global options"><i class="fas fa-cog"></i> <?php echo lang('menu_global_options'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('mode'); ?>" title="Manage QSO modes"><i class="fas fa-broadcast-tower"></i> <?php echo lang('menu_modes'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('contesting/add'); ?>" title="Manage Contest names"><i class="fas fa-broadcast-tower"></i> <?php echo lang('menu_contests'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('themes'); ?>" title="Manage Themes"><i class="fas fa-cog"></i> <?php echo lang('menu_themes'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('backup'); ?>" title="Backup Cloudlog content"><i class="fas fa-save"></i> <?php echo lang('menu_backup'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('update'); ?>" title="Update Country Files"><i class="fas fa-sync"></i> <?php echo lang('menu_update_country_files'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('maintenance'); ?>" title="maintenance"><i class="fas fa-tools"></i> <?php echo lang('menu_maintenance'); ?></a>

									<div class="dropdown-divider"></div>

									<a class="dropdown-item" href="<?php echo site_url('debug'); ?>" title="Debug Information"><i class="fas fa-tools"></i> <?php echo lang('menu_debug_information'); ?></a>

								</div>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>

				<?php if ($this->session->userdata('user_quicklog')  == 1) { ?>
					<script>
						function submitForm(action) {
							var form = document.getElementById('quicklog-form');
							var input = document.getElementById('quicklog-input');
							if (action === 'search') {
								form.action = "<?php echo site_url('search'); ?>";
								form.method = "post";
							}
							form.submit();
						}
						function logQuicklog() {
							if (localStorage.getItem("quicklogCallsign") !== "") {
  								localStorage.removeItem("quicklogCallsign");
							}
							localStorage.setItem("quicklogCallsign", $("input[name='callsign']").val());
							window.open("<?php echo site_url('qso?manual=0'); ?>", "_self");
						}
					</script>
					<?php if ($this->session->userdata('user_quicklog_enter')  == 1) { ?>
						<script>
							function handleKeyPress(event) {
								if (event.key === 'Enter') {
									submitForm('search'); // Treat Enter key press as clicking the 'quicksearch-search' button
								}
							}
						</script>
					<?php } else { ?>
						<script>
							function handleKeyPress(event) {
								if (event.key === 'Enter') {
									logQuicklog(); // Treat Enter key press as clicking the 'quicksearch-log' button
								}
							}
						</script>
					<?php } ?>
					<form id="quicklog-form" class="form-inline" onsubmit="return false;">
						<input class="form-control mr-sm-2" id="nav-bar-search-input" type="text" name="callsign" placeholder="<?php echo lang('menu_search_text_quicklog'); ?>" aria-label="Quicklog" onkeypress="handleKeyPress(event)">

						<button title="<?php echo lang('menu_search_button_qicksearch_log'); ?>" class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="logQuicklog()"><i class="fas fa-plus"></i>
							<div class="d-inline d-lg-none" style="padding-left: 10px"><?php echo lang('menu_search_button_qicksearch_log'); ?></div>
						</button>

						<button title="<?php echo lang('menu_search_button'); ?>" class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="submitForm('search')" style="margin-left: 5px"><i class="fas fa-search"></i>
							<div class="d-inline d-lg-none" style="padding-left: 10px"><?php echo lang('menu_search_button'); ?></div>
						</button>
					</form>
				<?php } else { ?>
					<form method="post" action="<?php echo site_url('search'); ?>" class="form-inline">
						<input class="form-control mr-sm-2" id="nav-bar-search-input" type="search" name="callsign" placeholder="<?php echo lang('menu_search_text'); ?>" aria-label="Search">
						<button title="<?php echo lang('menu_search_button'); ?>" class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i>
							<div class="d-inline d-lg-none" style="padding-left: 10px"><?php echo lang('menu_search_button'); ?></div>
						</button>
					</form>
				<?php } ?>

				<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
					<!-- Logged in Content-->
				<?php } else { ?>
					<!-- Not Logged In-->
					<form method="post" action="<?php echo site_url('user/login'); ?>" style="padding-left: 5px;" class="form-inline">
						<input class="form-control mr-sm-2" type="text" name="user_name" placeholder="Username" aria-label="Username">
						<input class="form-control mr-sm-2" type="password" name="user_password" placeholder="Password" aria-label="Password">
						<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
						<button class="btn btn-outline-success mr-sm-2" type="submit"><?php echo lang('menu_login_button'); ?></button>
					</form>
				<?php } ?>

				<?php if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
					<ul class="navbar-nav">
						<!-- Logged in As -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> <?php echo $this->session->userdata('user_callsign'); ?></a>

							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?php echo site_url('user/edit') . "/" . $this->session->userdata('user_id'); ?>" title="Account"><i class="fas fa-user"></i> <?php echo lang('menu_account'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('logbooks'); ?>" title="Manage station logbooks"><i class="fas fa-home"></i> <?php echo lang('menu_station_logbooks'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('station'); ?>" title="Manage station locations"><i class="fas fa-home"></i> <?php echo lang('menu_station_locations'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('band'); ?>" title="Manage Bands"><i class="fas fa-cog"></i> <?php echo lang('menu_bands'); ?></a>

								<div class="dropdown-divider"></div>

								<a class="dropdown-item" href="<?php echo site_url('adif'); ?>" title="Amateur Data Interchange Format (ADIF) import / export"><i class="fas fa-sync"></i> <?php echo lang('menu_adif_import_export'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('kmlexport'); ?>" title="KML Export for Google Earth"><i class="fas fa-sync"></i> <?php echo lang('menu_kml_export'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('dxatlas'); ?>" title="DX Atlas Gridsquare Export"><i class="fas fa-sync"></i> <?php echo lang('menu_dx_atlas_gridsquare_export'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('csv'); ?>" title="SOTA CSV Export"><i class="fas fa-sync"></i> <?php echo lang('menu_sota_csv_export'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('cabrillo'); ?>" title="Cabrillo Export"><i class="fas fa-sync"></i> <?php echo lang('menu_cabrillo_export'); ?></a>

								<div class="dropdown-divider"></div>

								<?php
								$CI = &get_instance();
								$CI->load->model('oqrs_model');
								$CI->load->model('logbooks_model');
								$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
								if ($logbooks_locations_array) {
									$location_list = "'" . implode("','", $logbooks_locations_array) . "'";
								} else {
									$location_list = null;
								}

								$oqrs_requests = $CI->oqrs_model->oqrs_requests($location_list);
								?>
								<a class="dropdown-item" href="<?php echo site_url('oqrs/requests'); ?>" title="OQRS Requests"><i class="fa fa-id-card"></i> <?php echo lang('menu_oqrs_requests'); ?> <?php if ($oqrs_requests > 0) {
																																																			echo "<span class=\"badge badge-light\">" . $oqrs_requests . "</span>";
																																																		} ?></a>

								<a class="dropdown-item" href="<?php echo site_url('qslprint'); ?>" title="Print Requested QSLs"><i class="fas fa-print"></i> <?php echo lang('menu_print_requested_qsls'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('labels'); ?>" title="Label setup"><i class="fas fa-print"></i> <?php echo lang('menu_labels'); ?></a>

								<div class="dropdown-divider"></div>

								<a class="dropdown-item" href="<?php echo site_url('lotw'); ?>" title="Synchronise with Logbook of the World (LoTW)"><i class="fas fa-sync"></i> <?php echo lang('menu_logbook_of_the_world'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('eqsl/import'); ?>" title="eQSL import / export"><i class="fas fa-sync"></i> <?php echo lang('menu_eqsl_import_export'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('hrdlog/export'); ?>" title="Upload to HRDLog.net logbook"><i class="fas fa-sync"></i> <?php echo lang('menu_hrd_logbook'); ?></a>
								<a class="dropdown-item" href="<?php echo site_url('qrz/export'); ?>" title="Upload to QRZ.com logbook"><i class="fas fa-sync"></i> <?php echo lang('menu_qrz_logbook'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('webadif/export'); ?>" title="Upload to webADIF"><i class="fas fa-sync"></i> <?php echo lang('menu_qo_100_dx_club_upload'); ?></a>

								<div class="dropdown-divider"></div>

								<a class="dropdown-item" href="<?php echo site_url('api/help'); ?>" title="Manage API keys"><i class="fas fa-key"></i> <?php echo lang('menu_api_keys'); ?></a>

								<a class="dropdown-item" href="<?php echo site_url('radio'); ?>" title="Interface with one or more radios"><i class="fas fa-broadcast-tower"></i> <?php echo lang('menu_hardware_interfaces'); ?></a>

								<div class="dropdown-divider"></div>

								<a class="dropdown-item" target="_blank" href="https://github.com/magicbug/Cloudlog/wiki" title="Help"><i class="fas fa-question"></i> <?php echo lang('menu_help'); ?></a>

								<a class="dropdown-item" target="_blank" href="https://github.com/magicbug/Cloudlog/discussions" title="Forum"><i class="far fa-comment-dots"></i> <?php echo lang('menu_forum'); ?></a>

								<div class="dropdown-divider"></div>

								<a class="dropdown-item" href="<?php echo site_url('user/logout'); ?>" title="Logout"><i class="fas fa-sign-out-alt"></i> <?php echo lang('menu_logout'); ?></a>
							</div>
						</li>

						<?php
						// Can add extra menu items by defining them in options. The format is json.
						// Useful to add extra things in Cloudlog without the need for modifying files. If you add extras, these files will not be overwritten when updating.
						//
						// The menu items will be displayed to the top right under extras.
						//
						// Example:
						// INSERT INTO options (option_name,option_value,autoload) VALUES
						// 	('menuitems','[
						// {
						// 		"url":"gridmap",
						// 		"text":"Gridmap",
						// 		"icon":"fa-globe-europe"
						// },
						// {
						// 		"url":"gallery",
						// 		"text":"Gallery",
						// 		"icon":"fa-globe-europe"
						// }
						// ]','yes');

						if ($this->optionslib->get_option('menuitems')) { ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Extras</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<?php
									foreach (json_decode($this->optionslib->get_option('menuitems')) as $item) {
										echo '<a class="dropdown-item" href="' . site_url($item->url) . '" title="' . $item->text . '"><i class="fas ' . $item->icon . '"></i> ' . $item->text . '</a>';
									}
									?>
								</div>
							</li>
						<?php } ?>

					</ul>

				<?php } ?>

			</div>
		</div>
	</nav>