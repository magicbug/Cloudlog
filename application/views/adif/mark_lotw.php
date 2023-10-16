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
            <?php echo lang('adif_qso_marked')?>
        </div>
        <div class="card-body">
            <h3 class="card-title"><?php echo lang('adif_yay_its_done')?></h3>
            <p class="card-text"><?php echo lang('adif_qso_lotw_marked_confirm')?></p>
        </div>
    </div>


</div>

