<?php
if ($qsoarray) {
    echo '<br />
        <table style="width:100%" class="qsotable table-sm table table-bordered table-hover table-striped table-condensed text-center">
            <thead>';
                    echo '<tr><th></th>';
                    foreach($bands as $band) {
                        echo '<th>' . $band . '</th>';
                    }
                    echo '<th>'.lang('statistics_total').'</th>';
                    echo '</tr>
            </thead>
            <tbody>';
    foreach ($qsoarray as $mode => $value) {
        echo '<tr>
                <th>'. $mode .'</th>';
        foreach ($value as $key => $val) {
            echo '<td>' . $val . '</td>';
        }
        echo '<th>' . (isset($modetotal[$mode]) ? $modetotal[$mode] : 0) . '</th>';
        echo '</tr>';
    }
    echo '</tbody><tfoot><tr><th>'.lang('statistics_total').'</th>';

    $grandtotal = 0;
    foreach ($bandtotal as $band => $value) {
            echo '<th>' . $value . '</th>';
            $grandtotal += $value;
    }
    echo '<th>' . $grandtotal . '</th>';
    echo '</tr></tfoot></table>';
}
