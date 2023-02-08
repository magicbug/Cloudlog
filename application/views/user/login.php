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
        <div class="rounded-0 card-header py-2">Cloudlog Login</div>
        <div class="card-body">

            <form method="post" action="<?php echo site_url('user/login'); ?>" name="users">
			<?php $this->form_validation->set_error_delimiters('', ''); ?>
                <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
                <div>
                    <label for="floatingInput"><strong>Username<strong></label>
                    <input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="Username"
                        value="<?php echo $this->input->post('user_name'); ?>">
                </div>
                <div>
                    <label for="floatingPassword"><strong>Password</strong></label>
                    <input type="password" name="user_password" class="form-control" id="floatingPassword"
                        placeholder="Password">
                </div>

                <div>
                    <p><small><a class="" href="<?php echo site_url('user/forgot_password'); ?>">Forgot your password?</a></small></p>
                </div>
					<?php $this->load->view('layout/messages'); ?>
                <button class="w-100 btn btn-info" type="submit">Login →</button>
            </form>
        </div>
    </div>
</main>