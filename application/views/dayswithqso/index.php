<div class="container">

    <br>

    <h2><?php echo $page_title; ?></h2>

    <?php
    if (is_array($result)) {
    echo '<div id="diffDays" class="table-responsive"><table class="qsotable table table-bordered table-hover table-striped table-condensed">';

            echo '<tr>';
                echo '<th style=\'text-align: center\'>Year</th>';

                foreach ($result as $master) {
                echo '<td style=\'text-align: center\'>' . $master->Year . '</td>';
                }

                echo '</tr>';

            echo '<tr>';
                echo '<th style=\'text-align: center\'>Days</th>';

                foreach ($result as $master) {
                echo '<td style=\'text-align: center\'>' . $master->Days . '</td>';
                }

                echo '</tr>';

            echo '</table></div>';
    }
    ?>
    <canvas id="myChartDiff" width="400" height="150"></canvas>
    <h2>Longest streak with QSOs in the log</h2>
    <p>A maximum of the 10 longest streaks are shown!</p>

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
    if (is_array($streaks)) {
        echo '<div id="streaks" class="table-responsive"><table class="qsotable table table-bordered table-hover table-striped table-condensed">';

            echo '<tr>';
                echo '<th style=\'text-align: center\'>Streak (Continues days with QSOs)</th>';
                echo '<th style=\'text-align: center\'>Begin date</th>';
                echo '<th style=\'text-align: center\'>End date</th>';
                echo '</tr>';

            foreach ($streaks as $streak) {
                echo '<tr>';
                echo '<td style=\'text-align: center\'>' . $streak['highstreak'] . '</td>';
                $beginstreak_newdate = strtotime($streak['beginstreak']);
                echo '<td style=\'text-align: center\'>' . date($custom_date_format, $beginstreak_newdate) . '</td>';
                $endstreak_newdate = strtotime($streak['endstreak']);
                echo '<td style=\'text-align: center\'>' . date($custom_date_format, $endstreak_newdate) . '</td>';
                echo '</tr>';
            }

            echo '</table></div>';
    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No streak found!</div>';
    }
    ?>

    <h2>Current streak with QSOs in the log</h2>
    <?php
    if (is_array($currentstreak)) {
        echo '<div id="streaks" class="table-responsive"><table class="qsotable table table-bordered table-hover table-striped table-condensed">';

        echo '<tr>';
        echo '<th style=\'text-align: center\'>Current Streak (Continues days with QSOs)</th>';
        echo '<th style=\'text-align: center\'>Begin date</th>';
        echo '<th style=\'text-align: center\'>End date</th>';
        echo '</tr>';

            echo '<tr>';
            echo '<td style=\'text-align: center\'>' . $currentstreak['highstreak'] . '</td>';
            $beginstreak_newdate = strtotime($currentstreak['beginstreak']);
            echo '<td style=\'text-align: center\'>' . date($custom_date_format, $beginstreak_newdate) . '</td>';
            $endstreak_newdate = strtotime($currentstreak['endstreak']);
            echo '<td style=\'text-align: center\'>' . date($custom_date_format, $endstreak_newdate) . '</td>';
            echo '</tr>';

        echo '</table></div>';
    }
    elseif (is_array($almostcurrentstreak)) {
        ?>
        <p>If you make a QSO today, you can continue to extend your streak, else your current streak will be broken!</p>
        <?php
        echo '<div id="streaks" class="table-responsive"><table class="qsotable table table-bordered table-hover table-striped table-condensed">';

        echo '<tr>';
        echo '<th style=\'text-align: center\'>Current Streak (Continues days with QSOs)</th>';
        echo '<th style=\'text-align: center\'>Begin date</th>';
        echo '<th style=\'text-align: center\'>End date</th>';
        echo '</tr>';

        echo '<tr>';
        echo '<td style=\'text-align: center\'>' . $almostcurrentstreak['highstreak'] . '</td>';
        $beginstreak_newdate = strtotime($almostcurrentstreak['beginstreak']);
        echo '<td style=\'text-align: center\'>' . date($custom_date_format, $beginstreak_newdate) . '</td>';
        $endstreak_newdate = strtotime($almostcurrentstreak['endstreak']);
        echo '<td style=\'text-align: center\'>' . date($custom_date_format, $endstreak_newdate) . '</td>';
        echo '</tr>';

        echo '</table></div>';
    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No current streak found!</div>';
    }
    ?>
</div>
