<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Web Logbook</title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/reset.css" type="text/css" />
	<link type="text/css" href="<?php echo base_url(); ?>css/flick/jquery-ui-1.8.12.custom.css" rel="stylesheet" />	

	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.5.1.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.12.custom.min.js"></script>
	
	<script type="text/javascript">
	$(function(){
		// Accordion
		$("#tabs").tabs();
		$( "button, input:submit", ".wrap_content" ).button();
		$( "button, input:submit", ".contest_wrap" ).button();
		$( "#admin" ).click(function() {
			$( "#submenu" ).toggle( 'blinds', null, 500 );
			$( "#clear" ).toggle( 'blinds', null, 500 );
			return false;
		});
	});
</script>
	<style type="text/css" media="screen" >
		/* Base CSS */
		body { background-color: #e6e6e6; font-size: 15px; font-family: Arial, "Trebuchet MS", sans-serif; }
		td { padding: 1px;}
		.tr1 td { background:none repeat scroll 0 0 #F0FFFF; }
		.partial td, .logbook td, .users td { padding: 5px; }
		#subnav { height: 30px; color:#ffffff; clear: both; }
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
		#nav { background-image: url('<?php echo base_url(); ?>/images/nav_bg.gif'); height: 39px; color:#ffffff; border-bottom: 1px solid #9bc9ed; }
		.log_title { background-image: url('<?php echo base_url(); ?>/images/grey_bg.png'); background-repeat: repeat-x; color: #439bf6; }
		.auth_title { background-image: url('<?php echo base_url(); ?>/images/grey_bg.png'); background-repeat: repeat-x; color: #439bf6; }
		.small { font-size: 9px; }
		.error { color: #a00; }
		p { line-height: 1.7; margin: 0px 0; }
		.wrap_content { margin: 0 auto; width: 780px; border: 1px solid #d7d7d7; background-color: #ffffff; padding-bottom: 5px; }
		#footer { margin: 0 auto; width: 780px; text-align: center; padding-top: 5px; padding-bottom: 5px; font-size: 12px; }
		#message { margin: 0 auto; width: 770px; border: 1px solid #fcefa1; background-color: #fbfaf3; padding: 5px; margin-top: 5px; margin-bottom: 5px; font-weight: bold; font-size: 12px; }
		#message p { line-height: 1.7; margin: 0px 0; }
		.clear { clear: both; }
		h2 { margin: 0 auto; width: 780px; font-weight: bold; font-size: 23px; margin-top: 5px; margin-bottom: 10px; }
		h3 { font-weight: bold; font-size: 16px; margin: 5px; margin-left: 0px; }
		table .titles { font-weight: bold; }
		#tabs { margin: 5px; }
		a { text-decoration: none; color: #000; } 
		a:hover { text-decoration: underline; }
	
		/* Nav List CSS */
		ul#navlist { font: bold 15px "Trebuchet MS", sans-serif; list-style-type: none; margin: 0; margin-left: 10px; }
		ul#navlist li.active { float: left; background-image: none; background-color: #fff; margin: 2px 2px 0 3px; height:34px; text-align:center; }
		ul#navlist li { float: left; margin: 2px 2px 0 3px; height:43px; border-bottom: none; text-align:center; }
		#navlist .active a{ color: #ebebeb ; }
		#navlist a { float: left; display: block; color: #ebebeb; text-decoration: none; padding-top: 7px; padding-left: 6px; padding-right: 5px; text-align:center; }
		#navlist a:hover {  }
	
		/* Submenu List CSS */
		ul#sublist { font: bold 15px "Trebuchet MS", sans-serif; list-style-type: none; margin: 0; margin-left: 10px; }
		ul#sublist li.active { float: left; background-image: none; background-color: #fff; margin: 2px 2px 0 3px; height:34px; text-align:center; }
		ul#sublist li { float: left; margin: 2px 2px 0 3px; height:20px; border-bottom: none; text-align:center; background-color: #fff; padding: 3px;}
		#sublist .active a{ color: #ebebeb ; }
		#sublist a { float: left; display: block; color: #000; text-decoration: none; padding-top: 0px; padding-left: 6px; padding-right: 5px; text-align:center; }
		#sublist a:hover {  }
	
		/* User CSS */
		ul#user { float: right; font: bold 15px "Trebuchet MS", sans-serif; list-style-type: none; margin: 0; margin-left: 10px; }
		ul#user li.active { background-image: none; background-color: #fff; margin: 2px 2px 0 3px; height:34px; text-align:center; }
		ul#user li { float: left; margin: 2px 2px 0 3px; height:43px; border-bottom: none; text-align:center; }
		#user .active a{ color: #ebebeb ; }
		#user a { float: left; display: block; color: #ebebeb; text-decoration: none; padding-top: 7px; padding-left: 6px; padding-right: 5px; text-align:center; }
		#user a:hover {  }
		.user { padding: 5px; }
	
		/* QSO Logging CSS */
		#callsign { text-transform: uppercase; }
		.controls { margin: 5px; }
		.title { padding-top: 5px; padding-bottom: 5px; color: #0073EA; font-weight: bold; }
		#qso_input { border: 1px solid #dddddd; margin: 5px; padding: 2px; }
		.input_date { width: 70px; }
		.input_time { width: 54px; }
		#locator { width: 55px; text-transform: uppercase; }
		#country { border: none; }
		#locator_info { font-size: 13px; }
		#name { width: 145px; }
		#comment { width: 89.5%; }
	
		/* Note CSS */
		.note { padding: 5px; }
		.auth { padding: 5px; }
		#notes_add { padding: 5px; }
		#search_box { padding: 5px; }
		ul.notes_list {list-style-type: circle; padding-left: 20px; }

		/* Contest CSS */
		.contest_wrap { margin: 0 auto; width: 95%; }
		.contest_wrap h2 { margin: 0; width: 100%; font-weight: bold; font-size: 23px; margin-top: 5px; margin-bottom: 10px; }
		.contest_view { margin: 0 auto; width: 100%; border: 1px solid #d7d7d7; background-color: #ffffff; padding: 5px; }
		.contest_sidebar { width: 30%; float: right; margin: 0 auto; }
		.contest_qso_box { width: 695px; margin: 5px; padding: 5px; border: 1px solid #dddddd; border-radius: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px; }
		.contest_qso_box table { width: 695px; }

		/* Dashboard CSS */
		.dashboard { padding: 5px; }
		.dashboard_top { margin-top: 5px; }
		.dashboard_log { float: left; width: 440px; }
		.dashboard_breakdown { float: right; width: 310px; }
		.dashboard_bottom .chart { float: left; }
		td.item { padding-bottom: 5px; }
		.dashboard_breakdown .title { color: #439BF6; }

		/* Tabs CSS */
		.ui-widget-content { border: none; }
		.ui-widget-header { background: none; border: none; border-bottom: 1px solid #DDD; }

		.pager {
			margin-top: 5px;
			margin-bottom: 5px;
			font-size: 12px;
		}
		.pager a, strong {
			border: 1px solid #D7D7D7;
			padding: 5px;
		}
		.pager a:hover {
			background-color: azure;
		}

	</style>
	
	<script src="http://maps.google.com/maps?file=api&amp;v=3&amp;key=<?php echo $this->config->item('google_maps_api'); ?>&sensor=true"
			type="text/javascript"></script>
</head>

<body onunload="GUnload()"> 
	
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
			<li><a href="<?php echo site_url('adif/export');?>" title="ADIF Export">ADIF Export</a></li>
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
<div id="clear" class="clear"></div>