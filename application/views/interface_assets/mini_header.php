<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <?php if($this->session->userdata('user_stylesheet')) { ?>
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/<?php echo $this->session->userdata('user_stylesheet');?>">
    <?php } else { ?>
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <?php } ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.fancybox.min.css" />

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/general.css">

    <!-- Maps -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />

    <link rel="icon" href="<?php echo base_url(); ?>/favicon.ico">

    <title><?php if(isset($page_title)) { echo $page_title; } ?> - Cloudlog</title>
  </head>
  <body>