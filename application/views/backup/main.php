<div class="container">
<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    Backup
  </div>
  <div class="card-body">
    <h5 class="card-title">You can export parts of Cloudlog like Notes</h5>
    <p class="card-text">Backup options.</p>
	<ul>
		<li><a href="<?php echo site_url('backup/adif'); ?>">Backup ADIF</a></li>
		<li><a href="<?php echo site_url('backup/notes'); ?>">Backup Notes</a></li>
	</ul>
  </div>
</div>


</div>

