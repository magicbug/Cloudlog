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
	    Export Requested QSLs for Printing
	  </div>
		<div class="card-body">
			<form class="form" action="<?php echo site_url('adif/import'); ?>" method="post" enctype="multipart/form-data">
				<?php echo lang('cloudlog_station_profile'); ?>:
				<select name="station_profile" class="station_id custom-select mb-3 mr-sm-3" style="width: 20%;">
					<option value="All">All</option>
					<?php foreach ($station_profile->result() as $station) { ?>
						<option <?php if ($station->station_id == $station_id) { echo "selected "; } ?>value="<?php echo $station->station_id; ?>">Callsign: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
					<?php } ?>
				</select>
			</form>

	    <p class="card-text">Here you can export requested QSLs as CSV or ADIF files for printing and, optionally, mark them as sent via bureau.</p>
	    <p class="card-text">Requested QSLs are any QSOs with a value of "Requested" or "Queued" in their "QSL Sent" field.</p>

		<div class="resulttable">
		<?php 
			$data2['qsos'] = $qsos;
			$this->load->view('qslprint/qslprint', $data2); 
		?>
			</div>
		</div>
	</div>
</div>
