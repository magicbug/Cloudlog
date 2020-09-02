<div class="container lotw">

	<h1><?php echo $page_title; ?></h1>

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
		    		<li>Open TQSL & go to the Callsign Certificates Tab</li>
		    		<li>Right Flick on desired Callsign</li>
		    		<li>Click "Save Callsign Certificate File" and do not add a password</li>
		    		<li>Upload File below.</li>
		    	</ul>
	    	</div>

			<?php echo form_open_multipart('lotw/do_cert_upload');?>
				<div class="form-group">
				    <label for="exampleFormControlFile1">Upload LoTW P12 File</label>
				    <input type="file" name="userfile" class="form-control-file" id="exampleFormControlFile1">
				 </div>

				<button type="submit" value="upload" class="btn btn-primary">Upload File</button>

			</form>

	    </div>
	</div>
	<!-- Card Ends -->

</div>
