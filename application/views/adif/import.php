
<div class="container adif">

	<h2><?php echo $page_title; ?></h2>

	<div class="card">
	  <div class="card-header">
	    ADIF Import
	  </div>

	  <div class="card-body">
	    <?php if(isset($error)) { ?>
			<div class="alert alert-danger" role="alert">
			  <?php echo $error; ?>
			</div>
	    <?php } ?>

	    <p><span class="label important">Important</span> Log files must have the file type .adi</p>

		<form class="form" action="<?php echo site_url('adif/import'); ?>" method="post" enctype="multipart/form-data">
		    <select name="station_profile" class="custom-select mb-2 mr-sm-2" style="width: 20%;">
			<option value="0">Select Station Profile</option>
			<?php foreach ($station_profile->result() as $station) { ?>
			<option value="<?php echo $station->station_id; ?>">Callsign: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
			<?php } ?>
		    </select>
		  <label class="sr-only" for="inlineFormInputName2">ADIF file</label>
		  <input class="file-input mb-2 mr-sm-2" type="file" name="userfile" size="20" />

            <div class="form-group row">
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="skipDuplicate" value="1" id="skipDuplicate">
                        <label class="form-check-label" for="skipDuplicate">Skip duplicate QSO check</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="markLotw" value="1" id="markLotwImport">
                        <label class="form-check-label" for="markLotwImport">Mark imported QSOs as uploaded to LoTW (use if ADIF does not contain this information)</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="dxccAdif" value="1" id="dxccAdif">
                        <label class="form-check-label" for="dxccAdif">Use DXCC set in ADIF (Cloudlog tries to determine DXCC if not checked)</label>
                    </div>
                </div>
            </div>

		  <button type="submit" class="btn btn-primary mb-2" value="Upload">Upload</button>
		</form>

		<p><span class="badge badge-warning">Warning</span> Maximum file upload size is <?php echo $max_upload; ?>B.</p>

	  </div>
	</div>

<br>

<div class="card">
  <div class="card-header">
    ADIF Export
  </div>

	<div class="alert alert-warning" role="alert">
 		Exporting QSOs from the Active Station Profile Name <span class="badge badge-danger"><?php echo $active_station_info->station_profile_name;?></span> with the station callsign <span class="badge badge-danger"><?php echo $active_station_info->station_callsign;?></span>
	</div>

  <div class="card-body">
      <form class="form" action="<?php echo site_url('adif/export_custom'); ?>" method="post" enctype="multipart/form-data">
    <h5 class="card-title">Take your logbook file anywhere!</h5>
    <p class="card-text">Exporting ADIFs allows you to import contacts into third party applications like LoTW, Awards or just for keeping a backup.</p>

          <p class="card-text">From date:</p>
          <div class="row">
              <div class="input-group date col-md-3" id="datetimepicker1" data-target-input="nearest">
                  <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                  <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
              </div>
          </div>

          <p class="card-text">To date:</p>
          <div class="row">
              <div class="input-group date col-md-3" id="datetimepicker2" data-target-input="nearest">
                  <input name="to" "totype="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
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
                      <label class="form-check-label" for="markLotwExport">Mark exported QSOs as uploaded to LoTW</label>
                  </div>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-md-10">
                  <div class="form-check-inline">
                      <input class="form-check-input" type="checkbox" name="exportLotw" value="1" id="exportLotw">
                      <label class="form-check-label" for="exportLotw">Export QSOs not uploaded to LoTW</label>
                  </div>
              </div>
          </div>

    <button type="submit" class="btn btn-outline-secondary btn-sm" value="Export">Export QSOs</button>
      </form>

    <br><br>
      <h6>Logbook of The World</h6>
      <p>If no date are chosen, that means all QSOs will be marked!</p>
      <form class="form" action="<?php echo site_url('adif/mark_lotw'); ?>" method="post" enctype="multipart/form-data">
          <p class="card-text">From date:</p>
          <div class="row">
              <div class="input-group date col-md-3" id="datetimepicker3" data-target-input="nearest">
                  <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                  <div class="input-group-append"  data-target="#datetimepicker3" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                  </div>
              </div>
          </div>
      <p class="card-text">To date:</p>
      <div class="row">
          <div class="input-group date col-md-3" id="datetimepicker4" data-target-input="nearest">
              <input name="to" "totype="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
              <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
          </div>
      </div>
      <br>
      <button type="submit" class="btn btn-outline-secondary btn-sm" value="Export">Mark QSOs as exported to LoTW</button>
      </form>
      <br><br>

    <h6>Export Satellite Only QSOs</h6>
    <a href="<?php echo site_url('adif/exportsat'); ?>" title="Export All Satellite Contacts" target="_blank" class="btn btn-outline-secondary btn-sm">Export All Satellite QSOs</a>

    <a href="<?php echo site_url('adif/exportsatlotw'); ?>" title="Export All Satellite QSOS Confirmed on LoTW" target="_blank" class="btn btn-outline-secondary btn-sm">Export All Satellite QSOs Confirmed on LoTW</a>
  </div>
</div>

</div>


