
<style>
#quillArea .ql-editor {
	min-height: 300px;
	padding: 1.5rem;
	font-size: 1rem;
	line-height: 1.6;
}

#quillArea .ql-editor p {
	margin-bottom: 1rem;
}

#quillArea .ql-editor h1,
#quillArea .ql-editor h2,
#quillArea .ql-editor h3,
#quillArea .ql-editor h4,
#quillArea .ql-editor h5,
#quillArea .ql-editor h6 {
	margin-top: 1.5rem;
	margin-bottom: 0.75rem;
	line-height: 1.3;
	font-weight: 600;
}

#quillArea .ql-editor h1:first-child,
#quillArea .ql-editor h2:first-child,
#quillArea .ql-editor h3:first-child,
#quillArea .ql-editor h4:first-child,
#quillArea .ql-editor h5:first-child,
#quillArea .ql-editor h6:first-child {
	margin-top: 0;
}

#quillArea .ql-editor ul,
#quillArea .ql-editor ol {
	margin-bottom: 1rem;
	padding-left: 2rem;
}

#quillArea .ql-editor li {
	margin-bottom: 0.5rem;
}

#quillArea .ql-editor li p {
	margin-bottom: 0.25rem;
}

#quillArea .ql-editor blockquote {
	border-left: 4px solid #ddd;
	margin: 1.5rem 0;
	padding: 0.75rem 1rem;
	background-color: #f9f9f9;
	font-style: italic;
	color: #666;
}

#quillArea .ql-editor pre {
	background-color: #f5f5f5;
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 1rem;
	margin: 1rem 0;
	overflow-x: auto;
}

#quillArea .ql-editor code {
	background-color: #f5f5f5;
	padding: 0.2rem 0.4rem;
	border-radius: 3px;
	font-family: 'Courier New', monospace;
	font-size: 0.9em;
}

#quillArea .ql-editor hr {
	margin: 1.5rem 0;
	border: none;
	border-top: 2px solid #e0e0e0;
}

#quillArea .ql-editor table {
	width: 100%;
	border-collapse: collapse;
	margin: 1rem 0;
}

#quillArea .ql-editor table th,
#quillArea .ql-editor table td {
	border: 1px solid #ddd;
	padding: 0.75rem;
}

#quillArea .ql-editor table th {
	background-color: #f5f5f5;
	font-weight: 600;
}

#quillArea .ql-editor img {
	max-width: 100%;
	height: auto;
	margin: 1rem 0;
	border-radius: 4px;
}

#quillArea .ql-editor a {
	color: #0d6efd;
	text-decoration: underline;
}
</style>

