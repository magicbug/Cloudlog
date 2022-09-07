<div class="container custom-map-QSOs">

	<h2><?php echo $logbook_name ?> logbook QSOs (Custom Date)</h2>

	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>

<form method="post" action="<?php echo site_url('map/custom');?>">
    <div class="row">
        <label class="col-md-2 control-label" for="from">Start Date/Time</label>
        <div class="input-group date col-md-3" id="datetimepicker1" data-target-input="nearest">
            <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker1" <?php if ($this->input->post('from') != "" || $this->input->method() !== 'post') echo 'value="'.$this->input->post('from').'"'; ?>/>
                <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
    </row>

    <div class="row">
        <label class="col-md-2 control-label" for="to">End Date/Time</label>

        <div class="input-group date col-md-3" id="datetimepicker2" data-target-input="nearest">
                <input name="to" type="text" placeholder="DD/MM/YYYY" class="form-control form-control-sm datetimepicker-input" data-target="#datetimepicker2" <?php if ($this->input->post('to') != "" || $this->input->method() !== 'post') echo 'value="'.$this->input->post('to').'"'; ?>/>
                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
        </div>
    </div>

    <div class="row">
        <label class="col-md-2 control-label" for="band">Band</label>

        <div class="col-md-3">
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

    <div class="row">
        <label class="col-md-2 control-label" for="mode">Mode</label>
        <div class="col-md-3">
        <select id="mode" name="mode" class="form-control custom-select-sm">
        <option value="All" <?php if ($this->input->post('mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>>All</option>
            <?php
            foreach($modes->result() as $mode){
                if ($mode->submode == null) {
                    echo '<option value="' . $mode->mode . '"';
                    if ($this->input->post('mode') == $mode->mode) echo ' selected';
                    echo '>'. $mode->mode . '</option>'."\n";
                } else {
                    echo '<option value="' . $mode->submode . '"';
                    if ($this->input->post('mode') == $mode->submode) echo ' selected';
                    echo '>' . $mode->submode . '</option>'."\n";
                }
            }
            ?>
        </select>
        </div>
    </div>
    <div class="row">
        <label class="col-md-2 control-label"for="selectPropagation">Propagation Mode</label>
        <div class="col-md-3">
            <select class="form-control custom-select-sm" id="selectPropagation" name="prop_mode">
                <option value="All" <?php if ($this->input->post('prop_mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>>All</option>
                <option value="AS" <?php if ($this->input->post('prop_mode') == "AS") echo ' selected';?>>Aircraft Scatter</option>
                <option value="AUR" <?php if ($this->input->post('prop_mode') == "AUR") echo ' selected';?>>Aurora</option>
                <option value="AUE" <?php if ($this->input->post('prop_mode') == "AUE") echo ' selected';?>>Aurora-E</option>
                <option value="BS" <?php if ($this->input->post('prop_mode') == "BS") echo ' selected';?>>Back scatter</option>
                <option value="ECH" <?php if ($this->input->post('prop_mode') == "ECH") echo ' selected';?>>EchoLink</option>
                <option value="EME" <?php if ($this->input->post('prop_mode') == "EME") echo ' selected';?>>Earth-Moon-Earth</option>
                <option value="ES" <?php if ($this->input->post('prop_mode') == "ES") echo ' selected';?>>Sporadic E</option>
                <option value="FAI" <?php if ($this->input->post('prop_mode') == "FAI") echo ' selected';?>>Field Aligned Irregularities</option>
                <option value="F2" <?php if ($this->input->post('prop_mode') == "F2") echo ' selected';?>>F2 Reflection</option>
                <option value="INTERNET" <?php if ($this->input->post('prop_mode') == "INTERNET") echo ' selected';?>>Internet-assisted</option>
                <option value="ION" <?php if ($this->input->post('prop_mode') == "ION") echo ' selected';?>>Ionoscatter</option>
                <option value="IRL" <?php if ($this->input->post('prop_mode') == "IRL") echo ' selected';?>>IRLP</option>
                <option value="MS" <?php if ($this->input->post('prop_mode') == "MS") echo ' selected';?>>Meteor scatter</option>
                <option value="RPT" <?php if ($this->input->post('prop_mode') == "RPT") echo ' selected';?>>Terrestrial or atmospheric repeater or transponder</option>
                <option value="RS" <?php if ($this->input->post('prop_mode') == "RS") echo ' selected';?>>Rain scatter</option>
                <option value="SAT" <?php if ($this->input->post('prop_mode') == "SAT") echo ' selected';?>>Satellite</option>
                <option value="TEP" <?php if ($this->input->post('prop_mode') == "TEP") echo ' selected';?>>Trans-equatorial</option>
                <option value="TR" <?php if ($this->input->post('prop_mode') == "TR") echo ' selected';?>>Tropospheric ducting</option>
            </select>
        </div>
    </div>

    <input type="submit" value="Load Map">
    <br><br>
</form>

</div>

	<!-- Map -->
	<div id="custommap" style="width: 100%; height: calc(100vh - 380px); max-height: 900px;"></div>

    <div class="alert alert-success" role="alert">Showing QSOs for Custom Date for Active Logbook <?php echo $logbook_name ?></div>
