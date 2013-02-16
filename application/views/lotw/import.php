<div id="container">
<h2><?php echo $page_title; ?></h2>

<p>Upload the Exported ADIF file from LoTW from the <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> Area, to mark QSOs as confirmed on LOTW.</p>

<p><span class="label important">Important</span> Log files must have the file type .adi</p>

<?php echo form_open_multipart('lotw/import');?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input class="btn primary" type="submit" value="Upload" />

</form>

</div>
