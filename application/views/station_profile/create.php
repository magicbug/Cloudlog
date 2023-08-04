
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
		    <label for="stationNameInput">Station Name</label>
		    <input type="text" class="form-control" name="station_profile_name" id="stationNameInput" aria-describedby="stationNameInputHelp" placeholder="Home QTH" required>
		    <small id="stationNameInputHelp" class="form-text text-muted">Shortname for the station location. For example: Home (IO87IP)</small>
		  </div>

			<div class="form-group">
		    <label for="stationCallsignInput">Station Callsign</label>
		    <input type="text" class="form-control" name="station_callsign" id="stationCallsignInput" aria-describedby="stationCallsignInputHelp" placeholder="2M0SQL" required>
		    <small id="stationCallsignInputHelp" class="form-text text-muted">Station callsign. For example: 2M0SQL/P</small>
		  </div>

			<div class="form-group">
		    <label for="stationPowerInput">Station Power</label>
		    <input type="number" class="form-control" name="station_power" id="stationPowerInput" step="1" aria-describedby="stationPowerInputHelp" placeholder="10">
		    <small id="stationPowerInputHelp" class="form-text text-muted">Default station power. Overwritten by CAT.</small>
		  </div>

		  <div class="form-group">
		    <label for="stationDXCCInput">Station DXCC</label>
				<?php if ($dxcc_list->num_rows() > 0) { ?>
				<select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
				<option value="0" selected>- NONE -</option>
				<?php foreach ($dxcc_list->result() as $dxcc) { ?>
				<option value="<?php echo $dxcc->adif; ?>"><?php echo ucwords(strtolower($dxcc->name)) . ' - ' . $dxcc->prefix; if ($dxcc->end != NULL) echo ' ('.lang('gen_hamradio_deleted_dxcc').')';?>
				</option>
				<?php } ?>
				</select>
				<?php } ?>
		    <small id="stationDXCCInputHelp" class="form-text text-muted">Station DXCC entity. For example: Scotland</small>
		  </div>

		  <div class="form-group">
		    <label for="stationCityInput">Station City</label>
		    <input type="text" class="form-control" name="city" id="stationCityInput" aria-describedby="stationCityInputHelp">
		    <small id="stationCityInputHelp" class="form-text text-muted">Station city. For example: Inverness</small>
		  </div>

        <div class="form-row">
            <div class="form-group col-sm-6" id="us_state">
		    <label for="stateInput">Station State</label>
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
		    <small id="StateHelp" class="form-text text-muted">Station state. Applies to certain countries only. Leave blank if not applicable.</small>
		  </div>

		  <div class="form-group col-sm-6" id="canada_state">
		    <label for="stateInput">Station Canadian Province</label>
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
		    <small id="StateHelp" class="form-text text-muted">Station state. Applies to certain countries only. Leave blank if not applicable.</small>
		  </div>

		  <div class="form-group col-sm-6">
		    <label for="stationCntyInput">Station County</label>
		    <input disabled="disabled" type="text" class="form-control" name="station_cnty" id="stationCntyInput" aria-describedby="stationCntyInputHelp">
		    <small id="stationCntyInputHelp" class="form-text text-muted">Station County (Only used for USA/Alaska/Hawaii)</small>
		  </div>
        </div>

            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="stationCQZoneInput">CQ Zone</label>
                    <select class="custom-select" id="stationCQZoneInput" name="station_cq" required>
                        <?php
                        for ($i = 1; $i<=40; $i++) {
                            echo '<option value='. $i;

                            echo '>'. $i .'</option>';
                        }
                        ?>
                    </select>
                    <small id="stationCQInputHelp" class="form-text text-muted">If you don't know your CQ Zone then <a href="https://zone-check.eu/?m=cq" target="_blank">click here</a> to find it!</small>
                </div>

                <div class="form-group col-sm-6">
                    <label for="stationITUZoneInput">ITU Zone</label>
                    <select class="custom-select" id="stationITUZoneInput" name="station_itu" required>
                        <?php
                        for ($i = 1; $i<=90; $i++) {
                            echo '<option value='. $i;

                            echo '>'. $i .'</option>';
                        }
                        ?>
                    </select>
                    <small id="stationITUInputHelp" class="form-text text-muted">If you don't know your ITU Zone then <a href="https://zone-check.eu/?m=itu" target="_blank">click here</a> to find it!</small>
                </div>
            </div>

		  <div class="form-group">
		    <label for="stationGridsquareInput">Gridsquare</label>

			<div class="input-group mb-3">
			<input type="text" class="form-control" name="gridsquare" id="stationGridsquareInput" aria-describedby="stationGridInputHelp" required>
			<div class="input-group-append">
				<button type="button" class="btn btn-outline-secondary" onclick="getLocation()"><i class="fas fa-compass"></i> Get Gridsquare</button>
			</div>
			</div>

		    <small id="stationGridInputHelp" class="form-text text-muted">Station grid square. For example: IO87IP. If you don't know your grid square then <a href="https://zone-check.eu/?m=loc" target="_blank">click here</a>!</small>
		    <small id="stationGridInputHelp" class="form-text text-muted">If you are located on a grid line, enter multiple grid squares separated with commas. For example: IO77,IO78,IO87,IO88.</small>
		  </div>

            <div class="form-group">
                <label for="stationIOTAInput">IOTA Reference</label>
                <select class="custom-select" name="iota" id="stationIOTAInput" aria-describedby="stationIOTAInputHelp" placeholder="EU-005">
                    <option value =""></option>

                    <?php
                    foreach($iota_list as $i){
                        echo '<option value=' . $i->tag . '>' . $i->tag . ' - ' . $i->name . '</option>';
                    }
                    ?>

                </select>
                <small id="stationIOTAInputHelp" class="form-text text-muted">Station IOTA reference. For example: EU-005</small>
                <small id="stationIOTAInputHelp" class="form-text text-muted">You can look up IOTA references at the <a target="_blank" href="https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html">IOTA World</a> website.</small>
            </div>

		  <div class="form-group">
		    <label for="stationSOTAInput">SOTA Reference</label>
		    <input type="text" class="form-control" name="sota" id="stationSOTAInput" aria-describedby="stationSOTAInputHelp">
		    <small id="stationSOTAInputHelp" class="form-text text-muted">Station SOTA reference. You can look up SOTA references at the <a target="_blank" href="https://www.sotamaps.org/">SOTA Maps</a> website.</small>
		  </div>

		  <div class="form-group">
		    <label for="stationWWFFInput">WWFF Reference</label>
		    <input type="text" class="form-control" name="wwff" id="stationWWFFInput" aria-describedby="stationWWFFInputHelp">
		    <small id="stationWWFFInputHelp" class="form-text text-muted">Station WWFF reference (e.g. DLFF-0069). You can look up WWFF references at the <a target="_blank" href="https://www.cqgma.org/mvs/">GMA Map</a> website.</small>
		  </div>

		  <div class="form-group">
		    <label for="stationPOTAInput">POTA Reference</label>
		    <input type="text" class="form-control" name="pota" id="stationPOTAInput" aria-describedby="stationPOTAInputHelp">
		    <small id="stationPOTAInputHelp" class="form-text text-muted">Station POTA reference (e.g. PA-0150). You can look up POTA references at the <a target="_blank" href="https://pota.app/#/map/">POTA Map</a> website.</small>
		  </div>

		  <div class="form-group">
		    <label for="stationSigInput">Signature</label>
		    <input type="text" class="form-control" name="sig" id="stationSigInput" aria-describedby="stationSigInputHelp">
		    <small id="stationSigInputHelp" class="form-text text-muted">Station Signature (e.g. GMA).</small>
		  </div>

		  <div class="form-group">
		    <label for="stationSigInfoInput">Signature Info</label>
		    <input type="text" class="form-control" name="sig_info" id="stationSigInfoInput" aria-describedby="stationSigInfoInputHelp">
		    <small id="stationSigInfoInput" class="form-text text-muted">Station Signature Info (e.g. DA/NW-357).</small>
		  </div>

            <div class="form-group">
                <label for="eqslNickname">eQSL QTH Nickname</label>
                <input type="text" class="form-control" name="eqslnickname" id="eqslNickname" aria-describedby="eqslhelp">
                <small id="eqslhelp" class="form-text text-muted">eQSL QTH Nickname.</small>
            </div>


            <div class="form-row">
                <div class="form-group col-sm-6">                                                                                                                                                    <label for="hrdlog_code">HRDLog.net Logbook API Key</label>
                    <input type="text" class="form-control" name="hrdlog_code" id="hrdlog_code" aria-describedby="hrdlog_codeHelp">
                    <small id="hrdlog_codeHelp" class="form-text text-muted">Find your API key on <a href="http://www.hrdlog.net/EditUser.aspx" target="_blank">HRDLog Userprofile</a></small>
                </div>
                <div class="form-group col-sm-6">
                    <label for="hrdlogrealtime">HRDLog.net Logbook Realtime Upload</label>                                                                                                                 <select class="custom-select" id="hrdlogrealtime" name="hrdlogrealtime">
                        <option value="1">Yes</option>
                        <option value="0" selected>No</option>
                    </select>
                </div>
            </div>

			<div class="alert alert-warning" role="alert">
					QRZ.com Logbook Requires Paid Subscription
			</div>

            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="qrzApiKey">QRZ.com Logbook API Key</label>
                    <input type="text" class="form-control" name="qrzapikey" id="qrzApiKey" aria-describedby="qrzApiKeyHelp">
                    <small id="qrzApiKeyHelp" class="form-text text-muted">Find your API key on <a href="https://logbook.qrz.com/logbook" target="_blank">QRZ.com's settings page</a></small>
                </div>
                <div class="form-group col-sm-6">
                    <label for="qrzrealtime">QRZ.com Logbook Realtime Upload</label>
                    <select class="custom-select" id="qrzrealtime" name="qrzrealtime">
                        <option value="1">Yes</option>
                        <option value="0" selected>No</option>
                    </select>
                </div>
            </div>

			<div class="form-row">
				<div class="form-group col-sm-6">
					<label for="webadifApiKey"> QO-100 Dx Club API Key </label>
					<input type="text" class="form-control" name="webadifapikey" id="webadifApiKey" aria-describedby="webadifApiKeyHelp">
					<small id="webadifApiKeyHelp" class="form-text text-muted">Create your API key on <a href="https://qo100dx.club" target="_blank">your QO-100 Dx Club's profile page</a></small>
				</div>
				<div class="form-group col-sm-6">
					<label for="webadifrealtime">QO-100 Dx Club Realtime Upload</label>
					<select class="custom-select" id="webadifrealtime" name="webadifrealtime">
						<option value="1">Yes</option>
						<option value="0" selected>No</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="oqrs">OQRS Enabled</label>
				<select class="custom-select" id="oqrs" name="oqrs">
					<option value="0">No</option>
					<option value="1">Yes</option>
				</select>
			</div>
			<div class="form-group">
						<label for="oqrs">OQRS Email alert</label>
						<select class="custom-select" id="oqrsemail" name="oqrsemail">
						<option value="0">No</option>
						<option value="1">Yes</option>
						</select>
						<small id="oqrsemailHelp" class="form-text text-muted">Make sure email is set up under admin and global options.</small>
					</div>
			<div class="form-group">
				<label for="oqrstext">OQRS Text</label>
				<input type="text" class="form-control" name="oqrstext" id="oqrstext" aria-describedby="oqrstextHelp">
				<small id="oqrstextHelp" class="form-text text-muted">Some info you want to add regarding QSL'ing.</small>
			</div>

			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Create Station Location</button>

		</form>
  </div>
</div>

<br>

</div>
