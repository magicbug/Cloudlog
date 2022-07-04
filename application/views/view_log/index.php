<div class="container logbook">

	<h2><?php echo $this->lang->line('gen_hamradio_logbook'); ?></h2>
	<?php if ($results) { ?>
		<h6><?php echo $this->lang->line('gen_hamradio_logbook').": ".$this->logbooks_model->find_name($this->session->userdata('active_station_logbook')); ?> <?php echo $this->lang->line('general_word_location').": ".$this->stations->find_name(); ?></h6>
	<?php } ?>


	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>
</div>
	
	<!-- Map -->
	<div id="map" style="width: 100%; height: 350px"></div> 

<div style="padding-top: 10px; margin-top: 0px;" class="container logbook">
	<?php $this->load->view('view_log/partial/log_ajax') ?>
