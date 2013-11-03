<!-- JS -->
<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

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