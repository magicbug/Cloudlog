<div class="container">

	<!-- Page Header -->
	<div class="row mt-4 mb-4">
		<div class="col-md-12">
			<div class="d-flex justify-content-between align-items-center">
				<h2><i class="fas fa-broadcast-tower me-2"></i><?php echo $page_title; ?></h2>
				<a href="<?php echo site_url('commands'); ?>" class="btn btn-primary">
					<i class="fas fa-terminal me-2"></i>Radio Commands
				</a>
			</div>
		</div>
	</div>

	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert alert-info alert-dismissible fade show" role="alert">
			<i class="fas fa-info-circle me-2"></i><?php echo $this->session->flashdata('message'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- Active Radios Card -->
	<div class="card">
		<div class="card-header bg-light">
			<h5 class="mb-0"><i class="fas fa-signal me-2"></i>Active Radios</h5>
		</div>
		<div class="card-body">
			<p class="card-text"><i class="fas fa-info-circle me-2"></i>Below is a list of active radios that are connected to Cloudlog.</p>
			<p class="card-text"><i class="fas fa-lightbulb me-2"></i>If you haven't connected any radios yet, see the API page to generate API keys.</p>
			
			<div class="table-responsive">
				<!-- Loading indicator -->
				<div id="radio-loading" class="text-center py-4">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
					<p class="mt-2 text-muted">Loading radio status...</p>
				</div>
				<!-- Display Radio Statuses -->	  
				<table class="table table-striped status" style="display: none;"></table>
			</div>

			<p class="card-text mt-3">
				<span class="badge text-bg-info"><i class="fas fa-book me-1"></i>Info</span> 
				You can find out how to use the <a href="https://github.com/magicbug/Cloudlog/wiki/Radio-Interface" target="_blank">radio functions <i class="fas fa-external-link-alt ms-1"></i></a> in the wiki.
			</p>
		</div>
	</div>

</div>
