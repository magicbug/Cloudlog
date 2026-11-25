<main class="container notes notes-page">
	<div class="notes-article-shell mx-auto">
		<?php foreach ($note->result() as $row) {
			$isStationDiary = strtoupper(trim((string)$row->cat)) === 'STATION DIARY';
			$isPublicDiary = $isStationDiary && ((int)($row->is_public ?? 0) === 1);
			$savedTime = (is_null($row->created_at) || $row->created_at === '' || $row->created_at === '0000-00-00 00:00:00') ? 'N/A' : date('M d, Y \a\t g:i A', strtotime($row->created_at));
		?>
			<!-- Unified Topbar Card -->
			<div class="card notes-topbar mb-3">
				<div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
					<a href="<?php echo site_url('notes'); ?>" class="btn btn-sm btn-outline-secondary">
						<i class="fas fa-arrow-left me-1"></i><?php echo lang('notes_menu_notes'); ?>
					</a>
					<div class="d-flex align-items-center gap-2">
						<a href="<?php echo site_url('notes/edit/' . $row->id); ?>" class="btn btn-sm btn-primary">
							<i class="fas fa-edit me-1"></i><?php echo lang('notes_input_btn_edit_note'); ?>
						</a>
						<button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteNoteModal" data-note-id="<?php echo $row->id; ?>" data-note-title="<?php echo htmlspecialchars($row->title, ENT_QUOTES); ?>">
							<i class="fas fa-trash-alt me-1"></i><?php echo lang('notes_input_btn_delete_note'); ?>
						</button>
					</div>
				</div>
			</div>

			<!-- Readable Article Card -->
			<article class="card notes-article-card">
				<div class="card-header notes-article-header p-4">
					<div class="d-flex flex-wrap align-items-center gap-2 mb-2">
						<?php if (!empty($row->cat)) { ?>
							<a href="<?php echo site_url('notes') . '?category=' . rawurlencode($row->cat); ?>" class="badge rounded-pill text-bg-primary bg-opacity-75 text-decoration-none"><?php echo htmlspecialchars($row->cat, ENT_QUOTES); ?></a>
						<?php } ?>
						<?php if ($isStationDiary) { ?>
							<span class="badge rounded-pill <?php echo $isPublicDiary ? 'text-bg-success' : 'text-bg-secondary'; ?>">
								<i class="fas <?php echo $isPublicDiary ? 'fa-globe' : 'fa-lock'; ?> me-1"></i><?php echo $isPublicDiary ? 'Public' : 'Private'; ?>
							</span>
						<?php } ?>
						<span class="ms-auto small font-monospace text-body-secondary">
							<i class="far fa-clock me-1"></i>Saved: <?php echo $savedTime; ?>
						</span>
					</div>
					<h1 class="h3 fw-bold mb-0"><?php echo htmlspecialchars($row->title, ENT_QUOTES); ?></h1>
				</div>

				<div class="card-body p-4">
					<?php
					$entryImages = isset($diary_images[$row->id]) ? $diary_images[$row->id] : array();

					$CI =& get_instance();
					$CI->load->model('note');
					$processed = $CI->note->process_image_shortcodes($row->note, $entryImages);
					$processedNote = preg_replace('/<p><br\s*\/?><\/p>/i', '', $processed['content']);
					$processedNote = preg_replace('/<\/p>\s*<br\s*\/?>\s*<p>/i', '</p><p>', $processedNote);
					$usedImageIds = $processed['used_image_ids'];

					$remainingImages = array();
					if (!empty($entryImages)) {
						foreach ($entryImages as $image) {
							if (!in_array((int)$image->id, $usedImageIds)) {
								$remainingImages[] = $image;
							}
						}
					}
					?>
					<div class="note-content lh-lg">
						<?php echo $processedNote; ?>
					</div>

					<?php if (!empty($remainingImages)) { ?>
						<hr class="my-4">
						<div class="row g-3">
							<?php foreach ($remainingImages as $image) { ?>
								<div class="col-12 col-sm-6 col-md-4">
									<img src="<?php echo base_url() . ltrim($image->filename, '/'); ?>" class="img-fluid rounded border" alt="Diary image">
									<?php if (!empty($image->caption)) { ?>
										<div class="small text-body-secondary mt-1"><?php echo htmlspecialchars($image->caption, ENT_QUOTES); ?></div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>

				<div class="card-footer notes-card-footer px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
					<a href="<?php echo site_url('notes'); ?>" class="btn btn-outline-secondary btn-sm">
						<i class="fas fa-arrow-left me-1"></i><?php echo lang('general_word_back') ?: 'All Notes'; ?>
					</a>
					<a href="<?php echo site_url('notes/add'); ?>" class="btn btn-outline-primary btn-sm">
						<i class="fas fa-plus me-1"></i><?php echo lang('notes_create_note'); ?>
					</a>
				</div>
			</article>
		<?php } ?>
		<?php $this->load->view('notes/partials/delete_modal'); ?>
	</div>
</main>