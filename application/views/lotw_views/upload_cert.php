<div class="container lotw">

	<h2><?php echo $page_title; ?></h2>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			Upload Logbook of the World .p12 Certificate
		</div>

		<div class="card-body">
			<?php if($error != " ") { ?>
				<div class="alert alert-danger" role="alert">
			  	<?php echo $error; ?>
				</div>
	    	<?php } ?>

	    	<div class="alert alert-info" role="alert">
		    	<h5>Export .p12 File Instructions</h5>

		    	<ul>
		    		<li>Open TQSL &amp; go to the Callsign Certificates Tab</li>
		    		<li>Right click on desired Callsign</li>
		    		<li>Click "Save Callsign Certificate File" and do not add a password</li>
		    		<li>Upload File below.</li>
		    	</ul>
	    	</div>

			<?php echo form_open_multipart('lotw/do_cert_upload');?>
				<div class="form-group">
				    <label for="exampleFormControlFile1">Upload LoTW P12 File</label>
				    <input type="file" name="userfile" class="form-control-file" id="exampleFormControlFile1">
				 </div>

			<div class="form-group">
				<label for="stationDXCCInput">Certificate DXCC</label>
					<?php if ($dxcc_list->num_rows() > 0) { ?>
					<select class="form-control" id="dxcc_select" name="dxcc" aria-describedby="stationCallsignInputHelp">
					<option value=""></option>
					<?php foreach ($dxcc_list->result() as $dxcc) { ?>
					<option value="<?php echo $dxcc->name; ?>"><?php echo $dxcc->name; ?></option>
					<?php } ?>
					</select>
					<?php } ?>
				<small id="stationDXCCInputHelp" class="form-text text-muted">Certificate DXCC entity. For example: Scotland</small>
			</div>

				<button type="submit" value="upload" class="btn btn-primary">Upload File</button>

			</form>

	    </div>
	</div>
	<!-- Card Ends -->

</div>
