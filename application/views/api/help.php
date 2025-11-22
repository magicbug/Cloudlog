<div class="container">

	<!-- Page Header -->
	<div class="row mt-4 mb-4">
		<div class="col-md-12">
			<div class="d-flex justify-content-between align-items-center">
				<h2><i class="fas fa-code me-2"></i><?php echo $page_title; ?></h2>
				<div>
					<a href="<?php echo site_url('api/generate/rw'); ?>" class="btn btn-primary">
						<i class="fas fa-plus me-2"></i>Create Read & Write Key
					</a>
					<a href="<?php echo site_url('api/generate/r'); ?>" class="btn btn-primary">
						<i class="fas fa-plus me-2"></i>Create Read-Only Key
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php if($this->session->flashdata('notice')) { ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('notice'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- API Keys Card -->
	<div class="card">
		<div class="card-header bg-light">
			<h5 class="mb-0"><i class="fas fa-key me-2"></i>API Keys</h5>
		</div>
		<div class="card-body">
			<p class="card-text"><i class="fas fa-info-circle me-2"></i>The Cloudlog API (Application Programming Interface) lets third party systems access Cloudlog in a controlled way. Access to the API is managed via API keys.</p>
			<p class="card-text"><i class="fas fa-lightbulb me-2"></i>You will need to generate an API key for each tool you wish to use (e.g. CloudlogCAT). Generate a read-write key if the application needs to send data to Cloudlog. Generate a read-only key if the application only needs to obtain data from Cloudlog.</p>
			<p class="card-text">
				<span class="badge text-bg-warning"><i class="fas fa-link me-1"></i>API URL</span> 
				The API URL for this Cloudlog instance is: 
				<span class="api-url" id="apiUrl"><a target="_blank" href="<?php echo base_url(); ?>"><?php echo base_url(); ?></a></span>
				<span data-bs-toggle="tooltip" title="<?php echo lang('copy_to_clipboard'); ?>" onClick='copyApiUrl()' style="cursor: pointer;">
					<i class="copy-icon fas fa-copy ms-1"></i>
				</span>
			</p>
			<p class="card-text">
				<span class="badge text-bg-info"><i class="fas fa-info-circle me-1"></i>Info</span> 
				It's good practice to delete a key if you are no longer using the associated application.
			</p>

			<?php if ($api_keys->num_rows() > 0) { ?>
				<div class="table-responsive">
					<table class="table table-hover table-striped mb-0">
						<thead class="table-light">
							<tr>
								<th scope="col">API Key</th>
								<th scope="col">Description</th>
								<th scope="col">Last Used</th>
								<th scope="col">Permissions</th>
								<th scope="col">Status</th>
								<th class="text-center" scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($api_keys->result() as $row) { ?>
							<tr>
								<td>
									<i class="fas fa-key me-2"></i>
									<span class="api-key" id="<?php echo $row->key; ?>"><?php echo $row->key; ?></span> 
									<span data-bs-toggle="tooltip" title="<?php echo lang('copy_to_clipboard'); ?>" onclick='copyApiKey("<?php echo $row->key; ?>")' style="cursor: pointer;">
										<i class="copy-icon fas fa-copy ms-1"></i>
									</span>
								</td>
								<td><?php echo $row->description; ?></td>
								<td><?php echo $row->last_used; ?></td>
								<td>
									<?php
										if($row->rights == "rw") {
											echo "<span class=\"badge bg-warning\">Read & Write</span>";
										} elseif($row->rights == "r") {
											echo "<span class=\"badge bg-success\">Read-Only</span>";
										} else {
											echo "<span class=\"badge bg-dark\">Unknown</span>";
										}
									?>
								</td>
								<td><span class="badge rounded-pill text-bg-success"><?php echo ucfirst($row->status); ?></span></td>
								<td class="text-center">
									<a href="<?php echo site_url('api/edit'); ?>/<?php echo $row->key; ?>" class="btn btn-outline-primary btn-sm">
										<i class="fas fa-edit"></i>
									</a>
									<a href="<?php echo site_url('api/auth/'.$row->key); ?>" target="_blank" class="btn btn-outline-info btn-sm">
										<i class="fas fa-vial"></i>
									</a>
									<a href="<?php echo site_url('api/delete/'.$row->key); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want delete API Key <?php echo $row->key; ?>?');">
										<i class="fas fa-trash-alt"></i>
									</a>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>	
				</div>
			<?php } else { ?>
				<div class="alert alert-info mt-3" role="alert">
					<i class="fas fa-info-circle me-2"></i>You have no API Keys. Create one using the buttons above to get started.
				</div>
			<?php } ?>
		</div>
	</div>

</div>

