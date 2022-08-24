<div class="container" id="create_station_profile">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('notice')) { ?>
		<div id="message" >
			<?php echo $this->session->flashdata('notice'); ?>
		</div>
	<?php } ?>

	<?php $this->load->helper('form'); ?>

	<?php echo validation_errors(); ?>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<div class="card-header"><?php echo $page_title; ?>: <?php echo $station_logbook_details->logbook_name; ?></div>
				<div class="card-body">
					<form method="post" action="<?php echo site_url('logbooks/edit/'); ?><?php echo $station_logbook_details->logbook_id; ?>" name="create_profile">
						<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">

						<div class="form-group">
							<label for="stationLogbookNameInput">Station Logbook Name</label>
							<input type="text" class="form-control" name="station_logbook_name" id="stationLogbookNameInput" aria-describedby="stationLogbookNameInputHelp" value="<?php if(set_value('station_logbook_name') != "") { echo set_value('station_logbook_name'); } else { echo $station_logbook_details->logbook_name; } ?>" required>
							<small id="stationLogbookNameInputHelp" class="form-text text-muted">Shortname for the station location. For example: Home (IO87IP)</small>
						</div>

						<input type="hidden" class="form-control" name="station_logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>" required>	

						<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Update Station Logbook Name</button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<div class="card-header">Public Slug</div>
				<div class="card-body">
					<p>Setting a public slug allows you to share your logbook with anyone via a custom website address, this slug can contain letters & numbers only.</p>
					
					<form hx-post="<?php echo site_url('logbooks/save_publicslug/'); ?>" hx-target="#publicSlugForm">
					<div id="publicSlugForm">
					</div>
					<div class="form-group">
						<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">
						<label for="publicSlugInput">Type in Public Slug choice</label>
						<div hx-target="this" hx-swap="outerHTML">
							<input class="form-control" name="public_slug" id="publicSlugInput" pattern="[a-zA-Z0-9-]+" value="<?php echo $station_logbook_details->public_slug; ?>" hx-post="<?php echo site_url('logbooks/publicslug_validate/'); ?>"  hx-trigger="keyup changed delay:500ms" required>
						</div>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Save</button>
					</form>

					<?php if($station_logbook_details->public_slug != "") { ?>
					<div class="alert alert-info" role="alert" style="margin-top: 20px;">
						Visit Public Page <a href="<?php echo site_url('visitor'); ?>/<?php echo $station_logbook_details->public_slug; ?>" target="_blank"><?php echo site_url('visitor'); ?>/<?php echo $station_logbook_details->public_slug; ?></a>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<div class="card-header">Station Locations</div>
				<div class="card-body">
					<form method="post" action="<?php echo site_url('logbooks/edit/'); ?><?php echo $station_logbook_details->logbook_id; ?>" name="create_profile">
					<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">

					<?php
						$linked_stations = array();
						if ($station_locations_linked) {
							foreach ($station_locations_linked->result() as $row) {
								$linked_stations[] = $row->station_id;
							}
						}
					?>

					<div class="form-group">
						<label for="StationLocationsSelect">Select Available Station Locations</label>
						<select name="SelectedStationLocation" class="form-control" id="StationLocationSelect" aria-describedby="StationLocationSelectHelp">
							<?php foreach ($station_locations_list->result() as $row) {
								if (!in_array($row->station_id, $linked_stations)) { ?>
								<option value="<?php echo $row->station_id;?>"><?php echo $row->station_profile_name;?> (Callsign: <?php echo $row->station_callsign;?> DXCC: <?php echo $row->station_country;?>)</option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>

					<input type="hidden" class="form-control" name="station_logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>" required>	

					<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Link Location</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			Linked Locations
		</div>

	    <div class="table-responsive">
			<table id="station_logbooks_linked_table" class="table table-hover">
				<thead class="thead-light">
					<tr>
						<th scope="col">Location Name</th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if ($station_locations_linked) {
							foreach ($station_locations_linked->result() as $row) {
					?>
					<tr>
						<td><?php echo $row->station_profile_name;?> (Callsign: <?php echo $row->station_callsign;?> DXCC: <?php echo $row->station_country;?>)</td>
						<td><a href="<?php echo site_url('logbooks/delete_relationship/'); ?><?php echo $station_logbook_details->logbook_id; ?>/<?php echo $row->station_id;?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
					</tr>
					<?php
							}
						} else {
					?>
					<tr>
						<td colspan="2">No linked locations</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

	</div>

</div>
