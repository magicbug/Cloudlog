<div class="alert alert-secondary" role="alert" style="margin-bottom: 0px !important;">
<div class="container">
	<?php if ($results) { ?>
		<div class="d-flex justify-content-between align-items-center">
			<p style="margin-bottom: 0px !important;"><?php echo lang('gen_hamradio_logbook'); ?>: <span class="badge text-bg-info"><?php echo $this->logbooks_model->find_name($this->session->userdata('active_station_logbook')); ?></span> <?php echo lang('general_word_location'); ?>: <span class="badge text-bg-info"><?php echo $this->stations->find_name(); ?></span></p>
			<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
				<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#stationDiaryModal">
					<i class="fas fa-book"></i> Station Diary
				</button>
			<?php } ?>
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
</div>

<!-- Station Diary Modal -->
<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
<div class="modal fade" id="stationDiaryModal" tabindex="-1" aria-labelledby="stationDiaryModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="stationDiaryModalLabel"><i class="fas fa-book"></i> Station Diary</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="stationDiaryForm" hx-post="<?php echo site_url('notes/quick_add'); ?>" hx-target="#diaryFormMessages">
				<div class="modal-body">
					<div id="diaryFormMessages"></div>
					
					<div class="mb-3">
						<label for="diaryTitle" class="form-label">Title</label>
						<input type="text" class="form-control" id="diaryTitle" name="title" required placeholder="e.g. Good conditions on 20m">
					</div>

					<div class="mb-3">
						<label for="diaryContent" class="form-label">Note</label>
						<textarea class="form-control" id="diaryContent" name="content" rows="5" required placeholder="e.g. Worked several DX stations on 20m today. Band was in excellent condition..."></textarea>
					</div>

					<input type="hidden" name="category" value="Station Diary">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save Note</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
