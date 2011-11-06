
<div id="container">
<h2>Backup - Notes XML</h2>


<?php if($status == true) { ?>

<p>Backing up your notes have been completed successfully and can be found at <a href="<?php echo base_url(); ?>backup/notes.xml"><?php echo base_url(); ?>backup/notes.xml</a></p>

<p>You could automate this process by making it a cronjob.</p>

<?php } else { ?>

<p>Something went wrong backing up check that the backup folder exists and has write permissions.</p>

<?php } ?>

</div>