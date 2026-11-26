<main class="auth-shell">
	<div class="auth-card-wrap auth-card-wrap-wide">
		<div class="text-center mb-3">
			<img src="<?php echo base_url(); ?>CloudLog_logo.png" alt="Cloudlog" class="auth-logo mb-2">
			<h1 class="h4 fw-bold mb-1">Create your Cloudlog account</h1>
			<p class="small text-muted mb-0">Sign up with your callsign to get started</p>
		</div>

		<div class="card auth-card">
			<div class="card-body p-4">
				<?php $this->load->view('layout/messages'); ?>

				<?php echo form_open(site_url('user/signup'), ['novalidate' => 'novalidate']); ?>
					<?php $this->form_validation->set_error_delimiters('', ''); ?>

					<div class="row g-3">
						<div class="col-md-6">
							<label for="signupUsername" class="auth-field-label"><?php echo lang('account_username'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-user"></i></span>
								<input id="signupUsername" type="text" name="user_name" autocomplete="username" class="form-control <?php echo form_error('user_name') || isset($username_error) ? 'is-invalid' : '';?>" value="<?php echo set_value('user_name'); ?>" required autofocus>
								<div class="invalid-feedback"><?php echo form_error('user_name') ?: (isset($username_error) ? $username_error : ''); ?></div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="signupEmail" class="auth-field-label"><?php echo lang('account_email_address'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-envelope"></i></span>
								<input id="signupEmail" type="email" name="user_email" autocomplete="email" class="form-control <?php echo form_error('user_email') || isset($email_error) ? 'is-invalid' : '';?>" value="<?php echo set_value('user_email'); ?>" required>
								<div class="invalid-feedback"><?php echo form_error('user_email') ?: (isset($email_error) ? $email_error : ''); ?></div>
							</div>
						</div>

						<div class="col-md-6">
							<label for="passwordInput" class="auth-field-label"><?php echo lang('account_password'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-lock"></i></span>
								<input id="passwordInput" type="password" name="user_password" autocomplete="new-password" class="form-control <?php echo form_error('user_password') || isset($password_error) ? 'is-invalid' : '';?>" required>
								<button class="btn btn-outline-secondary auth-eye-btn" type="button" id="toggleSignupPassword" aria-label="Show password" title="Show password">
									<i class="fas fa-eye"></i>
								</button>
								<div class="invalid-feedback"><?php echo form_error('user_password') ?: (isset($password_error) ? $password_error : ''); ?></div>
							</div>
							<div class="progress mt-2" style="height: 5px;">
								<div id="pwStrengthBar" class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<small id="pwStrengthText" class="text-muted">Start typing a password&hellip;</small>
						</div>
						<div class="col-md-6">
							<label for="passwordConfirmInput" class="auth-field-label"><?php echo lang('account_confirm_password'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-check"></i></span>
								<input id="passwordConfirmInput" type="password" name="user_password_confirm" autocomplete="new-password" class="form-control <?php echo form_error('user_password_confirm') ? 'is-invalid' : '';?>" required>
								<div class="invalid-feedback"><?php echo form_error('user_password_confirm'); ?></div>
							</div>
							<small id="pwConfirmText" class="text-muted"></small>
						</div>

						<div class="col-md-6">
							<label for="signupFirstName" class="auth-field-label"><?php echo lang('account_first_name'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-id-badge"></i></span>
								<input id="signupFirstName" type="text" name="user_firstname" autocomplete="given-name" class="form-control <?php echo form_error('user_firstname') ? 'is-invalid' : '';?>" value="<?php echo set_value('user_firstname'); ?>" required>
								<div class="invalid-feedback"><?php echo form_error('user_firstname'); ?></div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="signupLastName" class="auth-field-label"><?php echo lang('account_last_name'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-id-badge"></i></span>
								<input id="signupLastName" type="text" name="user_lastname" autocomplete="family-name" class="form-control <?php echo form_error('user_lastname') ? 'is-invalid' : '';?>" value="<?php echo set_value('user_lastname'); ?>" required>
								<div class="invalid-feedback"><?php echo form_error('user_lastname'); ?></div>
							</div>
						</div>

						<div class="col-md-6">
							<label for="signupCallsign" class="auth-field-label"><?php echo lang('account_callsign'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-broadcast-tower"></i></span>
								<input id="signupCallsign" type="text" name="user_callsign" class="form-control text-uppercase fw-semibold <?php echo form_error('user_callsign') || isset($callsign_error) ? 'is-invalid' : '';?>" style="text-transform: uppercase;" value="<?php echo set_value('user_callsign'); ?>" required>
								<div class="invalid-feedback"><?php echo form_error('user_callsign') ?: (isset($callsign_error) ? $callsign_error : ''); ?></div>
							</div>
						</div>
						<div class="col-md-6">
							<label for="signupLocator" class="auth-field-label"><?php echo lang('account_gridsquare'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-map-marker-alt"></i></span>
								<input id="signupLocator" type="text" name="user_locator" class="form-control text-uppercase <?php echo form_error('user_locator') ? 'is-invalid' : '';?>" value="<?php echo set_value('user_locator'); ?>" placeholder="e.g. IO87JP">
								<div class="invalid-feedback"><?php echo form_error('user_locator'); ?></div>
							</div>
						</div>

						<div class="col-12">
							<label for="signupTimezone" class="auth-field-label"><?php echo lang('account_timezone'); ?></label>
							<div class="input-group">
								<span class="input-group-text auth-input-icon"><i class="fas fa-clock"></i></span>
								<?php $tz_selected = set_value('user_timezone'); if ($tz_selected === '') { $tz_selected = '151'; } ?>
								<select id="signupTimezone" name="user_timezone" class="form-select <?php echo form_error('user_timezone') ? 'is-invalid' : '';?>" required>
									<option value="" disabled>Choose your timezone</option>
									<?php foreach ($timezones as $id => $name) { ?>
										<option value="<?php echo $id; ?>" <?php echo ((string)$id === (string)$tz_selected) ? 'selected' : ''; ?>><?php echo $name; ?></option>
									<?php } ?>
								</select>
								<div class="invalid-feedback"><?php echo form_error('user_timezone'); ?></div>
							</div>
						</div>
					</div>

					<button class="w-100 btn btn-primary auth-submit-btn mt-4" type="submit">
						<i class="fas fa-user-plus me-1"></i><?php echo lang('account_create_account'); ?> <i class="fas fa-arrow-right ms-1"></i>
					</button>
				</form>
			</div>

			<div class="card-footer auth-card-footer text-center py-3">
				<span class="small text-muted">Already have an account?</span>
				<a href="<?php echo site_url('user/login'); ?>" class="small fw-semibold text-decoration-none ms-1">Log in</a>
			</div>
		</div>
	</div>

	<script>
	(function(){
		function scorePassword(pw){
			if(!pw) return 0;
			let score = 0;
			const len = pw.length;
			score += Math.min(10, len) * 6;
			let varieties = 0;
			if(/[a-z]/.test(pw)) varieties++;
			if(/[A-Z]/.test(pw)) varieties++;
			if(/\d/.test(pw)) varieties++;
			if(/[^A-Za-z0-9]/.test(pw)) varieties++;
			score += (varieties - 1) * 10;
			if(len >= 12 && varieties >= 3) score += 10;
			if(/(.)\1{2,}/.test(pw)) score -= 10;
			return Math.max(0, Math.min(100, score));
		}

		function labelFor(score){
			if(score >= 80) return {label: 'Very strong', cls: 'bg-success'};
			if(score >= 60) return {label: 'Strong', cls: 'bg-info'};
			if(score >= 45) return {label: 'Good', cls: 'bg-secondary'};
			if(score >= 30) return {label: 'Fair', cls: 'bg-warning'};
			return {label: 'Weak', cls: 'bg-danger'};
		}

		var pw = document.getElementById('passwordInput');
		var pwc = document.getElementById('passwordConfirmInput');
		var bar = document.getElementById('pwStrengthBar');
		var text = document.getElementById('pwStrengthText');
		var confirmText = document.getElementById('pwConfirmText');
		var toggleBtn = document.getElementById('toggleSignupPassword');

		if (toggleBtn && pw) {
			toggleBtn.addEventListener('click', function() {
				var isPassword = pw.type === 'password';
				pw.type = isPassword ? 'text' : 'password';
				if (pwc) pwc.type = isPassword ? 'text' : 'password';
				toggleBtn.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
				toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
				toggleBtn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
			});
		}

		function refresh(){
			var val = pw.value || '';
			var s = scorePassword(val);
			var meta = labelFor(s);
			bar.style.width = s + '%';
			bar.className = 'progress-bar ' + meta.cls;
			text.textContent = (val.length ? meta.label + ' password' : 'Start typing a password\u2026');

			if(pwc){
				if(!pwc.value){
					confirmText.textContent = '';
					confirmText.className = 'text-muted';
				} else if (pwc.value === val){
					confirmText.textContent = 'Passwords match';
					confirmText.className = 'text-success';
				} else {
					confirmText.textContent = 'Passwords do not match';
					confirmText.className = 'text-danger';
				}
			}
		}

		if(pw){ pw.addEventListener('input', refresh); }
		if(pwc){ pwc.addEventListener('input', refresh); }
		refresh();
	})();
	</script>
</main>
