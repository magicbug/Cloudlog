<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="modal fade" id="deleteNoteModal" tabindex="-1" aria-labelledby="deleteNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteNoteModalLabel"><?php echo lang('general_word_danger'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo lang('admin_close'); ?>"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">
          <?php echo lang('qso_delete_warning'); ?>
          <strong id="deleteNoteTitle"></strong>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo lang('general_word_cancel'); ?></button>
        <form method="post" action="<?php echo site_url('notes/delete'); ?>" class="d-inline">
          <input type="hidden" name="id" id="deleteNoteId" value="">
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> <?php echo lang('notes_input_btn_delete_note'); ?>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
