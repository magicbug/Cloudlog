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

					<div class="mb-3">
						<label for="stationNameInput"><?php echo lang("station_location_name"); ?></label>
						<input type="text" class="form-control" name="station_profile_name" id="stationNameInput" aria-describedby="stationNameInputHelp" value="<?php if(set_value('station_profile_name') != "") { echo set_value('station_profile_name'); } else { echo $my_station_profile->station_profile_name; } ?>" required>
						<small id="stationNameInputHelp" class="form-text text-muted"><?php echo lang("station_location_name_hint"); ?></small>
					</div>

					<div class="mb-3">
						<label for="stationCallsignInput"><?php echo lang("station_location_callsign"); ?></label>
						<input type="text" class="form-control" name="station_callsign" id="stationCallsignInput" aria-describedby="stationCallsignInputHelp" value="<?php if(set_value('station_callsign') != "") { echo set_value('station_callsign'); } else { echo $my_station_profile->station_callsign; } ?>" required>
						<small id="stationCallsignInputHelp" class="form-text text-muted"><?php echo lang("station_location_callsign_hint"); ?></small>
					</div>

					<div class="mb-3">
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
					<div class="mb-3">
					    <label for="stationDXCCInput"><?php echo lang("station_location_dxcc"); ?></label>
					    <?php if ($dxcc_list->num_rows() > 0) { ?>
					        <select class="form-select" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
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
					<div class="mb-3">
						<label for="stationCityInput"><?php echo lang("station_location_city"); ?></label>
						<input type="text" class="form-control" name="city" id="stationCityInput" aria-describedby="stationCityInputHelp" value="<?php if(set_value('city') != "") { echo set_value('city'); } else { echo $my_station_profile->station_city; } ?>">
		    			<small id="stationCityInputHelp" class="form-text text-muted"><?php echo lang("station_location_city_hint"); ?></small>
		  			</div>

					<!-- US State -->
					<div class="mb-3" id="us_state">
		    			<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
		    				<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
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
					<div class="mb-3" id="canada_state">
		    			<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
		    				<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
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

						<div class="mb-3" id="aland_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="001" <?php if($my_station_profile->state == "001") { echo "selected"; } ?>>Brändö</option>
								<option value="002" <?php if($my_station_profile->state == "002") { echo "selected"; } ?>>Eckerö</option>
								<option value="003" <?php if($my_station_profile->state == "003") { echo "selected"; } ?>>Finström</option>
								<option value="004" <?php if($my_station_profile->state == "004") { echo "selected"; } ?>>Föglö</option>
								<option value="005" <?php if($my_station_profile->state == "005") { echo "selected"; } ?>>Geta</option>
								<option value="006" <?php if($my_station_profile->state == "006") { echo "selected"; } ?>>Hammarland</option>
								<option value="007" <?php if($my_station_profile->state == "007") { echo "selected"; } ?>>Jomala</option>
								<option value="008" <?php if($my_station_profile->state == "008") { echo "selected"; } ?>>Kumlinge</option>
								<option value="009" <?php if($my_station_profile->state == "009") { echo "selected"; } ?>>Kökar</option>
								<option value="010" <?php if($my_station_profile->state == "010") { echo "selected"; } ?>>Lemland</option>
								<option value="011" <?php if($my_station_profile->state == "011") { echo "selected"; } ?>>Lumparland</option>
								<option value="012" <?php if($my_station_profile->state == "012") { echo "selected"; } ?>>Maarianhamina</option>
								<option value="013" <?php if($my_station_profile->state == "013") { echo "selected"; } ?>>Saltvik</option>
								<option value="014" <?php if($my_station_profile->state == "014") { echo "selected"; } ?>>Sottunga</option>
								<option value="015" <?php if($my_station_profile->state == "015") { echo "selected"; } ?>>Sund</option>
								<option value="016" <?php if($my_station_profile->state == "016") { echo "selected"; } ?>>Vårdö</option>
								<option value="051" <?php if($my_station_profile->state == "051") { echo "selected"; } ?>>Märket (Deleted)</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="asiatic_russia_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="UO" <?php if($my_station_profile->state == "UO") { echo "selected"; } ?>>Ust’-Ordynsky Autonomous Okrug - for contacts made before 2008-01-01</option>
								<option value="AB" <?php if($my_station_profile->state == "AB") { echo "selected"; } ?>>Aginsky Buryatsky Autonomous Okrug - for contacts made before 2008-03-01</option>
								<option value="CB" <?php if($my_station_profile->state == "CB") { echo "selected"; } ?>>Chelyabinsk (Chelyabinskaya oblast)</option>
								<option value="SV" <?php if($my_station_profile->state == "SV") { echo "selected"; } ?>>Sverdlovskaya oblast</option>
								<option value="PM" <?php if($my_station_profile->state == "PM") { echo "selected"; } ?>>Perm` (Permskaya oblast) - for contacts made on or after 2005-12-01</option>
								<option value="PM" <?php if($my_station_profile->state == "PM") { echo "selected"; } ?>>Permskaya Kraj - for contacts made before 2005-12-01</option>
								<option value="KP" <?php if($my_station_profile->state == "KP") { echo "selected"; } ?>>Komi-Permyatsky Autonomous Okrug - for contacts made before 2005-12-01</option>
								<option value="TO" <?php if($my_station_profile->state == "TO") { echo "selected"; } ?>>Tomsk (Tomskaya oblast)</option>
								<option value="HM" <?php if($my_station_profile->state == "HM") { echo "selected"; } ?>>Khanty-Mansyisky Autonomous Okrug</option>
								<option value="YN" <?php if($my_station_profile->state == "YN") { echo "selected"; } ?>>Yamalo-Nenetsky Autonomous Okrug</option>
								<option value="TN" <?php if($my_station_profile->state == "TN") { echo "selected"; } ?>>Tyumen' (Tyumenskaya oblast)</option>
								<option value="OM" <?php if($my_station_profile->state == "OM") { echo "selected"; } ?>>Omsk (Omskaya oblast)</option>
								<option value="NS" <?php if($my_station_profile->state == "NS") { echo "selected"; } ?>>Novosibirsk (Novosibirskaya oblast)</option>
								<option value="KN" <?php if($my_station_profile->state == "KN") { echo "selected"; } ?>>Kurgan (Kurganskaya oblast)</option>
								<option value="OB" <?php if($my_station_profile->state == "OB") { echo "selected"; } ?>>Orenburg (Orenburgskaya oblast)</option>
								<option value="KE" <?php if($my_station_profile->state == "KE") { echo "selected"; } ?>>Kemerovo (Kemerovskaya oblast)</option>
								<option value="BA" <?php if($my_station_profile->state == "BA") { echo "selected"; } ?>>Republic of Bashkortostan</option>
								<option value="KO" <?php if($my_station_profile->state == "KO") { echo "selected"; } ?>>Republic of Komi</option>
								<option value="AL" <?php if($my_station_profile->state == "AL") { echo "selected"; } ?>>Altaysky Kraj</option>
								<option value="GA" <?php if($my_station_profile->state == "GA") { echo "selected"; } ?>>Republic Gorny Altay</option>
								<option value="KK" <?php if($my_station_profile->state == "KK") { echo "selected"; } ?>>Krasnoyarsk (Krasnoyarsk Kraj)</option>
								<option value="TM" <?php if($my_station_profile->state == "TM") { echo "selected"; } ?>>Taymyr Autonomous Okrug - for contacts made before 2007-01-01</option>
								<option value="HK" <?php if($my_station_profile->state == "HK") { echo "selected"; } ?>>Khabarovsk (Khabarovsky Kraj)</option>
								<option value="EA" <?php if($my_station_profile->state == "EA") { echo "selected"; } ?>>Yevreyskaya Autonomous Oblast</option>
								<option value="SL" <?php if($my_station_profile->state == "SL") { echo "selected"; } ?>>Sakhalin (Sakhalinskaya oblast)</option>
								<option value="EV" <?php if($my_station_profile->state == "EV") { echo "selected"; } ?>>Evenkiysky Autonomous Okrug - for contacts made before 2007-01-01</option>
								<option value="MG" <?php if($my_station_profile->state == "MG") { echo "selected"; } ?>>Magadan (Magadanskaya oblast)</option>
								<option value="AM" <?php if($my_station_profile->state == "AM") { echo "selected"; } ?>>Amurskaya oblast</option>
								<option value="CK" <?php if($my_station_profile->state == "CK") { echo "selected"; } ?>>Chukotka Autonomous Okrug</option>
								<option value="PK" <?php if($my_station_profile->state == "PK") { echo "selected"; } ?>>Primorsky Kraj</option>
								<option value="BU" <?php if($my_station_profile->state == "BU") { echo "selected"; } ?>>Republic of Buryatia</option>
								<option value="YA" <?php if($my_station_profile->state == "YA") { echo "selected"; } ?>>Sakha (Yakut) Republic</option>
								<option value="IR" <?php if($my_station_profile->state == "IR") { echo "selected"; } ?>>Irkutsk (Irkutskaya oblast)</option>
								<option value="CT" <?php if($my_station_profile->state == "CT") { echo "selected"; } ?>>Zabaykalsky Kraj - referred to as Chita (Chitinskaya oblast) before 2008-03-01</option>
								<option value="HA" <?php if($my_station_profile->state == "HA") { echo "selected"; } ?>>Republic of Khakassia</option>
								<option value="KY" <?php if($my_station_profile->state == "KY") { echo "selected"; } ?>>Koryaksky Autonomous Okrug - for contacts made before 2007-01-01</option>
								<option value="TU" <?php if($my_station_profile->state == "TU") { echo "selected"; } ?>>Republic of Tuva</option>
								<option value="KT" <?php if($my_station_profile->state == "KT") { echo "selected"; } ?>>Kamchatka (Kamchatskaya oblast)</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="belarus_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="MI" <?php if($my_station_profile->state == "MI") { echo "selected"; } ?>>Minsk (Minskaya voblasts')</option>
								<option value="BR" <?php if($my_station_profile->state == "BR") { echo "selected"; } ?>>Brest (Brestskaya voblasts')</option>
								<option value="HR" <?php if($my_station_profile->state == "HR") { echo "selected"; } ?>>Grodno (Hrodzenskaya voblasts')</option>
								<option value="VI" <?php if($my_station_profile->state == "VI") { echo "selected"; } ?>>Vitebsk (Vitsyebskaya voblasts')</option>
								<option value="MA" <?php if($my_station_profile->state == "MA") { echo "selected"; } ?>>Mogilev (Mahilyowskaya voblasts')</option>
								<option value="HO" <?php if($my_station_profile->state == "HO") { echo "selected"; } ?>>Gomel (Homyel'skaya voblasts')</option>
								<option value="HM" <?php if($my_station_profile->state == "HM") { echo "selected"; } ?>>Horad Minsk</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="mexico_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="COL" <?php if($my_station_profile->state == "COL") { echo "selected"; } ?>>Colima</option>
								<option value="DF" <?php if($my_station_profile->state == "DF") { echo "selected"; } ?>>Distrito Federal</option>
								<option value="EMX" <?php if($my_station_profile->state == "EMX") { echo "selected"; } ?>>Estado de México</option>
								<option value="GTO" <?php if($my_station_profile->state == "GTO") { echo "selected"; } ?>>Guanajuato</option>
								<option value="HGO" <?php if($my_station_profile->state == "HGO") { echo "selected"; } ?>>Hidalgo</option>
								<option value="JAL" <?php if($my_station_profile->state == "JAL") { echo "selected"; } ?>>Jalisco</option>
								<option value="MIC" <?php if($my_station_profile->state == "MIC") { echo "selected"; } ?>>Michoacán de Ocampo</option>
								<option value="MOR" <?php if($my_station_profile->state == "MOR") { echo "selected"; } ?>>Morelos</option>
								<option value="NAY" <?php if($my_station_profile->state == "NAY") { echo "selected"; } ?>>Nayarit</option>
								<option value="PUE" <?php if($my_station_profile->state == "PUE") { echo "selected"; } ?>>Puebla</option>
								<option value="QRO" <?php if($my_station_profile->state == "QRO") { echo "selected"; } ?>>Querétaro de Arteaga</option>
								<option value="TLX" <?php if($my_station_profile->state == "TLX") { echo "selected"; } ?>>Tlaxcala</option>
								<option value="VER" <?php if($my_station_profile->state == "VER") { echo "selected"; } ?>>Veracruz-Llave</option>
								<option value="AGS" <?php if($my_station_profile->state == "AGS") { echo "selected"; } ?>>Aguascalientes</option>
								<option value="BC" <?php if($my_station_profile->state == "BC") { echo "selected"; } ?>>Baja California</option>
								<option value="BCS" <?php if($my_station_profile->state == "BCS") { echo "selected"; } ?>>Baja California Sur</option>
								<option value="CHH" <?php if($my_station_profile->state == "CHH") { echo "selected"; } ?>>Chihuahua</option>
								<option value="COA" <?php if($my_station_profile->state == "COA") { echo "selected"; } ?>>Coahuila de Zaragoza</option>
								<option value="DGO" <?php if($my_station_profile->state == "DGO") { echo "selected"; } ?>>Durango</option>
								<option value="NL" <?php if($my_station_profile->state == "NL") { echo "selected"; } ?>>Nuevo Leon</option>
								<option value="SLP" <?php if($my_station_profile->state == "SLP") { echo "selected"; } ?>>San Luis Potosí</option>
								<option value="SIN" <?php if($my_station_profile->state == "SIN") { echo "selected"; } ?>>Sinaloa</option>
								<option value="SON" <?php if($my_station_profile->state == "SON") { echo "selected"; } ?>>Sonora</option>
								<option value="TMS" <?php if($my_station_profile->state == "TMS") { echo "selected"; } ?>>Tamaulipas</option>
								<option value="ZAC" <?php if($my_station_profile->state == "ZAC") { echo "selected"; } ?>>Zacatecas</option>
								<option value="CAM" <?php if($my_station_profile->state == "CAM") { echo "selected"; } ?>>Campeche</option>
								<option value="CHS" <?php if($my_station_profile->state == "CHS") { echo "selected"; } ?>>Chiapas</option>
								<option value="GRO" <?php if($my_station_profile->state == "GRO") { echo "selected"; } ?>>Guerrero</option>
								<option value="OAX" <?php if($my_station_profile->state == "OAX") { echo "selected"; } ?>>Oaxaca</option>
								<option value="QTR" <?php if($my_station_profile->state == "QTR") { echo "selected"; } ?>>Quintana Roo</option>
								<option value="TAB" <?php if($my_station_profile->state == "TAB") { echo "selected"; } ?>>Tabasco</option>
								<option value="YUC" <?php if($my_station_profile->state == "YUC") { echo "selected"; } ?>>Yucatán</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="eu_russia_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="SP" <?php if($my_station_profile->state == "SP") { echo "selected"; } ?>>City of St. Petersburg</option>
								<option value="LO" <?php if($my_station_profile->state == "LO") { echo "selected"; } ?>>Leningradskaya oblast</option>
								<option value="KL" <?php if($my_station_profile->state == "KL") { echo "selected"; } ?>>Republic of Karelia</option>
								<option value="AR" <?php if($my_station_profile->state == "AR") { echo "selected"; } ?>>Arkhangelsk (Arkhangelskaya oblast)</option>
								<option value="NO" <?php if($my_station_profile->state == "NO") { echo "selected"; } ?>>Nenetsky Autonomous Okrug</option>
								<option value="VO" <?php if($my_station_profile->state == "VO") { echo "selected"; } ?>>Vologda (Vologodskaya oblast)</option>
								<option value="NV" <?php if($my_station_profile->state == "NV") { echo "selected"; } ?>>Novgorodskaya oblast</option>
								<option value="PS" <?php if($my_station_profile->state == "PS") { echo "selected"; } ?>>Pskov (Pskovskaya oblast)</option>
								<option value="MU" <?php if($my_station_profile->state == "MU") { echo "selected"; } ?>>Murmansk (Murmanskaya oblast)</option>
								<option value="MA" <?php if($my_station_profile->state == "MA") { echo "selected"; } ?>>City of Moscow</option>
								<option value="MO" <?php if($my_station_profile->state == "MO") { echo "selected"; } ?>>Moscowskaya oblast</option>
								<option value="OR" <?php if($my_station_profile->state == "OR") { echo "selected"; } ?>>Oryel (Orlovskaya oblast)</option>
								<option value="LP" <?php if($my_station_profile->state == "LP") { echo "selected"; } ?>>Lipetsk (Lipetskaya oblast)</option>
								<option value="TV" <?php if($my_station_profile->state == "TV") { echo "selected"; } ?>>Tver' (Tverskaya oblast)</option>
								<option value="SM" <?php if($my_station_profile->state == "SM") { echo "selected"; } ?>>Smolensk (Smolenskaya oblast)</option>
								<option value="YR" <?php if($my_station_profile->state == "YR") { echo "selected"; } ?>>Yaroslavl (Yaroslavskaya oblast)</option>
								<option value="KS" <?php if($my_station_profile->state == "KS") { echo "selected"; } ?>>Kostroma (Kostromskaya oblast)</option>
								<option value="TL" <?php if($my_station_profile->state == "TL") { echo "selected"; } ?>>Tula (Tul'skaya oblast)</option>
								<option value="VR" <?php if($my_station_profile->state == "VR") { echo "selected"; } ?>>Voronezh (Voronezhskaya oblast)</option>
								<option value="TB" <?php if($my_station_profile->state == "TB") { echo "selected"; } ?>>Tambov (Tambovskaya oblast)</option>
								<option value="RA" <?php if($my_station_profile->state == "RA") { echo "selected"; } ?>>Ryazan' (Ryazanskaya oblast)</option>
								<option value="NN" <?php if($my_station_profile->state == "NN") { echo "selected"; } ?>>Nizhni Novgorod (Nizhegorodskaya oblast)</option>
								<option value="IV" <?php if($my_station_profile->state == "IV") { echo "selected"; } ?>>Ivanovo (Ivanovskaya oblast)</option>
								<option value="VL" <?php if($my_station_profile->state == "VL") { echo "selected"; } ?>>Vladimir (Vladimirskaya oblast)</option>
								<option value="KU" <?php if($my_station_profile->state == "KU") { echo "selected"; } ?>>Kursk (Kurskaya oblast)</option>
								<option value="KG" <?php if($my_station_profile->state == "KG") { echo "selected"; } ?>>Kaluga (Kaluzhskaya oblast)</option>
								<option value="BR" <?php if($my_station_profile->state == "BR") { echo "selected"; } ?>>Bryansk (Bryanskaya oblast)</option>
								<option value="BO" <?php if($my_station_profile->state == "BO") { echo "selected"; } ?>>Belgorod (Belgorodskaya oblast)</option>
								<option value="VG" <?php if($my_station_profile->state == "VG") { echo "selected"; } ?>>Volgograd (Volgogradskaya oblast)</option>
								<option value="SA" <?php if($my_station_profile->state == "SA") { echo "selected"; } ?>>Saratov (Saratovskaya oblast)</option>
								<option value="PE" <?php if($my_station_profile->state == "PE") { echo "selected"; } ?>>Penza (Penzenskaya oblast)</option>
								<option value="SR" <?php if($my_station_profile->state == "SR") { echo "selected"; } ?>>Samara (Samarskaya oblast)</option>
								<option value="UL" <?php if($my_station_profile->state == "UL") { echo "selected"; } ?>>Ulyanovsk (Ulyanovskaya oblast)</option>
								<option value="KI" <?php if($my_station_profile->state == "KI") { echo "selected"; } ?>>Kirov (Kirovskaya oblast)</option>
								<option value="TA" <?php if($my_station_profile->state == "TA") { echo "selected"; } ?>>Republic of Tataria</option>
								<option value="MR" <?php if($my_station_profile->state == "MR") { echo "selected"; } ?>>Republic of Marij-El</option>
								<option value="MD" <?php if($my_station_profile->state == "MD") { echo "selected"; } ?>>Republic of Mordovia</option>
								<option value="UD" <?php if($my_station_profile->state == "UD") { echo "selected"; } ?>>Republic of Udmurtia</option>
								<option value="CU" <?php if($my_station_profile->state == "CU") { echo "selected"; } ?>>Republic of Chuvashia</option>
								<option value="KR" <?php if($my_station_profile->state == "KR") { echo "selected"; } ?>>Krasnodar (Krasnodarsky Kraj)</option>
								<option value="KC" <?php if($my_station_profile->state == "KC") { echo "selected"; } ?>>Republic of Karachaevo-Cherkessia</option>
								<option value="ST" <?php if($my_station_profile->state == "ST") { echo "selected"; } ?>>Stavropol' (Stavropolsky Kraj)</option>
								<option value="KM" <?php if($my_station_profile->state == "KM") { echo "selected"; } ?>>Republic of Kalmykia</option>
								<option value="SO" <?php if($my_station_profile->state == "SO") { echo "selected"; } ?>>Republic of Northern Ossetia</option>
								<option value="RO" <?php if($my_station_profile->state == "RO") { echo "selected"; } ?>>Rostov-on-Don (Rostovskaya oblast)</option>
								<option value="CN" <?php if($my_station_profile->state == "CN") { echo "selected"; } ?>>Republic Chechnya</option>
								<option value="IN" <?php if($my_station_profile->state == "IN") { echo "selected"; } ?>>Republic of Ingushetia</option>
								<option value="AO" <?php if($my_station_profile->state == "AO") { echo "selected"; } ?>>Astrakhan' (Astrakhanskaya oblast)</option>
								<option value="DA" <?php if($my_station_profile->state == "DA") { echo "selected"; } ?>>Republic of Daghestan</option>
								<option value="KB" <?php if($my_station_profile->state == "KB") { echo "selected"; } ?>>Republic of Kabardino-Balkaria</option>
								<option value="AD" <?php if($my_station_profile->state == "AD") { echo "selected"; } ?>>Republic of Adygeya</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="argentina_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="C" <?php if($my_station_profile->state == "C") { echo "selected"; } ?>>Capital federal (Buenos Aires City)</option>
								<option value="B" <?php if($my_station_profile->state == "B") { echo "selected"; } ?>>Buenos Aires Province</option>
								<option value="S" <?php if($my_station_profile->state == "S") { echo "selected"; } ?>>Santa Fe</option>
								<option value="H" <?php if($my_station_profile->state == "H") { echo "selected"; } ?>>Chaco</option>
								<option value="P" <?php if($my_station_profile->state == "P") { echo "selected"; } ?>>Formosa</option>
								<option value="X" <?php if($my_station_profile->state == "X") { echo "selected"; } ?>>Cordoba</option>
								<option value="N" <?php if($my_station_profile->state == "N") { echo "selected"; } ?>>Misiones</option>
								<option value="E" <?php if($my_station_profile->state == "E") { echo "selected"; } ?>>Entre Rios</option>
								<option value="T" <?php if($my_station_profile->state == "T") { echo "selected"; } ?>>Tucumán</option>
								<option value="W" <?php if($my_station_profile->state == "W") { echo "selected"; } ?>>Corrientes</option>
								<option value="M" <?php if($my_station_profile->state == "M") { echo "selected"; } ?>>Mendoza</option>
								<option value="G" <?php if($my_station_profile->state == "G") { echo "selected"; } ?>>Santiago del Estero</option>
								<option value="A" <?php if($my_station_profile->state == "A") { echo "selected"; } ?>>Salta</option>
								<option value="J" <?php if($my_station_profile->state == "J") { echo "selected"; } ?>>San Juan</option>
								<option value="D" <?php if($my_station_profile->state == "D") { echo "selected"; } ?>>San Luis</option>
								<option value="K" <?php if($my_station_profile->state == "K") { echo "selected"; } ?>>Catamarca</option>
								<option value="F" <?php if($my_station_profile->state == "F") { echo "selected"; } ?>>La Rioja</option>
								<option value="Y" <?php if($my_station_profile->state == "Y") { echo "selected"; } ?>>Jujuy</option>
								<option value="L" <?php if($my_station_profile->state == "L") { echo "selected"; } ?>>La Pampa</option>
								<option value="R" <?php if($my_station_profile->state == "R") { echo "selected"; } ?>>Rió Negro</option>
								<option value="U" <?php if($my_station_profile->state == "U") { echo "selected"; } ?>>Chubut</option>
								<option value="Z" <?php if($my_station_profile->state == "Z") { echo "selected"; } ?>>Santa Cruz</option>
								<option value="V" <?php if($my_station_profile->state == "V") { echo "selected"; } ?>>Tierra del Fuego</option>
								<option value="Q" <?php if($my_station_profile->state == "Q") { echo "selected"; } ?>>Neuquén</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="brazil_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="ES" <?php if($my_station_profile->state == "ES") { echo "selected"; } ?>>Espírito Santo</option>
								<option value="GO" <?php if($my_station_profile->state == "GO") { echo "selected"; } ?>>Goiás</option>
								<option value="SC" <?php if($my_station_profile->state == "SC") { echo "selected"; } ?>>Santa Catarina</option>
								<option value="SE" <?php if($my_station_profile->state == "SE") { echo "selected"; } ?>>Sergipe</option>
								<option value="AL" <?php if($my_station_profile->state == "AL") { echo "selected"; } ?>>Alagoas</option>
								<option value="AM" <?php if($my_station_profile->state == "AM") { echo "selected"; } ?>>Amazonas</option>
								<option value="TO" <?php if($my_station_profile->state == "TO") { echo "selected"; } ?>>Tocantins</option>
								<option value="AP" <?php if($my_station_profile->state == "AP") { echo "selected"; } ?>>Amapá</option>
								<option value="PB" <?php if($my_station_profile->state == "PB") { echo "selected"; } ?>>Paraíba</option>
								<option value="MA" <?php if($my_station_profile->state == "MA") { echo "selected"; } ?>>Maranhão</option>
								<option value="RN" <?php if($my_station_profile->state == "RN") { echo "selected"; } ?>>Rio Grande do Norte</option>
								<option value="PI" <?php if($my_station_profile->state == "PI") { echo "selected"; } ?>>Piauí</option>
								<option value="DF" <?php if($my_station_profile->state == "DF") { echo "selected"; } ?>>Distrito Federal (Brasília)</option>
								<option value="CE" <?php if($my_station_profile->state == "CE") { echo "selected"; } ?>>Ceará</option>
								<option value="AC" <?php if($my_station_profile->state == "AC") { echo "selected"; } ?>>Acre</option>
								<option value="MS" <?php if($my_station_profile->state == "MS") { echo "selected"; } ?>>Mato Grosso do Sul</option>
								<option value="RR" <?php if($my_station_profile->state == "RR") { echo "selected"; } ?>>Roraima</option>
								<option value="RO" <?php if($my_station_profile->state == "RO") { echo "selected"; } ?>>Rondônia</option>
								<option value="RJ" <?php if($my_station_profile->state == "RJ") { echo "selected"; } ?>>Rio de Janeiro</option>
								<option value="SP" <?php if($my_station_profile->state == "SP") { echo "selected"; } ?>>São Paulo</option>
								<option value="RS" <?php if($my_station_profile->state == "RS") { echo "selected"; } ?>>Rio Grande do Sul</option>
								<option value="MG" <?php if($my_station_profile->state == "MG") { echo "selected"; } ?>>Minas Gerais</option>
								<option value="PR" <?php if($my_station_profile->state == "PR") { echo "selected"; } ?>>Paraná</option>
								<option value="BA" <?php if($my_station_profile->state == "BA") { echo "selected"; } ?>>Bahia</option>
								<option value="PE" <?php if($my_station_profile->state == "PE") { echo "selected"; } ?>>Pernambuco</option>
								<option value="PA" <?php if($my_station_profile->state == "PA") { echo "selected"; } ?>>Pará</option>
								<option value="MT" <?php if($my_station_profile->state == "MT") { echo "selected"; } ?>>Mato Grosso</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="chile_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="II" <?php if($my_station_profile->state == "II") { echo "selected"; } ?>>Antofagasta</option>
								<option value="III" <?php if($my_station_profile->state == "III") { echo "selected"; } ?>>Atacama</option>
								<option value="I" <?php if($my_station_profile->state == "I") { echo "selected"; } ?>>Tarapacá</option>
								<option value="XV" <?php if($my_station_profile->state == "XV") { echo "selected"; } ?>>Arica y Parinacota</option>
								<option value="IV" <?php if($my_station_profile->state == "IV") { echo "selected"; } ?>>Coquimbo</option>
								<option value="V" <?php if($my_station_profile->state == "V") { echo "selected"; } ?>>Valparaíso</option>
								<option value="RM" <?php if($my_station_profile->state == "RM") { echo "selected"; } ?>>Región Metropolitana de Santiago</option>
								<option value="VI" <?php if($my_station_profile->state == "VI") { echo "selected"; } ?>>Libertador General Bernardo O'Higgins</option>
								<option value="VII" <?php if($my_station_profile->state == "VII") { echo "selected"; } ?>>Maule</option>
								<option value="VIII" <?php if($my_station_profile->state == "VIII") { echo "selected"; } ?>>Bío-Bío</option>
								<option value="IX" <?php if($my_station_profile->state == "IX") { echo "selected"; } ?>>La Araucanía</option>
								<option value="XIV" <?php if($my_station_profile->state == "XIV") { echo "selected"; } ?>>Los Ríos</option>
								<option value="X" <?php if($my_station_profile->state == "X") { echo "selected"; } ?>>Los Lagos</option>
								<option value="XI" <?php if($my_station_profile->state == "XI") { echo "selected"; } ?>>Aisén del General Carlos Ibáñez del Campo</option>
								<option value="XII" <?php if($my_station_profile->state == "XII") { echo "selected"; } ?>>Magallanes</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="paraguay_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="16" <?php if($my_station_profile->state == "16") { echo "selected"; } ?>>Alto Paraguay</option>
								<option value="19" <?php if($my_station_profile->state == "19") { echo "selected"; } ?>>Boquerón</option>
								<option value="15" <?php if($my_station_profile->state == "15") { echo "selected"; } ?>>Presidente Hayes</option>
								<option value="13" <?php if($my_station_profile->state == "13") { echo "selected"; } ?>>Amambay</option>
								<option value="01" <?php if($my_station_profile->state == "01") { echo "selected"; } ?>>Concepción</option>
								<option value="14" <?php if($my_station_profile->state == "14") { echo "selected"; } ?>>Canindeyú</option>
								<option value="02" <?php if($my_station_profile->state == "02") { echo "selected"; } ?>>San Pedro</option>
								<option value="ASU" <?php if($my_station_profile->state == "ASU") { echo "selected"; } ?>>Asunción</option>
								<option value="11" <?php if($my_station_profile->state == "11") { echo "selected"; } ?>>Central</option>
								<option value="03" <?php if($my_station_profile->state == "03") { echo "selected"; } ?>>Cordillera</option>
								<option value="09" <?php if($my_station_profile->state == "09") { echo "selected"; } ?>>Paraguarí</option>
								<option value="06" <?php if($my_station_profile->state == "06") { echo "selected"; } ?>>Caazapá</option>
								<option value="05" <?php if($my_station_profile->state == "05") { echo "selected"; } ?>>Caaguazú</option>
								<option value="04" <?php if($my_station_profile->state == "04") { echo "selected"; } ?>>Guairá</option>
								<option value="08" <?php if($my_station_profile->state == "08") { echo "selected"; } ?>>Misiones</option>
								<option value="12" <?php if($my_station_profile->state == "12") { echo "selected"; } ?>>Ñeembucú</option>
								<option value="10" <?php if($my_station_profile->state == "10") { echo "selected"; } ?>>Alto Paraná</option>
								<option value="07" <?php if($my_station_profile->state == "07") { echo "selected"; } ?>>Itapúa</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="korea_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="A" <?php if($my_station_profile->state == "A") { echo "selected"; } ?>>Seoul (Seoul Teugbyeolsi)</option>
								<option value="N" <?php if($my_station_profile->state == "N") { echo "selected"; } ?>>Incheon (Incheon Gwang'yeogsi)</option>
								<option value="D" <?php if($my_station_profile->state == "D") { echo "selected"; } ?>>Kangwon-do (Gang 'weondo)</option>
								<option value="C" <?php if($my_station_profile->state == "C") { echo "selected"; } ?>>Kyunggi-do (Gyeonggido)</option>
								<option value="E" <?php if($my_station_profile->state == "E") { echo "selected"; } ?>>Choongchungbuk-do (Chungcheongbugdo)</option>
								<option value="F" <?php if($my_station_profile->state == "F") { echo "selected"; } ?>>Choongchungnam-do (Chungcheongnamdo)</option>
								<option value="R" <?php if($my_station_profile->state == "R") { echo "selected"; } ?>>Taejon (Daejeon Gwang'yeogsi)</option>
								<option value="M" <?php if($my_station_profile->state == "M") { echo "selected"; } ?>>Cheju-do (Jejudo)</option>
								<option value="G" <?php if($my_station_profile->state == "G") { echo "selected"; } ?>>Chollabuk-do (Jeonrabugdo)</option>
								<option value="H" <?php if($my_station_profile->state == "H") { echo "selected"; } ?>>Chollanam-do (Jeonranamdo)</option>
								<option value="Q" <?php if($my_station_profile->state == "Q") { echo "selected"; } ?>>Kwangju (Gwangju Gwang'yeogsi)</option>
								<option value="K" <?php if($my_station_profile->state == "K") { echo "selected"; } ?>>Kyungsangbuk-do (Gyeongsangbugdo)</option>
								<option value="L" <?php if($my_station_profile->state == "L") { echo "selected"; } ?>>Kyungsangnam-do (Gyeongsangnamdo)</option>
								<option value="B" <?php if($my_station_profile->state == "B") { echo "selected"; } ?>>Pusan (Busan Gwang'yeogsi)</option>
								<option value="P" <?php if($my_station_profile->state == "P") { echo "selected"; } ?>>Taegu (Daegu Gwang'yeogsi)</option>
								<option value="S" <?php if($my_station_profile->state == "S") { echo "selected"; } ?>>Ulsan (Ulsan Gwanq'yeogsi)</option>
								<option value="T" <?php if($my_station_profile->state == "T") { echo "selected"; } ?>>Sejong</option>
								<option value="IS" <?php if($my_station_profile->state == "IS") { echo "selected"; } ?>>Special Island</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="uruguay_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="MO" <?php if($my_station_profile->state == "MO") { echo "selected"; } ?>>Montevideo</option>
								<option value="CA" <?php if($my_station_profile->state == "CA") { echo "selected"; } ?>>Canelones</option>
								<option value="SJ" <?php if($my_station_profile->state == "SJ") { echo "selected"; } ?>>San José</option>
								<option value="CO" <?php if($my_station_profile->state == "CO") { echo "selected"; } ?>>Colonia</option>
								<option value="SO" <?php if($my_station_profile->state == "SO") { echo "selected"; } ?>>Soriano</option>
								<option value="RN" <?php if($my_station_profile->state == "RN") { echo "selected"; } ?>>Rio Negro</option>
								<option value="PA" <?php if($my_station_profile->state == "PA") { echo "selected"; } ?>>Paysandu</option>
								<option value="SA" <?php if($my_station_profile->state == "SA") { echo "selected"; } ?>>Salto</option>
								<option value="AR" <?php if($my_station_profile->state == "AR") { echo "selected"; } ?>>Artigas</option>
								<option value="FD" <?php if($my_station_profile->state == "FD") { echo "selected"; } ?>>Florida</option>
								<option value="FS" <?php if($my_station_profile->state == "FS") { echo "selected"; } ?>>Flores</option>
								<option value="DU" <?php if($my_station_profile->state == "DU") { echo "selected"; } ?>>Durazno</option>
								<option value="TA" <?php if($my_station_profile->state == "TA") { echo "selected"; } ?>>Tacuarembó</option>
								<option value="RV" <?php if($my_station_profile->state == "RV") { echo "selected"; } ?>>Rivera</option>
								<option value="MA" <?php if($my_station_profile->state == "MA") { echo "selected"; } ?>>Maldonado</option>
								<option value="LA" <?php if($my_station_profile->state == "LA") { echo "selected"; } ?>>Lavalleja</option>
								<option value="RO" <?php if($my_station_profile->state == "RO") { echo "selected"; } ?>>Rocha</option>
								<option value="TT" <?php if($my_station_profile->state == "TT") { echo "selected"; } ?>>Treinta y Tres</option>
								<option value="CL" <?php if($my_station_profile->state == "CL") { echo "selected"; } ?>>Cerro Largo</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="venezuela_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="AM" <?php if($my_station_profile->state == "AM") { echo "selected"; } ?>>Amazonas</option>
								<option value="AN" <?php if($my_station_profile->state == "AN") { echo "selected"; } ?>>Anzoátegui</option>
								<option value="AP" <?php if($my_station_profile->state == "AP") { echo "selected"; } ?>>Apure</option>
								<option value="AR" <?php if($my_station_profile->state == "AR") { echo "selected"; } ?>>Aragua</option>
								<option value="BA" <?php if($my_station_profile->state == "BA") { echo "selected"; } ?>>Barinas</option>
								<option value="BO" <?php if($my_station_profile->state == "BO") { echo "selected"; } ?>>Bolívar</option>
								<option value="CA" <?php if($my_station_profile->state == "CA") { echo "selected"; } ?>>Carabobo</option>
								<option value="CO" <?php if($my_station_profile->state == "CO") { echo "selected"; } ?>>Cojedes</option>
								<option value="DA" <?php if($my_station_profile->state == "DA") { echo "selected"; } ?>>Delta Amacuro</option>
								<option value="DC" <?php if($my_station_profile->state == "DC") { echo "selected"; } ?>>Distrito Capital</option>
								<option value="FA" <?php if($my_station_profile->state == "FA") { echo "selected"; } ?>>Falcón</option>
								<option value="GU" <?php if($my_station_profile->state == "GU") { echo "selected"; } ?>>Guárico</option>
								<option value="LA" <?php if($my_station_profile->state == "LA") { echo "selected"; } ?>>Lara</option>
								<option value="ME" <?php if($my_station_profile->state == "ME") { echo "selected"; } ?>>Mérida</option>
								<option value="MI" <?php if($my_station_profile->state == "MI") { echo "selected"; } ?>>Miranda</option>
								<option value="MO" <?php if($my_station_profile->state == "MO") { echo "selected"; } ?>>Monagas</option>
								<option value="NE" <?php if($my_station_profile->state == "NE") { echo "selected"; } ?>>Nueva Esparta</option>
								<option value="PO" <?php if($my_station_profile->state == "PO") { echo "selected"; } ?>>Portuguesa</option>
								<option value="SU" <?php if($my_station_profile->state == "SU") { echo "selected"; } ?>>Sucre</option>
								<option value="TA" <?php if($my_station_profile->state == "TA") { echo "selected"; } ?>>Táchira</option>
								<option value="TR" <?php if($my_station_profile->state == "TR") { echo "selected"; } ?>>Trujillo</option>
								<option value="VA" <?php if($my_station_profile->state == "VA") { echo "selected"; } ?>>Vargas</option>
								<option value="YA" <?php if($my_station_profile->state == "YA") { echo "selected"; } ?>>Yaracuy</option>
								<option value="ZU" <?php if($my_station_profile->state == "ZU") { echo "selected"; } ?>>Zulia</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="australia_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="ACT" <?php if($my_station_profile->state == "ACT") { echo "selected"; } ?>>Australian Capital Territory</option>
								<option value="NSW" <?php if($my_station_profile->state == "NSW") { echo "selected"; } ?>>New South Wales</option>
								<option value="VIC" <?php if($my_station_profile->state == "VIC") { echo "selected"; } ?>>Victoria</option>
								<option value="QLD" <?php if($my_station_profile->state == "QLD") { echo "selected"; } ?>>Queensland</option>
								<option value="SA" <?php if($my_station_profile->state == "SA") { echo "selected"; } ?>>South Australia</option>
								<option value="WA" <?php if($my_station_profile->state == "WA") { echo "selected"; } ?>>Western Australia</option>
								<option value="TAS" <?php if($my_station_profile->state == "TAS") { echo "selected"; } ?>>Tasmania</option>
								<option value="NT" <?php if($my_station_profile->state == "NT") { echo "selected"; } ?>>Northern Territory</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="png_state">
    						<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="NCD" <?php if($my_station_profile->state == "NCD") { echo "selected"; } ?>>National Capital District (Port Moresby)</option>
								<option value="CPM" <?php if($my_station_profile->state == "CPM") { echo "selected"; } ?>>Central</option>
								<option value="CPK" <?php if($my_station_profile->state == "CPK") { echo "selected"; } ?>>Chimbu</option>
								<option value="EHG" <?php if($my_station_profile->state == "EHG") { echo "selected"; } ?>>Eastern Highlands</option>
								<option value="EBR" <?php if($my_station_profile->state == "EBR") { echo "selected"; } ?>>East New Britain</option>
								<option value="ESW" <?php if($my_station_profile->state == "ESW") { echo "selected"; } ?>>East Sepik</option>
								<option value="EPW" <?php if($my_station_profile->state == "EPW") { echo "selected"; } ?>>Enga</option>
								<option value="GPK" <?php if($my_station_profile->state == "GPK") { echo "selected"; } ?>>Gulf</option>
								<option value="MPM" <?php if($my_station_profile->state == "MPM") { echo "selected"; } ?>>Madang</option>
								<option value="MRL" <?php if($my_station_profile->state == "MRL") { echo "selected"; } ?>>Manus</option>
								<option value="MBA" <?php if($my_station_profile->state == "MBA") { echo "selected"; } ?>>Milne Bay</option>
								<option value="MPL" <?php if($my_station_profile->state == "MPL") { echo "selected"; } ?>>Morobe</option>
								<option value="NIK" <?php if($my_station_profile->state == "NIK") { echo "selected"; } ?>>New Ireland</option>
								<option value="NPP" <?php if($my_station_profile->state == "NPP") { echo "selected"; } ?>>Northern</option>
								<option value="NSA" <?php if($my_station_profile->state == "NSA") { echo "selected"; } ?>>North Solomons</option>
								<option value="SAN" <?php if($my_station_profile->state == "SAN") { echo "selected"; } ?>>Santaun</option>
								<option value="SHM" <?php if($my_station_profile->state == "SHM") { echo "selected"; } ?>>Southern Highlands</option>
								<option value="WPD" <?php if($my_station_profile->state == "WPD") { echo "selected"; } ?>>Western</option>
								<option value="WHM" <?php if($my_station_profile->state == "WHM") { echo "selected"; } ?>>Western Highlands</option>
								<option value="WBR" <?php if($my_station_profile->state == "WBR") { echo "selected"; } ?>>West New Britain</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="nz_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="AUK" <?php if($my_station_profile->state == "AUK") { echo "selected"; } ?>>Auckland</option>
								<option value="BOP" <?php if($my_station_profile->state == "BOP") { echo "selected"; } ?>>Bay of Plenty</option>
								<option value="NTL" <?php if($my_station_profile->state == "NTL") { echo "selected"; } ?>>Northland</option>
								<option value="WKO" <?php if($my_station_profile->state == "WKO") { echo "selected"; } ?>>Waikato</option>
								<option value="GIS" <?php if($my_station_profile->state == "GIS") { echo "selected"; } ?>>Gisborne</option>
								<option value="HKB" <?php if($my_station_profile->state == "HKB") { echo "selected"; } ?>>Hawkes Bay</option>
								<option value="MWT" <?php if($my_station_profile->state == "MWT") { echo "selected"; } ?>>Manawatu-Wanganui</option>
								<option value="TKI" <?php if($my_station_profile->state == "TKI") { echo "selected"; } ?>>Taranaki</option>
								<option value="WGN" <?php if($my_station_profile->state == "WGN") { echo "selected"; } ?>>Wellington</option>
								<option value="CAN" <?php if($my_station_profile->state == "CAN") { echo "selected"; } ?>>Canterbury</option>
								<option value="MBH" <?php if($my_station_profile->state == "MBH") { echo "selected"; } ?>>Marlborough</option>
								<option value="NSN" <?php if($my_station_profile->state == "NSN") { echo "selected"; } ?>>Nelson</option>
								<option value="TAS" <?php if($my_station_profile->state == "TAS") { echo "selected"; } ?>>Tasman</option>
								<option value="WTC" <?php if($my_station_profile->state == "WTC") { echo "selected"; } ?>>West Coast</option>
								<option value="OTA" <?php if($my_station_profile->state == "OTA") { echo "selected"; } ?>>Otago</option>
								<option value="STL" <?php if($my_station_profile->state == "STL") { echo "selected"; } ?>>Southland</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="belgium_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="AN" <?php if($my_station_profile->state == "AN") { echo "selected"; } ?>>Antwerpen</option>
								<option value="BR" <?php if($my_station_profile->state == "BR") { echo "selected"; } ?>>Brussels</option>
								<option value="BW" <?php if($my_station_profile->state == "BW") { echo "selected"; } ?>>Brabant Wallon</option>
								<option value="HT" <?php if($my_station_profile->state == "HT") { echo "selected"; } ?>>Hainaut</option>
								<option value="LB" <?php if($my_station_profile->state == "LB") { echo "selected"; } ?>>Limburg</option>
								<option value="LG" <?php if($my_station_profile->state == "LG") { echo "selected"; } ?>>Liêge</option>
								<option value="NM" <?php if($my_station_profile->state == "NM") { echo "selected"; } ?>>Namur</option>
								<option value="LU" <?php if($my_station_profile->state == "LU") { echo "selected"; } ?>>Luxembourg</option>
								<option value="OV" <?php if($my_station_profile->state == "OV") { echo "selected"; } ?>>Oost-Vlaanderen</option>
								<option value="VB" <?php if($my_station_profile->state == "VB") { echo "selected"; } ?>>Vlaams Brabant</option>
								<option value="WV" <?php if($my_station_profile->state == "WV") { echo "selected"; } ?>>West-Vlaanderen</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="italy_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="AG" <?php if($my_station_profile->state == "AG") { echo "selected"; } ?>>Agrigento</option>
								<option value="AL" <?php if($my_station_profile->state == "AL") { echo "selected"; } ?>>Alessandria</option>
								<option value="AN" <?php if($my_station_profile->state == "AN") { echo "selected"; } ?>>Ancona</option>
								<option value="AO" <?php if($my_station_profile->state == "AO") { echo "selected"; } ?>>Aosta</option>
								<option value="AP" <?php if($my_station_profile->state == "AP") { echo "selected"; } ?>>Ascoli Piceno</option>
								<option value="AQ" <?php if($my_station_profile->state == "AQ") { echo "selected"; } ?>>L'Aquila</option>
								<option value="AR" <?php if($my_station_profile->state == "AR") { echo "selected"; } ?>>Arezzo</option>
								<option value="AT" <?php if($my_station_profile->state == "AT") { echo "selected"; } ?>>Asti</option>
								<option value="AV" <?php if($my_station_profile->state == "AV") { echo "selected"; } ?>>Avellino</option>
								<option value="BA" <?php if($my_station_profile->state == "BA") { echo "selected"; } ?>>Bari</option>
								<option value="BG" <?php if($my_station_profile->state == "BG") { echo "selected"; } ?>>Bergamo</option>
								<option value="BI" <?php if($my_station_profile->state == "BI") { echo "selected"; } ?>>Biella</option>
								<option value="BL" <?php if($my_station_profile->state == "BL") { echo "selected"; } ?>>Belluno</option>
								<option value="BN" <?php if($my_station_profile->state == "BN") { echo "selected"; } ?>>Benevento</option>
								<option value="BO" <?php if($my_station_profile->state == "BO") { echo "selected"; } ?>>Bologna</option>
								<option value="BR" <?php if($my_station_profile->state == "BR") { echo "selected"; } ?>>Brindisi</option>
								<option value="BS" <?php if($my_station_profile->state == "BS") { echo "selected"; } ?>>Brescia</option>
								<option value="BT" <?php if($my_station_profile->state == "BT") { echo "selected"; } ?>>Barletta-Andria-Trani</option>
								<option value="BZ" <?php if($my_station_profile->state == "BZ") { echo "selected"; } ?>>Bolzano</option>
								<option value="CB" <?php if($my_station_profile->state == "CB") { echo "selected"; } ?>>Campobasso</option>
								<option value="CE" <?php if($my_station_profile->state == "CE") { echo "selected"; } ?>>Caserta</option>
								<option value="CH" <?php if($my_station_profile->state == "CH") { echo "selected"; } ?>>Chieti</option>
								<option value="CL" <?php if($my_station_profile->state == "CL") { echo "selected"; } ?>>Caltanissetta</option>
								<option value="CN" <?php if($my_station_profile->state == "CN") { echo "selected"; } ?>>Cuneo</option>
								<option value="CO" <?php if($my_station_profile->state == "CO") { echo "selected"; } ?>>Como</option>
								<option value="CR" <?php if($my_station_profile->state == "CR") { echo "selected"; } ?>>Cremona</option>
								<option value="CS" <?php if($my_station_profile->state == "CS") { echo "selected"; } ?>>Cosenza</option>
								<option value="CT" <?php if($my_station_profile->state == "CT") { echo "selected"; } ?>>Catania</option>
								<option value="CZ" <?php if($my_station_profile->state == "CZ") { echo "selected"; } ?>>Catanzaro</option>
								<option value="EN" <?php if($my_station_profile->state == "EN") { echo "selected"; } ?>>Enna</option>
								<option value="FC" <?php if($my_station_profile->state == "FC") { echo "selected"; } ?>>Forlì-Cesena</option>
								<option value="FE" <?php if($my_station_profile->state == "FE") { echo "selected"; } ?>>Ferrara</option>
								<option value="FG" <?php if($my_station_profile->state == "FG") { echo "selected"; } ?>>Foggia</option>
								<option value="FI" <?php if($my_station_profile->state == "FI") { echo "selected"; } ?>>Firenze</option>
								<option value="FM" <?php if($my_station_profile->state == "FM") { echo "selected"; } ?>>Fermo</option>
								<option value="FO" <?php if($my_station_profile->state == "FO") { echo "selected"; } ?>>Forlì (import-only)</option>
								<option value="FR" <?php if($my_station_profile->state == "FR") { echo "selected"; } ?>>Frosinone</option>
								<option value="GE" <?php if($my_station_profile->state == "GE") { echo "selected"; } ?>>Genova</option>
								<option value="GO" <?php if($my_station_profile->state == "GO") { echo "selected"; } ?>>Gorizia</option>
								<option value="GR" <?php if($my_station_profile->state == "GR") { echo "selected"; } ?>>Grosseto</option>
								<option value="IM" <?php if($my_station_profile->state == "IM") { echo "selected"; } ?>>Imperia</option>
								<option value="IS" <?php if($my_station_profile->state == "IS") { echo "selected"; } ?>>Isernia</option>
								<option value="KR" <?php if($my_station_profile->state == "KR") { echo "selected"; } ?>>Crotone</option>
								<option value="LC" <?php if($my_station_profile->state == "LC") { echo "selected"; } ?>>Lecco</option>
								<option value="LE" <?php if($my_station_profile->state == "LE") { echo "selected"; } ?>>Lecce</option>
								<option value="LI" <?php if($my_station_profile->state == "LI") { echo "selected"; } ?>>Livorno</option>
								<option value="LO" <?php if($my_station_profile->state == "LO") { echo "selected"; } ?>>Lodi</option>
								<option value="LT" <?php if($my_station_profile->state == "LT") { echo "selected"; } ?>>Latina</option>
								<option value="LU" <?php if($my_station_profile->state == "LU") { echo "selected"; } ?>>Lucca</option>
								<option value="MB" <?php if($my_station_profile->state == "MB") { echo "selected"; } ?>>Monza e Brianza</option>
								<option value="MC" <?php if($my_station_profile->state == "MC") { echo "selected"; } ?>>Macerata</option>
								<option value="ME" <?php if($my_station_profile->state == "ME") { echo "selected"; } ?>>Messina</option>
								<option value="MI" <?php if($my_station_profile->state == "MI") { echo "selected"; } ?>>Milano</option>
								<option value="MN" <?php if($my_station_profile->state == "MN") { echo "selected"; } ?>>Mantova</option>
								<option value="MO" <?php if($my_station_profile->state == "MO") { echo "selected"; } ?>>Modena</option>
								<option value="MS" <?php if($my_station_profile->state == "MS") { echo "selected"; } ?>>Massa Carrara</option>
								<option value="MT" <?php if($my_station_profile->state == "MT") { echo "selected"; } ?>>Matera</option>
								<option value="NA" <?php if($my_station_profile->state == "NA") { echo "selected"; } ?>>Napoli</option>
								<option value="NO" <?php if($my_station_profile->state == "NO") { echo "selected"; } ?>>Novara</option>
								<option value="PA" <?php if($my_station_profile->state == "PA") { echo "selected"; } ?>>Palermo</option>
								<option value="PC" <?php if($my_station_profile->state == "PC") { echo "selected"; } ?>>Piacenza</option>
								<option value="PD" <?php if($my_station_profile->state == "PD") { echo "selected"; } ?>>Padova</option>
								<option value="PE" <?php if($my_station_profile->state == "PE") { echo "selected"; } ?>>Pescara</option>
								<option value="PG" <?php if($my_station_profile->state == "PG") { echo "selected"; } ?>>Perugia</option>
								<option value="PI" <?php if($my_station_profile->state == "PI") { echo "selected"; } ?>>Pisa</option>
								<option value="PN" <?php if($my_station_profile->state == "PN") { echo "selected"; } ?>>Pordenone</option>
								<option value="PO" <?php if($my_station_profile->state == "PO") { echo "selected"; } ?>>Prato</option>
								<option value="PR" <?php if($my_station_profile->state == "PR") { echo "selected"; } ?>>Parma</option>
								<option value="PS" <?php if($my_station_profile->state == "PS") { echo "selected"; } ?>>Pesaro e Urbino (import-only)</option>
								<option value="PT" <?php if($my_station_profile->state == "PT") { echo "selected"; } ?>>Pistoia</option>
								<option value="PU" <?php if($my_station_profile->state == "PU") { echo "selected"; } ?>>Pesaro e Urbino</option>
								<option value="PV" <?php if($my_station_profile->state == "PV") { echo "selected"; } ?>>Pavia</option>
								<option value="PZ" <?php if($my_station_profile->state == "PZ") { echo "selected"; } ?>>Potenza</option>
								<option value="RA" <?php if($my_station_profile->state == "RA") { echo "selected"; } ?>>Ravenna</option>
								<option value="RC" <?php if($my_station_profile->state == "RC") { echo "selected"; } ?>>Reggio Calabria</option>
								<option value="RE" <?php if($my_station_profile->state == "RE") { echo "selected"; } ?>>Reggio Emilia</option>
								<option value="RG" <?php if($my_station_profile->state == "RG") { echo "selected"; } ?>>Ragusa</option>
								<option value="RI" <?php if($my_station_profile->state == "RI") { echo "selected"; } ?>>Rieti</option>
								<option value="RM" <?php if($my_station_profile->state == "RM") { echo "selected"; } ?>>Roma</option>
								<option value="RN" <?php if($my_station_profile->state == "RN") { echo "selected"; } ?>>Rimini</option>
								<option value="RO" <?php if($my_station_profile->state == "RO") { echo "selected"; } ?>>Rovigo</option>
								<option value="SA" <?php if($my_station_profile->state == "SA") { echo "selected"; } ?>>Salerno</option>
								<option value="SI" <?php if($my_station_profile->state == "SI") { echo "selected"; } ?>>Siena</option>
								<option value="SO" <?php if($my_station_profile->state == "SO") { echo "selected"; } ?>>Sondrio</option>
								<option value="SP" <?php if($my_station_profile->state == "SP") { echo "selected"; } ?>>La Spezia</option>
								<option value="SR" <?php if($my_station_profile->state == "SR") { echo "selected"; } ?>>Siracusa</option>
								<option value="SV" <?php if($my_station_profile->state == "SV") { echo "selected"; } ?>>Savona</option>
								<option value="TA" <?php if($my_station_profile->state == "TA") { echo "selected"; } ?>>Taranto</option>
								<option value="TE" <?php if($my_station_profile->state == "TE") { echo "selected"; } ?>>Teramo</option>
								<option value="TN" <?php if($my_station_profile->state == "TN") { echo "selected"; } ?>>Trento</option>
								<option value="TO" <?php if($my_station_profile->state == "TO") { echo "selected"; } ?>>Torino</option>
								<option value="TP" <?php if($my_station_profile->state == "TP") { echo "selected"; } ?>>Trapani</option>
								<option value="TR" <?php if($my_station_profile->state == "TR") { echo "selected"; } ?>>Terni</option>
								<option value="TS" <?php if($my_station_profile->state == "TS") { echo "selected"; } ?>>Trieste</option>
								<option value="TV" <?php if($my_station_profile->state == "TV") { echo "selected"; } ?>>Treviso</option>
								<option value="UD" <?php if($my_station_profile->state == "UD") { echo "selected"; } ?>>Udine</option>
								<option value="VA" <?php if($my_station_profile->state == "VA") { echo "selected"; } ?>>Varese</option>
								<option value="VB" <?php if($my_station_profile->state == "VB") { echo "selected"; } ?>>Verbano Cusio Ossola</option>
								<option value="VC" <?php if($my_station_profile->state == "VC") { echo "selected"; } ?>>Vercelli</option>
								<option value="VE" <?php if($my_station_profile->state == "VE") { echo "selected"; } ?>>Venezia</option>
								<option value="VI" <?php if($my_station_profile->state == "VI") { echo "selected"; } ?>>Vicenza</option>
								<option value="VR" <?php if($my_station_profile->state == "VR") { echo "selected"; } ?>>Verona</option>
								<option value="VT" <?php if($my_station_profile->state == "VT") { echo "selected"; } ?>>Viterbo</option>
								<option value="VV" <?php if($my_station_profile->state == "VV") { echo "selected"; } ?>>Vibo Valentia</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<div class="mb-3" id="netherlands_state">
							<label for="stateInput"><?php echo lang("station_location_state"); ?></label>
							<select class="form-select" name="station_state" id="StateHelp" aria-describedby="stationCntyInputHelp">
								<option value=""></option>
								<option value="DR" <?php if($my_station_profile->state == "DR") { echo "selected"; } ?>>Drenthe</option>
								<option value="FL" <?php if($my_station_profile->state == "FL") { echo "selected"; } ?>>Flevoland</option>
								<option value="FR" <?php if($my_station_profile->state == "FR") { echo "selected"; } ?>>Friesland</option>
								<option value="GD" <?php if($my_station_profile->state == "GD") { echo "selected"; } ?>>Gelderland</option>
								<option value="GR" <?php if($my_station_profile->state == "GR") { echo "selected"; } ?>>Groningen</option>
								<option value="LB" <?php if($my_station_profile->state == "LB") { echo "selected"; } ?>>Limburg</option>
								<option value="NB" <?php if($my_station_profile->state == "NB") { echo "selected"; } ?>>Noord-Brabant</option>
								<option value="NH" <?php if($my_station_profile->state == "NH") { echo "selected"; } ?>>Noord-Holland</option>
								<option value="OV" <?php if($my_station_profile->state == "OV") { echo "selected"; } ?>>Overijssel</option>
								<option value="UT" <?php if($my_station_profile->state == "UT") { echo "selected"; } ?>>Utrecht</option>
								<option value="ZH" <?php if($my_station_profile->state == "ZH") { echo "selected"; } ?>>Zuid-Holland</option>
								<option value="ZL" <?php if($my_station_profile->state == "ZL") { echo "selected"; } ?>>Zeeland</option>
							</select>
							<small id="StateHelp" class="form-text text-muted"><?php echo lang("station_location_state_hint"); ?></small>
						</div>

						<!-- US County -->
						<div class="mb-3">
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
					<div class="mb-3">
						<label for="stationCQZoneInput"><?php echo lang("gen_hamradio_cq_zone"); ?></label>
						<select class="form-select" id="stationCQZoneInput" name="station_cq" required>
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
					<div class="mb-3">
                    	<label for="stationITUZoneInput"><?php echo lang("gen_hamradio_itu_zone"); ?></label>
                    	<select class="form-select" id="stationITUZoneInput" name="station_itu" required>
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
					<div class="mb-3">
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
					<div class="mb-3">
                		<label for="stationIOTAInput"><?php echo lang("gen_hamradio_iota_reference"); ?></label>
                		<select class="form-select" name="iota" id="stationIOTAInput" aria-describedby="stationIOTAInputHelp" placeholder="EU-005">
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
					<div class="mb-3">
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
					<div class="mb-3">
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
					<div class="mb-3">
						<label for="stationPOTAInput"><?php echo lang("gen_hamradio_pota_reference"); ?></label>
						<input type="text" class="form-control" name="pota" id="stationPOTAInput" aria-describedby="stationPOTAInputHelp" value="<?php if(set_value('pota') != "") { echo set_value('pota'); } else { echo $my_station_profile->station_pota; } ?>">
						<small id="stationPOTAInputHelp" class="form-text text-muted"><?php echo lang("station_location_pota_hint_ln1"); ?></small>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md" id="WABbox">
			<div class="card">
				<h5 class="card-header">Worked All Britain Reference</h5>
				<div class="card-body">
					<div class="mb-3">
						<label for="stationWABInput">WAB Reference Number</label>
						<input type="text" class="form-control" name="wab" id="stationWABInput" aria-describedby="stationWABInputHelp" value="<?php if(set_value('wab') != "") { echo set_value('wab'); } else { echo $my_station_profile->station_wab; } ?>">
						<small id="stationWABInputHelp" class="form-text text-muted">Enter your WAB Square, if you dont know it use <a href="https://www.whatsmylocator.co.uk/" target="_blank">WhatsMyLocator</a></small>
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
					<div class="mb-3">
		    			<label for="stationSigInput"><?php echo lang("station_location_signature_name"); ?></label>
		    			<input type="text" class="form-control" name="sig" id="stationSigInput" aria-describedby="stationSigInputHelp" value="<?php if(set_value('sig') != "") { echo set_value('sig'); } else { echo $my_station_profile->station_sig; } ?>">
		    			<small id="stationSigInputHelp" class="form-text text-muted"><?php echo lang("station_location_signature_name_hint"); ?></small>
					</div>

					<div class="mb-3">
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
					<div class="mb-3">
		    			<label for="eqslNickname">eQSL QTH Nickname</label> <!-- This does not need Multilanguage Support -->
		    			<input type="text" class="form-control" name="eqslnickname" id="eqslNickname" aria-describedby="eqslhelp" value="<?php if(set_value('eqslnickname') != "") { echo set_value('eqslnickname'); } else { echo $my_station_profile->eqslqthnickname; } ?>">
		    			<small id="eqslhelp" class="form-text text-muted"><?php echo lang("station_location_eqsl_hint"); ?></small>
		  			</div>
					<div class="mb-3">
		    			<label for="eqslDefaultQSLMsg"><?php echo lang("station_location_eqsl_defaultqslmsg"); ?></label>
						<label class="position-absolute end-0 mb-2 me-3" for="eqslDefaultQSLMsg" id="charsLeft"> </label>
		    			<?php $eqsl_default_qslmsg = (set_value('eqsl_default_qslmsg') != "")?set_value('eqsl_default_qslmsg'):$eqsl_default_qslmsg; ?>
		    			<textarea class="form-control" name="eqsl_default_qslmsg" id="eqslDefaultQSLMsg" aria-describedby="eqsldefaultqslmsghelp" maxlength="240" rows="2" style="width:100%;" value="<?php echo $eqsl_default_qslmsg; ?>"><?php echo $eqsl_default_qslmsg; ?></textarea>
		    			<small id="eqsldefaultqslmsghelp" class="form-text text-muted"><?php echo lang("station_location_eqsl_defaultqslmsg_hint"); ?></small>
		  			</div>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<h5 class="card-header">QRZ.com <span class="badge text-bg-warning"> <?php echo lang("station_location_qrz_subscription"); ?></span></h5> <!-- "QRZ.com" does not need Multilanguage Support -->
				<div class="card-body">
					<div class="mb-3">
						<label for="qrzApiKey">QRZ.com Logbook API Key</label> <!-- This does not need Multilanguage Support -->
						<input type="text" class="form-control" name="qrzapikey" pattern="^([A-F0-9]{4}-){3}[A-F0-9]{4}$" id="qrzApiKey" aria-describedby="qrzApiKeyHelp" value="<?php if(set_value('qrzapikey') != "") { echo set_value('qrzapikey'); } else { echo $my_station_profile->qrzapikey; } ?>">
						<small id="qrzApiKeyHelp" class="form-text text-muted"><?php echo lang("station_location_qrz_hint"); ?></a></small>
					</div>

					<div class="mb-3">
						<label for="qrzrealtime"><?php echo lang("station_location_qrz_realtime_upload"); ?></label>
						<select class="form-select" id="qrzrealtime" name="qrzrealtime">
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
				<h5 class="card-header">ClubLog</h5> <!-- This does not need Multilanguage Support -->
				<div class="card-body">
					<div class="mb-3">
						<label for="clublogrealtime"><?php echo lang("station_location_clublog_realtime_upload"); ?></label>
						<select class="form-select" id="clublogrealtime" name="clublogrealtime">
							<option value="1" <?php if ($my_station_profile->clublogrealtime == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->clublogrealtime == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
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
					<div class="mb-3">
						<label for="webadifApiKey"><?php echo lang("station_location_hrdlog_username"); ?></label>
						<input type="text" class="form-control" name="hrdlog_username" id="hrdlog_username" aria-describedby="hrdlog_usernameHelp" value="<?php if(set_value('hrdlog_username') != "") { echo set_value('hrdlog_username'); } else { echo $my_station_profile->hrdlog_username; } ?>">
						<small id="hrdlog_usernameHelp" class="form-text text-muted"><?php echo lang("station_location_hrdlog_username_hint"); ?></a></small>
					</div>
					<div class="mb-3">
						<label for="webadifApiKey"><?php echo lang("station_location_hrdlog_code"); ?></label> <!-- This does not need Multilanguage Support -->
						<input type="text" class="form-control" name="hrdlog_code" id="hrdlog_code" aria-describedby="hrdlog_codeHelp" value="<?php if(set_value('hrdlog_code') != "") { echo set_value('hrdlog_code'); } else { echo $my_station_profile->hrdlog_code; } ?>">
						<small id="hrdlog_codeHelp" class="form-text text-muted"><?php echo lang("station_location_hrdlog_code_hint"); ?></a></small>
					</div>
					<div class="mb-3">
						<label for="hrdlogrealtime"><?php echo lang("station_location_hrdlog_realtime_upload"); ?></label>
						<select class="form-select" id="hrdlogrealtime" name="hrdlogrealtime">
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
					<div class="mb-3">
						<label for="webadifApiKey">QO-100 Dx Club API Key</label> <!-- This does not need Multilanguage Support -->
						<input type="text" class="form-control" name="webadifapikey" id="webadifApiKey" aria-describedby="webadifApiKeyHelp" value="<?php if(set_value('webadifapikey') != "") { echo set_value('webadifapikey'); } else { echo $my_station_profile->webadifapikey; } ?>">
						<small id="webadifApiKeyHelp" class="form-text text-muted"><?php echo lang("station_location_qo100_hint"); ?></a></small>
					</div>
					<div class="mb-3">
						<label for="webadifrealtime"><?php echo lang("station_location_qo100_realtime_upload"); ?></label>
						<select class="form-select" id="webadifrealtime" name="webadifrealtime">
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
					<div class="mb-3">
						<label for="oqrs"><?php echo lang("station_location_oqrs_enabled"); ?></label>
						<select class="form-select" id="oqrs" name="oqrs">
							<option value="1" <?php if ($my_station_profile->oqrs == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->oqrs == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
						</select>
					</div>
					<div class="mb-3">
						<label for="oqrs"><?php echo lang("station_location_oqrs_email_alert"); ?></label>
						<select class="form-select" id="oqrsemail" name="oqrsemail">
							<option value="1" <?php if ($my_station_profile->oqrs_email == 1) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_yes"); ?></option>
							<option value="0" <?php if ($my_station_profile->oqrs_email == 0) { echo " selected =\"selected\""; } ?>><?php echo lang("general_word_no"); ?></option>
						</select>
						<small id="oqrsemailHelp" class="form-text text-muted"><?php echo lang("station_location_oqrs_email_hint"); ?></small>
					</div>
					<div class="mb-3">
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
