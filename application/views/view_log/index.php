

<div class="container logbook">

	<h2>Logbook</h2>

	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>

	
	<!-- Map -->
	<div id="map" style="width: 100%; height: 300px"></div> 

	<?php $this->load->view('view_log/partial/log') ?>
