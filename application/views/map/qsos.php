<div class="container map-QSOs">

	<h2><?php echo $station_profile->station_profile_name; ?> Station Profile QSOs (All)</h2>

	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>
</div>
	
	<!-- Map -->
	<div id="map" style="width: 100%; height: 700px;"></div> 

    <div class="alert alert-success" role="alert">Showing All QSOs for Active Station Profile - <?php echo $station_profile->station_profile_name; ?> </div>