<div class="container notes">
	<div class="row">
		<div class="col-12 col-xl-10">
			<div class="card shadow-sm">
			  <div class="card-header">
			    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
			    	<div class="d-flex flex-column">
			    		<a class="small text-decoration-none" href="<?php echo site_url('notes'); ?>">&larr; <?php echo lang('notes_menu_notes'); ?></a>
			    		<h2 class="card-title mb-0"><?php echo lang('notes_create_note'); ?></h2>
			    	</div>
			    	<ul class="nav nav-tabs card-header-tabs ms-auto">
				    <li class="nav-item">
				    	<a class="nav-link" href="<?php echo site_url('notes'); ?>"><?php echo lang('notes_menu_notes'); ?></a>
				    </li>
				    <li class="nav-item">
				    	<a class="nav-link active" href="<?php echo site_url('notes/add'); ?>"><?php echo lang('notes_create_note'); ?></a>
				    </li>
				</ul>
			    </div>
			  </div>

			  <div class="card-body">

  	<?php if (!empty(validation_errors())): ?>
    <div class="alert alert-danger">
        <a class="btn-close" data-bs-dismiss="alert" title="close">x</a>
        <ul><?php echo (validation_errors('<li>', '</li>')); ?></ul>
    </div>
	<?php endif; ?>

	<?php
	$defaultCategories = array('General', 'Antennas', 'Satellites');
	$existingCategories = isset($categories) && is_array($categories) ? $categories : array();
	$categoryOptions = array_values(array_unique(array_merge($defaultCategories, $existingCategories)));
	$selectedCategory = set_value('category', 'General');
	?>

	<form method="post" action="<?php echo site_url('notes/add'); ?>" name="notes_add" id="notes_add" enctype="multipart/form-data">

	<!-- Basic Information -->
	<div class="mb-3">
		<label for="inputTitle" class="form-label fw-semibold"><?php echo lang('notes_input_title'); ?></label>
		<input type="text" name="title" class="form-control" id="inputTitle" value="<?php echo set_value('title'); ?>" placeholder="e.g. Field day setup" autocomplete="off" required>
	</div>

	<div class="row mb-3">
		<div class="col-md-6">
			<label for="catSelect" class="form-label fw-semibold"><?php echo lang('notes_input_category'); ?></label>
			<select name="category" class="form-select" id="catSelect">
				<?php foreach ($categoryOptions as $catOption) { ?>
				<option value="<?php echo $catOption; ?>" <?php echo ($selectedCategory === $catOption) ? 'selected' : ''; ?>><?php echo $catOption; ?></option>
				<?php } ?>
			</select>
			<small class="text-muted">Select existing or create new below</small>
		</div>
		<div class="col-md-6">
			<label for="newCategoryInput" class="form-label fw-semibold">New category <span class="badge bg-secondary">Optional</span></label>
			<input type="text" name="new_category" class="form-control" id="newCategoryInput" value="<?php echo set_value('new_category'); ?>" placeholder="e.g. Portable Ops" autocomplete="off">
			<small class="text-muted">Overrides selected category</small>
		</div>
	</div>

	<!-- Note Content -->
	<div class="mb-4">
		<label for="hiddenArea" class="form-label fw-semibold"><?php echo lang('notes_input_notes_content'); ?></label>
		<div id="quillArea"></div>
		<textarea name="content" style="display:none" id="hiddenArea"></textarea>
	</div>

	<!-- Accordion for Optional Settings -->
	<div class="accordion mb-4" id="noteSettingsAccordion">
		
		<!-- Station Diary Settings -->
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingVisibility">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisibility" aria-expanded="false" aria-controls="collapseVisibility">
					<i class="fas fa-eye me-2"></i> Station Diary Settings
				</button>
			</h2>
			<div id="collapseVisibility" class="accordion-collapse collapse" aria-labelledby="headingVisibility" data-bs-parent="#noteSettingsAccordion">
				<div class="accordion-body">
					<?php if (isset($public_station_diary_enabled) && !$public_station_diary_enabled) { ?>
						<div class="alert alert-warning mb-3">Public Station Diary is globally disabled. Entries will remain private.</div>
					<?php } ?>
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" value="1" id="isPublicEntry" name="is_public" <?php echo set_value('is_public') ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
						<label class="form-check-label" for="isPublicEntry">
							<strong>🌍 Make entry public</strong>
							<small class="d-block text-muted">Only applies to "Station Diary" category</small>
						</label>
					</div>
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" value="1" id="includeQsoSummary" name="include_qso_summary" <?php echo set_value('include_qso_summary') ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
						<label class="form-check-label" for="includeQsoSummary">
							<strong>Include QSO summary</strong>
							<small class="d-block text-muted">Shows contact statistics on public page</small>
						</label>
					</div>
					<div id="logbookSelectorContainer" style="<?php echo set_value('include_qso_summary') ? '' : 'display:none;'; ?>">
						<label for="logbookSelect" class="form-label">Logbook <span class="text-danger">*</span></label>
						<select name="logbook_id" class="form-select" id="logbookSelect" required>
							<option value="">-- Choose a logbook --</option>
							<?php if (isset($user_logbooks) && $user_logbooks->num_rows() > 0) {
								foreach ($user_logbooks->result() as $logbook) { ?>
									<option value="<?php echo $logbook->logbook_id; ?>" <?php echo set_value('logbook_id') == $logbook->logbook_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($logbook->logbook_name, ENT_QUOTES); ?></option>
								<?php }
							} ?>
						</select>
						<small class="text-muted">QSO summary filtered to this logbook</small>
					</div>
				</div>
			</div>
		</div>

		<!-- QSO Filters -->
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingFilters">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
					<i class="fas fa-filter me-2"></i> QSO Summary Filters
				</button>
			</h2>
			<div id="collapseFilters" class="accordion-collapse collapse" aria-labelledby="headingFilters" data-bs-parent="#noteSettingsAccordion">
				<div class="accordion-body">
					<p class="text-muted mb-3">Control which QSOs appear in the summary:</p>
					<div class="row g-3 mb-3">
						<div class="col-md-6">
							<label for="qsoDateStart" class="form-label">Date Range Start</label>
							<input type="date" class="form-control" id="qsoDateStart" name="qso_date_start" value="<?php echo set_value('qso_date_start'); ?>">
							<small class="text-muted">Leave empty for entry date</small>
						</div>
						<div class="col-md-6">
							<label for="qsoDateEnd" class="form-label">Date Range End</label>
							<input type="date" class="form-control" id="qsoDateEnd" name="qso_date_end" value="<?php echo set_value('qso_date_end'); ?>">
							<small class="text-muted">Leave empty for today</small>
						</div>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="qsoSatelliteOnly" name="qso_satellite_only" <?php echo set_value('qso_satellite_only') ? 'checked' : ''; ?>>
						<label class="form-check-label" for="qsoSatelliteOnly">
							<i class="fas fa-satellite me-1"></i>Satellite QSOs only
						</label>
					</div>
				</div>
			</div>
		</div>

		<!-- Images -->
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingImages">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImages" aria-expanded="false" aria-controls="collapseImages">
					<i class="fas fa-image me-2"></i> Images
				</button>
			</h2>
			<div id="collapseImages" class="accordion-collapse collapse" aria-labelledby="headingImages" data-bs-parent="#noteSettingsAccordion">
				<div class="accordion-body">
					<label for="diaryImages" class="form-label fw-semibold">Add images</label>
					<input type="file" class="form-control" id="diaryImages" name="diary_images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
					<small class="text-muted d-block">Max 8 MB per image. Auto-resized and compressed.</small>
					<div class="alert alert-info mt-3 small">
						<strong>💡 Tip:</strong> After creating this note, edit it to see image IDs and add captions. Then you can use shortcodes like <code>[image:ID]</code> to display images inline in your text.
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="d-flex flex-wrap gap-2 sticky-bottom bg-white py-3 border-top">
		<button type="submit" value="Submit" class="btn btn-primary btn-lg">
			<i class="fas fa-save me-2"></i><?php echo lang('notes_input_btn_save_note'); ?>
		</button>
		<a href="<?php echo site_url('notes'); ?>" class="btn btn-outline-secondary btn-lg">
			<i class="fas fa-times me-2"></i><?php echo lang('general_word_cancel') ?: 'Cancel'; ?>
		</a>
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
			
			// Toggle logbook selector visibility and required attribute
			if (includeQsoSummary && logbookSelectorContainer && logbookSelect) {
				// Set initial state based on checkbox (for validation errors)
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
			
			if (!isPublicEntry) {
				return;
			}

			if (!confirmModalEl || !confirmModalProceed || typeof bootstrap === 'undefined') {
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
	  </div>
			</div>
		</div>
	</div>

</div>
