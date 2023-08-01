<div class="container">
	<h2><?php echo $page_title; ?></h2>

	<div class="card">
		<div class="card-header">
			<ul style="font-size: 15px;" class="nav nav-tabs card-header-tabs pull-right"  id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="dxcc-tab" data-toggle="tab" href="#dxcc" role="tab" aria-controls="update" aria-selected="true">DXCC Lookup Data</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" id="distance-tab" data-toggle="tab" href="#distance" role="tab" aria-controls="update" aria-selected="false">Distance Data</a>
				</li>
			</ul>
		</div>
		<div class="card-body">
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="dxcc" role="tabpanel" aria-labelledby="dxcc-tab">
					<p class="card-text">Here you can update the DXCC lookup data that is used for displaying callsign information.</p>
					<p class="card-text">This data is provided by <a href="https://clublog.org/">Clublog</a>.</p>

					<?php if(!extension_loaded('xml')) { ?>
						<div class="alert alert-danger" role="alert">
						You must install php-xml for this to work.
						</div>
					<?php } else { ?>
						<h5>Check for DXCC Data Updates</h5>
						<input type="submit" class="btn btn-primary" id="btn_update_dxcc" value="Update DXCC Data" />

						<div id="dxcc_update_status">Status:</br></div>

						<br/>
						<br/>
						<h5>Apply DXCC Data to Logbook</h5>
						<p class="card-text">
							After updating, Cloudlog can fill in missing callsign information in the logbook using the newly-obtained DXCC data.
							You can choose to check just the QSOs in the logbook that are missing DXCC metadata or to re-check the entire logbook
							and update existing metadata as well, in case it has changed.
						</p>
						<p><a class="btn btn-primary" href="<?php echo site_url('update/check_missing_dxcc');?>">Check QSOs missing DXCC data</a></p>
						<p><a class="btn btn-primary" href="<?php echo site_url('update/check_missing_dxcc/all');?>">Re-check all QSOs in logbook</a></p>

						<h5>Apply Continent Data to Logbook</h5>
						<p class="card-text">
							This function can be used to update QSO continent information for all QSOs in Cloudlog missing that information.
						</p>
						<p><a class="btn btn-primary" href="<?php echo site_url('update/check_missing_continent');?>">Check QSOs missing continent data</a></p>
						<style>
							#dxcc_update_status{
							display: None;
							}
						</style>
					<?php } ?>
				</div>
				<div class="tab-pane fade" id="distance" role="tabpanel" aria-labelledby="distance-tab">
					<p class="card-text">Here you can update QSOs with missing distance information.</p>
					<p><a class="btn btn-primary" hx-get="<?php echo site_url('update/update_distances');?>"  hx-target="#distance_results" href="<?php echo site_url('update/update_distances');?>">Update distance data</a></p>
					<div id="distance_results"></div>
				</div>
			</div>
		</div>
	</div>
</div>


