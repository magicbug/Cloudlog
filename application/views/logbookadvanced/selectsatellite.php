<form method="post" class="col-md">
    <div class="mb-3 row">
        <label class="my-1 me-2 col-md-4" for="sat_name_select">Satellite Name:</label>
        <div class="col-md-8">
            <input list="satellite_names_bulk" id="sat_name_select" type="text" name="sat_name_select" class="form-control" placeholder="Select satellite...">
            <datalist id="satellite_names_bulk" class="satellite_names_list_bulk"></datalist>
        </div>
    </div>
    <div class="mb-3 row">
        <label class="my-1 me-2 col-md-4" for="sat_mode_select">Satellite Mode:</label>
        <div class="col-md-8">
            <input list="satellite_modes_bulk" id="sat_mode_select" type="text" name="sat_mode_select" class="form-control" placeholder="Select mode...">
            <datalist id="satellite_modes_bulk" class="satellite_modes_list_bulk"></datalist>
        </div>
    </div>
    <input type="hidden" id="uplink_freq_hidden" name="uplink_freq_hidden" value="">
    <input type="hidden" id="downlink_freq_hidden" name="downlink_freq_hidden" value="">
    <input type="hidden" id="uplink_mode_hidden" name="uplink_mode_hidden" value="">
    <input type="hidden" id="downlink_mode_hidden" name="downlink_mode_hidden" value="">
</form>
