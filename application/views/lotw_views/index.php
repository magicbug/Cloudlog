<div class="container lotw awards-page">
	<?php $this->load->view('awards/components/topbar', array(
		'title' => lang('lotw_title'),
		'subtitle' => 'Manage LoTW certificates and synchronise QSOs with Logbook of the World',
		'icon' => 'fas fa-sync',
		'actions' => array(
			array(
				'label' => lang('lotw_btn_lotw_import'),
				'url' => site_url('/lotw/import'),
				'icon' => 'fas fa-cloud-download-alt',
				'class' => 'btn-primary',
			),
		),
	)); ?>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			<button class="btn btn-outline-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#uploadCertModal" role="button"><i class="fas fa-cloud-upload-alt"></i> <?php echo lang('lotw_btn_upload_certificate'); ?></button><i class="fab fa-expeditedssl"></i> <?php echo lang('lotw_title_available_cert'); ?>
		</div>

		<div class="lotw-cert-list" id="lotw-cert-list">
			<?php $this->load->view('lotw_views/cert_table', array('lotw_cert_results' => $lotw_cert_results)); ?>
		</div>
	</div>
	<!-- Card Ends -->

	<br>

	<!-- Information Card - Only show if certificates exist -->
	<?php if ($lotw_cert_results->num_rows() > 0) { ?>
	<div class="card">
		<div class="card-header">
			<?php echo lang('lotw_title_information'); ?>
		</div>

		<div class="card-body">
			<button id="manual-sync-btn" class="btn btn-outline-success" 
				hx-get="<?php echo site_url('lotw/lotw_upload'); ?>"  
				hx-target="#lotw_manual_results"
				hx-indicator="#sync-spinner">
				<span id="sync-spinner" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true" style="display: none;"></span>
				<?php echo lang('lotw_btn_manual_sync'); ?>
			</button>

			<div id="lotw_manual_results"></div>
		</div>
	</div>
	<?php } ?>

</div>

<!-- Load Certificate Upload Modal -->
<?php $this->load->view('lotw_views/upload_cert_modal'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const syncBtn = document.getElementById('manual-sync-btn');
    const spinner = document.getElementById('sync-spinner');
    
    // Show spinner when htmx request starts
    syncBtn.addEventListener('htmx:beforeRequest', function() {
        spinner.style.display = 'inline-block';
        syncBtn.disabled = true;
    });
    
    // Hide spinner when htmx request completes (success or error)
    syncBtn.addEventListener('htmx:afterRequest', function() {
        spinner.style.display = 'none';
        syncBtn.disabled = false;
    });
});
</script>
