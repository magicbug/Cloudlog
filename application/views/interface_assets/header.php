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

    <?php if ($this->uri->segment(1) == "search" && $this->uri->segment(2) == "filter") { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/query-builder.default.min.css" />
	<?php } ?>

	<?php if ($this->uri->segment(1) == "notes" && ($this->uri->segment(2) == "add" || $this->uri->segment(2) == "edit") ) { ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/quill/quill.snow.css" />
	<?php } ?>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/loading.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ldbtn.min.css" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/buttons.dataTables.min.css"/>

  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datatables.min.css"/>

 	<?php if ($this->uri->segment(1) == "adif") { ?>
  	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.css" />
  	<?php } ?>
	 
	<?php if (file_exists(APPPATH.'../assets/css/custom.css')) { echo '<link rel="stylesheet" href="'.base_url().'assets/css/custom.css">'; } ?>

    <link rel="icon" href="<?php echo base_url(); ?>favicon.ico">

    <title><?php if(isset($page_title)) { echo $page_title; } ?> - Cloudlog</title>
  </head>
  <body>

<nav class="navbar navbar-expand-lg navbar-light bg-light main-nav">
<div class="container">
		<a class="navbar-brand" href="<?php echo site_url(); ?>">Cloudlog</a> <?php if(ENVIRONMENT == "development") { ?><span class="badge badge-danger">Developer Mode</span><?php } ?>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

		<div class="collapse navbar-collapse" id="navbarNav">

    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo site_url('logbook');?>">Logbook</a>

        <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
        	<!-- QSO Menu Dropdown -->
        	<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">QSO</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="<?php echo site_url('qso?manual=0');?>" title="Log Live QSOs"><i class="fas fa-list"></i> Live QSO</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('qso?manual=1');?>" title="Log QSO made in the past"><i class="fas fa-list"></i> Post QSO</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('contesting?manual=0');?>" title="Live contest QSOs"><i class="fas fa-list"></i> Live Contest Logging</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('contesting?manual=1');?>" title="Post contest QSOs"><i class="fas fa-list"></i> Post Contest Logging</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('qsl');?>" title="QSL"><i class="fa fa-id-card"></i> View QSL</a>
						</div>
        	</li>

        	<!-- Notes -->
		<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
        	<a class="nav-link" href="<?php echo site_url('notes');?>">Notes</a>
		<?php } ?>
        	<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Analytics</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="<?php echo site_url('statistics');?>" title="Statistics"><i class="fas fa-chart-area"></i> Statistics</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php echo site_url('gridsquares');?>" title="Gridsquares"><i class="fas fa-globe-europe"></i> Gridsquares</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('activated_grids');?>" title="Activated Gridsquares"><i class="fas fa-globe-europe"></i> Activated Gridsquares</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('activators');?>" title="Gridsquare Activators"><i class="fas fa-globe-europe"></i> Gridsquare Activators</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('distances');?>" title="Distances"><i class="fas fa-chart-area"></i> Distances Worked</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('dayswithqso');?>" title="Days with QSOs"><i class="fas fa-chart-area"></i> Days with QSOs</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('timeline');?>" title="Timeline"><i class="fas fa-chart-area"></i> Timeline</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('accumulated');?>" title="Accumulated Statistics"><i class="fas fa-chart-area"></i> Accumulated Statistics</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('timeplotter');?>" title="View time when worked"><i class="fas fa-chart-area"></i> Timeplotter</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php echo site_url('map/custom');?>" title="Custom Maps of QSOs"><i class="fas fa-globe-europe"></i> Custom Maps</a>
				</div>
        	</li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Awards</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo site_url('awards/cq');?>"><i class="fas fa-trophy"></i> CQ</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/dok');?>"><i class="fas fa-trophy"></i> DOK</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/dxcc');?>"><i class="fas fa-trophy"></i> DXCC</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/iota');?>"><i class="fas fa-trophy"></i> IOTA</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php echo site_url('awards/sig');?>"><i class="fas fa-trophy"></i> SIG</a>
					<div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/sota');?>"><i class="fas fa-trophy"></i> SOTA</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/counties');?>"><i class="fas fa-trophy"></i> US Counties</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/vucc');?>"><i class="fas fa-trophy"></i> VUCC</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/was');?>"><i class="fas fa-trophy"></i> WAS</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('awards/wwff');?>"><i class="fas fa-trophy"></i> WWFF</a>
                </div>
            </li>

			<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') == 99)) { ?>
        	<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>

				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="<?php echo site_url('user');?>" title="Manage user accounts"><i class="fas fa-user"></i> User Accounts</a>

					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('options');?>" title="Manage global options"><i class="fas fa-cog"></i> Global Options</a>

					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('mode');?>" title="Manage QSO modes"><i class="fas fa-broadcast-tower"></i> Modes</a>

					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('contesting/add');?>" title="Manage Contest names"><i class="fas fa-broadcast-tower"></i> Contests</a>

					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('themes');?>" title="Manage Themes"><i class="fas fa-cog"></i> Themes</a>

					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('backup');?>" title="Backup Cloudlog content"><i class="fas fa-save"></i> Backup</a>

					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('update');?>" title="Update Country Files"><i class="fas fa-sync"></i> Update Country Files</a>

					<?php if(ENVIRONMENT == "development") { ?>
					<div class="dropdown-divider"></div>

					<a class="dropdown-item" href="<?php echo site_url('debug');?>" title="Debug Information"><i class="fas fa-tools"></i> Debug Information</a>
					<?php } ?>
				</div>
        	</li>
			<?php } ?>
        <?php } ?>
    </ul>

     <?php if($this->optionslib->get_option('global_search') != "false" || $this->session->userdata('user_type') >= 2) { ?>
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

				<a class="dropdown-item" href="<?php echo site_url('logbooks');?>" title="Manage station logbooks"><i class="fas fa-home"></i> Station Logbooks</a>
				
				<a class="dropdown-item" href="<?php echo site_url('station');?>" title="Manage station locations"><i class="fas fa-home"></i> Station Locations</a>

				<a class="dropdown-item" href="<?php echo site_url('band');?>" title="Manage Bands"><i class="fas fa-cog"></i> Bands</a>

				<div class="dropdown-divider"></div>

				<a class="dropdown-item" href="<?php echo site_url('adif');?>" title="Amateur Data Interchange Format (ADIF) import / export"><i class="fas fa-sync"></i> ADIF Import / Export</a>

				<a class="dropdown-item" href="<?php echo site_url('qslprint');?>" title="Print Requested QSLs"><i class="fas fa-print"></i> Print Requested QSLs</a>

				<a class="dropdown-item" href="<?php echo site_url('kml');?>" title="KML Export for Google Earth"><i class="fas fa-sync"></i> KML Export</a>

				<a class="dropdown-item" href="<?php echo site_url('dxatlas');?>" title="DX Atlas Gridsquare Export"><i class="fas fa-sync"></i> DX Atlas Gridsquare Export</a>

				<a class="dropdown-item" href="<?php echo site_url('csv');?>" title="SOTA CSV Export"><i class="fas fa-sync"></i> SOTA CSV Export</a>

				<div class="dropdown-divider"></div>

				<a class="dropdown-item" href="<?php echo site_url('lotw');?>" title="Synchronise with Logbook of the World (LotW)"><i class="fas fa-sync"></i> Logbook of the World</a>

				<a class="dropdown-item" href="<?php echo site_url('eqsl/import');?>" title="eQSL import / export"><i class="fas fa-sync"></i> eQSL Import / Export</a>

                <a class="dropdown-item" href="<?php echo site_url('qrz/export');?>" title="Upload to QRZ.com logbook"><i class="fas fa-sync"></i> QRZ Logbook</a>

				<div class="dropdown-divider"></div>

				<a class="dropdown-item" href="<?php echo site_url('api/help');?>" title="Manage API keys"><i class="fas fa-key"></i> API Keys</a>

				<a class="dropdown-item" href="<?php echo site_url('radio');?>" title="Interface with one or more radios"><i class="fas fa-broadcast-tower"></i> Hardware Interfaces</a>

				<div class="dropdown-divider"></div>

				<a class="dropdown-item" target="_blank" href="https://github.com/magicbug/Cloudlog/wiki" title="Help"><i class="fas fa-question"></i> Help</a>

				<a class="dropdown-item" target="_blank" href="https://github.com/magicbug/Cloudlog/discussions" title="Forum"><i class="far fa-comment-dots"></i> Forum</a>

				<div class="dropdown-divider"></div>

				<a class="dropdown-item" href="<?php echo site_url('user/logout');?>" title="Logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
			</div>
        </li>
    	</ul>

        <?php } ?>

  </div>
</div>
</nav>
