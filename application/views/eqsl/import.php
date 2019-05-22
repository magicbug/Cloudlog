<div class="container eqsl">
<div class="card">
  <div class="card-header">
  	<h5 class="card-title"><?php echo $page_title; ?></h5>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/Export');?>">Upload QSOs</a>
      </li>
    </ul>
  </div>
  <div class="card-body">
		<?php $this->load->view('layout/messages'); ?>

		<?php echo form_open_multipart('eqsl/import');?>
		<table>
			<tr>
				<td><input type="radio" name="eqslimport" id="upload" value="upload" checked /> Upload a file</td>
				<td>
					<p>Upload the Exported ADIF file from eQSL from the <a href="http://eqsl.cc/qslcard/DownloadInBox.cfm" target="_blank">Download Inbox</a> page, to mark QSOs as confirmed on eQSL.</p>
					<p><span class="label important">Important</span> Log files must have the file type .adi</p>
					<input type="file" name="userfile" size="20" />
				</td>
			</tr>
			<tr>
				<td><input type="radio" name="eqslimport" id="fetch" value="fetch" /> Pull eQSL data for me</td>
				<td>
					<p>Cloudlog will use the eQSL username and password stored in your user profile to download confirmations from eQSL for you. We will only download confirmations received since your last eQSL confirmed QSO.</p>
				</td>
			</tr>
		</table>
		<input class="btn primary" type="submit" value="Import" />

		</form>
  </div>
</div>

</div>
