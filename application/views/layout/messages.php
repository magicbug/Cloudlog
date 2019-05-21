<!-- Display Success -->
<?php if($this->session->flashdata('success') != '') { ?>
<div class="alert-message success">
        <?php echo $this->session->flashdata('success'); ?>
</div>
<?php } ?>

<!-- Display Notices -->
<?php if($this->session->flashdata('notice') != '') { ?>
<div class="alert-message info">
        <?php echo $this->session->flashdata('notice'); ?>
</div>
<?php } ?>

<!-- Display Warnings -->
<?php if($this->session->flashdata('warning') != '') { ?>
<div class="alert-message warning">
        <?php echo $this->session->flashdata('warning'); ?>
</div>
<?php } ?>

<!-- Display Errors -->
<?php if($this->session->flashdata('error') != '') { ?>
<div class="alert-message error">
        <?php echo $this->session->flashdata('error'); ?>
</div>
<?php } ?>

<!-- Display form validation errors -->
<?php if(validation_errors()) { ?>
<div class="alert-message error">
        <?php echo validation_errors(); ?>
</div>
<?php } ?>