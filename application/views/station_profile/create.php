
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
		    <small id="stationNameInputHelp" class="form-text text-muted">Shortname for the station location for example Home (IO87IP)</small>
		  </div>

			<div class="form-group">
		    <label for="stationCallsignInput">Station Callsign</label>
		    <input type="text" class="form-control" name="station_callsign" id="stationCallsignInput" aria-describedby="stationCallsignInputHelp" placeholder="2M0SQL" required>
		    <small id="stationCallsignInputHelp" class="form-text text-muted">Station Callsign for example 2M0SQL/P</small>
		  </div>

		  <div class="form-group">
		    <label for="stationDXCCInput">Station DXCC</label>
				<?php if ($dxcc_list->num_rows() > 0) { ?>
				<select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
				<?php foreach ($dxcc_list->result() as $dxcc) { ?>
				<option value="<?php echo $dxcc->adif; ?>"><?php echo $dxcc->name; ?></option>
				<?php } ?>
				</select>
				<?php } ?>
				<input type="hidden" id="country" name="station_country" value="" required />
		    <small id="stationDXCCInputHelp" class="form-text text-muted">Station DXCC</small>
		  </div>

			<div class="form-group">
		    <label for="stationCityInput">Station City</label>
		    <input type="text" class="form-control" name="city" id="stationCityInput" aria-describedby="stationCityInputHelp" required>
		    <small id="stationCityInputHelp" class="form-text text-muted">Station City for example Inverness</small>
		  </div>

		  <div class="form-group">
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
		    <small id="StateHelp" class="form-text text-muted">Select Station State</small>
		  </div>

		  <div style="display: none" class="form-group">
		    <label for="stationCntyInput">Station Cnty</label>
		    <input type="text" class="form-control" name="station_cnty" id="stationCntyInput" aria-describedby="stationCntyInputHelp">
		    <small id="stationCntyInputHelp" class="form-text text-muted">Station Cnty #get def from ADIF Spec#</small>
		  </div>

		  <div class="form-group">
		    <label for="stationCQZoneInput">CQ Zone</label>
		    <input type="text" class="form-control" name="station_cq" id="stationCQZoneInput" aria-describedby="stationCQInputHelp" required>
		    <small id="stationCQInputHelp" class="form-text text-muted">If you do not know your CQ Zone <a href="http://www4.plala.or.jp/nomrax/CQ/" target="_blank">click Here to find it!</a></small>
		  </div>

		  <div class="form-group">
		    <label for="stationITUZoneInput">ITU Zone</label>
		    <input type="text" class="form-control" name="station_itu" id="stationITUZoneInput" aria-describedby="stationITUInputHelp" required>
		    <small id="stationITUInputHelp" class="form-text text-muted">If you do not know your ITU Zone <a href="http://www4.plala.or.jp/nomrax/ITU/" target="_blank">click Here to find it!</a></small>
		  </div>

		  <div class="form-group">
		    <label for="stationGridsquareInput">Gridsquare</label>
		    <input type="text" class="form-control" name="gridsquare" id="stationGridsquareInput" aria-describedby="stationGridInputHelp" required>
		    <small id="stationGridInputHelp" class="form-text text-muted">Station Gridsquare for example IO87IP, if you are at a gridline enter the gridsquare with a comma for example IO77,IO78,IO87,IO88.</small>
		  </div>

		  <div class="form-group">
		    <label for="stationIOTAInput">IOTA Reference</label>
		    <input type="text" class="form-control" name="iota" id="stationIOTAInput" aria-describedby="stationIOTAInputHelp" placeholder="EU-005">
		    <small id="stationIOTAInputHelp" class="form-text text-muted">Station IOTA Reference for example EU-005.</small>
		  </div>

		  <div class="form-group">
		    <label for="stationSOTAInput">SOTA Reference</label>
		    <input type="text" class="form-control" name="sota" id="stationSOTAInput" aria-describedby="stationSOTAInputHelp">
		    <small id="stationSOTAInputHelp" class="form-text text-muted">Station SOTA Reference.</small>
		  </div>

			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Create Station Profile</button>

		</form>
  </div>
</div>

<br>

</div>