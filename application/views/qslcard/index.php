<div class="container">
    <h2><?php echo $page_title; ?></h2>

    <?php
    if (is_array($qslarray->result())) {
        echo '<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed">
        <thead>
        <tr>
        <th style=\'text-align: center\'>Callsign</th>
        <th style=\'text-align: center\'>QSL</th>
        <th style=\'text-align: center\'></th>
        <th style=\'text-align: center\'></th>
        </tr>
        </thead><tbody>';

        foreach ($qslarray->result() as $qsl) {
            echo '<tr>';
            echo '<td style=\'text-align: center\'>' . $qsl->COL_CALL . '</td>';
            echo '<td style=\'text-align: center\'>' . $qsl->filename . '</td>';
            echo '<td id="'.$qsl->id.'" style=\'text-align: center\'><button onclick="deleteQsl(\''.$qsl->id.'\')" class="btn btn-sm btn-danger">Delete</button></td>';
            echo '<td style=\'text-align: center\'><button onclick="viewQsl(\''.$qsl->filename.'\', \''. $qsl->COL_CALL . '\')" class="btn btn-sm btn-success">View</button></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
    ?>
</div>