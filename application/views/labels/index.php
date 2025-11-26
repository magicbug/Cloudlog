<div class="container">

	<!-- Page Header -->
	<div class="row mt-4 mb-4">
		<div class="col-md-12">
			<div class="d-flex justify-content-between align-items-center">
				<h2><i class="fas fa-tags me-2"></i><?php echo $page_title; ?></h2>
				<div>
					<a href="<?php echo site_url('labels/create'); ?>" class="btn btn-primary">
						<i class="fas fa-plus me-2"></i>Create New Label Type
					</a>
					<a href="<?php echo site_url('labels/createpaper'); ?>" class="btn btn-primary">
						<i class="fas fa-plus me-2"></i>Create New Paper Type
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('message'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('error')) { ?>
		<!-- Display Message -->
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<i class="fas fa-exclamation-triangle me-2"></i><?php echo $this->session->flashdata('error'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('warning')) { ?>
		<!-- Display Message -->
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<i class="fas fa-exclamation-circle me-2"></i><?php echo $this->session->flashdata('warning'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

	<?php if ($papertypes) { ?>
	<!-- Paper Types Card -->
	<div class="card mb-4">
		<div class="card-header bg-light">
			<h5 class="mb-0"><i class="fas fa-file me-2"></i>Paper types</h5>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover table-striped mb-0">
					<thead class="table-light">
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Measurement</th>
							<th scope="col">Width</th>
							<th scope="col">Height</th>
							<th scope="col">Used by labels</th>
							<th scope="col">Orientation</th>
							<th class="text-center" scope="col">Edit</th>
							<th class="text-center" scope="col">Delete</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($papertypes as $paper) { ?>
						<tr class='paper_<?php echo $paper->paper_id ?>'>
							<td><?php echo $paper->paper_name; ?></td>
							<td><?php echo $paper->metric; ?></td>
							<td><?php echo $paper->width; ?></td>
							<td><?php echo $paper->height; ?></td>
							<td><?php echo $paper->lbl_cnt ?? '0' ?></td>
							<td><?php echo $paper->orientation == 'P' ? 'Portrait': 'Landscape'; ?></td>
							<td class="text-center">
								<a href="<?php echo site_url('labels/editpaper/' . $paper->paper_id); ?>" class="btn btn-outline-primary btn-sm">
									<i class="fas fa-edit"></i>
								</a>
							</td>
							<td class="text-center">
								<a href="javascript:deletepaper(<?php echo $paper->paper_id; ?>);" class="btn btn-outline-danger btn-sm">
									<i class="fas fa-trash-alt"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ($labels) { ?>
	<!-- Label Types Card -->
	<div class="card mb-4">
		<div class="card-header bg-light">
			<h5 class="mb-0"><i class="fas fa-tag me-2"></i>Label types</h5>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover table-striped mb-0">
					<thead class="table-light">
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Paper Type</th>
							<th scope="col">Measurement</th>
							<th scope="col">Width</th>
							<th scope="col">Height</th>
							<th scope="col">Font Size</th>
							<th scope="col">QSOs</th>
							<th class="text-center" scope="col">Use For Print</th>
							<th class="text-center" scope="col">Edit</th>
							<th class="text-center" scope="col">Delete</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($labels as $label) { ?>
						<tr class='label_<?php echo $label->id ?>'>
							<td><?php echo $label->label_name; ?></td>
							<td><?php echo $label->paper_name ?? '<span class="badge text-bg-danger">No paper assigned</span>' ?></td>
							<td><?php echo $label->metric; ?></td>
							<td><?php echo $label->width; ?></td>
							<td><?php echo $label->height; ?></td>
							<td><?php echo $label->font_size; ?></td>
							<td><?php echo $label->qsos; ?></td>
							<td class="text-center">
								<?php if (($label->paper_name ?? '') != '') { ?>
									<input type="checkbox" <?php if ($label->useforprint == 1) {echo 'checked';}?>>
								<?php } ?>
							</td>
							<td class="text-center">
								<a href="<?php echo site_url('labels/edit/' . $label->id); ?>" class="btn btn-outline-primary btn-sm">
									<i class="fas fa-edit"></i>
								</a>
							</td>
							<td class="text-center">
								<a href="javascript:deletelabel(<?php echo $label->id; ?>);" class="btn btn-outline-danger btn-sm">
									<i class="fas fa-trash-alt"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>

	<!-- QSL Card Labels Pending -->
	<div class="card">
		<div class="card-header bg-light">
			<h5 class="mb-0"><i class="fas fa-clock me-2"></i>QSL Card Labels Pending</h5>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover table-striped mb-0">
					<thead class="table-light">
						<tr>
							<th scope="col"><i class="fas fa-user me-1"></i>Callsign</th>
							<th scope="col"><i class="fas fa-map-marker-alt me-1"></i>Station Location</th>
							<th scope="col"><i class="fas fa-th me-1"></i>Gridsquare</th>
							<th scope="col"><i class="fas fa-list-ol me-1"></i>QSOs Waiting</th>
							<th class="text-center" scope="col">View QSOs</th>
							<th class="text-center" scope="col">Print</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($qsos as $qso) { ?>
							<tr>
								<td><strong><?php echo $qso->station_callsign; ?></strong></td>
								<td><?php echo $qso->station_profile_name; ?></td>
								<td><?php echo $qso->station_gridsquare; ?></td>
								<td><span class="badge text-bg-primary"><?php echo $qso->count; ?></span></td>
								<td class="text-center">
									<a href="<?php echo site_url('qslprint/index/'.$qso->station_id); ?>" class="btn btn-outline-info btn-sm">
										<i class="fas fa-search"></i>
									</a>
								</td>
								<td class="text-center">
									<button class="btn btn-outline-success btn-sm printbutton" onclick="printat(<?php echo $qso->station_id; ?>)">
										<i class="fas fa-print"></i>
									</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
