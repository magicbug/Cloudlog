<form class="form col-md-12" enctype="multipart/form-data">
    <div class="form-group row">
        <div class="col-md-2 control-label" for="input">Locator 1</div>
        <div class="col-md-4">
            <input class="form-control input-group-sm" id="qrbcalc_locator1" type="text" name="locator1" placeholder="" value="<?php if ($station_locator != "0") echo $station_locator; ?>" aria-label="locator1">  
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-2 control-label" for="input">Locator 2</div>
        <div class="col-md-4">
        <input class="form-control input-group-sm" id="qrbcalc_locator2" type="text" name="locator2" placeholder="" aria-label="locator2">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label" for="button1id"></label>
        <div class="col-md-4">
            <button id="button2id" type="reset" name="button2id" class="btn-sm btn-warning">Reset</button>
            <button id="button1id" type="button" onclick="calculateQrb();" name="button1id" class="btn-sm btn-primary">Calculate</button>
        </div>
    </div>
</form>
<div class="qrbResult"></div>
<div id="mapqrb"><div id="mapqrbcontainer" style="Height: 500px"></div></div>