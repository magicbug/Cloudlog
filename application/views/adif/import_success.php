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
    ADIF Imported
  </div>
  <div class="card-body">
    <h3 class="card-title">Yay, its imported!</h3>
    <p class="card-text">The ADIF File has been imported, and any dupes skipped.</p>
    <?php if($adif_errors) { ?>
      <h3>ADIF Errors</h3>
      <p>You have ADIF errors, the QSOs have still been added but these fields have not been populated.</p>
      <p class="card-text"><?php echo $adif_errors; ?></p>
    <?php } ?>
  </div>
</div>


</div>
