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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/general.css?v=<?php echo filemtime(FCPATH . 'assets/css/general.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/selectize.bootstrap4.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-dialog.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->optionslib->get_theme(); ?>/overrides.css?v=<?php echo filemtime(FCPATH . 'assets/css/' . $this->optionslib->get_theme() . '/overrides.css'); ?>">
	<?php } ?>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/fontawesome.css">
	<link href="<?php echo base_url(); ?>assets/fontawesome/css/brands.css" rel="stylesheet" />
  	<link href="<?php echo base_url(); ?>assets/fontawesome/css/solid.css" rel="stylesheet" />
  
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/flag-icons.min.css" />

	<?php
	$load_leaflet = in_array($this->uri->segment(1), [NULL, '', 'dashboard', 'logbook', 'logbookadvanced', 'gridmap', 'activated_gridmap', 'qso', 'map', 'search', 'activators', 'activatorsmap'], true)
		|| ($this->uri->segment(1) == 'awards' && in_array($this->uri->segment(2), ['cq', 'iota', 'dxcc', 'ffma', 'gridmaster', 'wab', 'waja', 'was', 'sota', 'pota', 'rda', 'counties'], true));
	?>

	<!-- Maps -->
	<?php if ($load_leaflet) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/Control.FullScreen.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "search" && $this->uri->segment(2) == "filter") { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/query-builder.default.min.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "notes" && ($this->uri->segment(2) == "add" || $this->uri->segment(2) == "edit")) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/summernote/summernote-bs5.min.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "logbook" && $this->uri->segment(2) != "view") { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/quill/quill.snow.css" />
	<?php } ?>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loading.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ldbtn.min.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/buttons.dataTables.min.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datatables.min.css" />

	<?php if ($this->uri->segment(1) == "sattimers") { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sattimers.css" />
	<?php } ?>

	<?php if (file_exists(APPPATH . '../assets/css/custom.css')) {
		echo '<link rel="stylesheet" href="' . base_url() . 'assets/css/custom.css">';
	} ?>

	<?php if (file_exists(APPPATH . '../assets/js/sections/custom.js')) {
		echo '<script src="' . base_url() . 'assets/js/sections/custom.js"></script>';
	} ?>

	<link rel="icon" href="<?php echo base_url(); ?>favicon.ico">

	<title><?php if (isset($page_title)) {
				echo $page_title;
			} ?> - Cloudlog</title>
</head>

