<div class="container awards-page">
	<?php $this->load->view('awards/components/topbar', array(
		'title' => $page_title,
		'subtitle' => lang('cron_scheduler_intro'),
		'icon' => 'fas fa-clock',
	)); ?>

	<?php if (!empty($duplicate_warning)) { ?>
		<div class="alert alert-warning" role="alert">
			<span class="badge rounded-pill text-bg-warning"><?php echo lang('general_word_warning'); ?></span>
			<?php echo lang('cron_scheduler_duplicate_warning'); ?>
		</div>
	<?php } ?>

	<div class="card mb-3">
		<div class="card-header"><?php echo lang('cron_scheduler_enable_label'); ?></div>
		<div class="card-body">
			<form id="cron-scheduler-form">
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" id="scheduler_enabled" name="scheduler_enabled" <?php if ($scheduler_enabled) { echo 'checked'; } ?>>
					<label class="form-check-label" for="scheduler_enabled"><?php echo lang('cron_scheduler_enable_label'); ?></label>
				</div>
				<button type="button" class="btn btn-primary" id="save_scheduler_btn">Save</button>
			</form>
			<p class="small text-muted mt-3"><?php echo lang('cron_scheduler_antidouble_note'); ?></p>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-header"><?php echo lang('cron_scheduler_key_label'); ?></div>
		<div class="card-body">
			<?php if ($scheduler_key !== '') { ?>
				<code id="scheduler_key_display" class="user-select-all"><?php echo htmlspecialchars($scheduler_key, ENT_QUOTES, 'UTF-8'); ?></code>
			<?php } else { ?>
				<p class="text-muted mb-0" id="scheduler_key_display"><?php echo lang('cron_scheduler_key_empty'); ?></p>
			<?php } ?>
			<button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="regenerate_key_btn"><?php echo lang('cron_scheduler_regenerate_key'); ?></button>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-header"><?php echo lang('cron_scheduler_cli_line'); ?></div>
		<div class="card-body">
			<code class="user-select-all"><?php echo htmlspecialchars($cli_cron_line, ENT_QUOTES, 'UTF-8'); ?></code>
			<p class="small text-muted mt-2 mb-0"><?php echo lang('cron_scheduler_web_line'); ?>:</p>
			<code id="web_cron_line" class="user-select-all d-block mt-1"><?php echo htmlspecialchars($web_cron_line, ENT_QUOTES, 'UTF-8'); ?></code>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-header"><?php echo lang('cron_scheduler_legacy_heading'); ?></div>
		<div class="card-body">
			<code class="small">
			# Club Log upload (every 6 hours)<br>
			0 */6 * * * curl --silent <?php echo site_url('clublog/upload'); ?> &>/dev/null<br>
			# LoTW upload (hourly)<br>
			0 */1 * * * curl --silent <?php echo site_url('lotw/lotw_upload'); ?> &>/dev/null<br>
			# HRDLog, QRZ, LoTW users, SCP, DOK, SOTA, WWFF, plugins (see Welcome page)<br>
			</code>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-header"><?php echo lang('cron_scheduler_jobs_heading'); ?></div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-sm table-striped align-middle">
					<thead>
						<tr>
							<th><?php echo lang('cron_scheduler_col_description'); ?></th>
							<th style="min-width: 14rem;"><?php echo lang('cron_scheduler_col_interval'); ?></th>
							<th><?php echo lang('cron_scheduler_col_last_run'); ?></th>
							<th><?php echo lang('cron_scheduler_col_status'); ?></th>
							<th><?php echo lang('cron_scheduler_col_error'); ?></th>
							<th><?php echo lang('cron_scheduler_col_enabled'); ?></th>
							<th><?php echo lang('cron_scheduler_col_actions'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($jobs as $job) { ?>
						<tr data-job-key="<?php echo htmlspecialchars($job->job_key, ENT_QUOTES, 'UTF-8'); ?>">
							<td>
								<strong><?php echo htmlspecialchars($job->description, ENT_QUOTES, 'UTF-8'); ?></strong><br>
								<small class="text-muted"><?php echo htmlspecialchars($job->legacy_uri, ENT_QUOTES, 'UTF-8'); ?></small>
							</td>
							<td>
								<div class="cron-interval-editor" data-job-key="<?php echo htmlspecialchars($job->job_key, ENT_QUOTES, 'UTF-8'); ?>">
									<div class="fw-semibold cron-interval-label mb-1"><?php echo htmlspecialchars($job->interval_label, ENT_QUOTES, 'UTF-8'); ?></div>
									<div class="input-group input-group-sm" style="max-width: 14rem;" title="<?php echo htmlspecialchars(lang('cron_scheduler_interval_hint'), ENT_QUOTES, 'UTF-8'); ?>">
										<input type="number" class="form-control cron-interval-value" min="1" step="1" value="<?php echo (int) $job->interval_value; ?>" aria-label="<?php echo htmlspecialchars(lang('cron_scheduler_col_interval'), ENT_QUOTES, 'UTF-8'); ?>">
										<select class="form-select cron-interval-unit" aria-label="<?php echo htmlspecialchars(lang('cron_scheduler_col_interval'), ENT_QUOTES, 'UTF-8'); ?>">
											<option value="minutes" <?php if ($job->interval_unit === 'minutes') { echo 'selected'; } ?>><?php echo lang('cron_scheduler_unit_minutes'); ?></option>
											<option value="hours" <?php if ($job->interval_unit === 'hours') { echo 'selected'; } ?>><?php echo lang('cron_scheduler_unit_hours'); ?></option>
											<option value="days" <?php if ($job->interval_unit === 'days') { echo 'selected'; } ?>><?php echo lang('cron_scheduler_unit_days'); ?></option>
										</select>
									</div>
									<small class="cron-interval-feedback text-muted" aria-live="polite"></small>
								</div>
							</td>
							<td><?php echo $job->last_finished_at ? htmlspecialchars($job->last_finished_at, ENT_QUOTES, 'UTF-8') : '-'; ?></td>
							<td><?php echo $job->last_status ? htmlspecialchars($job->last_status, ENT_QUOTES, 'UTF-8') : '-'; ?></td>
							<td class="small"><?php echo $job->last_error ? htmlspecialchars($job->last_error, ENT_QUOTES, 'UTF-8') : '-'; ?></td>
							<td>
								<input type="checkbox" class="form-check-input cron-job-enabled" data-job-key="<?php echo htmlspecialchars($job->job_key, ENT_QUOTES, 'UTF-8'); ?>" <?php if ((int) $job->enabled === 1) { echo 'checked'; } ?>>
							</td>
							<td>
								<button type="button" class="btn btn-sm btn-outline-primary cron-run-now" data-job-key="<?php echo htmlspecialchars($job->job_key, ENT_QUOTES, 'UTF-8'); ?>"><?php echo lang('cron_scheduler_run_now'); ?></button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<a href="<?php echo site_url('maintenance'); ?>" class="btn btn-secondary"><?php echo lang('cron_scheduler_back_maintenance'); ?></a>
	<br><br>
</div>

<script>
document.getElementById('save_scheduler_btn').addEventListener('click', function () {
	const enabled = document.getElementById('scheduler_enabled').checked ? '1' : '0';
	fetch('<?php echo site_url('cronmanager/save_scheduler'); ?>', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'scheduler_enabled=' + encodeURIComponent(enabled)
	}).then(r => r.json()).then(data => {
		if (data.scheduler_key) {
			document.getElementById('scheduler_key_display').textContent = data.scheduler_key;
		}
		if (data.web_cron_line) {
			document.getElementById('web_cron_line').textContent = data.web_cron_line;
		}
	});
});

document.getElementById('regenerate_key_btn').addEventListener('click', function () {
	fetch('<?php echo site_url('cronmanager/regenerate_key'); ?>', { method: 'POST' })
		.then(r => r.json()).then(data => {
			if (data.scheduler_key) {
				document.getElementById('scheduler_key_display').textContent = data.scheduler_key;
			}
			if (data.web_cron_line) {
				document.getElementById('web_cron_line').textContent = data.web_cron_line;
			}
		});
});

document.querySelectorAll('.cron-job-enabled').forEach(function (el) {
	el.addEventListener('change', function () {
		const jobKey = el.getAttribute('data-job-key');
		const enabled = el.checked ? '1' : '0';
		fetch('<?php echo site_url('cronmanager/toggle_job'); ?>', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'job_key=' + encodeURIComponent(jobKey) + '&enabled=' + encodeURIComponent(enabled)
		});
	});
});

