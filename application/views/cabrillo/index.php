<div class="container">

    <br>

    <h2><?php echo $page_title; ?></h2>

    <div class="card">
        <div class="card-header">
            Export a contest to a Cabrillo log
        </div>
        <div class="card-body">

		<?php
		  echo '<div class="contests">';


		  if ($station_profile) { ?>

			<form class="form" action="<?php echo site_url('cabrillo/export'); ?>" method="post" enctype="multipart/form-data">
				<div class="form-group form-inline row">
					<div class="col-md-3 control-label" for="station_id">Select Station Location: </div>
					<select id="station_id" name="station_id" class="custom-select my-1 mr-sm-2 col-md-4">
					<?php foreach ($station_profile->result() as $station) { ?>
						<option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) { echo " selected =\"selected\""; } ?>>Callsign: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
					<?php } ?>
					</select>
					<button id="button1id" type="button" onclick="loadYears();" name="button1id" class="btn btn-sm btn-primary"> Proceed</button>
				</div>

				<div class="form-group form-inline row contestyear">
				</div>
				<div class="form-group form-inline row contestname">
				</div>
				<div class="form-group form-inline row contestdates">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="soapbox">Club: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="soapbox" type="soapbox" name="soapbox" aria-label="soapbox">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categoryoperator">Category-operator: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categoryoperator" name="categoryoperator">
						<option value="SINGLE-OP">Single-OP</option>
						<option value="MULTI-OP">Mulit-OP</option>
						<option value="CHECKLOG">Checklog</option>
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categoryassisted">Category-assisted: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categoryassisted" name="categoryassisted">
						<option value="NON-ASSISTED">Non-assisted</option>
						<option value="ASSISTED">Assisted</option>
					</select>
				</div>
					<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categoryband">Category-band: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categoryband" name="categoryband">
						<option value="ALL">ALL</option>
						<option value="160M">160M</option>
						<option value="80M">80M</option>
						<option value="40M">40M</option>
						<option value="20M">20M</option>
						<option value="15M">15M</option>
						<option value="10M">10M</option>
						<option value="6M">6M</option>
						<option value="4M">4M</option>
						<option value="2M">2M</option>
						<option value="222">222</option>
						<option value="432">432</option>
						<option value="902">902</option>
						<option value="1.2G">1.2G</option>
						<option value="2.3G">2.3G</option>
						<option value="3.4G">3.4G</option>
						<option value="5.7G">5.7G</option>
						<option value="10G">10G</option>
						<option value="24G">24G</option>
						<option value="47G">47G</option>
						<option value="75G">75G</option>
						<option value="122G">122G</option>
						<option value="134G">134G</option>
						<option value="241G">241G</option>
						<option value="Light">Light</option>
						<option value="VHF-3-BAND and VHF-FM-ONLY (ARRL VHF Contests only)">VHF-3-BAND and VHF-FM-ONLY (ARRL VHF Contests only)</option>
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categorymode">Category-mode: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categorymode" name="categorymode">
						<option value="MIXED">MIXED</option>
						<option value="CW">CW</option>
						<option value="DIGI">DIGI</option>
						<option value="FM">FM</option>
						<option value="RTTY">RTTY</option>
						<option value="SSB">SSB</option>
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categorypower">Category-power: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categorypower" name="categorypower">
						<option value="LOW">LOW</option>
						<option value="HIGH">HIGH</option>
						<option value="QRP">QRP</option>
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categorystation">Category-station: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categorystation" name="categorystation">
						<option value="FIXED">FIXED</option>
						<option value="DISTRIBUTED">DISTRIBUTED</option>
						<option value="MOBILE">MOBILE</option>
						<option value="PORTABLE">PORTABLE</option>
						<option value="ROVER">ROVER</option>
						<option value="ROVER-LIMITED">ROVER-LIMITED</option>
						<option value="ROVER-UNLIMITED">ROVER-UNLIMITED</option>
						<option value="EXPEDITION">EXPEDITION</option>
						<option value="HQ">HQ</option>
						<option value="SCHOOL">SCHOOL</option>
						<option value="EXPLORER">EXPLORER</option>
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categorytransmitter">Category-transmitter: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categorytransmitter" name="categorytransmitter">
						<option value="ONE">ONE</option>
						<option value="TWO">TWO</option>
						<option value="LIMITED">LIMITED</option>
						<option value="UNLIMITED">UNLIMITED</option>
						<option value="SWL">SWL</option>
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="categoryoverlay">Category-overlay: </div>
					<select class="custom-select my-1 mr-sm-2 col-md-4" id="categoryoverlay" name="categoryoverlay">
						<option value="CLASSIC">CLASSIC</option>
						<option value="ROOKIE">ROOKIE</option>
						<option value="TB-WIRES">TB-WIRES</option>
						<option value="YOUTH">YOUTH</option>
						<option value="NOVICE-TECH">NOVICE-TECH</option>
						<option value="YL">YL</option>
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="operators">Operators: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="operators" type="operators" name="operators" aria-label="operators">
					</select>
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="soapbox">Soapbox: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="soapbox" type="text" name="soapbox" aria-label="soapbox">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="address">Address: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="address" type="text" name="address" aria-label="address">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="addresscity">Address-city: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="addresscity" type="text" name="addresscity" aria-label="addresscity">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="addressstateprovince">Address-state-province: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="addressstateprovince" type="text" name="addressstateprovince" aria-label="addressstateprovince">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="addresspostalcode">Address-postalcode: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="addresspostalcode" type="text" name="addresspostalcode" aria-label="addresspostalcode">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="addresscountry">Address-country: </div>
					<input class="form-control my-1 mr-sm-2 col-md-4" id="addresscountry" type="text" name="addresscountry" aria-label="addresscountry">
				</div>
				<div hidden="true" class="form-group form-inline row additionalinfo">
					<div class="col-md-3 control-label" for="button1id"></div>
					<button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary"> Export</button>
				</div>
			</form>

			<?php }
			else {
				echo 'No contests were found in your log.';
			}
			?>

        </div>
    </div>
</div>