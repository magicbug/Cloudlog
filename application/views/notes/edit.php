<main class="container notes notes-page">
<?php foreach ($note->result() as $row) {
	$defaultCategories = array('General', 'Antennas', 'Satellites');
	$existingCategories = isset($categories) && is_array($categories) ? $categories : array();
	$categoryOptions = array_values(array_unique(array_merge($defaultCategories, $existingCategories, array($row->cat))));
	$selectedCategory = set_value('category', $row->cat);
	$entryImages = isset($diary_images[$row->id]) ? $diary_images[$row->id] : array();
	$isPublicInitial = ((int)($row->is_public ?? 0) === 1);
	$dateValue = '';
	$dateDisplayValue = '';
	if (!is_null($row->created_at) && $row->created_at !== '' && $row->created_at !== '0000-00-00 00:00:00') {
		$dateValue = date('Y-m-d', strtotime($row->created_at));
		$dateDisplayValue = date('M d, Y \a\t g:i A', strtotime($row->created_at));
	}
?>
	<form method="post" action="<?php echo site_url('notes/edit'); ?>/<?php echo $id; ?>" name="notes_add" id="notes_add" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $id; ?>" />

		<!-- Unified Topbar Card -->
		<div class="card notes-topbar mb-3">
			<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
				<div class="d-flex align-items-center gap-2">
					<a href="<?php echo site_url('notes'); ?>" class="btn btn-sm btn-outline-secondary">
						<i class="fas fa-arrow-left me-1"></i><?php echo lang('notes_menu_notes'); ?>
					</a>
					<div>
						<h1 class="h5 mb-0 fw-bold"><?php echo lang('notes_edit_note'); ?></h1>
						<div class="small text-body-secondary">Rich-text note &amp; Station Diary editor</div>
					</div>
				</div>
				<div class="d-flex align-items-center gap-2">
					<a href="<?php echo site_url('notes/view/' . $id); ?>" class="btn btn-sm btn-outline-secondary">
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
							<input type="text" name="title" class="form-control fw-semibold" value="<?php echo set_value('title', $row->title); ?>" id="inputTitle" placeholder="e.g. Field day setup" autocomplete="off" required>
						</div>

						<div>
							<label for="noteContent" class="notes-field-label"><?php echo lang('notes_input_notes_content'); ?></label>
							<textarea name="content" id="noteContent" class="form-control"><?php echo set_value('content', $row->note); ?></textarea>
						</div>
					</div>
					<div class="card-footer notes-card-footer px-3 px-md-4 py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
						<span class="small text-body-secondary font-monospace">
							<?php echo !empty($dateDisplayValue) ? 'Saved: ' . $dateDisplayValue : ''; ?>
						</span>
						<div class="d-flex gap-2">
							<a href="<?php echo site_url('notes/view/' . $id); ?>" class="btn btn-outline-secondary btn-sm px-3">
								<i class="fas fa-times me-1"></i><?php echo lang('general_word_cancel') ?: 'Cancel'; ?>
							</a>
							<button type="submit" value="Submit" class="btn btn-primary btn-sm px-4">
								<i class="fas fa-save me-1"></i><?php echo lang('notes_input_btn_save_note'); ?>
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Right 4 cols: Organization + Station Diary + Filters + Photos + Advanced -->
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
					<div class="card-header py-2 px-3 d-flex align-items-center justify-content-between gap-2">
						<h2 class="h6 mb-0 small text-uppercase fw-bold text-body-secondary"><i class="fas fa-eye me-1"></i>Station Diary</h2>
						<span id="visibilityStatusBadge" class="badge rounded-pill <?php echo $isPublicInitial ? 'text-bg-success' : 'text-bg-secondary'; ?>">
							<i class="fas <?php echo $isPublicInitial ? 'fa-globe' : 'fa-lock'; ?> me-1"></i><?php echo $isPublicInitial ? 'Public' : 'Private'; ?>
						</span>
					</div>
					<div class="card-body p-3">
						<?php if (isset($public_station_diary_enabled) && !$public_station_diary_enabled) { ?>
							<div class="alert alert-warning small py-2 mb-3">Public Station Diary is globally disabled. Entries will remain private.</div>
						<?php } ?>
						<div class="form-check form-switch mb-3">
							<input class="form-check-input" type="checkbox" value="1" id="isPublicEntry" name="is_public" <?php echo set_value('is_public', ($isPublicInitial ? '1' : '')) ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
							<label class="form-check-label small" for="isPublicEntry">
								<strong class="d-block"><i class="fas fa-globe me-1"></i>Make entry public</strong>
								<span class="text-body-secondary">Only applies to &ldquo;Station Diary&rdquo; category</span>
							</label>
						</div>
						<div class="form-check form-switch mb-3">
							<input class="form-check-input" type="checkbox" value="1" id="includeQsoSummary" name="include_qso_summary" <?php echo set_value('include_qso_summary', ((int)($row->include_qso_summary ?? 0) === 1 ? '1' : '')) ? 'checked' : ''; ?> <?php echo (isset($public_station_diary_enabled) && !$public_station_diary_enabled) ? 'disabled' : ''; ?>>
							<label class="form-check-label small" for="includeQsoSummary">
								<strong class="d-block">Include QSO summary</strong>
								<span class="text-body-secondary">Shows contact statistics on public page</span>
							</label>
						</div>
						<div id="logbookSelectorContainer" style="<?php echo set_value('include_qso_summary', ((int)($row->include_qso_summary ?? 0) === 1 ? '1' : '')) ? '' : 'display:none;'; ?>">
							<label for="logbookSelect" class="notes-field-label">Logbook <span class="text-danger">*</span></label>
							<select name="logbook_id" class="form-select form-select-sm" id="logbookSelect" required>
								<option value="">-- Choose a logbook --</option>
								<?php if (isset($user_logbooks) && $user_logbooks->num_rows() > 0) {
									$selected_logbook = set_value('logbook_id', $row->logbook_id ?? '');
									foreach ($user_logbooks->result() as $logbook) { ?>
										<option value="<?php echo $logbook->logbook_id; ?>" <?php echo $selected_logbook == $logbook->logbook_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($logbook->logbook_name, ENT_QUOTES); ?></option>
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
								<input type="date" class="form-control form-control-sm" id="qsoDateStart" name="qso_date_start" value="<?php echo set_value('qso_date_start', !empty($row->qso_date_start) ? $row->qso_date_start : ''); ?>">
							</div>
							<div class="mb-3">
								<label for="qsoDateEnd" class="notes-field-label">Date Range End</label>
								<input type="date" class="form-control form-control-sm" id="qsoDateEnd" name="qso_date_end" value="<?php echo set_value('qso_date_end', !empty($row->qso_date_end) ? $row->qso_date_end : ''); ?>">
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="1" id="qsoSatelliteOnly" name="qso_satellite_only" <?php echo set_value('qso_satellite_only', ((int)($row->qso_satellite_only ?? 0) === 1 ? '1' : '')) ? 'checked' : ''; ?>>
								<label class="form-check-label small" for="qsoSatelliteOnly">
									<i class="fas fa-satellite me-1"></i>Satellite QSOs only
								</label>
							</div>
						</div>
					</div>
				</div>

				<!-- Diary Photos Card -->
				<div class="card notes-side-card mb-0">
					<div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
						<h2 class="h6 mb-0 small text-uppercase fw-bold text-body-secondary"><i class="fas fa-image me-1"></i>Diary Photos</h2>
						<?php if (!empty($entryImages)) { ?>
							<span class="badge rounded-pill text-bg-primary"><?php echo count($entryImages); ?></span>
						<?php } ?>
					</div>
					<div class="card-body p-3">
						<?php if (!empty($entryImages)) { ?>
							<div class="alert alert-info py-2 px-2 small mb-3">
								<i class="fas fa-info-circle me-1"></i>Use <code>[image:<?php echo $entryImages[0]->id; ?>]</code> (or <code>:left</code>, <code>:center</code>, <code>:small</code>) inline.
							</div>
							<div class="d-flex flex-column gap-3 mb-3" id="currentImagesContainer">
								<?php foreach ($entryImages as $image) { ?>
									<div class="border rounded p-2" id="image-<?php echo $image->id; ?>">
										<div class="position-relative mb-2">
											<img src="<?php echo base_url() . ltrim($image->filename, '/'); ?>" class="img-fluid rounded w-100" style="max-height: 140px; object-fit: cover;" alt="Diary image">
											<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="deleteDiaryImage(<?php echo $image->id; ?>)" title="Delete image">
												<i class="fas fa-trash"></i>
											</button>
										</div>
										<div class="d-flex align-items-center justify-content-between small mb-1">
											<span>ID: <code class="user-select-all"><?php echo $image->id; ?></code></span>
											<button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" onclick="navigator.clipboard.writeText('[image:<?php echo $image->id; ?>]')" title="Copy shortcode">
												<i class="fas fa-copy me-1"></i>Copy shortcode
											</button>
										</div>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control form-control-sm" id="caption-<?php echo $image->id; ?>" value="<?php echo htmlspecialchars($image->caption ?? '', ENT_QUOTES); ?>" placeholder="Add caption">
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
								<?php } ?>
							</div>
						<?php } ?>

						<label for="diaryImages" class="notes-field-label">Add New Images</label>
						<input type="file" class="form-control form-control-sm" id="diaryImages" name="diary_images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
						<div class="form-text">Max 8 MB per image. Auto-resized and compressed.</div>
					</div>
				</div>

				<!-- Advanced Settings Card (Collapsible) -->
				<div class="card notes-side-card mb-0">
					<div class="card-header py-2 px-3">
						<button type="button" class="btn btn-link btn-sm p-0 text-decoration-none w-100 d-flex align-items-center justify-content-between text-body-secondary fw-bold text-uppercase small" data-bs-toggle="collapse" data-bs-target="#collapseAdvanced" aria-expanded="false" aria-controls="collapseAdvanced">
							<span><i class="fas fa-cog me-1"></i>Advanced Settings</span>
							<i class="fas fa-chevron-down fa-xs"></i>
						</button>
					</div>
					<div id="collapseAdvanced" class="collapse">
						<div class="card-body p-3">
							<label for="createdAtInput" class="notes-field-label">Created Date</label>
							<input type="date" name="created_at" class="form-control form-control-sm" id="createdAtInput" value="<?php echo set_value('created_at', $dateValue); ?>">
							<div class="form-text">Current: <strong><?php echo !empty($dateDisplayValue) ? $dateDisplayValue : 'N/A'; ?></strong></div>
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
			var visibilityStatusBadge = document.getElementById('visibilityStatusBadge');
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

			if (!isPublicEntry) {
				return;
			}

			function updateVisibilityBadge() {
				if (!visibilityStatusBadge) {
					return;
				}
				if (isPublicEntry.checked) {
					visibilityStatusBadge.classList.remove('text-bg-secondary');
					visibilityStatusBadge.classList.add('text-bg-success');
					visibilityStatusBadge.innerHTML = '<i class="fas fa-globe me-1"></i>Public';
				} else {
					visibilityStatusBadge.classList.remove('text-bg-success');
					visibilityStatusBadge.classList.add('text-bg-secondary');
					visibilityStatusBadge.innerHTML = '<i class="fas fa-lock me-1"></i>Private';
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
			if (!imageElement) return;
			imageElement.style.opacity = '0.5';

			fetch('<?php echo site_url("notes/delete_diary_image"); ?>', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'image_id=' + encodeURIComponent(imageId)
			})
			.then(function(response) { return response.json(); })
			.then(function(data) {
				if (data.success) {
					imageElement.style.transition = 'opacity 0.3s';
					imageElement.style.opacity = '0';
					setTimeout(function() {
						imageElement.remove();
						var container = document.getElementById('currentImagesContainer');
						if (container && container.children.length === 0) {
							container.remove();
						}
					}, 300);
				} else {
					imageElement.style.opacity = '1';
					alert('Error: ' + (data.message || 'Failed to delete image'));
				}
			})
			.catch(function() {
				imageElement.style.opacity = '1';
				alert('Error: Failed to delete image');
			});
		}

		function saveImageCaption(imageId) {
			var captionInput = document.getElementById('caption-' + imageId);
			if (!captionInput) return;
			var caption = captionInput.value.trim();
			captionInput.disabled = true;

			fetch('<?php echo site_url("notes/update_image_caption"); ?>', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'image_id=' + encodeURIComponent(imageId) + '&caption=' + encodeURIComponent(caption)
			})
			.then(function(response) { return response.json(); })
			.then(function(data) {
				captionInput.disabled = false;
				if (data.success) {
					captionInput.classList.add('border-success');
					setTimeout(function() { captionInput.classList.remove('border-success'); }, 2000);
				} else {
					captionInput.classList.add('border-danger');
					alert('Error: ' + (data.message || 'Failed to save caption'));
					setTimeout(function() { captionInput.classList.remove('border-danger'); }, 2000);
				}
			})
			.catch(function() {
				captionInput.disabled = false;
				captionInput.classList.add('border-danger');
				alert('Error: Failed to save caption');
				setTimeout(function() { captionInput.classList.remove('border-danger'); }, 2000);
			});
		}
	</script>
<?php } ?>
</main>

