<div class="container">

	<br>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>

	<h2><?php echo $page_title; ?></h2>

	<div class="card">
	  <div class="card-header">
	    <?php echo lang('qslcard_qslprint_header'); ?>
	  </div>
		<div class="card-body">
			<form class="form" action="<?php echo site_url('adif/import'); ?>" method="post" enctype="multipart/form-data">
				<?php echo lang('cloudlog_station_profile'); ?>:
				<select name="station_profile" class="station_id form-select mb-3 me-sm-3" style="width: 20%;">
					<option value="All"><?php echo lang('general_word_all'); ?></option>
					<?php foreach ($station_profile->result() as $station) { ?>
						<option <?php if ($station->station_id == $station_id) { echo "selected "; } ?>value="<?php echo $station->station_id; ?>"><?php echo lang('gen_hamradio_callsign'); ?>: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
					<?php } ?>
				</select>
			</form>

	    <p class="card-text"><?php echo lang('qslcard_qslprint_text_line1'); ?></p>
	    <p class="card-text"><?php echo lang('qslcard_qslprint_text_line2'); ?></p>

		<div class="resulttable">
		<?php 
			$data2['qsos'] = $qsos;
			$this->load->view('qslprint/qslprint', $data2); 
		?>
			</div>
		</div>
	</div>
</div>
