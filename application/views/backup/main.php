<div class="container">
<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<h2><?php echo $page_title; ?></h2>

<!-- Legacy backup card removed as ADIF/Notes exports superseded by user data ZIP import/export workflow -->

<div class="card mt-4">
	<div class="card-header d-flex justify-content-between align-items-center">
		<span>User Data Backup & Restore</span>
		<a href="<?php echo site_url('backup/user_export'); ?>" class="btn btn-sm btn-outline-primary" title="Download ZIP backup of your stations, logbooks & QSOs">Download ZIP Backup</a>
	</div>
	<div class="card-body">
		<p class="card-text">Export and restore only your own Stations, Logbooks and QSOs. Imported stations/logbooks are never set active automatically; duplicates reuse existing records; QSOs skip conflicts.</p>
		<form method="post" enctype="multipart/form-data" hx-post="<?php echo site_url('backup/user_import'); ?>" hx-target="#user-import-preview" hx-swap="innerHTML" class="mb-3">
			<div class="mb-2">
				<input type="file" name="backup_file" accept=".zip,application/zip,application/json" required class="form-control" />
			</div>
			<button type="submit" class="btn btn-success">Upload & Preview</button>
		</form>
		<div id="user-import-preview"></div>
	</div>
</div>


</div>

