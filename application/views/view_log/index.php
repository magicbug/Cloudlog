<?php
$can_log = (($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE);
$show_map = ($this->optionslib->get_option('logbook_map') != "false");
$logbook_name = isset($active_logbook_name) ? $active_logbook_name : $this->logbooks_model->find_name($this->session->userdata('active_station_logbook'));
$station_name = isset($active_station_name) ? $active_station_name : $this->stations->find_name();
$total_qso_count = isset($total_qsos) ? (int) $total_qsos : 0;
$page_offset = isset($offset) ? (int) $offset : 0;
$page_size = isset($per_page) ? (int) $per_page : 25;
$page_rows = ($results && is_object($results)) ? (int) $results->num_rows() : 0;
$range_start = $page_rows > 0 ? ($page_offset + 1) : 0;
$range_end = $page_offset + $page_rows;
?>

<style>
.logbook-page {
	padding-top: 1.25rem;
	padding-bottom: 3rem;
}

.logbook-header {
	padding-bottom: 0.9rem;
	margin-bottom: 1.25rem;
	border-bottom: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.22));
}

.logbook-context-bar {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 0.45rem;
	font-size: 0.84rem;
}

.logbook-meta-pill {
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
	padding: 0.22rem 0.65rem;
	border-radius: 999px;
	border: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.28));
	color: inherit;
	font-weight: 500;
	text-decoration: none;
	transition: border-color 0.15s ease, background-color 0.15s ease;
}

a.logbook-meta-pill:hover {
	border-color: var(--bs-primary, #0d6efd);
	background-color: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.08);
}

.logbook-page .card {
	border-radius: 0.5rem;
	margin-bottom: 0;
}

.logbook-page .card-header {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
	gap: 0.5rem;
	font-weight: 600;
	font-size: 0.92rem;
	padding: 0.65rem 1rem;
}

.logbook-page .card-header-title {
	display: inline-flex;
	align-items: center;
	gap: 0.45rem;
}

.logbook-page .card-header-title i {
	opacity: 0.72;
	font-size: 0.88rem;
}

.logbook-map-card #map {
	width: 100%;
	height: 320px;
}

@media (max-width: 991.98px) {
	.logbook-map-card #map {
		height: 250px;
	}
}

@media (max-width: 575.98px) {
	.logbook-map-card #map {
		height: 210px;
	}
}

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
	border-left: 4px solid var(--bs-border-color, #ddd);
	margin: 1.5rem 0;
	padding: 0.75rem 1rem;
	font-style: italic;
}

#quickQuillArea .ql-editor pre {
	border: 1px solid var(--bs-border-color, #ddd);
	border-radius: 4px;
	padding: 1rem;
	margin: 1rem 0;
	overflow-x: auto;
}

#quickQuillArea .ql-editor code {
	padding: 0.2rem 0.4rem;
	border-radius: 3px;
	font-family: 'Courier New', monospace;
	font-size: 0.9em;
}

#quickQuillArea .ql-editor hr {
	margin: 1.5rem 0;
	border: none;
	border-top: 2px solid var(--bs-border-color, #e0e0e0);
}

#quickQuillArea .ql-editor table {
	width: 100%;
	border-collapse: collapse;
	margin: 1rem 0;
}

#quickQuillArea .ql-editor table th,
#quickQuillArea .ql-editor table td {
	border: 1px solid var(--bs-border-color, #ddd);
	padding: 0.75rem;
}

#quickQuillArea .ql-editor img {
	max-width: 100%;
	height: auto;
	margin: 1rem 0;
	border-radius: 4px;
}
</style>

