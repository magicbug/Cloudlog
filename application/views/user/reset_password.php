<main class="auth-shell">
	<div class="auth-card-wrap">
		<div class="text-center mb-3">
			<img src="<?php echo base_url(); ?>CloudLog_logo.png" alt="Cloudlog" class="auth-logo mb-2">
			<h1 class="h4 fw-bold mb-1"><?php echo lang('account_reset_password'); ?></h1>
			<p class="small text-muted mb-0"><?php echo lang('account_you_can_reset_your_password_here'); ?></p>
		</div>

		<div class="card auth-card">
			<div class="card-body p-4">
				<?php if (validation_errors() != ''): ?>
					<div class="alert alert-danger py-2 small" role="alert">
						<?php echo validation_errors(); ?>
					</div>
				<?php endif; ?>

				<form role="form" autocomplete="off" class="form" method="post" action="<?php echo site_url('user/reset_password'); ?>/<?php echo $reset_code; ?>">
					<div class="mb-3">
						<label for="inputPassword" class="auth-field-label"><?php echo lang('account_password'); ?></label>
						<div class="input-group">
							<span class="input-group-text auth-input-icon"><i class="fas fa-lock"></i></span>
							<input type="password" name="password" class="form-control" id="inputPassword" placeholder="<?php echo lang('account_password'); ?>" autocomplete="new-password" autofocus>
						</div>
					</div>

					<div class="mb-3">
						<label for="inputPasswordConfirm" class="auth-field-label"><?php echo lang('account_confirm_password'); ?></label>
						<div class="input-group">
							<span class="input-group-text auth-input-icon"><i class="fas fa-lock"></i></span>
							<input type="password" name="password_confirm" class="form-control" id="inputPasswordConfirm" placeholder="<?php echo lang('account_confirm_password'); ?>" autocomplete="new-password">
						</div>
					</div>

					<input type="hidden" class="hide" name="token" id="token" value="">

					<button name="recover-submit" class="w-100 btn btn-primary auth-submit-btn" type="submit">
						<i class="fas fa-check me-1"></i><?php echo lang('account_reset_password'); ?>
					</button>
				</form>
			</div>

			<div class="card-footer auth-card-footer text-center py-3">
				<a class="small fw-semibold text-decoration-none" href="<?php echo site_url('user/login'); ?>">
					<i class="fas fa-arrow-left me-1"></i>Back to <?php echo lang('account_login'); ?>
				</a>
			</div>
		</div>
	</div>
</main>
