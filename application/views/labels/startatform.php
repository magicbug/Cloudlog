<form method="post" action="<?php echo site_url('labels/print/'.$stationid) ?>" class="form-inline">
	<input class="form-control input-group-sm" type="checkbox" id="grid" name="grid" value="1">
	Include Grid?
	<input class="form-control input-group-sm" type="number" id="startat" name="startat" value="1">
	<button type="submit" id="button1id" name="button1id" class="btn btn-primary ld-ext-right">Print</button>
</form>
