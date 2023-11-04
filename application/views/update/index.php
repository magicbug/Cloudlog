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
				<li class="nav-item">
					<a class="nav-link" id="mig2datafolder-tab" data-toggle="tab" href="#mig2datafolder" role="tab" aria-controls="update" aria-selected="false">Data folder migration</a>
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
				
				<div class="tab-pane fade" id="mig2datafolder" role="tabpanel" aria-labelledby="mig2datafolder-tab">
					<?php if ($update_mig2datafolder_centralized_data_folder_not_exist) {
						echo "<div class=\"alert alert-danger\">WARNING : You config value 'centralized_data_folder' was not create on config.php file !</div>";
					} ?>
					<p class="card-text">Here you can migrate (move) oldest folder to a centralized data folder.</p>
					<div class="form-row">
                    	<div class="col-sm-1"><b>EQSL :</b>
                    	</div>
                    	<div class="col-sm-5">
                    		<label class="form-label">Original folder : </label><input name="update_mg2datafolder_eqsl_oldfolder" type="text" class="form-control" value="<?php echo $update_mig2datafolder_eqsl_oldfolder;?>" disabled />
                    	</div>
                    	<div class="col-sm-5">
                    		<label class="form-label">New folder : </label>
                    		<input name="update_mg2datafolder_eqsl_newfolder" type="text" class="form-control" value="<?php echo $update_mig2datafolder_eqsl_newfolder;?>" disabled />
                    	</div>
						<div class="col-sm-1">
							<?php if (!$update_mig2datafolder_centralized_data_folder_not_exist) { ?>
							<a class="btn btn-primary" hx-get="<?php echo site_url('update/update_datafolder_eqsl');?>" hx-target="#data_move2_results" style="padding:8px;margin-top:25px;" href="<?php echo site_url('update/update_datafolder_eqsl');?>">Move To</a>
							<?php } ?>
						</div>
                    </div>
                    <br/>
					<div class="form-row">
                    	<div class="col-sm-1"><b>QSL Card:</b>
                    	</div>
                    	<div class="col-sm-5">
                    		<label class="form-label">Original folder : </label><input name="update_mg2datafolder_eqsl_oldfolder" type="text" class="form-control" value="<?php echo $update_mig2datafolder_qsl_oldfolder;?>" disabled />
                    	</div>
                    	<div class="col-sm-5">
                    		<label class="form-label">New folder : </label>
                    		<input name="update_mg2datafolder_eqsl_newfolder" type="text" class="form-control" value="<?php echo $update_mig2datafolder_qsl_newfolder;?>" disabled />
                    	</div>
						<div class="col-sm-1">
							<?php if (!$update_mig2datafolder_centralized_data_folder_not_exist) { ?>
							<a class="btn btn-primary" hx-get="<?php echo site_url('update/update_datafolder_qsl');?>" hx-target="#data_move2_results" style="padding:8px;margin-top:25px;" href="<?php echo site_url('update/update_datafolder_qsl');?>">Move To</a>
							<?php } ?>
						</div>
                    </div>
					<div id="data_move2_results" style="margin:10px 20px 10px 10px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>


