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
    <?php echo lang('adif_imported')?>
  </div>
  <div class="card-body">
    <h3 class="card-title"><?php echo lang('adif_yay_its_imported')?></h3>
    <p class="card-text"><?php echo lang('adif_import_confirm')?>
    <?php if(isset($skip_dupes)) {
             echo lang('adif_import_dupes_inserted');
          } else {
             echo lang('adif_import_dupes_skipped');
          } ?>
    </p>
    <?php if($adif_errors) { ?>
      <h3><?php echo lang('adif_import_errors')?></h3>
      <p><?php echo lang('adif_import_errors_hint')?></p>
      <p class="card-text"><?php echo $adif_errors; ?></p>
    <?php } ?>
  </div>
</div>


</div>
