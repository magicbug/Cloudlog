<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
if ($radio_status->num_rows()) {
?><div class="card">
	<div class="card-header">
		<span class="card-header-title">
			<span class="radio-live-dot" aria-hidden="true"></span>
			<span>Radio Status</span>
		</span>
		<span class="badge text-bg-success fw-normal">Live</span>
	</div>
	<div class="table-responsive">
		<table class="table table-sm dashboard-stat-table mb-0">
			<tbody>
				<?php foreach ($radio_status->result_array() as $row) { ?>
					<tr>
						<td class="fw-semibold"><?php echo htmlspecialchars($row['radio'], ENT_QUOTES, 'UTF-8'); ?></td>
						<td class="text-end">
							<?php if ($row['prop_mode'] == 'SAT') { ?>
								<span class="badge text-bg-primary"><?php echo htmlspecialchars($row['sat_name'], ENT_QUOTES, 'UTF-8'); ?></span>
							<?php } else { ?>
								<span class="fw-semibold"><?php echo $this->frequency->hz_to_mhz($row['frequency']); ?></span>
								<span class="badge text-bg-secondary ms-1"><?php echo htmlspecialchars($row['mode'], ENT_QUOTES, 'UTF-8'); ?></span>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div><?php } ?>