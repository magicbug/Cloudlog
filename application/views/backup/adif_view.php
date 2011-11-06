<div id="container">

<h2>Backup - ADIF</h2>


<?php if($status == true) { ?>

<p>Backing up your log has been completed successfully and can be found at <a href="<?php echo base_url(); ?>backup/logbook.adi"><?php echo base_url(); ?>backup/logbook.adi</a></a></p>

<p>You could automate this process by making it a cronjob.</p>

<?php } else { ?>

<p>Something went wrong backing up check that the backup folder exists and has write permissions.</p>

<?php } ?>

</div>