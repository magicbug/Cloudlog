<div class="container">

<br>
<?php if($this->session->flashdata('notice')) { ?>
	<div id="message" >
    	<?php echo $this->session->flashdata('notice'); ?>
	</div>
<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?>
  </div>
  <div class="card-body">
    <h5 class="card-title">Cloudlog Needs Users You make them here.</h5>
    <p class="card-text"></p>
    <div class="table-responsive">
		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">User</th>
		      <th scope="col">E-mail</th>
		      <th scope="col">Type</th>
		      <th scope="col">Options</th>
		    </tr>
		  </thead>
		  <tbody>

				<?php

				$i = 0;
				foreach ($results->result() as $row) { ?>
				<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
					<td><a href="<?php echo site_url('user/edit')."/".$row->user_id; ?>"><?php echo $row->user_name; ?></a></td>
					<td><?php echo $row->user_email; ?></td>
					<td><?php $l = $this->config->item('auth_level'); echo $l[$row->user_type]; ?></td>
					<td><a href="<?php echo site_url('user/edit')."/".$row->user_id; ?>" class="btn btn-primary btn-sm"><i class="fas fa-user-edit"></i> Edit</a> <a href="<?php echo site_url('user/delete')."/".$row->user_id; ?>" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i> Delete</a></td>
				</tr>
				<?php $i++; } ?>
			</tbody>
		</table>
	</div>
		<p>
			<a class="btn btn-primary" href="<?php echo site_url('user/add'); ?>">Add user</a>
		</p>
  </div>
</div>


</div>