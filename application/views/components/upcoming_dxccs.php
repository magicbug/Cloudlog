
<?php

if (isset($thisWeekRecords['error'])) {
?>
<div class="alert alert-warning" role="alert">
    <i class="fas fa-exclamation-triangle"></i> <?php echo $thisWeekRecords['error']; ?>
</div>
<?php } else { ?>

<?php
// Hide entries with unknown callsigns in the dashboard list.
$thisWeekRecords = array_values(array_filter($thisWeekRecords, function ($record) {
    $callsign = isset($record['callsign']) ? trim((string) $record['callsign']) : '';
    return strcasecmp($callsign, 'Unknown') !== 0;
}));

$pageSize = 8;
$recordCount = count($thisWeekRecords);
$totalPages = max(1, (int) ceil($recordCount / $pageSize));
?>

<div id="dxped-dashboard-card" data-pagesize="<?php echo $pageSize; ?>">
    <style>
        #dxped-dashboard-card table {
            --bs-table-striped-bg: transparent;
            --bs-table-accent-bg: transparent;
        }

        #dxped-dashboard-card .dxped-row > td {
            box-shadow: none !important;
        }

        #dxped-dashboard-card .dxped-row.dxped-worked > td {
            background-color: #cfe8cf !important;
        }

        #dxped-dashboard-card .dxped-row.dxped-needed > td {
            background-color: #f2d6d6 !important;
        }
    </style>

    <table class="table border-top mb-2">
        <tr class="titles">
            <td colspan="2"><i class="fas fa-chart-bar"></i> DXPeditions (This Week)</td>
            <td class="text-end">
                <a href="<?php echo site_url('workabledxcc'); ?>" class="btn btn-outline-secondary btn-sm">Full List</a>
            </td>
        </tr>

        <?php if ($recordCount === 0) { ?>
            <tr>
                <td colspan="3" class="text-muted">No DXPeditions found for this week.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($thisWeekRecords as $index => $record) {
                $isWorked = $record['workedBefore'] == 1;
                $rowClass = $isWorked ? 'dxped-worked' : 'dxped-needed';
                $displayCallsign = isset($record['callsign']) ? $record['callsign'] : 'Unknown';
                $tooltipNote = htmlspecialchars(isset($record['6']) ? $record['6'] : '', ENT_QUOTES, 'UTF-8');
                $page = (int) floor($index / $pageSize) + 1;
            ?>
                <tr class="dxped-row <?php echo $rowClass; ?>" data-page="<?php echo $page; ?>" <?php echo $page > 1 ? 'style="display:none;"' : ''; ?>>
                    <td><?php echo $record['daysLeft']; ?></td>
                    <td>
                        <?php if ($displayCallsign !== 'Unknown') { ?>
                            <a target="_blank" href="https://dxheat.com/db/<?php echo $displayCallsign; ?>" data-bs-toggle="tooltip" data-bs-title="<?php echo $tooltipNote; ?>"><?php echo $displayCallsign; ?></a>
                        <?php } else { ?>
                            <span data-bs-toggle="tooltip" data-bs-title="<?php echo $tooltipNote; ?>">Unknown</span>
                        <?php } ?>
                    </td>
                    <td><?php echo $record['2']; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        <?php if ($recordCount > $pageSize) { ?>
            <tfoot>
                <tr>
                    <td colspan="3" class="bg-body-tertiary">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Showing <span id="dxped-range">1-<?php echo min($pageSize, $recordCount); ?></span> of <?php echo $recordCount; ?></small>
                            <div class="btn-group btn-group-sm" role="group" aria-label="DXpedition pagination">
                                <button type="button" class="btn btn-outline-secondary" id="dxped-prev" disabled>Prev</button>
                                <button type="button" class="btn btn-outline-secondary" id="dxped-page-indicator" disabled>1 / <?php echo $totalPages; ?></button>
                                <button type="button" class="btn btn-outline-secondary" id="dxped-next">Next</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        <?php } ?>
    </table>

    <?php if ($recordCount > $pageSize) { ?>

        <script>
            (function() {
                var card = document.getElementById('dxped-dashboard-card');
                if (!card) return;

                var rows = card.querySelectorAll('.dxped-row');
                var prevButton = card.querySelector('#dxped-prev');
                var nextButton = card.querySelector('#dxped-next');
                var pageIndicator = card.querySelector('#dxped-page-indicator');
                var range = card.querySelector('#dxped-range');
                var pageSize = parseInt(card.getAttribute('data-pagesize'), 10);
                var totalRows = rows.length;
                var totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
                var currentPage = 1;

                function render() {
                    rows.forEach(function(row) {
                        row.style.display = parseInt(row.getAttribute('data-page'), 10) === currentPage ? '' : 'none';
                    });

                    var start = ((currentPage - 1) * pageSize) + 1;
                    var end = Math.min(currentPage * pageSize, totalRows);
                    if (range) {
                        range.textContent = start + '-' + end;
                    }

                    if (pageIndicator) {
                        pageIndicator.textContent = currentPage + ' / ' + totalPages;
                    }

                    prevButton.disabled = currentPage === 1;
                    nextButton.disabled = currentPage === totalPages;
                }

                prevButton.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        render();
                    }
                });

                nextButton.addEventListener('click', function() {
                    if (currentPage < totalPages) {
                        currentPage++;
                        render();
                    }
                });

                render();
            })();
        </script>
    <?php } ?>
</div>
<?php } ?>