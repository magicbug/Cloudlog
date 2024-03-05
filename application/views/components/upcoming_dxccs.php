<table id="upcoming_dxccs_component" class="table table-striped border-top">
    <tr class="titles">
        <td colspan="3"><i class="fas fa-chart-bar"></i> DXPeditions (This Week)</td>
    </tr>

    <?php
    foreach ($thisWeekRecords as $record) {
        $name = $record['workedBefore'] == 1 ? 'worked_before' : 'not_worked_before';
        echo '<tr>';
        echo '<td id="' . $name . '">' . $record['daysLeft'] . '</td>'; // Date
        echo '<td id="' . $name . '">' . '<a target="_blank" href="https://dxheat.com/db/'.$record['callsign'].'" data-bs-toggle="tooltip" data-bs-title="'.$record['6'].'">'.$record['callsign'] . '</a>'. '</td>'; // Callsign
        echo '<td id="' . $name . '">' . $record['2'] . '</td>'; // Country
        echo '</tr>';
    }
    ?>
</table>