<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo $page_title; ?> - Cloudlog</title>
	<link type="text/css" href="<?php echo base_url(); ?>css/flick/jquery-ui-1.8.12.custom.css" rel="stylesheet" />	

	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.12.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-dropdown.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-tabs.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>js/global.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>


	<link rel="stylesheet" href="<?php echo base_url();?>css/bootcamp/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/main.css" type="text/css" />

</head>

<body> 

    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="<?php echo site_url(); ?>">Cloudlog</a>
          <ul class="nav">
            <li class="active"><a href="<?php echo site_url('logbook');?>" title="Logbook">Logbook</a></li>

            <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
			<li><a href="<?php echo site_url('qso');?>" title="Add QSO">Add QSO</a></li>
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
			    <a href="#" class="dropdown-toggle">Admin</a>
			    <ul class="dropdown-menu">
			     <li><a href="<?php echo site_url('setup');?>" title="Setup">Setup</a></li>
			     <li><a href="<?php echo site_url('user');?>" title="Users">Users</a></li>
			     <li><a href="<?php echo site_url('backup');?>" title="Backup">Backup</a></li>
			     <li><a href="<?php echo site_url('adif/export');?>" title="ADIF Export">ADIF Export</a></li>
			     <li><a href="<?php echo site_url('api/help');?>" title="API">API</a></li>
			</ul>

			<?php } ?>

          </ul>

        <form method="post" action="<?php echo site_url('search'); ?>"><input type="text" name="callsign" placeholder="Search Callsign"></form>

        <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
        	<p class="pull-right">Logged in as <a href="#"><?php echo $this->session->userdata('user_callsign'); ?></a></p>
        <?php } else { ?>
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
