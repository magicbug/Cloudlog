<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?>
  </div>
  <div class="card-body">
    <p class="card-text">Station Profiles define locations of operating, useful for portable operating or using a friends QTH.</p>
    <p class="card-text">Within Cloudlog these act in a similar way to logbooks, a Station Profile keeps a set of QSOs together.</p>

		<?php if ($stations->num_rows() > 0) { ?>

		<?php if($current_active == 0) { ?>
		<div class="alert alert-danger" role="alert">
		  Attention you need to set an active station profile.
		</div>
		<?php } ?>

		<?php if($is_there_qsos_with_no_station_id == 0) { ?>
			<div class="alert alert-danger" role="alert">
		  		<span class="badge badge-pill badge-warning">Warning</span> Due to recent changes within Cloudlog you need to reassign QSOs to your station profiles.
			</div>
		<?php } ?>

		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">Profile Name</th>
					<th scope="col">Station Callsign</th>
					<th scope="col">Country</th>
					<th scope="col">Gridsquare</th>
					<th scope="col">City</th>
					<th scope="col">IOTA</th>
					<th scope="col">SOTA</th>
					<th scope="col">CQ</th>
					<th scope="col">ITU</th>
					<th scope="col">QSO Count</th>
					<th></th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($stations->result() as $row) { ?>
				<tr>
					<td><?php echo $row->station_profile_name;?> (#<?php echo $row->station_id;?>)</td>
					<td><?php echo $row->station_callsign;?></td>
					<td><?php echo $row->station_country;?></td>
					<td><?php echo $row->station_gridsquare;?></td>
					<td><?php echo $row->station_city;?></td>	
					<td><?php echo $row->station_iota;?></td>	
					<td><?php echo $row->station_sota;?></td>
					<td><?php echo $row->station_cq;?></td>
					<td><?php echo $row->station_itu;?></td>
					<td><?php echo $row->qso_total;?></td>
					<td>
						<?php if($row->station_active != 1) { ?>			
							<a href="<?php echo site_url('station/set_active/').$current_active."/".$row->station_id; ?>" class="btn btn-outline-secondary btn-sm btn-sm" onclick="return confirm('Are you sure you want to make logbook <?php echo $row->station_profile_name; ?> the active logbook?');">Set Active</a>
						<?php } else { ?>
							<span class="badge badge-success">Active Logbook</span>
						<?php } ?>

						<?php if($is_there_qsos_with_no_station_id == 0) { ?>
							<a href="<?php echo site_url('station/reassign_profile/').$row->station_id; ?>" class="btn btn-outline-secondary btn-sm btn-sm" onclick="return confirm('Are you sure you want to reassign QSOs to the  <?php echo $row->station_profile_name; ?> profile?');">Reassign</a>
						<?php } ?>
					</td>
					<td><a href="<?php echo site_url('station/delete')."/".$row->station_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want delete QSO <?php echo $row->station_profile_name; ?>?');"><i class="fas fa-trash-alt"></i> Delete</a></td>
				</tr>

				<?php } ?>
			</tbody>
		<table>
		<?php } ?>

		<p><a href="<?php echo site_url('station/create'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create a Station Profile</a></p>
  </div>
</div>


</div>