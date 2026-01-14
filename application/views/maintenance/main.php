<div class="container">
	<br>
		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>
	
	<h2><?php echo $page_title; ?></h2>

	<style>
		.htmx-indicator {
			display: none;
		}
		.htmx-request .htmx-indicator {
			display: inline-block;
		}
		.htmx-request.htmx-indicator {
			display: inline-block;
		}
	</style>
	
	<div class="card" style="margin-bottom: 15px;">
		  <div class="card-header">
			QSO-DB Maintenance
		  </div>
		<?php if(!empty($qsos_with_no_station_id)) { ?>
					<div class="alert alert-danger" role="alert" style="margin-bottom: 0px !important;">
						<span class="badge rounded-pill text-bg-warning">Warning</span> The Database contains <?php echo count($qsos_with_no_station_id); ?> QSO<?php echo count($qsos_with_no_station_id) > 1 ? 's' : '' ?> without a station-profile (location)<br/>
					</div>
		  <div class="card-body">
		  <div class?"table-responsive">
				<table id="unasigned_qsos_table" class="table table-sm table-striped">
					<thead>
						<tr>
							<th scope="col"><input type="checkbox" onClick="toggleAll(this)"></th>
							<th scope="col">Date</th>
							<th scope="col">Time</th>
							<th scope="col">Call</th>
							<th scope="col">Mode</th>
							<th scope="col">Band</th>
						</tr>
					</thead>
					<tbody>
						<?php if($this->session->userdata('user_date_format')) {
									$custom_date_format = $this->session->userdata('user_date_format');
								} else {
									$custom_date_format = 'd.m.Y';
								}
								foreach ($qsos_with_no_station_id as $qso) {
									echo '<tr>';
									echo '<td><input type="checkbox" id="'.$qso->COL_PRIMARY_KEY.'" name="cBox[]" value="'.$qso->COL_PRIMARY_KEY.'"></td>';
									$timestamp = strtotime($qso->COL_TIME_ON);
									echo '<td>'.date($custom_date_format, $timestamp).'</td>';
									$timestamp = strtotime($qso->COL_TIME_ON);
									echo '<td>'.date('H:i', $timestamp).'</td>';
									echo '<td>'.$qso->COL_CALL.'</td>';
									echo '<td>'.$qso->COL_MODE.'</td>';
									echo '<td>'.$qso->COL_BAND.'</td>';
									echo '</tr>';
								} ?>
					</tbody>
				</table>
		  </div>
		  	<p class="card-text">Please reassign those QSOs to an existing station location:</p>
		
		
		 	<div class="table-responsive">
				<table id="station_locations_table" class="table table-sm table-striped">
					<thead>
						<tr>
							<th scope="col">Call</th>
							<th scope="col">Target Location</th>
							<th scope="col">Reassign</th>
						</tr>
					</thead>
					<tbody>
		<?php 
		foreach ($calls_wo_sid as $call) {
			echo '<tr><td><div id="station_call">'.$call['COL_STATION_CALLSIGN'].'</div></td><td><select name="station_profile" id="station_profile" onChange="updateCallsign(this)">';
			$options='';
			foreach ($stations->result() as $station) {
				$options.='<option value='.$station->station_id.'>'.$station->station_profile_name.' ('.$station->station_callsign.')</option>';
			}
			echo $options.'</select></td><td><button class="btn btn-warning" onClick="reassign(\''.$call['COL_STATION_CALLSIGN'].'\',$(\'#station_profile option:selected\').val());"><i class="fas fa-sync"></i> Reassign</a></button></td></tr>';
		} ?>
					</tbody>
				</table>
		  	</div>
		 </div>
		<?php 
			} else { ?>
		<div class="alert alert-secondary" role="alert" style="margin-bottom: 0px !important;">
			<span class="badge rounded-pill text-bg-success">Everything ok</span> Every QSO in your Database is assigned to a station-profile (location)
		</div>
		<?php } ?>
	  </div>

	  <div class="card" style="margin-bottom: 15px;">
		  <div class="card-header">
			Settings Maintenance
		  </div>
		<?php if(!$this->config->item('cl_multilanguage')) { ?>
					<div class="alert alert-danger" role="alert" style="margin-bottom: 0px !important;">
						<span class="badge rounded-pill text-bg-warning">Warning</span> You didn't enabled Multilanguage support in your config.php
					</div>
		  <div class="card-body">
		  	<p class="card-text">Please edit your ./application/config/config.php File and add some rows to it:</br></br>
			Go to your application/config Folder and compare config.sample.php with your config.php</br>
			You'll probably find a block with language-settings. Please include this block into your current config.php
			</p>
		  </div>	
		
		<?php 
		} else { ?>
		<div class="alert alert-secondary" role="alert" style="margin-bottom: 0px !important;">
			<span class="badge rounded-pill text-bg-success">Everything ok</span> You have enabled Multuser-Language support
		</div>
		<?php } ?>
	  </div>

	  <div class="card">
		  <div class="card-header">
			Data File Updates
		  </div>
		  <div class="card-body">
		  	<p class="card-text">Manually update reference data files used by Cloudlog. These updates are normally run via cron, but you can trigger them manually here.</p>
			
			<div class="table-responsive">
				<table class="table table-sm">
					<thead>
						<tr>
							<th scope="col">Data Source</th>
							<th scope="col">Description</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>Clublog SCP</strong></td>
							<td>Super Check Partial callsign database</td>
							<td>
								<button class="btn btn-sm btn-primary" 
										hx-get="<?php echo site_url('update/update_clublog_scp_ui'); ?>" 
										hx-target="#update-result-clublog"
										hx-indicator="#spinner-clublog">
									<i class="fas fa-sync"></i> Update Clublog SCP
								</button>
								<span id="spinner-clublog" class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
								<div id="update-result-clublog" class="mt-2"></div>
							</td>
						</tr>
						<tr>
							<td><strong>LoTW Users</strong></td>
							<td>Active Logbook of the World users database</td>
							<td>
								<button class="btn btn-sm btn-primary" 
										hx-get="<?php echo site_url('update/lotw_users_ui'); ?>" 
										hx-target="#update-result-lotw"
										hx-indicator="#spinner-lotw">
									<i class="fas fa-sync"></i> Update LoTW Users
								</button>
								<span id="spinner-lotw" class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
								<div id="update-result-lotw" class="mt-2"></div>
							</td>
						</tr>
						<tr>
							<td><strong>DOK</strong></td>
							<td>German DOK and SDOK reference data</td>
							<td>
								<button class="btn btn-sm btn-primary" 
										hx-get="<?php echo site_url('update/update_dok_ui'); ?>" 
										hx-target="#update-result-dok"
										hx-indicator="#spinner-dok">
									<i class="fas fa-sync"></i> Update DOK
								</button>
								<span id="spinner-dok" class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
								<div id="update-result-dok" class="mt-2"></div>
							</td>
						</tr>
						<tr>
							<td><strong>SOTA</strong></td>
							<td>Summits on the Air reference data</td>
							<td>
								<button class="btn btn-sm btn-primary" 
										hx-get="<?php echo site_url('update/update_sota_ui'); ?>" 
										hx-target="#update-result-sota"
										hx-indicator="#spinner-sota">
									<i class="fas fa-sync"></i> Update SOTA
								</button>
								<span id="spinner-sota" class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
								<div id="update-result-sota" class="mt-2"></div>
							</td>
						</tr>
						<tr>
							<td><strong>WWFF</strong></td>
							<td>World Wide Flora & Fauna reference data</td>
							<td>
								<button class="btn btn-sm btn-primary" 
										hx-get="<?php echo site_url('update/update_wwff_ui'); ?>" 
										hx-target="#update-result-wwff"
										hx-indicator="#spinner-wwff">
									<i class="fas fa-sync"></i> Update WWFF
								</button>
								<span id="spinner-wwff" class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
								<div id="update-result-wwff" class="mt-2"></div>
							</td>
						</tr>
						<tr>
							<td><strong>POTA</strong></td>
							<td>Parks on the Air reference data</td>
							<td>
								<button class="btn btn-sm btn-primary" 
										hx-get="<?php echo site_url('update/update_pota_ui'); ?>" 
										hx-target="#update-result-pota"
										hx-indicator="#spinner-pota">
									<i class="fas fa-sync"></i> Update POTA
								</button>
								<span id="spinner-pota" class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
								<div id="update-result-pota" class="mt-2"></div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		  </div>
	  </div>
</div>

