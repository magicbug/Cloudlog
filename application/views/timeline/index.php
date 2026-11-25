<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => lang('statistics_timeline'),
        'subtitle' => 'Track first worked entities over time and inspect the related QSOs.',
        'icon' => 'fas fa-stream',
    )); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h2 class="h5 mb-0">Filters</h2>
        </div>
        <div class="card-body">
            <form class="form" action="<?php echo site_url('timeline'); ?>" method="post" enctype="multipart/form-data">
                <div class="row g-3 align-items-end mb-2">
                    <div class="col-md-4">
                        <label class="form-label" for="band"><?php echo lang('gen_hamradio_band') ?></label>
                        <select id="band" name="band" class="form-select">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>><?php echo lang('general_word_all') ?></option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '"';
                                if ($this->input->post('band') == $band) echo ' selected';
                                echo '>' . $band . '</option>' . "\n";
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="mode"><?php echo lang('gen_hamradio_mode') ?></label>
                        <select id="mode" name="mode" class="form-select">
                            <option value="All" <?php if ($this->input->post('mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>><?php echo lang('general_word_all') ?></option>
                            <?php
                            foreach($modes->result() as $mode){
                                if ($mode->submode == null) {
                                    echo '<option value="' . $mode->mode . '"';
                                    if ($this->input->post('mode') == $mode->mode) echo ' selected';
                                    echo '>' . $mode->mode . '</option>' . "\n";
                                } else {
                                    echo '<option value="' . $mode->submode . '"';
                                    if ($this->input->post('mode') == $mode->submode) echo ' selected';
                                    echo '>' . $mode->submode . '</option>' . "\n";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="award"><?php echo lang('gen_hamradio_award') ?></label>
                        <select id="award" name="award" class="form-select">
                            <option value="dxcc" <?php if ($this->input->post('award') == "dxcc") echo ' selected'; ?>>DX Century Club (DXCC)</option>
                            <option value="was" <?php if ($this->input->post('award') == "was") echo ' selected'; ?>>Worked All States (WAS)</option>
                            <option value="iota" <?php if ($this->input->post('award') == "iota") echo ' selected'; ?>>Islands On The Air (IOTA)</option>
                            <option value="waz" <?php if ($this->input->post('award') == "waz") echo ' selected'; ?>>Worked All Zones (WAZ)</option>
                            <option value="vucc" <?php if ($this->input->post('award') == "vucc") echo ' selected'; ?>>VHF / UHF Century Club (VUCC)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label d-block mb-2"><?php echo lang('general_word_confirmation') ?></label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="qsl" value="1" id="qsl" <?php if ($this->input->post('qsl'))  echo ' checked="checked"'; ?>>
                                <label class="form-check-label" for="qsl"><?php echo lang('gen_hamradio_qsl') ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="lotw" value="1" id="lotw" <?php if ($this->input->post('lotw')) echo ' checked="checked"'; ?>>
                                <label class="form-check-label" for="lotw"><?php echo lang('general_word_lotw_short') ?></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="eqsl" value="1" id="eqsl" <?php if ($this->input->post('eqsl')) echo ' checked="checked"'; ?>>
                                <label class="form-check-label" for="eqsl"><?php echo lang('eqsl_short') ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex justify-content-md-end gap-2">
                        <a href="<?php echo site_url('timeline'); ?>" class="btn btn-outline-secondary">Reset</a>
                        <button id="button1id" type="submit" name="button1id" class="btn btn-primary"><?php echo lang('filter_options_show') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
    $selected_award = $this->input->post('award') ?: 'dxcc';

    if ($timeline_array) {
        echo '<div class="card shadow-sm">';
        echo '<div class="card-header d-flex align-items-center justify-content-between">';
        echo '<h2 class="h5 mb-0">'.$this->lang->line('statistics_timeline').'</h2>';
        echo '<span class="badge text-bg-primary rounded-pill">'.count($timeline_array).'</span>';
        echo '</div><div class="card-body">';

        if ($selected_award === 'dxcc') {
            echo '<div class="alert alert-info py-2 px-3 mb-3" role="alert">';
            echo '<strong>DXCC column guide:</strong> <strong>Status</strong> marks entities that are now deleted from DXCC. <strong>End Date</strong> is the DXCC deletion/end date for that entity.';
            echo '</div>';
        }

        switch ($selected_award) {
            case 'dxcc': $result = write_dxcc_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $selected_award); break;
            case 'was':  $result = write_was_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $selected_award); break;
            case 'iota': $result = write_iota_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $selected_award); break;
            case 'waz':  $result = write_waz_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $selected_award); break;
            case 'vucc':  $result = write_vucc_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $selected_award); break;
        }
        echo '</div></div>';
    }
    else {
        echo '<div class="alert alert-warning" role="alert">Nothing found!</div>';
    }
    ?>

    <div class="modal fade" id="timelineDetailsModal" tabindex="-1" aria-labelledby="timelineDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="timelineDetailsModalLabel"><?php echo lang('general_word_qso_data'); ?></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="timelineDetailsBody">
                    <div class="d-flex justify-content-center py-4">
                        <div class="spinner-border" role="status" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php

