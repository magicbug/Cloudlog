<div class="container" style="padding-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Most Worked Callsigns</h2>
                    <p class="card-text">Callsigns worked multiple times from your active logbook, showing contact frequency and other details.</p>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="post" id="mostworked_filter_form">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="band" class="form-label">Band:</label>
                                <select class="form-select form-select-sm" name="band" id="band">
                                    <option value="all" <?php echo ($filters['band'] == 'all') ? 'selected' : ''; ?>>All Bands</option>
                                    <option value="SAT" <?php echo ($filters['band'] == 'SAT') ? 'selected' : ''; ?>>Satellites</option>
                                    <?php foreach($bands as $band) { ?>
                                        <option value="<?php echo $band; ?>" <?php echo ($filters['band'] == $band) ? 'selected' : ''; ?>><?php echo $band; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="mode" class="form-label">Mode:</label>
                                <select class="form-select form-select-sm" name="mode" id="mode">
                                    <option value="all" <?php echo ($filters['mode'] == 'all') ? 'selected' : ''; ?>>All Modes</option>
                                    <?php foreach($modes as $mode) { ?>
                                        <option value="<?php echo $mode->mode; ?>" <?php echo ($filters['mode'] == $mode->mode) ? 'selected' : ''; ?>><?php echo $mode->mode; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="satellite" class="form-label">Satellite:</label>
                                <select class="form-select form-select-sm" name="satellite" id="satellite">
                                    <option value="all" <?php echo ($filters['satellite'] == 'all') ? 'selected' : ''; ?>>All Satellites</option>
                                    <?php foreach($satellites as $sat) { ?>
                                        <option value="<?php echo $sat->satellite; ?>" <?php echo ($filters['satellite'] == $sat->satellite) ? 'selected' : ''; ?>><?php echo $sat->satellite; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="fromdate" class="form-label">From Date:</label>
                                <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate" value="<?php echo $filters['fromdate']; ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="todate" class="form-label">To Date:</label>
                                <input type="date" class="form-control form-control-sm" name="todate" id="todate" value="<?php echo $filters['todate']; ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="min_qsos" class="form-label">Min QSOs:</label>
                                <select class="form-select form-select-sm" name="min_qsos" id="min_qsos">
                                    <option value="2" <?php echo ($filters['min_qsos'] == 2) ? 'selected' : ''; ?>>2+</option>
                                    <option value="3" <?php echo ($filters['min_qsos'] == 3) ? 'selected' : ''; ?>>3+</option>
                                    <option value="5" <?php echo ($filters['min_qsos'] == 5) ? 'selected' : ''; ?>>5+</option>
                                    <option value="10" <?php echo ($filters['min_qsos'] == 10) ? 'selected' : ''; ?>>10+</option>
                                    <option value="20" <?php echo ($filters['min_qsos'] == 20) ? 'selected' : ''; ?>>20+</option>
                                    <option value="50" <?php echo ($filters['min_qsos'] == 50) ? 'selected' : ''; ?>>50+</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                                <button type="button" class="btn btn-secondary btn-sm" id="clear_filters">Clear Filters</button>
                            </div>
                        </div>
                    </form>

                    <?php if (empty($mostworked_callsigns)) { ?>
                        <div class="alert alert-warning" role="alert">
                            <strong>No QSOs found!</strong> Make sure you have an active logbook selected and QSOs logged, or try adjusting your filters.
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-info" role="alert">
                            <strong><?php echo count($mostworked_callsigns); ?> callsigns</strong> worked <?php echo $filters['min_qsos']; ?>+ times with current filters.
                        </div>
                        <div class="table-responsive">
                            <table id="mostworked_table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">Rank</th>
                                        <th style="width: 150px;">Callsign</th>
                                        <th class="text-center" style="width: 100px;">Contacts</th>
                                        <th class="text-center" style="width: 120px;">First QSO</th>
                                        <th class="text-center" style="width: 120px;">Last QSO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $rank = 1; ?>
                                    <?php 
                                    // Get Date format
                                    if($this->session->userdata('user_date_format')) {
                                        // If Logged in and session exists
                                        $custom_date_format = $this->session->userdata('user_date_format');
                                    } else {
                                        // Get Default date format from /config/cloudlog.php
                                        $custom_date_format = $this->config->item('qso_date_format');
                                    }
                                    
                                    foreach ($mostworked_callsigns as $row) { ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-secondary"><?php echo $rank++; ?></span>
                                            </td>
                                            <td class="align-middle">
                                                <strong class="text-primary fs-6"><?php echo str_replace("0","&Oslash;",strtoupper($row->callsign)); ?></strong>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-primary fs-6"><?php echo $row->contact_count; ?></span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <small class="text-muted">
                                                    <?php 
                                                    $timestamp = strtotime($row->first_qso); 
                                                    echo date($custom_date_format, $timestamp); 
                                                    ?>
                                                </small>
                                            </td>
                                            <td class="text-center align-middle">
                                                <small class="text-muted">
                                                    <?php 
                                                    $timestamp = strtotime($row->last_qso); 
                                                    echo date($custom_date_format, $timestamp); 
                                                    ?>
                                                </small>
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
    </div>
</div>
