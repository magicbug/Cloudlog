<?php
$defaultCategories = array('General', 'Antennas', 'Satellites');
$existingCategories = isset($categories) && is_array($categories) ? $categories : array();
$categoryOptions = array_values(array_unique(array_merge($defaultCategories, $existingCategories)));
$selectedCategory = set_value('category', 'General');
?>
<main class="container notes notes-page">
	<form method="post" action="<?php echo site_url('notes/add'); ?>" name="notes_add" id="notes_add" enctype="multipart/form-data">
		<!-- Unified Topbar Card -->
		<div class="card notes-topbar mb-3">
			<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
				<div class="d-flex align-items-center gap-2">
					<a href="<?php echo site_url('notes'); ?>" class="btn btn-sm btn-outline-secondary">
						<i class="fas fa-arrow-left me-1"></i><?php echo lang('notes_menu_notes'); ?>
					</a>
					<div>
						<h1 class="h5 mb-0 fw-bold"><?php echo lang('notes_create_note'); ?></h1>
						<div class="small text-body-secondary">Rich-text note &amp; Station Diary editor</div>
					</div>
				</div>
				<div class="d-flex align-items-center gap-2">
					<a href="<?php echo site_url('notes'); ?>" class="btn btn-sm btn-outline-secondary">
						<?php echo lang('general_word_cancel') ?: 'Cancel'; ?>
					</a>
					<button type="submit" value="Submit" class="btn btn-sm btn-primary px-3">
						<i class="fas fa-save me-1"></i><?php echo lang('notes_input_btn_save_note'); ?>
					</button>
				</div>
			</div>
		</div>

		<?php if (!empty(validation_errors())): ?>
			<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
				<ul class="mb-0"><?php echo (validation_errors('<li>', '</li>')); ?></ul>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>

		<!-- 2-Column Editor Layout -->
		<div class="row g-3 align-items-start">
			<!-- Left 8 cols: Title + Rich Text Editor -->
			<div class="col-lg-8">
				<div class="card notes-editor-card">
					<div class="card-body p-3 p-md-4">
						<div class="mb-3">
							<label for="inputTitle" class="notes-field-label"><?php echo lang('notes_input_title'); ?></label>
							<input type="text" name="title" class="form-control fw-semibold" id="inputTitle" value="<?php echo set_value('title'); ?>" placeholder="e.g. Field day setup" autocomplete="off" required>
						</div>

						<div>
							<label for="noteContent" class="notes-field-label"><?php echo lang('notes_input_notes_content'); ?></label>
							<textarea name="content" id="noteContent" class="form-control"><?php echo set_value('content'); ?></textarea>
						</div>
					</div>
					<div class="card-footer notes-card-footer px-3 px-md-4 py-3 d-flex justify-content-end gap-2">
						<a href="<?php echo site_url('notes'); ?>" class="btn btn-outline-secondary btn-sm px-3">
							<i class="fas fa-times me-1"></i><?php echo lang('general_word_cancel') ?: 'Cancel'; ?>
						</a>
						<button type="submit" value="Submit" class="btn btn-primary btn-sm px-4">
							<i class="fas fa-save me-1"></i><?php echo lang('notes_input_btn_save_note'); ?>
						</button>
					</div>
				</div>
			</div>

			<!-- Right 4 cols: Organization + Station Diary Settings + Filters + Images -->
			<div class="col-lg-4 d-flex flex-column gap-3">
				<!-- Organization Card -->
				<div class="card notes-side-card mb-0">
					<div class="card-header py-2 px-3">
						<h2 class="h6 mb-0 small text-uppercase fw-bold text-body-secondary">Organization</h2>
					</div>
					<div class="card-body p-3">
						<div class="mb-3">
							<label for="catSelect" class="notes-field-label"><?php echo lang('notes_input_category'); ?></label>
							<select name="category" class="form-select form-select-sm" id="catSelect">
								<?php foreach ($categoryOptions as $catOption) { ?>
									<option value="<?php echo htmlspecialchars($catOption, ENT_QUOTES); ?>" <?php echo ($selectedCategory === $catOption) ? 'selected' : ''; ?>><?php echo htmlspecialchars($catOption, ENT_QUOTES); ?></option>
								<?php } ?>
							</select>
						</div>
						<div>
							<label for="newCategoryInput" class="notes-field-label">New Category <span class="fw-normal opacity-75">(optional)</span></label>
							<input type="text" name="new_category" class="form-control form-control-sm" id="newCategoryInput" value="<?php echo set_value('new_category'); ?>" placeholder="e.g. Portable Ops" autocomplete="off">
							<div class="form-text">Overrides the selected category above.</div>
						</div>
					</div>
				</div>

				<!-- Station Diary Settings Card -->
				<div class="card notes-side-card mb-0">
					<div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
						<h2 class="h6 mb-0 small text-uppercase fw-bold text-body-secondary"><i class="fas fa-eye me-1"></i>Station Diary Settings</h2>
					</div>
					<div class="card-body p-3">
						<?php if (isset($public_station_diary_enabled) && !$public_station_diary_enabled) { ?>
							<div class="alert alert-warning small py-2 mb-3">Public Station Diary is globally disabled. Entries will remain private.</div>
						<?php } ?>
						<div class="form-check form-switch mb-3">
							<input class="form-check-input" type="checkbox" value="1" id="isPublicEntry" name="is_public" <?php echo set_value('is_public') ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
							<label class="form-check-label small" for="isPublicEntry">
								<strong class="d-block"><i class="fas fa-globe me-1"></i>Make entry public</strong>
								<span class="text-body-secondary">Only applies to &ldquo;Station Diary&rdquo; category</span>
							</label>
						</div>
						<div class="form-check form-switch mb-3">
							<input class="form-check-input" type="checkbox" value="1" id="includeQsoSummary" name="include_qso_summary" <?php echo set_value('include_qso_summary') ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
							<label class="form-check-label small" for="includeQsoSummary">
								<strong class="d-block">Include QSO summary</strong>
								<span class="text-body-secondary">Shows contact statistics on public page</span>
							</label>
						</div>
						<div id="logbookSelectorContainer" style="<?php echo set_value('include_qso_summary') ? '' : 'display:none;'; ?>">
							<label for="logbookSelect" class="notes-field-label">Logbook <span class="text-danger">*</span></label>
							<select name="logbook_id" class="form-select form-select-sm" id="logbookSelect" required>
								<option value="">-- Choose a logbook --</option>
								<?php if (isset($user_logbooks) && $user_logbooks->num_rows() > 0) {
									foreach ($user_logbooks->result() as $logbook) { ?>
										<option value="<?php echo $logbook->logbook_id; ?>" <?php echo set_value('logbook_id') == $logbook->logbook_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($logbook->logbook_name, ENT_QUOTES); ?></option>
									<?php }
								} ?>
							</select>
						</div>
					</div>
				</div>

				<!-- QSO Summary Filters Card (Collapsible) -->
				<div class="card notes-side-card mb-0">
					<div class="card-header py-2 px-3">
						<button type="button" class="btn btn-link btn-sm p-0 text-decoration-none w-100 d-flex align-items-center justify-content-between text-body-secondary fw-bold text-uppercase small" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
							<span><i class="fas fa-filter me-1"></i>QSO Summary Filters</span>
							<i class="fas fa-chevron-down fa-xs"></i>
						</button>
					</div>
					<div id="collapseFilters" class="collapse">
						<div class="card-body p-3">
							<div class="mb-2">
								<label for="qsoDateStart" class="notes-field-label">Date Range Start</label>
								<input type="date" class="form-control form-control-sm" id="qsoDateStart" name="qso_date_start" value="<?php echo set_value('qso_date_start'); ?>">
							</div>
							<div class="mb-3">
								<label for="qsoDateEnd" class="notes-field-label">Date Range End</label>
								<input type="date" class="form-control form-control-sm" id="qsoDateEnd" name="qso_date_end" value="<?php echo set_value('qso_date_end'); ?>">
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="1" id="qsoSatelliteOnly" name="qso_satellite_only" <?php echo set_value('qso_satellite_only') ? 'checked' : ''; ?>>
								<label class="form-check-label small" for="qsoSatelliteOnly">
									<i class="fas fa-satellite me-1"></i>Satellite QSOs only
								</label>
							</div>
						</div>
					</div>
				</div>

				<!-- Diary Photos Card -->
				<div class="card notes-side-card mb-0">
					<div class="card-header py-2 px-3">
						<h2 class="h6 mb-0 small text-uppercase fw-bold text-body-secondary"><i class="fas fa-image me-1"></i>Diary Photos</h2>
					</div>
					<div class="card-body p-3">
						<label for="diaryImages" class="notes-field-label">Upload Images</label>
						<input type="file" class="form-control form-control-sm" id="diaryImages" name="diary_images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
						<div class="form-text">Max 8 MB per image (JPG, PNG, GIF, WebP). Auto-resized.</div>
						<div class="alert alert-info py-2 px-2 mt-2 mb-0 small">
							<i class="fas fa-info-circle me-1"></i>After saving, edit this note to copy inline <code>[image:ID]</code> shortcodes and add captions.
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="modal fade" id="confirmPublicModal" tabindex="-1" aria-labelledby="confirmPublicModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="confirmPublicModalLabel">Make this entry public?</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					This entry will become visible at your public station diary URL if category is <strong>Station Diary</strong>.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" id="confirmPublicModalProceed">Make Public</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var isPublicEntry = document.getElementById('isPublicEntry');
			var includeQsoSummary = document.getElementById('includeQsoSummary');
			var logbookSelectorContainer = document.getElementById('logbookSelectorContainer');
			var logbookSelect = document.getElementById('logbookSelect');
			var confirmModalEl = document.getElementById('confirmPublicModal');
			var confirmModalProceed = document.getElementById('confirmPublicModalProceed');

			if (includeQsoSummary && logbookSelectorContainer && logbookSelect) {
				if (includeQsoSummary.checked) {
					logbookSelect.setAttribute('required', 'required');
				} else {
					logbookSelect.removeAttribute('required');
				}

				includeQsoSummary.addEventListener('change', function() {
					if (includeQsoSummary.checked) {
						logbookSelectorContainer.style.display = 'block';
						logbookSelect.setAttribute('required', 'required');
					} else {
						logbookSelectorContainer.style.display = 'none';
						logbookSelect.removeAttribute('required');
					}
				});
			}

			if (!isPublicEntry || !confirmModalEl || !confirmModalProceed || typeof bootstrap === 'undefined') {
				return;
			}

			var confirmModal = new bootstrap.Modal(confirmModalEl);
			var allowPublicChange = false;

			isPublicEntry.addEventListener('change', function() {
				if (isPublicEntry.checked && !allowPublicChange) {
					isPublicEntry.checked = false;
					confirmModal.show();
				}
			});

			confirmModalProceed.addEventListener('click', function() {
				allowPublicChange = true;
				isPublicEntry.checked = true;
				confirmModal.hide();
				allowPublicChange = false;
			});
		});
	</script>
</main>
