<style>
#quickQuillArea .ql-editor {
	font-size: 1rem;
	line-height: 1.6;
	padding: 1.5rem;
}

#quickQuillArea .ql-editor p {
	margin-bottom: 1rem;
}

#quickQuillArea .ql-editor h1,
#quickQuillArea .ql-editor h2,
#quickQuillArea .ql-editor h3,
#quickQuillArea .ql-editor h4,
#quickQuillArea .ql-editor h5,
#quickQuillArea .ql-editor h6 {
	margin-top: 1.5rem;
	margin-bottom: 0.75rem;
	line-height: 1.3;
	font-weight: 600;
}

#quickQuillArea .ql-editor h1:first-child,
#quickQuillArea .ql-editor h2:first-child,
#quickQuillArea .ql-editor h3:first-child,
#quickQuillArea .ql-editor h4:first-child,
#quickQuillArea .ql-editor h5:first-child,
#quickQuillArea .ql-editor h6:first-child {
	margin-top: 0;
}

#quickQuillArea .ql-editor ul,
#quickQuillArea .ql-editor ol {
	margin-bottom: 1rem;
	padding-left: 2rem;
}

#quickQuillArea .ql-editor li {
	margin-bottom: 0.5rem;
}

#quickQuillArea .ql-editor li p {
	margin-bottom: 0.25rem;
}

#quickQuillArea .ql-editor blockquote {
	border-left: 4px solid #ddd;
	margin: 1.5rem 0;
	padding: 0.75rem 1rem;
	background-color: #f9f9f9;
	font-style: italic;
	color: #666;
}

#quickQuillArea .ql-editor pre {
	background-color: #f5f5f5;
	border: 1px solid #ddd;
	border-radius: 4px;
	padding: 1rem;
	margin: 1rem 0;
	overflow-x: auto;
}

#quickQuillArea .ql-editor code {
	background-color: #f5f5f5;
	padding: 0.2rem 0.4rem;
	border-radius: 3px;
	font-family: 'Courier New', monospace;
	font-size: 0.9em;
}

#quickQuillArea .ql-editor hr {
	margin: 1.5rem 0;
	border: none;
	border-top: 2px solid #e0e0e0;
}

#quickQuillArea .ql-editor table {
	width: 100%;
	border-collapse: collapse;
	margin: 1rem 0;
}

#quickQuillArea .ql-editor table th,
#quickQuillArea .ql-editor table td {
	border: 1px solid #ddd;
	padding: 0.75rem;
}

#quickQuillArea .ql-editor table th {
	background-color: #f5f5f5;
	font-weight: 600;
}

#quickQuillArea .ql-editor img {
	max-width: 100%;
	height: auto;
	margin: 1rem 0;
	border-radius: 4px;
}

#quickQuillArea .ql-editor a {
	color: #0d6efd;
	text-decoration: underline;
}

@media (max-width: 767.98px) {
	.logbook-info-bar .logbook-diary-action .btn {
		width: 100%;
	}
}

@media (min-width: 768px) {
	.logbook-info-bar .logbook-diary-action {
		width: auto !important;
	}
}
</style>

