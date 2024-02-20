<p>Data is collected by Cloudlog from multiple sources.</p>

<?php
$grouped = [];

// Step 2: Iterate over $dxcclist.
foreach ($dxcclist as $dxcc) {
    // Get the month from the start date.
    $month = date('F Y', strtotime($dxcc['clean_date']));

    // Check if this month already exists in $grouped.
    if (!isset($grouped[$month])) {
        // If it doesn't, create a new array for it.
        $grouped[$month] = [];
    }

    // Add the current item to the array for its month.
    $grouped[$month][] = $dxcc;
}

// Step 5: Iterate over $grouped to create a table for each month.
foreach ($grouped as $month => $dxccs) {
    echo "<h3>$month</h3>";
    echo '<table class="table table-striped table-hover">';
    echo '<tr>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Country</td>
        <td>Callsign</td>
        <td></td>
        <td>Notes</td>
    </tr>';

    foreach ($dxccs as $dxcc) {
        echo '<tr>
            <td>' . $dxcc['start_date'] . '</td>
            <td>' . $dxcc['end_date'] . '</td>
            <td>' . $dxcc['country'] . '</td>
            <td>' . $dxcc['callsign'] . '</td>
            <td>';

        if (!$dxcc['workedBefore']) {
            echo '<span class="badge bg-danger">Not Worked Before</span>';
        } else {
            echo '<span class="badge bg-success">Worked Before</span>';
        }

        if ($dxcc['confirmed']) {
            echo '<span class="badge bg-primary">Confirmed</span>';
        }

        echo '</td>
            <td>' . $dxcc['notes'] . '</td>
        </tr>';
    }

    echo '</table>';
}
