<div class="container">

    <h2><?php echo $page_title; ?></h2>
    <div class="card-body">
        <?php if(isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-success" role="alert">
                QSLcard has been uploaded!
    </div>
        <?php } ?>
    </div>
</div>