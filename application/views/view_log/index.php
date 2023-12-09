<div class="alert alert-secondary" role="alert" style="margin-bottom: 0px !important;">
<div class="container">
	<?php if ($results) { ?>
		<p style="margin-bottom: 0px !important;"><?php echo lang('gen_hamradio_logbook'); ?>: <span class="badge text-bg-info"><?php echo $this->logbooks_model->find_name($this->session->userdata('active_station_logbook')); ?></span> <?php echo lang('general_word_location'); ?>: <span class="badge text-bg-info"><?php echo $this->stations->find_name(); ?></span></p>
	<?php } ?>
</div>
</div>

<div class="container logbook">

	<h2><?php echo lang('gen_hamradio_logbook'); ?></h2>
	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>
</div>
	
<?php if($this->optionslib->get_option('logbook_map') != "false") { ?>
	<!-- Map -->
	<div id="map" class="map-leaflet" style="width: 100%; height: 350px"></div>
<?php } ?>

<div style="padding-top: 10px; margin-top: 0px;" class="container logbook">
	<?php $this->load->view('view_log/partial/log_ajax') ?>
