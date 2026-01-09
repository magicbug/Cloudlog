<div class="container notes">
	<div class="row">
		<div class="col-12 col-xl-10">
			<div class="card shadow-sm">
	  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
	  	<div class="d-flex align-items-center gap-3 flex-wrap">
	  		<h2 class="card-title mb-0"><?php echo lang('notes_menu_notes'); ?></h2>
	    	<ul class="nav nav-tabs card-header-tabs">
	      	<li class="nav-item">
	        	<a class="nav-link active" href="<?php echo site_url('notes'); ?>"><?php echo lang('notes_menu_notes'); ?></a>
	      	</li>
	      	<li class="nav-item">
	        	<a class="nav-link" href="<?php echo site_url('notes/add'); ?>"><?php echo lang('notes_create_note'); ?></a>
	      	</li>
	    	</ul>
	  	</div>
	  	<div class="ms-auto">
	  		<button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
	  			<?php echo lang('notes_manage_categories') ?: 'Manage categories'; ?>
	  		</button>
	  	</div>
	  </div>

	<div class="card-body">
		<?php
		$defaultCategories = array('General', 'Antennas', 'Satellites');
		$existingCategories = isset($categories) && is_array($categories) ? $categories : array();
		$categoryOptions = array_values(array_unique(array_merge($defaultCategories, $existingCategories)));
		$activeSearch = isset($filters['search']) ? $filters['search'] : (isset($filters['q']) ? $filters['q'] : '');
		$activeCategory = isset($filters['category']) ? $filters['category'] : '';
		?>

		<form class="row g-3 mb-4" method="get" action="<?php echo site_url('notes'); ?>">
			<div class="col-md-6">
				<label for="notesSearch" class="form-label mb-1">Search</label>
				<input type="text" class="form-control" id="notesSearch" name="q" value="<?php echo htmlspecialchars($activeSearch, ENT_QUOTES); ?>" placeholder="Search title or content">
			</div>
			<div class="col-md-4">
				<label for="notesCategory" class="form-label mb-1">Category</label>
				<select class="form-select" id="notesCategory" name="category">
					<option value="">All categories</option>
					<?php foreach ($categoryOptions as $catOption) { ?>
					<option value="<?php echo $catOption; ?>" <?php echo ($activeCategory === $catOption) ? 'selected' : ''; ?>><?php echo $catOption; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-2 d-flex align-items-end gap-2">
				<button type="submit" class="btn btn-primary w-100">Filter</button>
				<a class="btn btn-outline-secondary w-100" href="<?php echo site_url('notes'); ?>">Clear</a>
			</div>
		</form>

				<?php if ($notes->num_rows() > 0) { ?>
					<h3 class="h5 mb-3"><?php echo lang('notes_your_notes'); ?></h3>
					<ul class="list-group list-group-flush">
						<?php foreach ($notes->result() as $row) { 
							$plain = trim(strip_tags($row->note));
							$excerpt = mb_substr($plain, 0, 140) . (mb_strlen($plain) > 140 ? '…' : '');
							$catLink = !empty($row->cat) ? site_url('notes').'?category='.rawurlencode($row->cat) : '';
						?>
							<li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start gap-2">
								<div class="me-md-3">
									<a class="fw-semibold text-decoration-none" href="<?php echo site_url('notes/view/'.$row->id); ?>"><?php echo $row->title; ?></a>
									<?php if (!empty($row->cat)) { ?>
										<a class="badge bg-secondary ms-2 align-middle text-decoration-none" href="<?php echo $catLink; ?>"><?php echo htmlspecialchars($row->cat, ENT_QUOTES); ?></a>
									<?php } ?>
									<?php if (!empty($excerpt)) { ?>
										<small class="text-muted d-block mt-1"><?php echo $excerpt; ?></small>
									<?php } ?>
								</div>
								<div class="ms-md-auto">
									<div class="btn-group btn-group-sm" role="group" aria-label="Note actions">
										<a href="<?php echo site_url('notes/view/'.$row->id); ?>" class="btn btn-outline-secondary">View</a>
										<a href="<?php echo site_url('notes/edit/'.$row->id); ?>" class="btn btn-outline-primary">Edit</a>
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
							</li>
						<?php } ?>
					</ul>
				<?php } else { ?>
					<div class="text-center py-5">
						<p class="mb-3"><?php echo lang('notes_welcome'); ?></p>
						<a href="<?php echo site_url('notes/add'); ?>" class="btn btn-primary"><?php echo lang('notes_create_note'); ?></a>
					</div>
				<?php } ?>
				<?php $this->load->view('notes/partials/delete_modal'); ?>
				<?php $this->load->view('notes/partials/category_modal'); ?>
		  </div>
					</div>
				</div>
			</div>
		</div>
