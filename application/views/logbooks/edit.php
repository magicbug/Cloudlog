<div class="container" id="create_station_profile">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('notice')) { ?>
		<div id="message" >
			<?php echo $this->session->flashdata('notice'); ?>
		</div>
	<?php } ?>

	<?php $this->load->helper('form'); ?>

	<?php echo validation_errors(); ?>

	<form method="post" action="<?php echo site_url('logbooks/edit/'); ?><?php echo $station_logbook_details->logbook_id; ?>" name="create_profile">

	<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">

	<div class="row">
		<div class="col-md">
			<div class="card">
				<div class="card-header"><?php echo $page_title; ?>: <?php echo $station_logbook_details->logbook_name; ?></div>
				<div class="card-body">
				
					<div class="form-group">
						<label for="stationLogbookNameInput">Station Logbook Name</label>
						<input type="text" class="form-control" name="station_logbook_name" id="stationLogbookNameInput" aria-describedby="stationLogbookNameInputHelp" value="<?php if(set_value('station_logbook_name') != "") { echo set_value('station_logbook_name'); } else { echo $station_logbook_details->logbook_name; } ?>" required>
						<small id="stationLogbookNameInputHelp" class="form-text text-muted">Shortname for the station location. For example: Home (IO87IP)</small>
					</div>
				</div>
			</div>
		</div>
	</div>


	<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Update Station Logbook</button>

	</form>

</div>
