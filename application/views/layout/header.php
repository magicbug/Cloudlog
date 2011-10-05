<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Web Logbook</title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/reset.css" type="text/css" />
	<link type="text/css" href="<?php echo base_url(); ?>css/flick/jquery-ui-1.8.12.custom.css" rel="stylesheet" />	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/global.css">

	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.12.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/global.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
	
	<style type="text/css" media="screen" >
		#submenu { height: 30px; color:#ffffff; clear: both; <?php
	
		if($this->session->userdata('user_type') == 99) {
			switch ($this->uri->segment(1)) {
				case "user":
					?> display: normal; <?php
					break;
				default:
					?> display: none; <?php
			}
		} else {
					?> display: none; visibility: hidden; <?php
		}?> }

	</style>
</head>

<body> 
	
	<div id="nav">
		
		<ul id="navlist">
			<?php if((($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= $this->config->item('auth_mode'))) || $this->config->item('use_auth') === FALSE) { ?>
			<li><a href="<?php echo site_url();?> " title="Dashboard">Dashboard</a></li>
			<li><a href="<?php echo site_url('logbook');?>" title="Logbook">Logbook</a></li>
			<li><a href="<?php echo site_url('search');?>" title="Search">Search</a></li>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
			<li><a href="<?php echo site_url('qso');?>" title="Add QSO">Add QSO</a></li>
			<li><a href="<?php echo site_url('contest');?>" title="Contests">Contests</a></li>
			<?php } ?>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE){ ?>
			<li><a href="<?php echo site_url('notes');?>" title="Notes">Notes</a></li>
			<li><a href="<?php echo site_url('qsl');?>" title="Notes">QSLing</a></li>
			<?php } ?>
			<li><a href="<?php echo site_url('statistics');?>" title="Statistics">Statistics</a></li>
			<?php if(($this->config->item('use_auth') && $this->session->userdata('user_type') >= 99) || $this->config->item('use_auth') === FALSE) { ?>
			<li><a href="#" id="admin">Admin</a></li>
			<?php }} ?>
		</ul>

		<?php if($this->config->item('use_auth')) { ?>
		<ul id="user">
			<?php if($this->session->userdata('user_id')) { ?>
			<li><a href="<?php echo site_url('user/profile');?>"><?php echo $this->session->userdata('user_name'); ?></a></li>
			<li><a href="<?php echo site_url('user/logout');?>">Logout</a></li>
			<?php } else { ?>
			<li><a href="<?php echo site_url('user/login');?>">Log in</a></li>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>
	<div id="submenu">
		<ul id="sublist">
			<?php if(($this->config->item('use_auth') && $this->session->userdata('user_type') >= 99) || $this->config->item('use_auth') === FALSE){ ?>
			<li class="ui-corner-all"><a href="<?php echo site_url('user');?>" title="Users">Users</a></li>
			<li><a href="<?php echo site_url('setup');?>" title="Setup">Setup</a></li>
			<li><a href="<?php echo site_url('backup');?>" title="Backup">Backup</a></li>
			<li><a href="<?php echo site_url('adif/export');?>" title="ADIF Export">ADIF Export</a></li>
			<li><a href="<?php echo site_url('api/help');?>" title="API">API</a></li>
			<?php } ?>
		</ul>
	</div>
	
	<?php if($this->uri->segment(1) == "contest" && $this->uri->segment(2) != "view") { ?>
	<div id="subnav">
		<ul id="sublist">
			<li class="ui-corner-all"><a href="<?php echo site_url('contest');?>" title="View Contest Logs">Contest Logs</a></li>
			<li><a href="<?php echo site_url('contest/create');?>" title="Create Contest">Create Contest</a></li>
			<li><a href="<?php echo site_url('contest/add_template');?>" title="Create Template">Create Template</a></li>
		</ul>
	</div>
	<?php } ?>
	
	<?php if($this->uri->segment(1) == "notes") { ?>
	<div id="subnav">
		<ul id="sublist">
			<li class="ui-corner-all"><a href="<?php echo site_url('notes');?>" title="Note">Notes</a></li>
		</ul>
		<ul id="sublist">
			<li class="ui-corner-all"><a href="<?php echo site_url('notes/add');?>" title="Add a note">Add Note</a></li>
		</ul>
	</div>
	<?php } ?>

	<?php if($this->uri->segment(1) == "backup") { ?>
	<div id="subnav">
		<ul id="sublist">
			<li class="ui-corner-all"><a href="<?php echo site_url('backup/adif');?>" title="Backup Logbook">Logbook</a></li>
			<li class="ui-corner-all"><a href="<?php echo site_url('backup/notes');?>" title="Backup Notes">Notes</a></li>
		</ul>
	</div>
	<?php } ?>

	<?php if($this->uri->segment(1) == "qsl") { ?>
	<div id="subnav">
		<ul id="sublist">
			<li class="ui-corner-all"><a href="<?php echo site_url('qsl/print');?>" title="Print QSL Labels">Print Labels</a></li>
		</ul>
	</div>
	<?php } ?>
<div id="clear" class="clear"></div>
