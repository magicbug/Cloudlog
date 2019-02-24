
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