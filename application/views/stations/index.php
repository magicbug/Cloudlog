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
    Station Locations
  </div>
  <div class="card-body">
    <p class="card-text">Station Locations define operating locations, such as your QTH, a friend's QTH, or a portable station.</p>
	<p class="card-text">Similar to logbooks, a station profile keeps a set of QSOs together.</p>
	<p class="card-text">Only one station may be active at a time. In the table below this is shown with the "Active Station" badge.</p>

	  <p><a href="<?php echo site_url('station/create'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create a Station Location</a></p>
	  
		<?php if ($stations) { ?>

		<?php if($current_active == 0) { ?>
		<div class="alert alert-danger" role="alert">
		  Attention: You need to set an active station location. Go to Callsign->Station Location to select one.
		</div>
		<?php } ?>

		<?php if($is_there_qsos_with_no_station_id >= 1) { ?>
			<div class="alert alert-danger" role="alert">
		  		<span class="badge rounded-pill text-bg-warning">Warning</span> Due to recent changes within Cloudlog you need to reassign QSOs to your station profiles.

		  		Create a station profile, if you haven't already, then <a href="<?php echo site_url('station/assign_all/'); ?>" class="btn btn-danger" onclick="return confirm('Assign All QSOs to Default Station ID"><i class="fas fa-trash-alt"></i> press this button to assign all QSOs to the first Station Profile.</a>
			</div>
		<?php } ?>
	  
		<div class="table-responsive">
		<table id="station_locations_table" class="table table-sm table-striped">
			<thead>
				<tr>
					<th scope="col">Profile Name</th>
					<th scope="col">Station Callsign</th>
					<th scope="col">Country</th>
					<th scope="col">Gridsquare</th>
					<th></th>
					<th scope="col">Edit</th>
					<th scope="col">Copy</th>
					<th scope="col">Empty Log</th>
                    <th scope="col">Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($stations->result() as $row) { ?>
				<tr>
					<td>
						<?php echo $row->station_profile_name;?><br>
					</td>
					<td><?php echo $row->station_callsign;?></td>
					<td><?php echo $row->station_country == '' ? '- NONE -' : $row->station_country; if ($row->dxcc_end != NULL) { echo ' <span class="badge text-bg-danger">'.lang('gen_hamradio_deleted_dxcc').'</span>'; } ?></td>
					<td><?php echo $row->station_gridsquare;?></td>
					<td style="text-align: center" data-order="<?php echo $row->station_id;?>">
						<?php if($row->station_active != 1) { ?>
							<a href="<?php echo site_url('station/set_active/').$current_active."/".$row->station_id; ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Are you sure you want to make station <?php echo $row->station_profile_name; ?> the active station?');">Set Active</a>
						<?php } else { ?>
							<span class="badge text-bg-success">Active Station</span>
						<?php } ?>

						<?php if($is_there_qsos_with_no_station_id >= 1) { ?>
							<a href="<?php echo site_url('station/reassign_profile/').$row->station_id; ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Are you sure you want to reassign QSOs to the <?php echo $row->station_profile_name; ?> profile?');">Reassign</a>
						<?php } ?>
						<br>
						<span class="badge text-bg-info">ID: <?php echo $row->station_id;?></span>
						<span class="badge text-bg-light"><?php echo $row->qso_total;?> QSOs</span>
					</td>
					<td>
						<?php if($row->user_id == "") { ?>
							<a href="<?php echo site_url('station/claim_user')."/".$row->station_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-plus"></i> Claim Ownership</a>
						<?php } ?>
						<a href="<?php echo site_url('station/edit')."/".$row->station_id; ?>" title="Edit" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
						</td>
						<td>
						<a href="<?php echo site_url('station/copy')."/".$row->station_id; ?>" title="Copy" class="btn btn-outline-primary btn-sm"><i class="fas fa-copy"></i></a>
					</td>
                    <td>
                        <a href="<?php echo site_url('station/deletelog')."/".$row->station_id; ?>" class="btn btn-danger btn-sm" title="Empty Log" onclick="return confirm('Are you sure you want to delete all QSOs within this station profile?');"><i class="fas fa-trash-alt"></i></a></td>
                    </td>
					<td>
						<?php if($row->station_active != 1) { ?>
							<a href="<?php echo site_url('station/delete')."/".$row->station_id; ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want delete station profile <?php echo $row->station_profile_name; ?> this will delete all QSOs within this station profile?');"><i class="fas fa-trash-alt"></i></a>
						<?php } ?>
					</td>
				</tr>

				<?php } ?>
			</tbody>
		<table>
		</div>
		<?php } ?>
  </div>
</div>


</div>