document.querySelectorAll('.cron-run-now').forEach(function (btn) {
	btn.addEventListener('click', function () {
		const jobKey = btn.getAttribute('data-job-key');
		btn.disabled = true;
		fetch('<?php echo site_url('cronmanager/run_job'); ?>', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'job_key=' + encodeURIComponent(jobKey)
		}).then(r => r.json()).then(function () {
			window.location.reload();
		});
	});
});

(function () {
	const setIntervalUrl = '<?php echo site_url('cronmanager/set_interval'); ?>';
	const msgInvalid = <?php echo json_encode(lang('cron_scheduler_interval_invalid')); ?>;

	function saveInterval(editor) {
		const jobKey = editor.getAttribute('data-job-key');
		const valueEl = editor.querySelector('.cron-interval-value');
		const unitEl = editor.querySelector('.cron-interval-unit');
		const labelEl = editor.querySelector('.cron-interval-label');
		const feedbackEl = editor.querySelector('.cron-interval-feedback');
		const value = parseInt(valueEl.value, 10);
		const unit = unitEl.value;

		if (!jobKey || !value || value < 1) {
			feedbackEl.textContent = msgInvalid;
			feedbackEl.className = 'cron-interval-feedback text-danger';
			return;
		}

		feedbackEl.textContent = '';
		feedbackEl.className = 'cron-interval-feedback text-muted';
		valueEl.disabled = true;
		unitEl.disabled = true;

		fetch(setIntervalUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'job_key=' + encodeURIComponent(jobKey)
				+ '&value=' + encodeURIComponent(value)
				+ '&unit=' + encodeURIComponent(unit)
		}).then(function (r) { return r.json(); }).then(function (data) {
			valueEl.disabled = false;
			unitEl.disabled = false;
			if (data && data.ok) {
				if (data.label) {
					labelEl.textContent = data.label;
				}
				if (data.value) {
					valueEl.value = data.value;
				}
				if (data.unit) {
					unitEl.value = data.unit;
				}
				feedbackEl.textContent = data.message || '';
				feedbackEl.className = 'cron-interval-feedback text-success';
			} else {
				feedbackEl.textContent = (data && data.message) ? data.message : msgInvalid;
				feedbackEl.className = 'cron-interval-feedback text-danger';
			}
		}).catch(function () {
			valueEl.disabled = false;
			unitEl.disabled = false;
			feedbackEl.textContent = msgInvalid;
			feedbackEl.className = 'cron-interval-feedback text-danger';
		});
	}

	document.querySelectorAll('.cron-interval-editor').forEach(function (editor) {
		const valueEl = editor.querySelector('.cron-interval-value');
		const unitEl = editor.querySelector('.cron-interval-unit');
		let debounceTimer = null;

		function scheduleSave() {
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function () {
				saveInterval(editor);
			}, 400);
		}

		valueEl.addEventListener('change', scheduleSave);
		valueEl.addEventListener('blur', function () {
			clearTimeout(debounceTimer);
			saveInterval(editor);
		});
		unitEl.addEventListener('change', function () {
			clearTimeout(debounceTimer);
			saveInterval(editor);
		});
	});
})();
</script>
