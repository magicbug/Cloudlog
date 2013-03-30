<div id="container">

<h2><?php echo $page_title; ?></h2>
<?php $this->load->view('layout/messages'); ?>

	<h4>Step 1</h4>
		<a href="<?php echo site_url('adif/export_lotw'); ?>" title="Export LoTW" target="_blank">Export an ADIF</a> file of QSOs that have not been uploaded to LoTW.
		
	<h4>Step 2</h4>
		<p>Use Trusted QSL to sign the exported file.</p>
		
	<h4>Step 3</h4>
		<p>Select the signed file and click "Upload". It will be sent to LoTW for processing.</p>
			
	<?php echo form_open_multipart('lotw/export');?>
		<p><span class="label important">Important</span> Log files must have the file type .tq8</p>
		<input type="file" name="userfile" size="20" />
			
		<p>Cloudlog will use the LoTW username an password stored in your user profile to download a report from LoTW for you. The report Cloudlog downloads will have all confirmations since your last LoTW confirmation, up until now.</p>

		<input class="btn primary" type="submit" value="Upload" />
	</form>


</div>
