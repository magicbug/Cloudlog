<?php
$defaultCategories = array('General', 'Antennas', 'Satellites');
$existingCategories = isset($categories) && is_array($categories) ? $categories : array();
$categoryOptions = array_values(array_unique(array_merge($defaultCategories, $existingCategories)));
$activeSearch = isset($filters['search']) ? $filters['search'] : (isset($filters['q']) ? $filters['q'] : '');
$activeCategory = isset($filters['category']) ? $filters['category'] : '';
$activeDateFrom = isset($filters['date_from']) ? $filters['date_from'] : '';
$activeDateTo = isset($filters['date_to']) ? $filters['date_to'] : '';
$hasActiveFilters = ($activeSearch !== '' || $activeCategory !== '' || $activeDateFrom !== '' || $activeDateTo !== '');
$notesCount = $notes->num_rows();
?>
<main class="container notes notes-page">
	<!-- Unified Page Header Card -->
	<div class="card notes-topbar mb-3">
		<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
			<div class="d-flex align-items-center gap-2">
				<span class="notes-brand-icon d-inline-flex align-items-center justify-content-center">
					<i class="fas fa-sticky-note"></i>
				</span>
				<div>
					<h1 class="h5 mb-0 fw-bold"><?php echo lang('notes_menu_notes'); ?></h1>
					<div class="small text-body-secondary">Keep shack notes, antenna logs, and Station Diary entries</div>
				</div>
			</div>

			<div class="d-flex flex-wrap align-items-center gap-2">
				<?php if (isset($has_diary_entries) && $has_diary_entries) { ?>
					<?php if (isset($public_station_diary_enabled) && $public_station_diary_enabled && !empty($public_diary_url)) { ?>
						<a href="<?php echo $public_diary_url; ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="Open your public station diary page">
							<i class="fas fa-globe me-1"></i>Public Diary
						</a>
					<?php } ?>
					<a href="<?php echo site_url('notes/station_diary'); ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Print and view all Station Diary entries">
						<i class="fas fa-book me-1"></i>Station Diary
					</a>
				<?php } ?>
				<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
					<i class="fas fa-tags me-1"></i><?php echo lang('notes_manage_categories') ?: 'Categories'; ?>
				</button>
				<a href="<?php echo site_url('notes/add'); ?>" class="btn btn-primary btn-sm">
					<i class="fas fa-plus me-1"></i><?php echo lang('notes_create_note'); ?>
				</a>
			</div>
		</div>
	</div>

	<?php if ($this->session->flashdata('notice')) { ?>
		<div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
			<?php echo $this->session->flashdata('notice'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- Search + Category Quick-Filter Card -->
	<div class="card notes-filter-card mb-3">
		<div class="card-body py-2 px-3">
			<div class="d-flex d-md-none align-items-center justify-content-between mb-2">
				<span class="small fw-semibold text-body-secondary">Filter &amp; Search</span>
				<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#notesFilterCollapse" aria-expanded="<?php echo $hasActiveFilters ? 'true' : 'false'; ?>" aria-controls="notesFilterCollapse">
					<i class="fas fa-filter me-1"></i>Filters
				</button>
			</div>

			<div id="notesFilterCollapse" class="collapse d-md-block<?php echo $hasActiveFilters ? ' show' : ''; ?>">
				<form class="row g-2 align-items-end mb-2" method="get" action="<?php echo site_url('notes'); ?>">
					<input type="hidden" name="category" id="notesCategoryHidden" value="<?php echo htmlspecialchars($activeCategory, ENT_QUOTES); ?>">
					<div class="col-12 col-md-5">
						<label for="notesSearch" class="visually-hidden">Search</label>
						<div class="input-group input-group-sm">
							<span class="input-group-text"><i class="fas fa-search"></i></span>
							<input type="search" class="form-control" id="notesSearch" name="q" value="<?php echo htmlspecialchars($activeSearch, ENT_QUOTES); ?>" placeholder="Search title or content…">
						</div>
					</div>
					<div class="col-6 col-md-2">
						<label for="notesDateFrom" class="visually-hidden">From</label>
						<input type="date" class="form-control form-control-sm" id="notesDateFrom" name="date_from" value="<?php echo htmlspecialchars($activeDateFrom, ENT_QUOTES); ?>" title="From date">
					</div>
					<div class="col-6 col-md-2">
						<label for="notesDateTo" class="visually-hidden">To</label>
						<input type="date" class="form-control form-control-sm" id="notesDateTo" name="date_to" value="<?php echo htmlspecialchars($activeDateTo, ENT_QUOTES); ?>" title="To date">
					</div>
					<div class="col-12 col-md-3 d-flex gap-2">
						<button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="fas fa-filter me-1"></i>Filter</button>
						<a class="btn btn-outline-secondary btn-sm" href="<?php echo site_url('notes'); ?>">Clear</a>
					</div>
				</form>
			</div>

			<!-- Clickable Category Quick-Pills -->
			<div class="d-flex flex-wrap align-items-center gap-1 pt-1">
				<span class="small text-body-secondary me-1 fw-semibold">Category:</span>
				<a href="<?php echo site_url('notes'); ?>" class="badge rounded-pill text-decoration-none notes-cat-pill <?php echo $activeCategory === '' ? 'is-active' : ''; ?>">All</a>
				<?php foreach ($categoryOptions as $catOption) {
					$catUrl = site_url('notes') . '?category=' . rawurlencode($catOption) . ($activeSearch !== '' ? '&q=' . rawurlencode($activeSearch) : '');
				?>
					<a href="<?php echo $catUrl; ?>" class="badge rounded-pill text-decoration-none notes-cat-pill <?php echo ($activeCategory === $catOption) ? 'is-active' : ''; ?>"><?php echo htmlspecialchars($catOption, ENT_QUOTES); ?></a>
				<?php } ?>
				<span class="ms-auto small font-monospace text-body-secondary"><?php echo $notesCount; ?> <?php echo $notesCount === 1 ? 'note' : 'notes'; ?></span>
			</div>
		</div>
	</div>

	<!-- Notes List Card -->
	<div class="card notes-list-card">
		<?php if ($notesCount > 0) { ?>
			<div class="list-group list-group-flush">
				<?php foreach ($notes->result() as $row) {
					$cleanText = preg_replace('/\[image:[^\]]+\]/i', '', (string)$row->note);
					$plain = trim(preg_replace('/\s+/', ' ', strip_tags($cleanText)));
					$excerpt = mb_substr($plain, 0, 160) . (mb_strlen($plain) > 160 ? '…' : '');
					$catLink = !empty($row->cat) ? site_url('notes') . '?category=' . rawurlencode($row->cat) : '';
					$createdDate = (is_null($row->created_at) || $row->created_at === '' || $row->created_at === '0000-00-00 00:00:00') ? 'N/A' : date('M d, Y \a\t g:i A', strtotime($row->created_at));
					$isStationDiary = strtoupper(trim((string)$row->cat)) === 'STATION DIARY';
					$isPublicDiary = $isStationDiary && isset($row->is_public) && (int)$row->is_public === 1;
				?>
					<div class="list-group-item py-3 px-3 px-md-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
						<div class="flex-grow-1 min-w-0">
							<div class="d-flex align-items-center gap-2 flex-wrap">
								<a class="fw-semibold text-decoration-none notes-item-title" href="<?php echo site_url('notes/view/' . $row->id); ?>"><?php echo htmlspecialchars($row->title, ENT_QUOTES); ?></a>
								<?php if (!empty($row->cat)) { ?>
									<a class="badge rounded-pill text-bg-primary bg-opacity-75 text-decoration-none" href="<?php echo $catLink; ?>"><?php echo htmlspecialchars($row->cat, ENT_QUOTES); ?></a>
								<?php } ?>
								<?php if ($isStationDiary) { ?>
									<span class="badge rounded-pill <?php echo $isPublicDiary ? 'text-bg-success' : 'text-bg-secondary'; ?>">
										<i class="fas <?php echo $isPublicDiary ? 'fa-globe' : 'fa-lock'; ?> me-1"></i><?php echo $isPublicDiary ? 'Public' : 'Private'; ?>
									</span>
								<?php } ?>
							</div>
							<?php if (!empty($excerpt)) { ?>
								<p class="small text-body-secondary mb-1 mt-1"><?php echo htmlspecialchars($excerpt, ENT_QUOTES); ?></p>
							<?php } ?>
							<div class="small text-body-secondary font-monospace" style="font-size: 0.74rem;">
								<i class="far fa-clock me-1"></i><?php echo $createdDate; ?>
							</div>
						</div>

						<div class="flex-shrink-0">
							<div class="btn-group btn-group-sm" role="group" aria-label="Note actions">
								<a href="<?php echo site_url('notes/view/' . $row->id); ?>" class="btn btn-outline-secondary">View</a>
								<a href="<?php echo site_url('notes/edit/' . $row->id); ?>" class="btn btn-outline-primary">Edit</a>
								<button type="button"
									class="btn btn-outline-danger"
									data-bs-toggle="modal"
									data-bs-target="#deleteNoteModal"
									data-note-id="<?php echo $row->id; ?>"
									data-note-title="<?php echo htmlspecialchars($row->title, ENT_QUOTES); ?>">
									Delete
								</button>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } else { ?>
			<div class="card-body text-center py-5">
				<div class="notes-empty-icon mx-auto mb-3 d-inline-flex align-items-center justify-content-center">
					<i class="fas fa-sticky-note"></i>
				</div>
				<h2 class="h5 fw-bold mb-2"><?php echo $hasActiveFilters ? 'No Matching Notes' : 'No Notes Yet'; ?></h2>
				<p class="text-body-secondary mb-3"><?php echo $hasActiveFilters ? 'Try clearing or adjusting your search filters.' : lang('notes_welcome'); ?></p>
				<div class="d-flex justify-content-center gap-2">
					<?php if ($hasActiveFilters) { ?>
						<a href="<?php echo site_url('notes'); ?>" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
					<?php } ?>
					<a href="<?php echo site_url('notes/add'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i><?php echo lang('notes_create_note'); ?></a>
				</div>
			</div>
		<?php } ?>
	</div>

	<?php $this->load->view('notes/partials/delete_modal'); ?>
	<?php $this->load->view('notes/partials/category_modal'); ?>
</main>