function write_dxcc_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $ci =& get_instance();
    $i = count($timeline_array);
    
    // Check if any DXCC entries have an end date
    $has_end_dates = false;
    foreach ($timeline_array as $line) {
        if (!empty($line->end)) {
            $has_end_dates = true;
            break;
        }
    }
    
    echo '<div class="table-responsive"><table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped align-middle text-center mb-0">
              <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date Worked</th>
                        <th scope="col">'.$ci->lang->line('gen_hamradio_prefix').'</th>
                        <th scope="col">'.$ci->lang->line('general_word_country').'</th>
                        <th scope="col">DXCC Status</th>';
    if ($has_end_dates) {
        echo '<th scope="col">DXCC End Date</th>';
    }
    echo '<th scope="col">'.$ci->lang->line('gridsquares_show_qsos').'</th>
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
        if (!empty($line->end)) {
            echo '<span class="badge text-bg-danger">'.$ci->lang->line('gen_hamradio_deleted_dxcc').'</span>';
        } else {
            echo '<span class="badge text-bg-success">Active</span>';
        }
        echo '</td>';
        if ($has_end_dates) {
            echo '<td>' . $line->end . '</td>';
        }
        echo '<td><button type="button" class="btn btn-sm btn-outline-primary" onclick="displayTimelineContacts(\'' . $line->adif . '\',\'' . $bandselect . '\',\'' . $modeselect . '\',\'' . $award . '\')">'.$ci->lang->line('filter_options_show').'</button></td>
               </tr>';
    }
    echo '</tbody></table></div>';
}

function write_was_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $ci =& get_instance();
    $i = count($timeline_array);
    echo '<div class="table-responsive"><table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped align-middle text-center mb-0">
              <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date Worked</th>
                        <th scope="col">'.$ci->lang->line('gen_hamradio_state').'</th>
                        <th scope="col">'.$ci->lang->line('gridsquares_show_qsos').'</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line->date);
        echo '<tr>
                <td>' . $i-- . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line->col_state . '</td>
                <td><button type="button" class="btn btn-sm btn-outline-primary" onclick="displayTimelineContacts(\'' . $line->col_state . '\',\'' . $bandselect . '\',\'' . $modeselect . '\',\'' . $award . '\')">'.$ci->lang->line('filter_options_show').'</button></td>
               </tr>';
    }
    echo '</tbody></table></div>';
}

function write_iota_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $ci =& get_instance();
    $i = count($timeline_array);
    echo '<div class="table-responsive"><table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped align-middle text-center mb-0">
              <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date Worked</th>
                        <th scope="col">'.$ci->lang->line('gen_hamradio_iota').'</th>
                        <th scope="col">'.$ci->lang->line('general_word_name').'</th>
                        <th scope="col">'.$ci->lang->line('gen_hamradio_prefix').'</th>
                        <th scope="col">'.$ci->lang->line('gridsquares_show_qsos').'</th>
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
                <td><button type="button" class="btn btn-sm btn-outline-primary" onclick="displayTimelineContacts(\'' . $line->col_iota . '\',\'' . $bandselect . '\',\'' . $modeselect . '\',\'' . $award . '\')">'.$ci->lang->line('filter_options_show').'</button></td>
               </tr>';
    }
    echo '</tbody></table></div>';
}

function write_waz_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $ci =& get_instance();
    $i = count($timeline_array);
    echo '<div class="table-responsive"><table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped align-middle text-center mb-0">
              <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date Worked</th>
                        <th scope="col">'.$ci->lang->line('gen_hamradio_cqzone').'</th>
                        <th scope="col">'.$ci->lang->line('gridsquares_show_qsos').'</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line->date);
        echo '<tr>
                <td>' . $i-- . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line->col_cqz . '</td>
                <td><button type="button" class="btn btn-sm btn-outline-primary" onclick="displayTimelineContacts(\'' . $line->col_cqz . '\',\'' . $bandselect . '\',\'' . $modeselect . '\',\'' . $award . '\')">'.$ci->lang->line('filter_options_show').'</button></td>
               </tr>';
    }
    echo '</tbody></table></div>';
}

function write_vucc_timeline($timeline_array, $custom_date_format, $bandselect, $modeselect, $award) {
    $ci =& get_instance();
    $i = count($timeline_array);
    echo '<div class="table-responsive"><table style="width:100%" class="table table-sm timelinetable table-bordered table-hover table-striped align-middle text-center mb-0">
              <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date Worked</th>
                        <th scope="col">'.$ci->lang->line('gen_hamradio_gridsquare').'</th>
                        <th scope="col">'.$ci->lang->line('gridsquares_show_qsos').'</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($timeline_array as $line) {
        $date_as_timestamp = strtotime($line['date']);
        echo '<tr>
                <td>' . $i-- . '</td>
                <td>' . date($custom_date_format, $date_as_timestamp) . '</td>
                <td>' . $line['gridsquare'] . '</td>
                <td><button type="button" class="btn btn-sm btn-outline-primary" onclick="displayTimelineContacts(\'' . $line['gridsquare'] . '\',\'' . $bandselect . '\',\'' . $modeselect . '\',\'' . $award . '\')">'.$ci->lang->line('filter_options_show').'</button></td>
               </tr>';
    }
    echo '</tbody></table></div>';
}
