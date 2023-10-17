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
    <?php echo lang('station_logbooks_description_header')?>
  </div>
  <div class="card-body">
    <p class="card-text"><?php echo lang('station_logbooks_description_text')?></p>
  </div>
</div>

<div class="card" style="margin-top: 20px;">
  <div class="card-header">
  <?php echo lang('station_logbooks')?> <a class="btn btn-primary float-right" href="<?php echo site_url('logbooks/create'); ?>"><i class="fas fa-plus"></i> <?php echo lang('station_logbooks_create')?></a>
  </div>
  <div class="card-body">
  <div id="station_logbooks">
		<?php if ($my_logbooks->num_rows() > 0) { ?>

		<div class="table-responsive">
		<table id="station_logbooks_table" class="table table-sm table-striped">
			<thead>
				<tr>
					<th scope="col"><?php echo lang('general_word_name')?></th>
					<th scope="col"><?php echo lang('station_logbooks_status')?></th>
					<th scope="col"><?php echo lang('admin_edit')?></th>
					<th scope="col"><?php echo lang('admin_delete')?></th>
					<th scope="col"><?php echo lang('station_logbooks_link')?></th>
					<th scope="col"><?php echo lang('station_logbooks_public_search')?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($my_logbooks->result() as $row) { ?>
				<tr>
					<td>
						<?php echo $row->logbook_name;?><br>
					</td>

					<td>
						<?php if($this->session->userdata('active_station_logbook') != $row->logbook_id) { ?>
						<a href="<?php echo site_url('logbooks/set_active')."/".$row->logbook_id; ?>" class="btn btn-outline-primary btn-sm"><?php echo lang('station_logbooks_set_active')?></a>
						<?php } else {
							echo "<span class='badge badge-success'>" . lang('station_logbooks_active_logbook') . "</span>";
							}?>
					</td>
					<td>
						<a href="<?php echo site_url('logbooks/edit')."/".$row->logbook_id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit" title="<?php echo lang('station_logbooks_edit_logbook') . ': ' . $row->logbook_name;?>"></i> </a>
					</td>
					<td>
						<?php if($this->session->userdata('active_station_logbook') != $row->logbook_id) { ?>
						<a href="<?php echo site_url('Logbooks/delete')."/".$row->logbook_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo lang('station_logbooks_confirm_delete') . $row->logbook_name; ?>');"><i class="fas fa-trash-alt"></i></a>
						<?php } ?>
					</td>
					<td>
						<?php if($row->public_slug != '') { ?>
							<a target="_blank" href="<?php echo site_url('visitor')."/".$row->public_slug; ?>" class="btn btn-outline-primary btn-sm" ><i class="fas fa-globe" title="<?php echo lang('station_logbooks_view_public') . $row->logbook_name;?>"></i> </a>
							<?php } ?>
					</td>
					<td>
							<?php if ($row->public_search == 1) {
							echo "<span class='badge badge-success'>" . lang('general_word_enabled') . "</span>";
							} else {
							echo "<span class='badge badge-dark'>" . lang('general_word_disabled') . "</span>";
							} ?>
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


</div>
