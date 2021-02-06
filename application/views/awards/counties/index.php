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
        foreach($counties_array as $band => $counties) {
            echo '<tr>';
            echo '<td>' . $counties['COL_STATE'] .'</td>';
            echo '<td><a href=\'counties?Type="worked"\'>'. $counties['countycountworked'] .'</td>';
            echo '<td><a href=\'counties?Type="confirmed"\'>'. $counties['countycountconfirmed'] .'</td>';
            echo '</tr>';
            $worked += $counties['countycountworked'];
            $confirmed += $counties['countycountconfirmed'];
        }
        ?><tfoot><tr>
        <td>Total</td>
        <td><?php echo $worked ?></td>
        <td><?php echo $confirmed ?></td>
        </tr></tfoot>
        </tbody>
    </table>
    <?php } ?>
</div>