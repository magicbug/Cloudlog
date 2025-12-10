<style>
    html,
    body { height: 100%; }
    body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    .form-signin {
        width: 100%;
        max-width: 640px;
        padding: 15px;
        margin: auto;
    }
    .brand-badge {
        width: 92px; height: 92px;
        border-radius: 20px;
        display: block;
    }
</style>
<main class="form-signin">
    <img src="<?php echo base_url() ?>/CloudLog_logo.png" class="mx-auto d-block brand-badge mb-2" alt="Cloudlog">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pb-0">
            <h2 class="h3 mb-1">
                <i class="fas fa-user-plus text-primary me-2"></i>
                Create your Cloudlog account
            </h2>
            <p class="text-muted mb-0">Sign up with your callsign to get started.</p>
        </div>
        <div class="card-body pt-3">
            <?php $this->load->view('layout/messages'); ?>

            <?php echo form_open(site_url('user/signup'), ['novalidate' => 'novalidate']); ?>
                <?php $this->form_validation->set_error_delimiters('', ''); ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_username'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="user_name" autocomplete="username" class="form-control <?php echo form_error('user_name') || isset($username_error) ? 'is-invalid' : '';?>" value="<?php echo set_value('user_name'); ?>" required>
                            <div class="invalid-feedback"><?php echo form_error('user_name') ?: (isset($username_error) ? $username_error : ''); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_email_address'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="user_email" autocomplete="email" class="form-control <?php echo form_error('user_email') || isset($email_error) ? 'is-invalid' : '';?>" value="<?php echo set_value('user_email'); ?>" required>
                            <div class="invalid-feedback"><?php echo form_error('user_email') ?: (isset($email_error) ? $email_error : ''); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_password'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="passwordInput" type="password" name="user_password" autocomplete="new-password" class="form-control <?php echo form_error('user_password') || isset($password_error) ? 'is-invalid' : '';?>" required>
                            <div class="invalid-feedback"><?php echo form_error('user_password') ?: (isset($password_error) ? $password_error : ''); ?></div>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <div id="pwStrengthBar" class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small id="pwStrengthText" class="text-muted">Start typing a password…</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_confirm_password'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                            <input id="passwordConfirmInput" type="password" name="user_password_confirm" autocomplete="new-password" class="form-control <?php echo form_error('user_password_confirm') ? 'is-invalid' : '';?>" required>
                            <div class="invalid-feedback"><?php echo form_error('user_password_confirm'); ?></div>
                        </div>
                        <small id="pwConfirmText" class="text-muted"></small>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_first_name'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                            <input type="text" name="user_firstname" autocomplete="given-name" class="form-control <?php echo form_error('user_firstname') ? 'is-invalid' : '';?>" value="<?php echo set_value('user_firstname'); ?>" required>
                            <div class="invalid-feedback"><?php echo form_error('user_firstname'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_last_name'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                            <input type="text" name="user_lastname" autocomplete="family-name" class="form-control <?php echo form_error('user_lastname') ? 'is-invalid' : '';?>" value="<?php echo set_value('user_lastname'); ?>" required>
                            <div class="invalid-feedback"><?php echo form_error('user_lastname'); ?></div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_callsign'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-broadcast-tower"></i></span>
                            <input type="text" name="user_callsign" class="form-control text-uppercase <?php echo form_error('user_callsign') ? 'is-invalid' : '';?>" style="text-transform: uppercase;" value="<?php echo set_value('user_callsign'); ?>" required>
                            <div class="invalid-feedback"><?php echo form_error('user_callsign'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><strong><?php echo lang('account_gridsquare'); ?></strong></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="user_locator" class="form-control <?php echo form_error('user_locator') ? 'is-invalid' : '';?>" value="<?php echo set_value('user_locator'); ?>" placeholder="e.g. IO87JP">
                            <div class="invalid-feedback"><?php echo form_error('user_locator'); ?></div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label"><strong><?php echo lang('account_timezone'); ?></strong></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        <?php $tz_selected = set_value('user_timezone'); if ($tz_selected === '') { $tz_selected = '151'; } ?>
                        <select name="user_timezone" class="form-select <?php echo form_error('user_timezone') ? 'is-invalid' : '';?>" required>
                            <option value="" disabled>Choose your timezone</option>
                            <?php foreach ($timezones as $id => $name) { ?>
                                <option value="<?php echo $id; ?>" <?php echo ((string)$id === (string)$tz_selected) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback"><?php echo form_error('user_timezone'); ?></div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <a href="<?php echo site_url('user/login'); ?>" class="text-decoration-none">Already have an account? Log in</a>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-user-plus me-1"></i> <?php echo lang('account_create_account'); ?> →</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function(){
        function scorePassword(pw){
            if(!pw) return 0;
            let score = 0;
            const len = pw.length;
            score += Math.min(10, len) * 6; // up to 60
            let varieties = 0;
            if(/[a-z]/.test(pw)) varieties++;
            if(/[A-Z]/.test(pw)) varieties++;
            if(/\d/.test(pw)) varieties++;
            if(/[^A-Za-z0-9]/.test(pw)) varieties++;
            score += (varieties - 1) * 10; // up to 30
            if(len >= 12 && varieties >= 3) score += 10; // bonus
            if(/(.)\1{2,}/.test(pw)) score -= 10; // repeated chars penalty
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

        function refresh(){
            var val = pw.value || '';
            var s = scorePassword(val);
            var meta = labelFor(s);
            bar.style.width = s + '%';
            bar.className = 'progress-bar ' + meta.cls;
            text.textContent = (val.length ? meta.label + ' password' : 'Start typing a password…');

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
