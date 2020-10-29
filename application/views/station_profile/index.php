<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<h2><?php echo $page_title; ?></h2>

<div class="card">
  <div class="card-header">
    Station Profiles
  </div>
  <div class="card-body">
    <p class="card-text">Station Profiles define operating locations, such as your QTH, a friend's QTH, or a portable station.</p>
	<p class="card-text">Similar to logbooks, a Station Profile keeps a set of QSOs together.</p>
	<p class="card-text">Only one logbook may be active at a time. In the table below this is shown with the "Active Logbook" badge.</p>

		<?php if ($stations->num_rows() > 0) { ?>

		<?php if($current_active == 0) { ?>
		<div class="alert alert-danger" role="alert">
		  Attention you need to set an active station profile.
		</div>
		<?php } ?>

		<?php if($is_there_qsos_with_no_station_id >= 1) { ?>
			<div class="alert alert-danger" role="alert">
		  		<span class="badge badge-pill badge-warning">Warning</span> Due to recent changes within Cloudlog you need to reassign QSOs to your station profiles.

		  		If you haven't any station profiles, create a profile then <a href="<?php echo site_url('station/assign_all/'); ?>" class="btn btn-danger" onclick="return confirm('Assign All QSOs to Default Station ID"><i class="fas fa-trash-alt"></i> Press this Button to assign all QSOs to the the #1 station ID</a>
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
					<th scope="col">QSO Count</th>
					<th></th>
					<th scope="col"></th>
					<th scope="col"></th>
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
					<td><?php echo $row->qso_total;?></td>
					<td style="text-align: center">
						<?php if($row->station_active != 1) { ?>			
							<a href="<?php echo site_url('station/set_active/').$current_active."/".$row->station_id; ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Are you sure you want to make logbook <?php echo $row->station_profile_name; ?> the active logbook?');">Set Active</a>
						<?php } else { ?>
							<span class="badge badge-success">Active Logbook</span>
						<?php } ?>

						<?php if($is_there_qsos_with_no_station_id >= 1) { ?>
							<a href="<?php echo site_url('station/reassign_profile/').$row->station_id; ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Are you sure you want to reassign QSOs to the <?php echo $row->station_profile_name; ?> profile?');">Reassign</a>
						<?php } ?>
					</td>
					<td>
						<a href="<?php echo site_url('station/edit')."/".$row->station_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
					</td>
                    <td>
                        <a href="<?php echo site_url('station/deletelog')."/".$row->station_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete all QSOs within this station profile?');"><i class="fas fa-trash-alt"></i> Empty Log</a></td>
                    </td>
					<td>
						<a href="<?php echo site_url('station/delete')."/".$row->station_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want delete station profile <?php echo $row->station_profile_name; ?> this will delete all QSOs within this station profile?');"><i class="fas fa-trash-alt"></i> Delete Profile</a></td>
				</tr>

				<?php } ?>
			</tbody>
		<table>
		<?php } ?>


		<p><a href="<?php echo site_url('station/create'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create a Station Profile</a></p>
  </div>
</div>


</div>