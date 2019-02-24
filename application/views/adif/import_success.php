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
    <h5 class="card-title">Yay, its imported!</h5>
    <p class="card-text"><p>The ADIF File has been imported, and any dupes skipped.</p></p>
  </div>
</div>


</div>
