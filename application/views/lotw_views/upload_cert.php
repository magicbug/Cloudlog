<div class="container lotw">

	<h1><?php echo $page_title; ?></h1>

	<!-- Card Starts -->
	<div class="card">
		<div class="card-header">
			Upload Certificate
		</div>

		<div class="card-body">
			<?php if($error != " ") { ?>
				<div class="alert alert-danger" role="alert">
			  	<?php echo $error; ?>
				</div>
	    	<?php } ?>

			<?php echo form_open_multipart('lotw/do_cert_upload');?>

				<div class="form-group">
				    <label for="exampleFormControlFile1">Upload LoTW P12 File</label>
				    <input type="file" name="userfile" class="form-control-file" id="exampleFormControlFile1">
				 </div>

				<button type="submit" value="upload" class="btn btn-primary">Submit</button>

			</form>

	    </div>
	</div>
	<!-- Card Ends -->

</div>
