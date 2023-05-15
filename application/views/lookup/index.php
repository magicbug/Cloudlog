	<form method="post" class="form-inline">
		<select id="quicklookuptype" name="type" class="form-control custom-select">
			<option value="cq">CQ Zone</option>
			<option value="dxcc">DXCC</option>
			<option value="vucc">Gridsquare</option>
			<option value="iota">IOTA</option>
			<option value="sota">SOTA</option>
			<option value="was">US State</option>
			<option value="wwff">WWFF</option>
		</select>
		<div>&nbsp;</div>
		<input style="display:none" class="form-control input-group-sm" id="quicklookuptext" type="text" name="searchfield" placeholder="" aria-label="Search">
		<select style="display:none" class="form-control custom-select" id="quicklookupdxcc" name="dxcc" required>

			<?php
			foreach($dxcc as $d){
				echo '<option value=' . $d->adif . '>' . $d->prefix . ' - ' . ucwords(strtolower($d->name), "- (/");
				if ($d->Enddate != null) {
					echo ' ('.lang('gen_hamradio_deleted_dxcc').')';
				}
				echo '</option>';
			}
			?>

		</select>

		<select class="form-control custom-select" id="quicklookupcqz" name="cqz" required>
			<?php
			for ($i = 1; $i<=40; $i++) {
				echo '<option value="'. $i . '">'. $i .'</option>';
			}
			?>
		</select>

		<select style="display:none" class="form-control custom-select" id="quicklookupwas" name="was">
			<option value="AL">Alabama (AL)</option>
			<option value="AK">Alaska (AK)</option>
			<option value="AZ">Arizona (AZ)</option>
			<option value="AR">Arkansas (AR)</option>
			<option value="CA">California (CA)</option>
			<option value="CO">Colorado (CO)</option>
			<option value="CT">Connecticut (CT)</option>
			<option value="DE">Delaware (DE)</option>
			<option value="DC">District Of Columbia (DC)</option>
			<option value="FL">Florida (FL)</option>
			<option value="GA">Georgia (GA)</option>
			<option value="HI">Hawaii (HI)</option>
			<option value="ID">Idaho (ID)</option>
			<option value="IL">Illinois (IL)</option>
			<option value="IN">Indiana (IN)</option>
			<option value="IA">Iowa (IA)</option>
			<option value="KS">Kansas (KS)</option>
			<option value="KY">Kentucky (KY)</option>
			<option value="LA">Louisiana (LA)</option>
			<option value="ME">Maine (ME)</option>
			<option value="MD">Maryland (MD)</option>
			<option value="MA">Massachusetts (MA)</option>
			<option value="MI">Michigan (MI)</option>
			<option value="MN">Minnesota (MN)</option>
			<option value="MS">Mississippi (MS)</option>
			<option value="MO">Missouri (MO)</option>
			<option value="MT">Montana (MT)</option>
			<option value="NE">Nebraska (NE)</option>
			<option value="NV">Nevada (NV)</option>
			<option value="NH">New Hampshire (NH)</option>
			<option value="NJ">New Jersey (NJ)</option>
			<option value="NM">New Mexico (NM)</option>
			<option value="NY">New York (NY)</option>
			<option value="NC">North Carolina (NC)</option>
			<option value="ND">North Dakota (ND)</option>
			<option value="OH">Ohio (OH)</option>
			<option value="OK">Oklahoma (OK)</option>
			<option value="OR">Oregon (OR)</option>
			<option value="PA">Pennsylvania (PA)</option>
			<option value="RI">Rhode Island (RI)</option>
			<option value="SC">South Carolina (SC)</option>
			<option value="SD">South Dakota (SD)</option>
			<option value="TN">Tennessee (TN)</option>
			<option value="TX">Texas (TX)</option>
			<option value="UT">Utah (UT)</option>
			<option value="VT">Vermont (VT)</option>
			<option value="VA">Virginia (VA)</option>
			<option value="WA">Washington (WA)</option>
			<option value="WV">West Virginia (WV)</option>
			<option value="WI">Wisconsin (WI)</option>
			<option value="WY">Wyoming (WY)</option>
		</select>

		<select style="display:none" class="form-control custom-select" id="quicklookupiota" name="iota_ref">

			<?php
			foreach($iota as $i){
				echo '<option value=' . $i->tag . '>' . $i->tag . ' - ' . $i->name . '</option>';
			}
			?>

		</select>
		<div>&nbsp;</div><button id="button1id" type="button" name="button1id" class="btn btn-primary ld-ext-right" onclick="getLookupResult(this.form)">Show<div class="ld ld-ring ld-spin"></div></button>
	</form>
<br/>
	<div class="table-responsive"  id="lookupresulttable">
	</div>
