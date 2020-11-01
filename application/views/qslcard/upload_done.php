<div class="container">

    <h2><?php echo $page_title; ?></h2>
    <div class="card-body">
        <?php if($front != 'Success') { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $front; ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-success" role="alert">
                Front QSL Card image has been uploaded!
        </div>
        <?php } ?>

        <?php if($back != 'Success') { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $back; ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-success" role="alert">
                Back QSL Card image has been uploaded!
            </div>
        <?php } ?>
    </div>
</div>