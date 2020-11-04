<!-- Display Success -->
<?php if($this->session->flashdata('success') != '') { ?>
<div class="alert alert-success" role="alert">
        <?php echo $this->session->flashdata('success'); ?>
</div>
<?php } ?>

<!-- Display Notices -->
<?php if($this->session->flashdata('notice') != '') { ?>
<div class="alert alert-info" role="alert">
        <?php echo $this->session->flashdata('notice'); ?>
</div>
<?php } ?>

<!-- Display Warnings -->
<?php if($this->session->flashdata('warning') != '') { ?>
<div class="alert alert-warning" role="alert">
        <?php echo $this->session->flashdata('warning'); ?>
</div>
<?php } ?>

<!-- Display Errors -->
<?php if($this->session->flashdata('error') != '') { ?>
<div class="alert alert-danger" role="alert">
        <?php echo $this->session->flashdata('error'); ?>
</div>
<?php } ?>

<!-- Display form validation errors -->
<?php if(validation_errors()) { ?>
<div class="alert alert-danger" role="alert">
        <?php echo validation_errors(); ?>
</div>
<?php } ?>