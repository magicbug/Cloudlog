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
	});
</script>
	<style type="text/css" media="screen" >
body { background-color: #e6e6e6; font-family: Arial, "Trebuchet MS", sans-serif; }
td { padding: 1px;}
.tr1 td { background:none repeat scroll 0 0 #F0FFFF; }
.partial td, .logbook td, .users td { padding: 5px; }
#nav {  background-image: url('<?php echo base_url(); ?>/images/nav_bg.gif'); height: 39px; color:#ffffff; border-bottom: 1px solid #9bc9ed; }
.log_title { background-image: url('<?php echo base_url(); ?>/images/grey_bg.png'); background-repeat: repeat-x; color: #439bf6; }
.auth_title { background-image: url('<?php echo base_url(); ?>/images/grey_bg.png'); background-repeat: repeat-x; color: #439bf6; }
.small { font-size: 9px; }

/* Nav List CSS */
ul#navlist { font: bold 15px "Trebuchet MS", sans-serif; list-style-type: none; margin: 0; margin-left: 10px; }
ul#navlist li.active { float: left; background-image: none; background-color: #fff; margin: 2px 2px 0 3px; height:34px; text-align:center; }
ul#navlist li { float: left; margin: 2px 2px 0 3px; height:43px; border-bottom: none; text-align:center; }
#navlist .active a{ color: #ebebeb ; }
#navlist a { float: left; display: block; color: #ebebeb; text-decoration: none; padding-top: 7px; padding-left: 6px; padding-right: 5px; text-align:center; }
#navlist a:hover {  }

ul#user { float: right; font: bold 15px "Trebuchet MS", sans-serif; list-style-type: none; margin: 0; margin-left: 10px; }
ul#user li.active { background-image: none; background-color: #fff; margin: 2px 2px 0 3px; height:34px; text-align:center; }
ul#user li { float: left; margin: 2px 2px 0 3px; height:43px; border-bottom: none; text-align:center; }
#user .active a{ color: #ebebeb ; }
#user a { float: left; display: block; color: #ebebeb; text-decoration: none; padding-top: 7px; padding-left: 6px; padding-right: 5px; text-align:center; }
#user a:hover {  }


.wrap_content { margin: 0 auto; width: 780px; border: 1px solid #d7d7d7; background-color: #ffffff; padding-bottom: 5px; }

#footer { margin: 0 auto; width: 780px; text-align: center; padding-top: 5px; padding-bottom: 5px; font-size: 12px; }

#message { margin: 0 auto; width: 770px; border: 1px solid #fcefa1; background-color: #fbfaf3; padding: 5px; margin-top: 5px; margin-bottom: 5px; font-weight: bold; font-size: 12px; }
#message p { line-height: 1.7; margin: 0px 0; }
.clear { clear: both; }
h2 { margin: 0 auto; width: 780px; font-weight: bold; font-size: 23px; margin-top: 5px; margin-bottom: 10px; }
h3 { font-weight: bold; font-size: 16px; margin: 5px; }
table .titles { font-weight: bold; }
#tabs { margin: 5px; }

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

.dash_left { float: left; width: 430px; }
.dash_sidebar { float: right; width: 350px; }
.note { padding: 5px; }
.auth { padding: 5px; }
#notes_add { padding: 5px; }
#search_box { padding: 5px; }

a { text-decoration: none; color: #000; } 
a:hover { text-decoration: underline; }


ul.notes_list {list-style-type: circle; padding-left: 20px; }

p {
line-height: 1.7;
margin: 10px 0;
}

.contest_wrap { margin: 0 auto; width: 95%; }
.contest_wrap h2 { margin: 0; width: 100%; font-weight: bold; font-size: 23px; margin-top: 5px; margin-bottom: 10px; }
.contest_view { margin: 0 auto; width: 100%; border: 1px solid #d7d7d7; background-color: #ffffff; padding: 5px; }
.contest_sidebar {
	width: 30%;
	float: right;
	margin: 0 auto;
}
	</style>
	
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->config->item('google_maps_api'); ?>&sensor=true"
			type="text/javascript"></script>
</head>

<body onunload="GUnload()"> 
	
	<div id="nav">
		
		<ul id="navlist">
			<li><a href="<?php echo site_url();?> " title="Dashboard">Dashboard</a></li>
			<li><a href="<?php echo site_url('logbook');?>" title="View Log">View Log</a></li>
			<li><a href="<?php echo site_url('search');?>" title="Search">Search</a></li>
			<?php if(!$this->config->item('use_auth') || $this->session->userdata('user_type') >= 2) { ?>
			<li><a href="<?php echo site_url('qso');?>" title="Add QSO">Add QSO</a></li>
			<li><a href="<?php echo site_url('contest');?>" title="Contests">Contests</a></li>
			<li><a href="<?php echo site_url('notes');?>" title="Notes">Notes</a></li>
			<?php } ?>
			<li><a href="<?php echo site_url('statistics');?>" title="Statistics">Statistics</a></li>
			<?php if($this->config->item('use_auth') && $this->session->userdata('user_type') >= 99) { ?>
			<li><a href="<?php echo site_url('user');?>" title="Users">Users</a></li>
			<?php } ?>
		</ul>

		<?php if($this->config->item('use_auth')) { ?>
		<ul id="user">
			<?php if($this->session->userdata('user_id')) { ?>
			<li><a href="<?php echo site_url('user/account');?>"><?php echo $this->session->userdata('user_name'); ?></a></li>
			<li><a href="<?php echo site_url('user/logout');?>">Logout</a></li>
			<?php } else { ?>
			<li><a href="<?php echo site_url('user/login');?>">Log in</a></li>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>
<div class="clear"></div>
