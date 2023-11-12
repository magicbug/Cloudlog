
<div class="container adif">

	<h2><?php echo $page_title; ?></h2>
   <?php
        $showtab = '';
        if(isset($tab)) {
           $showtab = $tab;
        }
    ?>

    <div class="card">
    <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs pull-right" role="tablist">
        <li class="nav-item">
            <a class="nav-link <?php if($showtab == '' || $showtab == 'adif') { echo 'active'; } ?>" id="import-tab" data-toggle="tab" href="#import" role="tab" aria-controls="import" aria-selected="<?php if ($showtab == '' || $showtab == 'adif') { echo 'true'; } else { echo 'false'; } ?>"><?php echo lang('adif_import')?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="export-tab" data-toggle="tab" href="#export" role="tab" aria-controls="export" aria-selected="false"><?php echo lang('adif_export')?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="lotw-tab" data-toggle="tab" href="#lotw" role="tab" aria-controls="lotw" aria-selected="false"><?php echo lang('lotw_title')?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($showtab == 'dcl') { echo 'active'; } ?>" id="dcl-tab" data-toggle="tab" href="#dcl" role="tab" aria-controls="dcl" aria-selected="<?php if ($showtab == 'dcl') { echo 'true'; } else { echo 'false'; } ?>"><?php echo lang('darc_dcl')?></a>
        </li>
    </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane <?php if($showtab == '' || $showtab == 'adif') { echo 'active'; } else { echo 'fade'; } ?>" id="import" role="tabpanel" aria-labelledby="home-tab">

                <?php if(isset($error) && ($showtab == '' || $showtab == 'adif')) { ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo $error; ?>
                    </div>
                <?php } ?>

                <p><span class="badge badge-warning"><?php echo lang('general_word_important')?></span> <?php echo lang('adif_alert_log_files_type')?></p>
                <p><span class="badge badge-warning"><?php echo lang('general_word_warning')?></span> <?php echo lang('gen_max_file_upload_size')?><?php echo $max_upload; ?>B.</p>

                <form class="form" action="<?php echo site_url('adif/import'); ?>" method="post" enctype="multipart/form-data">
                    <select name="station_profile" class="custom-select mb-2 mr-sm-2" style="width: 20%;">
                    <option value="0"><?php echo lang('adif_select_stationlocation')?></option>
                    <?php foreach ($station_profile->result() as $station) { ?>
                    <option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_callsign') . ": "?><?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
                    <?php } ?>
                    </select>
                  <label class="sr-only" for="inlineFormInputName2"><?php echo lang('adif_file_label')?></label>
                  <input class="file-input mb-2 mr-sm-2" type="file" name="userfile" size="20" />

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="skipDuplicate" value="1" id="skipDuplicate">
                                <label class="form-check-label" for="skipDuplicate"><?php echo lang('adif_import_dup')?></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="markLotw" value="1" id="markLotwImport">
                                <label class="form-check-label" for="markLotwImport"><?php echo lang('adif_mark_imported_lotw')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('adif_hint_no_info_in_file')?></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="markHrd" value="1" id="markHrdImport">
                                <label class="form-check-label" for="markHrdImport"><?php echo lang('adif_mark_imported_hrdlog')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('adif_hint_no_info_in_file')?></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="markQrz" value="1" id="markQrzImport">
                                <label class="form-check-label" for="markQrzImport"><?php echo lang('adif_mark_imported_qrz')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('adif_hint_no_info_in_file')?></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="markClublog" value="1" id="markClublogImport">
                                <label class="form-check-label" for="markClublogImport"><?php echo lang('adif_mark_imported_clublog')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('adif_hint_no_info_in_file')?></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="dxccAdif" value="1" id="dxccAdif">
                                <label class="form-check-label" for="dxccAdif"><?php echo lang('adif_dxcc_from_adif')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('adif_dxcc_from_adif_hint')?></div>
                        </div>
                    </div>

					<div class="form-group row">
						<div class="col-md-10">
							<div class="form-check-inline">
								<input class="form-check-input" type="checkbox" name="operatorName" value="1" id="operatorName">
								<label class="form-check-label" for="operatorName"><?php echo lang('adif_always_use_login_call_as_op')?></label>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-md-10">
							<div class="form-check-inline">
								<input class="form-check-input" type="checkbox" name="skipStationCheck" value="1" id="skipStationCheck">
								<label class="form-check-label" for="skipStationCheck"><span class="badge badge-warning"><?php echo lang('general_word_danger')?></span> <?php echo lang('adif_ignore_station_call')?></label>
							</div>
                            				<div class="small form-text text-muted"><?php echo lang('adif_ignore_station_call_hint')?></div>
						</div>
					</div>

                  <button type="submit" class="btn-sm btn-primary mb-2" value="Upload"><?php echo lang('adif_upload')?></button>
                </form>
                </div>

        <div class="tab-pane fade" id="export" role="tabpanel" aria-labelledby="home-tab">

		  <form class="form" action="<?php echo site_url('adif/export_custom'); ?>" method="post" enctype="multipart/form-data">
                <h5 class="card-title"><?php echo lang('adif_export_take_it_anywhere')?> </h5>
                <p class="card-text"><?php echo lang('adif_export_take_it_anywhere_hint')?> </p>
					  <select name="station_profile" class="custom-select mb-2 mr-sm-2" style="width: 20%;">
						  <option value="0"><?php echo lang('adif_select_stationlocation')?></option>
						  <?php foreach ($station_profile->result() as $station) { ?>
                       <option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_callsign') . ": "?><?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
						  <?php } ?>
					  </select>
                      <p class="card-text"><?php echo lang('gen_from_date') . ": "?></p>
                      <div class="row">
                          <div class="input-group date col-md-3" id="datetimepicker1" data-target-input="nearest">
                              <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                              <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>

                      <p class="card-text"><?php echo lang('gen_to_date') . ": "?></p>
                      <div class="row">
                          <div class="input-group date col-md-3" id="datetimepicker2" data-target-input="nearest">
                              <input name="to" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                              <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>
                        <br>
                      <div class="form-group row">
                          <div class="col-md-10">
                              <div class="form-check-inline">
                                  <input class="form-check-input" type="checkbox" name="markLotw" value="1" id="markLotwExport">
                                  <label class="form-check-label" for="markLotwExport"><?php echo lang('adif_mark_exported_lotw')?></label>
                              </div>
                          </div>
                      </div>
                      <div class="form-group row">
                          <div class="col-md-10">
                              <div class="form-check-inline">
                                  <input class="form-check-input" type="checkbox" name="exportLotw" value="1" id="exportLotw">
                                  <label class="form-check-label" for="exportLotw"><?php echo lang('adif_mark_exported_no_lotw')?></label>
                              </div>
                          </div>
                      </div>

                <button type="submit" class="btn-sm btn-primary" value="Export"><?php echo lang('adif_export_qso')?></button>
		  </form>

                <br><br>

                <h5><?php echo lang('adif_export_sat_only_qso')?></h5>
                <p><a href="<?php echo site_url('adif/exportsat'); ?>" title="Export All Satellite Contacts" target="_blank" class="btn-sm btn-primary"><?php echo lang('adif_export_sat_only_qso_all')?></a></p>

                <p><a href="<?php echo site_url('adif/exportsatlotw'); ?>" title="Export All Satellite QSOs Confirmed on LoTW" target="_blank" class="btn-sm btn-primary"><?php echo lang('adif_export_sat_only_qso_lotw')?></a></p>
                </div>


        <div class="tab-pane fade" id="lotw" role="tabpanel" aria-labelledby="home-tab">
            <form class="form" action="<?php echo site_url('adif/mark_lotw'); ?>" method="post" enctype="multipart/form-data">
				<select name="station_profile" class="custom-select mb-2 mr-sm-2" style="width: 20%;">
					<option value="0"><?php echo lang('adif_select_stationlocation')?></option>
					<?php foreach ($station_profile->result() as $station) { ?>
						<option value="<?php echo $station->station_id; ?>"><?php echo lang('gen_hamradio_callsign') . ": "?><?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
					<?php } ?>
				</select>
				<p><span class="badge badge-warning"><?php echo lang('general_word_warning')?></span> <?php echo lang('adif_lotw_export_if_selected')?></p>
                <p class="card-text"><?php echo lang('gen_from_date') . ": "?></p>
                <div class="row">
                    <div class="input-group date col-md-3" id="datetimepicker3" data-target-input="nearest">
                        <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                        <div class="input-group-append"  data-target="#datetimepicker3" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <p class="card-text"><?php echo lang('gen_to_date') . ": "?></p>
                <div class="row">
                    <div class="input-group date col-md-3" id="datetimepicker4" data-target-input="nearest">
                        <input name="to" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                        <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn-sm btn-primary" value="Export"><?php echo lang('adif_mark_qso_as_exported_to_lotw')?></button>
            </form>
            </div>
        <div class="tab-pane <?php if ($showtab == 'dcl') { echo 'active'; } else { echo 'fade'; } ?>" id="dcl" role="tabpanel" aria-labelledby="home-tab">
                <?php if(isset($error) && $showtab == 'dcl') { ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo $error; ?>
                    </div>
                <?php } ?>

                <p class="card-text"><?php echo lang('adif_dcl_text_pre')?> <a href="http://dcl.darc.de/dml/export_adif_form.php" target="_blank"><?php echo lang('darc_dcl')?></a> <?php echo lang('adif_dcl_text_post')?></p>
                <form class="form" action="<?php echo site_url('adif/dcl'); ?>" method="post" enctype="multipart/form-data">

                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="onlyConfirmed" value="1" id="onlyConfirmed" checked>
                                <label class="form-check-label" for="onlyConfirmed"><?php echo lang('only_confirmed_qsos')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('only_confirmed_qsos_hint')?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="overwriteDok" value="1" id="overwriteDok">
                                <label class="form-check-label" for="overwriteDok"><span class="badge badge-warning"><?php echo lang('general_word_warning')?></span> <?php echo lang('overwrite_by_dcl')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('overwrite_by_dcl_hint')?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="ignoreAmbiguous" value="1" id="ignoreAmbiguous" checked>
                                <label class="form-check-label" for="ignoreAmbiguous"><?php echo lang('ignore_ambiguous')?></label>
                            </div>
                            <div class="small form-text text-muted"><?php echo lang('ignore_ambiguous_hint')?></div>
                        </div>
                    </div>
                  <input class="file-input mb-2 mr-sm-2" type="file" name="userfile" size="20" />
                  <button type="submit" class="btn-sm btn-primary mb-2" value="Upload"><?php echo lang('adif_upload')?></button>
                </form>
        </div>
    </div>
    </div>
    </div>
