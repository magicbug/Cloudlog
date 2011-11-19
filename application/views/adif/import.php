<div id="container">
<h2><?php echo $page_title; ?></h2>

<?php echo $error;?>

<?php echo form_open_multipart('adif/import');?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input class="btn primary" type="submit" value="Upload" />

</form>

</div>