<div class="alert alert-secondary" role="alert" style="margin-bottom: 0px !important;">
<div class="container">
	<?php if ($results) { ?>
		<div class="logbook-info-bar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
			<p class="mb-0 small d-flex flex-wrap align-items-center gap-1"><?php echo lang('gen_hamradio_logbook'); ?>: <span class="badge text-bg-info"><?php echo $this->logbooks_model->find_name($this->session->userdata('active_station_logbook')); ?></span> <?php echo lang('general_word_location'); ?>: <span class="badge text-bg-info"><?php echo $this->stations->find_name(); ?></span></p>
			<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
				<div class="logbook-diary-action w-100">
					<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#stationDiaryModal">
					<i class="fas fa-book"></i> Station Diary
					</button>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
</div>

<div class="container logbook">

	<h2><?php echo lang('gen_hamradio_logbook'); ?></h2>
	<?php if($this->session->flashdata('notice')) { ?>
	<div class="alert alert-info" role="alert">
	  <?php echo $this->session->flashdata('notice'); ?>
	</div>
	<?php } ?>
</div>
	
<?php if($this->optionslib->get_option('logbook_map') != "false") { ?>
	<!-- Map -->
	<div id="map" class="map-leaflet" style="width: 100%; height: 350px"></div>
<?php } ?>

<div style="padding-top: 10px; margin-top: 0px;" class="container logbook">
	<?php $this->load->view('view_log/partial/log_ajax') ?>
</div>

<!-- Station Diary Modal -->
<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
<div class="modal fade" id="stationDiaryModal" tabindex="-1" aria-labelledby="stationDiaryModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="stationDiaryModalLabel"><i class="fas fa-book"></i> Station Diary</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="stationDiaryForm" hx-post="<?php echo site_url('notes/quick_add'); ?>" hx-target="#diaryFormMessages" hx-encoding="multipart/form-data">
				<div class="modal-body">
					<div id="diaryFormMessages"></div>
					
					<div class="mb-3">
						<label for="diaryTitle" class="form-label fw-semibold">Title</label>
						<input type="text" class="form-control" id="diaryTitle" name="title" required placeholder="e.g. Good conditions on 20m">
					</div>

					<div class="mb-3">
						<label for="quickHiddenArea" class="form-label fw-semibold">Note</label>
						<div id="quickQuillArea" class="border rounded" style="height: 200px;"></div>
						<textarea name="content" style="display:none" id="quickHiddenArea"></textarea>
					</div>

					<!-- Accordion for Optional Settings -->
					<div class="accordion" id="quickNoteAccordion">
						
						<!-- Station Diary Settings -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingQuickDiary">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQuickDiary" aria-expanded="false" aria-controls="collapseQuickDiary">
									<i class="fas fa-eye me-2"></i> Station Diary Settings
								</button>
							</h2>
							<div id="collapseQuickDiary" class="accordion-collapse collapse" aria-labelledby="headingQuickDiary" data-bs-parent="#quickNoteAccordion">
								<div class="accordion-body">
									<div class="form-check mb-3">
										<input class="form-check-input" type="checkbox" value="1" id="quickIsPublic" name="is_public">
										<label class="form-check-label" for="quickIsPublic">
											<strong>🌍 Make entry public</strong>
											<small class="d-block text-muted">Visible on Station Diary page</small>
										</label>
									</div>
									<div class="form-check mb-3">
										<input class="form-check-input" type="checkbox" value="1" id="quickIncludeQso" name="include_qso_summary">
										<label class="form-check-label" for="quickIncludeQso">
											<strong>Include QSO summary</strong>
											<small class="d-block text-muted">Shows contact statistics</small>
										</label>
									</div>
									<div id="quickLogbookSelector" style="display:none;">
										<label for="quickLogbookSelect" class="form-label">Logbook <span class="text-danger">*</span></label>
										<select name="logbook_id" class="form-select" id="quickLogbookSelect">
											<option value="">-- Choose a logbook --</option>
											<?php 
											$this->load->model('logbooks_model');
											$user_logbooks = $this->logbooks_model->show_all();
											if ($user_logbooks->num_rows() > 0) {
												foreach ($user_logbooks->result() as $logbook) { ?>
													<option value="<?php echo $logbook->logbook_id; ?>"><?php echo htmlspecialchars($logbook->logbook_name, ENT_QUOTES); ?></option>
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
							<h2 class="accordion-header" id="headingQuickFilters">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQuickFilters" aria-expanded="false" aria-controls="collapseQuickFilters">
									<i class="fas fa-filter me-2"></i> QSO Summary Filters
								</button>
							</h2>
							<div id="collapseQuickFilters" class="accordion-collapse collapse" aria-labelledby="headingQuickFilters" data-bs-parent="#quickNoteAccordion">
								<div class="accordion-body">
									<p class="text-muted small mb-3">Control which QSOs appear in the summary:</p>
									<div class="row g-2 mb-3">
										<div class="col-6">
											<label for="quickQsoDateStart" class="form-label">Date Start</label>
											<input type="date" class="form-control" id="quickQsoDateStart" name="qso_date_start">
											<small class="text-muted">Leave empty for entry date</small>
										</div>
										<div class="col-6">
											<label for="quickQsoDateEnd" class="form-label">Date End</label>
											<input type="date" class="form-control" id="quickQsoDateEnd" name="qso_date_end">
											<small class="text-muted">Leave empty for today</small>
										</div>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="1" id="quickQsoSatOnly" name="qso_satellite_only">
										<label class="form-check-label" for="quickQsoSatOnly">
											<i class="fas fa-satellite me-1"></i>Satellite QSOs only
										</label>
									</div>
								</div>
							</div>
						</div>

						<!-- Images -->
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingQuickImages">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQuickImages" aria-expanded="false" aria-controls="collapseQuickImages">
									<i class="fas fa-image me-2"></i> Images
								</button>
							</h2>
							<div id="collapseQuickImages" class="accordion-collapse collapse" aria-labelledby="headingQuickImages" data-bs-parent="#quickNoteAccordion">
								<div class="accordion-body">
									<label for="quickDiaryImages" class="form-label fw-semibold">Add images</label>
									<input type="file" class="form-control" id="quickDiaryImages" name="diary_images[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
									<small class="text-muted d-block">Max 2 MB per image. Auto-resized and compressed.</small>
									<div class="alert alert-info mt-3 small mb-0">
										<strong>💡 Tip:</strong> After saving, edit the note to see image IDs and add captions. Then use shortcodes like <code>[image:ID]</code> to display images inline.
									</div>
								</div>
							</div>
						</div>
					</div>

					<input type="hidden" name="category" value="Station Diary">
				</div>
				<div class="modal-footer bg-light">
					<div id="diaryFormFooterMessages" class="flex-grow-1 me-3"></div>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="fas fa-times me-1"></i> Close
					</button>
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-save me-1"></i> Save Note
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var quickIncludeQso = document.getElementById('quickIncludeQso');
	var quickLogbookSelector = document.getElementById('quickLogbookSelector');
	var quickLogbookSelect = document.getElementById('quickLogbookSelect');
	var messagesDiv = document.getElementById('diaryFormMessages');
	var footerMessagesDiv = document.getElementById('diaryFormFooterMessages');
	
	// Mirror messages from body to footer using MutationObserver
	if (messagesDiv && footerMessagesDiv) {
		var observer = new MutationObserver(function(mutations) {
			// Copy content to footer
			if (messagesDiv.innerHTML.trim() !== '') {
				footerMessagesDiv.innerHTML = messagesDiv.innerHTML;
			} else {
				footerMessagesDiv.innerHTML = '';
			}
		});
		observer.observe(messagesDiv, { 
			childList: true, 
			subtree: true,
			characterData: true 
		});
	}
	
	// Also listen to HTMX events to ensure we catch the response
	if (messagesDiv && footerMessagesDiv) {
		document.body.addEventListener('htmx:afterSwap', function(event) {
			if (event.detail.target.id === 'diaryFormMessages') {
				footerMessagesDiv.innerHTML = messagesDiv.innerHTML;
			}
		});
	}
	
	if (quickIncludeQso && quickLogbookSelector && quickLogbookSelect) {
		quickIncludeQso.addEventListener('change', function() {
			if (quickIncludeQso.checked) {
				quickLogbookSelector.style.display = 'block';
				quickLogbookSelect.setAttribute('required', 'required');
				// Fade in effect
				quickLogbookSelector.style.opacity = '0';
				setTimeout(function() {
					quickLogbookSelector.style.transition = 'opacity 0.3s';
					quickLogbookSelector.style.opacity = '1';
				}, 10);
			} else {
				quickLogbookSelect.removeAttribute('required');
				quickLogbookSelector.style.opacity = '0';
				setTimeout(function() {
					quickLogbookSelector.style.display = 'none';
				}, 300);
			}
		});
	}
	
	// Initialize Quill editor for Station Diary modal
	if (typeof Quill !== 'undefined') {
		var quickQuill = new Quill('#quickQuillArea', {
			placeholder: 'Compose an epic...',
			theme: 'snow'
		});
		
		// Copy Quill content to hidden textarea on form submit
		var stationDiaryForm = document.getElementById('stationDiaryForm');
		if (stationDiaryForm) {
			stationDiaryForm.addEventListener('submit', function(e) {
				var quillText = quickQuill.getText().trim();
				
				// Validate that Quill has content
				if (quillText.length === 0) {
					e.preventDefault();
					e.stopPropagation();
					
					// Show error message in both body and footer
					var errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
						'<i class="fas fa-exclamation-triangle me-2"></i>Please enter some content for your note.' +
						'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
						'</div>';
					if (messagesDiv) {
						messagesDiv.innerHTML = errorHtml;
					}
					return false;
				}
				
				// Copy Quill HTML to hidden textarea
				document.getElementById('quickHiddenArea').value = quickQuill.root.innerHTML;
			});
		}
		
		// Reset Quill content when modal is hidden
		var stationDiaryModal = document.getElementById('stationDiaryModal');
		if (stationDiaryModal) {
			stationDiaryModal.addEventListener('hidden.bs.modal', function() {
				quickQuill.setText('');
				document.getElementById('quickHiddenArea').value = '';
				// Clear any error messages in both locations
				if (messagesDiv) {
					messagesDiv.innerHTML = '';
				}
				if (footerMessagesDiv) {
					footerMessagesDiv.innerHTML = '';
				}
				// Reset checkboxes and hide logbook selector
				if (quickIncludeQso) quickIncludeQso.checked = false;
				var quickIsPublic = document.getElementById('quickIsPublic');
				if (quickIsPublic) quickIsPublic.checked = false;
				if (quickLogbookSelector) {
					quickLogbookSelector.style.display = 'none';
					quickLogbookSelector.style.opacity = '1';
				}
			});
		}
	}
});
</script>
<?php } ?>
