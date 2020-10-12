
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
		    <label for="stateInput">Station State</label>
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
		    <small id="StateHelp" class="form-text text-muted">Select Station State</small>
		  </div>

		  <div style="display: none" class="form-group">
		    <label for="stationCntyInput">Station Cnty</label>
		    <input type="text" class="form-control" name="station_cnty" id="stationCntyInput" aria-describedby="stationCntyInputHelp" value="<?php if(set_value('station_cnty') != "") { echo set_value('station_cnty'); } else { echo $my_station_profile->station_cnty; } ?>">
		    <small id="stationCntyInputHelp" class="form-text text-muted">Station Cnty #get def from ADIF Spec#</small>
		  </div>

            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="stationCQZoneInput">CQ Zone</label>
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
                    <small id="stationCQInputHelp" class="form-text text-muted">If you do not know your CQ Zone <a href="http://www4.plala.or.jp/nomrax/CQ/" target="_blank">click Here to find it!</a></small>
                </div>

                <div class="form-group col-sm-6">
                    <label for="stationITUZoneInput">ITU Zone</label>
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
                    <small id="stationITUInputHelp" class="form-text text-muted">If you do not know your ITU Zone <a href="http://www4.plala.or.jp/nomrax/ITU/" target="_blank">click Here to find it!</a></small>
                </div>
            </div>

		  <div class="form-group">
		    <label for="stationGridsquareInput">Gridsquare</label>
		    <input type="text" class="form-control" name="gridsquare" id="stationGridsquareInput" aria-describedby="stationGridInputHelp" value="<?php if(set_value('gridsquare') != "") { echo set_value('gridsquare'); } else { echo $my_station_profile->station_gridsquare; } ?>" required>
		    <small id="stationGridInputHelp" class="form-text text-muted">Station Gridsquare for example IO87IP, if you are at a gridline enter the gridsquare with a comma for example IO77,IO78,IO87,IO88.</small>
		  </div>

            <div class="form-group">
                <label for="stationIOTAInput">IOTA Reference</label>
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
                <small id="stationIOTAInputHelp" class="form-text text-muted">Station IOTA Reference for example EU-005, You can lookup IOTA References at the <a target="_blank" href="https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html">IOTA World</a> website.</small>
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

            <div class="form-group">
                <label for="qrzApiKey">QRZ.com Logbook API Key</label>
                <input type="text" class="form-control" name="qrzapikey" id="qrzApiKey" aria-describedby="qrzApiKeyHelp" value="<?php if(set_value('qrzapikey') != "") { echo set_value('qrzapikey'); } else { echo $my_station_profile->qrzapikey; } ?>">
                <small id="qrzApiKeyHelp" class="form-text text-muted">Your QRZ.com Logbook API can be found in the <a href="https://logbook.qrz.com/logbook" target="_blank">settings page</a></small>
            </div>

			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Update Station Profile</button>

		</form>
  </div>
</div>

<br>

</div>