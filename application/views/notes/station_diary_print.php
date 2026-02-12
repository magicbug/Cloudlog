<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Station Diary</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Segoe UI', 'Roboto', 'Helvetica', sans-serif;
			line-height: 1.6;
			color: #000;
			background: #fff;
		}

		.diary-container {
			max-width: 8.5in;
			margin: 0 auto;
			background: #fff;
			padding: 1.5in;
			min-height: 100vh;
		}

		.diary-header {
			text-align: center;
			margin-bottom: 2rem;
			border-bottom: 3px solid #000;
			border-top: 3px solid #000;
			padding: 1rem 0;
		}

		.diary-header h1 {
			font-size: 2rem;
			color: #000;
			margin-bottom: 0.25rem;
			font-weight: 700;
			letter-spacing: 1px;
		}

		.diary-header .subtitle {
			color: #000;
			font-size: 0.9rem;
			margin-top: 0.5rem;
			font-weight: 500;
		}

		.diary-entries {
			margin-top: 2rem;
		}

		.diary-entry {
			margin-bottom: 2.5rem;
			page-break-inside: avoid;
			border-left: 4px solid #000;
			padding-left: 1rem;
		}

		.entry-date {
			font-size: 1rem;
			font-weight: 700;
			color: #000;
			margin-bottom: 0.75rem;
		}

		.entry-title {
			font-size: 1.15rem;
			font-weight: 700;
			color: #000;
			margin-bottom: 0.5rem;
		}

		.entry-content {
			color: #000;
			line-height: 1.7;
			font-size: 0.95rem;
			word-wrap: break-word;
		}

		.entry-meta {
			color: #666;
			font-size: 0.85rem;
			margin-top: 0.75rem;
            padding-top: 0.5rem;
			border-top: 1px solid #ccc;
		}

		.no-entries {
			text-align: center;
			color: #000;
			padding: 3rem 1.5rem;
			font-size: 1.1rem;
		}

		.page-break {
			page-break-after: always;
		}

		@media print {
			body {
				background: white;
			}

			.diary-container {
				max-width: 100%;
				padding: 0.5in;
				box-shadow: none;
				background: white;
			}

			.diary-entry {
				page-break-inside: avoid;
			}

			.entry-content {
				color: #000;
			}

			.print-button {
				display: none;
			}
		}

		.print-button {
			display: block;
			margin-bottom: 1.5rem;
			text-align: center;
		}

		.print-button button {
			background: #0d6efd;
			color: white;
			border: none;
			padding: 0.75rem 1.5rem;
			border-radius: 4px;
			font-size: 1rem;
			cursor: pointer;
			transition: background 0.3s;
		}

		.print-button button:hover {
			background: #0b5ed7;
		}

		.diary-stats {
			text-align: center;
			color: #000;
			font-size: 0.9rem;
			margin-bottom: 1rem;
			padding: 0.75rem;
			background: #f0f0f0;
			border-radius: 4px;
			font-weight: 600;
		}
	</style>
</head>
<body>
	<div class="diary-container">
		<div class="print-button">
			<button onclick="window.print()">
				<i class="fas fa-print"></i> Print Diary
			</button>
		</div>

		<div class="diary-header">
			<h1>STATION DIARY</h1>
			<div class="subtitle">Amateur Radio Operating Log</div>
		</div>

		<?php if ($diary_entries->num_rows() > 0) { ?>
			<div class="diary-stats">
				Total Entries: <?php echo $diary_entries->num_rows(); ?>
			</div>

			<div class="diary-entries">
				<?php 
				foreach ($diary_entries->result() as $entry) {
					$entry_date = (is_null($entry->created_at) || $entry->created_at === '' || $entry->created_at === '0000-00-00 00:00:00') 
						? null 
						: date('l, F j, Y', strtotime($entry->created_at));
					
					$entry_time = (is_null($entry->created_at) || $entry->created_at === '' || $entry->created_at === '0000-00-00 00:00:00') 
						? null 
						: date('g:i A', strtotime($entry->created_at));
				?>
					<div class="diary-entry">
						<?php if ($entry_date) { ?>
							<div class="entry-date">
								<?php echo $entry_date; ?> — <?php echo $entry_time; ?>
							</div>
						<?php } ?>
						<div class="entry-title">
							<?php echo htmlspecialchars($entry->title, ENT_QUOTES); ?>
						</div>
						<div class="entry-content">
							<?php echo htmlspecialchars($entry->note, ENT_QUOTES); ?>
						</div>
						<div class="entry-meta">
							Saved: <?php echo $entry_date ? date('M d, Y \a\t g:i A', strtotime($entry->created_at)) : 'N/A'; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } else { ?>
			<div class="no-entries">
				<p>No Station Diary entries found.</p>
				<p style="margin-top: 1rem;">Start adding entries from the <a href="<?php echo site_url('logbook'); ?>">Logbook</a> page using the Station Diary modal.</p>
			</div>
		<?php } ?>
	</div>
</body>
</html>
