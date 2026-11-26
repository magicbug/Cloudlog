<main class="auth-shell">
	<div class="auth-card-wrap">
		<div class="text-center mb-3">
			<img src="<?php echo base_url(); ?>CloudLog_logo.png" alt="Cloudlog" class="auth-logo mb-2">
			<h1 class="h4 fw-bold mb-1"><?php echo lang('account_login_to_cloudlog'); ?></h1>
			<p class="small text-muted mb-0">Sign in to access your amateur radio logbook</p>
		</div>

		<div class="card auth-card">
			<div class="card-body p-4">
				<?php $this->load->view('layout/messages'); ?>

				<form method="post" action="<?php echo site_url('user/login'); ?>" name="users">
					<?php $this->form_validation->set_error_delimiters('', ''); ?>
					<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />

					<div class="mb-3">
						<label for="floatingInput" class="auth-field-label"><?php echo lang('account_username'); ?></label>
						<div class="input-group">
							<span class="input-group-text auth-input-icon"><i class="fas fa-user"></i></span>
							<input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="<?php echo lang('account_username'); ?>"
								value="<?php echo htmlspecialchars((string)$this->input->post('user_name'), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="username" autofocus>
						</div>
					</div>

					<div class="mb-3">
						<label for="floatingPassword" class="auth-field-label"><?php echo lang('account_password'); ?></label>
						<div class="input-group">
							<span class="input-group-text auth-input-icon"><i class="fas fa-lock"></i></span>
							<input type="password" name="user_password" class="form-control" id="floatingPassword"
								placeholder="<?php echo lang('account_password'); ?>" autocomplete="current-password">
							<button class="btn btn-outline-secondary auth-eye-btn" type="button" id="toggleLoginPassword" aria-label="Show password" title="Show password">
								<i class="fas fa-eye"></i>
							</button>
						</div>
					</div>

					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
						<div class="form-check mb-0">
							<input class="form-check-input" type="checkbox" name="remember_me" value="1" id="rememberMeCheck">
							<label class="form-check-label small" for="rememberMeCheck"><?php echo lang('account_remember_me'); ?></label>
						</div>
						<a class="small text-decoration-none" href="<?php echo site_url('user/forgot_password'); ?>"><?php echo lang('account_forgot_your_password'); ?></a>
					</div>

					<button class="w-100 btn btn-primary auth-submit-btn" type="submit">
						<?php echo lang('account_login'); ?> <i class="fas fa-arrow-right ms-1"></i>
					</button>
				</form>
			</div>

			<?php if ($this->optionslib->get_option('open_registration') == 'true' && !$this->config->item('disable_open_registration')) { ?>
			<div class="card-footer auth-card-footer text-center py-3">
				<span class="small text-muted">Don&rsquo;t have an account?</span>
				<a class="small fw-semibold text-decoration-none ms-1" href="<?php echo site_url('user/signup'); ?>">Sign up</a>
			</div>
			<?php } ?>
		</div>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var btn = document.getElementById('toggleLoginPassword');
	var input = document.getElementById('floatingPassword');
	if (!btn || !input) return;
	btn.addEventListener('click', function() {
		var isPassword = input.type === 'password';
		input.type = isPassword ? 'text' : 'password';
		btn.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
		btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
		btn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
	});
});
</script>
