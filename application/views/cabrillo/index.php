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
					<select id="station_id" name="station_id" class="custom-select my-1 mr-sm-2 col-md-3">
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
			</form>

			<?php }
			else {
				echo 'No contests were found in your log.';
			}
			?>

        </div>
    </div>
</div>