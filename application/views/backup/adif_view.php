<h2>Backup ADIF</h2>
<div class="wrap_content note">

<?php if($status == true) { ?>

<p>Backing up your log has been completed successfully and can be found at <a href="<?php echo site_url('backup'); ?>/logbook.adi"><?php echo site_url('backup'); ?>/logbook.adi</a></p>

<p>You could automate this process by making it a cronjob.</p>

<?php } else { ?>

<p>Something went wrong backing up check that the backup folder exists and has write permissions.</p>

<?php } ?>

</div>