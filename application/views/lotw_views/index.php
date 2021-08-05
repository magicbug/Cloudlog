<div class="container lotw">
<br>
	<a class="btn btn-outline-primary btn-sm float-right" href="<?php echo site_url('/lotw/import'); ?>" role="button"><i class="fas fa-cloud-download-alt"></i> <?php echo $this->lang->line('lotw_btn_lotw_import'); ?></a>
	<h2><?php echo $this->lang->line('lotw_title'); ?></h2>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			<a class="btn btn-outline-success btn-sm float-right" href="<?php echo site_url('/lotw/cert_upload'); ?>" role="button"><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line('lotw_btn_upload_certificate'); ?></a><i class="fab fa-expeditedssl"></i> <?php echo $this->lang->line('lotw_title_available_cert'); ?>
		</div>

		<div class="card-body">
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
				 			<th scope="col"><?php echo $this->lang->line('gen_hamradio_callsign'); ?></th>
							<th scope="col"><?php echo $this->lang->line('gen_hamradio_dxcc'); ?></th>
				      		<th scope="col"><?php echo $this->lang->line('lotw_date_created'); ?></th>
				    		<th scope="col"><?php echo $this->lang->line('lotw_date_expires'); ?></th>
				    		<th scope="col"><?php echo $this->lang->line('lotw_status'); ?></th>
				    		<th scope="col"><?php echo $this->lang->line('lotw_options'); ?></th>
				    	</tr>
					</thead>
				 
					<tbody>

						<?php foreach ($lotw_cert_results->result() as $row) { ?>
							<tr>
					      		<td><?php echo $row->callsign; ?></td>
					      		<td><?php echo ucfirst($row->cert_dxcc); ?></td>
								<td><?php 
									$valid_form = strtotime( $row->date_created );
									$new_valid_from = date($this->config->item('qso_date_format'), $valid_form );
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

									<?php if ($current_date <= $row->date_expires) { ?>
										<span class="badge badge-success"><?php echo $this->lang->line('lotw_valid'); ?></span>
									<?php } else { ?>
										<span class="badge badge-danger"><?php echo $this->lang->line('lotw_expired'); ?></span>
									<?php } ?>

									<?php if ($row->last_upload) { ?>
										<span class="badge badge-success"><?php echo $row->last_upload; ?></span>
									<?php } else { ?>
										<span class="badge badge-warning"><?php echo $this->lang->line('lotw_not_synced'); ?></span>
									<?php } ?>
								</td>
								<td>
									<a class="btn btn-outline-danger btn-sm" href="<?php echo site_url('lotw/delete_cert/'.$row->lotw_cert_id); ?>" role="button"><i class="far fa-trash-alt"></i> <?php echo $this->lang->line('lotw_btn_delete'); ?></a>
								</td>
							</tr>
						<?php } ?>

					</tbody>
				</table>
			</div>

			<?php } else { ?>
			<div class="alert alert-info" role="alert">
				<?php echo $this->lang->line('lotw_no_certs_uploaded'); ?>
			</div>
			<?php } ?>

	    </div>
	</div>
	<!-- Card Ends -->

	<br>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			<?php echo $this->lang->line('lotw_title_information'); ?>
		</div>

		<div class="card-body">
			<p><a class="btn btn-outline-success" href="<?php echo site_url('lotw/lotw_upload'); ?>"><?php echo $this->lang->line('lotw_btn_manual_sync'); ?></a></p>
		</div>
	</div>

</div>
