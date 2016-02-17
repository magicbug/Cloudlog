<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $page_title; ?> - Cloudlog</title>

	<!-- Javascript -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.12.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-dropdown.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-tabs.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/global.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

	<!-- CSS Files -->
	<link type="text/css" href="<?php echo base_url(); ?>css/flick/jquery-ui-1.8.12.custom.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootcamp/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/main.css" type="text/css" />

	<!-- Sticky Footer IE -->
	<!--[if !IE 7]>
	<style type="text/css">
		#wrap {display:table;height:100%}
	</style>
	<![endif]-->

	<!-- Theming Code Goes Here -->

	<!-- Icons -->
	<link rel="icon" href="<?php echo base_url(); ?>/CloudLog.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url(); ?>/CloudLog.ico" type="image/x-icon" />
</head>

<body>

	<!-- Header -->
<div id="wrap">
    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="<?php echo site_url(); ?>">Cloudlog</a>
          <ul class="nav">
            <li class="active"><a href="<?php echo site_url('logbook');?>" title="Logbook">Logbook</a></li>

            <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
			<li class="dropdown" data-dropdown="dropdown" >
				<a href="#" class="dropdown-toggle">QSOs</a>
				<ul class="dropdown-menu">
				 <li><a href="<?php echo site_url('qso');?>" title="qso">Live QSOs</a></li>
				  <li class="divider"></li>
				  <li><a href="<?php echo site_url('qso/manual');?>" title="Notes">Post QSOs</a></li>
			</ul>

			<?php } ?>


			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE){ ?>

			<li class="dropdown" data-dropdown="dropdown" >
			    <a href="#" class="dropdown-toggle">Notes</a>
			    <ul class="dropdown-menu">
			     <li><a href="<?php echo site_url('notes');?>" title="Notes">View Notes</a></li>
			      <li class="divider"></li>
			      <li><a href="<?php echo site_url('notes/add');?>" title="Notes">Create Note</a></li>
			</ul>

			<?php } ?>
			<li><a href="<?php echo site_url('statistics');?>" title="Statistics">Statistics</a></li>

			<?php if(($this->config->item('use_auth') && $this->session->userdata('user_type') >= 99) || $this->config->item('use_auth') === FALSE) { ?>

			<li class="dropdown" data-dropdown="dropdown" >
				<a href="#" class="dropdown-toggle">Tools</a>
				<ul class="dropdown-menu">
				 <li><a href="<?php echo site_url('awards');?>" title="">Awards</a></li>
				 <li><a href="<?php echo site_url('dxcluster');?>" title="DX Cluster">Cluster</a></li>
				</ul>
			</li>

			<li class="dropdown" data-dropdown="dropdown" >
				<a href="#" class="dropdown-toggle">Admin</a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo site_url('user');?>" title="Users">Users</a></li>
					<li><a href="<?php echo site_url('radio');?>" title="Backup">Radios</a></li>
					<li><a href="<?php echo site_url('backup');?>" title="Backup">Backup</a></li>
					<li><a href="<?php echo site_url('adif/import');?>" title="ADIF Import">ADIF Import</a></li>
					<!-- <li><a href="<?php echo site_url('adif/export');?>" title="ADIF Export">ADIF Export</a></li> -->
					<li><a href="<?php echo site_url('export');?>" title="Data Export">Data Export</a></li>
					<li><a href="<?php echo site_url('api/help');?>" title="API">API</a></li>
					<li><a href="<?php echo site_url('eqsl/import');?>" title="eQSL Import">eQSL Import</a></li>
					<li><a href="<?php echo site_url('eqsl/export');?>" title="eQSL Import">eQSL Export</a></li>
					<li><a href="<?php echo site_url('lotw/import');?>" title="LoTW Import">LoTW Import</a></li>
					<li><a href="<?php echo site_url('lotw/export');?>" title="LoTW Export">LoTW Export</a></li>
				</ul>
				</ul>
			<?php } ?>
        	</ul>

		<!-- Search Form -->
        <form method="post" action="<?php echo site_url('search'); ?>"><input type="text" name="callsign" placeholder="Search Callsign"></form>

        <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
		<!-- Profile Dropdown -->
        	<div class="pull-right">
	        	<ul>
	        	<li class="dropdown" data-dropdown="dropdown" >
				    <a href="#" class="dropdown-toggle">Logged in as <?php echo $this->session->userdata('user_callsign'); ?></a>
				    <ul class="dropdown-menu">
				     <li><a href="<?php echo site_url('user/profile');?>" title="Profile">Profile</a></li>
				     <li><a href="<?php echo site_url('user/logout');?>" title="Logout">Logout</a></li>
				</ul>
			</div>
        <?php } else { ?>
		<!-- Login Form  -->
          <form method="post" action="<?php echo site_url('user/login'); ?>" class="pull-right">
            <input class="input-small" type="text" name="user_name" placeholder="Username">
            <input class="input-small" type="password" name="user_password" placeholder="Password">
            <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
            <button class="btn" type="submit">Sign in</button>
          </form>
          <?php } ?>
        </div>
      </div>

      <div id="clear" class="clear"></div>

    </div>

<div id="clear" class="clear"></div>
