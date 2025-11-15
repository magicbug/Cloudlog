<style>
	#modeChart, #bandChart, #satChart{
		margin: 0 auto;
	}
	
	.stats-summary-cards {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: 1.5rem;
		margin-bottom: 2rem;
	}
	
	.stats-card {
		background: var(--bs-body-bg);
		border: 1px solid var(--bs-border-color);
		border-radius: 8px;
		padding: 1.5rem;
		text-align: center;
		transition: transform 0.2s, box-shadow 0.2s;
	}
	
	.stats-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 4px 12px rgba(0,0,0,0.3);
	}
	
	.stats-card-value {
		font-size: 2.5rem;
		font-weight: bold;
		color: var(--bs-primary);
		margin-bottom: 0.5rem;
	}
	
	.stats-card-label {
		font-size: 0.875rem;
		color: var(--bs-secondary-color, var(--bs-secondary));
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	
	.loading-indicator {
		text-align: center;
		padding: 2rem;
		color: var(--bs-body-color);
	}
	
	.loading-spinner {
		border: 3px solid var(--bs-border-color);
		border-radius: 50%;
		border-top: 3px solid var(--bs-primary);
		width: 40px;
		height: 40px;
		animation: spin 1s linear infinite;
		margin: 0 auto 1rem;
	}
	
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
	
	.chart-container {
		position: relative;
		min-height: 200px;
	}
	
	/* Dark theme specific adjustments */
	[data-bs-theme="dark"] .stats-card {
		background: var(--bs-dark-bg-subtle, #212529);
		border-color: var(--bs-border-color, #495057);
	}
	
	[data-bs-theme="dark"] .stats-card:hover {
		box-shadow: 0 4px 12px rgba(255,255,255,0.1);
	}
	
	[data-bs-theme="dark"] .loading-spinner {
		border-color: rgba(255,255,255,0.1);
		border-top-color: var(--bs-primary);
	}
	
	@media (max-width: 768px) {
		.stats-summary-cards {
			grid-template-columns: repeat(2, 1fr);
			gap: 1rem;
		}
		
		.stats-card {
			padding: 1rem;
		}
		
		.stats-card-icon {
			font-size: 2rem;
		}
		
		.stats-card-value {
			font-size: 1.5rem;
		}
	}
</style>


<script>
		// General Language
		var lang_statistics_years = "<?php echo lang('statistics_years')?>";
		var lang_statistics_modes = "<?php echo lang('statistics_modes')?>";
		var lang_statistics_bands = "<?php echo lang('statistics_bands')?>";
		var lang_statistics_number_of_qso_worked_each_year = "<?php echo lang('statistics_number_of_qso_worked_each_year')?>";
		var lang_statistics_year = "<?php echo lang('statistics_year')?>";
		var lang_statistics_number_of_qso_worked = "<?php echo lang('statistics_number_of_qso_worked')?>";
		var lang_gen_hamradio_mode = "<?php echo lang('gen_hamradio_mode')?>";
		var lang_gen_hamradio_band = "<?php echo lang('gen_hamradio_band')?>";
		var lang_statistics_unique_callsigns = "<?php echo lang('statistics_unique_callsigns')?>";
</script>

<div class="container statistics">

	<h2>
		<?php echo $page_title; ?>
		<small class="text-muted"><?php echo lang('statistics_explore_the_logbook'); ?></small>
	</h2>

	<!-- Summary Cards -->
	<div class="stats-summary-cards" id="summaryCards">
		<div class="loading-indicator">
			<div class="loading-spinner"></div>
			<p>Loading statistics...</p>
		</div>
	</div>

	<!-- Date Range Filter (Optional) -->
	<div class="card mb-3" style="display: none;" id="dateFilterCard">
		<div class="card-body">
			<div class="row align-items-end">
				<div class="col-md-4">
					<label for="filterStartDate" class="form-label">
						<i class="fas fa-calendar-alt"></i> Start Date
					</label>
					<input type="date" class="form-control" id="filterStartDate">
				</div>
				<div class="col-md-4">
					<label for="filterEndDate" class="form-label">
						<i class="fas fa-calendar-alt"></i> End Date
					</label>
					<input type="date" class="form-control" id="filterEndDate">
				</div>
				<div class="col-md-4">
					<button class="btn btn-primary" onclick="applyDateFilter()">
						<i class="fas fa-filter"></i> Apply Filter
					</button>
					<button class="btn btn-secondary" onclick="clearDateFilter()">
						<i class="fas fa-times"></i> Clear
					</button>
					<button class="btn btn-outline-secondary" onclick="toggleDateFilter()">
						<i class="fas fa-chevron-up"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="text-end mb-2">
		<button class="btn btn-sm btn-outline-primary" onclick="toggleDateFilter()">
			<i class="fas fa-filter"></i> Date Range Filter
		</button>
	</div>
	<div hidden class="tabs">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
					<i class="fas fa-chart-line"></i> General
				</a>
			</li>
			<?php if ($sat_active) { ?>
			<li class="nav-item">
				<a class="nav-link" id="satellite-tab" data-bs-toggle="tab" href="#satellite" role="tab" aria-controls="satellite" aria-selected="false">
					<i class="fas fa-satellite"></i> Satellites
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>

		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<br />
					<ul class="nav nav-pills" id="myTab2" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="years-tab" data-bs-toggle="tab" href="#yearstab" role="tab" aria-controls="yearstab" aria-selected="true">
								<i class="fas fa-calendar-alt"></i> <?php echo lang('statistics_years'); ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="mode-tab" data-bs-toggle="tab" href="#modetab" role="tab" aria-controls="modetab" aria-selected="false">
								<i class="fas fa-broadcast-tower"></i> <?php echo lang('statistics_modes'); ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="band-tab" data-bs-toggle="tab" href="#bandtab" role="tab" aria-controls="bandtab" aria-selected="false">
								<i class="fas fa-wave-square"></i> <?php echo lang('statistics_bands'); ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="qso-tab" data-bs-toggle="tab" href="#qsotab" role="tab" aria-controls="qsotab" aria-selected="false">
								<i class="fas fa-table"></i> <?php echo lang('statistics_qsos'); ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="unique-tab" data-bs-toggle="tab" href="#uniquetab" role="tab" aria-controls="uniquetab" aria-selected="false">
								<i class="fas fa-users"></i> <?php echo lang('statistics_unique_callsigns'); ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="trends-tab" data-bs-toggle="tab" href="#trendstab" role="tab" aria-controls="trendstab" aria-selected="false">
								<i class="fas fa-chart-area"></i> Trends
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="continents-tab" data-bs-toggle="tab" href="#continentstab" role="tab" aria-controls="continentstab" aria-selected="false">
								<i class="fas fa-globe-americas"></i> Continents
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="mostworked-tab" data-bs-toggle="tab" href="#mostworkedtab" role="tab" aria-controls="mostworkedtab" aria-selected="false">
								<i class="fas fa-trophy"></i> Most Worked
							</a>
						</li>
					</ul>
				<div class="tab-content">
					<div class="tab-pane fade show active" id="yearstab" role="tabpanel" aria-labelledby="years-tab">
						<div class="years chart-container" style="margin-top: 20px;">
							<div class="loading-indicator">
								<div class="loading-spinner"></div>
								<p>Loading year statistics...</p>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="modetab" role="tabpanel" aria-labelledby="mode-tab">
							<div class="mode chart-container">
								<div class="loading-indicator">
									<div class="loading-spinner"></div>
									<p>Loading mode statistics...</p>
								</div>
							</div>
					</div>
					<div class="tab-pane fade" id="bandtab" role="tabpanel" aria-labelledby="band-tab">
							<div class="band chart-container">
								<div class="loading-indicator">
									<div class="loading-spinner"></div>
									<p>Loading band statistics...</p>
								</div>
							</div>
					</div>
					<div class="tab-pane fade" id="qsotab" role="tabpanel" aria-labelledby="qso-tab">
							<div class="qsos chart-container">
								<div class="loading-indicator">
									<div class="loading-spinner"></div>
									<p>Loading QSO statistics...</p>
								</div>
							</div>
					</div>
					<div class="tab-pane fade" id="uniquetab" role="tabpanel" aria-labelledby="unique-tab">
							<div class="unique chart-container">
								<div class="loading-indicator">
									<div class="loading-spinner"></div>
									<p>Loading unique callsigns...</p>
								</div>
							</div>
					</div>
					<div class="tab-pane fade" id="trendstab" role="tabpanel" aria-labelledby="trends-tab">
							<div class="trends chart-container" style="margin-top: 20px;">
								<div class="loading-indicator">
									<div class="loading-spinner"></div>
									<p>Loading trends...</p>
								</div>
							</div>
					</div>
					<div class="tab-pane fade" id="continentstab" role="tabpanel" aria-labelledby="continents-tab">
							<div class="continents chart-container" style="margin-top: 20px;">
								<div class="loading-indicator">
									<div class="loading-spinner"></div>
									<p>Loading continent statistics...</p>
								</div>
							</div>
					</div>
					<div class="tab-pane fade" id="mostworkedtab" role="tabpanel" aria-labelledby="mostworked-tab">
							<div class="mostworked chart-container" style="margin-top: 20px;">
								<div class="loading-indicator">
									<div class="loading-spinner"></div>
									<p>Loading most worked statistics...</p>
								</div>
							</div>
					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="satellite" role="tabpanel" aria-labelledby="satellite-tab">
				<br/>	
				<div style="display: flex;" id="satContainer"><div style="flex: 1;"><canvas id="satChart" width="500" height="500"></canvas></div><div style="flex: 1;" id="satTable">
				
				<table style="width:100%" class="sattable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>
					<tr>
					<td>#</td>
					<td>Satellite</td>
					<td># of QSO's worked</td>
					</tr>
					</thead>
					<tbody></tbody>
				</table></div></div>
			</div>
		</div>
</div>
