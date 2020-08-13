<div class="container lotw">

	<h1><?php echo $page_title; ?></h1>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			<a class="btn btn-success btn-sm float-right" href="#" role="button"><i class="fas fa-cloud-upload-alt"></i> Upload Certificate</a>Available Certificates
		</div>

		<div class="card-body">
			<?php if(isset($error)) { ?>
				<div class="alert alert-danger" role="alert">
			  	<?php echo $error; ?>
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
				    	</tr>
					</thead>
				 
					<tbody>

						<?php foreach ($lotw_cert_results->result() as $row) { ?>
							<tr>
					      		<td><?php echo $row->callsign; ?></td>
					      		<td><?php echo $row->cert_dxcc; ?></td>
								<td><?php echo $row->date_created; ?></td>
								<td><?php echo $row->date_expires; ?></td>
								<td></td>
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
