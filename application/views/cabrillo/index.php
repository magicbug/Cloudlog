<script>
	var lang_export_cabrillo_proceed = '<?php echo lang('export_cabrillo_proceed') ?>';
	var lang_export_cabrillo_select_year = "<?php echo lang('export_cabrillo_select_year') ?>";
	var lang_export_cabrillo_select_contest = '<?php echo lang ('export_cabrillo_select_contest') ?>';
	var lang_export_cabrillo_select_date_range = '<?php echo lang ('export_cabrillo_select_date_range') ?>'; 
	var lang_export_cabrillo_no_contests_for_stationlocation = '<?php echo lang('export_cabrillo_no_contests_for_stationlocation') ?>';
</script>
<div class="container">

    <br>

    <h2><?php echo $page_title; ?></h2>

    <div class="card">
        <div class="card-header">
			<?php echo lang('export_cabrillo_description'); ?>
        </div>
        <div class="card-body">

		<?php
		  echo '<div class="contests">';


		  if ($station_profile) { ?>

			<form class="form" action="<?php echo site_url('cabrillo/export'); ?>" method="post" enctype="multipart/form-data">
				<div class="mb-3 d-flex align-items-center row">
					<div class="col-md-3 control-label" for="station_id"><?php echo lang('export_cabrillo_select_station'); ?> </div>
					<select id="station_id" name="station_id" class="form-select my-1 me-sm-2 col-md-4 w-auto">
					<?php foreach ($station_profile->result() as $station) { ?>
						<option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_callsign') ?>: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
					<?php } ?>
					</select>
					<button id="button1id" type="button" onclick="loadYears();" name="button1id" class="btn btn-sm btn-primary w-auto"> <?php echo lang('export_cabrillo_proceed') ?></button>
				</div>

				<div class="mb-3 d-flex align-items-center row contestyear">
				</div>
				<div class="mb-3 d-flex align-items-center row contestname">
				</div>
				<div class="mb-3 d-flex align-items-center row contestdates">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="soapbox">Club: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="soapbox" type="soapbox" name="soapbox" aria-label="soapbox">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categoryoperator"><?php echo lang('export_cabrillo_cat_operator') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categoryoperator" name="categoryoperator">
						<option value="SINGLE-OP"><?php echo lang('export_cabrillo_cat_operator_single_op') ?></option>
						<option value="MULTI-OP"><?php echo lang('export_cabrillo_cat_operator_multi_op') ?></option>
						<option value="CHECKLOG"><?php echo lang('export_cabrillo_cat_operator_checklog') ?></option>
					</select>
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categoryassisted"><?php echo lang('export_cabrillo_cat_assisted') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categoryassisted" name="categoryassisted">
						<option value="NON-ASSISTED"><?php echo lang('export_cabrillo_cat_assisted_not_ass') ?></option>
						<option value="ASSISTED"><?php echo lang('export_cabrillo_cat_assisted_ass') ?></option>
					</select>
				</div>
					<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categoryband"><?php echo lang('export_cabrillo_cat_band') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categoryband" name="categoryband">
						<option value="ALL"><?php echo lang('general_word_all') ?></option>
						<option value="160M">160 M</option>
						<option value="80M">80 M</option>
						<option value="40M">40 M</option>
						<option value="20M">20 M</option>
						<option value="15M">15 M</option>
						<option value="10M">10 M</option>
						<option value="6M">6 M</option>
						<option value="4M">4 M</option>
						<option value="2M">2 M</option>
						<option value="222">222 MHz (1.25 M)</option>
						<option value="432">432 MHz (70 CM)</option>
						<option value="902">902 MHz (33 CM)</option>
						<option value="1.2G">1.2 GHz</option>
						<option value="2.3G">2.3 GHz</option>
						<option value="3.4G">3.4 GHz</option>
						<option value="5.7G">5.7 GHz</option>
						<option value="10G">10 GHz</option>
						<option value="24G">24 GHz</option>
						<option value="47G">47 GHz</option>
						<option value="75G">75 GHz</option>
						<option value="122G">122 GHz</option>
						<option value="134G">134 GHz</option>
						<option value="241G">241 GHz</option>
						<option value="Light"><?php echo lang('general_word_light') ?></option>
						<option value="VHF-3-BAND and VHF-FM-ONLY (ARRL VHF Contests only)"><?php echo lang('export_cabrillo_cat_band_arrl_vhf') ?></option>
					</select>
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categorymode"><?php echo lang('export_cabrillo_cat_mode') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categorymode" name="categorymode">
						<option value="MIXED">MIXED</option>
						<option value="CW">CW</option>
						<option value="DIGI">DIGI</option>
						<option value="FM">FM</option>
						<option value="RTTY">RTTY</option>
						<option value="SSB">SSB</option>
					</select>
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categorypower"><?php echo lang('export_cabrillo_cat_power') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categorypower" name="categorypower">
						<option value="LOW">LOW</option>
						<option value="HIGH">HIGH</option>
						<option value="QRP">QRP</option>
					</select>
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categorystation"><?php echo lang('export_cabrillo_cat_station') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categorystation" name="categorystation">
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
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categorytransmitter"><?php echo lang('export_cabrillo_cat_transmitter') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categorytransmitter" name="categorytransmitter">
						<option value="ONE">ONE</option>
						<option value="TWO">TWO</option>
						<option value="LIMITED">LIMITED</option>
						<option value="UNLIMITED">UNLIMITED</option>
						<option value="SWL">SWL</option>
					</select>
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="categoryoverlay"><?php echo lang('export_cabrillo_cat_overlay') ?>: </div>
					<select class="form-select my-1 me-sm-2 col-md-4 w-auto" id="categoryoverlay" name="categoryoverlay">
						<option value="CLASSIC">CLASSIC</option>
						<option value="ROOKIE">ROOKIE</option>
						<option value="TB-WIRES">TB-WIRES</option>
						<option value="YOUTH">YOUTH</option>
						<option value="NOVICE-TECH">NOVICE-TECH</option>
						<option value="YL">YL</option>
					</select>
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="operators"><?php echo lang('export_cabrillo_operators') ?>: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="operators" type="operators" name="operators" aria-label="operators">
					</select>
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="soapbox"><?php echo lang('export_cabrillo_soapbox') ?>: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="soapbox" type="text" name="soapbox" aria-label="soapbox">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="address"><?php echo lang('export_cabrillo_address') ?>: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="address" type="text" name="address" aria-label="address">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="addresscity"><?php echo lang('export_cabrillo_address_city') ?>: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="addresscity" type="text" name="addresscity" aria-label="addresscity">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="addressstateprovince"><?php echo lang('export_cabrillo_address_state_province') ?>: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="addressstateprovince" type="text" name="addressstateprovince" aria-label="addressstateprovince">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="addresspostalcode"><?php echo lang('export_cabrillo_address_postalcode') ?>: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="addresspostalcode" type="text" name="addresspostalcode" aria-label="addresspostalcode">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="addresscountry"><?php echo lang('export_cabrillo_address_country') ?>: </div>
					<input class="form-control my-1 me-sm-2 col-md-4 w-auto" id="addresscountry" type="text" name="addresscountry" aria-label="addresscountry">
				</div>
				<div hidden="true" class="mb-3 d-flex align-items-center row additionalinfo">
					<div class="col-md-3 control-label" for="button1id"></div>
					<button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary w-auto"> <?php echo lang('general_word_export') ?></button>
				</div>
			</form>

			<?php }
			else {
				echo lang('export_cabrillo_no_contests_in_log');
			}
			?>

        </div>
    </div>
</div>