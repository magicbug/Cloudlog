
<div class="container notes">
<div class="row">
<div class="col-12 col-xl-10">
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

	<form method="post" action="<?php echo site_url('notes/edit'); ?>/<?php echo $id; ?>" name="notes_add" id="notes_add">

	<div class="mb-3">
		<label for="inputTitle" class="form-label"><?php echo lang('notes_input_title'); ?></label>
		<input type="text" name="title" class="form-control" value="<?php echo set_value('title', $row->title); ?>" id="inputTitle" placeholder="e.g. Field day setup" autocomplete="off" required>
	</div>

	<div class="mb-3">
	   <label for="catSelect" class="form-label"><?php echo lang('notes_input_category'); ?></label>
	   <select name="category" class="form-select" id="catSelect">
	   	<?php foreach ($categoryOptions as $catOption) { ?>
	   	<option value="<?php echo $catOption; ?>" <?php echo ($selectedCategory === $catOption) ? 'selected' : ''; ?>><?php echo $catOption; ?></option>
	   	<?php } ?>
	   </select>
	   <small class="text-muted d-block mt-1">Select an existing category or type a new one below.</small>
	</div>

	<div class="mb-3">
		<label for="newCategoryInput" class="form-label">New category (optional)</label>
		<input type="text" name="new_category" class="form-control" id="newCategoryInput" value="<?php echo set_value('new_category'); ?>" placeholder="e.g. Portable Ops" autocomplete="off">
		<small class="text-muted">If filled, this will be used instead of the selected category.</small>
	</div>

	<div class="mb-3">
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
			<small class="text-muted d-block">Saved: <strong><?php echo !empty($dateDisplayValue) ? $dateDisplayValue : 'N/A'; ?></strong></small>
		</div>
		<input type="date" name="created_at" class="form-control" id="createdAtInput" value="<?php echo set_value('created_at', $dateValue); ?>">
		<small class="text-muted">Leave empty to keep the current date, or set a custom creation date.</small>
	</div>

	<div class="mb-3">
		<label for="hiddenArea" class="form-label"><?php echo lang('notes_input_notes_content'); ?></label>
		<div id="quillArea"><?php echo $row->note; ?></div>
		<textarea name="content" style="display:none" id="hiddenArea"></textarea>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<div class="d-flex flex-wrap gap-2">
		<button type="submit" value="Submit" class="btn btn-primary"><?php echo lang('notes_input_btn_save_note'); ?></button>
		<a href="<?php echo site_url('notes/view/'.$id); ?>" class="btn btn-outline-secondary"><?php echo lang('general_word_cancel') ?: 'Cancel'; ?></a>
	</div>
	</form>
  </div>

  <?php } ?>
</div>


</div>
</div>

