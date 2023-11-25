<style>
html,
body {
    height: 100%;
}

body {
    display: flex;
    align-items: center;
    padding-top: 40px;
    padding-bottom: 40px;
}

.form-forgot {
    width: 100%;
    max-width: 430px;
    padding: 15px;
    margin: auto;
}

input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
}
</style>

<main class="form-forgot">
    <img src="<?php echo base_url()?>/CloudLog_logo.png" class="mx-auto d-block" alt="" style="width:100px;height:100px;">
    <div class="my-2 bg-body rounded-0 shadow-sm card mb-2 shadow-sm">
        <div class="card-body">
            <div class="text-center">
                        <h3 class="text-center"><?php echo lang('account_forgot_password'); ?> <i class="fa fa-lock"></i></h3>
                        <p><?php echo lang('account_you_can_reset_your_password_here'); ?></p>
                <div class="panel-body">

                    <?php if(validation_errors() != ''): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo validation_errors(); ?>
                    </div>
                    <?php endif; ?>

                    <form id="register-form" role="form" autocomplete="off" class="form" method="post" action="<?php echo site_url('user/forgot_password'); ?>">
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                <input id="email" name="email" placeholder="<?php echo lang('account_email_address'); ?>" class="form-control" type="email" autofocus>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input name="recover-submit" class="w-100 btn btn-primary btn-block" value="<?php echo lang('account_reset_password'); ?>" type="submit">
                        </div>
                        <input type="hidden" class="hide" name="token" id="token" value="">
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>