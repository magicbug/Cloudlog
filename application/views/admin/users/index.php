<div class="container settings">

	<div class="row">
		<!-- Nav Start -->
		<?php $this->view('admin/assets/menu'); ?>
		<!-- Nav End -->

		<!-- Content -->
		<div class="col-md-9">

			<h2>Admin Area</h2>

			<?php if($this->session->flashdata('notice')) { ?>
				<div class="alert alert-info" role="alert">
					<?php echo $this->session->flashdata('notice'); ?>
				</div>
			<?php } ?>

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
							<td><a href="<?php echo site_url('admin/edit_user')."/".$row->user_id; ?>" class="btn btn-primary btn-sm"><i class="fas fa-user-edit"></i> Edit</a> 
							<?php if($row->user_type != 1) { ?>
								<a href="<?php echo site_url('admin/delete_user')."/".$row->user_id; ?>" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i> Delete</a><?php } ?></td>
						</tr>
						<?php $i++; } ?>
					</tbody>
				</table>
			</div>


		</div>
	</div>

</div>