<div class="container">

<br>
<?php if($this->session->flashdata('notice')) { ?>
	<div id="message" >
    	<?php echo $this->session->flashdata('notice'); ?>
	</div>
<?php } ?>

<h2><?php echo $page_title; ?></h2>

<div class="card">
  <div class="card-header">
    User List
  </div>
  <div class="card-body">
    <p class="card-text">Cloudlog needs at least one user configured in order to operate.</p>
    <p class="card-text">Users can be assigned roles which give them different permissions, such as adding QSOs to the logbook and accessing Cloudlog APIs.</p>
    <p class="card-text">The currently logged-in user is displayed at the upper-right of each page.</p>
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
					<td>
						<a href="<?php echo site_url('user/edit')."/".$row->user_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-edit"></i> Edit</a>
						<?php
						if ($_SESSION['user_id'] != $row->user_id) {
							echo "<a href=" . site_url('user/delete'). "/" . $row->user_id . " class=\"btn btn-danger btn-sm\"><i class=\"fas fa-user-minus\"></i> Delete</a>";
						}
						?>
					</td>
				</tr>
				<?php $i++; } ?>
			</tbody>
		</table>
	</div>
		<p>
			<a class="btn btn-primary" href="<?php echo site_url('user/add'); ?>"><i class="fas fa-user-plus"></i> Create user</a>
		</p>
  </div>
</div>


</div>
