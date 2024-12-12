<script>
    var lang_days_with_qso = "<?php echo lang('statistics_days_with_qso'); ?>";
    var lang_qsos_this_day = "<?php echo lang('statistics_number_of_qsos_this_day'); ?>";
    var lang_qsos_this_weekday = "<?php echo lang('statistics_number_of_qsos_this_weekday'); ?>";
</script>

<div class="container">
    <br>
    <h2><?php echo $page_title; ?></h2>

    <br>
	<div class="tabs">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo lang('statistics_tab_yearly'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="daysofweek-tab" data-bs-toggle="tab" href="#daysofweek" role="tab" aria-controls="daysofweek" aria-selected="false"><?php echo lang('statistics_tab_weekdays'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="streaks-tab" data-bs-toggle="tab" href="#streaks" role="tab" aria-controls="streaks" aria-selected="false"><?php echo lang('statistics_tab_streaks'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="daily-tab" data-bs-toggle="tab" href="#daily" role="tab" aria-controls="daily" aria-selected="false"><?php echo lang('statistics_tab_daily'); ?></a>
			</li>
		</ul>
	</div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <br/>
            <h3><?php echo lang('statistics_days_yearly'); ?></h3>
            <br/>
            <?php
            if (is_array($result)) {
            echo '<div id="diffDays" class="table-responsive"><table class="qsotable table table-sm table-bordered table-hover table-striped table-condensed">';

                    echo '<tr>';
                        echo '<th style=\'text-align: center\'>' . lang('general_word_year') . '</th>';

                        foreach ($result as $master) {
                        echo '<td style=\'text-align: center\'>' . $master->Year . '</td>';
                        }

                        echo '</tr>';

                    echo '<tr>';
                        echo '<th style=\'text-align: center\'>' . lang('general_word_days') . '</th>';

                        foreach ($result as $master) {
                        echo '<td style=\'text-align: center\'>' . $master->Days . '</td>';
                        }

                        echo '</tr>';

                    echo '</table></div>';
            }
            ?>
            <canvas id="myChartDiff" width="400" height="150"></canvas>
        </div>

        <div class="tab-pane fade" id="daysofweek" role="tabpanel" aria-labelledby="daysofweek-tab">
            <br/>
            <h3><?php echo lang('statistics_weekdays_with_qso'); ?></h3>
            <canvas id="weekdaysChart" width="400" height="150"></canvas>
        </div>

        <div class="tab-pane fade" id="streaks" role="tabpanel" aria-labelledby="streaks-tab">
            <br/>
            <h3><?php echo lang('statistics_dwq_longest_streak_in_log'); ?></h3>
            <p><?php echo lang('statistics_dwq_longest_streak_in_log_hint'); ?></p>

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
                echo '<div id="streaks" class="table-responsive"><table class="qsotable table table-sm table-bordered table-hover table-striped table-condensed">';

                    echo '<tr>';
                        echo '<th style=\'text-align: center\'>' . lang('statistics_dwq_streak_continuous_days') . '</th>';
                        echo '<th style=\'text-align: center\'>' . lang('general_word_startdate') . '</th>';
                        echo '<th style=\'text-align: center\'>' . lang('general_word_enddate') . '</th>';
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
                echo '<div class="alert alert-danger" role="alert">No streak found!</div>';
            }
            ?>

            <h3><?php echo lang('statistics_dwq_current_streak_in_log'); ?></h3>
            <?php
            if (is_array($currentstreak)) {
                echo '<div id="streaks" class="table-responsive"><table class="qsotable table table-sm table-bordered table-hover table-striped table-condensed">';

                echo '<tr>';
                echo '<th style=\'text-align: center\'>' . lang('statistics_dwq_current_streak_continuous_days') . '</th>';
                echo '<th style=\'text-align: center\'>' . lang('general_word_startdate') . '</th>';
                echo '<th style=\'text-align: center\'>' . lang('general_word_enddate') . '</th>';
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
                <div class="alert alert-warning" role="alert"><?php echo lang('statistics_dwq_make_qso_to_extend_streak'); ?></div>
                <?php
                echo '<div id="streaks" class="table-responsive"><table class="qsotable table table-sm table-bordered table-hover table-striped table-condensed">';

                echo '<tr>';
                echo '<th style=\'text-align: center\'>' . lang('statistics_dwq_current_streak_continuous_days') . '</th>';
                echo '<th style=\'text-align: center\'>' . lang('general_word_startdate') . '</th>';
                echo '<th style=\'text-align: center\'>' . lang('general_word_enddate') . '</th>';
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
                echo '<div class="alert alert-danger" role="alert">' . lang('statistics_dwq_no_current_streak') . '</div>';
            }
            ?>
        </div>

        <div class="tab-pane fade" id="daily" role="tabpanel" aria-labelledby="daily-tab">
            <br/>
            <h3><?php echo lang('statistics_qsos_each_day'); ?></h3>
            <canvas id="dailyChart" width="400" height="150"></canvas>
        </div>
    </div>
</div>
