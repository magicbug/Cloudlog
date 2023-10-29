<script type="text/javascript">
	var Bands = <?php echo json_encode($bands); ?>;
	var user_id = <?php echo $this->session->userdata('user_id'); ?>;
</script>

<div class="container">

	<br>
	<div id="simpleFleInfo">
		<script>
			var lang_simplefle_info_button = "<?php echo lang('simplefle_info_button'); ?>";
			var lang_simplefle_info_ln1 = "<?php echo lang('simplefle_info_ln1'); ?>";
			var lang_simplefle_info_ln2 = "<?php echo lang('simplefle_info_ln2'); ?>";
			var lang_simplefle_info_ln3 = "<?php echo lang('simplefle_info_ln3'); ?>";
			var lang_simplefle_info_ln4 = "<?php echo lang('simplefle_info_ln4'); ?>";
			var lang_simplefle_syntax_help = "<?php echo lang('simplefle_syntax_help_button'); ?>";
			var lang_simplefle_syntax_help_title = "<?php echo lang('simplefle_syntax_help_title'); ?>";
		</script>
		<h2><?php echo $page_title; ?></h2>
		<button type="button" class="btn btn-sm btn-primary mr-1" id="simpleFleInfoButton"><?php echo lang('simplefle_info'); ?></button>
	</div>

	<?php if ($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>
</div>
<div class="container-fluid">
	<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
		<div class="col-md-3 mb-2 mb-md-0">

		</div>

		<div class="col-md-3 justify-content-end d-flex">



		</div>
	</header>
	<div class="row mt-4">
		<!-- START BASIC QSO DATA -->
		<div class="col-xs-12 col-md-4">

			<div class="card-header">
				QSO Data
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xs-12 col-lg-12 col-xl-6">
						<div class="form-group">
							<label for="qsodate">QSO date</label>
							<input type="date" class="form-control" id="qsodate">
						</div>
					</div>
					<div class="col-xs-12 col-lg-6">
						<div class="form-group">
							<label for="operator">Operator <span class="text-muted input-example">e.g. OK2CQR</span></label>
							<input type="text" class="form-control text-uppercase" id="operator" value="<?php echo $this->session->userdata('operator_callsign'); ?>">
							<div class="alert alert-danger" role="alert" id="warningOperatorField" style="display: none"> </div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-lg-6">
						<div class="form-group">
							<label for="station-call">
								Station Call
							</label>
							<select name="station_profile" class="station_id custom-select" id="station-call">
								<option value="-">-</option>
								<?php foreach ($station_profile->result() as $station) { ?>
									<option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) {
																							echo 'selected';
																						} ?>>
										<?php echo lang('gen_hamradio_callsign') . ": " . $station->station_callsign . " (" . $station->station_profile_name . ")"; ?>
									</option>
								<?php } ?>
							</select>
							<div class="alert alert-danger" role="alert" id="warningStationCall" style="display: none"> </div>
							<small class="form-text text-muted">If you did operate from a new location, first create a new <a href='<?php echo site_url('station'); ?>'>Station Location</a></small>
						</div>
					</div>
				</div>
			</div>

			<!-- END BASIC QSO DATA -->
			<div class="card-body">
				<div class="row">
					<div class="col">
						<p>Enter the data</p>
						<textarea name="qso" class="form-control qso-area" cols="auto" rows="11" id="textarea"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-md-8">
			<div class="card-header">
				QSO list
			</div>
			<div class="card-body">
				<div class="qsoList">
					<table class="table table-condensed table-striped table-sm" id="qsoTable">
						<thead>
							<tr>
								<th>Date</th>
								<th>Time</th>
								<th>Callsign</th>
								<th>Band</th>
								<th>Mode</th>
								<th>RS</th>
								<th>RR</th>
								<th>Op.</th>
								<th>SOTA/WFF</th>
							</tr>
						</thead>
						<tbody id="qsoTableBody">

						</tbody>
					</table>
				</div>
				<span class="js-qso-count"></span>
			</div>
			<div class="row mt-2">
				<div class="col-3 col-sm-3">
					<button class="btn btn-primary js-reload-qso">Reload QSO list</button>
				</div>
				<div class="col-3 col-sm-3">
					<button class="btn btn-warning js-save-to-log">Save in Cloudlog</button>
				</div>
				<div class="col-3 col-sm-3">
					<button class="btn btn-danger js-empty-qso">Clear logging session</button>
				</div>
				<div class="col-3 col-sm-3">
					<button class="btn btn-success" id="js-syntax"><?php echo lang('simplefle_syntax_help_button'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>