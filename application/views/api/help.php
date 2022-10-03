<div class="container">

<br>
<?php if($this->session->flashdata('notice')) { ?>
<div class="alert alert-success" role="alert">
	<?php echo $this->session->flashdata('notice'); ?>
</div>
<?php } ?>

<h2><?php echo $page_title; ?></h2>

<div class="card">
  <div class="card-header">
    API Keys
  </div>
  <div class="card-body">
	<p class="card-text">The Cloudlog API (Application Programming Interface) lets third party systems access Cloudlog in a controlled way. Access to the API is managed via API keys.</p>
	<p class="card-text">You will need to generate an API key for each tool you wish to use (e.g. CloudlogCAT). Generate a read-write key if the application needs to send data to Cloudlog. Generate a read-only key if the application only needs to obtain data from Cloudlog.</p>
	<p class="card-text"><span class="badge badge-info">Info</span> It's good practice to delete a key if you are no longer using the associated application.</p>

		<?php if ($api_keys->num_rows() > 0) { ?>

		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">API Key</th>
		      <th scope="col">Description</th>
		      <th scope="col">Rights</th>
		      <th scope="col">Status</th>
		    </tr>
		  </thead>
		  <tbody>
			<?php foreach ($api_keys->result() as $row) { ?>
				<tr>
					<td><i class="fas fa-key"></i> <span class="api-key" id="<?php echo $row->key; ?>"><?php echo $row->key; ?></span> <span data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('copy_to_clipboard'); ?>" onclick='copyApiKey("<?php echo $row->key; ?>")'><i class="copy-icon fas fa-copy"></span></td>
					<td><?php echo $row->description; ?></td>
					<td>
						<?php
							
							if($row->rights == "rw") {
								echo "Read & Write";
							} elseif($row->rights == "r") {
								echo "Read Only";
							} else {
								echo "Unknown";
							}
			
						?>

					</td>
					<td><span class="badge badge-pill badge-light"><?php echo ucfirst($row->status); ?></span>

						<a href="<?php echo site_url('api/edit'); ?>/<?php echo $row->key; ?>" class="btn btn-outline-primary btn-sm">Edit</a>

						<a href="<?php echo site_url('api/auth/'.$row->key); ?>" target="_blank" class="btn btn-primary btn-sm">Test</a>

						<a href="<?php echo site_url('api/delete/'.$row->key); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want delete API Key <?php echo $row->key; ?>?');">Delete</a>
					</td>

				</tr>

			<?php } ?>

		</table>	

		<?php } else { ?>
			<p>You have no API Keys.</p>
		<?php } ?>

		<p>
			<a href="<?php echo site_url('api/generate/rw'); ?>" class="btn btn-primary "><i class="fas fa-plus"></i> Create a read & write key</a>
			<a href="<?php echo site_url('api/generate/r'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create a read-only key</a>
		</p>

  </div>
</div>

</div>

