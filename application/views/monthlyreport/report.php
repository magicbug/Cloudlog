<div class="container">
	<br>
	<h2>
		<i class="fas fa-chart-line"></i> <?php echo $page_title; ?>
	</h2>

	<div class="alert alert-success" role="alert">
		<i class="fas fa-check-circle"></i> 
		Report generated for <strong><?php echo $logbook_name; ?></strong> - 
		<strong><?php echo $month_name . ' ' . $year; ?></strong>
	</div>

	<!-- Summary Cards -->
	<div class="row mb-4">
		<div class="col-md-3">
			<div class="card text-center">
				<div class="card-body">
					<h5 class="card-title text-muted">Total QSOs</h5>
					<h2 class="text-primary"><i class="fas fa-signal"></i> <?php echo number_format($report['total_qsos']); ?></h2>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card text-center">
				<div class="card-body">
					<h5 class="card-title text-muted">New Countries</h5>
					<h2 class="text-success"><i class="fas fa-flag"></i> <?php echo count($report['new_dxcc']); ?></h2>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card text-center">
				<div class="card-body">
					<h5 class="card-title text-muted">New Gridsquares</h5>
					<h2 class="text-info"><i class="fas fa-th"></i> <?php echo count($report['new_grids']); ?></h2>
					<?php if (count($report['new_grids_satellite']) > 0 || count($report['new_grids_eme']) > 0 || count($report['new_grids_hf']) > 0) { ?>
					<small class="text-muted">
						<?php if (count($report['new_grids_hf']) > 0) echo count($report['new_grids_hf']) . ' HF '; ?>
						<?php if (count($report['new_grids_satellite']) > 0) echo count($report['new_grids_satellite']) . ' SAT '; ?>
						<?php if (count($report['new_grids_eme']) > 0) echo count($report['new_grids_eme']) . ' EME'; ?>
					</small>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card text-center">
				<div class="card-body">
					<h5 class="card-title text-muted">Unique Callsigns</h5>
					<h2 class="text-warning"><i class="fas fa-user"></i> <?php echo number_format($report['unique_callsigns']); ?></h2>
				</div>
			</div>
		</div>
	</div>

	<!-- Export Buttons -->
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-download"></i> Export Report
		</div>
		<div class="card-body">
			<p>Export this report in AI-friendly or text formats to create articles, blog posts, or share your activity.</p>
			
			<form method="post" action="<?php echo site_url('monthlyreport/export_json'); ?>" class="d-inline">
				<input type="hidden" name="logbook_id" value="<?php echo $this->input->post('logbook_id'); ?>">
				<input type="hidden" name="year" value="<?php echo $year; ?>">
				<input type="hidden" name="month" value="<?php echo $month; ?>">
				<button type="submit" class="btn btn-primary">
					<i class="fas fa-file-code"></i> Export as JSON (for AI)
				</button>
			</form>

			<form method="post" action="<?php echo site_url('monthlyreport/export_text'); ?>" class="d-inline">
				<input type="hidden" name="logbook_id" value="<?php echo $this->input->post('logbook_id'); ?>">
				<input type="hidden" name="year" value="<?php echo $year; ?>">
				<input type="hidden" name="month" value="<?php echo $month; ?>">
				<button type="submit" class="btn btn-secondary">
					<i class="fas fa-file-alt"></i> Export as Text
				</button>
			</form>

			<a href="<?php echo site_url('monthlyreport'); ?>" class="btn btn-outline-primary">
				<i class="fas fa-redo"></i> Generate Another Report
			</a>
		</div>
	</div>

	<!-- New Countries -->
	<?php if (count($report['new_dxcc']) > 0 || !empty($report['new_dxcc_by_band']) || count($report['new_dxcc_satellite']) > 0 || count($report['new_dxcc_eme']) > 0) { ?>
	
	<?php if (count($report['new_dxcc']) > 0) { ?>
	<div class="card mb-4 border-success">
		<div class="card-header bg-success bg-opacity-10 border-success">
			<h5 class="mb-0"><i class="fas fa-flag text-success"></i> New Countries This Month - Overall (<?php echo count($report['new_dxcc']); ?>)</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<?php foreach ($report['new_dxcc'] as $dxcc) { ?>
					<div class="col-md-4 mb-2">
						<span class="badge bg-success fs-6">
							<i class="fas fa-star"></i> <?php echo $dxcc['name']; ?>
						</span>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } elseif (!empty($report['new_dxcc_by_band']) || count($report['new_dxcc_satellite']) > 0 || count($report['new_dxcc_eme']) > 0) { ?>
	<div class="alert alert-info mb-4" role="alert">
		<i class="fas fa-info-circle"></i> 
		<strong>No new countries overall</strong> - but you worked existing countries on new bands or propagation modes this month (see below).
	</div>
	<?php } ?>

	<!-- New Countries by Band -->
	<?php 
	// Filter out Satellite and EME from band list to see if there's anything to display
	$terrestrial_bands = array();
	if (!empty($report['new_dxcc_by_band'])) {
		foreach ($report['new_dxcc_by_band'] as $band => $dxcc_list) {
			if ($band != 'Satellite' && $band != 'EME') {
				$terrestrial_bands[$band] = $dxcc_list;
			}
		}
	}
	
	if (!empty($terrestrial_bands)) { 
	?>
	<div class="card mb-4 border-success">
		<div class="card-header bg-success bg-opacity-10 border-success">
			<h5 class="mb-0"><i class="fas fa-broadcast-tower text-success"></i> New Countries by Band</h5>
		</div>
		<div class="card-body">
			<?php 
			// Bands already sorted in model
			foreach ($terrestrial_bands as $band => $dxcc_list) { 
				$icon = '<i class="fas fa-signal"></i>';
			?>
				<div class="mb-3">
					<h6 class="text-muted"><?php echo $icon; ?> <?php echo $band; ?> (<?php echo count($dxcc_list); ?> new)</h6>
					<div class="row">
						<?php foreach ($dxcc_list as $dxcc) { ?>
							<div class="col-md-6 col-lg-4 mb-2">
								<div class="d-flex align-items-center">
									<span class="badge bg-success me-2">
										<i class="fas fa-star"></i> <?php echo $dxcc['name']; ?>
									</span>
									<small class="text-muted">
										<?php echo $dxcc['callsign']; ?>
										<?php if (!empty($dxcc['mode'])) echo ' • ' . $dxcc['mode']; ?>
									</small>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<!-- New Countries via Satellite -->
	<?php if (count($report['new_dxcc_satellite']) > 0) { ?>
	<div class="card mb-4 border-success">
		<div class="card-header bg-success bg-opacity-10 border-success">
			<h5 class="mb-0"><i class="fas fa-satellite text-success"></i> New Countries via Satellite (<?php echo count($report['new_dxcc_satellite']); ?>)</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<?php foreach ($report['new_dxcc_satellite'] as $dxcc) { ?>
					<div class="col-md-6 col-lg-4 mb-2">
						<div class="d-flex align-items-center">
							<span class="badge bg-success me-2">
								<i class="fas fa-star"></i> <?php echo $dxcc['name']; ?>
							</span>
							<small class="text-muted">
								<?php echo $dxcc['callsign']; ?>
								<?php if (!empty($dxcc['mode'])) echo ' • ' . $dxcc['mode']; ?>
							</small>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>

	<!-- New Countries via EME -->
	<?php if (count($report['new_dxcc_eme']) > 0) { ?>
	<div class="card mb-4 border-success">
		<div class="card-header bg-success bg-opacity-10 border-success">
			<h5 class="mb-0"><i class="fas fa-moon text-success"></i> New Countries via EME (Moonbounce) (<?php echo count($report['new_dxcc_eme']); ?>)</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<?php foreach ($report['new_dxcc_eme'] as $dxcc) { ?>
					<div class="col-md-6 col-lg-4 mb-2">
						<div class="d-flex align-items-center">
							<span class="badge bg-success me-2">
								<i class="fas fa-star"></i> <?php echo $dxcc['name']; ?>
							</span>
							<small class="text-muted">
								<?php echo $dxcc['callsign']; ?>
								<?php if (!empty($dxcc['mode'])) echo ' • ' . $dxcc['mode']; ?>
							</small>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<?php } // End of new countries section ?>

	<!-- New Gridsquares -->
	<?php if (count($report['new_grids']) > 0) { ?>
	<div class="card mb-4 border-info">
		<div class="card-header bg-info bg-opacity-10 border-info">
			<h5 class="mb-0"><i class="fas fa-th text-info"></i> New Gridsquares This Month</h5>
		</div>
		<div class="card-body">
			
			<?php if (count($report['new_grids_hf']) > 0) { ?>
			<div class="mb-4">
				<h6 class="text-muted"><i class="fas fa-tower-broadcast"></i> HF/VHF Terrestrial (<?php echo count($report['new_grids_hf']); ?>)</h6>
				<div class="row">
					<?php foreach ($report['new_grids_hf'] as $grid) { ?>
						<div class="col-md-4 col-lg-3 mb-2">
							<div class="d-flex align-items-center">
								<span class="badge bg-primary me-2"><?php echo $grid['grid']; ?></span>
								<small class="text-muted">
									<?php echo $grid['callsign']; ?>
									<?php if (!empty($grid['mode'])) echo ' • ' . $grid['mode']; ?>
								</small>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>

			<?php if (count($report['new_grids_satellite']) > 0) { ?>
			<div class="mb-4">
				<h6 class="text-muted"><i class="fas fa-satellite-dish"></i> Satellite (<?php echo count($report['new_grids_satellite']); ?>)</h6>
				<div class="row">
					<?php foreach ($report['new_grids_satellite'] as $grid) { ?>
						<div class="col-md-6 col-lg-4 mb-2">
							<div class="d-flex align-items-center">
								<span class="badge bg-success me-2"><?php echo $grid['grid']; ?></span>
								<small class="text-muted">
									<?php echo $grid['callsign']; ?>
									<?php if (!empty($grid['satellite'])) echo ' • ' . $grid['satellite']; ?>
									<?php if (!empty($grid['mode'])) echo ' • ' . $grid['mode']; ?>
								</small>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>

			<?php if (count($report['new_grids_eme']) > 0) { ?>
			<div class="mb-3">
				<h6 class="text-muted"><i class="fas fa-moon"></i> EME (Moonbounce) (<?php echo count($report['new_grids_eme']); ?>)</h6>
				<div class="row">
					<?php foreach ($report['new_grids_eme'] as $grid) { ?>
						<div class="col-md-4 col-lg-3 mb-2">
							<div class="d-flex align-items-center">
								<span class="badge bg-warning text-dark me-2"><?php echo $grid['grid']; ?></span>
								<small class="text-muted">
									<?php echo $grid['callsign']; ?>
									<?php if (!empty($grid['mode'])) echo ' • ' . $grid['mode']; ?>
								</small>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>

		</div>
	</div>
	<?php } ?>

	<!-- Modes and Bands -->
	<div class="row mb-4">
		<div class="col-md-6 mb-3">
			<div class="card h-100">
				<div class="card-header">
					<h5 class="mb-0"><i class="fas fa-broadcast-tower"></i> Modes Used</h5>
				</div>
				<div class="card-body">
					<?php if (!empty($report['modes'])) { ?>
						<table class="table table-sm table-hover">
							<thead class="table-light">
								<tr>
									<th>Mode</th>
									<th class="text-end">QSOs</th>
									<th class="text-end">%</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($report['modes'] as $mode => $count) { 
									$percentage = ($report['total_qsos'] > 0) ? round(($count / $report['total_qsos']) * 100, 1) : 0;
								?>
									<tr>
										<td><strong><?php echo $mode; ?></strong></td>
										<td class="text-end"><?php echo number_format($count); ?></td>
										<td class="text-end"><span class="badge bg-secondary"><?php echo $percentage; ?>%</span></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					<?php } else { ?>
						<p class="text-muted">No mode data available</p>
					<?php } ?>
				</div>
			</div>
		</div>

		<div class="col-md-6 mb-3">
			<div class="card h-100">
				<div class="card-header">
					<h5 class="mb-0"><i class="fas fa-wave-square"></i> Bands Used</h5>
				</div>
				<div class="card-body">
					<?php if (!empty($report['bands'])) { ?>
						<table class="table table-sm table-hover">
							<thead class="table-light">
								<tr>
									<th>Band</th>
									<th class="text-end">QSOs</th>
									<th class="text-end">%</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($report['bands'] as $band => $count) { 
									$percentage = ($report['total_qsos'] > 0) ? round(($count / $report['total_qsos']) * 100, 1) : 0;
								?>
									<tr>
										<td><strong><?php echo $band; ?></strong></td>
										<td class="text-end"><?php echo number_format($count); ?></td>
										<td class="text-end"><span class="badge bg-secondary"><?php echo $percentage; ?>%</span></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					<?php } else { ?>
						<p class="text-muted">No band data available</p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Satellite Breakdown -->
	<?php if (!empty($report['satellite_breakdown'])) { ?>
	<div class="card mb-4">
		<div class="card-header">
			<h5 class="mb-0"><i class="fas fa-satellite"></i> Satellites Worked (<?php echo count($report['satellite_breakdown']); ?>)</h5>
		</div>
		<div class="card-body">
			<table class="table table-sm table-hover">
				<thead class="table-light">
					<tr>
						<th>Satellite</th>
						<th class="text-end">QSOs</th>
						<th class="text-end">% of Satellite QSOs</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($report['satellite_breakdown'] as $satellite => $count) { 
						$percentage = ($report['satellite_qsos'] > 0) ? round(($count / $report['satellite_qsos']) * 100, 1) : 0;
					?>
						<tr>
							<td><strong><?php echo $satellite; ?></strong></td>
							<td class="text-end"><?php echo number_format($count); ?></td>
							<td class="text-end"><span class="badge bg-success"><?php echo $percentage; ?>%</span></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php } ?>

	<!-- Continental Distribution -->
	<?php if (!empty($report['continents'])) { ?>
	<div class="card mb-4">
		<div class="card-header">
			<h5 class="mb-0"><i class="fas fa-globe"></i> Continental Distribution</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<?php 
				$continent_names = array(
					'AF' => 'Africa',
					'AN' => 'Antarctica',
					'AS' => 'Asia',
					'EU' => 'Europe',
					'NA' => 'North America',
					'OC' => 'Oceania',
					'SA' => 'South America'
				);
				foreach ($report['continents'] as $cont => $count) { 
					$cont_name = isset($continent_names[$cont]) ? $continent_names[$cont] : $cont;
					$percentage = ($report['total_qsos'] > 0) ? round(($count / $report['total_qsos']) * 100, 1) : 0;
				?>
					<div class="col-md-4 mb-3">
						<div class="card border-secondary">
							<div class="card-body text-center">
								<h5 class="text-muted"><?php echo $cont_name; ?></h5>
								<h3 class="text-primary"><?php echo number_format($count); ?></h3>
								<p class="text-muted mb-0"><strong><?php echo $percentage; ?>%</strong> of QSOs</p>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>

	<!-- Special Activity -->
	<?php if ($report['satellite_qsos'] > 0 || $report['eme_qsos'] > 0) { ?>
	<div class="card mb-4 border-primary">
		<div class="card-header bg-primary bg-opacity-10 border-primary">
			<h5 class="mb-0"><i class="fas fa-satellite text-primary"></i> Special Activity</h5>
		</div>
		<div class="card-body">
			<div class="row">
				<?php if ($report['satellite_qsos'] > 0) { ?>
					<div class="col-md-6 mb-3">
						<div class="card border-primary">
							<div class="card-body text-center">
								<h5 class="text-muted"><i class="fas fa-satellite-dish"></i> Satellite QSOs</h5>
								<h2 class="text-primary"><?php echo number_format($report['satellite_qsos']); ?></h2>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if ($report['eme_qsos'] > 0) { ?>
					<div class="col-md-6 mb-3">
						<div class="card border-primary">
							<div class="card-body text-center">
								<h5 class="text-muted"><i class="fas fa-moon"></i> EME (Moonbounce) QSOs</h5>
								<h2 class="text-primary"><?php echo number_format($report['eme_qsos']); ?></h2>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>

	<!-- Statistics Summary -->
	<div class="card mb-4">
		<div class="card-header">
			<i class="fas fa-chart-bar"></i> Statistics
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-4">
					<h6 class="text-muted">Average QSOs per Day</h6>
					<h4><?php 
						$days_in_month = date('t', strtotime("$year-$month-01"));
						echo number_format($report['total_qsos'] / $days_in_month, 2); 
					?></h4>
				</div>
				<div class="col-md-4">
					<h6 class="text-muted">Most Active Band</h6>
					<h4><?php echo $report['top_band'] ? $report['top_band'] : 'N/A'; ?></h4>
				</div>
				<div class="col-md-4">
					<h6 class="text-muted">Most Used Mode</h6>
					<h4><?php echo $report['top_mode'] ? $report['top_mode'] : 'N/A'; ?></h4>
				</div>
			</div>
		</div>
	</div>

	<!-- Unique Callsigns Worked -->
	<?php if (!empty($report['callsign_list'])) { ?>
	<div class="card mb-4">
		<div class="card-header">
			<h5 class="mb-0"><i class="fas fa-address-book"></i> Unique Callsigns Worked This Month (<?php echo count($report['callsign_list']); ?>)</h5>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped">
					<thead class="table-light">
						<tr>
							<th>Callsign</th>
							<th class="text-end">QSOs</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($report['callsign_list'] as $callsign) { ?>
							<tr>
								<td><strong><?php echo $callsign['callsign']; ?></strong></td>
								<td class="text-end">
									<span class="badge bg-primary fs-6"><?php echo $callsign['qso_count']; ?></span>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>

</div>
