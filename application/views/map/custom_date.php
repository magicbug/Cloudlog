<div class="container custom-map-QSOs">

	<h2><?php echo $station_profile->station_profile_name; ?> Station Profile QSOs (Custom Date)</h2>

	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>

<form method="post" action="<?php echo site_url('map/custom');?>">
    <div class="row">
        <label class="col-md-2 control-label" for="from">Start Date/Time</label>
        <div class="input-group date col-md-3" id="datetimepicker1" data-target-input="nearest">
            <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
    </row>

    <div class="row">
        <label class="col-md-2 control-label" for="to">End Date/Time</label>
        
        <div class="input-group date col-md-3" id="datetimepicker2" data-target-input="nearest">
                <input name="to" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label" for="band">Band</label>
        
        <div class="col-md-2">
            <select id="band2" name="band" class="form-control custom-select-sm">
                <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >Every band</option>
                <?php foreach($worked_bands as $band) {
                    echo '<option value="' . $band . '"';
                    if ($this->input->post('band') == $band) echo ' selected';
                        echo '>' . $band . '</option>'."\n";
                } ?>
            </select>
        </div>
    </div>

    <input type="submit" value="Load Map">
    <br><br>
</form>

</div>

	<!-- Map -->
	<div id="map" style="width: 100%; height: 700px;"></div> 

    <div class="alert alert-success" role="alert">Showing QSOs for Custom Date for Active Station Profile - <?php echo $station_profile->station_profile_name; ?> </div>