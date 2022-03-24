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
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/selectize.bootstrap4.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-dialog.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->optionslib->get_theme();?>/overrides.css">
	<?php } ?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.fancybox.min.css" />

    <!-- Maps -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loading.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ldbtn.min.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/buttons.dataTables.min.css"/>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datatables.min.css"/>

	<?php if (file_exists(APPPATH.'../assets/css/custom.css')) { echo '<link rel="stylesheet" href="'.base_url().'assets/css/custom.css">'; } ?>

    <link rel="icon" href="<?php echo base_url(); ?>favicon.ico">

    <title><?php if(isset($page_title)) { echo $page_title; } ?> - Cloudlog</title>
  </head>
  <body>

<nav class="navbar navbar-expand-lg navbar-light bg-light main-nav">
<div class="container">

	<a class="navbar-brand" href="<?php echo site_url('visitor/'.$slug);?>">Cloudlog</a> <?php if(ENVIRONMENT == "development") { ?><span class="badge badge-danger">Developer Mode</span><?php } ?>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

	<div class="collapse navbar-collapse" id="navbarNav">

		<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" href="<?php echo site_url('visitor/satellites/'.$slug);?>">Gridsquares</a>
		</li>
		<li class="nav-item">
			 <a class="btn btn-outline-primary" href="<?php echo site_url('user/login');?>">Login</a>
		</li>
		</ul>
	</div>
</div>
</nav>
