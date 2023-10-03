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
        <a class="nav-link" href="<?php echo site_url('eqsl/tools');?>">Tools</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/download');?>">Download eQSL cards</a>
      </li>
    </ul>
  </div>
  <div class="card-body">
		<?php $this->load->view('layout/messages'); ?>

		<?php echo form_open_multipart('eqsl/import');?>

			<div class="form-check">
			  <input class="form-check-input" type="radio" name="eqslimport" id="upload" value="upload" checked /> 
			  <label class="form-check-label" for="exampleRadios1">
			    Import from file...
			  </label>
 			  <br>
			  <p>Upload the Exported ADIF file from eQSL from the <a href="https://eqsl.cc/qslcard/DownloadInBox.cfm" target="_blank">Download Inbox</a> page, to mark QSOs as confirmed on eQSL.</p>
					<p><span class="label important">Important</span> Log files must have the file type .adi</p>
					<input type="file" name="userfile" size="20" />
			  <br/><br/>
			<p>Choose Station(location) eQSL File belongs to:</p>
                    <select name="station_profile" class="custom-select mb-2 mr-sm-2" style="width: 20%;">
                    <option value="0">Select Station Location</option>
                    <?php foreach ($station_profile->result() as $station) { ?>
                    <option value="<?php echo $station->station_id; ?>" <?php if ($station->station_id == $this->stations->find_active()) { echo " selected =\"selected\""; } ?>>Callsign: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name.") eQSL: ".$station->eqslqthnickname; ?></option>
                    <?php } ?>
                    </select>
			</div>

			<br><br>
			
			<div class="form-check">
			  <input class="form-check-input" type="radio" name="eqslimport" id="fetch" value="fetch"  checked="checked"/>
			  <label class="form-check-label" for="exampleRadios1">Import directly from eQSL</label>
			  <p>Cloudlog will use the eQSL credentials from your Cloudlog user profile to connect to eQSL and download confirmations.</p>
				<div class="form-row">
		        	<div class="form-group col-sm-2">
			          	<div class="dxatlasdatepicker input-group date" id="eqsl_force_from_date" data-target-input="nearest">
			            	<input name="eqsl_force_from_date" type="text" placeholder="YYYY/MM/DD" class="form-control datetimepicker-input" data-target="#eqsl_force_from_date" value=""/>
			              	<div class="input-group-append"  data-target="#eqsl_force_from_date" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div>
			          	</div>
		        	</div>
		         	<div class="form-group col-sm-5" style="vertical-align:middle;"><label class="form-label"><?php echo "(Select a date, only if you want to force an import with an older date)"; //$this->lang->line(''); ?></label></div>
				</div>
			</div>

			<br>
			<div class="form-group"><input class="btn btn-primary" type="submit" value="Import eQSL QSO Matches" /></div>
		</form>
  </div>
</div>

</div>
