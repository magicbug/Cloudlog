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
    Station Locations
  </div>
  <div class="card-body">
    <p class="card-text">Intro to Station Logbooks.</p>

	  <p><a href="<?php echo site_url('logbooks/create'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create a Station Logbook</a></p>
	  
		<?php if ($my_logbooks->num_rows() > 0) { ?>

		<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">Name</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($my_logbooks->result() as $row) { ?>
				<tr>
					<td>
						<?php echo $row->logbook_name;?><br>
					</td>

					<td>
						<a href="<?php echo site_url('logbooks/edit')."/".$row->logbook_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
					</td>

					<td>
					    <a href="<?php echo site_url('Logbooks/delete')."/".$row->logbook_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want delete station profile <?php echo $row->logbook_name; ?> this will delete all QSOs within this station logbook?');"><i class="fas fa-trash-alt"></i> Delete Station Logbook</a>
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
