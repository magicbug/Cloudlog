<div class="container">

	<!-- Page Header -->
	<div class="row mt-4 mb-4">
		<div class="col-md-12">
			<div class="d-flex justify-content-between align-items-center">
				<h2><i class="fas fa-users me-2"></i><?php echo $page_title; ?></h2>
				<a class="btn btn-primary" href="<?php echo site_url('user/add'); ?>">
					<i class="fas fa-user-plus me-2"></i><?php echo lang('admin_create_user'); ?>
				</a>
			</div>
		</div>
	</div>

	<?php if ($this->session->flashdata('notice')) { ?>
		<!-- Display Message -->
		<div class="alert alert-info alert-dismissible fade show" role="alert">
			<i class="fas fa-info-circle me-2"></i><?php echo $this->session->flashdata('notice'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<?php if ($this->session->flashdata('success')) { ?>
		<!-- Display Message -->
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('success'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<?php if ($this->session->flashdata('danger')) { ?>
		<!-- Display Message -->
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<i class="fas fa-exclamation-triangle me-2"></i><?php echo $this->session->flashdata('danger'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<!-- Information Card -->
	<div class="row mb-4">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header bg-light">
					<h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>About User Management</h5>
				</div>
				<div class="card-body">
					<p class="card-text mb-2"><?php echo lang('admin_user_line1'); ?></p>
					<p class="card-text mb-2"><?php echo lang('admin_user_line2'); ?></p>
					<p class="card-text mb-2"><?php echo lang('admin_user_line3'); ?></p>
					<p class="card-text mb-0"><?php echo lang('admin_user_line4'); ?></p>
				</div>
			</div>
		</div>
	</div>

	<!-- User List Card -->
	<div class="card">
		<div class="card-header bg-light">
			<div class="d-flex justify-content-between align-items-center">
				<h5 class="mb-0"><i class="fas fa-list me-2"></i><?php echo lang('admin_user_list'); ?> (<?php echo $results->num_rows(); ?>)</h5>
			</div>
		</div>
		<div class="card-body p-0">

			<div class="table-responsive">
				<table class="table table-hover table-striped mb-0">
					<thead class="table-light">
						<tr>
							<th scope="col"><i class="fas fa-user me-1"></i><?php echo lang('admin_user'); ?></th>
							<th scope="col"><i class="fas fa-broadcast-tower me-1"></i><?php echo lang('gen_hamradio_callsign'); ?></th>
							<th scope="col"><i class="fas fa-envelope me-1"></i><?php echo lang('admin_email'); ?></th>
							<th scope="col"><i class="fas fa-shield-alt me-1"></i><?php echo lang('admin_type'); ?></th>
							<th scope="col"><i class="fas fa-clock me-1"></i><?php echo lang('admin_last_login'); ?></th>
							<th class="text-center" scope="col"><?php echo lang('admin_edit'); ?></th>
							<th class="text-center" scope="col">Password</th>
							<th class="text-center" scope="col">Email</th>
							<th class="text-center" scope="col"><?php echo lang('admin_delete'); ?></th>
						</tr>
					</thead>
					<tbody hx-confirm="Are you sure you want to delete the user?" hx-target="closest tr" hx-swap="outerHTML swap:1s">

						<?php
						$i = 0;
						foreach ($results->result() as $row) { ?>
							<tr>
							<td class="align-middle">
								<a href="<?php echo site_url('user/edit') . "/" . $row->user_id; ?>" class="fw-bold text-decoration-none">
									<?php echo $row->user_name; ?>
								</a>
							</td>
							<td class="align-middle">
								<span class="badge bg-secondary"><?php echo $row->user_callsign; ?></span>
							</td>
							<td class="align-middle"><?php echo $row->user_email; ?></td>
							<td class="align-middle">
								<?php 
								$l = $this->config->item('auth_level');
								$type_class = ($row->user_type == 99) ? 'bg-danger' : 'bg-primary';
								echo '<span class="badge ' . $type_class . '">' . $l[$row->user_type] . '</span>';
								?>
							</td>
							<td class="align-middle">
								<?php
								if ($row->last_login_date != null) {
									echo '<small class="text-muted">' . $row->last_login_date . '</small>';
								} else {
									echo '<span class="badge bg-warning text-dark">' . lang('general_word_never') . '</span>';
								} ?>
							</td>
							<td class="text-center align-middle">
								<a href="<?php echo site_url('user/edit') . "/" . $row->user_id; ?>" 
								   class="btn btn-outline-primary btn-sm" 
								   title="Edit User">
									<i class="fas fa-user-edit"></i>
								</a>
							</td>
							<td class="text-center align-middle">
								<?php if ($_SESSION['user_id'] != $row->user_id) { ?>
									<a href="<?php echo site_url('user/admin_send_passwort_reset') . "/" . $row->user_id; ?>" 
									   class="btn btn-primary btn-sm" 
									   title="Send Password Reset Email">
										<i class="fas fa-key"></i>
									</a>
								<?php } ?>
							</td>
							<td class="text-center align-middle">
								<?php if ($_SESSION['user_id'] != $row->user_id) { ?>
									<a href="<?php echo site_url('user/resend_welcome_email/' . $row->user_id); ?>" 
									   class="btn btn-success btn-sm" 
									   title="Resend Welcome Email (without password)">
										<i class="fas fa-envelope text-white"></i>
									</a>
								<?php } ?>
							</td>
							<td class="text-center align-middle">
								<?php if ($_SESSION['user_id'] != $row->user_id) { ?>
									<button class="btn btn-danger btn-sm" 
											hx-delete="<?php echo site_url('user/delete_new/' . $row->user_id); ?>"
											title="Delete User">
										<i class="fas fa-user-minus"></i>
									</button>
								<?php } ?>
							</td>
							</tr>
						<?php $i++;
						} ?>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>