<div class="container" id="create_station_profile">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('notice')) { ?>
		<div id="message" >
			<?php echo $this->session->flashdata('notice'); ?>
		</div>
	<?php } ?>

	<?php $this->load->helper('form'); ?>

	<?php echo validation_errors(); ?>

	<?php if($my_station_profile->station_id != NULL) {
		$form_action = lang("admin_update");
	?>
		<form method="post" action="<?php echo site_url('station/edit/'); ?><?php echo $my_station_profile->station_id; ?>" name="create_profile">
			<input type="hidden" name="station_id" value="<?php echo $my_station_profile->station_id; ?>">

	<?php } else {
		$form_action = lang("admin_create");
	?>
		<form method="post" action="<?php echo site_url('station/copy/'); ?><?php echo $copy_from; ?>" name="create_profile">
	<?php } ?>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<div class="card-header"><?php echo $page_title; ?> <?php echo "(" . lang("gen_hamradio_callsign") . ": "; ?> <?php echo $my_station_profile->station_callsign; ?>)</div>
				<div class="card-body">

					<div class="form-group">
						<label for="stationNameInput"><?php echo lang("station_location_name"); ?></label>
						<input type="text" class="form-control" name="station_profile_name" id="stationNameInput" aria-describedby="stationNameInputHelp" value="<?php if(set_value('station_profile_name') != "") { echo set_value('station_profile_name'); } else { echo $my_station_profile->station_profile_name; } ?>" required>
						<small id="stationNameInputHelp" class="form-text text-muted"><?php echo lang("station_location_name_hint"); ?></small>
					</div>

					<div class="form-group">
						<label for="stationCallsignInput"><?php echo lang("station_location_callsign"); ?></label>
						<input type="text" class="form-control" name="station_callsign" id="stationCallsignInput" aria-describedby="stationCallsignInputHelp" value="<?php if(set_value('station_callsign') != "") { echo set_value('station_callsign'); } else { echo $my_station_profile->station_callsign; } ?>" required>
						<small id="stationCallsignInputHelp" class="form-text text-muted"><?php echo lang("station_location_callsign_hint"); ?></small>
					</div>

					<div class="form-group">
						<label for="stationPowerInput"><?php echo lang("station_location_power"); ?></label>
						<input type="number" class="form-control" name="station_power" step="1" id="stationPowerInput" aria-describedby="stationPowerInputHelp" value="<?php if(set_value('station_power') != "") { echo set_value('station_power'); } else { echo $my_station_profile->station_power; } ?>">
						<small id="stationPowerInputHelp" class="form-text text-muted"><?php echo lang("station_location_power_hint"); ?></small>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<!-- Location Ends -->
		<div class="col-md">
			<div class="card">
				<div class="card-header"><?php echo lang("general_word_location"); ?></div>
				<div class="card-body">
					<!-- DXCC -->
					<div class="form-group">
					    <label for="stationDXCCInput"><?php echo lang("station_location_dxcc"); ?></label>
					    <?php if ($dxcc_list->num_rows() > 0) { ?>
					        <select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
					            <option value="0" <?php if($my_station_profile->station_dxcc == "0") { ?>selected<?php } ?>><?php echo "- " . lang('general_word_none') . " -"; ?></option>
					            <?php foreach ($dxcc_list->result() as $dxcc) { ?>
					                <?php $isDeleted = $dxcc->end !== NULL; ?>
					                <option value="<?php echo $dxcc->adif; ?>" <?php if($my_station_profile->station_dxcc == $dxcc->adif) { ?>selected<?php } ?>>
					                    <?php echo ucwords(strtolower($dxcc->name)) . ' - ' . $dxcc->prefix;
					                    if ($isDeleted) {
					                        echo ' (' . lang('gen_hamradio_deleted_dxcc') . ')';
					                    }
					                    ?>
					                </option>
					            <?php } ?>
					        </select>
					        <?php } ?>
					    <small id="stationDXCCInputHelp" class="form-text text-muted"><?php echo lang("station_location_dxcc_hint"); ?></small>
						<div class="alert alert-danger" role="alert" id="warningMessageDXCC" style="display: none"></div>
					</div>

					<!-- City -->
					<div class="form-group">
						<label for="stationCityInput"><?php echo lang("station_location_city"); ?></label>
						<input type="text" class="form-control" name="city" id="stationCityInput" aria-describedby="stationCityInputHelp" value="<?php if(set_value('city') != "") { echo set_value('city'); } else { echo $my_station_profile->station_city; } ?>">
		    			<small id="stationCityInputHelp" class="form-text text-muted"><?php echo lang("station_location_city_hint"); ?></small>
		  			</div>

					<!-- US State -->
					<div class="form-group" id="us_state">
		    			<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
		    				<select class="form-control custom-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="AK" <?php if($my_station_profile->state == "AK") { echo "selected"; } ?>>Alaska</option>
								<option value="AL" <?php if($my_station_profile->state == "AL") { echo "selected"; } ?>>Alabama</option>
								<option value="AR" <?php if($my_station_profile->state == "AR") { echo "selected"; } ?>>Arkansas</option>
								<option value="AZ" <?php if($my_station_profile->state == "AZ") { echo "selected"; } ?>>Arizona</option>
								<option value="CA" <?php if($my_station_profile->state == "CA") { echo "selected"; } ?>>California</option>
								<option value="CO" <?php if($my_station_profile->state == "CO") { echo "selected"; } ?>>Colorado</option>
								<option value="CT" <?php if($my_station_profile->state == "CT") { echo "selected"; } ?>>Connecticut</option>
								<option value="DE" <?php if($my_station_profile->state == "DE") { echo "selected"; } ?>>Delaware</option>
								<option value="DC" <?php if($my_station_profile->state == "DC") { echo "selected"; } ?>>District of Columbia</option>
								<option value="FL" <?php if($my_station_profile->state == "FL") { echo "selected"; } ?>>Florida</option>
								<option value="GA" <?php if($my_station_profile->state == "GA") { echo "selected"; } ?>>Georgia</option>
								<option value="HI" <?php if($my_station_profile->state == "HI") { echo "selected"; } ?>>Hawaii</option>
								<option value="IA" <?php if($my_station_profile->state == "IA") { echo "selected"; } ?>>Iowa</option>
								<option value="ID" <?php if($my_station_profile->state == "ID") { echo "selected"; } ?>>Idaho</option>
								<option value="IL" <?php if($my_station_profile->state == "IL") { echo "selected"; } ?>>Illinois</option>
								<option value="IN" <?php if($my_station_profile->state == "IN") { echo "selected"; } ?>>Indiana</option>
								<option value="KS" <?php if($my_station_profile->state == "KS") { echo "selected"; } ?>>Kansas</option>
								<option value="KY" <?php if($my_station_profile->state == "KY") { echo "selected"; } ?>>Kentucky</option>
								<option value="LA" <?php if($my_station_profile->state == "LA") { echo "selected"; } ?>>Louisiana</option>
								<option value="MA" <?php if($my_station_profile->state == "MA") { echo "selected"; } ?>>Massachusetts</option>
								<option value="MD" <?php if($my_station_profile->state == "MD") { echo "selected"; } ?>>Maryland</option>
								<option value="ME" <?php if($my_station_profile->state == "ME") { echo "selected"; } ?>>Maine</option>
								<option value="MI" <?php if($my_station_profile->state == "MI") { echo "selected"; } ?>>Michigan</option>
								<option value="MN" <?php if($my_station_profile->state == "MN") { echo "selected"; } ?>>Minnesota</option>
								<option value="MO" <?php if($my_station_profile->state == "MO") { echo "selected"; } ?>>Missouri</option>
								<option value="MS" <?php if($my_station_profile->state == "MS") { echo "selected"; } ?>>Mississippi</option>
								<option value="MT" <?php if($my_station_profile->state == "MT") { echo "selected"; } ?>>Montana</option>
								<option value="NC" <?php if($my_station_profile->state == "NC") { echo "selected"; } ?>>North Carolina</option>
								<option value="ND" <?php if($my_station_profile->state == "ND") { echo "selected"; } ?>>North Dakota</option>
								<option value="NE" <?php if($my_station_profile->state == "NE") { echo "selected"; } ?>>Nebraska</option>
								<option value="NH" <?php if($my_station_profile->state == "NH") { echo "selected"; } ?>>New Hampshire</option>
								<option value="NJ" <?php if($my_station_profile->state == "NJ") { echo "selected"; } ?>>New Jersey</option>
								<option value="NM" <?php if($my_station_profile->state == "NM") { echo "selected"; } ?>>New Mexico</option>
								<option value="NV" <?php if($my_station_profile->state == "NV") { echo "selected"; } ?>>Nevada</option>
								<option value="NY" <?php if($my_station_profile->state == "NY") { echo "selected"; } ?>>New York</option>
								<option value="OH" <?php if($my_station_profile->state == "OH") { echo "selected"; } ?>>Ohio</option>
								<option value="OK" <?php if($my_station_profile->state == "OK") { echo "selected"; } ?>>Oklahoma</option>
								<option value="OR" <?php if($my_station_profile->state == "OR") { echo "selected"; } ?>>Oregon</option>
								<option value="PA" <?php if($my_station_profile->state == "PA") { echo "selected"; } ?>>Pennsylvania</option>
								<option value="RI" <?php if($my_station_profile->state == "RI") { echo "selected"; } ?>>Rhode Island</option>
								<option value="SC" <?php if($my_station_profile->state == "SC") { echo "selected"; } ?>>South Carolina</option>
								<option value="SD" <?php if($my_station_profile->state == "SD") { echo "selected"; } ?>>South Dakota</option>
								<option value="TN" <?php if($my_station_profile->state == "TN") { echo "selected"; } ?>>Tennessee</option>
								<option value="TX" <?php if($my_station_profile->state == "TX") { echo "selected"; } ?>>Texas</option>
								<option value="UT" <?php if($my_station_profile->state == "UT") { echo "selected"; } ?>>Utah</option>
								<option value="VA" <?php if($my_station_profile->state == "VA") { echo "selected"; } ?>>Virginia</option>
								<option value="VT" <?php if($my_station_profile->state == "VT") { echo "selected"; } ?>>Vermont</option>
								<option value="WA" <?php if($my_station_profile->state == "WA") { echo "selected"; } ?>>Washington</option>
								<option value="WI" <?php if($my_station_profile->state == "WI") { echo "selected"; } ?>>Wisconsin</option>
								<option value="WV" <?php if($my_station_profile->state == "WV") { echo "selected"; } ?>>West Virginia</option>
								<option value="WY" <?php if($my_station_profile->state == "WY") { echo "selected"; } ?>>Wyoming</option>
							</select>
		    				<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
		 				</div>

					<!-- Canada State -->
					<div class="form-group" id="canada_state">
		    			<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
		    				<select class="form-control custom-select" name="station_ca_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="AB" <?php if($my_station_profile->state == "AB") { echo "selected"; } ?>>Alberta</option>
								<option value="BC" <?php if($my_station_profile->state == "BC") { echo "selected"; } ?>>British Columbia</option>
								<option value="MB" <?php if($my_station_profile->state == "MB") { echo "selected"; } ?>>Manitoba</option>
								<option value="NB" <?php if($my_station_profile->state == "NB") { echo "selected"; } ?>>New Brunswick</option>
								<option value="NL" <?php if($my_station_profile->state == "NL") { echo "selected"; } ?>>Newfoundland & Labrador</option>
								<option value="NS" <?php if($my_station_profile->state == "NS") { echo "selected"; } ?>>Nova Scotia</option>
								<option value="NT" <?php if($my_station_profile->state == "NT") { echo "selected"; } ?>>Northwest Territories</option>
								<option value="NU" <?php if($my_station_profile->state == "NU") { echo "selected"; } ?>>Nunavut</option>
								<option value="ON" <?php if($my_station_profile->state == "ON") { echo "selected"; } ?>>Ontario</option>
								<option value="PE" <?php if($my_station_profile->state == "PE") { echo "selected"; } ?>>Prince Edward Island</option>
								<option value="QC" <?php if($my_station_profile->state == "QC") { echo "selected"; } ?>>Quebec</option>
								<option value="SK" <?php if($my_station_profile->state == "SK") { echo "selected"; } ?>>Saskatchewan</option>
								<option value="YT" <?php if($my_station_profile->state == "YT") { echo "selected"; } ?>>Yukon</option>
							</select>
		    				<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<!-- US County -->
						<div class="form-group">
							<label for="stationCntyInput"><?php echo lang("station_location_county"); ?></label>
							<input disabled="disabled" type="text" class="form-control" name="station_cnty" id="stationCntyInput" aria-describedby="stationCntyInputHelp" value="<?php if(set_value('station_cnty') != "") { echo set_value('station_cnty'); } else { echo $my_station_profile->station_cnty; } ?>">
							<small id="stationCntyInputHelp" class="form-text text-muted"><?php echo lang("station_location_county_hint"); ?></small>
		  				</div>
				</div>
			</div>
		</div>
		<!-- Location Ends -->

		<!-- Zones -->
		<div class="col-md">
			<div class="card">
				<div class="card-header"><?php echo lang("gen_hamradio_zones"); ?></div>
				<div class="card-body">
					<!-- CQ Zone -->
					<div class="form-group">
						<label for="stationCQZoneInput"><?php echo lang("gen_hamradio_cq_zone"); ?></label>
						<select class="custom-select" id="stationCQZoneInput" name="station_cq" required>
							<?php
							for ($i = 1; $i<=40; $i++) {
								echo '<option value='. $i;
								if ($my_station_profile->station_cq == $i) {
									echo " selected=\"selected\"";
								}
								echo '>'. $i .'</option>';
							}
							?>
						</select>
						<small id="stationCQInputHelp" class="form-text text-muted"><?php echo lang("gen_find_zone_cq_part1")." <a href='https://zone-check.eu/?m=cq' target='_blank'>".lang("gen_find_zone_part2")."</a> ".lang("gen_find_zone_part3"); ?></small>
					</div>

					<!-- ITU Zone -->
					<div class="form-group">
                    	<label for="stationITUZoneInput"><?php echo lang("gen_hamradio_itu_zone"); ?></label>
                    	<select class="custom-select" id="stationITUZoneInput" name="station_itu" required>
							<?php
							for ($i = 1; $i<=90; $i++) {
								echo '<option value='. $i;
								if ($my_station_profile->station_itu == $i) {
									echo " selected=\"selected\"";
								}
								echo '>'. $i .'</option>';
							}
							?>
                    	</select>
                    	<small id="stationITUInputHelp" class="form-text text-muted"><?php echo lang("gen_find_zone_itu_part1")." <a href='https://zone-check.eu/?m=itu' target='_blank'>".lang("gen_find_zone_part2")."</a> ".lang("gen_find_zone_part3"); ?></small>
                	</div>

				</div>
			</div>
		</div>
		<!-- Zones End -->
	</div>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header"><?php echo lang("station_location_gridsquare"); ?></h5>
				<div class="card-body">
					<div class="form-group">
		    			<label for="stationGridsquareInput"><?php echo lang("station_location_gridsquare"); ?></label>

						<div class="input-group mb-3">
						<input type="text" class="form-control" name="gridsquare" id="stationGridsquareInput" aria-describedby="stationGridInputHelp" value="<?php if(set_value('gridsquare') != "") { echo set_value('gridsquare'); } else { echo $my_station_profile->station_gridsquare; } ?>" required>
							<div class="input-group-append">
								<button type="button" class="btn btn-outline-secondary" onclick="getLocation()"><i class="fas fa-compass"></i> <?php echo lang("gen_hamradio_get_gridsquare"); ?></button>
							</div>
						</div>

		    			<small id="stationGridInputHelp" class="form-text text-muted"><?php echo lang("station_location_gridsquare_hint_ln1"); ?></small>
		    			<small id="stationGridInputHelp" class="form-text text-muted"><?php echo lang("station_location_gridsquare_hint_ln2"); ?></small>
		  			</div>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<h5 class="card-header"><?php echo lang("gen_hamradio_iota"); ?></h5>
				<div class="card-body">
					<div class="form-group">
                		<label for="stationIOTAInput"><?php echo lang("gen_hamradio_iota_reference"); ?></label>
                		<select class="custom-select" name="iota" id="stationIOTAInput" aria-describedby="stationIOTAInputHelp" placeholder="EU-005">
                    		<option value =""></option>
                    		<?php
                    			foreach($iota_list as $i){
                        		echo '<option value=' . $i->tag;
		                        if ($my_station_profile->station_iota == $i->tag) {
        		                    echo " selected =\"selected\"";
                		        }
                        		echo '>' . $i->tag . ' - ' . $i->name . '</option>';
                    			}
                    		?>
                		</select>

						<small id="stationIOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_iota_hint_ln1"); ?></small>
                		<small id="stationIOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_iota_hint_ln2"); ?></small>
            		</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header"><?php echo lang("gen_hamradio_sota"); ?></h5>
				<div class="card-body">
					<div class="form-group">
		    			<label for="stationSOTAInput"><?php echo lang("gen_hamradio_sota_reference"); ?></label>
		    			<input type="text" class="form-control" name="sota" id="stationSOTAInput" aria-describedby="stationSOTAInputHelp" value="<?php if(set_value('sota') != "") { echo set_value('sota'); } else { echo $my_station_profile->station_sota; } ?>">
		    			<small id="stationSOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_sota_hint_ln1"); ?></small>
		  			</div>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<h5 class="card-header"><?php echo lang("gen_hamradio_wwff"); ?></h5>
				<div class="card-body">
					<div class="form-group">
						<label for="stationWWFFInput"><?php echo lang("gen_hamradio_wwff_reference"); ?></label>
						<input type="text" class="form-control" name="wwff" id="stationWWFFInput" aria-describedby="stationWWFFInputHelp" value="<?php if(set_value('wwff') != "") { echo set_value('wwff'); } else { echo $my_station_profile->station_wwff; } ?>">
						<small id="stationWWFFInputHelp" class="form-text text-muted"><?php echo lang("station_location_wwff_hint_ln1"); ?></small>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<h5 class="card-header"><?php echo lang("gen_hamradio_pota"); ?></h5>
				<div class="card-body">
					<div class="form-group">
						<label for="stationPOTAInput"><?php echo lang("gen_hamradio_pota_reference"); ?></label>
						<input type="text" class="form-control" name="pota" id="stationPOTAInput" aria-describedby="stationPOTAInputHelp" value="<?php if(set_value('pota') != "") { echo set_value('pota'); } else { echo $my_station_profile->station_pota; } ?>">
						<small id="stationPOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_pota_hint_ln1"); ?></small>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header"><?php echo lang("station_location_signature"); ?></h5>
				<div class="card-body">
					<div class="form-group">
		    			<label for="stationSigInput"><?php echo lang("station_location_signature_name"); ?></label>
		    			<input type="text" class="form-control" name="sig" id="stationSigInput" aria-describedby="stationSigInputHelp" value="<?php if(set_value('sig') != "") { echo set_value('sig'); } else { echo $my_station_profile->station_sig; } ?>">
		    			<small id="stationSigInputHelp" class="form-text text-muted"><?php echo lang("station_location_signature_name_hint"); ?></small>
					</div>

					<div class="form-group">
		    			<label for="stationSigInfoInput"><?php echo lang("station_location_signature_info"); ?></label>
		    			<input type="text" class="form-control" name="sig_info" id="stationSigInfoInput" aria-describedby="stationSigInfoInputHelp" value="<?php if(set_value('sig_info') != "") { echo set_value('sig_info'); } else { echo $my_station_profile->station_sig_info; } ?>">
		    			<small id="stationSigInfoInputHelp" class="form-text text-muted"><?php echo lang("station_location_signature_info_hint"); ?></small>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header"><?php echo lang("eqsl_short"); ?></h5>
				<div class="card-body">
					<div class="form-group">
		    			<label for="eqslNickname">eQSL QTH Nickname</label> <!-- This does not need Multilanguage Support -->
		    			<input type="text" class="form-control" name="eqslnickname" id="eqslNickname" aria-describedby="eqslhelp" value="<?php if(set_value('eqslnickname') != "") { echo set_value('eqslnickname'); } else { echo $my_station_profile->eqslqthnickname; } ?>">
		    			<small id="eqslhelp" class="form-text text-muted"><?php echo lang("station_location_eqsl_hint"); ?></small>
		  			</div>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<h5 class="card-header">QRZ.com <span class="badge badge-warning"> <?php echo lang("station_location_qrz_subscription"); ?></span></h5> <!-- "QRZ.com" does not need Multilanguage Support -->
				<div class="card-body">
					<div class="form-group">
						<label for="qrzApiKey">QRZ.com Logbook API Key</label> <!-- This does not need Multilanguage Support -->
						<input type="text" class="form-control" name="qrzapikey" pattern="^([A-F0-9]{4}-){3}[A-F0-9]{4}$" id="qrzApiKey" aria-describedby="qrzApiKeyHelp" value="<?php if(set_value('qrzapikey') != "") { echo set_value('qrzapikey'); } else { echo $my_station_profile->qrzapikey; } ?>">
						<small id="qrzApiKeyHelp" class="form-text text-muted"><?php echo lang("station_location_qrz_hint"); ?></a></small>
					</div>

					<div class="form-group">
						<label for="qrzrealtime"><?php echo lang("station_location_qrz_realtime_upload"); ?></label>
						<select class="custom-select" id="qrzrealtime" name="qrzrealtime">
							<option value="1" <?php if ($my_station_profile->qrzrealtime == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->qrzrealtime == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header">HRDLog.net</h5> <!-- This does not need Multilanguage Support -->
				<div class="card-body">
					<div class="form-group">
						<label for="webadifApiKey">HRDLog.net API Code</label> <!-- This does not need Multilanguage Support -->
						<input type="text" class="form-control" name="hrdlog_code" id="hrdlog_code" aria-describedby="hrdlog_codeHelp" value="<?php if(set_value('hrdlog_code') != "") { echo set_value('hrdlog_code'); } else { echo $my_station_profile->hrdlog_code; } ?>">
						<small id="hrdlog_codeHelp" class="form-text text-muted"><?php echo lang("station_location_hrdlog_hint"); ?></a></small>
					</div>
					<div class="form-group">
						<label for="hrdlogrealtime"><?php echo lang("station_location_hrdlog_realtime_upload"); ?></label>
						<select class="custom-select" id="hrdlogrealtime" name="hrdlogrealtime">
							<option value="1" <?php if ($my_station_profile->hrdlogrealtime == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->hrdlogrealtime == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header">QO-100 Dx Club</h5> <!-- This does not need Multilanguage Support -->
				<div class="card-body">
					<div class="form-group">
						<label for="webadifApiKey">QO-100 Dx Club API Key</label> <!-- This does not need Multilanguage Support -->
						<input type="text" class="form-control" name="webadifapikey" id="webadifApiKey" aria-describedby="webadifApiKeyHelp" value="<?php if(set_value('webadifapikey') != "") { echo set_value('webadifapikey'); } else { echo $my_station_profile->webadifapikey; } ?>">
						<small id="webadifApiKeyHelp" class="form-text text-muted"><?php echo lang("station_location_qo100_hint"); ?></a></small>
					</div>
					<div class="form-group">
						<label for="webadifrealtime"><?php echo lang("station_location_qo100_realtime_upload"); ?></label>
						<select class="custom-select" id="webadifrealtime" name="webadifrealtime">
							<option value="1" <?php if ($my_station_profile->webadifrealtime == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->webadifrealtime == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md">
			<div class="card">
				<h5 class="card-header">OQRS</h5> <!-- This does not need Multilanguage Support -->
				<div class="card-body">
					<div class="form-group">
						<label for="oqrs"><?php echo lang("station_location_oqrs_enabled"); ?></label>
						<select class="custom-select" id="oqrs" name="oqrs">
							<option value="1" <?php if ($my_station_profile->oqrs == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->oqrs == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="oqrs"><?php echo lang("station_location_oqrs_email_alert"); ?></label>
						<select class="custom-select" id="oqrsemail" name="oqrsemail">
							<option value="1" <?php if ($my_station_profile->oqrs_email == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->oqrs_email == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
						</select>
						<small id="oqrsemailHelp" class="form-text text-muted"><?php echo lang("station_location_oqrs_email_hint"); ?></small>
					</div>
					<div class="form-group">
						<label for="oqrstext"><?php echo lang("station_location_oqrs_text"); ?></label>
						<input type="text" class="form-control" name="oqrstext" id="oqrstext" aria-describedby="oqrstextHelp" value="<?php if(set_value('oqrs_text') != "") { echo set_value('oqrs_text'); } else { echo $my_station_profile->oqrs_text; } ?>">
						<small id="oqrstextHelp" class="form-text text-muted"><?php echo lang("station_location_oqrs_text_hint"); ?></small>
					</div>

				</div>
			</div>
		</div>
	</div>

	<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;"><i class="fas fa-plus-square"></i> <?php echo $form_action; ?> <?php echo lang("station_location"); ?></button>

	</form>

</div>
