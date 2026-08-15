<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <?php if($this->optionslib->get_theme()) { ?>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->optionslib->get_theme();?>/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/general.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/visitor.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/selectize.bootstrap4.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-dialog.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->optionslib->get_theme();?>/overrides.css">
	<?php } ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.fancybox.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/flag-icons.min.css" />
	
    <!-- Maps -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loading.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ldbtn.min.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/buttons.dataTables.min.css"/>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datatables.min.css"/>

	<?php if (file_exists(APPPATH.'../assets/css/custom.css')) { echo '<link rel="stylesheet" href="'.base_url().'assets/css/custom.css">'; } ?>

    <link rel="icon" href="<?php echo base_url(); ?>favicon.ico">

    <title><?php if(isset($page_title)) { echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); } ?></title>
  </head>
  <body class="visitor-body">
<?php
	$slug = isset($slug) ? $slug : '';
	$brand_name = isset($brand_name) && $brand_name !== '' ? $brand_name : 'Cloudlog';
	$public_search_enabled = !empty($public_search_enabled);
	$oqrs_enabled = !empty($oqrs_enabled);
	$seg1 = $this->uri->segment(1);
	$seg2 = $this->uri->segment(2);
	$reserved = array('search', 'satellites', 'gridsquares');
	$is_home = ($seg1 == 'visitor' && !in_array($seg2, $reserved));
	$is_grids = ($seg1 == 'visitor' && in_array($seg2, array('satellites', 'gridsquares')));
	$is_oqrs = ($seg1 == 'oqrs');
	$brand_href = !empty($slug) ? site_url('visitor/'.$slug) : site_url();
	$brand_display = str_replace('0', '&Oslash;', htmlspecialchars($brand_name, ENT_QUOTES, 'UTF-8'));
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light main-nav visitor-nav">
<div class="container">
		<a class="navbar-brand" href="<?php echo $brand_href; ?>"><?php echo $brand_display; ?></a>
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

	<div class="collapse navbar-collapse" id="navbarNav">

		<ul class="navbar-nav me-auto">
		<?php if (!empty($slug)) { ?>
		<li class="nav-item">
			<a class="nav-link<?php echo $is_home ? ' active' : ''; ?>" href="<?php echo site_url('visitor/'.$slug); ?>"><?php echo lang('visitor_logbook'); ?></a>
		</li>
		<li class="nav-item">
			<a class="nav-link<?php echo $is_grids ? ' active' : ''; ?>" href="<?php echo site_url('visitor/gridsquares/'.$slug); ?>"><?php echo lang('menu_gridsquares'); ?></a>
		</li>
		<?php if ($oqrs_enabled) { ?>
			<li class="nav-item">
				<a class="nav-link<?php echo $is_oqrs ? ' active' : ''; ?>" href="<?php echo site_url('oqrs/'.$slug); ?>"><?php echo lang('visitor_request_qsl'); ?></a>
			</li>
		<?php } ?>
		<?php } ?>
		</ul>
		<?php if (!empty($slug) && $public_search_enabled && !$is_home && !$is_oqrs) { ?>
					<form method="post" name="searchForm" action="<?php echo site_url('visitor/search'); ?>" onsubmit="return validateForm()" class="d-flex align-items-center">
						<input class="form-control me-sm-2" id="searchcall" type="search" name="callsign" placeholder="<?php echo lang('visitor_search_callsign'); ?>" <?php if (isset($callsign) && $callsign != '') { echo 'value="'.htmlspecialchars(strtoupper($callsign), ENT_QUOTES, 'UTF-8').'"'; } ?> aria-label="Search" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo lang('visitor_search_callsign'); ?>">
						<input type="hidden" name="public_slug" value="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>">
						<button title="<?php echo lang('menu_search_button'); ?>" class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i>
							<div class="d-inline d-lg-none" style="padding-left: 10px"><?php echo lang('menu_search_button'); ?></div>
						</button>
					</form>
		<?php } ?>
	</div>
</div>
</nav>
