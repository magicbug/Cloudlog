<div class="container">
	<br>
	<h2><?php echo $page_title; ?></h2>
	
	<div class="card">
		<div class="card-header">
			<i class="fas fa-calendar-alt"></i> Generate Monthly Activity Report
		</div>
		<div class="card-body">
			<p class="card-text">
				Generate a comprehensive monthly report of your amateur radio activity. The report includes QSO counts, 
				new countries and gridsquares worked, modes and bands used, and special activity like satellites and EME. 
				You can export the data in formats suitable for sharing or feeding into AI tools to generate articles.
			</p>

			<?php if ($logbooks->num_rows() > 0) { ?>
				
				<form method="post" action="<?php echo site_url('monthlyreport/generate'); ?>" class="mt-4">
					
					<div class="row mb-3">
						<div class="col-md-6">
							<label for="logbook_id" class="form-label">
								<i class="fas fa-book"></i> Select Logbook
							</label>
							<select name="logbook_id" id="logbook_id" class="form-select" required>
								<option value="">Choose a logbook...</option>
								<?php foreach ($logbooks->result() as $logbook) { ?>
									<option value="<?php echo $logbook->logbook_id; ?>"
										<?php if ($this->session->userdata('active_station_logbook') == $logbook->logbook_id) echo 'selected'; ?>>
										<?php echo $logbook->logbook_name; ?>
									</option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col-md-3">
							<label for="year" class="form-label">
								<i class="fas fa-calendar"></i> Year
							</label>
							<select name="year" id="year" class="form-select" required>
								<?php 
								// Generate year options from 2010 to current year
								$current_year = date('Y');
								for ($y = $current_year; $y >= 2010; $y--) {
									$selected = ($y == $current_year) ? 'selected' : '';
									echo "<option value=\"{$y}\" {$selected}>{$y}</option>";
								}
								?>
							</select>
						</div>

						<div class="col-md-3">
							<label for="month" class="form-label">
								<i class="fas fa-calendar-day"></i> Month
							</label>
							<select name="month" id="month" class="form-select" required>
								<?php 
								$current_month = date('n');
								$months = array(
									1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
									5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
									9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
								);
								foreach ($months as $num => $name) {
									$selected = ($num == $current_month) ? 'selected' : '';
									echo "<option value=\"{$num}\" {$selected}>{$name}</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-chart-line"></i> Generate Report
							</button>
						</div>
					</div>

				</form>

			<?php } else { ?>
				
				<div class="alert alert-warning" role="alert">
					<i class="fas fa-exclamation-triangle"></i> 
					<strong>No Logbooks Found</strong> - You need to create at least one logbook before generating reports.
					<a href="<?php echo site_url('logbooks/create'); ?>" class="alert-link">Create a logbook now</a>.
				</div>

			<?php } ?>

		</div>
	</div>

	<div class="card mt-4">
		<div class="card-header">
			<i class="fas fa-info-circle"></i> About Monthly Reports
		</div>
		<div class="card-body">
			<h5>What's Included in the Report?</h5>
			<ul>
				<li><strong>Total QSOs</strong> - Number of contacts made during the month</li>
				<li><strong>New DXCC Entities</strong> - Countries worked for the first time ever</li>
				<li><strong>New Gridsquares</strong> - Maidenhead grid squares worked for the first time</li>
				<li><strong>Modes & Bands</strong> - Breakdown of activity by mode and frequency band</li>
				<li><strong>Satellite Activity</strong> - QSOs made via satellite</li>
				<li><strong>EME Activity</strong> - Earth-Moon-Earth (moonbounce) contacts</li>
				<li><strong>Continental Distribution</strong> - Where your contacts were located</li>
			</ul>

			<h5 class="mt-3">Export Options</h5>
			<p>
				Once generated, you can export your monthly report in two formats:
			</p>
			<ul>
				<li><strong>JSON Format</strong> - Structured data perfect for feeding into OpenAI's ChatGPT, Claude, or other AI tools to generate blog posts or articles</li>
				<li><strong>Text Format</strong> - Human-readable summary that you can share on social media, email, or include in newsletters</li>
			</ul>

			<div class="alert alert-info mt-3" role="alert">
				<i class="fas fa-robot"></i> <strong>AI Tip:</strong> 
				Use the JSON export with AI tools like ChatGPT by saying: 
				<em>"Write a blog post about my amateur radio activity based on this data: [paste JSON here]"</em>
			</div>
		</div>
	</div>

</div>
