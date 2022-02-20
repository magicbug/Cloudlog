
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
		    <label for="stationDXCCInput">Station DXCC</label>
				<?php if ($dxcc_list->num_rows() > 0) { ?>
				<select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
				<option value="0" selected>NONE</option>
				<?php foreach ($dxcc_list->result() as $dxcc) { ?>
				<option value="<?php echo $dxcc->adif; ?>"><?php echo $dxcc->name; ?></option>
				<?php } ?>
				</select>
				<?php } ?>
				<input type="hidden" id="country" name="station_country" value="" required />
		    <small id="stationDXCCInputHelp" class="form-text text-muted">Station DXCC entity. For example: Scotland</small>
		  </div>

		  <div class="form-group">
		    <label for="stationCityInput">Station City</label>
		    <input type="text" class="form-control" name="city" id="stationCityInput" aria-describedby="stationCityInputHelp" required>
		    <small id="stationCityInputHelp" class="form-text text-muted">Station city. For example: Inverness</small>
		  </div>

        <div class="form-row">
            <div class="form-group col-sm-6">
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
                    <small id="stationCQInputHelp" class="form-text text-muted">If you don't know your CQ Zone then <a href="http://www4.plala.or.jp/nomrax/CQ/" target="_blank">click here to find it!</a></small>
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
                    <small id="stationITUInputHelp" class="form-text text-muted">If you don't know your ITU Zone then <a href="http://www4.plala.or.jp/nomrax/ITU/" target="_blank">click here to find it!</a></small>
                </div>
            </div>

		  <div class="form-group">
		    <label for="stationGridsquareInput">Gridsquare</label>
		    <input type="text" class="form-control" name="gridsquare" id="stationGridsquareInput" aria-describedby="stationGridInputHelp" required>
		    <small id="stationGridInputHelp" class="form-text text-muted">Station grid square. For example: IO87IP</small>
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
		    <small id="stationSOTAInputHelp" class="form-text text-muted">Station SOTA reference.</small>
		  </div>

		  <div class="form-group">
		    <label for="stationSigInput">Signature</label>
		    <input type="text" class="form-control" name="sig" id="stationSigInput" aria-describedby="stationSigInputHelp">
		    <small id="stationSigInputHelp" class="form-text text-muted">Station Signature (e.g. WWFF).</small>
		  </div>

		  <div class="form-group">
		    <label for="stationSigInfoInput">Signature Info</label>
		    <input type="text" class="form-control" name="sig_info" id="stationSigInfoInput" aria-describedby="stationSigInfoInputHelp">
		    <small id="stationSigInfoInput" class="form-text text-muted">Station Signature Info (e.g. DLFF-0029).</small>
		  </div>

            <div class="form-group">
                <label for="eqslNickname">eQSL QTH Nickname</label>
                <input type="text" class="form-control" name="eqslnickname" id="eqslNickname" aria-describedby="eqslhelp">
                <small id="eqslhelp" class="form-text text-muted">eQSL QTH Nickname.</small>
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

			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Create Station Location</button>

		</form>
  </div>
</div>

<br>

</div>
