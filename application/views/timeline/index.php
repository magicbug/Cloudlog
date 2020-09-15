<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <form class="form" action="<?php echo site_url('timeline'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <!-- Select Basic -->
            <div class="form-group row">
                <label class="col-md-1 control-label" for="band">Band</label>
                <div class="col-md-2">
                    <select id="band2" name="band" class="form-control">
                        <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >All</option>
                        <?php foreach($worked_bands as $band) {
                            echo '<option value="' . $band . '"';
                            if ($this->input->post('band') == $band) echo ' selected';
                            echo '>' . $band . '</option>'."\n";
                        } ?>
                    </select>
                </div>
            </div>

            <!-- Button (Double) -->
            <div class="form-group row">
                <label class="col-md-1 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button1id" type="submit" name="button1id" class="btn btn-success btn-primary">Show</button>
                </div>
            </div>

        </fieldset>
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
    $i = count($dxcc_timeline_array);
    if ($dxcc_timeline_array) {
        echo '<table class="table table-bordered table-hover table-striped table-condensed text-center">
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

        foreach ($dxcc_timeline_array as $line) {
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
                <td><a href=\'timeline\details?Adif="' . $line->adif . '"&Band="'. $bandselect . '"\'>Show</a></td>
               </tr>';
        }
        echo '</tfoot></table></div>';
    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>

</div>