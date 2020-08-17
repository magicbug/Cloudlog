<div class="container lotw">

	<h1><?php echo $page_title; ?></h1>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			<a class="btn btn-success btn-sm float-right" href="<?php echo site_url('/lotw/cert_upload'); ?>" role="button"><i class="fas fa-cloud-upload-alt"></i> Upload Certificate</a>Available Certificates
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
				 			<th scope="col">Callsign</th>
							<th scope="col">DXCC</th>
				      		<th scope="col">Date Created</th>
				    		<th scope="col">Date Expires</th>
				    		<th scope="col">Status</th>
				    		<th scope="col">Options</th>
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
										<span class="badge badge-success">Valid</span>
									<?php } else { ?>
										<span class="badge badge-dark">Expired</span>
									<?php } ?>
								</td>
								<td>
									<a class="btn btn-primary btn-sm" href="<?php echo site_url('lotw/delete_cert/'.$row->lotw_cert_id); ?>" role="button"><i class="far fa-trash-alt"></i> Delete</a>
								</td>
							</tr>
						<?php } ?>

					</tbody>
				</table>
			</div>

			<?php } else { ?>
			<div class="alert alert-info" role="alert">
				You need to upload some LoTW p12 certificates to use this area.
			</div>
			<?php } ?>

	    </div>
	</div>
	<!-- Card Ends -->

</div>
