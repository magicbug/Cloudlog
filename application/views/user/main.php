<div class="container">

<br>

<h2><?php echo $page_title; ?></h2>

<?php if($this->session->flashdata('notice')) { ?>
		<!-- Display Message -->
		<div class="alert alert-info" role="alert">
			<?php echo $this->session->flashdata('notice'); ?>
		</div>

<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo lang('admin_user_list'); ?>
  </div>
  <div class="card-body">
    <p class="card-text"><?php echo lang('admin_user_line1'); ?></p>
    <p class="card-text"><?php echo lang('admin_user_line2'); ?></p>
    <p class="card-text"><?php echo lang('admin_user_line3'); ?></p>
    <div class="table-responsive">
		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col"><?php echo lang('admin_user'); ?></th>
		      <th scope="col"><?php echo lang('admin_email'); ?></th>
		      <th scope="col"><?php echo lang('admin_type'); ?></th>
		      <th scope="col"><?php echo lang('admin_options'); ?></th>
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
						<a href="<?php echo site_url('user/edit')."/".$row->user_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-edit"></i> <?php echo lang('admin_edit'); ?></a>
						<?php
						if ($_SESSION['user_id'] != $row->user_id) {
							echo "<a href=" . site_url('user/delete'). "/" . $row->user_id . " class=\"btn btn-danger btn-sm\"><i class=\"fas fa-user-minus\"></i> ".lang('admin_delete')."</a>";
						}
						?>
					</td>
				</tr>
				<?php $i++; } ?>
			</tbody>
		</table>
	</div>
		<p>
			<a class="btn btn-primary" href="<?php echo site_url('user/add'); ?>"><i class="fas fa-user-plus"></i> <?php echo lang('admin_create_user'); ?></a>
		</p>
  </div>
</div>


</div>
