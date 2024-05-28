<div class="table-responsive">
    <br>
    <h2>Hamsat - Satellite Rovers</h2>
    <p>This data is from <a target="_blank" href="https://hams.at/">https://hams.at/</a>.
        <?php if ($user_hamsat_workable_only) {
            echo " Only workable passes shown.";
        } else {
            echo " All passes shown.";
        } ?>
    </p>
    <?php if ($user_hamsat_workable_only && $user_hamsat_key == '') { ?>
        <div class="alert alert-warning" role="warning">
            Private feed key empty. Please set the feed key in your profile.
        </div>
    <?php } else { ?>
        <?php if ($rovedata['data'] == []) { ?>
            <div class="alert alert-warning" role="warning">
                <?php echo lang('hams_at_no_activations_found'); ?>
            </div>
        <?php } else { ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Callsign</th>
                        <th>Comment</th>
                        <th>Satellite</th>
                        <th>Mode</th>
                        <th>Gridsquare(s)</th>
                        <th>Workable</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rovedata['data'] as $rove) :
                        if ($user_hamsat_workable_only) {
                            if (!$rove['is_workable']) {
                                continue;
                            }
                        } ?>
                        <tr>
                            <td>
                                <?php

                                // Get Date format
                                if ($this->session->userdata('user_date_format')) {
                                    // If Logged in and session exists
                                    $custom_date_format = $this->session->userdata('user_date_format');
                                } else {
                                    // Get Default date format from /config/wavelog.php
                                    $custom_date_format = $this->config->item('qso_date_format');
                                }

                                ?>

                                <?php $timestamp = strtotime($rove['aos_at']);
                                echo date($custom_date_format, $timestamp); ?>

                            </td>
                            <td>
                                <?php echo date("H:i:s", $timestamp) . " - " . date("H:i:s", strtotime($rove['los_at'])); ?>
                            </td>
                            <td>
                                <?php
                                $CI = &get_instance();
                                $CI->load->model('logbooks_model');
                                $logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
                                $CI->load->model('logbook_model');
                                $call_worked = $CI->logbook_model->check_if_callsign_worked_in_logbook($rove['callsign'], $logbooks_locations_array, "SAT");
                                if ($call_worked != 0) {
                                    echo "<span class=\"text-success\">" . $rove['callsign'] . "</span>";
                                } else {
                                    echo $rove['callsign'];
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                echo xss_clean($rove['comment'] ?? '-');
                                ?>
                            </td>
                            <?php
                            $direction = '';
                            if ($rove['mhz_direction'] == 'up') {
                                $direction = '&uarr;';
                            } else if ($rove['mhz_direction'] == 'down') {
                                $direction = '&darr;';
                            }
                            $modeclass = '';
                            if ($rove['mode'] == 'SSB' || $rove['mode'] == 'CW') {
                                $modeclass = 'hamsatBgLin';
                            } else if ($rove['mode'] == 'Data') {
                                $modeclass = 'hamsatBgData';
                            } else if ($rove['mode'] == 'FM') {
                                $modeclass = 'hamsatBgFm';
                            }

                            ?>
                            <td><span data-bs-toggle="tooltip" title="<?php if ($rove['mhz'] != '') {
                                                                            printf("%.3f", $rove['mhz']);
                                                                            echo " " . $direction ?? '';
                                                                        } ?>"><?= $rove['satellite']['name'] ?></span></td>
                            <td><span title="<?php echo $rove['mode']; ?>" class="badge <?php echo $modeclass; ?>"><?php echo $rove['mode']; ?></span></td>
                            <td>


                                <?php
                                // Load the logbook model and call check_if_grid_worked_in_logbook
                                foreach ($rove['grids'] as $grid) {
                                    $worked = $CI->logbook_model->check_if_grid_4char_worked_in_logbook($grid, null, "SAT");
                                    if ($worked != 0) {
                                        echo " <span data-bs-toggle=\"tooltip\" title=\"Worked\" class=\"badge bg-success\">" . $grid . "</span>";
                                    } else {
                                        echo " <span data-bs-toggle=\"tooltip\" title=\"Not Worked\" class=\"badge bg-danger\">" . $grid . "</span>";
                                    }
                                }
                                ?>


                            </td>
                            <td>
                                <?php
                                if ($user_hamsat_key != '') {
                                    if ($rove['is_workable']) {
                                        echo date("H:i", strtotime($rove['workable_start_at'])) . " - " . date("H:i", strtotime($rove['workable_end_at']));
                                    } else {
                                        echo "<span data-bs-toggle=\"tooltip\" title=\"No\" class=\"badge bg-danger\">No</span>";
                                    }
                                } else {
                                    echo "<span data-bs-toggle=\"tooltip\" title=\"Unkown\" class=\"badge bg-warning\">Unknown</span>";
                                }
                                ?>
                            </td>
                            <td><a href="<?php echo $rove['url']; ?>" target="_blank">Track</a></td>
                            <?php
                            $sat = $rove['satellite']['name'];
                            switch (strtoupper($rove['satellite']['name'])) {
                                case "GREENCUBE":
                                    $sat = 'IO-117';
                                    break;
                            }
                            ?>
                            <td>
                                <?php
                                if ($rove['is_workable'] || $user_hamsat_key == '') { ?>
                                    <a href="https://sat.fg8oj.com/sked.php?s%5B%5D=<?php echo $sat; ?>&l=<?php echo strtoupper($gridsquare); ?>&el1=0&l2=<?php echo $rove['grids'][0]; ?>&el2=0&duration=1&start=0&OK=Search" target="_blank">Sked</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } ?>
    <?php } ?>
</div>