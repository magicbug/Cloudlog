<form method="post" class="col-md" action="<?php echo site_url('labels/print/'.$stationid) ?>">
    <div class="form-group row">
        <label class="my-1 mr-2 col-md-4" for="grid">Include Grid?</label>
        <div class="form-check-inline">
            <input class="form-check-input" type="checkbox" name="grid" id="grid">
        </div>
    </div>
    <div class="form-group row">
        <label class="my-1 mr-2 col-md-4" for="via">Include Via (if filled)?</label>
        <div class="form-check-inline">
            <input class="form-check-input" type="checkbox" name="via" id="via">
        </div>
    </div>
    <div class="form-group row">
        <label class="my-1 mr-2 col-md-4" for="startat">Start printing at?</label>
        <div class="form-inline">
            <input class="form-control input-group-sm" type="number" id="startat" name="startat" value="1">
        </div>
    </div>
    <div class="text-left">
        <button type="submit" id="button1id" name="button1id" class="btn btn-primary ld-ext-right">Print</button>
    </div>
</form>
