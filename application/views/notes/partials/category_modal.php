<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$defaultCategories = array('General', 'Antennas', 'Satellites');
$existingCategories = isset($categories) && is_array($categories) ? $categories : array();
$categoryOptions = array_values(array_unique(array_merge($defaultCategories, $existingCategories)));
?>
<div class="modal fade" id="manageCategoriesModal" tabindex="-1" aria-labelledby="manageCategoriesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manageCategoriesModalLabel">Manage categories</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="<?php echo site_url('notes/delete_category'); ?>">
        <div class="modal-body">
          <div class="mb-3">
            <label for="sourceCategory" class="form-label">Category to remove</label>
            <select class="form-select" id="sourceCategory" name="source_category" required>
              <option value="" disabled selected>Select category</option>
              <?php foreach ($categoryOptions as $catOption) { ?>
                <option value="<?php echo $catOption; ?>"><?php echo $catOption; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="targetCategory" class="form-label">Reassign notes to</label>
            <select class="form-select" id="targetCategory" name="target_category" required>
              <?php foreach ($categoryOptions as $catOption) { ?>
                <option value="<?php echo $catOption; ?>" <?php echo ($catOption === 'General') ? 'selected' : ''; ?>><?php echo $catOption; ?></option>
              <?php } ?>
            </select>
            <small class="text-muted">All notes in the removed category will be reassigned.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Remove</button>
        </div>
      </form>
    </div>
  </div>
</div>
