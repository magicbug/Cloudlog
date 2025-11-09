<form method="post" class="col-md">
    <div class="mb-3 row">
        <label class="my-1 me-2 col-md-4" for="station_id_select">Station Location:</label>
        <div class="col-md-8">
            <select class="form-select" id="station_id_select" name="station_id_select">
                <option value="">-- Select Station Location --</option>
                <?php foreach($station_profile->result() as $station) { ?>
                    <option value="<?php echo $station->station_id; ?>">
                        <?php echo $station->station_callsign; ?> - <?php echo $station->station_profile_name; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
</form>
