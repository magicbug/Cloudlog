<div class="container settings">

	<div class="row">
		<!-- Nav Start -->
		<?php $this->view('settings/assets/menu'); ?>
		<!-- Nav End -->

		<!-- Content -->
		<div class="col-md-9" id="create_station_profile">
			<?php if($this->session->flashdata('message')) { ?>
				<!-- Display Message -->
				<div class="alert-message error">
					<p><?php echo $this->session->flashdata('message'); ?></p>
				</div>
			<?php } ?>

			<div class="card">
			  <div class="card-header">
			    <?php echo $page_title; ?> <?php echo $my_station_profile->station_profile_name; ?> (Callsign: <?php echo $my_station_profile->station_callsign; ?>)
			  </div>
			  <div class="card-body">
			    <h5 class="card-title"></h5>
			    <p class="card-text"></p>
					<?php if($this->session->flashdata('notice')) { ?>
						<div id="message" >
						<?php echo $this->session->flashdata('notice'); ?>
						</div>
					<?php } ?>

					<?php $this->load->helper('form'); ?>

					<?php echo validation_errors(); ?>

					<form method="post" action="<?php echo site_url('station/edit/'); ?><?php echo $my_station_profile->station_id; ?>" name="create_profile">

						<input type="hidden" name="station_id" value="<?php echo $my_station_profile->station_id; ?>">
					  <div class="form-group">
					    <label for="stationNameInput">Station Name</label>
					    <input type="text" class="form-control" name="station_profile_name" id="stationNameInput" aria-describedby="stationNameInputHelp" value="<?php if(set_value('station_profile_name') != "") { echo set_value('station_profile_name'); } else { echo $my_station_profile->station_profile_name; } ?>" required>
					    <small id="stationNameInputHelp" class="form-text text-muted">Shortname for the station location for example Home (IO87IP)</small>
					  </div>

						<div class="form-group">
					    <label for="stationCallsignInput">Station Callsign</label>
					    <input type="text" class="form-control" name="station_callsign" id="stationCallsignInput" aria-describedby="stationCallsignInputHelp" value="<?php if(set_value('station_callsign') != "") { echo set_value('station_callsign'); } else { echo $my_station_profile->station_callsign; } ?>" required>
					    <small id="stationCallsignInputHelp" class="form-text text-muted">Station Callsign for example 2M0SQL/P</small>
					  </div>

					  <div class="form-group">
					    <label for="stationDXCCInput">Station DXCC</label>
							<?php if ($dxcc_list->num_rows() > 0) { ?>
							<select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
							<?php foreach ($dxcc_list->result() as $dxcc) { ?>
							<option value="<?php echo $dxcc->adif; ?>" <?php if($my_station_profile->station_dxcc == $dxcc->adif) { ?>selected<?php } ?>><?php echo $dxcc->name; ?></option>
							<?php } ?>
							</select>
							<?php } ?>
							<input type="hidden" id="country" name="station_country" value="<?php if(set_value('station_country') != "") { echo set_value('station_country'); } else { echo $my_station_profile->station_country; } ?>" required />
					    <small id="stationDXCCInputHelp" class="form-text text-muted">Station DXCC</small>
					  </div>

						<div class="form-group">
					    <label for="stationCityInput">Station City</label>
					    <input type="text" class="form-control" name="city" id="stationCityInput" aria-describedby="stationCityInputHelp" value="<?php if(set_value('city') != "") { echo set_value('city'); } else { echo $my_station_profile->station_city; } ?>" required>
					    <small id="stationCityInputHelp" class="form-text text-muted">Station City for example Inverness</small>
					  </div>

					  <div class="form-group">
					    <label for="stationCntyInput">Station Cnty</label>
					    <input type="text" class="form-control" name="station_cnty" id="stationCntyInput" aria-describedby="stationCntyInputHelp" value="<?php if(set_value('station_cnty') != "") { echo set_value('station_cnty'); } else { echo $my_station_profile->station_cnty; } ?>">
					    <small id="stationCntyInputHelp" class="form-text text-muted">Station Cnty #get def from ADIF Spec#</small>
					  </div>

					  <div class="form-group">
					    <label for="stationCQZoneInput">CQ Zone</label>
					    <input type="text" class="form-control" name="station_cq" id="stationCQZoneInput" aria-describedby="stationCQInputHelp" value="<?php if(set_value('station_cq') != "") { echo set_value('station_cq'); } else { echo $my_station_profile->station_cq; } ?>" required>
					    <small id="stationCQInputHelp" class="form-text text-muted">If you do not know your CQ Zone <a href="http://www4.plala.or.jp/nomrax/CQ/" target="_blank">click Here to find it!</a></small>
					  </div>

					  <div class="form-group">
					    <label for="stationITUZoneInput">ITU Zone</label>
					    <input type="text" class="form-control" name="station_itu" id="stationITUZoneInput" aria-describedby="stationITUInputHelp" value="<?php if(set_value('station_itu') != "") { echo set_value('station_itu'); } else { echo $my_station_profile->station_itu; } ?>" required>
					    <small id="stationITUInputHelp" class="form-text text-muted">If you do not know your ITU Zone <a href="http://www4.plala.or.jp/nomrax/ITU/" target="_blank">click Here to find it!</a></small>
					  </div>

					  <div class="form-group">
					    <label for="stationGridsquareInput">Gridsquare</label>
					    <input type="text" class="form-control" name="gridsquare" id="stationGridsquareInput" aria-describedby="stationGridInputHelp" value="<?php if(set_value('gridsquare') != "") { echo set_value('gridsquare'); } else { echo $my_station_profile->station_gridsquare; } ?>" required>
					    <small id="stationGridInputHelp" class="form-text text-muted">Station Gridsquare for example IO87IP, if you are at a gridline enter the gridsquare with a comma for example IO77,IO78,IO87,IO88.</small>
					  </div>

					  <div class="form-group">
					    <label for="stationIOTAInput">IOTA Reference</label>
					    <input type="text" class="form-control" name="iota" id="stationIOTAInput" aria-describedby="stationIOTAInputHelp" value="<?php if(set_value('iota') != "") { echo set_value('iota'); } else { echo $my_station_profile->station_iota; } ?>">
					    <small id="stationIOTAInputHelp" class="form-text text-muted">Station IOTA Reference for example EU-005.</small>
					  </div>

					  <div class="form-group">
					    <label for="stationSOTAInput">SOTA Reference</label>
					    <input type="text" class="form-control" name="sota" id="stationSOTAInput" aria-describedby="stationSOTAInputHelp" value="<?php if(set_value('sota') != "") { echo set_value('sota'); } else { echo $my_station_profile->station_sota; } ?>">
					    <small id="stationSOTAInputHelp" class="form-text text-muted">Station SOTA Reference.</small>
					  </div>

					  <div class="form-group">
					    <label for="eqslNickname">eQSL QTH Nickname</label>
					    <input type="text" class="form-control" name="eqslnickname" id="eqslNickname" aria-describedby="eqslhelp" value="<?php if(set_value('eqslnickname') != "") { echo set_value('eqslnickname'); } else { echo $my_station_profile->eqslqthnickname; } ?>">
					    <small id="eqslhelp" class="form-text text-muted">eQSL QTH Nickname.</small>
					  </div>

						<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Update Station Profile</button>

					</form>
			  </div>
			</div>
		</div>
	</div>

</div>