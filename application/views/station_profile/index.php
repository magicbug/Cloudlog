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
  <div class="card-body">
    <p class="card-text"><?php echo lang('station_location_header_ln1'); ?></p>
	<p class="card-text"><?php echo lang('station_location_header_ln2'); ?></p>
	<p class="card-text"><?php echo lang('station_location_header_ln3'); ?></p>

	  <p><a href="<?php echo site_url('station/create'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> <?php echo lang('station_location_create'); ?></a></p>

		<?php if ($stations->num_rows() > 0) { ?>

		<?php if($current_active == 0) { ?>
		<div class="alert alert-danger" role="alert">
		<?php echo lang('station_location_warning'); ?>
		</div>
		<?php } ?>

		<?php if (($is_there_qsos_with_no_station_id >= 1) && ($is_admin)) { ?>
			<div class="alert alert-danger" role="alert">
		  		<span class="badge rounded-pill text-bg-warning"><?php echo lang('general_word_warning'); ?></span> <?php echo lang('station_location_warning_reassign'); ?>
				</br>
				<?php echo lang('station_location_reassign_at'); ?> <a href="<?php echo site_url('maintenance/'); ?>" class="btn btn-warning"><i class="fas fa-sync"></i><?php echo lang('account_word_admin') . "/" . lang('general_word_maintenance'); ?></a>
			</div>
		<?php } ?>

		<div class="table-responsive">
		<table id="station_locations_table" class="table table-sm table-striped">
			<thead>
				<tr>
					<th scope="col"><?php echo lang('station_location_name'); ?></th>
					<th scope="col"><?php echo lang('station_location_callsign'); ?></th>
					<th scope="col"><?php echo lang('general_word_country'); ?></th>
					<th scope="col"><?php echo lang('gen_hamradio_gridsquare'); ?></th>
					<th></th>
					<th scope="col"><?php echo lang('admin_edit'); ?></th>
					<th scope="col"><?php echo lang('admin_copy'); ?></th>
					<th scope="col"><?php echo lang('station_location_emptylog'); ?></th>
                    <th scope="col"><?php echo lang('admin_delete'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($stations->result() as $row) { ?>
				<tr>
					<td style="text-align: center; vertical-align: middle;">
						<?php echo $row->station_profile_name;?><br>
					</td>
					<td style="text-align: center; vertical-align: middle;"><?php echo $row->station_callsign;?></td>
					<td style="text-align: center; vertical-align: middle;"><?php echo $row->station_country == '' ? '- NONE -' : $row->station_country; if ($row->dxcc_end != NULL) { echo ' <span class="badge text-bg-danger">'.lang('gen_hamradio_deleted_dxcc').'</span>'; } ?></td>
					<td style="text-align: center; vertical-align: middle;"><?php echo $row->station_gridsquare;?></td>
					<td style="text-align: center" data-order="<?php echo $row->station_id;?>">
						<?php if($row->station_active != 1) { ?>
							<a href="<?php echo site_url('station/set_active/').$current_active."/".$row->station_id; ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('<?php echo lang('station_location_confirm_active'); ?> <?php echo $row->station_profile_name; ?>');"><?php echo lang('station_location_set_active'); ?></a>
						<?php } else { ?>
							<span class="badge text-bg-success"><?php echo lang('station_location_active'); ?></span>
						<?php } ?>

						<br>
						<span class="badge text-bg-info">ID: <?php echo $row->station_id;?></span>
						<span class="badge text-bg-light"><?php echo $row->qso_total;?> <?php echo lang('gen_hamradio_qso'); ?></span>
					</td>
					<td style="text-align: center; vertical-align: middle;">
						<?php if($row->user_id == "") { ?>
							<a href="<?php echo site_url('station/claim_user')."/".$row->station_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-plus"></i> <?php echo lang('station_location_claim_ownership'); ?></a>
						<?php } ?>
						<a href="<?php echo site_url('station/edit')."/".$row->station_id; ?>" title=<?php echo lang('admin_edit'); ?> class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
						</td>
						<td style="text-align: center; vertical-align: middle;">
						<a href="<?php echo site_url('station/copy')."/".$row->station_id; ?>" title=<?php echo lang('admin_copy'); ?> class="btn btn-outline-primary btn-sm"><i class="fas fa-copy"></i></a>
					</td>
                    <td style="text-align: center; vertical-align: middle;">
                        <a href="<?php echo site_url('station/deletelog')."/".$row->station_id; ?>" class="btn btn-danger btn-sm" title=<?php echo lang('station_location_emptylog'); ?> onclick="return confirm('<?php echo lang('station_location_confirm_del_qso'); ?>');"><i class="fas fa-trash-alt"></i></a></td>
                    </td>
					<td style="text-align: center; vertical-align: middle;">
						<?php if($row->station_active != 1) { ?>
							<a href="<?php echo site_url('station/delete')."/".$row->station_id; ?>" class="btn btn-danger btn-sm" title=<?php echo lang('admin_delete'); ?> onclick="return confirm('<?php echo lang('station_location_confirm_del_stationlocation'); ?> <?php echo $row->station_profile_name; ?> <?php echo lang('station_location_confirm_del_stationlocation_qso'); ?>');"><i class="fas fa-trash-alt"></i></a>
						<?php } ?>
					</td>
				</tr>

				<?php } ?>
			</tbody>
		</table>
		</div>
		<?php } ?>
  </div>
</div>


</div>
