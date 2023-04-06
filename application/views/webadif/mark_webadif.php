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
            QSOs marked
        </div>
        <div class="card-body">
            <h3 class="card-title">Yay, it's done!</h3>
            <p class="card-text">The QSOs are marked as exported to QO-100 Dx Club.</p>
        </div>
    </div>


</div>

