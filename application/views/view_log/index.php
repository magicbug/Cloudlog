<div class="alert alert-secondary" role="alert" style="margin-bottom: 0px !important;">
<div class="container">
	<?php if ($results) { ?>
		<div class="row align-items-center">
			<div class="col-md-8">
				<p style="margin-bottom: 0px !important;">
					<?php echo lang('gen_hamradio_logbook'); ?>: <span class="badge text-bg-info"><?php echo $this->logbooks_model->find_name($this->session->userdata('active_station_logbook')); ?></span> 
					<?php echo lang('general_word_location'); ?>: <span class="badge text-bg-info"><?php echo $this->stations->find_name(); ?></span>
				</p>
			</div>
			<div class="col-md-4">
				<form method="post" action="<?php echo site_url('logbooks/switch_logbook_location'); ?>" class="d-flex align-items-center gap-2" id="switcher_form">
					<select name="logbook_id" class="form-select form-select-sm" style="min-width: 120px;" id="logbook_switcher">
						<option value="">Select Logbook</option>
						<?php if (isset($available_logbooks)) {
							foreach ($available_logbooks->result() as $logbook) { ?>
								<option value="<?php echo $logbook->logbook_id; ?>" <?php echo ($logbook->logbook_id == $this->session->userdata('active_station_logbook')) ? 'selected' : ''; ?>>
									<?php echo $logbook->logbook_name; ?>
								</option>
							<?php }
						} ?>
					</select>
					<select name="station_id" class="form-select form-select-sm" style="min-width: 120px;" id="location_switcher">
						<option value="">Select Location</option>
						<?php if (isset($available_stations)) {
							$active_station_id = $this->stations->find_active();
							foreach ($available_stations->result() as $station) { ?>
								<option value="<?php echo $station->station_id; ?>" <?php echo ($station->station_id == $active_station_id) ? 'selected' : ''; ?>>
									<?php echo $station->station_profile_name; ?>
								</option>
							<?php }
						} ?>
					</select>
					<button type="submit" class="btn btn-primary btn-sm" id="change_button">
						<i class="fas fa-sync-alt me-1"></i>Change
					</button>
				</form>
			</div>
		</div>
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

<script>
// Enable/disable change button based on selections
document.addEventListener('DOMContentLoaded', function() {
	const logbookSelect = document.getElementById('logbook_switcher');
	const locationSelect = document.getElementById('location_switcher');
	const changeButton = document.getElementById('change_button');
	
	function updateButtonState() {
		const logbookChanged = logbookSelect.value && logbookSelect.value !== logbookSelect.dataset.original;
		const locationChanged = locationSelect.value && locationSelect.value !== locationSelect.dataset.original;
		
		changeButton.disabled = !(logbookChanged || locationChanged);
	}
	
	// Store original values
	logbookSelect.dataset.original = logbookSelect.value;
	locationSelect.dataset.original = locationSelect.value;
	
	// Initial button state
	updateButtonState();
	
	// Add event listeners
	logbookSelect.addEventListener('change', updateButtonState);
	locationSelect.addEventListener('change', updateButtonState);
});
</script>
