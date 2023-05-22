
<div class="container eqsl">
<h2><?php echo $page_title; ?></h2>
<div class="card">
  <div class="card-header">
  	<div class="card-title">eQSL Card Image Download</div>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/Export');?>">Upload QSOs</a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/tools');?>">Tools</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/download');?>">Download eQSL cards</a>
      </li>
    </ul>
  </div>

  <div class="card-body">
  <?php $this->load->view('layout/messages'); ?>

<?php
   if (! empty($qslsnotdownloaded->result())) {
?>
    	<p>Below is a table of QSOs that have been confirmed on eQSL but QSL images have not been downloaded yet.</p>


		<table = style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">
			<thead><tr class="titles">
				<th>Date</th>
				<th>Time</th>
				<th>Call</th>
				<th>Mode</th>
				<th>Submode</th>
				<th>Band</th>
				<th>Action</th>
			</tr></thead><tbody>
<?php
foreach ($qslsnotdownloaded->result_array() as $qsl) {
	echo "<tr>";
	$timestamp = strtotime($qsl['COL_TIME_ON']);
	echo "<td>".date($custom_date_format, $timestamp)."</td>";
	echo "<td>".date('H:i', $timestamp)."</td>";
	echo "<td>".str_replace("0","&Oslash;",$qsl['COL_CALL'])."</td>";
	echo "<td>".$qsl['COL_MODE']."</td>";
	if(isset($qsl['COL_SUBMODE'])) {
		echo "<td>".$qsl['COL_SUBMODE']."</td>";
	} else {
		echo "<td></td>";
	}
	echo "<td>".$qsl['COL_BAND']."</td>";
	echo "<td><a href=\"".site_url()."/eqsl/image/".$qsl['COL_PRIMARY_KEY']."\" data-fancybox=\"images\" data-width=\"528\" data-height=\"336\" class=\"btn btn-primary btn-sm\">View/Download</a></td>";
}
	echo "</tr>";
?>
		</tbody></table>
		<br /><br />
		<?php echo form_open_multipart('eqsl/download');?>

			<div class="form-check">
			  <input class="form-check-input" type="hidden" name="eqsldownload" id="download" value="download" checked />
			  <p>Cloudlog will use the eQSL credentials from your Cloudlog user profile to connect to eQSL and download confirmations.</p>
			</div>
			<p><div class="alert alert-danger" role="alert">Due to a rate limit of approximately 10 seconds per eQSL picture download calling this function will take a long time to complete! Thus you may have to call this function several times depending on the amount of outstanding cards. This may run into a script timeout depending on the PHP configuration.</div></p>

		<input class="btn btn-primary" type="submit" value="Download un-synced eQSL cards" />

		</form>

<?php
	} else {
		echo "<p>There are no QSOs whose eQSL card images have not yet been downloaded. Go log some more QSOs!</p>";
	}
?>
</div>

</div>
</div>
