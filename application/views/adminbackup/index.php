<div class="container mt-4">
  <h2>Admin Backup & Restore</h2>
  <div class="row">
    <div class="col-md-6">
      <h4>Backup</h4>
      <form method="get" action="<?php echo site_url('adminbackup/export'); ?>">
        <button type="submit" class="btn btn-primary">Download Full Backup (ZIP)</button>
      </form>
    </div>
    <div class="col-md-6">
      <h4>Restore</h4>
      <form method="post" enctype="multipart/form-data" action="<?php echo site_url('adminbackup/import'); ?>">
        <input type="file" name="backup_file" accept=".zip,application/zip" required>
        <button type="submit" class="btn btn-success">Upload & Restore</button>
      </form>
    </div>
  </div>
</div>
