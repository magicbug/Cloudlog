<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<h2><?php echo $page_title; ?></h2>

<div class="card">
  <div class="card-header">
    What are Station Logbooks
  </div>
  <div class="card-body">
    <p class="card-text">Station Logbooks allow you to group Station Locations, this allows you to see all the locations across one session from the logbook areas to the analytics. Great for when your operating in multiple locations but they are part of the same DXCC or VUCC Circle.</p>
  </div>
</div>

<div class="card" style="margin-top: 20px;">
  <div class="card-header">
    Station Logbooks <a class="btn btn-primary float-right" href="<?php echo site_url('logbooks/create'); ?>"><i class="fas fa-plus"></i> Create a Station Logbook</a>
  </div>
  <div class="card-body">
  <div id="station_logbooks">
		<?php if ($my_logbooks->num_rows() > 0) { ?>

		<div class="table-responsive">
		<table id="station_logbooks_table" class="table table-sm table-striped">
			<thead>
				<tr>
					<th scope="col">Name</th>
					<th scope="col">Status</th>
					<th scope="col">Edit</th>
					<th scope="col">Delete</th>
					<th scope="col">Link</th>
					<th scope="col">Public Search</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($my_logbooks->result() as $row) { ?>
				<tr>
					<td>
						<?php echo $row->logbook_name;?><br>
					</td>

					<td>
						<?php if($this->session->userdata('active_station_logbook') != $row->logbook_id) { ?>
						<a href="<?php echo site_url('logbooks/set_active')."/".$row->logbook_id; ?>" class="btn btn-outline-primary btn-sm">Set as Active Logbook</a>
						<?php } else {
							echo '<span class="badge badge-success">Active Logbook</span>';
							}?>
					</td>
					<td>
						<a href="<?php echo site_url('logbooks/edit')."/".$row->logbook_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit" title="Edit <?php echo $row->logbook_name;?>"></i> </a>
					</td>
					<td>
						<?php if($this->session->userdata('active_station_logbook') != $row->logbook_id) { ?>
						<a href="<?php echo site_url('Logbooks/delete')."/".$row->logbook_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want delete station logbook <?php echo $row->logbook_name; ?>? You must re-link any locations linked here to another logbook.');"><i class="fas fa-trash-alt"></i></a>
						<?php } ?>
					</td>
					<td>
						<?php if($row->public_slug != '') { ?>
							<a target="_blank" href="<?php echo site_url('visitor')."/".$row->public_slug; ?>" class="btn btn-outline-primary btn-sm" ><i class="fas fa-globe" title="View Public Page for <?php echo $row->logbook_name;?> Logbook"></i> </a>
							<?php } ?>
					</td>
					<td>
							<?php if ($row->public_search == 1) {
							echo "<span class=\"badge badge-success\">Enabled</span>";
							} else {
							echo "<span class=\"badge badge-dark\">Disabled</span>";
							} ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		<table>
		</div>
		<?php } ?>
  </div>
</div>
</div>


</div>
