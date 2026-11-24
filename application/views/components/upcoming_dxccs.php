<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (isset($thisWeekRecords['error'])) { ?>
	<div class="alert alert-warning py-2 mb-0 small" role="alert">
		<i class="fas fa-exclamation-triangle me-1"></i> <?php echo $thisWeekRecords['error']; ?>
	</div>
<?php } else {
	// Hide entries with unknown callsigns in the dashboard list.
	$thisWeekRecords = array_values(array_filter($thisWeekRecords, function ($record) {
		$callsign = isset($record['callsign']) ? trim((string) $record['callsign']) : '';
		return strcasecmp($callsign, 'Unknown') !== 0;
	}));

	$pageSize = 8;
	$recordCount = count($thisWeekRecords);
	$totalPages = max(1, (int) ceil($recordCount / $pageSize));
?>

	<div id="dxped-dashboard-card" class="card" data-pagesize="<?php echo $pageSize; ?>">
		<style>
			#dxped-dashboard-card table {
				--bs-table-striped-bg: transparent;
				--bs-table-accent-bg: transparent;
				font-size: 0.85rem;
			}

			#dxped-dashboard-card .dxped-row > td {
				box-shadow: none !important;
				padding: 0.45rem 0.75rem;
				vertical-align: middle;
			}

			#dxped-dashboard-card .dxped-row.dxped-worked > td {
				background-color: rgba(25, 135, 84, 0.14) !important;
			}

			#dxped-dashboard-card .dxped-row.dxped-needed > td {
				background-color: rgba(220, 53, 69, 0.12) !important;
			}

			#dxped-dashboard-card .dxped-legend-dot {
				width: 0.55rem;
				height: 0.55rem;
				border-radius: 50%;
				display: inline-block;
			}

			#dxped-dashboard-card .dxped-legend-worked {
				background-color: rgba(25, 135, 84, 0.75);
			}

			#dxped-dashboard-card .dxped-legend-needed {
				background-color: rgba(220, 53, 69, 0.7);
			}
		</style>

		<div class="card-header">
			<span class="card-header-title">
				<i class="fas fa-globe"></i>
				<span>DXPeditions</span>
			</span>
			<div class="d-flex align-items-center gap-2">
				<span class="d-none d-sm-inline-flex align-items-center gap-2 small fw-normal text-muted" style="font-size: 0.74rem;">
					<span class="d-inline-flex align-items-center gap-1"><span class="dxped-legend-dot dxped-legend-worked"></span>Worked</span>
					<span class="d-inline-flex align-items-center gap-1"><span class="dxped-legend-dot dxped-legend-needed"></span>Needed</span>
				</span>
				<a href="<?php echo site_url('workabledxcc'); ?>" class="btn btn-outline-secondary btn-sm py-0 px-2">Full List</a>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table mb-0">
				<tbody>
					<?php if ($recordCount === 0) { ?>
						<tr>
							<td colspan="3" class="text-muted text-center py-3">No DXPeditions found for this week.</td>
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
								<td class="text-nowrap text-muted small"><?php echo $record['daysLeft']; ?></td>
								<td class="fw-semibold text-nowrap">
									<?php if ($displayCallsign !== 'Unknown') { ?>
										<a target="_blank" rel="noopener" href="https://dxheat.com/db/<?php echo urlencode($displayCallsign); ?>" data-bs-toggle="tooltip" data-bs-title="<?php echo $tooltipNote; ?>"><?php echo htmlspecialchars($displayCallsign, ENT_QUOTES, 'UTF-8'); ?></a>
									<?php } else { ?>
										<span data-bs-toggle="tooltip" data-bs-title="<?php echo $tooltipNote; ?>">Unknown</span>
									<?php } ?>
								</td>
								<td><?php echo htmlspecialchars($record['2'], ENT_QUOTES, 'UTF-8'); ?></td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>

		<?php if ($recordCount > $pageSize) { ?>
			<div class="card-footer d-flex justify-content-between align-items-center py-2 px-3">
				<small class="text-muted">Showing <span id="dxped-range">1-<?php echo min($pageSize, $recordCount); ?></span> of <?php echo $recordCount; ?></small>
				<div class="btn-group btn-group-sm" role="group" aria-label="DXpedition pagination">
					<button type="button" class="btn btn-outline-secondary" id="dxped-prev" disabled>Prev</button>
					<button type="button" class="btn btn-outline-secondary" id="dxped-page-indicator" disabled>1 / <?php echo $totalPages; ?></button>
					<button type="button" class="btn btn-outline-secondary" id="dxped-next">Next</button>
				</div>
			</div>

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
