<div class="container station-management">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('message'); ?>
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-tower-broadcast me-2"></i><?php echo $page_title; ?></h2>
                <p class="text-muted mb-0">Manage your station locations.</p>
            </div>
            <a href="<?php echo site_url('station/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i><?php echo lang('station_location_create'); ?>
            </a>
        </div>
    </div>
</div>

<!-- Information Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>About Station Locations</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Location Management</h6>
                                <p class="mb-0 text-muted small"><?php echo lang('station_location_header_ln1'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-radio text-primary me-2 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Multiple Locations</h6>
                                <p class="mb-0 text-muted small"><?php echo lang('station_location_header_ln2'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="fas fa-cog text-primary me-2 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Active Location</h6>
                                <p class="mb-0 text-muted small"><?php echo lang('station_location_header_ln3'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warnings Section -->
<?php if($current_active == 0 || (($is_there_qsos_with_no_station_id >= 1) && ($is_admin))) { ?>
<div class="row mb-4">
    <div class="col-md-12">
        <?php if($current_active == 0) { ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div><?php echo lang('station_location_warning'); ?></div>
        </div>
        <?php } ?>

        <?php if (($is_there_qsos_with_no_station_id >= 1) && ($is_admin)) { ?>
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong><?php echo lang('general_word_warning'); ?>:</strong> <?php echo lang('station_location_warning_reassign'); ?><br>
                <div class="mt-2">
                    <?php echo lang('station_location_reassign_at'); ?> 
                    <a href="<?php echo site_url('maintenance/'); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-sync me-1"></i><?php echo lang('account_word_admin') . "/" . lang('general_word_maintenance'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>

<!-- Station Locations Table -->
<?php if ($stations->num_rows() > 0) { ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Station Locations (<?php echo $stations->num_rows(); ?>)</h5>
                    <small class="text-muted">Click on a station name to set it as active</small>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="station_locations_table" class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><i class="fas fa-tag me-1"></i><?php echo lang('station_location_name'); ?></th>
                                <th scope="col"><i class="fas fa-microphone me-1"></i><?php echo lang('station_location_callsign'); ?></th>
                                <th scope="col"><i class="fas fa-flag me-1"></i><?php echo lang('general_word_country'); ?></th>
                                <th scope="col"><i class="fas fa-th me-1"></i><?php echo lang('gen_hamradio_gridsquare'); ?></th>
                                <th scope="col" class="text-center"><i class="fas fa-power-off me-1"></i>Status</th>
                                <th scope="col" class="text-center"><i class="fas fa-tools me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stations->result() as $row) { ?>
                            <tr <?php if($row->station_active == 1) { echo 'class="table-success"'; } ?>>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong><?php echo $row->station_profile_name;?></strong>
                                            <?php if($row->station_active == 1) { ?>
                                                <span class="badge bg-success ms-2">
                                                    <i class="fas fa-check me-1"></i><?php echo lang('station_location_active'); ?>
                                                </span>
                                            <?php } ?>
                                            <br>
                                            <small class="text-muted">
                                                ID: <?php echo $row->station_id;?> • 
                                                <?php echo $row->qso_total;?> <?php echo lang('gen_hamradio_qso'); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <strong><?php echo $row->station_callsign;?></strong>
                                </td>
                                <td class="align-middle">
                                    <?php echo $row->station_country == '' ? '<span class="text-muted">- NONE -</span>' : $row->station_country; ?>
                                    <?php if ($row->dxcc_end != NULL) { echo '<br><span class="badge bg-danger">'.lang('gen_hamradio_deleted_dxcc').'</span>'; } ?>
                                </td>
                                <td class="align-middle">
                                    <?php echo $row->station_gridsquare == '' ? '<span class="text-muted">-</span>' : $row->station_gridsquare;?>
                                </td>
                                <td class="align-middle text-center" data-order="<?php echo $row->station_id;?>">
                                    <?php if($row->station_active != 1) { ?>
                                        <a href="<?php echo site_url('station/set_active/').$current_active."/".$row->station_id; ?>" 
                                           class="btn btn-outline-success btn-sm" 
                                           onclick="return confirm('<?php echo lang('station_location_confirm_active'); ?> <?php echo $row->station_profile_name; ?>');",
                                           title="<?php echo lang('station_location_set_active'); ?>">
                                            <i class="fas fa-power-off me-1"></i><?php echo lang('station_location_set_active'); ?>
                                        </a>
                                    <?php } else { ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i><?php echo lang('station_location_active'); ?>
                                        </span>
                                    <?php } ?>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <?php if($row->user_id == "") { ?>
                                            <a href="<?php echo site_url('station/claim_user')."/".$row->station_id; ?>" 
                                               class="btn btn-info btn-sm" 
                                               title="<?php echo lang('station_location_claim_ownership'); ?>">
                                                <i class="fas fa-user-plus"></i>
                                            </a>
                                        <?php } ?>
                                        <a href="<?php echo site_url('station/edit')."/".$row->station_id; ?>" 
                                           title="<?php echo lang('admin_edit'); ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('station/copy')."/".$row->station_id; ?>" 
                                           title="<?php echo lang('admin_copy'); ?>" 
                                           class="btn btn-secondary btn-sm">
                                            <i class="fas fa-copy"></i>
                                        </a>
                                        <a href="<?php echo site_url('station/deletelog')."/".$row->station_id; ?>" 
                                           class="btn btn-warning btn-sm" 
                                           title="<?php echo lang('station_location_emptylog'); ?>" 
                                           onclick="return confirm('<?php echo lang('station_location_confirm_del_qso'); ?>');">
                                            <i class="fas fa-broom"></i>
                                        </a>
                                        <?php if($row->station_active != 1) { ?>
                                            <a href="<?php echo site_url('station/delete')."/".$row->station_id; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               title="<?php echo lang('admin_delete'); ?>" 
                                               onclick="return confirm('<?php echo lang('station_location_confirm_del_stationlocation'); ?> <?php echo $row->station_profile_name; ?> <?php echo lang('station_location_confirm_del_stationlocation_qso'); ?>');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<!-- Empty State -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-tower-broadcast text-muted mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-muted mb-3">No Station Locations</h5>
                <p class="text-muted mb-4">You haven't created any station locations yet. Create your first station location to get started.</p>
                <a href="<?php echo site_url('station/create'); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i><?php echo lang('station_location_create'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php } ?>

</div>
