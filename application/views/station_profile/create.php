
<div class="container" id="create_station_profile">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?>
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

		<form method="post" action="<?php echo site_url('station/create'); ?>" name="create_profile">
		  <div class="form-group">
		    <label for="stationNameInput"><?php echo lang("station_location_name"); ?></label>
		    <input type="text" class="form-control" name="station_profile_name" id="stationNameInput" aria-describedby="stationNameInputHelp" placeholder="Home QTH" required>
		    <small id="stationNameInputHelp" class="form-text text-muted"><?php echo lang("station_location_name_hint"); ?></small>
		  </div>

			<div class="form-group">
		    <label for="stationCallsignInput"><?php echo lang("station_location_callsign"); ?></label>
		    <input type="text" class="form-control" name="station_callsign" id="stationCallsignInput" aria-describedby="stationCallsignInputHelp" placeholder="2M0SQL" required>
		    <small id="stationCallsignInputHelp" class="form-text text-muted"><?php echo lang("station_location_callsign_hint"); ?></small>
		  </div>

			<div class="form-group">
		    <label for="stationPowerInput"><?php echo lang("station_location_power"); ?></label>
		    <input type="number" class="form-control" name="station_power" id="stationPowerInput" step="1" aria-describedby="stationPowerInputHelp" placeholder="10">
		    <small id="stationPowerInputHelp" class="form-text text-muted"><?php echo lang("station_location_power_hint"); ?></small>
		  </div>
		  <div class="form-group">
		    <label for="stationDXCCInput"><?php echo lang("station_location_dxcc"); ?></label>
				<?php if ($dxcc_list->num_rows() > 0) { ?>
				<select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
				<option value="0" selected><?php echo "- " . lang('general_word_none') . " -"; ?></option>
				<?php foreach ($dxcc_list->result() as $dxcc) { ?>
				<option value="<?php echo $dxcc->adif; ?>"><?php echo ucwords(strtolower($dxcc->name)) . ' - ' . $dxcc->prefix; if ($dxcc->end != NULL) echo ' ('.lang('gen_hamradio_deleted_dxcc').')';?>
				</option>
				<?php } ?>
				</select>
				<?php } ?>
		    <small id="stationDXCCInputHelp" class="form-text text-muted"><?php echo lang("station_location_dxcc_hint"); ?></small>
			<div class="alert alert-danger" role="alert" id="warningMessageDXCC" style="display: none"> </div>
		  </div>

		  <div class="form-group">
		    <label for="stationCityInput"><?php echo lang("station_location_city"); ?></label>
		    <input type="text" class="form-control" name="city" id="stationCityInput" aria-describedby="stationCityInputHelp">
		    <small id="stationCityInputHelp" class="form-text text-muted"><?php echo lang("station_location_city_hint"); ?></small>
		  </div>

        <div class="form-row">
            <div class="form-group col-sm-6" id="us_state">
		    <label for="stateInput"><?php echo lang("station_location_state"); ?></label>
		    <select class="form-control custom-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
		    	<option value="" selected></option>
				<option value="AK">Alaska</option>
				<option value="AL">Alabama</option>
				<option value="AR">Arkansas</option>
				<option value="AZ">Arizona</option>
				<option value="CA">California</option>
				<option value="CO">Colorado</option>
				<option value="CT">Connecticut</option>
				<option value="DE">Delaware</option>
				<option value="FL">Florida</option>
				<option value="GA">Georgia</option>
				<option value="HI">Hawaii</option>
				<option value="IA">Iowa</option>
				<option value="ID">Idaho</option>
				<option value="IL">Illinois</option>
				<option value="IN">Indiana</option>
				<option value="KS">Kansas</option>
				<option value="KY">Kentucky</option>
				<option value="LA">Louisiana</option>
				<option value="MA">Massachusetts</option>
				<option value="MD">Maryland</option>
				<option value="ME">Maine</option>
				<option value="MI">Michigan</option>
				<option value="MN">Minnesota</option>
				<option value="MO">Missouri</option>
				<option value="MS">Mississippi</option>
				<option value="MT">Montana</option>
				<option value="NC">North Carolina</option>
				<option value="ND">North Dakota</option>
				<option value="NE">Nebraska</option>
				<option value="NH">New Hampshire</option>
				<option value="NJ">New Jersey</option>
				<option value="NM">New Mexico</option>
				<option value="NV">Nevada</option>
				<option value="NY">New York</option>
				<option value="OH">Ohio</option>
				<option value="OK">Oklahoma</option>
				<option value="OR">Oregon</option>
				<option value="PA">Pennsylvania</option>
				<option value="RI">Rhode Island</option>
				<option value="SC">South Carolina</option>
				<option value="SD">South Dakota</option>
				<option value="TN">Tennessee</option>
				<option value="TX">Texas</option>
				<option value="UT">Utah</option>
				<option value="VA">Virginia</option>
				<option value="VT">Vermont</option>
				<option value="WA">Washington</option>
				<option value="WI">Wisconsin</option>
				<option value="WV">West Virginia</option>
				<option value="WY">Wyoming</option>
			</select>
		    <small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
		  </div>

		  <div class="form-group col-sm-6" id="canada_state">
		    <label for="stateInput"><?php echo lang("station_location_state"); ?></label>
		    <select class="form-control custom-select" name="station_ca_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
		    	<option value="" selected></option>
				<option value="AB">Alberta</option>
				<option value="BC">British Columbia</option>
				<option value="MB">Manitoba</option>
				<option value="NB">New Brunswick</option>
				<option value="NL">Newfoundland & Labrador</option>
				<option value="NS">Nova Scotia</option>
				<option value="NT">Northwest Territories</option>
				<option value="NU">Nunavut</option>
				<option value="ON">Ontario</option>
				<option value="PE">Prince Edward Island</option>
				<option value="QC">Quebec</option>
				<option value="SK">Saskatchewan</option>
				<option value="YT">Yukon</option>
			</select>
		    <small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
		  </div>

		  <div class="form-group col-sm-6">
		    <label for="stationCntyInput"><?php echo lang("station_location_county"); ?></label>
		    <input disabled="disabled" type="text" class="form-control" name="station_cnty" id="stationCntyInput" aria-describedby="stationCntyInputHelp">
		    <small id="stationCntyInputHelp" class="form-text text-muted"><?php echo lang("station_location_county_hint"); ?></small>
		  </div>
        </div>

            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="stationCQZoneInput"><?php echo lang("gen_hamradio_cq_zone"); ?></label>
                    <select class="custom-select" id="stationCQZoneInput" name="station_cq" required>
                        <?php
                        for ($i = 1; $i<=40; $i++) {
                            echo '<option value='. $i;

                            echo '>'. $i .'</option>';
                        }
                        ?>
                    </select>
                    <small id="stationCQInputHelp" class="form-text text-muted"><?php echo lang("gen_find_zone_cq_part1")." <a href='https://zone-check.eu/?m=cq' target='_blank'>".lang("gen_find_zone_part2")."</a> ".lang("gen_find_zone_part3"); ?></small>
                </div>

                <div class="form-group col-sm-6">
                    <label for="stationITUZoneInput"><?php echo lang("gen_hamradio_itu_zone"); ?></label>
                    <select class="custom-select" id="stationITUZoneInput" name="station_itu" required>
                        <?php
                        for ($i = 1; $i<=90; $i++) {
                            echo '<option value='. $i;

                            echo '>'. $i .'</option>';
                        }
                        ?>
                    </select>
                    <small id="stationITUInputHelp" class="form-text text-muted"><?php echo lang("gen_find_zone_itu_part1")." <a href='https://zone-check.eu/?m=itu' target='_blank'>".lang("gen_find_zone_part2")."</a> ".lang("gen_find_zone_part3"); ?></small>
                </div>
            </div>

		  <div class="form-group">
		    <label for="stationGridsquareInput"><?php echo lang("station_location_gridsquare"); ?></label>

			<div class="input-group mb-3">
			<input type="text" class="form-control" name="gridsquare" id="stationGridsquareInput" aria-describedby="stationGridInputHelp" required>
			<div class="input-group-append">
				<button type="button" class="btn btn-outline-secondary" onclick="getLocation()"><i class="fas fa-compass"></i> <?php echo lang("gen_hamradio_get_gridsquare"); ?></button>
			</div>
			</div>

		    <small id="stationGridInputHelp" class="form-text text-muted"><?php echo lang("station_location_gridsquare_hint_ln1"); ?></small>
		    <small id="stationGridInputHelp" class="form-text text-muted"><?php echo lang("station_location_gridsquare_hint_ln2"); ?></small>
		  </div>

            <div class="form-group">
                <label for="stationIOTAInput"><?php echo lang("gen_hamradio_iota_reference"); ?></label>
                <select class="custom-select" name="iota" id="stationIOTAInput" aria-describedby="stationIOTAInputHelp" placeholder="EU-005">
                    <option value =""></option>

                    <?php
                    foreach($iota_list as $i){
                        echo '<option value=' . $i->tag . '>' . $i->tag . ' - ' . $i->name . '</option>';
                    }
                    ?>

                </select>
                <small id="stationIOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_iota_hint_ln1"); ?></small>
                <small id="stationIOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_iota_hint_ln2"); ?></small>
            </div>

		  <div class="form-group">
		    <label for="stationSOTAInput"><?php echo lang("gen_hamradio_sota_reference"); ?></label>
		    <input type="text" class="form-control" name="sota" id="stationSOTAInput" aria-describedby="stationSOTAInputHelp">
		    <small id="stationSOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_sota_hint_ln1"); ?></small>
		  </div>

		  <div class="form-group">
		    <label for="stationWWFFInput"><?php echo lang("gen_hamradio_wwff_reference"); ?></label>
		    <input type="text" class="form-control" name="wwff" id="stationWWFFInput" aria-describedby="stationWWFFInputHelp">
		    <small id="stationWWFFInputHelp" class="form-text text-muted"><?php echo lang("station_location_wwff_hint_ln1"); ?></small>
		  </div>

		  <div class="form-group">
		    <label for="stationPOTAInput"><?php echo lang("gen_hamradio_pota_reference"); ?></label>
		    <input type="text" class="form-control" name="pota" id="stationPOTAInput" aria-describedby="stationPOTAInputHelp">
		    <small id="stationPOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_pota_hint_ln1"); ?></small>
		  </div>

		  <div class="form-group">
		    <label for="stationSigInput"><?php echo lang("station_location_signature"); ?></label>
		    <input type="text" class="form-control" name="sig" id="stationSigInput" aria-describedby="stationSigInputHelp">
		    <small id="stationSigInputHelp" class="form-text text-muted"><?php echo lang("station_location_signature_name_hint"); ?></small>
		  </div>

		  <div class="form-group">
		    <label for="stationSigInfoInput"><?php echo lang("station_location_signature_info"); ?></label>
		    <input type="text" class="form-control" name="sig_info" id="stationSigInfoInput" aria-describedby="stationSigInfoInputHelp">
		    <small id="stationSigInfoInput" class="form-text text-muted"><?php echo lang("station_location_signature_info_hint"); ?></small>
		  </div>

            <div class="form-group">
                <label for="eqslNickname">eQSL QTH Nickname</label> <!-- This does not need Multilanguage Support -->
                <input type="text" class="form-control" name="eqslnickname" id="eqslNickname" aria-describedby="eqslhelp">
                <small id="eqslhelp" class="form-text text-muted"><?php echo lang("station_location_eqsl_hint"); ?></small>
            </div>


            <div class="form-row">
                <div class="form-group col-sm-6">                                                                                                                                                    
					<label for="hrdlog_code">HRDLog.net API Code</label> <!-- This does not need Multilanguage Support -->
                    <input type="text" class="form-control" name="hrdlog_code" id="hrdlog_code" aria-describedby="hrdlog_codeHelp">
                    <small id="hrdlog_codeHelp" class="form-text text-muted"><?php echo lang("station_location_hrdlog_hint"); ?></a></small>
                </div>
                <div class="form-group col-sm-6">
                    <label for="hrdlogrealtime"><?php echo lang("station_location_hrdlog_realtime_upload"); ?></label>                                                                                                                 
					<select class="custom-select" id="hrdlogrealtime" name="hrdlogrealtime">
                        <option value="1"><?php echo lang("general_word_yes"); ?></option>
                        <option value="0" selected><?php echo lang("general_word_no"); ?></option>
                    </select>
                </div>
            </div>

			<div class="alert alert-warning" role="alert">
				<?php echo "QRZ.com - " . lang("station_location_qrz_subscription"); ?>
			</div>

            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="qrzApiKey">QRZ.com Logbook API Key</label>  <!-- This does not need Multilanguage Support -->
                    <input type="text" class="form-control" name="qrzapikey" pattern="^([A-F0-9]{4}-){3}[A-F0-9]{4}$" id="qrzApiKey" aria-describedby="qrzApiKeyHelp">
                    <small id="qrzApiKeyHelp" class="form-text text-muted"><?php echo lang("station_location_qrz_hint"); ?></a></small>
                </div>
                <div class="form-group col-sm-6">
                    <label for="qrzrealtime"><?php echo lang("station_location_qrz_realtime_upload"); ?></label>
                    <select class="custom-select" id="qrzrealtime" name="qrzrealtime">
                        <option value="1"><?php echo lang("general_word_yes"); ?></option>
                        <option value="0" selected><?php echo lang("general_word_no"); ?></option>
                    </select>
                </div>
            </div>

			<div class="form-row">
				<div class="form-group col-sm-6">
					<label for="webadifApiKey"> QO-100 Dx Club API Key </label> <!-- This does not need Multilanguage Support -->
					<input type="text" class="form-control" name="webadifapikey" id="webadifApiKey" aria-describedby="webadifApiKeyHelp">
					<small id="webadifApiKeyHelp" class="form-text text-muted"><?php echo lang("station_location_qo100_hint"); ?></a></small>
				</div>
				<div class="form-group col-sm-6">
					<label for="webadifrealtime"><?php echo lang("station_location_qo100_realtime_upload"); ?></label>
					<select class="custom-select" id="webadifrealtime" name="webadifrealtime">
						<option value="1"><?php echo lang("general_word_yes"); ?></option>
						<option value="0" selected><?php echo lang("general_word_no"); ?></option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="oqrs"><?php echo lang("station_location_oqrs_enabled"); ?></label>
				<select class="custom-select" id="oqrs" name="oqrs">
					<option value="0"><?php echo lang("general_word_no"); ?></option>
					<option value="1"><?php echo lang("general_word_yes"); ?></option>
				</select>
			</div>
			<div class="form-group">
						<label for="oqrs"><?php echo lang("station_location_oqrs_email_alert"); ?></label>
						<select class="custom-select" id="oqrsemail" name="oqrsemail">
						<option value="0"><?php echo lang("general_word_no"); ?></option>
						<option value="1"><?php echo lang("general_word_yes"); ?></option>
						</select>
						<small id="oqrsemailHelp" class="form-text text-muted"><?php echo lang("station_location_oqrs_email_hint"); ?></small>
					</div>
			<div class="form-group">
				<label for="oqrstext"><?php echo lang("station_location_oqrs_text"); ?></label>
				<input type="text" class="form-control" name="oqrstext" id="oqrstext" aria-describedby="oqrstextHelp">
				<small id="oqrstextHelp" class="form-text text-muted"><?php echo lang("station_location_oqrs_text_hint"); ?></small>
			</div>

			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> <?php echo lang("admin_create"); ?> <?php echo lang("station_location"); ?></button>

		</form>
  </div>
</div>

<br>

</div>
