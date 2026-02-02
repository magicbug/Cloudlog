<div class="container">
    <h2><?php echo $page_title; ?></h2>

    <!-- Filter Card -->
    <div class="card mt-4 mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fa fa-filter"></i> <?php echo lang('awards_sig_filter_detail'); ?>
            </h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo site_url('/awards/sig_details?type=' . urlencode($type)); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="bandFilter" class="form-label"><?php echo lang('awards_sig_filter_band'); ?></label>
                        <select class="form-select" id="bandFilter" name="band">
                            <option value="all"><?php echo lang('awards_sig_filter_all'); ?></option>
                            <?php if ($bands) {
                                foreach ($bands as $band) {
                                    $selected = (!empty($filters['band']) && $filters['band'] == $band) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($band) . "\" $selected>" . htmlspecialchars($band) . "</option>";
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="modeFilter" class="form-label"><?php echo lang('awards_sig_filter_mode'); ?></label>
                        <select class="form-select" id="modeFilter" name="mode">
                            <option value="all"><?php echo lang('awards_sig_filter_all'); ?></option>
                            <?php if ($modes) {
                                foreach ($modes as $mode) {
                                    $selected = (!empty($filters['mode']) && $filters['mode'] == $mode) ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($mode) . "\" $selected>" . htmlspecialchars($mode) . "</option>";
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="confirmedOnlyFilter" name="confirmed_only" value="1" 
                                <?php echo (!empty($filters['confirmed_only']) && ($filters['confirmed_only'] === true || $filters['confirmed_only'] === 'true' || $filters['confirmed_only'] === 1 || $filters['confirmed_only'] === '1')) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="confirmedOnlyFilter">
                                <?php echo lang('awards_sig_filter_confirmed_only'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-check"></i> <?php echo lang('awards_sig_action_apply'); ?>
                        </button>
                        <a href="<?php echo site_url('/awards/sig_details?type=' . urlencode($type)); ?>" class="btn btn-secondary">
                            <i class="fa fa-redo"></i> <?php echo lang('awards_sig_action_reset'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted"><?php echo lang('awards_sig_stat_worked'); ?></h6>
                    <h3 class="card-text text-primary"><?php echo $worked_refs; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted"><?php echo lang('awards_sig_stat_confirmed'); ?></h6>
                    <h3 class="card-text text-success"><?php echo $confirmed_refs; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted"><?php echo lang('awards_sig_stat_qsos'); ?></h6>
                    <h3 class="card-text text-info"><?php echo ($sig_all) ? $sig_all->num_rows() : 0; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php if ($filter_summary) { ?>
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> <?php echo lang('awards_sig_filters_active'); ?>: <strong><?php echo $filter_summary; ?></strong>
        </div>
    <?php } ?>

    <?php if ($sig_all && $sig_all->num_rows() > 0) { ?>

	<table class="table table-bordered table-hover table-striped">
            <thead class="table-light">
        <tr>
            <th><?php echo lang('awards_sig_table_ref'); ?></th>
            <th><?php echo lang('awards_sig_table_datetime'); ?></th>
            <th><?php echo lang('awards_sig_table_call'); ?></th>
            <th><?php echo lang('awards_sig_table_mode'); ?></th>
            <th><?php echo lang('awards_sig_table_band'); ?></th>
            <th><?php echo lang('awards_sig_table_rst_sent'); ?></th>
            <th><?php echo lang('awards_sig_table_rst_rcvd'); ?></th>
            <th><?php echo lang('awards_sig_table_qsl_status'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($sig_all->result() as $row) { 
            $is_confirmed = ($row->COL_QSL_RCVD == 'Y' || $row->COL_EQSL_QSL_RCVD == 'Y' || $row->COL_LOTW_QSL_RCVD == 'Y');
            $row_class = $is_confirmed ? '' : 'table-light';
        ?>
            <tr class="<?php echo $row_class; ?>">
                <td>
                    <?php echo htmlspecialchars($row->COL_SIG_INFO); ?>
                </td>
                <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?> - <?php echo date('H:i', $timestamp); ?></td>
                <td><?php echo htmlspecialchars($row->COL_CALL); ?></td>
                <td><?php echo htmlspecialchars($row->COL_MODE); ?></td>
                <td><?php echo htmlspecialchars($row->COL_BAND); ?></td>
                <td><?php echo htmlspecialchars($row->COL_RST_SENT); ?></td>
                <td><?php echo htmlspecialchars($row->COL_RST_RCVD); ?></td>
                <td>
                    <span class="badge <?php echo $is_confirmed ? 'bg-success' : 'bg-warning text-dark'; ?>">
                        <?php 
                            if ($row->COL_LOTW_QSL_RCVD == 'Y') {
                                echo '✓ LoTW';
                            } elseif ($row->COL_EQSL_QSL_RCVD == 'Y') {
                                echo '✓ eQSL';
                            } elseif ($row->COL_QSL_RCVD == 'Y') {
                                echo '✓ QSL';
                            } else {
                                echo lang('awards_sig_qsl_unconfirmed');
                            }
                        ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <p class="mt-4">
        <a href="<?php echo site_url('/awards/sigexportadif/' . urlencode($type)); ?>" title="Export QSOs to ADIF" target="_blank" class="btn btn-primary">
            <i class="fa fa-download"></i> <?php echo lang('awards_sig_action_export'); ?>
        </a>
        <a href="<?php echo site_url('/awards/sigexportcsv/' . urlencode($type)); ?>" title="Export QSOs to CSV" class="btn btn-secondary">
            <i class="fa fa-file-csv"></i> Export CSV
        </a>
    </p>
    <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-exclamation-triangle"></i> <?php echo lang('awards_sig_no_qsos'); ?>
        </div>
    <?php } ?>
</div>
