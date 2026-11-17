<div class="container search">
	<h1><?php echo lang('search_merge_title'); ?></h1>

	<div class="alert alert-danger" role="alert">
		<i class="fas fa-exclamation-triangle"></i> <?php echo lang('search_merge_warning'); ?>
	</div>

	<div class="card mb-3">
		<div class="card-header"><?php echo lang('search_merge_keeper'); ?></div>
		<div class="card-body">
			<p class="text-muted"><?php echo lang('search_merge_keeper_help'); ?></p>
			<div class="btn-group flex-wrap" role="group" id="keeper_select">
				<?php foreach ($qsos as $qso_id => $qso) {
					$checked = ((int) $qso_id === (int) $default_keeper) ? ' checked' : '';
					$time_on = date('Y-m-d H:i', strtotime($qso['COL_TIME_ON']));
					?>
					<input type="radio" class="btn-check" name="keeper_id" id="keeper_<?php echo (int) $qso_id; ?>" value="<?php echo (int) $qso_id; ?>"<?php echo $checked; ?> autocomplete="off">
					<label class="btn btn-outline-primary" for="keeper_<?php echo (int) $qso_id; ?>">
						<?php echo lang('search_merge_qso_label'); ?> #<?php echo (int) $qso_id; ?>
						<small class="d-block"><?php echo htmlspecialchars($time_on, ENT_QUOTES, 'UTF-8'); ?></small>
					</label>
				<?php } ?>
			</div>
		</div>
	</div>

	<form id="merge_form">
		<input type="hidden" name="ids" id="merge_ids" value="<?php echo htmlspecialchars($qso_ids, ENT_QUOTES, 'UTF-8'); ?>">

		<div class="table-responsive">
			<table class="table table-sm table-bordered" id="merge_fields_table">
				<thead class="table-light">
					<tr>
						<th><?php echo lang('search_merge_field'); ?></th>
						<?php foreach ($qsos as $qso_id => $qso) { ?>
							<th class="text-center">
								<?php echo lang('search_merge_qso_label'); ?> #<?php echo (int) $qso_id; ?>
							</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($fields as $field) {
						if ($field === 'station_id') {
							continue;
						}
						$values = [];
						foreach ($qsos as $qso_id => $qso) {
							$values[$qso_id] = $qso[$field] ?? '';
						}
						$normalised = array_map(static function ($v) {
							return ($v === null || $v === '') ? '' : (string) $v;
						}, $values);
						$differs = count(array_unique($normalised)) > 1;
						$row_class = $differs ? 'table-warning' : '';
						$default_source = $default_choices[$field] ?? $default_keeper;
						?>
						<tr class="<?php echo $row_class; ?>" data-field="<?php echo htmlspecialchars($field, ENT_QUOTES, 'UTF-8'); ?>">
							<th scope="row">
								<?php echo htmlspecialchars($field, ENT_QUOTES, 'UTF-8'); ?>
								<?php if ($differs) { ?>
									<span class="badge text-bg-warning ms-1"><?php echo lang('search_merge_differs'); ?></span>
								<?php } ?>
							</th>
							<?php foreach ($qsos as $qso_id => $qso) {
								$display = $values[$qso_id];
								if ($display === null || $display === '') {
									$display = '—';
								} else {
									$display = htmlspecialchars((string) $display, ENT_QUOTES, 'UTF-8');
								}
								$checked = ((int) $qso_id === (int) $default_source) ? ' checked' : '';
								$input_id = 'field_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $field) . '_' . (int) $qso_id;
								?>
								<td class="text-center">
									<div class="form-check d-flex flex-column align-items-center">
										<input class="form-check-input field-source" type="radio"
											name="field_sources[<?php echo htmlspecialchars($field, ENT_QUOTES, 'UTF-8'); ?>]"
											id="<?php echo $input_id; ?>"
											value="<?php echo (int) $qso_id; ?>"<?php echo $checked; ?>>
										<label class="form-check-label small text-break" for="<?php echo $input_id; ?>">
											<?php echo $display; ?>
										</label>
									</div>
								</td>
							<?php } ?>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

		<div class="card mb-3">
			<div class="card-header d-flex justify-content-between align-items-center">
				<span><?php echo lang('search_merge_preview'); ?></span>
				<button type="button" class="btn btn-sm btn-outline-secondary" id="merge_preview_btn">
					<?php echo lang('search_merge_preview_btn'); ?>
				</button>
			</div>
			<div class="card-body">
				<pre class="small mb-0" id="merge_preview_output"><?php echo lang('search_merge_preview_btn'); ?>…</pre>
			</div>
		</div>

		<div class="d-flex gap-2 flex-wrap">
			<button type="button" class="btn btn-danger" id="merge_confirm_btn">
				<i class="fas fa-compress-alt"></i> <?php echo lang('search_merge_confirm'); ?>
			</button>
			<a class="btn btn-secondary" href="<?php echo site_url('search/duplicates'); ?>">
				<?php echo lang('search_merge_cancel'); ?>
			</a>
		</div>
	</form>
</div>

<script src="<?php echo base_url(); ?>assets/js/sections/qso_merge.js"></script>
<script>
	var merge_lang_confirm = <?php echo json_encode(lang('search_merge_confirm') . '?'); ?>;
</script>
