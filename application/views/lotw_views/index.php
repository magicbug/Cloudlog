<div class="container lotw">
<br>
	<a class="btn btn-outline-primary btn-sm float-end" href="<?php echo site_url('/lotw/import'); ?>" role="button"><i class="fas fa-cloud-download-alt"></i> <?php echo lang('lotw_btn_lotw_import'); ?></a>
	<h2><?php echo lang('lotw_title'); ?></h2>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			<a class="btn btn-outline-success btn-sm float-end" href="<?php echo site_url('/lotw/cert_upload'); ?>" role="button"><i class="fas fa-cloud-upload-alt"></i> <?php echo lang('lotw_btn_upload_certificate'); ?></a><i class="fab fa-expeditedssl"></i> <?php echo lang('lotw_title_available_cert'); ?>
		</div>

		<div class="lotw-cert-list">
			<?php if(isset($error)) { ?>
				<div class="alert alert-danger" role="alert">
			  	<?php echo $error; ?>
				</div>
	    	<?php } ?>

	    	<?php if(isset($_SESSION['Success'])) { ?>
				<div class="alert alert-success" role="alert">
			  	<?php echo $_SESSION['Success']; ?>
				</div>
	    	<?php } ?>

	    	<?php if ($lotw_cert_results->num_rows() > 0) { ?>

	    	<div class="table-responsive">
				<table class="table table-hover">
					<thead class="thead-light">
						<tr>
				 			<th scope="col"><?php echo lang('gen_hamradio_callsign'); ?></th>
							<th scope="col"><?php echo lang('gen_hamradio_dxcc'); ?></th>
							<th scope="col"><?php echo lang('lotw_qso_start_date'); ?></th>
							<th scope="col"><?php echo lang('lotw_qso_end_date'); ?></th>
							<th scope="col"><?php echo lang('lotw_date_created'); ?></th>
							<th scope="col"><?php echo lang('lotw_date_expires'); ?></th>
							<th scope="col"><?php echo lang('lotw_status'); ?></th>
							<th scope="col"><?php echo lang('lotw_options'); ?></th>
						</tr>
					</thead>
				 
					<tbody>

						<?php foreach ($lotw_cert_results->result() as $row) { ?>
							<tr>
					      		<td><?php echo $row->callsign; ?></td>
                           <td><?php echo $row->cert_dxcc == '' ? '- NONE -' : ucfirst($row->cert_dxcc); if ($row->cert_dxcc_end != NULL) { echo ' <span class="badge text-bg-danger">'.lang('gen_hamradio_deleted_dxcc').'</span>'; } ?></td>
								<td><?php
									if (isset($row->qso_start_date)) {
										$valid_qso_start = strtotime( $row->qso_start_date );
										$new_valid_qso_start = date($this->config->item('qso_date_format'), $valid_qso_start );
										echo $new_valid_qso_start;
									} else {
										echo "n/a";
									} ?>
								</td>
								<td><?php
									if (isset($row->qso_end_date)) {
										$valid_qso_end = strtotime( $row->qso_end_date );
										$new_valid_qso_end = date($this->config->item('qso_date_format'), $valid_qso_end );
										echo $new_valid_qso_end;
									} else {
										echo "n/a";
									} ?>
								</td>
								<td><?php
									$valid_from = strtotime( $row->date_created );
									$new_valid_from = date($this->config->item('qso_date_format'), $valid_from );
									echo $new_valid_from; ?>
								</td>
								<td>
									<?php
									$valid_to = strtotime( $row->date_expires );
									$new_valid_to = date($this->config->item('qso_date_format'), $valid_to );
									echo $new_valid_to; ?>
								</td>
								<td>
									<?php $current_date = date('Y-m-d H:i:s'); ?>
									<?php $warning_date = date('Y-m-d H:i:s', strtotime($row->date_expires.'-30 days')); ?>

									<?php if ($current_date > $row->date_expires) { ?>
										<span class="badge text-bg-danger"><?php echo lang('lotw_expired'); ?></span>
									<?php } else if ($current_date <= $row->date_expires && $current_date > $warning_date) { ?>
										<span class="badge text-bg-warning"><?php echo lang('lotw_expiring'); ?></span>
									<?php } else { ?>
										<span class="badge text-bg-success"><?php echo lang('lotw_valid'); ?></span>
									<?php } ?>

									<?php if ($row->last_upload) {
										$last_upload = date($this->config->item('qso_date_format').' H:i:s', strtotime( $row->last_upload )); ?>
										<span class="badge text-bg-success"><?php echo $last_upload; ?></span>
									<?php } else { ?>
										<span class="badge text-bg-warning"><?php echo lang('lotw_not_synced'); ?></span>
									<?php } ?>
								</td>
								<td>
									<a class="btn btn-outline-danger btn-sm" href="<?php echo site_url('lotw/delete_cert/'.$row->lotw_cert_id); ?>" role="button"><i class="far fa-trash-alt"></i> <?php echo lang('lotw_btn_delete'); ?></a>
								</td>
							</tr>
						<?php } ?>

					</tbody>
				</table>
			</div>

			<?php } else { ?>
			<div class="alert alert-info" role="alert">
				<?php echo lang('lotw_no_certs_uploaded'); ?>
			</div>
			<?php } ?>

	    </div>
	</div>
	<!-- Card Ends -->

	<br>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			<?php echo lang('lotw_title_information'); ?>
		</div>

		<div class="card-body">
			<button class="btn btn-outline-success" hx-get="<?php echo site_url('lotw/lotw_upload'); ?>"  hx-target="#lotw_manual_results">
				<?php echo lang('lotw_btn_manual_sync'); ?>
			</button>

			<div id="lotw_manual_results"></div>
		</div>
	</div>

</div>
