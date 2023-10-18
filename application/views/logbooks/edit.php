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
				<div class="card-header"><?php echo lang('station_logbooks_edit_logbook'); ?>: <?php echo $station_logbook_details->logbook_name; ?></div>
				<div class="card-body">
					<form method="post" action="<?php echo site_url('logbooks/edit/'); ?><?php echo $station_logbook_details->logbook_id; ?>" name="create_profile">
						<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">

						<div class="form-group">
							<label for="stationLogbookNameInput"><?php echo lang('station_logbooks_create_name'); ?></label>
							<input type="text" class="form-control" name="station_logbook_name" id="stationLogbookNameInput" aria-describedby="stationLogbookNameInputHelp" value="<?php if(set_value('station_logbook_name') != "") { echo set_value('station_logbook_name'); } else { echo $station_logbook_details->logbook_name; } ?>" required>
							<small id="stationLogbookNameInputHelp" class="form-text text-muted"><?php echo lang('station_logbooks_edit_name_hint'); ?></small>
						</div>

						<input type="hidden" class="form-control" name="station_logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>" required>	

						<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> <?php echo lang('station_logbooks_edit_name_update'); ?></button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md">
			<div class="card">
				<div class="card-header"><?php echo lang('station_logbooks_public_slug'); ?></div>
				<div class="card-body">
					<p><?php echo lang('station_logbooks_public_slug_hint'); ?></p>
					<p><?php echo lang('station_logbooks_public_slug_format1')?><br>
					<?php echo site_url('visitor'); ?>/<?php echo lang('station_logbooks_public_slug_format2'); ?></p>
					<form hx-post="<?php echo site_url('logbooks/save_publicslug/'); ?>" hx-target="#publicSlugForm" style="display: inline;">
					<div id="publicSlugForm">
					</div>
					<div class="form-group">
						<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">
						<label for="publicSlugInput"><?php echo lang('station_logbooks_public_slug_input'); ?></label>
						<div hx-target="this" hx-swap="outerHTML">
							<input class="form-control" name="public_slug" id="publicSlugInput" pattern="[a-zA-Z0-9-]+" value="<?php echo $station_logbook_details->public_slug; ?>" hx-post="<?php echo site_url('logbooks/publicslug_validate/'); ?>"  hx-trigger="keyup changed delay:500ms" required>
						</div>
					</div>
					<button type="submit" class="btn btn-primary" style="display:inline-block;"><i class="fas fa-plus-square"></i> <?php echo lang('admin_save'); ?></button>
					</form>
					<form hx-post="<?php echo site_url('logbooks/remove_publicslug/'); ?>" hx-target="#publicSlugForm" style="display: inline; margin-left: 5px;">
						<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">
						<button type="submit" class="btn btn-primary" style="display:inline-block;" onclick="removeSlug()"><i class="fas fa-minus-square"></i> <?php echo lang('admin_remove'); ?></button>
					</form>

					<?php if($station_logbook_details->public_slug != "") { ?>
					<div id="slugLink" class="alert alert-info" role="alert" style="margin-top: 20px;">
						<p><?php echo lang('station_logbooks_public_slug_visit') . " "; ?></p>
						<p><a href="<?php echo site_url('visitor'); ?>/<?php echo $station_logbook_details->public_slug; ?>" target="_blank"><?php echo site_url('visitor'); ?>/<?php echo $station_logbook_details->public_slug; ?></a></p>
					</div>
					<?php } ?>
					<form style="display: inline;">
					<input type="hidden" name="logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>">
					<p style="margin-top: 15px;"><?php echo lang('station_logbooks_public_search_hint'); ?></p>
					<label for="public_search"><?php echo lang('station_logbooks_public_search_enabled'); ?></label>
					<select class="custom-select" id="public_search" name="public_search" hx-post="<?php echo site_url('logbooks/save_publicsearch/'); ?>" hx-target="#publicSearchForm" hx-trigger="change">
						<option value="1" <?php if ($station_logbook_details->public_search == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
						<option value="0" <?php if ($station_logbook_details->public_search == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
					</select>
					</form>
					<p>
					<div id="publicSearchForm">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md">
			<div class="card">
				<div class="card-header"><?php echo lang('station_location_plural'); ?></div>
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
						<label for="StationLocationsSelect"><?php echo lang('station_logbooks_select_avail_loc'); ?></label>
						<select name="SelectedStationLocation" class="form-control" id="StationLocationSelect" aria-describedby="StationLocationSelectHelp">
							<?php foreach ($station_locations_list->result() as $row) {
								if (!in_array($row->station_id, $linked_stations)) { ?>
								<option value="<?php echo $row->station_id;?>"><?php echo $row->station_profile_name;?> (<?php echo lang('gen_hamradio_callsign'); ?>: <?php echo $row->station_callsign;?> <?php echo lang('gen_hamradio_dxcc'); ?>: <?php echo $row->station_country; if ($row->dxcc_end != NULL) { echo ' ('.lang('gen_hamradio_deleted_dxcc').')'; } ?>)</option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>

					<input type="hidden" class="form-control" name="station_logbook_id" value="<?php echo $station_logbook_details->logbook_id; ?>" required>	

					<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> <?php echo lang('station_logbooks_link_loc'); ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			<?php echo lang('station_logbooks_linked_loc'); ?>
		</div>

	    <div class="table-responsive">
			<table id="station_logbooks_linked_table" class="table table-hover">
				<thead class="thead-light">
					<tr>
						<th scope="col"><?php echo lang('station_location_name'); ?></th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if ($station_locations_linked) {
							foreach ($station_locations_linked->result() as $row) {
					?>
					<tr>
						<td><?php echo $row->station_profile_name;?> (<?php echo lang('gen_hamradio_callsign'); ?>: <?php echo $row->station_callsign;?> <?php echo lang('gen_hamradio_dxcc'); ?>: <?php echo $row->station_country; if ($row->end != NULL) { echo ' <span class="badge badge-danger">'.lang('gen_hamradio_deleted_dxcc').'</span>'; } ?>)</td>
						<td><a href="<?php echo site_url('logbooks/delete_relationship/'); ?><?php echo $station_logbook_details->logbook_id; ?>/<?php echo $row->station_id;?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
					</tr>
					<?php
							}
						} else {
					?>
					<tr>
						<td colspan="2"><?php echo lang('station_logbooks_no_linked_loc'); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

	</div>

</div>
