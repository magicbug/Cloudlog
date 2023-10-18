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
    <?php } else { ?>
       <h3 class="card-title"><?php echo lang('dcl_no_qsos_updated')?></h3>
    <?php } ?>
       <div class="alert alert-info" role="alert">
          <?php echo lang('dcl_qsos_updated')?>: <?php echo $dcl_error_count[0] ?> / <?php echo lang('dcl_qsos_ignored')?>: <?php echo $dcl_error_count[1] ?> / <?php echo lang('dcl_qsos_unmatched')?>: <?php echo $dcl_error_count[2] ?>
       </div>
    <?php if($dcl_errors) { ?>
      <h3><?php echo lang('dcl_dok_errors')?></h3>
      <p><?php echo lang('dcl_dok_errors_details')?></p>
      <table width="100%">
         <tr class="titles">
            <td>Date</td>
            <td>Time</td>
            <td>Call</td>
            <td>Band</td>
            <td>Mode</td>
            <td>DOK in Log</td>
            <td>DOK in DCL</td>
            <td>DCL QSL Status</td>
         </tr>
      <?php echo $dcl_errors; ?>
      </table>
    <?php } ?>
  </div>
</div>


</div>