<body>

	<?php
	$show_eqsl_menu = (bool) $this->session->userdata('has_eqsl_credentials');
	$show_qsl_cards_setting = $this->session->userdata('user_show_qsl_cards');
	$show_qsl_cards_menu = true;
	if ($show_qsl_cards_setting !== NULL && $show_qsl_cards_setting !== '') {
		$show_qsl_cards_menu = filter_var($show_qsl_cards_setting, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if ($show_qsl_cards_menu === NULL) {
			$show_qsl_cards_menu = true;
		}
	}
	$show_sstv_images_setting = $this->session->userdata('user_show_sstv_images');
	$show_sstv_menu = false;
	if ($show_sstv_images_setting !== NULL && $show_sstv_images_setting !== '') {
		$show_sstv_menu = filter_var($show_sstv_images_setting, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if ($show_sstv_menu === NULL) {
			$show_sstv_menu = false;
		}
	}

	$nav_is_logged_in = ($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2);
	$nav_is_admin_user = ($this->config->item('use_auth')) && ($this->session->userdata('user_type') == 99);
	$nav_seg1 = (string) $this->uri->segment(1);
	$nav_seg2 = (string) $this->uri->segment(2);

	$nav_is_logbook = in_array($nav_seg1, ['logbook', 'logbookadvanced', 'qsl', 'eqsl', 'sstv'], true);
	$nav_is_qso = in_array($nav_seg1, ['qso', 'simplefle'], true) || ($nav_seg1 === 'contesting' && $nav_seg2 !== 'add');
	$nav_is_notes = ($nav_seg1 === 'notes');
	$nav_is_analytics = in_array($nav_seg1, ['statistics', 'monthlyreport', 'gridmap', 'activated_gridmap', 'activators', 'activatorsmap', 'distances', 'dayswithqso', 'timeline', 'accumulated', 'timeplotter', 'mostworked', 'map', 'continents', 'emeinitials'], true);
	$nav_is_awards = in_array($nav_seg1, ['awards', 'award'], true);
	$nav_is_tools = in_array($nav_seg1, ['propagationadvisor', 'callhistory', 'dxcluster', 'workabledxcc', 'hamsat'], true);
	$nav_is_admin = in_array($nav_seg1, ['user', 'options', 'mode', 'themes', 'update', 'maintenance', 'cronmanager', 'plugins', 'debug'], true)
		|| ($nav_seg1 === 'contesting' && $nav_seg2 === 'add');

	$oqrs_requests = 0;
	$active_logbook_name = '';
	if ($nav_is_logged_in) {
		$CI = &get_instance();
		$CI->load->model('oqrs_model');
		$CI->load->model('logbooks_model');
		$active_logbook_id = $this->session->userdata('active_station_logbook');
		$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($active_logbook_id);
		if ($logbooks_locations_array) {
			$location_list = "'" . implode("','", $logbooks_locations_array) . "'";
		} else {
			$location_list = null;
		}
		$oqrs_requests = (int) $CI->oqrs_model->oqrs_requests($location_list);
		if ($active_logbook_id) {
			$found_logbook_name = $CI->logbooks_model->find_name($active_logbook_id);
			if ($found_logbook_name && $found_logbook_name !== 'n/a') {
				$active_logbook_name = $found_logbook_name;
			}
		}
	}
	?>

	<nav class="navbar navbar-expand-lg navbar-light bg-light main-nav sticky-top">
		<div class="container">
			<a class="navbar-brand d-inline-flex align-items-center gap-2" href="<?php echo site_url(); ?>">
				<img src="<?php echo base_url(); ?>CloudLog_logo.png" alt="Cloudlog" class="navbar-brand-logo" width="26" height="26">
				<span>Cloudlog</span>
			</a>
			<?php if (ENVIRONMENT == "development") { ?><span class="badge text-bg-danger me-2"><?php echo lang('menu_badge_developer_mode'); ?></span><?php } ?>

			<?php if ($nav_is_logged_in) { ?>
				<a class="btn btn-primary btn-sm d-inline-flex d-lg-none align-items-center gap-1 ms-auto me-2" href="<?php echo site_url('qso?manual=0'); ?>" title="Log Live QSOs">
					<i class="fas fa-plus"></i><span><?php echo lang('menu_qso'); ?></span>
				</a>
			<?php } ?>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

			<div class="collapse navbar-collapse" id="navbarNav">

				<ul class="navbar-nav me-auto align-items-lg-center gap-lg-1">
					<!-- Logbook Dropdown -->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle<?php echo $nav_is_logbook ? ' active' : ''; ?>" href="#" id="navLogbook" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_logbook'); ?></a>
						<div class="dropdown-menu" aria-labelledby="navLogbook">
							<h6 class="dropdown-header"><?php echo lang('menu_logbook'); ?></h6>
							<a class="dropdown-item" href="<?php echo site_url('logbook'); ?>"><i class="fas fa-atlas"></i> <?php echo lang('menu_overview'); ?></a>
							<a class="dropdown-item" href="<?php echo site_url('logbookadvanced'); ?>"><i class="fas fa-book-open"></i> <?php echo lang('menu_advanced'); ?></a>
							<?php if ($show_qsl_cards_menu || $show_eqsl_menu || $show_sstv_menu) { ?>
								<div class="dropdown-divider"></div>
								<h6 class="dropdown-header">QSL &amp; Media</h6>
								<?php if ($show_qsl_cards_menu) { ?>
									<a class="dropdown-item" href="<?php echo site_url('qsl'); ?>" title="QSL"><i class="fas fa-envelope"></i> <?php echo lang('menu_view_qsl'); ?></a>
								<?php } ?>
								<?php if ($show_eqsl_menu) { ?>
									<a class="dropdown-item" href="<?php echo site_url('eqsl'); ?>" title="eQSL"><i class="fas fa-at"></i> <?php echo lang('menu_view_eqsl'); ?></a>
								<?php } ?>
								<?php if ($show_sstv_menu) { ?>
									<a class="dropdown-item" href="<?php echo site_url('sstv'); ?>" title="SSTV"><i class="fa fa-image"></i> <?php echo lang('menu_view_sstv'); ?></a>
								<?php } ?>
							<?php } ?>
						</div>
					</li>

					<?php if ($nav_is_logged_in) { ?>
						<!-- QSO 1-Click Split CTA (Desktop) / Dropdown (Mobile) -->
						<li class="nav-item dropdown nav-qso-item mx-lg-1">
							<div class="btn-group d-none d-lg-inline-flex nav-qso-cta">
								<a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" href="<?php echo site_url('qso?manual=0'); ?>" title="Log Live QSOs">
									<i class="fas fa-plus"></i> <span><?php echo lang('menu_qso'); ?></span>
								</a>
								<button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" id="navQsoDesktop" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="visually-hidden"><?php echo lang('menu_qso'); ?></span>
								</button>
							</div>
							<a class="nav-link dropdown-toggle d-lg-none<?php echo $nav_is_qso ? ' active' : ''; ?>" href="#" id="navQsoMobile" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_qso'); ?></a>
							<div class="dropdown-menu" aria-labelledby="navQsoDesktop">
								<h6 class="dropdown-header"><?php echo lang('menu_qso'); ?></h6>
								<a class="dropdown-item" href="<?php echo site_url('qso?manual=0'); ?>" title="Log Live QSOs"><i class="fas fa-pencil-alt"></i> <?php echo lang('menu_live_qso'); ?></a>
								<a class="dropdown-item" href="<?php echo site_url('contesting?manual=0'); ?>" title="Contest QSOs"><i class="fas fa-trophy"></i> <?php echo lang('menu_live_contest_logging'); ?></a>
								<a class="dropdown-item" href="<?php echo site_url('simplefle'); ?>" title="Simple Fast Log Entry"><i class="fas fa-edit"></i> <?php echo lang('menu_fast_log_entry'); ?></a>
							</div>
						</li>

						<!-- Notes -->
						<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
							<li class="nav-item">
								<a class="nav-link<?php echo $nav_is_notes ? ' active' : ''; ?>" href="<?php echo site_url('notes'); ?>"><?php echo lang('menu_notes'); ?></a>
							</li>
						<?php } ?>

						<!-- Analytics 2-Column Mega-Dropdown -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle<?php echo $nav_is_analytics ? ' active' : ''; ?>" href="#" id="navAnalytics" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_analytics'); ?></a>
							<div class="dropdown-menu nav-mega-menu nav-mega-2col" aria-labelledby="navAnalytics">
								<div class="nav-mega-grid">
									<div class="nav-mega-col">
										<h6 class="dropdown-header">Reports &amp; Trends</h6>
										<a class="dropdown-item" href="<?php echo site_url('statistics'); ?>" title="Statistics"><i class="fas fa-chart-pie"></i> <?php echo lang('menu_statistics'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('monthlyreport'); ?>" title="Monthly Report"><i class="fas fa-calendar-alt"></i> Monthly Report</a>
										<a class="dropdown-item" href="<?php echo site_url('timeline'); ?>" title="Timeline"><i class="fas fa-stream"></i> <?php echo lang('menu_timeline'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('accumulated'); ?>" title="Accumulated Statistics"><i class="fas fa-chart-line"></i> <?php echo lang('menu_accumulated_statistics'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('dayswithqso'); ?>" title="Days with QSOs"><i class="fas fa-chart-area"></i> <?php echo lang('menu_days_with_qsos'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('timeplotter'); ?>" title="View time when worked"><i class="fas fa-clock"></i> <?php echo lang('menu_timeplotter'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('mostworked'); ?>" title="Most Worked Callsigns"><i class="fas fa-user-friends"></i> <?php echo lang('menu_most_worked'); ?></a>
									</div>
									<div class="nav-mega-col">
										<h6 class="dropdown-header">Maps &amp; Propagation</h6>
										<a class="dropdown-item" href="<?php echo site_url('gridmap'); ?>" title="Gridmap"><i class="fas fa-layer-group"></i> <?php echo lang('menu_gridmap'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('activated_gridmap'); ?>" title="Activated Gridsquares"><i class="fas fa-th-large"></i> <?php echo lang('menu_activated_gridsquares'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('activators'); ?>" title="Gridsquare Activators"><i class="fas fa-th"></i> <?php echo lang('menu_gridsquare_activators'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('map/custom'); ?>" title="Custom Maps of QSOs"><i class="fas fa-map"></i> <?php echo lang('menu_custom_maps'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('distances'); ?>" title="Distances"><i class="fas fa-ruler"></i> <?php echo lang('menu_distances_worked'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('continents'); ?>" title="Continents"><i class="fas fa-globe-europe"></i> <?php echo lang('menu_continents'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('emeinitials'); ?>" title="EME Initials"><i class="fas fa-moon"></i> <?php echo lang('menu_eme_initials'); ?></a>
									</div>
								</div>
							</div>
						</li>

						<!-- Awards Categorized Mega-Dropdown -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle<?php echo $nav_is_awards ? ' active' : ''; ?>" href="#" id="navAwards" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_awards'); ?></a>
							<div class="dropdown-menu nav-mega-menu nav-mega-3col" aria-labelledby="navAwards">
								<?php
								$CI->load->model('awards_model');
								$user_awards = $CI->awards_model->get_user_awards();

								$CI->load->library('plugin_manager');
								$CI->load->model('user_options_model');
								$plugin_award_entries = $CI->plugin_manager->get_award_menu_entries();
								$plugin_award_visibility = array();
								$plugin_award_visibility_query = $CI->user_options_model->get_options('plugin_awards_menu');
								foreach ($plugin_award_visibility_query->result() as $plugin_award_visibility_row) {
									if ($plugin_award_visibility_row->option_key === 'show') {
										$plugin_award_visibility[$plugin_award_visibility_row->option_name] = (string)$plugin_award_visibility_row->option_value === '1';
									}
								}
								$visible_plugin_awards = array();
								if (!empty($plugin_award_entries)) {
									foreach ($plugin_award_entries as $plugin_award_entry) {
										$show_plugin_award = true;
										if (isset($plugin_award_visibility[$plugin_award_entry['slug']])) {
											$show_plugin_award = (bool)$plugin_award_visibility[$plugin_award_entry['slug']];
										}
										if ($show_plugin_award) {
											$visible_plugin_awards[] = $plugin_award_entry;
										}
									}
								}

								$has_gridmaster = ($user_awards->gridmaster_dl == 1 || $user_awards->gridmaster_lx == 1 || $user_awards->gridmaster_ja == 1 || $user_awards->gridmaster_us == 1 || $user_awards->gridmaster_uk == 1);
								$has_global_awards = ($user_awards->dxcc == 1 || $user_awards->cq == 1 || !empty($user_awards->wac) || $user_awards->iota == 1 || $user_awards->gmdxsummer == 1);
								$has_grid_awards = ($user_awards->vucc == 1 || $user_awards->ffma == 1 || $has_gridmaster);
								$has_portable_awards = ($user_awards->pota == 1 || $user_awards->sota == 1 || $user_awards->wwff == 1 || $user_awards->sig == 1 || !empty($user_awards->skcc) || !empty($user_awards->rsgb) || !empty($visible_plugin_awards));
								$has_regional_awards = ($user_awards->was == 1 || $user_awards->uscounties == 1 || $user_awards->wab == 1 || $user_awards->dok == 1 || !empty($user_awards->rac) || !empty($user_awards->rda) || $user_awards->waja == 1);
								?>
								<div class="nav-mega-grid">
									<?php if ($has_global_awards || $has_grid_awards) { ?>
										<div class="nav-mega-col">
											<?php if ($has_global_awards) { ?>
												<h6 class="dropdown-header">Global &amp; DX</h6>
												<?php if ($user_awards->dxcc == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/dxcc'); ?>"><i class="fas fa-globe"></i> <?php echo lang('menu_dxcc'); ?></a>
												<?php } ?>
												<?php if ($user_awards->cq == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/cq'); ?>"><i class="fas fa-globe-americas"></i> <?php echo lang('menu_cq'); ?></a>
												<?php } ?>
												<?php if (!empty($user_awards->wac)) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/wac'); ?>"><i class="fas fa-globe-africa"></i> <?php echo lang('menu_wac'); ?></a>
												<?php } ?>
												<?php if ($user_awards->iota == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/iota'); ?>"><i class="fas fa-water"></i> <?php echo lang('menu_iota'); ?></a>
												<?php } ?>
												<?php if ($user_awards->gmdxsummer == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/gmdxsummer'); ?>"><i class="fas fa-medal"></i> GMDX Summer Challenge</a>
												<?php } ?>
											<?php } ?>

											<?php if ($has_grid_awards) { ?>
												<h6 class="dropdown-header<?php echo $has_global_awards ? ' mt-2' : ''; ?>">Grids</h6>
												<?php if ($user_awards->vucc == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/vucc'); ?>"><i class="fas fa-th-large"></i> <?php echo lang('menu_vucc'); ?></a>
												<?php } ?>
												<?php if ($user_awards->ffma == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/ffma'); ?>"><i class="fas fa-seedling"></i> <?php echo lang('menu_ffma'); ?></a>
												<?php } ?>
												<?php if ($user_awards->gridmaster_dl == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/gridmaster/dl'); ?>"><i class="fas fa-th"></i> <?php echo lang('menu_dl_gridmaster'); ?></a>
												<?php } ?>
												<?php if ($user_awards->gridmaster_lx == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/gridmaster/lx'); ?>"><i class="fas fa-th"></i> <?php echo lang('menu_lx_gridmaster'); ?></a>
												<?php } ?>
												<?php if ($user_awards->gridmaster_ja == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/gridmaster/ja'); ?>"><i class="fas fa-th"></i> <?php echo lang('menu_ja_gridmaster'); ?></a>
												<?php } ?>
												<?php if ($user_awards->gridmaster_us == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/gridmaster/us'); ?>"><i class="fas fa-th"></i> <?php echo lang('menu_us_gridmaster'); ?></a>
												<?php } ?>
												<?php if ($user_awards->gridmaster_uk == 1) { ?>
													<a class="dropdown-item" href="<?php echo site_url('awards/gridmaster/uk'); ?>"><i class="fas fa-th"></i> <?php echo lang('menu_uk_gridmaster'); ?></a>
												<?php } ?>
											<?php } ?>
										</div>
									<?php } ?>

									<?php if ($has_portable_awards) { ?>
										<div class="nav-mega-col">
											<h6 class="dropdown-header">Portable &amp; Special</h6>
											<?php if ($user_awards->pota == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/pota'); ?>"><i class="fas fa-tree"></i> <?php echo lang('menu_pota'); ?></a>
											<?php } ?>
											<?php if ($user_awards->sota == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/sota'); ?>"><i class="fas fa-mountain"></i> <?php echo lang('menu_sota'); ?></a>
											<?php } ?>
											<?php if ($user_awards->wwff == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/wwff'); ?>"><i class="fas fa-leaf"></i> <?php echo lang('menu_wwff'); ?></a>
											<?php } ?>
											<?php if ($user_awards->sig == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/sig'); ?>"><i class="fas fa-certificate"></i> <?php echo lang('menu_sig'); ?></a>
											<?php } ?>
											<?php if (!empty($user_awards->skcc)) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/skcc'); ?>"><i class="fas fa-key"></i> <?php echo lang('menu_skcc'); ?></a>
											<?php } ?>
											<?php if (!empty($user_awards->rsgb)) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/rsgb'); ?>"><i class="fas fa-award"></i> <?php echo lang('menu_rsgb'); ?></a>
											<?php } ?>
											<?php foreach ($visible_plugin_awards as $plugin_award_entry) { ?>
												<a class="dropdown-item" href="<?php echo site_url($plugin_award_entry['route']); ?>"><i class="<?php echo htmlspecialchars($plugin_award_entry['icon']); ?>"></i> <?php echo htmlspecialchars($plugin_award_entry['title']); ?></a>
											<?php } ?>
										</div>
									<?php } ?>

									<?php if ($has_regional_awards) { ?>
										<div class="nav-mega-col">
											<h6 class="dropdown-header">Regional</h6>
											<?php if ($user_awards->was == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/was'); ?>"><i class="fas fa-flag-usa"></i> <?php echo lang('menu_was'); ?></a>
											<?php } ?>
											<?php if ($user_awards->uscounties == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/counties'); ?>"><i class="fas fa-map-marked-alt"></i> <?php echo lang('menu_us_counties'); ?></a>
											<?php } ?>
											<?php if ($user_awards->wab == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/wab'); ?>"><i class="fas fa-map-pin"></i> Worked All Britain</a>
											<?php } ?>
											<?php if ($user_awards->dok == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/dok'); ?>"><i class="fas fa-flag"></i> <?php echo lang('menu_dok'); ?></a>
											<?php } ?>
											<?php if (!empty($user_awards->rac)) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/rac'); ?>"><i class="fas fa-award"></i> <?php echo lang('menu_rac'); ?></a>
											<?php } ?>
											<?php if (!empty($user_awards->rda)) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/rda'); ?>"><i class="fas fa-map-marked-alt"></i> <?php echo lang('menu_rda'); ?></a>
											<?php } ?>
											<?php if ($user_awards->waja == 1) { ?>
												<a class="dropdown-item" href="<?php echo site_url('awards/waja'); ?>"><i class="fas fa-torii-gate"></i> <?php echo lang('menu_waja'); ?></a>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
								<div class="nav-mega-footer">
									<a class="dropdown-item" href="<?php echo site_url('award'); ?>"><i class="fas fa-cog"></i> <?php echo lang('awards_menu_settings'); ?></a>
								</div>
							</div>
						</li>

						<!-- Tools Dropdown -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle<?php echo $nav_is_tools ? ' active' : ''; ?>" href="#" id="navTools" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Tools">Tools</a>
							<div class="dropdown-menu" aria-labelledby="navTools">
								<h6 class="dropdown-header">Tools</h6>
								<a class="dropdown-item" href="<?php echo site_url('propagationadvisor'); ?>" title="<?php echo lang('menu_propagation_advisor'); ?>"><i class="fas fa-clock"></i> <?php echo lang('menu_propagation_advisor'); ?></a>
								<a class="dropdown-item" href="<?php echo site_url('callhistory'); ?>" title="Call History"><i class="fas fa-address-book"></i> Call History</a>
								<a class="dropdown-item" href="<?php echo site_url('dxcluster'); ?>" title="DX Cluster"><i class="fas fa-broadcast-tower"></i> DX Cluster</a>
								<a class="dropdown-item" href="<?php echo site_url('workabledxcc'); ?>" title="Upcoming DXPeditions"><i class="fas fa-globe"></i> Upcoming DXPeditions</a>
								<a class="dropdown-item" href="<?php echo site_url('hamsat'); ?>" title="Hams.at"><i class="fas fa-list"></i> Hams.at</a>
							</div>
						</li>
					<?php } ?>
				</ul>

				<!-- Right Utility Cluster: Search + Admin + User + Extras -->
				<div class="nav-utilities d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 mt-2 mt-lg-0">
					<?php if ($this->session->userdata('user_quicklog') == 1) { ?>
						<script>
							function submitForm(action) {
								var form = document.getElementById('quicklog-form');
								if (action === 'search') {
									form.action = "<?php echo site_url('search'); ?>";
									form.method = "get";
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
						<?php if ($this->session->userdata('user_quicklog_enter') == 1) { ?>
							<script>
								function handleKeyPress(event) {
									if (event.key === 'Enter') {
										submitForm('search');
									}
								}
							</script>
						<?php } else { ?>
							<script>
								function handleKeyPress(event) {
									if (event.key === 'Enter') {
										logQuicklog();
									}
								}
							</script>
						<?php } ?>
						<form id="quicklog-form" class="nav-search-form" onsubmit="return false;">
							<div class="input-group input-group-sm nav-search-group">
								<input class="form-control" id="nav-bar-search-input" type="text" name="callsign" placeholder="<?php echo lang('menu_search_text_quicklog'); ?>" aria-label="Quicklog" onkeypress="handleKeyPress(event)">
								<button title="<?php echo lang('menu_search_button_qicksearch_log'); ?>" class="btn btn-outline-secondary" type="button" onclick="logQuicklog()"><i class="fas fa-plus"></i></button>
								<button title="<?php echo lang('menu_search_button'); ?>" class="btn btn-outline-secondary" type="button" onclick="submitForm('search')"><i class="fas fa-search"></i></button>
							</div>
						</form>
					<?php } else { ?>
						<form method="get" class="nav-search-form" action="<?php echo site_url('search'); ?>">
							<div class="input-group input-group-sm nav-search-group">
								<input class="form-control" id="nav-bar-search-input" type="search" name="callsign" placeholder="<?php echo lang('menu_search_text'); ?>" aria-label="Search">
								<button title="<?php echo lang('menu_search_button'); ?>" class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
							</div>
						</form>
					<?php } ?>

					<?php if (!$nav_is_logged_in) { ?>
						<!-- Not Logged In -->
						<form method="post" action="<?php echo site_url('user/login'); ?>" class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-1">
							<input class="form-control form-control-sm" type="text" name="user_name" placeholder="Username" aria-label="Username">
							<input class="form-control form-control-sm" type="password" name="user_password" placeholder="Password" aria-label="Password">
							<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
							<button class="btn btn-primary btn-sm text-nowrap" type="submit"><?php echo lang('menu_login_button'); ?></button>
						</form>
					<?php } else { ?>
						<ul class="navbar-nav align-items-lg-center gap-lg-1">
							<?php if ($nav_is_admin_user) { ?>
								<!-- Admin Dropdown -->
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle nav-icon-link<?php echo $nav_is_admin ? ' active' : ''; ?>" href="#" id="navAdmin" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo lang('menu_admin'); ?>">
										<i class="fas fa-users-cog"></i>
										<span class="d-inline d-lg-none ms-2"><?php echo lang('menu_admin'); ?></span>
									</a>
									<div class="dropdown-menu dropdown-menu-end" aria-labelledby="navAdmin">
										<h6 class="dropdown-header"><?php echo lang('menu_admin'); ?></h6>
										<a class="dropdown-item" href="<?php echo site_url('user'); ?>" title="Manage user accounts"><i class="fas fa-users"></i> <?php echo lang('menu_user_account'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('options'); ?>" title="Manage global options"><i class="fas fa-cog"></i> <?php echo lang('menu_global_options'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('mode'); ?>" title="Manage QSO modes"><i class="fas fa-broadcast-tower"></i> <?php echo lang('menu_modes'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('contesting/add'); ?>" title="Manage Contest names"><i class="fas fa-medal"></i> <?php echo lang('menu_contests'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('plugins'); ?>" title="Plugin Manager"><i class="fas fa-puzzle-piece"></i> Plugin Manager</a>
										<div class="dropdown-divider"></div>
										<h6 class="dropdown-header">System</h6>
										<a class="dropdown-item" href="<?php echo site_url('update'); ?>" title="Update Country Files"><i class="fas fa-download"></i> <?php echo lang('menu_update_country_files'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('maintenance'); ?>" title="maintenance"><i class="fas fa-wrench"></i> <?php echo lang('menu_maintenance'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('cronmanager'); ?>" title="<?php echo lang('menu_cron_scheduler'); ?>"><i class="fas fa-clock"></i> <?php echo lang('menu_cron_scheduler'); ?></a>
										<a class="dropdown-item" href="<?php echo site_url('debug'); ?>" title="Debug Information"><i class="fas fa-bug"></i> <?php echo lang('menu_debug_information'); ?></a>
									</div>
								</li>
							<?php } ?>

							<!-- Logged in As (User 2-Column Menu) -->
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle nav-user-trigger d-inline-flex align-items-center gap-1" href="#" id="navUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="position-relative d-inline-flex align-items-center">
										<i class="fas fa-user"></i>
										<?php if ($oqrs_requests > 0) { ?>
											<span class="nav-oqrs-dot" title="<?php echo $oqrs_requests; ?> OQRS requests"></span>
										<?php } ?>
									</span>
									<span class="fw-semibold"><?php echo $this->session->userdata('user_callsign'); ?></span>
								</a>

								<div class="dropdown-menu dropdown-menu-end nav-mega-menu nav-mega-2col nav-user-menu" aria-labelledby="navUser">
									<div class="nav-user-header">
										<div class="fw-semibold"><?php echo htmlspecialchars($this->session->userdata('user_callsign')); ?></div>
										<?php if ($active_logbook_name !== '') { ?>
											<div class="small text-body-secondary"><i class="fas fa-book-open me-1"></i><?php echo htmlspecialchars($active_logbook_name); ?></div>
										<?php } ?>
									</div>
									<div class="nav-mega-grid">
										<div class="nav-mega-col">
											<h6 class="dropdown-header">Station &amp; Account</h6>
											<a class="dropdown-item" href="<?php echo site_url('user/edit') . "/" . $this->session->userdata('user_id'); ?>" title="Account"><i class="fas fa-user"></i> <?php echo lang('menu_account'); ?></a>
											<?php if ($this->config->item('subscription_management_url')) { ?>
												<a class="dropdown-item" href="<?php echo $this->config->item('subscription_management_url'); ?>" target="_blank" title="Manage Subscription"><i class="fas fa-credit-card"></i> Manage Subscription</a>
											<?php } ?>
											<a class="dropdown-item" href="<?php echo site_url('logbooks'); ?>" title="Manage station logbooks"><i class="fas fa-book"></i> <?php echo lang('menu_station_logbooks'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('station'); ?>" title="Manage station locations"><i class="fas fa-map-marker-alt"></i> <?php echo lang('menu_station_locations'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('band'); ?>" title="Manage Bands"><i class="fas fa-wave-square"></i> <?php echo lang('menu_bands'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('radio'); ?>" title="Interface with one or more radios"><i class="fas fa-plug"></i> <?php echo lang('menu_hardware_interfaces'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('api/help'); ?>" title="Manage API keys"><i class="fas fa-key"></i> <?php echo lang('menu_api_keys'); ?></a>

											<h6 class="dropdown-header mt-2">QSL &amp; Printing</h6>
											<a class="dropdown-item d-flex align-items-center justify-content-between" href="<?php echo site_url('oqrs/requests'); ?>" title="OQRS Requests">
												<span><i class="fa fa-envelope-open-text"></i> <?php echo lang('menu_oqrs_requests'); ?></span>
												<?php if ($oqrs_requests > 0) { ?>
													<span class="badge text-bg-danger ms-2"><?php echo $oqrs_requests; ?></span>
												<?php } ?>
											</a>
											<a class="dropdown-item" href="<?php echo site_url('qslprint'); ?>" title="Print Requested QSLs"><i class="fas fa-print"></i> <?php echo lang('menu_print_requested_qsls'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('labels'); ?>" title="Label setup"><i class="fas fa-address-card"></i> <?php echo lang('menu_labels'); ?></a>
										</div>

										<div class="nav-mega-col">
											<h6 class="dropdown-header">Sync &amp; Logbooks</h6>
											<a class="dropdown-item" href="<?php echo site_url('adif'); ?>" title="Amateur Data Interchange Format (ADIF) import / export"><i class="fas fa-sync-alt"></i> <?php echo lang('menu_adif_import_export'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('lotw'); ?>" title="Synchronise with Logbook of the World (LoTW)"><i class="fas fa-sync"></i> <?php echo lang('menu_logbook_of_the_world'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('eqsl/import'); ?>" title="eQSL import / export"><i class="fas fa-sync"></i> <?php echo lang('menu_eqsl_import_export'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('qrz/export'); ?>" title="Upload to QRZ.com logbook"><i class="fas fa-sync"></i> <?php echo lang('menu_qrz_logbook'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('hrdlog/export'); ?>" title="Upload to HRDLog.net logbook"><i class="fas fa-upload"></i> <?php echo lang('menu_hrd_logbook'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('webadif/export'); ?>" title="Upload to webADIF"><i class="fas fa-upload"></i> <?php echo lang('menu_qo_100_dx_club_upload'); ?></a>

											<h6 class="dropdown-header mt-2">Other Exports &amp; Backup</h6>
											<a class="dropdown-item" href="<?php echo site_url('kmlexport'); ?>" title="KML Export for Google Earth"><i class="fas fa-map"></i> <?php echo lang('menu_kml_export'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('dxatlas'); ?>" title="DX Atlas Gridsquare Export"><i class="fas fa-globe-europe"></i> <?php echo lang('menu_dx_atlas_gridsquare_export'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('csv'); ?>" title="SOTA CSV Export"><i class="fas fa-mountain"></i> <?php echo lang('menu_sota_csv_export'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('cabrillo'); ?>" title="Cabrillo Export"><i class="fas fa-file-alt"></i> <?php echo lang('menu_cabrillo_export'); ?></a>
											<a class="dropdown-item" href="<?php echo site_url('backup'); ?>" title="Backup Cloudlog content"><i class="fas fa-database"></i> <?php echo lang('menu_backup'); ?></a>
										</div>
									</div>
									<div class="nav-mega-footer d-flex flex-wrap align-items-center justify-content-between gap-2">
										<div class="d-flex flex-wrap align-items-center gap-3 small">
											<a class="nav-footer-link" href="javascript:displayVersionDialog();" title="Version Information"><i class="fas fa-info-circle"></i> <?php echo lang('options_version_dialog'); ?></a>
											<a class="nav-footer-link" target="_blank" href="https://docs.cloudlog.org/" title="Help"><i class="fas fa-question-circle"></i> <?php echo lang('menu_help'); ?></a>
											<a class="nav-footer-link" target="_blank" href="https://github.com/magicbug/Cloudlog/discussions" title="Forum"><i class="far fa-comment-dots"></i> <?php echo lang('menu_forum'); ?></a>
										</div>
										<a class="nav-footer-link text-danger fw-semibold small" href="<?php echo site_url('user/logout'); ?>" title="Logout"><i class="fas fa-sign-out-alt"></i> <?php echo lang('menu_logout'); ?></a>
									</div>
								</div>
							</li>

							<?php
							// Can add extra menu items by defining them in options. The format is json.
							// Useful to add extra things in Cloudlog without the need for modifying files. If you add extras, these files will not be overwritten when updating.
							if ($this->optionslib->get_option('menuitems')) { ?>
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#" id="navExtras" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Extras</a>
									<div class="dropdown-menu dropdown-menu-end" aria-labelledby="navExtras">
										<?php
										foreach (json_decode($this->optionslib->get_option('menuitems')) as $item) {
											echo '<a class="dropdown-item" href="' . site_url($item->url) . '" title="' . htmlspecialchars($item->text) . '"><i class="fas ' . htmlspecialchars($item->icon) . '"></i> ' . htmlspecialchars($item->text) . '</a>';
										}
										?>
									</div>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				</div>

			</div>
		</div>
	</nav>
