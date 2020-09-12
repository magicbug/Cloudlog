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
                echo '<td style=\'text-align: center\'>' . $streak['beginstreak'] . '</td>';
                echo '<td style=\'text-align: center\'>' . $streak['endstreak'] . '</td>';
                echo '</tr>';
            }

            echo '</table></div>';
    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No streak found!</div>';
    }
    ?>
</div>
