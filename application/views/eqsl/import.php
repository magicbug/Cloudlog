<?php
$page_title = $page_title ?? '';
$eqsl_mapping_mode = $eqsl_mapping_mode ?? false;
$eqsl_mappings_count = $eqsl_mappings_count ?? 0;
$mapped_station_ids = $mapped_station_ids ?? array();
?>
<div class="container eqsl">
<h2><?php echo $page_title; ?></h2>
<div class="card">
  <div class="card-header">
  	<div class="card-title">eQSL Import</div>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/Export');?>">Upload QSOs</a>
      </li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo site_url('eqsl/mappings');?>">Mappings</a>
			</li>

	  <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/tools');?>">Tools</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/download');?>">Download eQSL cards</a>
      </li>
    </ul>
  </div>
  <div class="card-body">
		<?php $this->load->view('layout/messages'); ?>

		<?php if (!empty($eqsl_mapping_mode)) { ?>
			<div class="alert alert-info" role="alert">
				Using mapping mode with <?php echo (int) $eqsl_mappings_count; ?> active mapping(s). eQSL import fetches password and QTH nickname from your mappings.
			</div>
		<?php } else { ?>
			<div class="alert alert-warning" role="alert">
				Using legacy fallback mode. Configure mappings to manage multiple eQSL identities and nicknames explicitly.
			</div>
		<?php } ?>

		<?php echo form_open_multipart('eqsl/import');?>

			<div class="form-check">
			  <input class="form-check-input" type="radio" name="eqslimport" id="upload" value="upload" checked /> 
			  <label class="form-check-label" for="exampleRadios1">
			    Import from file...
			  </label>
 			  <br>
			  <p>Upload the Exported ADIF file from eQSL from the <a href="https://eqsl.cc/qslcard/DownloadInBox.cfm" target="_blank">Download Inbox</a> page, to mark QSOs as confirmed on eQSL.</p>
			<p>Choose Station(location) eQSL File belongs to:</p>
                    <select name="station_profile" class="form-select mb-2 me-sm-2" style="width: 20%;">
                    <option value="0">Select Station Location</option>
					<?php if (isset($station_profile)) { foreach ($station_profile->result() as $station) {
							$show_station = false;
							if (!empty($eqsl_mapping_mode)) {
								$show_station = in_array((int) $station->station_id, $mapped_station_ids ?? array(), true);
							} else {
								$show_station = (bool) $station->eqslqthnickname;
							}
							if($show_station) { ?>
					<option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) { echo " selected =\"selected\""; } ?>>Callsign: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name.") eQSL: ".(!empty($eqsl_mapping_mode) ? 'Mapped' : $station->eqslqthnickname); ?></option>
					<?php } } } ?>
                    </select>
                    <p><span class="label important">Important</span>Log files must have the file type .adi</p>
                    <input type="file" name="userfile" size="20" />
			</div>
			<hr class="divider">
			<div class="form-check">
			  <input class="form-check-input" type="radio" name="eqslimport" id="fetch" value="fetch"  checked="checked"/>
			  <label class="form-check-label" for="exampleRadios1">Import directly from eQSL</label>
			  <?php if (!empty($eqsl_mapping_mode)) { ?>
			  <p>Cloudlog will use the password from each active eQSL mapping to download confirmations for mapped station callsigns and nicknames.</p>
			  <?php } else { ?>
			  <p>Cloudlog will use the eQSL credentials from your Cloudlog user profile to connect to eQSL and download confirmations.</p>
			  <?php } ?>
				<div class="row">
		        	<div class="mb-3 col-sm-2">
			          	<div class="dxatlasdatepicker input-group date" id="eqsl_force_from_date" data-target-input="nearest">
                        	<input name="eqsl_force_from_date" id="eqsl_force_from_date" type="date" class="form-control w-auto">
			          	</div>
		        	</div>
		         	<div class="mb-3 col-sm-5" style="vertical-align:middle;"><label class="form-label"><?php echo "(Select a date, only if you want to force an import with an older date)"; //$this->lang->line(''); ?></label></div>
				</div>
			</div>
			<hr class="divider">
			<div class="mb-3"><input class="btn btn-primary" type="submit" value="Import eQSL QSO Matches" /></div>
		</form>
  </div>
</div>

</div>
