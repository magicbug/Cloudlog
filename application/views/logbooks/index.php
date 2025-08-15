<div class="container logbooks-management">

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
                <h2><i class="fas fa-book me-2"></i><?php echo $page_title; ?></h2>
                <p class="text-muted mb-0">Organize your QSOs into separate logbooks</p>
            </div>
            <a href="<?php echo site_url('logbooks/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i><?php echo lang('station_logbooks_create')?></a>
        </div>
    </div>
</div>

<!-- Information Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i><?php echo lang('station_logbooks_description_header')?></h5>
            </div>
            <div class="card-body">
                <p class="mb-0"><?php echo lang('station_logbooks_description_text')?></p>
            </div>
        </div>
    </div>
</div>

<!-- Station Logbooks Table -->
<?php if ($my_logbooks->num_rows() > 0) { ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i><?php echo lang('station_logbooks')?> (<?php echo $my_logbooks->num_rows(); ?>)</h5>
                    <small class="text-muted">Click to set a logbook as active</small>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="station_logbooks_table" class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col"><i class="fas fa-tag me-1"></i><?php echo lang('general_word_name')?></th>
                                <th scope="col" class="text-center"><i class="fas fa-power-off me-1"></i><?php echo lang('station_logbooks_status')?></th>
                                <th scope="col" class="text-center"><i class="fas fa-globe me-1"></i><?php echo lang('station_logbooks_link')?></th>
                                <th scope="col" class="text-center"><i class="fas fa-search me-1"></i><?php echo lang('station_logbooks_public_search')?></th>
                                <th scope="col" class="text-center"><i class="fas fa-tools me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($my_logbooks->result() as $row) { ?>
                            <tr <?php if($this->session->userdata('active_station_logbook') == $row->logbook_id) { echo 'class="table-success"'; } ?>>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong><?php echo $row->logbook_name;?></strong>
                                            <?php if($this->session->userdata('active_station_logbook') == $row->logbook_id) { ?>
                                                <span class="badge bg-success ms-2">
                                                    <i class="fas fa-check me-1"></i><?php echo lang('station_logbooks_active_logbook'); ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <?php if($this->session->userdata('active_station_logbook') != $row->logbook_id) { ?>
                                        <a href="<?php echo site_url('logbooks/set_active')."/".$row->logbook_id; ?>" 
                                           class="btn btn-outline-success btn-sm"
                                           title="<?php echo lang('station_logbooks_set_active')?>">
                                            <i class="fas fa-power-off me-1"></i><?php echo lang('station_logbooks_set_active')?>
                                        </a>
                                    <?php } else { ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i><?php echo lang('station_logbooks_active_logbook'); ?>
                                        </span>
                                    <?php } ?>
                                </td>
                                <td class="align-middle text-center">
                                    <?php if($row->public_slug != '') { ?>
                                        <a target="_blank" href="<?php echo site_url('visitor')."/".$row->public_slug; ?>" 
                                           class="btn btn-info btn-sm"
                                           title="<?php echo lang('station_logbooks_view_public') . $row->logbook_name;?>">
                                            <i class="fas fa-globe me-1"></i>View Public
                                        </a>
                                    <?php } else { ?>
                                        <span class="text-muted">-</span>
                                    <?php } ?>
                                </td>
                                <td class="align-middle text-center">
                                    <?php if ($row->public_search == 1) { ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i><?php echo lang('general_word_enabled'); ?>
                                        </span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times me-1"></i><?php echo lang('general_word_disabled'); ?>
                                        </span>
                                    <?php } ?>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="<?php echo site_url('logbooks/edit')."/".$row->logbook_id; ?>" 
                                           class="btn btn-primary btn-sm"
                                           title="<?php echo lang('station_logbooks_edit_logbook') . ': ' . $row->logbook_name;?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($this->session->userdata('active_station_logbook') != $row->logbook_id) { ?>
                                            <a href="<?php echo site_url('Logbooks/delete')."/".$row->logbook_id; ?>" 
                                               class="btn btn-danger btn-sm" 
                                               title="<?php echo lang('admin_delete')?>"
                                               onclick="return confirm('<?php echo lang('station_logbooks_confirm_delete') . $row->logbook_name; ?>');">
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
                <i class="fas fa-book text-muted mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-muted mb-3">No Logbooks</h5>
                <p class="text-muted mb-4">You haven't created any logbooks yet. Create your first logbook to organize your QSOs.</p>
                <a href="<?php echo site_url('logbooks/create'); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i><?php echo lang('station_logbooks_create')?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php } ?>

</div>
