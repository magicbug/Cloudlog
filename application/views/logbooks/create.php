<div class="container" id="create_station_profile">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?>
  </div>
  <div class="card-body">
		<?php if($this->session->flashdata('notice')) { ?>
			<div id="message" >
			<?php echo $this->session->flashdata('notice'); ?>
			</div>
		<?php } ?>

		<?php $this->load->helper('form'); ?>

		<?php echo validation_errors(); ?>

		<form method="post" action="<?php echo site_url('logbooks/create'); ?>" name="create_profile">
		  <div class="form-group">
		    <label for="stationLogbookNameInput">Station Logbook Name</label>
		    <input type="text" class="form-control" name="stationLogbook_Name" id="stationLogbookNameInput" aria-describedby="stationLogbookNameHelp" placeholder="Home QTH" required>
		    <small id="stationLogbookNameHelp" class="form-text text-muted">You can call a station logbook anything.</small>
		  </div>

			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Create Station Logbook</button>

		</form>
  </div>
</div>

<br>

</div>
