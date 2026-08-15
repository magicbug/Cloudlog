
<div class="container notes">
<div class="row">
<div class="col-12 col-xl-10">
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
<?php foreach ($note->result() as $row) { ?>
<?php
$defaultCategories = array('General', 'Antennas', 'Satellites');
$existingCategories = isset($categories) && is_array($categories) ? $categories : array();
$categoryOptions = array_values(array_unique(array_merge($defaultCategories, $existingCategories, array($row->cat))));
$selectedCategory = set_value('category', $row->cat);
?>
<div class="card shadow-sm">
  <div class="card-header">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
    	<div class="d-flex flex-column">
    		<a class="small text-decoration-none" href="<?php echo site_url('notes'); ?>">&larr; <?php echo lang('notes_menu_notes'); ?></a>
    		<h2 class="card-title mb-0"><?php echo lang('notes_edit_note'); ?></h2>
    	</div>
		<ul class="nav nav-tabs card-header-tabs ms-auto">
		    <li class="nav-item">
		    	<a class="nav-link" href="<?php echo site_url('notes'); ?>"><?php echo lang('notes_menu_notes'); ?></a>
		    </li>
		    <li class="nav-item">
		    	<a class="nav-link" href="<?php echo site_url('notes/add'); ?>"><?php echo lang('notes_create_note'); ?></a>
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

	<form method="post" action="<?php echo site_url('notes/edit'); ?>/<?php echo $id; ?>" name="notes_add" id="notes_add" enctype="multipart/form-data">

	<!-- Basic Information -->
	<div class="mb-3">
		<label for="inputTitle" class="form-label fw-semibold"><?php echo lang('notes_input_title'); ?></label>
		<input type="text" name="title" class="form-control" value="<?php echo set_value('title', $row->title); ?>" id="inputTitle" placeholder="e.g. Field day setup" autocomplete="off" required>
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
		<div id="quillArea"><?php echo $row->note; ?></div>
		<textarea name="content" style="display:none" id="hiddenArea"></textarea>
	</div>

	<!-- Accordion for Optional Settings -->
	<div class="accordion mb-4" id="noteSettingsAccordion">
		
		<!-- Station Diary Settings -->
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingVisibility">
				<button class="accordion-button <?php echo ((int)($row->is_public ?? 0) === 1 || (int)($row->include_qso_summary ?? 0) === 1) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVisibility" aria-expanded="<?php echo ((int)($row->is_public ?? 0) === 1 || (int)($row->include_qso_summary ?? 0) === 1) ? 'true' : 'false'; ?>" aria-controls="collapseVisibility">
					<i class="fas fa-eye me-2"></i> Station Diary Settings
				</button>
			</h2>
			<div id="collapseVisibility" class="accordion-collapse collapse <?php echo ((int)($row->is_public ?? 0) === 1 || (int)($row->include_qso_summary ?? 0) === 1) ? 'show' : ''; ?>" aria-labelledby="headingVisibility" data-bs-parent="#noteSettingsAccordion">
				<div class="accordion-body">
					<?php if (isset($public_station_diary_enabled) && !$public_station_diary_enabled) { ?>
						<div class="alert alert-warning mb-3">Public Station Diary is globally disabled. Entries will remain private.</div>
					<?php } ?>
					<div class="mb-3">
						<span id="visibilityStatusBadge" class="badge <?php echo ((int)($row->is_public ?? 0) === 1) ? 'bg-success' : 'bg-dark'; ?>">
							<?php echo ((int)($row->is_public ?? 0) === 1) ? '🌍 Public' : '🔒 Private'; ?>
						</span>
					</div>
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" value="1" id="isPublicEntry" name="is_public" <?php echo set_value('is_public', ((int)($row->is_public ?? 0) === 1 ? '1' : '')) ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
						<label class="form-check-label" for="isPublicEntry">
							<strong>🌍 Make entry public</strong>
							<small class="d-block text-muted">Only applies to "Station Diary" category</small>
						</label>
					</div>
					<div class="form-check mb-3">
						<input class="form-check-input" type="checkbox" value="1" id="includeQsoSummary" name="include_qso_summary" <?php echo set_value('include_qso_summary', ((int)($row->include_qso_summary ?? 0) === 1 ? '1' : '')) ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
						<label class="form-check-label" for="includeQsoSummary">
							<strong>Include QSO summary</strong>
							<small class="d-block text-muted">Shows contact statistics on public page</small>
						</label>
					</div>
					<div id="logbookSelectorContainer" style="<?php echo set_value('include_qso_summary', ((int)($row->include_qso_summary ?? 0) === 1 ? '1' : '')) ? '' : 'display:none;'; ?>">
						<label for="logbookSelect" class="form-label">Logbook <span class="text-danger">*</span></label>
						<select name="logbook_id" class="form-select" id="logbookSelect" required>
							<option value="">-- Choose a logbook --</option>
							<?php if (isset($user_logbooks) && $user_logbooks->num_rows() > 0) {
								$selected_logbook = set_value('logbook_id', $row->logbook_id ?? '');
								foreach ($user_logbooks->result() as $logbook) { ?>
									<option value="<?php echo $logbook->logbook_id; ?>" <?php echo $selected_logbook == $logbook->logbook_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($logbook->logbook_name, ENT_QUOTES); ?></option>
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
							<input type="date" class="form-control" id="qsoDateStart" name="qso_date_start" 
								value="<?php echo set_value('qso_date_start', !empty($row->qso_date_start) ? $row->qso_date_start : ''); ?>">
							<small class="text-muted">Leave empty for entry date</small>
						</div>
						<div class="col-md-6">
							<label for="qsoDateEnd" class="form-label">Date Range End</label>
							<input type="date" class="form-control" id="qsoDateEnd" name="qso_date_end" 
								value="<?php echo set_value('qso_date_end', !empty($row->qso_date_end) ? $row->qso_date_end : ''); ?>">
							<small class="text-muted">Leave empty for today</small>
						</div>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="qsoSatelliteOnly" 
							name="qso_satellite_only" 
							<?php echo set_value('qso_satellite_only', ((int)($row->qso_satellite_only ?? 0) === 1 ? '1' : '')) ? 'checked' : ''; ?>>
						<label class="form-check-label" for="qsoSatelliteOnly">
							<i class="fas fa-satellite me-1"></i>Satellite QSOs only
						</label>
					</div>
				</div>
			</div>
		</div>

		<!-- Images -->
		<?php $entryImages = isset($diary_images[$row->id]) ? $diary_images[$row->id] : array(); ?>
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingImages">
				<button class="accordion-button <?php echo !empty($entryImages) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseImages" aria-expanded="<?php echo !empty($entryImages) ? 'true' : 'false'; ?>" aria-controls="collapseImages">
					<i class="fas fa-image me-2"></i> Images <?php if (!empty($entryImages)) { ?><span class="badge bg-primary ms-2"><?php echo count($entryImages); ?></span><?php } ?>
				</button>
			</h2>
			<div id="collapseImages" class="accordion-collapse collapse <?php echo !empty($entryImages) ? 'show' : ''; ?>" aria-labelledby="headingImages" data-bs-parent="#noteSettingsAccordion">
				<div class="accordion-body">
					<?php if (!empty($entryImages)) { ?>
						<div class="alert alert-info small mb-3">
							<strong>💡 Inline Shortcodes:</strong> Use <code>[image:<?php echo $entryImages[0]->id; ?>]</code> or <code>[image:caption]</code> in your note text. 
							Add <code>:left</code>, <code>:right</code>, <code>:center</code>, <code>:small</code>, <code>:medium</code>, or <code>:large</code> for positioning/sizing.
						</div>
						<div class="row g-3 mb-4" id="currentImagesContainer">
							<?php foreach ($entryImages as $image) { ?>
								<div class="col-md-4" id="image-<?php echo $image->id; ?>">
									<div class="position-relative">
										<img src="<?php echo base_url() . ltrim($image->filename, '/'); ?>" class="img-fluid rounded border" alt="Diary image">
										<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="deleteDiaryImage(<?php echo $image->id; ?>)" title="Delete image">
											<i class="fas fa-trash"></i>
										</button>
									</div>
									<div class="mt-2 small">
										<div class="text-muted mb-1">
											<strong>ID:</strong> <code class="user-select-all bg-light px-1"><?php echo $image->id; ?></code>
											<button type="button" class="btn btn-sm btn-link p-0 ms-1" onclick="navigator.clipboard.writeText('[image:<?php echo $image->id; ?>]')" title="Copy shortcode">
												<i class="fas fa-copy"></i>
											</button>
										</div>
										<div class="input-group input-group-sm">
											<input type="text" 
												   class="form-control form-control-sm" 
												   id="caption-<?php echo $image->id; ?>" 
												   value="<?php echo htmlspecialchars($image->caption ?? '', ENT_QUOTES); ?>" 
												   placeholder="Add caption">
											<button type="button" class="btn btn-outline-secondary" onclick="saveImageCaption(<?php echo $image->id; ?>)" title="Save caption">
												<i class="fas fa-save"></i>
											</button>
											<?php if (!empty($image->caption)) { ?>
												<button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('[image:<?php echo htmlspecialchars($image->caption, ENT_QUOTES); ?>]')" title="Copy caption shortcode">
													<i class="fas fa-copy"></i>
												</button>
											<?php } ?>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					
					<div>
						<label for="diaryImages" class="form-label fw-semibold">Add new images</label>
						<input type="file" class="form-control" id="diaryImages" name="diary_images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
						<small class="text-muted">Max 8 MB per image. Auto-resized and compressed.</small>
					</div>
				</div>
			</div>
		</div>

		<!-- Advanced Settings -->
		<div class="accordion-item">
			<h2 class="accordion-header" id="headingAdvanced">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdvanced" aria-expanded="false" aria-controls="collapseAdvanced">
					<i class="fas fa-cog me-2"></i> Advanced Settings
				</button>
			</h2>
			<div id="collapseAdvanced" class="accordion-collapse collapse" aria-labelledby="headingAdvanced" data-bs-parent="#noteSettingsAccordion">
				<div class="accordion-body">
					<label for="createdAtInput" class="form-label">Created Date</label>
					<?php 
					$dateValue = '';
					$dateDisplayValue = '';
					if (!is_null($row->created_at) && $row->created_at !== '' && $row->created_at !== '0000-00-00 00:00:00') {
						$dateValue = date('Y-m-d', strtotime($row->created_at));
						$dateDisplayValue = date('M d, Y \a\t g:i A', strtotime($row->created_at));
					}
					?>
					<div class="mb-2">
						<small class="text-muted">Current: <strong><?php echo !empty($dateDisplayValue) ? $dateDisplayValue : 'N/A'; ?></strong></small>
					</div>
					<input type="date" name="created_at" class="form-control" id="createdAtInput" value="<?php echo set_value('created_at', $dateValue); ?>">
					<small class="text-muted">Leave empty to keep current date, or set custom creation date.</small>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<div class="d-flex flex-wrap gap-2 sticky-bottom bg-white py-3 border-top">
		<button type="submit" value="Submit" class="btn btn-primary btn-lg">
			<i class="fas fa-save me-2"></i><?php echo lang('notes_input_btn_save_note'); ?>
		</button>
		<a href="<?php echo site_url('notes/view/'.$id); ?>" class="btn btn-outline-secondary btn-lg">
			<i class="fas fa-times me-2"></i><?php echo lang('general_word_cancel') ?: 'Cancel'; ?>
		</a>
	</div>
	</form>
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
		var visibilityStatusBadge = document.getElementById('visibilityStatusBadge');
		var confirmModalEl = document.getElementById('confirmPublicModal');
		var confirmModalProceed = document.getElementById('confirmPublicModalProceed');
		
		// Toggle logbook selector visibility and required attribute
		if (includeQsoSummary && logbookSelectorContainer && logbookSelect) {
			// Set initial state based on checkbox
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

		function updateVisibilityBadge() {
				if (!visibilityStatusBadge) {
					return;
				}

				if (isPublicEntry.checked) {
					visibilityStatusBadge.classList.remove('bg-dark');
					visibilityStatusBadge.classList.add('bg-success');
					visibilityStatusBadge.textContent = 'Current visibility: 🌍 Public';
				} else {
					visibilityStatusBadge.classList.remove('bg-success');
					visibilityStatusBadge.classList.add('bg-dark');
					visibilityStatusBadge.textContent = 'Current visibility: 🔒 Private';
				}
			}

			updateVisibilityBadge();

			if (!confirmModalEl || !confirmModalProceed || typeof bootstrap === 'undefined') {
				isPublicEntry.addEventListener('change', updateVisibilityBadge);
				return;
			}

			var confirmModal = new bootstrap.Modal(confirmModalEl);
			var allowPublicChange = false;

			isPublicEntry.addEventListener('change', function() {
				if (isPublicEntry.checked && !allowPublicChange) {
					isPublicEntry.checked = false;
					updateVisibilityBadge();
					confirmModal.show();
					return;
				}

				updateVisibilityBadge();
			});

			confirmModalProceed.addEventListener('click', function() {
				allowPublicChange = true;
				isPublicEntry.checked = true;
				updateVisibilityBadge();
				confirmModal.hide();
				allowPublicChange = false;
			});
		});

		function deleteDiaryImage(imageId) {
			if (!confirm('Are you sure you want to delete this image? This cannot be undone.')) {
				return;
			}

			var imageElement = document.getElementById('image-' + imageId);
			if (!imageElement) {
				return;
			}

			// Show loading state
			imageElement.style.opacity = '0.5';

			fetch('<?php echo site_url("notes/delete_diary_image"); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'image_id=' + encodeURIComponent(imageId)
			})
			.then(function(response) { return response.json(); })
			.then(function(data) {
				if (data.success) {
					// Remove element with fade out
					imageElement.style.transition = 'opacity 0.3s';
					imageElement.style.opacity = '0';
					setTimeout(function() {
						imageElement.remove();
						// Hide container if no more images
						var container = document.getElementById('currentImagesContainer');
						if (container && container.children.length === 0) {
							container.parentElement.remove();
						}
					}, 300);
				} else {
					imageElement.style.opacity = '1';
					alert('Error: ' + (data.message || 'Failed to delete image'));
				}
			})
			.catch(function(error) {
				imageElement.style.opacity = '1';
				alert('Error: Failed to delete image');
			});
		}

		function saveImageCaption(imageId) {
			var captionInput = document.getElementById('caption-' + imageId);
			if (!captionInput) {
				return;
			}

			var caption = captionInput.value.trim();
			captionInput.disabled = true;

			fetch('<?php echo site_url("notes/update_image_caption"); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: 'image_id=' + encodeURIComponent(imageId) + '&caption=' + encodeURIComponent(caption)
			})
			.then(function(response) { return response.json(); })
			.then(function(data) {
				captionInput.disabled = false;
				if (data.success) {
					captionInput.classList.add('border-success');
					setTimeout(function() {
						captionInput.classList.remove('border-success');
					}, 2000);
				} else {
					captionInput.classList.add('border-danger');
					alert('Error: ' + (data.message || 'Failed to save caption'));
					setTimeout(function() {
						captionInput.classList.remove('border-danger');
					}, 2000);
				}
			})
			.catch(function(error) {
				captionInput.disabled = false;
				captionInput.classList.add('border-danger');
				alert('Error: Failed to save caption');
				setTimeout(function() {
					captionInput.classList.remove('border-danger');
				}, 2000);
			});
		}
	</script>
  </div>

  <?php } ?>
</div>


</div>
</div>

