<div class="container">
    <h1><?php echo lang('statistics_emeinitials'); ?></h1>

    <form class="form" action="<?php echo site_url('emeinitials'); ?>" method="post" enctype="multipart/form-data">
        <!-- Select Basic -->
                <div class="mb-3 row">
                    <label class="col-md-1 control-label" for="band"><?php echo lang('gen_hamradio_band') ?></label>
                    <div class="col-md-3">
                        <select id="band" name="band" class="form-select">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> ><?php echo lang('general_word_all') ?></option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '"';
                                if ($this->input->post('band') == $band) echo ' selected';
                                echo '>' . $band . '</option>'."\n";
                            } ?>
                        </select>
                    </div>

                    <label class="col-md-1 control-label" for="mode"><?php echo lang('gen_hamradio_mode') ?></label>
                    <div class="col-md-3">
                        <select id="mode" name="mode" class="form-select">
                            <option value="All" <?php if ($this->input->post('mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> ><?php echo lang('general_word_all') ?></option>
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

            <div class="mb-3 row">
                <label class="col-md-1 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button1id" type="submit" name="button1id" class="btn btn-primary"><?php echo lang('filter_options_show') ?></button>
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
         $result = write_initials_data($timeline_array, $custom_date_format, $bandselect, $modeselect);
    }
    else {
        echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
    }
    ?>

</div>

<?php

function write_initials_data($timeline_array, $custom_date_format, $bandselect, $modeselect) {
    $ci =& get_instance();
    $i = 1;
    echo '<table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped table-condensed text-center">
              <thead>
                    <tr>
                        <td>#</td>
                        <td>'.$ci->lang->line('gen_hamradio_callsign').'</td>
                        <td>'.$ci->lang->line('statistics_first_qso').'</td>
                        <td>'.$ci->lang->line('gen_hamradio_gridsquare').'</td>
                        <td>'.$ci->lang->line('gen_hamradio_state').'</td>
                        <td>'.$ci->lang->line('statistics_times_worked').'</td>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line->date);
        echo '<tr>
                <td>' . $i++ . '</td>
                <td>' . $line->callsign . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line->gridsquare . '</td>
                <td>' . $line->state . '</td>
                <td>' . $line->count . '</td>
               </tr>';
    }
    echo '</tbody></table>';
}
