<div class="container adif" id="adif_import">

    <div class="row">
        <div class="col-md-12">
            <h2><i class="fas fa-file-import me-2"></i><?php echo $page_title; ?></h2>
            <p class="text-muted mb-4">Import and export your amateur radio logs in ADIF format</p>
        </div>
    </div>

    <?php
    $showtab = '';
    if (isset($tab)) {
        $showtab = $tab;
    }
    ?>

    <style>
    /* Fix text-muted visibility in dark themes */
    .text-muted {
        color: var(--bs-secondary-color) !important;
        opacity: 0.8;
    }
    </style>

    <div class="card shadow-sm">
        <div class="card-header">
            <nav class="card-header-tabs">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link <?php if ($showtab == '' || $showtab == 'adif') echo 'active'; ?>" 
                       id="import-tab" data-bs-toggle="tab" href="#import" role="tab" aria-controls="import" 
                       aria-selected="<?php echo ($showtab == '' || $showtab == 'adif') ? 'true' : 'false'; ?>">
                        <i class="fas fa-upload me-1"></i><?php echo lang('adif_import') ?>
                    </a>
                    <a class="nav-item nav-link" id="export-tab" data-bs-toggle="tab" href="#export" role="tab" 
                       aria-controls="export" aria-selected="false">
                        <i class="fas fa-download me-1"></i><?php echo lang('adif_export') ?>
                    </a>
                    <a class="nav-item nav-link" id="lotw-tab" data-bs-toggle="tab" href="#lotw" role="tab" 
                       aria-controls="lotw" aria-selected="false">
                        <i class="fas fa-globe me-1"></i><?php echo lang('lotw_title') ?>
                    </a>
                    <a class="nav-item nav-link <?php if ($showtab == 'dcl') echo 'active'; ?>" 
                       id="dcl-tab" data-bs-toggle="tab" href="#dcl" role="tab" aria-controls="dcl" 
                       aria-selected="<?php echo ($showtab == 'dcl') ? 'true' : 'false'; ?>">
                        <i class="fas fa-flag me-1"></i><?php echo lang('darc_dcl') ?>
                    </a>
                </div>
            </nav>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane <?php if ($showtab == '' || $showtab == 'adif') {
                                            echo 'active show';
                                        } else {
                                            echo 'fade';
                                        } ?>" id="import" role="tabpanel" aria-labelledby="import-tab">

                    <?php if (isset($error) && ($showtab == '' || $showtab == 'adif')) { ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <!-- Important Notices -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong><?php echo lang('general_word_important') ?>:</strong> <?php echo lang('adif_alert_log_files_type') ?><br>
                                    <strong><?php echo lang('general_word_warning') ?>:</strong> <?php echo lang('gen_max_file_upload_size') ?><?php echo $max_upload; ?>B.
                                </div>
                            </div>
                        </div>
                    </div>

                    <form class="form" action="<?php echo site_url('adif/import'); ?>" method="post" enctype="multipart/form-data">
                        
                        <!-- Station Selection and File Upload -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-tower-broadcast me-2"></i>Station Selection</h5>
                                <div class="mb-3">
                                    <label for="station_profile" class="form-label">Select Station Location</label>
                                    <select name="station_profile" id="station_profile" class="form-select">
                                        <option value="0"><?php echo lang('adif_select_stationlocation') ?></option>
                                        <?php foreach ($station_profile->result() as $station) { ?>
                                            <option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) {
                                                                                                    echo " selected =\"selected\"";
                                                                                                } ?>>
                                                <?php echo lang('gen_hamradio_callsign') . ": " ?><?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fas fa-file me-2"></i>File Upload</h5>
                                <div class="mb-3">
                                    <label for="userfile" class="form-label">Choose ADIF File</label>
                                    <input class="form-control" type="file" name="userfile" id="userfile" accept=".adi,.ADI,.adif,.ADIF" />
                                    <div class="form-text">Supported formats: .adi, .adif</div>
                                </div>
                            </div>
                        </div>

                        <!-- Import Options -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-cogs me-2"></i>Import Options</h5>
                                
                                <!-- Basic Options -->
                                <div class="card mb-3">
                                    <div class="card-header" style="background-color: var(--bs-secondary-bg); border-bottom: 1px solid var(--bs-border-color);">
                                        <h6 class="mb-0">Basic Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="skipDuplicate" value="1" id="skipDuplicate">
                                                    <label class="form-check-label" for="skipDuplicate">
                                                        <strong><?php echo lang('adif_import_dup') ?></strong>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="dxccAdif" value="1" id="dxccAdif">
                                                    <label class="form-check-label" for="dxccAdif">
                                                        <strong><?php echo lang('adif_dxcc_from_adif') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('adif_dxcc_from_adif_hint') ?></small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="operatorName" value="1" id="operatorName">
                                                    <label class="form-check-label" for="operatorName">
                                                        <strong><?php echo lang('adif_always_use_login_call_as_op') ?></strong>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="skipStationCheck" value="1" id="skipStationCheck">
                                                    <label class="form-check-label" for="skipStationCheck">
                                                        <span class="badge bg-danger me-1"><?php echo lang('general_word_danger') ?></span>
                                                        <strong><?php echo lang('adif_ignore_station_call') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('adif_ignore_station_call_hint') ?></small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Logbook Upload Markers -->
                                <div class="card">
                                    <div class="card-header" style="background-color: var(--bs-secondary-bg); border-bottom: 1px solid var(--bs-border-color);">
                                        <h6 class="mb-0">Mark as Uploaded to Online Logbooks</h6>                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="markLotw" value="1" id="markLotwImport">
                                                    <label class="form-check-label" for="markLotwImport">
                                                        <strong><?php echo lang('adif_mark_imported_lotw') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('adif_hint_no_info_in_file') ?></small>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="markHrd" value="1" id="markHrdImport">
                                                    <label class="form-check-label" for="markHrdImport">
                                                        <strong><?php echo lang('adif_mark_imported_hrdlog') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('adif_hint_no_info_in_file') ?></small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="markQrz" value="1" id="markQrzImport">
                                                    <label class="form-check-label" for="markQrzImport">
                                                        <strong><?php echo lang('adif_mark_imported_qrz') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('adif_hint_no_info_in_file') ?></small>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="markClublog" value="1" id="markClublogImport">
                                                    <label class="form-check-label" for="markClublogImport">
                                                        <strong><?php echo lang('adif_mark_imported_clublog') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('adif_hint_no_info_in_file') ?></small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-upload me-2"></i><?php echo lang('adif_upload') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="export" role="tabpanel" aria-labelledby="export-tab">
                    
                    <!-- Custom Export Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="mb-3"><i class="fas fa-download me-2"></i><?php echo lang('adif_export_take_it_anywhere') ?></h5>
                            <p class="text-muted mb-4"><?php echo lang('adif_export_take_it_anywhere_hint') ?></p>
                            
                            <form class="form" action="<?php echo site_url('adif/export_custom'); ?>" method="post" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-header" style="background-color: var(--bs-secondary-bg); border-bottom: 1px solid var(--bs-border-color);">
                                        <h6 class="mb-0">Export Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="station_profile_export" class="form-label">Station Location</label>
                                                    <select name="station_profile" id="station_profile_export" class="form-select">
                                                        <option value="0"><?php echo lang('adif_select_stationlocation') ?></option>
                                                        <?php foreach ($station_profile->result() as $station) { ?>
                                                            <option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) {
                                                                                                                    echo " selected =\"selected\"";
                                                                                                                } ?>>
                                                                <?php echo lang('gen_hamradio_callsign') . ": " ?><?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="from_export" class="form-label"><?php echo lang('gen_from_date') ?></label>
                                                    <input name="from" id="from_export" type="date" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="to_export" class="form-label"><?php echo lang('gen_to_date') ?></label>
                                                    <input name="to" id="to_export" type="date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="mb-3">Export Options</h6>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="markLotw" value="1" id="markLotwExport">
                                                    <label class="form-check-label" for="markLotwExport">
                                                        <strong><?php echo lang('adif_mark_exported_lotw') ?></strong>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="exportLotw" value="1" id="exportLotw">
                                                    <label class="form-check-label" for="exportLotw">
                                                        <strong><?php echo lang('adif_mark_exported_no_lotw') ?></strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i><?php echo lang('adif_export_qso') ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Satellite Export Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header" style="background-color: var(--bs-secondary-bg); border-bottom: 1px solid var(--bs-border-color);">
                                    <h6 class="mb-0"><i class="fas fa-satellite me-2"></i><?php echo lang('adif_export_sat_only_qso') ?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-3">Export all satellite contacts from your logbook</p>
                                            <a href="<?php echo site_url('adif/exportsat'); ?>" title="Export All Satellite Contacts" target="_blank" class="btn btn-outline-primary">
                                                <i class="fas fa-satellite me-2"></i><?php echo lang('adif_export_sat_only_qso_all') ?>
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-3">Export satellite contacts confirmed on LoTW</p>
                                            <a href="<?php echo site_url('adif/exportsatlotw'); ?>" title="Export All Satellite QSOs Confirmed on LoTW" target="_blank" class="btn btn-outline-primary">
                                                <i class="fas fa-check-circle me-2"></i><?php echo lang('adif_export_sat_only_qso_lotw') ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="lotw" role="tabpanel" aria-labelledby="lotw-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3"><i class="fas fa-globe me-2"></i>LoTW Export Management</h5>
                            
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <strong><?php echo lang('general_word_warning') ?>:</strong> <?php echo lang('adif_lotw_export_if_selected') ?>
                                </div>
                            </div>
                            
                            <form class="form" action="<?php echo site_url('adif/mark_lotw'); ?>" method="post" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-header" style="background-color: var(--bs-secondary-bg); border-bottom: 1px solid var(--bs-border-color);">
                                        <h6 class="mb-0">Mark QSOs as Exported to LoTW</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="station_profile_lotw" class="form-label">Station Location</label>
                                                    <select name="station_profile" id="station_profile_lotw" class="form-select">
                                                        <option value="0"><?php echo lang('adif_select_stationlocation') ?></option>
                                                        <?php foreach ($station_profile->result() as $station) { ?>
                                                            <option value="<?php echo $station->station_id; ?>">
                                                                <?php echo lang('gen_hamradio_callsign') . ": " ?><?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="from_lotw" class="form-label"><?php echo lang('gen_from_date') ?></label>
                                                    <input name="from" id="from_lotw" type="date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="to_lotw" class="form-label"><?php echo lang('gen_to_date') ?></label>
                                                    <input name="to" id="to_lotw" type="date" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="button" class="btn btn-primary" id="markExportedToLotw">
                                            <i class="fas fa-check me-2"></i><?php echo lang('adif_mark_qso_as_exported_to_lotw') ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane <?php if ($showtab == 'dcl') {
                                            echo 'active show';
                                        } else {
                                            echo 'fade';
                                        } ?>" id="dcl" role="tabpanel" aria-labelledby="dcl-tab">
                    
                    <?php if (isset($error) && $showtab == 'dcl') { ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3"><i class="fas fa-flag me-2"></i>DARC DCL Import</h5>
                            
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <?php echo lang('adif_dcl_text_pre') ?> <a href="http://dcl.darc.de/dml/export_adif_form.php" target="_blank" class="alert-link"><?php echo lang('darc_dcl') ?></a> <?php echo lang('adif_dcl_text_post') ?>
                                </div>
                            </div>
                            
                            <form class="form" action="<?php echo site_url('adif/dcl'); ?>" method="post" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-header" style="background-color: var(--bs-secondary-bg); border-bottom: 1px solid var(--bs-border-color);">
                                        <h6 class="mb-0">DCL Import Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="userfile_dcl" class="form-label">Choose DCL ADIF File</label>
                                                    <input class="form-control" type="file" name="userfile" id="userfile_dcl" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="mb-3">Import Options</h6>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="onlyConfirmed" value="1" id="onlyConfirmed" checked>
                                                    <label class="form-check-label" for="onlyConfirmed">
                                                        <strong><?php echo lang('only_confirmed_qsos') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('only_confirmed_qsos_hint') ?></small>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="overwriteDok" value="1" id="overwriteDok">
                                                    <label class="form-check-label" for="overwriteDok">
                                                        <span class="badge bg-warning text-dark me-1"><?php echo lang('general_word_warning') ?></span>
                                                        <strong><?php echo lang('overwrite_by_dcl') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('overwrite_by_dcl_hint') ?></small>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="ignoreAmbiguous" value="1" id="ignoreAmbiguous" checked>
                                                    <label class="form-check-label" for="ignoreAmbiguous">
                                                        <strong><?php echo lang('ignore_ambiguous') ?></strong>
                                                        <br><small class="text-muted"><?php echo lang('ignore_ambiguous_hint') ?></small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload me-2"></i><?php echo lang('adif_upload') ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>