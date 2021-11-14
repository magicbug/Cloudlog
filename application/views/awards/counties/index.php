<div class="container">
    <h2><?php echo $page_title; ?></h2>
    <?php if ($counties_array) { ?>
    <table  style="width:100%" class="countiestable table table-sm table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>State</td>
            <td>Counties Worked</td>
            <td>Counties Confirmed</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $worked = 0;
        $confirmed = 0;
        foreach($counties_array as $counties) {
            echo '<tr>';
            echo '<td>' . $counties['COL_STATE'] .'</td>';
            echo '<td><a href=\'counties_details?State="'.$counties['COL_STATE'].'"&Type="worked"\'>'. $counties['countycountworked'] .'</a></td>';
            echo '<td><a href=\'counties_details?State="'.$counties['COL_STATE'].'"&Type="confirmed"\'>'. $counties['countycountconfirmed'] .'</a></td>';
            echo '</tr>';
            $worked += $counties['countycountworked'];
            $confirmed += $counties['countycountconfirmed'];
        }
        ?><tfoot><tr>
            <td>Total</td>
            <td><a href=counties_details?State="All"&Type="worked"><?php echo $worked ?></a></td>
            <td><a href=counties_details?State="All"&Type="confirmed"><?php echo $confirmed ?></a></td>
        </tr></tfoot>
        </tbody>
    </table>
    <?php } else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>
</div>