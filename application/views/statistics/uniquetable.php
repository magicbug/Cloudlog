<?php
if ($qsoarray) {
    echo '<br />
        <table style="width:100%" class="uniquetable table-sm table table-bordered table-hover table-striped table-condensed text-center">
            <thead>';
                    echo '<tr><th></th>';
                    foreach($bands as $band) {
                        echo '<th>' . $band . '</th>';
                    }
                    echo '<th>Total</th>';
                    echo '</tr>
            </thead>
            <tbody>';
    foreach ($qsoarray as $mode => $value) {
        echo '<tr>
                <th>'. $mode .'</th>';
        foreach ($value as $key => $val) {
            echo '<td>' . $val . '</td>';
        }
        echo '<th>' . $modeunique[$mode] . '</th>';
        echo '</tr>';
    }
    echo '</tbody><tfoot><tr><th>Total</th>';

    foreach($bands as $band) {
        echo '<th>' . $bandunique[$band] . '</th>';
    }
    echo '<th>' . $total->calls . '</th>';
    echo '</tr></tfoot></table>';
}