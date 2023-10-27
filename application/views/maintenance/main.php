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
		<?php if($is_there_qsos_with_no_station_id >= 1) { ?>
					<div class="alert alert-danger" role="alert" style="margin-bottom: 0px !important;">
						<span class="badge badge-pill badge-warning">Warning</span> The Database contains <?php echo $is_there_qsos_with_no_station_id; ?> QSO<?php echo $is_there_qsos_with_no_station_id > 1 ? 's' : '' ?> without a station-profile (location)<br/>
					</div>
		  <div class="card-body">
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
			echo '<tr><td>'.$call['COL_STATION_CALLSIGN'].'</td><td><select name="station_profile" id="station_profile">';
			$options='';
			foreach ($stations->result() as $station) {
				$options.='<option value='.$station->station_id.'>'.$station->station_profile_name.' ('.$station->station_callsign.')</option>';
			}
			echo $options.'</select></td><td><button class="btn btn-warning" onClick="reassign(\''.$call['COL_STATION_CALLSIGN'].'\',$(\'#station_profile option:selected\').val());"><i class="fas fa-sync"></i>Reassign</a></button></td></tr>'; 
		} ?>
					</tbody>
				</table>
		  	</div>
		 </div>
		<?php 
			} else { ?>
		<div class="alert alert-secondary" role="alert" style="margin-bottom: 0px !important;">
			<span class="badge badge-pill badge-success">Everything ok</span> Every QSO in your Database is assigned to a station-profile (location)
		</div>
		<?php } ?>
	  </div>

	  <div class="card">
		  <div class="card-header">
			Settings Maintenance
		  </div>
		<?php if(!$this->config->item('cl_multilanguage')) { ?>
					<div class="alert alert-danger" role="alert" style="margin-bottom: 0px !important;">
						<span class="badge badge-pill badge-warning">Warning</span> You didn't enabled Multilanguage support in your config.php
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
			<span class="badge badge-pill badge-success">Everything ok</span> You have enabled Multuser-Language support
		</div>
		<?php } ?>
	  </div>
</div>

