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
		  if ($contestyears) { ?>

			<form class="form" action="<?php echo site_url('cabrillo/export'); ?>" method="post" enctype="multipart/form-data">
				<div class="form-group form-inline row">
					<div class="col-md-2 control-label" for="year">Select year: </div>
								<select id="year" class="custom-select my-1 mr-sm-2 col-md-2" name="year">
								<?php foreach ($contestyears as $row) { ?>
									<option value="<?php echo $row->year; ?>"><?php echo $row->year;?></option>
								<?php } ?>
								</select>
							<button id="button1id" type="button" onclick="loadContests();" name="button1id" class="btn btn-sm btn-primary"> Proceed</button>
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