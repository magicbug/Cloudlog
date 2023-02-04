<div class="container">
	<h2><?php echo $page_title; ?></h2>

	<div class="card">
		<div class="card-header">
    		DXCC Lookup Data
		 </div>
		<div class="card-body">
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
	</div>
</div>


