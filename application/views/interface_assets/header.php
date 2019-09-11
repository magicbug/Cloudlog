<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/general.css">

    <!-- Maps -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/leaflet/leaflet.css" />

    <link rel="icon" href="<?php echo base_url(); ?>/favicon.ico">

    <title><?php if(isset($page_title)) { echo $page_title; } ?> - Cloudlog</title>
  </head>
  <body>

<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light main-nav">
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
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">QSO</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="<?php echo site_url('qso?manual=0');?>" title="Log Live QSOs">Live QSO</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('qso?manual=1');?>" title="Log QSO made in the past">Post QSO</a>
						</div>
        	</li>

        	<!-- Notes -->
        	<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Notes</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="<?php echo site_url('notes');?>" title="Notes">Notes</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo site_url('notes/add');?>" title="Create Note">Create Note</a>
						</div>
        	</li>

        	<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Analytics</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="<?php echo site_url('statistics');?>" title="Statistics">Statistics</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php echo site_url('gridsquares');?>" title="Gridsquares">Gridsquares</a>
				</div>
        	</li>

        	<a class="nav-link" href="<?php echo site_url('awards/dxcc');?>">Awards</a>

        	
        	<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
				
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="<?php echo site_url('user');?>" title="Accounts"><i class="fas fa-user"></i> Accounts</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('api/help');?>" title="API Interface"><i class="fas fa-key"></i> API</a>
					
					<div class="dropdown-divider"></div>					
					
					<a class="dropdown-item" href="<?php echo site_url('station');?>" title="Station Profiles"><i class="fas fa-home"></i> Station Profiles</a>
					
					<div class="dropdown-divider"></div>		
					
					<a class="dropdown-item" href="<?php echo site_url('radio');?>" title="External Radios"><i class="fas fa-broadcast-tower"></i> Radio Interface</a>
					
					<div class="dropdown-divider"></div>	
					
					<a class="dropdown-item" href="<?php echo site_url('adif');?>" title="ADIF Import/Export"><i class="fas fa-sync"></i> ADIF Import/Export</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('lotw/import');?>" title="LoTW Import/Export"><i class="fas fa-sync"></i> LoTW Import/Export</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('eqsl/import');?>" title="eQSL Import/Export"><i class="fas fa-sync"></i> eQSL Import/Export</a>
					
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('qslprint');?>" title="Print Requested QSLs"><i class="fas fa-print"></i> Print Requested QSLs</a>

					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('backup');?>" title="Backup Cloudlog"><i class="fas fa-save"></i> Backup</a>

					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="<?php echo site_url('update');?>" title="Update Country Files"><i class="fas fa-sync"></i> Update Country Files</a>
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
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Logged in as <?php echo $this->session->userdata('user_callsign'); ?></a>
			
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="<?php echo site_url('user/profile');?>" title="Profile"><i class="far fa-user"></i> Profile</a>
				
				<div class="dropdown-divider"></div>
				
				<a class="dropdown-item" target="_blank" href="https://github.com/magicbug/Cloudlog/wiki" title="Help"><i class="fas fa-question"></i> Help</a>
				
				<div class="dropdown-divider"></div>
				
				<a class="dropdown-item" href="<?php echo site_url('user/logout');?>" title="Logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
			</div>
        </li>
    	</ul>

        <?php } ?>

  </div>
</div>
</nav>
