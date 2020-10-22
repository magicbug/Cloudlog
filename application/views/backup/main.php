<div class="container">
<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<h2><?php echo $page_title; ?></h2>

<div class="card">
  <div class="card-header">
    Backup
  </div>
  <div class="card-body">
    <p class="card-text">Some of the data stored in Cloudlog can be exported so that you can keep a backup copy elsewhere.</p>
    <p class="card-text">It's recommended to create backups on a regular basis to protect your data.</p>
    <p class="card-text">Choose an option from the list below:</p>
	<ul>
		<li><a href="<?php echo site_url('backup/adif'); ?>">Backup ADIF</a></li>
		<li><a href="<?php echo site_url('backup/notes'); ?>">Backup Notes</a></li>
	</ul>
  </div>
</div>


</div>

