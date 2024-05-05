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

.form-signin {
    width: 100%;
    max-width: 430px;
    padding: 15px;
    margin: auto;
}

.form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
</style>
<main class="form-signin">
    <img src="<?php echo base_url()?>/CloudLog_logo.png" class="mx-auto d-block" alt="" style="width:100px;height:100px;">
    <div class="my-2 bg-body rounded-0 shadow-sm card mb-2 shadow-sm">
        <div class="card-body">
            <h3><?php echo lang('account_login_to_cloudlog'); ?></h3>
            
            <?php $this->load->view('layout/messages'); ?>
            
            <form method="post" action="<?php echo site_url('user/login'); ?>" name="users">
			<?php $this->form_validation->set_error_delimiters('', ''); ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
                <div>
                    <label for="floatingInput"><strong><?php echo lang('account_username'); ?></strong></label>
                    <input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="<?php echo lang('account_username'); ?>"
                        value="<?php echo $this->input->post('user_name'); ?>" autofocus>
                </div>
                <div>
                    <label for="floatingPassword"><strong><?php echo lang('account_password'); ?></strong></label>
                    <input type="password" name="user_password" class="form-control" id="floatingPassword"
                        placeholder="<?php echo lang('account_password'); ?>">
                </div>

                <!-- build a remember me checkbox -->
                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" name="remember_me" value="1"> <?php echo lang('account_remember_me'); ?>
                    </label>

                <div>
                    <p><small><a class="" href="<?php echo site_url('user/forgot_password'); ?>"><?php echo lang('account_forgot_your_password'); ?></a></small></p>
                </div>
                <button class="w-100 btn btn-info" type="submit"><?php echo lang('account_login'); ?> →</button>
            </form>
        </div>
    </div>
</main>