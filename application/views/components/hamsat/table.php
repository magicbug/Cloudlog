<div class="table-responsive">

    <h2>Hamsat - Satellite Rovers</h2>
    <p>This data is from <a target="_blank" href="https://hams.at/">https://hams.at/</a></p>
    <?php if ($rovedata == []) { ?>
     <div class="alert alert-warning" role="warning">
       <?php echo lang('hams_at_no_activations_found');?>
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
                <th>Gridsquare(s)</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rovedata as $rove) : ?>
                <tr>
                    <td>
                        <?php

                        // Get Date format
                        if ($this->session->userdata('user_date_format')) {
                            // If Logged in and session exists
                            $custom_date_format = $this->session->userdata('user_date_format');
                        } else {
                            // Get Default date format from /config/cloudlog.php
                            $custom_date_format = $this->config->item('qso_date_format');
                        }

                        ?>

                        <?php $timestamp = strtotime($rove['date']);
                           echo date($custom_date_format, $timestamp); ?>

                    </td>
                    <td>
                        <?php echo $rove['start_time']." - ".$rove['end_time']; ?>
                    </td>
                    <td>
                        <?php
                        $CI = &get_instance();
			$CI->load->model('logbooks_model');
			$logbooks_locations_array = $CI->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
                        $CI->load->model('logbook_model');
                        $call_worked = $CI->logbook_model->check_if_callsign_worked_in_logbook($rove['callsign'], $logbooks_locations_array, "SAT");
                        if ($call_worked != 0) {
                            echo "<span class=\"text-success\">".$rove['callsign']."</span>";
                        } else {
                            echo $rove['callsign'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        echo xss_clean($rove['comment']);
                        ?>
                    </td>
                    <td><span data-toggle="tooltip" title="<?php echo $rove['frequency']; ?> - <?php echo $rove['mode']; ?>"><?= $rove['satellite'] ?></span></td>
                    <td>


                        <?php

                        // Load the logbook model and call check_if_grid_worked_in_logbook
                        if (strpos($rove['gridsquare'], '/') !== false) {
                           $grids = explode('/', $rove['gridsquare']);
                           foreach ($grids as $grid) {
                           $worked = $CI->logbook_model->check_if_grid_worked_in_logbook($grid, null, "SAT");
                              if ($worked != 0) {
                                  echo " <span data-toggle=\"tooltip\" title=\"Worked\" class=\"badge badge-success\">" . $grid . "</span>";
                              } else {
                                  echo " <span data-toggle=\"tooltip\" title=\"Not Worked\" class=\"badge badge-danger\">" . $grid . "</span>";
                              }
                           }
                        } else {
                           $worked = $CI->logbook_model->check_if_grid_worked_in_logbook($rove['gridsquare'], null, "SAT");
                           if ($worked != 0) {
                               echo " <span data-toggle=\"tooltip\" title=\"Worked\" class=\"badge badge-success\">" . $rove['gridsquare'] . "</span>";
                           } else {
                               echo " <span data-toggle=\"tooltip\" title=\"Not Worked\" class=\"badge badge-danger\">" . $rove['gridsquare'] . "</span>";
                           }
                        }
                        ?>


                    </td>
                    <td><a href="<?php echo $rove['track_link']; ?>" target="_blank">Track</a></td>
                    <?php
                        $sat = $rove['satellite'];
                        switch (strtoupper($rove['satellite'])) {
                        case "GREENCUBE":
                           $sat = 'IO-117';
                           break;
                        }
                    ?>
                    <td><a href="https://sat.fg8oj.com/sked.php?s%5B%5D=<?php echo $sat; ?>&l=<?php echo strtoupper($gridsquare); ?>&el1=0&l2=<?php echo $rove['gridsquare']; ?>&el2=0&duration=1&start=0&OK=Search" target="_blank">Sked</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php } ?>
</div>
