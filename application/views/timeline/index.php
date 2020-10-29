<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <form class="form" action="<?php echo site_url('timeline'); ?>" method="post" enctype="multipart/form-data">
        <!-- Select Basic -->
                <div class="form-group row">
                    <label class="col-md-1 control-label" for="band">Band</label>
                    <div class="col-md-3">
                        <select id="band" name="band" class="form-control custom-select">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >All</option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '"';
                                if ($this->input->post('band') == $band) echo ' selected';
                                echo '>' . $band . '</option>'."\n";
                            } ?>
                        </select>
                    </div>

                    <label class="col-md-1 control-label" for="mode">Mode</label>
                    <div class="col-md-3">
                        <select id="mode" name="mode" class="form-control custom-select">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >All</option>
                            <?php
                            foreach($modes->result() as $mode){
                                if ($mode->submode == null) {
                                    echo '<option value="' . $mode->mode . '"';
                                    if ($this->input->post('mode') == $mode->mode) echo ' selected';
                                    echo '>' . $mode->mode . '</option>'."\n";
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

        <div class="form-group row">

            <label class="col-md-1 control-label" for="radio">Award</label>
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="awardradio" id="dxcc" value="dxcc" <?php if ($this->input->post('awardradio') == 'dxcc' || $this->input->method() !== 'post') echo ' checked'?>>
                    <label class="form-check-label" for="dxcc">
                        DX Century Club (DXCC)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="awardradio" id="was" value="was" <?php if ($this->input->post('awardradio') == 'was') echo ' checked'?>>
                    <label class="form-check-label" for="was">
                        Worked all states (WAS)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="awardradio" id="iota" value="iota" <?php if ($this->input->post('awardradio') == 'iota') echo ' checked'?>>
                    <label class="form-check-label" for="iota">
                        Islands on the air (IOTA)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="awardradio" id="waz" value="waz" <?php if ($this->input->post('awardradio') == 'waz') echo ' checked'?>>
                    <label class="form-check-label" for="waz">
                        Worked all zones (WAZ)
                    </label>
                </div>
            </div>
        </div>

            <div class="form-group row">
                <label class="col-md-1 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button1id" type="submit" name="button1id" class="btn btn-primary">Show</button>
                </div>
            </div>

    </form>

    <?php 
    // Get Date format
    if($this->session->userdata('user_date_format')) {
        // If Logged in and session exists
        $custom_date_format = $this->session->userdata('user_date_format');
    } else {
        // Get Default date format from /config/cloudlog.php
        $custom_date_format = $this->config->item('qso_date_format');
    }
    ?>

    <?php

    if ($timeline_array) {
        switch ($this->input->post('awardradio')) {
            case 'dxcc': $result = write_dxcc_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $this->input->post('awardradio')); break;
            case 'was':  $result = write_was_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $this->input->post('awardradio')); break;
            case 'iota': $result = write_iota_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $this->input->post('awardradio')); break;
            case 'waz':  $result = write_waz_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $this->input->post('awardradio')); break;
        }
    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>

</div>

<?php

function write_dxcc_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $i = count($timeline_array);
    echo '<table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped table-condensed text-center">
              <thead>
                    <tr>
                        <td>#</td>
                        <td>Date</td>
                        <td>Prefix</td>
                        <td>Country</td>
                        <td>Deleted</td>
                        <td>End date</td>
                        <td>Show QSOs</td>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line->date);
        echo '<tr>
                <td>' . $i-- . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line->prefix . '</td>
                <td>' . $line->col_country . '</td>
                <td>';
        if (!empty($line->end)) echo 'Yes';
        echo '</td>
                <td>' . $line->end . '</td>
                <td><a href=javascript:displayTimelineContacts("' . $line->adif . '","'. $bandselect . '","'. $modeselect . '","' . $award .'")>Show</a></td>
               </tr>';
    }
    echo '</tfoot></table></div>';
}

function write_was_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $i = count($timeline_array);
    echo '<table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped table-condensed text-center">
              <thead>
                    <tr>
                        <td>#</td>
                        <td>Date</td>
                        <td>State</td>
                        <td>Show QSOs</td>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line->date);
        echo '<tr>
                <td>' . $i-- . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line->col_state . '</td>
                <td><a href=javascript:displayTimelineContacts("' . $line->col_state . '","'. $bandselect . '","'. $modeselect . '","' . $award .'")>Show</a></td>
               </tr>';
    }
    echo '</tfoot></table></div>';
}

function write_iota_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $i = count($timeline_array);
    echo '<table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped table-condensed text-center">
              <thead>
                    <tr>
                        <td>#</td>
                        <td>Date</td>
                        <td>Iota</td>
                        <td>Name</td>
                        <td>Prefix</td>
                        <td>Show QSOs</td>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line->date);
        echo '<tr>
                <td>' . $i-- . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line->col_iota . '</td>
                <td>' . $line->name . '</td>
                <td>' . $line->prefix . '</td>
                <td><a href=javascript:displayTimelineContacts("' . $line->col_iota . '","'. $bandselect . '","'. $modeselect . '","' . $award .'")>Show</a></td>
               </tr>';
    }
    echo '</tfoot></table></div>';
}

function write_waz_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $i = count($timeline_array);
    echo '<table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped table-condensed text-center">
              <thead>
                    <tr>
                        <td>#</td>
                        <td>Date</td>
                        <td>CQ Zone</td>
                        <td>Show QSOs</td>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line->date);
        echo '<tr>
                <td>' . $i-- . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line->col_cqz . '</td>
                <td><a href=javascript:displayTimelineContacts("' . $line->col_cqz . '","'. $bandselect . '","'. $modeselect . '","' . $award .'")>Show</a></td>
               </tr>';
    }
    echo '</tfoot></table></div>';
}