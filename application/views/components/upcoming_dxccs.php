<table class="table table-striped border-top">
    <tr class="titles">
        <td colspan="3"><i class="fas fa-chart-bar"></i> DXPeditions (This Week)</td>
    </tr>

    <?php
    foreach ($thisWeekRecords as $record) {
        $color = $record['workedBefore'] == 1 ? '#ddffdd' : '#ffdddd';
        echo '<tr>';
        echo '<td style="background-color: ' . $color . ';" width="33%">' . $record['daysLeft'] . '</td>'; // Date
        echo '<td style="background-color: ' . $color . ';" width="33%">' . $record['callsign'] . '</td>'; // Callsign
        echo '<td style="background-color: ' . $color . ';" width="33%">' . $record['2'] . '</td>'; // Country
        echo '</tr>';
    }
    ?>
</table>