<div class="container logbook-page">
	<!-- Page Header -->
	<div class="logbook-header d-flex flex-wrap align-items-center justify-content-between gap-3">
		<div>
			<div class="d-flex flex-wrap align-items-center gap-2 mb-1">
				<h1 class="h3 mb-0 fw-bold"><?php echo lang('gen_hamradio_logbook'); ?></h1>
				<?php if ($total_qso_count > 0) { ?>
					<span class="badge text-bg-secondary fw-normal"><?php echo number_format($total_qso_count); ?> QSOs</span>
				<?php } ?>
			</div>

			<?php if ($results) { ?>
				<div class="logbook-context-bar text-muted">
					<a href="<?php echo site_url('logbooks'); ?>" class="logbook-meta-pill" title="Manage Station Logbooks">
						<i class="fas fa-book opacity-75"></i>
						<span><?php echo htmlspecialchars((string) $logbook_name, ENT_QUOTES, 'UTF-8'); ?></span>
					</a>
					<a href="<?php echo site_url('station'); ?>" class="logbook-meta-pill" title="Manage Station Locations">
						<i class="fas fa-map-marker-alt opacity-75"></i>
						<span><?php echo htmlspecialchars((string) $station_name, ENT_QUOTES, 'UTF-8'); ?></span>
					</a>
					<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" data-bs-toggle="modal" data-bs-target="#logbookQuickSwitchModal">
						<i class="fas fa-exchange-alt me-1"></i><?php echo lang('station_logbooks_change'); ?>
					</button>
				</div>
			<?php } ?>
		</div>

		<div class="d-flex flex-wrap align-items-center gap-2">
			<?php if ($can_log) { ?>
				<a href="<?php echo site_url('qso?manual=0'); ?>" class="btn btn-primary btn-sm">
					<i class="fas fa-pencil-alt me-1"></i> Log QSO
				</a>
			<?php } ?>
			<?php if ($results && $this->session->userdata('user_show_notes') == 1) { ?>
				<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#stationDiaryModal">
					<i class="fas fa-book me-1"></i> Station Diary
				</button>
			<?php } ?>
			<a href="<?php echo site_url('logbookadvanced'); ?>" class="btn btn-outline-secondary btn-sm">
				<i class="fas fa-sliders-h me-1"></i> Advanced
			</a>
		</div>
	</div>

	<?php if ($this->session->flashdata('notice')) { ?>
		<div class="alert alert-info alert-dismissible fade show py-2 mb-3" role="alert">
			<i class="fas fa-info-circle me-1"></i>
			<?php echo $this->session->flashdata('notice'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- Framed Collapsible Map -->
	<?php if ($show_map) { ?>
		<div class="card logbook-map-card overflow-hidden mb-4">
			<div class="card-header py-2 px-3">
				<span class="card-header-title">
					<i class="fas fa-globe-europe"></i>
					<span>QSO Map</span>
					<?php if ($page_rows > 0) { ?>
						<span class="badge text-bg-secondary fw-normal">Page: <?php echo number_format($range_start); ?>&ndash;<?php echo number_format($range_end); ?></span>
					<?php } ?>
				</span>
				<button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" id="toggleLogbookMapBtn" aria-expanded="true" aria-controls="logbookMapBody">
					<i class="fas fa-chevron-up me-1" id="toggleLogbookMapIcon"></i><span id="toggleLogbookMapText">Hide Map</span>
				</button>
			</div>
			<div id="logbookMapBody">
				<div id="map" class="map-leaflet"></div>
			</div>
		</div>
	<?php } ?>

	<!-- QSO Logbook Table Card -->
	<div class="card logbook-table-card">
		<div class="card-header">
			<span class="card-header-title">
				<i class="fas fa-list"></i>
				<span>Contacts</span>
				<?php if ($total_qso_count > 0 && $page_rows > 0) { ?>
					<span class="small fw-normal text-muted">Showing <?php echo number_format($range_start); ?>&ndash;<?php echo number_format($range_end); ?> of <?php echo number_format($total_qso_count); ?></span>
				<?php } ?>
			</span>
			<?php if ($can_log) { ?>
				<span class="d-none d-md-inline-flex align-items-center gap-2 small fw-normal text-muted" style="font-size: 0.78rem;">
					<span><span class="qsl-green">&#9650;</span> Sent</span>
					<span><span class="qsl-green">&#9660;</span> Received</span>
					<span><span class="qsl-yellow">&#9650;</span> Queued/Req</span>
				</span>
			<?php } ?>
		</div>
		<div class="card-body p-0">
			<?php $this->load->view('view_log/partial/log_ajax'); ?>
		</div>
	</div>
</div>

<?php if ($results) {
	$this->load->view('logbooks/components/quick_switch_modal');
} ?>

<!-- Station Diary Modal -->
<?php if ($this->session->userdata('user_show_notes') == 1) { ?>
<div class="modal fade" id="stationDiaryModal" tabindex="-1" aria-labelledby="stationDiaryModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="stationDiaryModalLabel"><i class="fas fa-book me-1"></i> Station Diary</h5>
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
											<strong>Make entry public</strong>
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
									<small class="text-muted d-block">Max 8 MB per image. Auto-resized and compressed.</small>
									<div class="alert alert-info mt-3 small mb-0">
										<strong>Tip:</strong> After saving, edit the note to see image IDs and add captions. Then use shortcodes like <code>[image:ID]</code> to display images inline.
									</div>
								</div>
							</div>
						</div>
					</div>

					<input type="hidden" name="category" value="Station Diary">
				</div>
				<div class="modal-footer">
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

	if (messagesDiv && footerMessagesDiv) {
		var observer = new MutationObserver(function() {
			footerMessagesDiv.innerHTML = messagesDiv.innerHTML.trim() !== '' ? messagesDiv.innerHTML : '';
		});
		observer.observe(messagesDiv, { childList: true, subtree: true, characterData: true });

		document.body.addEventListener('htmx:afterSwap', function(event) {
			if (event.detail.target && event.detail.target.id === 'diaryFormMessages') {
				footerMessagesDiv.innerHTML = messagesDiv.innerHTML;
			}
		});
	}

	if (quickIncludeQso && quickLogbookSelector && quickLogbookSelect) {
		quickIncludeQso.addEventListener('change', function() {
			if (quickIncludeQso.checked) {
				quickLogbookSelector.style.display = 'block';
				quickLogbookSelect.setAttribute('required', 'required');
			} else {
				quickLogbookSelect.removeAttribute('required');
				quickLogbookSelector.style.display = 'none';
			}
		});
	}

	if (typeof Quill !== 'undefined') {
		var quickQuill = new Quill('#quickQuillArea', {
			placeholder: 'Compose an epic...',
			theme: 'snow'
		});

		var stationDiaryForm = document.getElementById('stationDiaryForm');
		if (stationDiaryForm) {
			stationDiaryForm.addEventListener('submit', function(e) {
				var quillText = quickQuill.getText().trim();
				if (quillText.length === 0) {
					e.preventDefault();
					e.stopPropagation();
					if (messagesDiv) {
						messagesDiv.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
							'<i class="fas fa-exclamation-triangle me-2"></i>Please enter some content for your note.' +
							'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
							'</div>';
					}
					return false;
				}
				document.getElementById('quickHiddenArea').value = quickQuill.root.innerHTML;
			});
		}

		var stationDiaryModal = document.getElementById('stationDiaryModal');
		if (stationDiaryModal) {
			stationDiaryModal.addEventListener('hidden.bs.modal', function() {
				quickQuill.setText('');
				document.getElementById('quickHiddenArea').value = '';
				if (messagesDiv) messagesDiv.innerHTML = '';
				if (footerMessagesDiv) footerMessagesDiv.innerHTML = '';
				if (quickIncludeQso) quickIncludeQso.checked = false;
				var quickIsPublic = document.getElementById('quickIsPublic');
				if (quickIsPublic) quickIsPublic.checked = false;
				if (quickLogbookSelector) quickLogbookSelector.style.display = 'none';
			});
		}
	}
});
</script>
<?php } ?>

