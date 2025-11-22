<div class="container">

	<!-- Page Header -->
	<div class="row mt-4 mb-4">
		<div class="col-md-12">
			<div class="d-flex justify-content-between align-items-center">
				<h2><i class="fas fa-address-card me-2"></i><?php echo $page_title; ?></h2>
			</div>
		</div>
	</div>

	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert alert-info alert-dismissible fade show" role="alert">
			<i class="fas fa-info-circle me-2"></i><?php echo $this->session->flashdata('message'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- Main Card -->
	<div class="card">
		<div class="card-header bg-light">
			<div class="d-flex justify-content-between align-items-center">
				<h5 class="mb-0"><i class="fas fa-print me-2"></i><?php echo lang('qslcard_qslprint_header'); ?></h5>
				<div>
					<label class="me-2 mb-0"><?php echo lang('cloudlog_station_profile'); ?>:</label>
					<select name="station_profile" class="station_id form-select form-select-sm d-inline-block" style="width: auto;">
						<option value="All"><?php echo lang('general_word_all'); ?></option>
						<?php foreach ($station_profile->result() as $station) { ?>
							<option <?php if ($station->station_id == $station_id) { echo "selected "; } ?>value="<?php echo $station->station_id; ?>"><?php echo lang('gen_hamradio_callsign'); ?>: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="card-body">
			<p class="card-text"><i class="fas fa-info-circle me-2"></i><?php echo lang('qslcard_qslprint_text_line1'); ?></p>
			<p class="card-text"><i class="fas fa-info-circle me-2"></i><?php echo lang('qslcard_qslprint_text_line2'); ?></p>

			<div class="resulttable">
			<?php 
				$data2['qsos'] = $qsos;
				$this->load->view('qslprint/qslprint', $data2); 
			?>
			</div>
		</div>
	</div>
</div>
