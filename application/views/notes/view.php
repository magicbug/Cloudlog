<div class="container notes">
	<div class="row">
		<div class="col-12 col-xl-10">
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
					<div class="note-content lh-base">
						<?php echo nl2br($row->note); ?>
					</div>
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