<script>
(function() {
	var toggleBtn = document.getElementById('toggleLogbookMapBtn');
	var mapBody = document.getElementById('logbookMapBody');
	var icon = document.getElementById('toggleLogbookMapIcon');
	var label = document.getElementById('toggleLogbookMapText');
	var storageKey = 'cloudlog.logbook.mapCollapsed';
	if (!toggleBtn || !mapBody) {
		return;
	}

	function applyState(collapsed, triggerResize) {
		mapBody.classList.toggle('d-none', collapsed);
		toggleBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
		if (icon) {
			icon.className = collapsed ? 'fas fa-chevron-down me-1' : 'fas fa-chevron-up me-1';
		}
		if (label) {
			label.textContent = collapsed ? 'Show Map' : 'Hide Map';
		}
		if (!collapsed && triggerResize && typeof map !== 'undefined' && map && typeof map.invalidateSize === 'function') {
			setTimeout(function() {
				map.invalidateSize();
			}, 50);
		}
	}

	var initiallyCollapsed = false;
	try {
		initiallyCollapsed = localStorage.getItem(storageKey) === '1';
	} catch (e) {}

	if (initiallyCollapsed) {
		applyState(true, false);
	}

	toggleBtn.addEventListener('click', function() {
		var nextCollapsed = !mapBody.classList.contains('d-none');
		applyState(nextCollapsed, true);
		try {
			localStorage.setItem(storageKey, nextCollapsed ? '1' : '0');
		} catch (e) {}
	});
})();
</script>
