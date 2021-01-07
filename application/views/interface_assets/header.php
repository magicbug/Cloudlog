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

    <!-- Maps -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />

    <?php if ($this->uri->segment(1) == "search" && $this->uri->segment(2) == "filter") { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/query-builder.default.min.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "notes" && ($this->uri->segment(2) == "add" || $this->uri->segment(2) == "edit") ) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/quill/quill.snow.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "qrz" || $this->uri->segment(1) == "accumulated" || $this->uri->segment(1) == "timeplotter") { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loading.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ldbtn.min.css" />
	<?php } ?>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/bootstrapdialog/css/bootstrap-dialog.min.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/buttons.dataTables.min.css"/>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datatables.min.css"/>

 	<?php if ($this->uri->segment(1) == "adif") { ?>
  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.css" />
  	<?php } ?>
    <link rel="icon" href="<?php echo base_url(); ?>/favicon.ico">

    <title><?php if(isset($page_title)) { echo $page_title; } ?> - Cloudlog</title>
  </head>
  <body>

<nav class="navbar navbar-expand-lg navbar-light bg-light main-nav">
<div class="container">
		<a class="navbar-brand" href="<?php echo site_url(); ?>">Cloudlog</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

		<div class="collapse navbar-collapse" id="navbarNav">
    
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo site_url('logbook');?>">Logbook</a>

        <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
        	<!-- QSO Menu Dropdown -->
        	<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <?php echo lang('qso'); ?>
                                                </a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="<?php echo site_url('qso?manual=0');?>" title="Log Live QSOs">
                                                            <?php echo lang('qso_live'); ?>
                                                        </a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('qso?manual=1');?>" title="Log QSO made in the past">
                                                            <?php echo lang('qso_post'); ?>
                                                        </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo site_url('contesting');?>" title="Log contest QSOs">Contest Logging</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo site_url('qsl');?>" title="QSL"> View QSL</a>
						</div>
        	</li>

        	<!-- Notes -->
		<a class="nav-link" href="<?php echo site_url('notes');?>">
                    <?php echo lang('notes'); ?>
                </a>
 
        	<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Analytics</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="<?php echo site_url('statistics');?>" title="Statistics">Statistics</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php echo site_url('gridsquares');?>" title="Gridsquares">Gridsquares</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('distances');?>" title="Distances">Distances Worked</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('dayswithqso');?>" title="Days with QSOs">Days with QSOs</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('timeline');?>" title="Timeline">Timeline</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('accumulated');?>" title="Accumulated Statistics">Accumulated Statistics</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('timeplotter');?>" title="View time when worked">Timeplotter</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php echo site_url('map');?>" title="Maps of QSOs">Maps</a>
				</div>
        	</li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Awards</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo site_url('awards/cq');?>">CQ</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/dok');?>">DOK</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/dxcc');?>">DXCC</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/iota');?>">IOTA</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/sota');?>">SOTA</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/vucc');?>">VUCC</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/wab');?>">WAB</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/was');?>">WAS</a>
                </div>
            </li>
        	
        	<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
				
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="<?php echo site_url('user');?>" title="Manage user accounts"><i class="fas fa-user"></i> User Accounts</a>
					
					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('options');?>" title="Manage global options"><i class="fas fa-cog"></i> Global Options</a>

					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('api/help');?>" title="Manage API keys"><i class="fas fa-key"></i> API</a>
					
					<div class="dropdown-divider"></div>					
					
					<a class="dropdown-item" href="<?php echo site_url('station');?>" title="Manage station profiles"><i class="fas fa-home"></i> Station Profiles</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('mode');?>" title="Manage QSO modes"><i class="fas fa-broadcast-tower"></i> Modes</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('radio');?>" title="Interface with one or more radios"><i class="fas fa-broadcast-tower"></i> Radio Interface</a>
					
					<div class="dropdown-divider"></div>	
					
					<a class="dropdown-item" href="<?php echo site_url('adif');?>" title="Amateur Data Interchange Format (ADIF) import / export"><i class="fas fa-sync"></i> ADIF Import / Export</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('lotw');?>" title="Synchronise with Logbook of the World (LotW)"><i class="fas fa-sync"></i> Logbook of the World</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('eqsl/import');?>" title="eQSL import / export"><i class="fas fa-sync"></i> eQSL Import / Export</a>
					
					<div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="<?php echo site_url('qrz/export');?>" title="Upload to QRZ.com logbook"><i class="fas fa-sync"></i> QRZ Logbook</a>

                    <div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('qslprint');?>" title="Print Requested QSLs"><i class="fas fa-print"></i> Print Requested QSLs</a>

					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('backup');?>" title="Backup Cloudlog content"><i class="fas fa-save"></i> Backup</a>

					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('update');?>" title="Update Country Files"><i class="fas fa-sync"></i> Update Country Files</a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="<?php echo site_url('kml');?>" title="KML Export for Google Earth"><i class="fas fa-sync"></i> KML Export</a>
				</div>
        	</li>
        <?php } ?>
    </ul>

     <?php if($this->config->item('public_search') == TRUE || $this->session->userdata('user_type') >= 2) { ?>
		<form method="post" action="<?php echo site_url('search'); ?>" class="form-inline">
		<input class="form-control mr-sm-2" id="nav-bar-search-input" type="search" name="callsign" placeholder="Search Callsign" aria-label="Search">
		
		<button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i> Search</button>
		</form>
	<?php } ?>

	<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
    	<!-- Logged in Content-->
    <?php } else { ?>
    <!-- Not Logged In-->
	<form method="post" action="<?php echo site_url('user/login'); ?>" style="padding-left: 5px;" class="form-inline">
			<input class="form-control mr-sm-2" type="text" name="user_name" placeholder="Username" aria-label="Username">
			<input class="form-control mr-sm-2" type="password" name="user_password" placeholder="Password" aria-label="Password">
			<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
      <button class="btn btn-outline-success mr-sm-2" type="submit">Login</button>
	</form>
	<?php } ?>

		<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
        <ul class="navbar-nav">
        <!-- Logged in As -->
        <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> <?php echo $this->session->userdata('user_callsign'); ?></a>
			
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="<?php echo site_url('user/edit')."/".$this->session->userdata('user_id'); ?>" title="Account"><i class="fas fa-user"></i> Account</a>
				
				<div class="dropdown-divider"></div>
				
				<a class="dropdown-item" target="_blank" href="https://github.com/magicbug/Cloudlog/wiki" title="Help"><i class="fas fa-question"></i> Help</a>
				
				<div class="dropdown-divider"></div>

				<a class="dropdown-item" target="_blank" href="https://forum.cloudlog.co.uk" title="Forum"><i class="fas fa-question"></i> Forum</a>
				
				<div class="dropdown-divider"></div>
				
				<a class="dropdown-item" href="<?php echo site_url('user/logout');?>" title="Logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
			</div>
        </li>
    	</ul>

        <?php } ?>

  </div>
</div>
</nav>
