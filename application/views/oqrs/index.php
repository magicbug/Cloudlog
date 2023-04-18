<div class="container">

    <br>

    <h2><?php echo $page_title; ?></h2>

    <div class="card">
        <div class="card-header">
            Request a QSL card
        </div>
        <div class="card-body">


		<?php
		if ($global_oqrs_text) {
			echo $global_oqrs_text;
			echo '<br />';
		}
		  echo '<div class="resulttable">';
		  if ($stations->result() != NULL) { ?>

            <form class="form-inline" enctype="multipart/form-data">
			<label class="my-1 mr-2" for="station">Select station: </label>
                        <select id="station" class="custom-select my-1 mr-sm-2" name="station">
							<?php foreach($stations->result() as $station) {
									echo '<option value="' . $station->station_id . '">' . $station->station_profile_name . ' - ' . $station->station_callsign . '</option>'."\n";
								} ?>
						</select>
					<button id="button1id" type="button" onclick="loadStationInfo();" name="button1id" class="btn btn-sm btn-primary"> Proceed</button>
            </form>

			<div class="stationinfo"></div>
			<div class="searchinfo"></div>

			<?php }
			else {
				echo 'No stations found that are using Cloudlog OQRS.';
			}
			?>

        </div>
    </div>
</div>