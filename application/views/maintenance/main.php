<div class="container">
	<br>
		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>
	
	<h2><?php echo $page_title; ?></h2>
	
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

	  <div class="card">
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
</div>

