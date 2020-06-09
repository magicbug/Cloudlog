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
</div>
