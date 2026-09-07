<div class="container-fluid notes px-3 px-lg-4">
	<div class="row">
		<div class="col-12">
		<?php foreach ($note->result() as $row) { ?>
			<div class="card shadow-sm">
				<div class="card-header">
					<div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
						<div class="d-flex flex-column gap-1">
							<div class="d-flex align-items-center gap-2 flex-wrap">
								<a class="small text-decoration-none" href="<?php echo site_url('notes'); ?>">&larr; <?php echo lang('notes_menu_notes'); ?></a>
								<?php if (!empty($row->cat)) { ?>
									<span class="badge bg-secondary"><?php echo $row->cat; ?></span>
								<?php } ?>
								<?php if (strtoupper(trim((string)$row->cat)) === 'STATION DIARY') { ?>
									<span class="badge <?php echo ((int)($row->is_public ?? 0) === 1) ? 'bg-success' : 'bg-dark'; ?>"><?php echo ((int)($row->is_public ?? 0) === 1) ? '🌍 Public' : '🔒 Private'; ?></span>
								<?php } ?>
							</div>
							<h2 class="card-title mb-0"><?php echo $row->title; ?></h2>
						</div>
						<div class="btn-group btn-group-sm" role="group" aria-label="Note actions">
							<a href="<?php echo site_url('notes/edit/'.$row->id); ?>" class="btn btn-outline-primary">
								<i class="fas fa-edit"></i> <?php echo lang('notes_input_btn_edit_note'); ?>
							</a>
							<button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteNoteModal" data-note-id="<?php echo $row->id; ?>" data-note-title="<?php echo htmlspecialchars($row->title, ENT_QUOTES); ?>">
								<i class="fas fa-trash-alt"></i> <?php echo lang('notes_input_btn_delete_note'); ?>
							</button>
						</div>
					</div>
				</div>
				<div class="card-body">
					<?php 
					$entryImages = isset($diary_images[$row->id]) ? $diary_images[$row->id] : array();
					
					// Process image shortcodes in the note content
					$CI =& get_instance();
					$CI->load->model('note');
					$processed = $CI->note->process_image_shortcodes($row->note, $entryImages);
					// Remove empty <p><br></p> tags and <br> tags between paragraph tags
					$processedNote = preg_replace('/<p><br\s*\/?><\/p>/i', '', $processed['content']);
					$processedNote = preg_replace('/<\/p>\s*<br\s*\/?>\s*<p>/i', '</p><p>', $processedNote);
					$usedImageIds = $processed['used_image_ids'];
					
					// Filter out images that were used inline
					$remainingImages = array();
					if (!empty($entryImages)) {
						foreach ($entryImages as $image) {
							if (!in_array((int)$image->id, $usedImageIds)) {
								$remainingImages[] = $image;
							}
						}
					}
					?>
					<div class="note-content lh-base">
						<?php echo $processedNote; ?>
					</div>
					<?php if (!empty($remainingImages)) { ?>
						<hr class="my-3">
						<div class="row g-3">
							<?php foreach ($remainingImages as $image) { ?>
								<div class="col-md-4">
									<img src="<?php echo base_url() . ltrim($image->filename, '/'); ?>" class="img-fluid rounded border" alt="Diary image">
									<?php if (!empty($image->caption)) { ?>
										<div class="small text-muted mt-1"><?php echo htmlspecialchars($image->caption, ENT_QUOTES); ?></div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<div class="card-footer bg-transparent pt-0 border-0">
					<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
						<div class="d-flex flex-wrap gap-2">
							<a href="<?php echo site_url('notes'); ?>" class="btn btn-outline-secondary btn-sm"><?php echo lang('general_word_back') ?: 'Back'; ?></a>
							<a href="<?php echo site_url('notes/add'); ?>" class="btn btn-outline-primary btn-sm"><?php echo lang('notes_create_note'); ?></a>
						</div>
						<small class="text-muted">
							<?php 
								$savedTime = (is_null($row->created_at) || $row->created_at === '' || $row->created_at === '0000-00-00 00:00:00') ? 'N/A' : date('M d, Y \a\t g:i A', strtotime($row->created_at));
								echo 'Saved: ' . $savedTime;
							?>
						</small>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php $this->load->view('notes/partials/delete_modal'); ?>
		</div>
	</div>
</div>