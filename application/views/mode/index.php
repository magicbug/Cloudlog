<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?>
  </div>
  <div class="card-body">
    <p class="card-text">This is the place you can customize your modes-list by activating/deactivating modes to be shown in the select-list.</p>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">Mode</th>
					<th scope="col">Sub-Mode</th>
					<th scope="col">SSB/DATA/CW</th>
					<th scope="col">Active</th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($modes->result() as $row) { ?>
				<tr>
					<td><?php echo $row->mode;?></td>
					<td><?php echo $row->submode;?></td>
					<td><?php echo $row->qrgmode;?></td>
					<td><?php if ($row->active == 1) { echo "active";} else { echo "not active";};?></td>
					<td>
						<a href="<?php echo site_url('mode/edit')."/".$row->id; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit-alt"></i> Edit</a>
					</td>
					<td>
						<a href="<?php echo site_url('mode/delete')."/".$row->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want delete mode <?php echo $row->mode; ?> ');"><i class="fas fa-trash-alt"></i> Delete</a></td>
				</tr>

				<?php } ?>
			</tbody>
		<table>
		<p><a href="<?php echo site_url('mode/create'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create a Mode</a></p>
  </div>
</div>


</div>