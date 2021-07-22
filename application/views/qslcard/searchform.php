		<form method="post" onsubmit="return false;" action="" id="search_box" name="test">
			<div class="form-group row col-sm-12">
				<label for="callsign" class="col-sm-2 col-form-label">Callsign</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="callsign" value="">
				</div>
				<div class="col-sm-2">
					<button onclick="searchAdditionalQsos('<?php echo $filename; ?>')" class="btn-sm btn-success" type="button"><i class="fas fa-search"></i> Search</button>
				</div>
			</div>
		</form>

		<div id="searchresult"></div>
