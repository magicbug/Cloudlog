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
    <?php echo lang('dcl_results');?>
  </div>
  <div class="card-body">
    <?php if($dcl_error_count[0] > 0) { ?>
       <h3 class="card-title">Yay, its updated!</h3>
       <p class="card-text"><?php echo lang('dcl_info_updated')?></p>
       <p class="card-text"><?php echo lang('dcl_qsos_updated')?> <?php echo $dcl_error_count[0] ?>.</p>
       <p class="card-text"><?php echo lang('dcl_qsos_ignored')?> <?php echo $dcl_error_count[1] ?>.</p>
    <?php } else { ?>
       <h3 class="card-title"><?php echo lang('dcl_no_qsos_updated')?></h3>
       <p>
    <?php } ?>
    <?php if($dcl_errors) { ?>
      <h3><?php echo lang('dcl_dok_errors')?></h3>
      <p><?php echo lang('dcl_dok_errors_details')?></p>
      <p class="card-text"><?php echo $dcl_errors; ?></p>
    <?php } ?>
  </div>
</div>


</div>
