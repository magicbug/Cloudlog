<div class="container awards-page">
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert alert-info alert-dismissible fade show" role="alert">
		  <?php echo $this->session->flashdata('message'); ?>
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<?php if(!isset($zip_installed) || !$zip_installed) { ?>
		<div class="alert alert-warning" role="alert">
			<strong>Warning:</strong> The PHP ZIP extension is not installed. Backup and restore functionality requires the ZIP extension. Please install it to use these features. See the <a href="<?php echo site_url('debug'); ?>" class="alert-link">Debug page</a> for more information.
		</div>
	<?php } ?>

<?php $this->load->view('awards/components/topbar', array(
	'title' => $page_title,
	'subtitle' => 'Export and restore your stations, logbooks, and QSOs as a portable ZIP archive',
	'icon' => 'fas fa-database',
	'actions' => array(
		array(
			'label' => 'Download ZIP Backup',
			'url' => site_url('backup/user_export'),
			'icon' => 'fas fa-download',
			'class' => 'btn-primary',
		),
	),
)); ?>

<!-- Legacy backup card removed as ADIF/Notes exports superseded by user data ZIP import/export workflow -->

<div class="card">
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

