<?php
if ($qsoarray) {
    echo '<br />
        <table style="width:100%" class="uniquetable table-sm table table-bordered table-hover table-striped table-condensed text-center">
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
        echo '<th>' . (isset($modeunique[$mode]) ? $modeunique[$mode] : 0) . '</th>';
        echo '</tr>';
    }
    echo '</tbody><tfoot><tr><th>'.lang('statistics_total').'</th>';

    foreach($bands as $band) {
        echo '<th>' . (isset($bandunique[$band]) ? $bandunique[$band] : 0) . '</th>';
    }
    echo '<th>' . $total->calls . '</th>';
    echo '</tr></tfoot></table>';
}