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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->optionslib->get_theme();?>/overrides.css">
	<?php } ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.fancybox.min.css" />

    <?php
    $load_leaflet = in_array($this->uri->segment(1), [NULL, '', 'dashboard', 'logbook', 'logbookadvanced', 'gridmap', 'activated_gridmap', 'qso', 'map', 'activators', 'activatorsmap'], true)
        || ($this->uri->segment(1) == 'awards' && in_array($this->uri->segment(2), ['cq', 'iota', 'dxcc', 'ffma', 'gridmaster', 'waja', 'was', 'sota', 'pota'], true));
    ?>

    <!-- Maps -->
    <?php if ($load_leaflet) { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />
    <?php } ?>

    <link rel="icon" href="<?php echo base_url(); ?>favicon.ico">

    <title><?php if(isset($page_title)) { echo $page_title; } ?> - Cloudlog</title>
  </head>
  <body>
