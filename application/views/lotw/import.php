<div class="container lotw">

	<h1>LOTW Import</h1>

<div class="card">
	<div class="card-header">Import Options</div>
  <div class="card-body">

    <?php $this->load->view('layout/messages'); ?>

    <?php echo form_open_multipart('lotw/import');?>

		<div class="custom-control custom-radio">
			<input type="radio" id="lotwimport" name="lotwimport" class="custom-control-input">
			<label class="custom-control-label" for="lotwimport">Upload a file</label>
			<p>Upload the Exported ADIF file from LoTW from the <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> Area, to mark QSOs as confirmed on LOTW.</p>
			<p><span class="label important">Important</span> Log files must have the file type .adi</p>
			<div class="custom-file">
			  	<input type="file" class="custom-file-input" id="adiffile" name="userfile" size="20" />
			  <label class="custom-file-label" for="adiffile">Choose file</label>
			</div>
		</div>

		<br><br>

		<div class="custom-control custom-radio">
			<input type="radio" name="lotwimport" id="fetch" class="custom-control-input" value="fetch" />
			<label class="custom-control-label" for="fetch">Pull LoTW data for me</label>
		</div>
      <p class="card-text">From date:</p>
      <div class="row">
          <div class="input-group date col-md-3" id="datetimepicker1" data-target-input="nearest">
              <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
              <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
          </div>
      </div>

		<p class="form-text text-muted">Cloudlog will use the LoTW username and password stored in your user profile to download a report from LoTW for you. The report Cloudlog downloads will have all confirmations since chosen date, or since your last LoTW confirmation (fetched from your log), up until now.</p>

		<p class="form-text text-muted"><span class="badge badge-info">Important</span> You must have QSOs in the logbook before this option works, it will not populate your log from empty based on LoTW QSOs yet.</p>

		<input class="btn primary" type="submit" value="Import" />

	</form>
  </div>
</div>

</div>
