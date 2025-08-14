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

        // Add satellite badge if worked via satellite
        if (isset($dxcc['workedViaSatellite']) && $dxcc['workedViaSatellite']) {
            echo ' <span class="badge bg-info">Worked via Satellite</span>';
        }

        // IOTA handling: show badge if JSON contained an iota field
        if (isset($dxcc['iota']) && !empty($dxcc['iota'])) {
            $iotaTag = $dxcc['iota'];
            $mapUrl = 'https://www.iota-world.org/iotamaps/?grpref=' . $iotaTag;
            // Anchor inside badge should inherit readable text colour
            $iotaAnchor = '<a href="' . $mapUrl . '" target="_blank" class="text-white text-decoration-none">' . $iotaTag . '</a>';

            if (isset($dxcc['iota_status'])) {
                $s = $dxcc['iota_status'];

                if (!empty($s) && isset($s['worked']) && $s['worked']) {
                    echo ' <span class="badge bg-success">IOTA ' . $iotaAnchor . ' Worked</span>';
                } else {
                    echo ' <span class="badge bg-danger">IOTA ' . $iotaAnchor . ' Needed</span>';
                }

                if (!empty($s) && isset($s['confirmed']) && $s['confirmed']) {
                    echo ' <span class="badge bg-primary">Confirmed</span>';
                }
            } else {
                // No status; render a neutral badge containing the link
                echo ' <span class="badge bg-secondary">' . $iotaAnchor . '</span>';
            }
        }

        echo '</td>
            <td>' . $dxcc['notes'] . '</td>
        </tr>';
    }

    echo '</table>';
}
