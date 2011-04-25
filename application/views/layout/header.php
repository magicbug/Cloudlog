<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Web Logbook</title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/reset.css" type="text/css" />
	
	<style type="text/css" media="screen" >
body {
background-color: #e6e6e6;
font-family: Arial, "Trebuchet MS", sans-serif;
}

td {
	padding: 5px;
}
#nav { 
	background-image: url('/logger/images/nav_bg.gif');
	height: 39px;
	color: #ffffff;
	border-bottom: 1px solid #9bc9ed;
}
/* Nav List CSS */
ul#navlist { font: bold 15px "Trebuchet MS", sans-serif; list-style-type: none; margin: 0; margin-left: 10px; }
ul#navlist li.active { float: left; background-image: none; background-color: #fff; margin: 2px 2px 0 3px; height:34px; text-align:center; }
ul#navlist li { float: left; margin: 2px 2px 0 3px; height:43px; border-bottom: none; text-align:center; }
#navlist .active a{ color: #ebebeb ; }
#navlist a { float: left; display: block; color: #ebebeb; text-decoration: none; padding-top: 7px; padding-left: 6px; padding-right: 5px; text-align:center; }
#navlist a:hover {  }

.wrap_content {
	margin: 0 auto;
	width: 80%;
	border: 1px solid #d7d7d7;
	background-color: #ffffff;
}

.clear { clear: both; }

h2 {
	margin: 0 auto;
	width: 80%;
	font-weight: bold;
	font-size: 23px;
	margin-top: 5px;
	margin-bottom: 10px;
}

table .titles {
	font-weight: bold;
}
	</style>
</head>

<body>
	
	<div id="nav">
		
		<ul id="navlist">
			<li><a href="<?php echo site_url();?> " title="Dashboard">Dashboard</a></li>
			<li><a href="<?php echo site_url('logbook');?>" title="View Log">View Log</a></li>
			<li><a href="<?php echo site_url('qso');?>" title="Add QSO">Add QSO</a></li>
		</ul>
	</div>
<div class="clear"></div>