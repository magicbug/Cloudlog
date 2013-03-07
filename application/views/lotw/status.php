<div id="container">
<h2><?php echo $page_title; ?></h2>

<?php if($this->session->flashdata('warning')) { ?>
    <div id="message" >
        <?php echo $this->session->flashdata('warning'); ?>
    </div>
<?php } ?>
<?php if($this->session->flashdata('lotw_status')) { ?>
    <div id="message" >
        <?php echo $this->session->flashdata('lotw_status'); ?>
    </div>
<?php } ?>

</div>
