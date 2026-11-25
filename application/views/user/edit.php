<?php
$this->load->helper('form');

$is_add = isset($user_add);
$is_admin = ($this->session->userdata('user_type') == 99);
$is_self = ($this->session->userdata('user_id') == $this->uri->segment(3));

// Safe defaults for add/edit parity
$user_type = isset($user_type) ? $user_type : 3;
$user_stylesheet = isset($user_stylesheet) ? $user_stylesheet : 'default';
$user_timezone = isset($user_timezone) ? $user_timezone : '151';
$user_date_format = isset($user_date_format) ? $user_date_format : 'd/m/y';
$user_measurement_base = isset($user_measurement_base) ? $user_measurement_base : 'M';
$user_show_notes = isset($user_show_notes) ? $user_show_notes : '0';
$user_quicklog = isset($user_quicklog) ? $user_quicklog : '0';
$user_quicklog_enter = isset($user_quicklog_enter) ? $user_quicklog_enter : '0';
$user_default_band = isset($user_default_band) ? $user_default_band : 'All';
$user_default_confirmation = isset($user_default_confirmation) ? $user_default_confirmation : '';
$user_column1 = isset($user_column1) ? $user_column1 : 'Mode';
$user_column2 = isset($user_column2) ? $user_column2 : 'RSTS';
$user_column3 = isset($user_column3) ? $user_column3 : 'RSTR';
$user_column4 = isset($user_column4) ? $user_column4 : 'Band';
$user_column5 = isset($user_column5) ? $user_column5 : 'Country';
$user_qso_end_times = isset($user_qso_end_times) ? $user_qso_end_times : '0';
$user_show_profile_image = isset($user_show_profile_image) ? $user_show_profile_image : '0';
$user_qth_lookup = isset($user_qth_lookup) ? $user_qth_lookup : '0';
$user_sota_lookup = isset($user_sota_lookup) ? $user_sota_lookup : '0';
$user_wwff_lookup = isset($user_wwff_lookup) ? $user_wwff_lookup : '0';
$user_pota_lookup = isset($user_pota_lookup) ? $user_pota_lookup : '0';
$user_previous_qsl_type = isset($user_previous_qsl_type) ? $user_previous_qsl_type : '0';
$user_hamsat_workable_only = isset($user_hamsat_workable_only) ? $user_hamsat_workable_only : '0';
$user_amsat_status_upload = isset($user_amsat_status_upload) ? $user_amsat_status_upload : '0';
$user_oscarwatch_status_upload = isset($user_oscarwatch_status_upload) ? $user_oscarwatch_status_upload : '0';
$user_force_amsat_status_upload = isset($user_force_amsat_status_upload) ? $user_force_amsat_status_upload : '0';
$user_qso_webhook_enabled = isset($user_qso_webhook_enabled) ? $user_qso_webhook_enabled : '0';
$user_winkey = isset($user_winkey) ? $user_winkey : '0';
$user_winkey_websocket = isset($user_winkey_websocket) ? $user_winkey_websocket : '0';
$user_remote_operation = isset($user_remote_operation) ? $user_remote_operation : '0';

$dashboard_upcoming_dx_card = !empty($dashboard_upcoming_dx_card);
$dashboard_dxpedition_sat_worked = !empty($dashboard_dxpedition_sat_worked);
$dashboard_qslcard_card = !empty($dashboard_qslcard_card);
$dashboard_eqslcard_card = !empty($dashboard_eqslcard_card);
$dashboard_lotw_card = !empty($dashboard_lotw_card);
$dashboard_vuccgrids_card = !empty($dashboard_vuccgrids_card);
$dashboard_map_greyline = !isset($dashboard_map_greyline) || $dashboard_map_greyline;
$menu_show_qsl_cards = !isset($menu_show_qsl_cards) || $menu_show_qsl_cards;
$menu_show_sstv_images = !empty($menu_show_sstv_images);

if (!isset($qso_fields)) {
	$qso_fields = [];
}
$qso_fields = array_merge([
	'rst' => true, 'name' => true, 'qth' => true, 'locator' => true, 'comment' => true,
	'station_tab' => true, 'freq_tx' => true, 'freq_rx' => true, 'band_rx' => true,
	'transmit_power' => true, 'operator_callsign' => true,
	'general_tab' => true, 'iota' => true, 'sota' => true, 'wwff' => true, 'pota' => true,
	'sig' => true, 'dok' => true, 'usa_state' => true,
	'satellite_tab' => true, 'notes_tab' => true, 'qsl_tab' => true,
	'dxcluster_tab' => true,
], $qso_fields);

$logbook_column_base_options = [
	'Band' => lang('gen_hamradio_band'),
	'Frequency' => lang('gen_hamradio_frequency'),
	'Mode' => lang('gen_hamradio_mode'),
	'RSTS' => lang('gen_hamradio_rsts'),
	'RSTR' => lang('gen_hamradio_rstr'),
	'Country' => lang('general_word_country'),
	'IOTA' => lang('gen_hamradio_iota'),
	'SOTA' => lang('gen_hamradio_sota'),
	'WWFF' => lang('gen_hamradio_wwff'),
	'POTA' => lang('gen_hamradio_pota'),
	'State' => lang('gen_hamradio_state'),
	'Grid' => lang('gen_hamradio_gridsquare'),
	'Distance' => lang('gen_hamradio_distance'),
	'Operator' => lang('gen_hamradio_operator'),
	'Name' => lang('general_word_name'),
	'Flag' => lang('general_word_flag'),
];

$logbook_column5_options = ['' => '— None —'];
foreach ($logbook_column_base_options as $opt_key => $opt_label) {
	if ($opt_key === 'Flag') {
		$logbook_column5_options['Location'] = lang('cloudlog_station_profile');
	}
	$logbook_column5_options[$opt_key] = $opt_label;
}

$logbook_columns_config = [
	['id' => 'column1', 'name' => 'user_column1', 'label' => lang('account_column1_text'), 'value' => $user_column1, 'options' => $logbook_column_base_options],
	['id' => 'column2', 'name' => 'user_column2', 'label' => lang('account_column2_text'), 'value' => $user_column2, 'options' => $logbook_column_base_options],
	['id' => 'column3', 'name' => 'user_column3', 'label' => lang('account_column3_text'), 'value' => $user_column3, 'options' => $logbook_column_base_options],
	['id' => 'column4', 'name' => 'user_column4', 'label' => lang('account_column4_text'), 'value' => $user_column4, 'options' => $logbook_column_base_options],
	['id' => 'column5', 'name' => 'user_column5', 'label' => lang('account_column5_text'), 'value' => $user_column5, 'options' => $logbook_column5_options],
];

$submit_label = $is_add ? lang('account_create_account') : lang('account_save_account_changes');
?>

<style>
	.user-settings-page {
		padding-top: 1.5rem;
		padding-bottom: 3rem;
	}

	.user-settings-header {
		border-bottom: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.25));
		padding-bottom: 1rem;
		margin-bottom: 1.5rem;
	}

	.user-settings-nav {
		gap: 0.25rem;
	}

	.user-settings-nav .nav-link {
		display: flex;
		align-items: center;
		gap: 0.65rem;
		padding: 0.65rem 0.9rem;
		border-radius: 0.5rem;
		color: var(--bs-body-color, inherit);
		font-weight: 500;
		font-size: 0.925rem;
		text-align: left;
		border: 1px solid transparent;
		transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
		white-space: nowrap;
	}

	.user-settings-nav .nav-link i {
		width: 1.15rem;
		text-align: center;
		opacity: 0.75;
		flex-shrink: 0;
	}

	.user-settings-nav .nav-link:hover {
		background-color: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.08);
	}

	.user-settings-nav .nav-link.active {
		background-color: var(--bs-primary, #0d6efd);
		color: #fff;
		font-weight: 600;
	}

	.user-settings-nav .nav-link.active i {
		opacity: 1;
	}

	.user-settings-nav .nav-error-dot {
		width: 0.5rem;
		height: 0.5rem;
		border-radius: 50%;
		background-color: var(--bs-danger, #dc3545);
		margin-left: auto;
		display: none;
		flex-shrink: 0;
	}

	.user-settings-nav .nav-link.has-error .nav-error-dot {
		display: inline-block;
	}

	.user-settings-nav .nav-link.active.has-error .nav-error-dot {
		background-color: #fff;
	}

	@media (min-width: 992px) {
		.user-settings-sidebar {
			position: sticky;
			top: 1.25rem;
		}
	}

	@media (max-width: 991.98px) {
		.user-settings-nav {
			flex-direction: row !important;
			flex-wrap: wrap;
			gap: 0.4rem;
		}

		.user-settings-nav .nav-link {
			border: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.25));
			padding: 0.42rem 0.72rem;
			font-size: 0.84rem;
		}
	}

	.user_edit .settings-pane-header {
		margin-bottom: 1rem;
	}

	.user_edit .settings-pane-header h4 {
		font-size: 1.2rem;
		font-weight: 600;
		margin-bottom: 0.2rem;
	}

	.user_edit .card {
		border-radius: 0.5rem;
		margin-bottom: 0;
	}

	.user_edit .card-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 0.5rem;
		font-weight: 600;
		font-size: 0.95rem;
		padding: 0.75rem 1.15rem;
	}

	.user_edit .card-header .card-header-title {
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}

	.user_edit .card-header .card-header-title i {
		opacity: 0.7;
		font-size: 0.9rem;
	}

	.user_edit .card-body {
		padding: 1.15rem;
	}

	.user_edit .form-label {
		font-size: 0.875rem;
		font-weight: 600;
		margin-bottom: 0.35rem;
	}

	.user_edit .form-text {
		font-size: 0.8rem;
		margin-top: 0.3rem;
		display: block;
	}

	.user_edit .form-check-input {
		border-color: rgba(108, 117, 125, 0.6);
	}

	.user_edit .form-check-input:checked {
		border-color: var(--bs-primary, #0d6efd);
	}

	.user_edit .input-group .btn-pwd-showhide,
	.user_edit .input-group .btn-pwd-showhide:hover,
	.user_edit .input-group .btn-outline-secondary {
		border-color: var(--bs-border-color, #ced4da);
	}

	.user_edit .setting-switch-row {
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 1rem;
		padding: 0.75rem 0;
		border-bottom: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.18));
	}

	.user_edit .setting-switch-row:first-child {
		padding-top: 0.15rem;
	}

	.user_edit .setting-switch-row:last-child {
		border-bottom: 0;
		padding-bottom: 0.15rem;
	}

	.user_edit .setting-switch-row .form-check.form-switch {
		padding-left: 0;
		margin-bottom: 0;
		flex-shrink: 0;
	}

	.user_edit .setting-switch-row .form-check.form-switch .form-check-input {
		margin-left: 0;
		margin-top: 0.15rem;
		width: 2.4rem;
		height: 1.25rem;
		cursor: pointer;
	}

	.user_edit .setting-switch-label {
		font-size: 0.9rem;
		font-weight: 600;
		margin-bottom: 0;
		cursor: pointer;
		display: block;
	}

	.user_edit .setting-switch-desc {
		font-size: 0.8rem;
		margin-top: 0.15rem;
		margin-bottom: 0;
	}

	.user_edit .qsl-method-pill {
		display: inline-flex;
		align-items: center;
		gap: 0.5rem;
		padding: 0.45rem 0.85rem;
		border: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.35));
		border-radius: 0.4rem;
		cursor: pointer;
		font-size: 0.875rem;
		font-weight: 500;
		user-select: none;
		transition: border-color 0.15s ease, background-color 0.15s ease;
	}

	.user_edit .qsl-method-pill:hover {
		border-color: var(--bs-primary, #0d6efd);
		background-color: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.06);
	}

	.user_edit .qsl-method-pill:has(.form-check-input:checked) {
		border-color: var(--bs-primary, #0d6efd);
		background-color: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.08);
	}

	.user_edit .qsl-method-pill .form-check-input {
		margin: 0;
		cursor: pointer;
		border-width: 1.5px;
		border-color: rgba(108, 117, 125, 0.65);
	}

	.user_edit .map-marker-row {
		padding: 0.7rem 0;
		border-bottom: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.18));
	}

	.user_edit .map-marker-row:last-of-type {
		border-bottom: 0;
	}

	.user_edit .map-icon-picker {
		position: relative;
	}

	.user_edit .map-icon-picker .icon_overSelect {
		cursor: pointer;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		min-height: 2.375rem;
		display: flex;
		align-items: center;
		gap: 0.4rem;
	}

	.user_edit .map-icon-picker .icon_selectBox_data {
		position: absolute;
		top: calc(100% + 2px);
		left: 0;
		right: 0;
		width: 100%;
		border-radius: 0.375rem;
		box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
		padding: 0.35rem;
		z-index: 100;
	}

	.user_edit .map-icon-picker .icon_selectBox_data .icon_option {
		display: block;
		width: 100%;
		border: 0;
		background: transparent;
		color: inherit;
		text-align: center;
		padding: 0.4rem 0.5rem;
		border-radius: 0.25rem;
		font-weight: 500;
		cursor: pointer;
	}

	.user_edit .map-icon-picker .icon_selectBox_data .icon_option:hover,
	.user_edit .map-icon-picker .icon_selectBox_data .icon_option:focus {
		background-color: var(--bs-primary, #0d6efd);
		color: #fff;
		outline: none;
	}

	.user_edit .user_icon_color {
		width: 3.25rem;
		height: 2.375rem;
		padding: 0.25rem !important;
		cursor: pointer;
	}

	.user_edit .qso-child-group {
		transition: opacity 0.2s ease;
	}

	.user_edit .qso-child-group.is-muted {
		opacity: 0.48;
	}

	.user_edit .qso-master-toggle {
		padding: 0.65rem 0.85rem;
		border-radius: 0.4rem;
		background-color: rgba(var(--bs-primary-rgb, 13, 110, 253), 0.06);
		border: 1px solid rgba(var(--bs-primary-rgb, 13, 110, 253), 0.2);
		margin-bottom: 0.85rem;
	}

	.user_edit .qso-child-list .form-check {
		padding-top: 0.3rem;
		padding-bottom: 0.3rem;
	}

	.user_edit .qso-child-list .form-check-label {
		font-weight: 500;
		font-size: 0.88rem;
		cursor: pointer;
	}

	.user-settings-footer {
		margin-top: 1.5rem;
		padding-top: 1rem;
		border-top: 1px solid var(--bs-border-color, rgba(127, 127, 127, 0.25));
	}

	#unsavedChangesBadge {
		display: none;
	}

	#unsavedChangesBadge.is-visible {
		display: inline-flex;
	}
</style>

<div class="container user-settings-page">
	<form id="user-edit-form" method="post" action="<?php echo $user_form_action; ?>" name="users" autocomplete="off">
		<div class="user-settings-header d-flex flex-wrap align-items-center justify-content-between gap-3">
			<div>
				<h2 class="h3 mb-1 d-flex align-items-center flex-wrap gap-2">
					<?php if ($is_add) {
						echo lang('account_create_user_account');
					} else {
						echo lang('account_edit_account');
						if (isset($user_name) && $user_name !== '') {
							echo ' <span class="badge text-bg-secondary fw-normal fs-6">' . htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') . '</span>';
						}
					} ?>
				</h2>
				<p class="text-muted mb-0 small">
					<?php echo $is_add ? 'Configure credentials, preferences, and logging defaults for the new account.' : 'Manage profile details, appearance, logging defaults, third-party integrations, and hardware.'; ?>
				</p>
			</div>
			<div class="d-flex align-items-center gap-2">
				<span id="unsavedChangesBadge" class="badge text-bg-warning align-items-center gap-1 py-2 px-2">
					<i class="fas fa-circle fa-2xs"></i> Unsaved changes
				</span>
				<button type="submit" class="btn btn-primary">
					<i class="fas fa-save me-1"></i> <?php echo $submit_label; ?>
				</button>
			</div>
		</div>

		<?php if ($this->session->flashdata('success')) { ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<i class="fas fa-check-circle me-1"></i>
				<?php echo $this->session->flashdata('success'); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php } ?>

		<?php if ($this->session->flashdata('notice')) { ?>
			<div class="alert alert-info alert-dismissible fade show" role="alert">
				<i class="fas fa-info-circle me-1"></i>
				<?php echo $this->session->flashdata('notice'); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php } ?>

		<?php if ($this->session->flashdata('message')) { ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<i class="fas fa-exclamation-triangle me-1"></i>
				<?php echo $this->session->flashdata('message'); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php } ?>

		<?php if (validation_errors() || isset($usertype_error)) { ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<ul class="mb-0 ps-3">
					<?php echo validation_errors('<li>', '</li>'); ?>
					<?php if (isset($usertype_error) && !form_error('user_type')) { ?>
						<li><?php echo $usertype_error; ?></li>
					<?php } ?>
				</ul>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php } ?>

		<div class="row g-4">
			<!-- Left Sidebar Navigation -->
			<div class="col-lg-3">
				<div class="user-settings-sidebar">
					<nav class="nav flex-column user-settings-nav" id="settings-nav" aria-label="Account settings sections">
						<button type="button" class="nav-link active" data-target="panelsStayOpen-B_user_general">
							<i class="fas fa-user-circle"></i>
							<span><?php echo lang('account_general_information'); ?></span>
							<span class="nav-error-dot" aria-hidden="true"></span>
						</button>
						<button type="button" class="nav-link" data-target="panelsStayOpen-B_cloudlog_general">
							<i class="fas fa-sliders-h"></i>
							<span><?php echo lang('account_cloudlog_preferences'); ?></span>
							<span class="nav-error-dot" aria-hidden="true"></span>
						</button>
						<button type="button" class="nav-link" data-target="panelsStayOpen-B_default_value">
							<i class="fas fa-list-alt"></i>
							<span><?php echo lang('account_default_values'); ?></span>
							<span class="nav-error-dot" aria-hidden="true"></span>
						</button>
						<button type="button" class="nav-link" data-target="panelsStayOpen-B_confirmation_account">
							<i class="fas fa-plug"></i>
							<span><?php echo lang('account_third_party_services'); ?></span>
							<span class="nav-error-dot" aria-hidden="true"></span>
						</button>
						<button type="button" class="nav-link" data-target="panelsStayOpen-B_miscellaneous">
							<i class="fas fa-microchip"></i>
							<span>Hardware</span>
							<span class="nav-error-dot" aria-hidden="true"></span>
						</button>
						<button type="button" class="nav-link" data-target="panelsStayOpen-B_qso_form">
							<i class="fas fa-edit"></i>
							<span>QSO Form</span>
							<span class="nav-error-dot" aria-hidden="true"></span>
						</button>
					</nav>
				</div>
			</div>

			<!-- Right Content Panes -->
			<div class="col-lg-9">
				<div class="user_edit">

					<!-- ===================================================== -->
					<!-- ZONE 1 / GENERAL INFORMATION                          -->
					<!-- ===================================================== -->
					<section id="panelsStayOpen-B_user_general" class="settings-pane">
						<div class="settings-pane-header">
							<h4><?php echo lang('account_general_information'); ?></h4>
							<p class="text-muted small mb-0">Login credentials, account role, and operator identity.</p>
						</div>

						<div class="d-flex flex-column gap-3">
							<!-- Account Information -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-shield-alt"></i> <?php echo lang('account_account_information'); ?></span>
								</div>
								<div class="card-body">
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label" for="user_name"><?php echo lang('account_username'); ?></label>
											<div class="input-group">
												<span class="input-group-text"><i class="fa fa-user"></i></span>
												<input id="user_name" class="form-control<?php echo (form_error('user_name') || isset($username_error)) ? ' is-invalid' : ''; ?>" type="text" name="user_name" value="<?php echo isset($user_name) ? htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') : ''; ?>" />
											</div>
											<?php if (isset($username_error)) { ?>
												<div class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> <?php echo $username_error; ?></div>
											<?php } elseif (form_error('user_name')) { ?>
												<div class="invalid-feedback d-block"><?php echo form_error('user_name'); ?></div>
											<?php } ?>
										</div>

										<div class="col-md-6">
											<label class="form-label" for="user_email"><?php echo lang('account_email_address'); ?></label>
											<div class="input-group">
												<span class="input-group-text"><i class="fa fa-envelope"></i></span>
												<input id="user_email" class="form-control<?php echo (form_error('user_email') || isset($email_error)) ? ' is-invalid' : ''; ?>" type="email" name="user_email" value="<?php echo isset($user_email) ? htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8') : ''; ?>" />
											</div>
											<?php if (isset($email_error)) { ?>
												<div class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> <?php echo $email_error; ?></div>
											<?php } elseif (form_error('user_email')) { ?>
												<div class="invalid-feedback d-block"><?php echo form_error('user_email'); ?></div>
											<?php } ?>
										</div>

										<div class="col-md-6">
											<label class="form-label" for="user_password"><?php echo lang('account_password'); ?></label>
											<div class="input-group">
												<span class="input-group-text"><i class="fa fa-lock"></i></span>
												<input id="user_password" class="form-control<?php echo (form_error('user_password') || isset($password_error)) ? ' is-invalid' : ''; ?>" type="password" name="user_password" autocomplete="new-password" />
												<button class="btn btn-outline-secondary btn-pwd-showhide" type="button" aria-label="Toggle password visibility"><i class="fa fa-eye-slash"></i></button>
											</div>
											<?php if (isset($password_error)) { ?>
												<div class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> <?php echo $password_error; ?></div>
											<?php } elseif (form_error('user_password')) { ?>
												<div class="invalid-feedback d-block"><?php echo form_error('user_password'); ?></div>
											<?php } elseif (!$is_add) { ?>
												<small class="form-text text-muted"><i class="fa fa-info-circle"></i> <?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
											<?php } ?>
										</div>

										<div class="col-md-6">
											<label class="form-label" for="user_type"><?php echo lang('account_user_role'); ?></label>
											<?php if ($is_admin) { ?>
												<div class="input-group">
													<span class="input-group-text"><i class="fa fa-users"></i></span>
													<select id="user_type" class="form-select<?php echo (form_error('user_type') || isset($usertype_error)) ? ' is-invalid' : ''; ?>" name="user_type">
														<?php
														$levels = $this->config->item('auth_level');
														foreach ($levels as $key => $value) {
															echo '<option value="' . $key . '" ' . (($user_type == $key) ? 'selected="selected"' : '') . '>' . $value . '</option>';
														}
														?>
													</select>
												</div>
												<?php if (form_error('user_type') || isset($usertype_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo form_error('user_type') ?: $usertype_error; ?></div>
												<?php } ?>
											<?php } else {
												$l = $this->config->item('auth_level');
											?>
												<div class="input-group">
													<span class="input-group-text"><i class="fa fa-user-tag"></i></span>
													<input id="user_type" type="text" class="form-control" value="<?php echo isset($l[$user_type]) ? htmlspecialchars($l[$user_type], ENT_QUOTES, 'UTF-8') : ''; ?>" disabled>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>

							<!-- Personal & Ham Radio Side by Side -->
							<div class="row g-3">
								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-id-card"></i> <?php echo lang('account_personal_information'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_firstname"><?php echo lang('account_first_name'); ?></label>
												<input id="user_firstname" class="form-control<?php echo (form_error('user_firstname') || isset($firstname_error)) ? ' is-invalid' : ''; ?>" type="text" name="user_firstname" value="<?php echo isset($user_firstname) ? htmlspecialchars($user_firstname, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<?php if (isset($firstname_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $firstname_error; ?></div>
												<?php } elseif (form_error('user_firstname')) { ?>
													<div class="invalid-feedback d-block"><?php echo form_error('user_firstname'); ?></div>
												<?php } ?>
											</div>

											<div class="mb-0">
												<label class="form-label" for="user_lastname"><?php echo lang('account_last_name'); ?></label>
												<input id="user_lastname" class="form-control<?php echo (form_error('user_lastname') || isset($lastname_error)) ? ' is-invalid' : ''; ?>" type="text" name="user_lastname" value="<?php echo isset($user_lastname) ? htmlspecialchars($user_lastname, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<?php if (isset($lastname_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $lastname_error; ?></div>
												<?php } elseif (form_error('user_lastname')) { ?>
													<div class="invalid-feedback d-block"><?php echo form_error('user_lastname'); ?></div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-broadcast-tower"></i> <?php echo lang('account_hamradio_information'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_callsign"><?php echo lang('account_callsign'); ?></label>
												<input id="user_callsign" class="form-control<?php echo (form_error('user_callsign') || isset($callsign_error)) ? ' is-invalid' : ''; ?>" type="text" name="user_callsign" value="<?php echo isset($user_callsign) ? htmlspecialchars($user_callsign, ENT_QUOTES, 'UTF-8') : ''; ?>" style="text-transform: uppercase;" />
												<?php if (form_error('user_callsign') || isset($callsign_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo form_error('user_callsign') ?: $callsign_error; ?></div>
												<?php } ?>
											</div>

											<div class="mb-0">
												<label class="form-label" for="user_locator"><?php echo lang('account_gridsquare'); ?></label>
												<input id="user_locator" class="form-control<?php echo (form_error('user_locator') || isset($locator_error)) ? ' is-invalid' : ''; ?>" type="text" name="user_locator" value="<?php echo isset($user_locator) ? htmlspecialchars($user_locator, ENT_QUOTES, 'UTF-8') : ''; ?>" style="text-transform: uppercase;" />
												<?php if (form_error('user_locator') || isset($locator_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo form_error('user_locator') ?: $locator_error; ?></div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>

					<!-- ===================================================== -->
					<!-- ZONE 2 / CLOUDLOG PREFERENCES                         -->
					<!-- ===================================================== -->
					<section id="panelsStayOpen-B_cloudlog_general" class="settings-pane d-none">
						<div class="settings-pane-header">
							<h4><?php echo lang('account_cloudlog_preferences'); ?></h4>
							<p class="text-muted small mb-0">Theme, regional formatting, dashboard widgets, navigation menu, and map markers.</p>
						</div>

						<div class="d-flex flex-column gap-3">
							<!-- General Preferences -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-palette"></i> <?php echo lang('general_word_general'); ?></span>
								</div>
								<div class="card-body">
									<div class="row g-3">
										<?php $normalized_user_theme = $this->optionslib->normalize_theme($user_stylesheet); ?>
										<div class="<?php echo $this->config->item('cl_multilanguage') ? 'col-md-6' : 'col-12'; ?>">
											<label class="form-label d-block">Appearance</label>
											<div class="btn-group w-100" role="group" aria-label="Appearance mode">
												<input type="radio" class="btn-check" name="user_stylesheet" id="user_stylesheet_light" value="default" <?php echo ($normalized_user_theme === 'default') ? 'checked' : ''; ?> required>
												<label class="btn btn-outline-primary" for="user_stylesheet_light"><i class="fas fa-sun me-1"></i> Light</label>

												<input type="radio" class="btn-check" name="user_stylesheet" id="user_stylesheet_dark" value="darkly" <?php echo ($normalized_user_theme === 'darkly') ? 'checked' : ''; ?> required>
												<label class="btn btn-outline-primary" for="user_stylesheet_dark"><i class="fas fa-moon me-1"></i> Dark</label>
											</div>
											<small class="form-text text-muted">Choose Light or Dark mode for your Cloudlog interface.</small>
										</div>

										<?php if ($this->config->item('cl_multilanguage')) { ?>
											<div class="col-md-6">
												<label class="form-label" for="language"><?php echo lang('account_cloudlog_language'); ?></label>
												<?php
												$options = [];
												foreach ($existing_languages as $lang_item) {
													$options[$lang_item] = ucfirst($lang_item);
												}
												echo form_dropdown('language', $options, isset($language) ? $language : 'english', 'class="form-select" id="language" aria-describedby="languageHelp"');
												?>
												<small id="languageHelp" class="form-text text-muted"><?php echo lang('account_choose_cloudlog_language'); ?></small>
											</div>
										<?php } ?>

										<div class="col-12">
											<label class="form-label" for="user_timezone"><?php echo lang('account_timezone'); ?></label>
											<?php echo form_dropdown('user_timezone', $timezones, $user_timezone, 'class="form-select" id="user_timezone"'); ?>
										</div>

										<div class="col-md-6">
											<label class="form-label" for="SelectDateFormat"><?php echo lang('account_date_format'); ?></label>
											<select name="user_date_format" class="form-select" id="SelectDateFormat" aria-describedby="SelectDateFormatHelp">
												<option value="d/m/y" <?php if ($user_date_format == "d/m/y") echo 'selected="selected"'; ?>><?php echo date('d/m/y'); ?></option>
												<option value="d/m/Y" <?php if ($user_date_format == "d/m/Y") echo 'selected="selected"'; ?>><?php echo date('d/m/Y'); ?></option>
												<option value="m/d/y" <?php if ($user_date_format == "m/d/y") echo 'selected="selected"'; ?>><?php echo date('m/d/y'); ?></option>
												<option value="m/d/Y" <?php if ($user_date_format == "m/d/Y") echo 'selected="selected"'; ?>><?php echo date('m/d/Y'); ?></option>
												<option value="d.m.Y" <?php if ($user_date_format == "d.m.Y") echo 'selected="selected"'; ?>><?php echo date('d.m.Y'); ?></option>
												<option value="y/m/d" <?php if ($user_date_format == "y/m/d") echo 'selected="selected"'; ?>><?php echo date('y/m/d'); ?></option>
												<option value="Y-m-d" <?php if ($user_date_format == "Y-m-d") echo 'selected="selected"'; ?>><?php echo date('Y-m-d'); ?></option>
												<option value="M d, Y" <?php if ($user_date_format == "M d, Y") echo 'selected="selected"'; ?>><?php echo date('M d, Y'); ?></option>
												<option value="M d, y" <?php if ($user_date_format == "M d, y") echo 'selected="selected"'; ?>><?php echo date('M d, y'); ?></option>
											</select>
											<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_select_how_you_would_like_dates_shown_when_logged_into_your_account'); ?></small>
										</div>

										<div class="col-md-6">
											<label class="form-label" for="user_measurement_base"><?php echo lang('account_measurement_preferences'); ?></label>
											<select class="form-select" id="user_measurement_base" name="user_measurement_base" aria-describedby="user_measurement_base_Help" required>
												<option value=""></option>
												<option value="K" <?php if ($user_measurement_base == "K") echo 'selected="selected"'; ?>>Kilometers</option>
												<option value="M" <?php if ($user_measurement_base == "M") echo 'selected="selected"'; ?>>Miles</option>
												<option value="N" <?php if ($user_measurement_base == "N") echo 'selected="selected"'; ?>>Nautical miles</option>
											</select>
											<small id="user_measurement_base_Help" class="form-text text-muted"><?php echo lang('account_choose_which_unit_distances_will_be_shown_in'); ?></small>
										</div>
									</div>
								</div>
							</div>

							<!-- Dashboard Options -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-tachometer-alt"></i> Dashboard Options</span>
								</div>
								<div class="card-body">
									<div class="row g-2">
										<div class="col-md-6">
											<div class="form-check form-switch py-1">
												<input name="user_dashboard_enable_dxpedition_card" class="form-check-input" type="checkbox" role="switch" id="DashboardUpcomingDXpeditionCheck" <?php if ($dashboard_upcoming_dx_card) echo 'checked'; ?>>
												<label class="form-check-label" for="DashboardUpcomingDXpeditionCheck">Enable Upcoming DXPedition Card</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch py-1">
												<input name="user_dashboard_dxpedition_sat_worked" class="form-check-input" type="checkbox" role="switch" id="DashboardDxpeditionSatWorkedCheck" <?php if ($dashboard_dxpedition_sat_worked) echo 'checked'; ?>>
												<label class="form-check-label" for="DashboardDxpeditionSatWorkedCheck">Count Satellite QSOs as Worked for DXPeditions</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch py-1">
												<input name="user_dashboard_enable_qslcards_card" class="form-check-input" type="checkbox" role="switch" id="DashboardQSLCardCheck" <?php if ($dashboard_qslcard_card) echo 'checked'; ?>>
												<label class="form-check-label" for="DashboardQSLCardCheck">Enable QSL Cards Card</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch py-1">
												<input name="user_dashboard_enable_eqslcards_card" class="form-check-input" type="checkbox" role="switch" id="DashboardeQSLCardCheck" <?php if ($dashboard_eqslcard_card) echo 'checked'; ?>>
												<label class="form-check-label" for="DashboardeQSLCardCheck">Enable eQSL Cards Card</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch py-1">
												<input name="user_dashboard_enable_lotw_card" class="form-check-input" type="checkbox" role="switch" id="DashboardlotwCardCheck" <?php if ($dashboard_lotw_card) echo 'checked'; ?>>
												<label class="form-check-label" for="DashboardlotwCardCheck">Enable Logbook of the World Card</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch py-1">
												<input name="user_dashboard_enable_vuccgrids_card" class="form-check-input" type="checkbox" role="switch" id="DashboardvuccgridsCardCheck" <?php if ($dashboard_vuccgrids_card) echo 'checked'; ?>>
												<label class="form-check-label" for="DashboardvuccgridsCardCheck">Enable VUCC-Grids Card</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch py-1">
												<input name="user_dashboard_enable_map_greyline" class="form-check-input" type="checkbox" role="switch" id="DashboardMapGreylineCheck" <?php if ($dashboard_map_greyline) echo 'checked'; ?>>
												<label class="form-check-label" for="DashboardMapGreylineCheck">Enable Dashboard Map Greyline Layer</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Menu Options -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-bars"></i> <?php echo lang('account_main_menu'); ?></span>
								</div>
								<div class="card-body">
									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="shownotes"><?php echo lang('account_show_notes_in_the_main_menu'); ?></label>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_show_notes" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="shownotes" name="user_show_notes" value="1" <?php if ($user_show_notes == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="quicklog"><?php echo lang('account_quicklog_feature'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_quicklog_feature_hint'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_quicklog" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="quicklog" name="user_quicklog" value="1" <?php if ($user_quicklog == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="py-3 border-bottom">
										<div class="row align-items-center g-2">
											<div class="col-md-7">
												<label class="form-label mb-0" for="quicklog_enter"><?php echo lang('account_quicklog_enter'); ?></label>
												<small id="quicklogEnterHelp" class="form-text text-muted mt-1"><?php echo lang('account_quicklog_enter_hint'); ?></small>
											</div>
											<div class="col-md-5">
												<select class="form-select" id="quicklog_enter" name="user_quicklog_enter" aria-describedby="quicklogEnterHelp">
													<option value="0" <?php if ($user_quicklog_enter == 0) echo 'selected="selected"'; ?>><?php echo lang('account_quicklog_enter_log'); ?></option>
													<option value="1" <?php if ($user_quicklog_enter == 1) echo 'selected="selected"'; ?>><?php echo lang('account_quicklog_enter_search'); ?></option>
												</select>
											</div>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="ShowQslCardsInMenuCheck">Show "View QSL Cards" in Logbook menu</label>
											<p class="setting-switch-desc text-muted">Display the QSL Cards shortcut in the Logbook dropdown menu.</p>
										</div>
										<div class="form-check form-switch">
											<input name="user_menu_show_qsl_cards" class="form-check-input" type="checkbox" role="switch" id="ShowQslCardsInMenuCheck" <?php if ($menu_show_qsl_cards) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="ShowSstvImagesInMenuCheck">Show "View SSTV Images" in Logbook menu</label>
											<p class="setting-switch-desc text-muted">Display the SSTV Images shortcut in the Logbook dropdown menu.</p>
										</div>
										<div class="form-check form-switch">
											<input name="user_menu_show_sstv_images" class="form-check-input" type="checkbox" role="switch" id="ShowSstvImagesInMenuCheck" <?php if ($menu_show_sstv_images) echo 'checked'; ?>>
										</div>
									</div>
								</div>
							</div>

							<!-- Map Settings -->
							<?php if ($is_self) { ?>
								<div class="card">
									<div class="card-header">
										<span class="card-header-title"><i class="fas fa-map-marked-alt"></i> <?php echo $this->lang->line('account_map_params'); ?></span>
									</div>
									<div class="card-body">
										<div class="row g-2 pb-2 border-bottom d-none d-sm-flex text-muted small fw-semibold">
											<div class="col-sm-5">Marker</div>
											<div class="col-sm-4"><?php echo $this->lang->line('general_word_icon'); ?></div>
											<div class="col-sm-3"><?php echo $this->lang->line('general_word_colors'); ?></div>
										</div>

										<!-- Station Marker -->
										<div class="row g-2 align-items-center map-marker-row">
											<div class="col-12 col-sm-5">
												<span class="fw-semibold small"><?php echo $this->lang->line('gen_hamradio_station'); ?></span>
											</div>
											<div class="col-8 col-sm-4">
												<div class="map-icon-picker">
													<div class="icon_selectBox" data-boxcontent="station">
														<input type="hidden" name="user_map_station_icon" value="<?php echo $user_map_station_icon; ?>">
														<div class="form-select icon_overSelect"><?php echo (($user_map_station_icon == "0") ? $this->lang->line('general_word_not_display') : ("<i class='" . $user_map_station_icon . "'></i>")); ?></div>
													</div>
													<div class="icon_selectBox_data" data-boxcontent="station">
														<?php foreach ($map_icon_select['station'] as $val) {
															echo "<button type='button' class='icon_option' data-value='" . $val . "'>" . (($val == "0") ? $this->lang->line('general_word_not_display') : ("<i class='" . $val . "'></i>")) . "</button>";
														} ?>
													</div>
												</div>
											</div>
											<div class="col-4 col-sm-3">
												<input type="color" class="form-control form-control-color user_icon_color" name="user_map_station_color" id="user_map_station_color" aria-label="Station marker color" value="<?php echo $user_map_station_color; ?>" style="<?php echo ($user_map_station_icon == "0") ? 'display:none;' : ''; ?>" data-icon="station" />
											</div>
										</div>

										<!-- QSO Default Marker -->
										<div class="row g-2 align-items-center map-marker-row">
											<div class="col-12 col-sm-5">
												<span class="fw-semibold small"><?php echo $this->lang->line('account_map_qso_by_default'); ?></span>
											</div>
											<div class="col-8 col-sm-4">
												<div class="map-icon-picker">
													<div class="icon_selectBox" data-boxcontent="qso">
														<input type="hidden" name="user_map_qso_icon" value="<?php echo $user_map_qso_icon; ?>">
														<div class="form-select icon_overSelect"><?php echo "<i class='" . $user_map_qso_icon . "'></i>"; ?></div>
													</div>
													<div class="icon_selectBox_data" data-boxcontent="qso">
														<?php foreach ($map_icon_select['qso'] as $val) {
															echo "<button type='button' class='icon_option' data-value='" . $val . "'><i class='" . $val . "'></i></button>";
														} ?>
													</div>
												</div>
											</div>
											<div class="col-4 col-sm-3">
												<input type="color" class="form-control form-control-color user_icon_color" name="user_map_qso_color" id="user_map_qso_color" aria-label="Default QSO marker color" value="<?php echo $user_map_qso_color; ?>" data-icon="qso" />
											</div>
										</div>

										<!-- QSO Confirmed Marker -->
										<div class="row g-2 align-items-center map-marker-row">
											<div class="col-12 col-sm-5">
												<span class="fw-semibold small d-block"><?php echo $this->lang->line('account_map_qso_confirm'); ?></span>
												<small class="form-text text-muted mt-0"><?php echo lang('account_map_qso_confirm_same_qso'); ?></small>
											</div>
											<div class="col-8 col-sm-4">
												<div class="map-icon-picker">
													<div class="icon_selectBox" data-boxcontent="qsoconfirm">
														<input type="hidden" name="user_map_qsoconfirm_icon" value="<?php echo $user_map_qsoconfirm_icon; ?>">
														<div class="form-select icon_overSelect"><?php echo (($user_map_qsoconfirm_icon == "0") ? $this->lang->line('general_word_no') : ("<i class='" . $user_map_qsoconfirm_icon . "'></i>")); ?></div>
													</div>
													<div class="icon_selectBox_data" data-boxcontent="qsoconfirm">
														<?php foreach ($map_icon_select['qsoconfirm'] as $val) {
															echo "<button type='button' class='icon_option' data-value='" . $val . "'>" . (($val == "0") ? $this->lang->line('general_word_no') : ("<i class='" . $val . "'></i>")) . "</button>";
														} ?>
													</div>
												</div>
											</div>
											<div class="col-4 col-sm-3">
												<input type="color" class="form-control form-control-color user_icon_color" name="user_map_qsoconfirm_color" id="user_map_qsoconfirm_color" aria-label="Confirmed QSO marker color" value="<?php echo $user_map_qsoconfirm_color; ?>" style="<?php echo ($user_map_qsoconfirm_icon == "0") ? 'display:none;' : ''; ?>" data-icon="qsoconfirm" />
											</div>
										</div>

										<div class="setting-switch-row border-top mt-2 pt-3 pb-0">
											<div>
												<label class="setting-switch-label" for="user_map_gridsquare_show"><?php echo $this->lang->line('gen_hamradio_gridsquare_show'); ?></label>
												<p class="setting-switch-desc text-muted">Overlay Maidenhead gridsquare boundaries on QSO maps.</p>
											</div>
											<div class="form-check form-switch">
												<input type="hidden" name="user_map_gridsquare_show" value="0">
												<input class="form-check-input" type="checkbox" role="switch" id="user_map_gridsquare_show" name="user_map_gridsquare_show" value="1" <?php if (isset($user_map_gridsquare_show) && $user_map_gridsquare_show == 1) echo 'checked'; ?>>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</section>

					<!-- ===================================================== -->
					<!-- ZONE 3 / DEFAULT VALUES                               -->
					<!-- ===================================================== -->
					<section id="panelsStayOpen-B_default_value" class="settings-pane d-none">
						<div class="settings-pane-header">
							<h4><?php echo lang('account_default_values'); ?></h4>
							<p class="text-muted small mb-0">Default band, confirmation preferences, logbook columns, and QSO auto-lookups.</p>
						</div>

						<div class="d-flex flex-column gap-3">
							<!-- Band, Confirmation & Previous QSL -->
							<div class="row g-3">
								<div class="col-md-7">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-check-double"></i> <?php echo lang('account_default_band_settings'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_default_band"><?php echo lang('account_gridmap_default_band'); ?></label>
												<select id="user_default_band" class="form-select" name="user_default_band">
													<option value="All">All</option>
													<?php foreach ($bands as $band) {
														echo '<option value="' . $band . '" ' . (($user_default_band == $band) ? 'selected="selected"' : '') . '>' . $band . '</option>' . "\n";
													} ?>
												</select>
											</div>

											<div>
												<span class="form-label d-block"><?php echo lang('account_qsl_settings'); ?></span>
												<div class="d-flex flex-wrap gap-2">
													<label class="qsl-method-pill" for="user_default_confirmation_qsl">
														<input class="form-check-input" type="checkbox" name="user_default_confirmation_qsl" id="user_default_confirmation_qsl" <?php if (strpos($user_default_confirmation, 'Q') !== false) echo 'checked'; ?>>
														<span><?php echo lang('gen_hamradio_qsl'); ?></span>
													</label>
													<label class="qsl-method-pill" for="user_default_confirmation_lotw">
														<input class="form-check-input" type="checkbox" name="user_default_confirmation_lotw" id="user_default_confirmation_lotw" <?php if (strpos($user_default_confirmation, 'L') !== false) echo 'checked'; ?>>
														<span><?php echo lang('lotw_short'); ?></span>
													</label>
													<label class="qsl-method-pill" for="user_default_confirmation_eqsl">
														<input class="form-check-input" type="checkbox" name="user_default_confirmation_eqsl" id="user_default_confirmation_eqsl" <?php if (strpos($user_default_confirmation, 'E') !== false) echo 'checked'; ?>>
														<span><?php echo lang('account_eqsl'); ?></span>
													</label>
													<label class="qsl-method-pill" for="user_default_confirmation_qrz">
														<input class="form-check-input" type="checkbox" name="user_default_confirmation_qrz" id="user_default_confirmation_qrz" <?php if (strpos($user_default_confirmation, 'Z') !== false) echo 'checked'; ?>>
														<span>QRZ.com</span>
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-5">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-history"></i> <?php echo lang('account_previous_qsl_type'); ?></span>
										</div>
										<div class="card-body">
											<label class="form-label" for="previousqsltype"><?php echo lang('account_select_the_type_of_qsl_to_show_in_the_previous_qsos_section'); ?></label>
											<select class="form-select" id="previousqsltype" name="user_previous_qsl_type">
												<option value="0" <?php if ($user_previous_qsl_type == 0) echo 'selected="selected"'; ?>><?php echo lang('gen_hamradio_qsl'); ?></option>
												<option value="1" <?php if ($user_previous_qsl_type == 1) echo 'selected="selected"'; ?>><?php echo lang('lotw_short'); ?></option>
												<option value="2" <?php if ($user_previous_qsl_type == 2) echo 'selected="selected"'; ?>><?php echo lang('eqsl_short'); ?></option>
												<option value="4" <?php if ($user_previous_qsl_type == 4) echo 'selected="selected"'; ?>>QRZ</option>
											</select>
										</div>
									</div>
								</div>
							</div>

							<!-- Logbook Columns -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-columns"></i> <?php echo lang('account_logbook_fields'); ?></span>
								</div>
								<div class="card-body">
									<div class="row g-3">
										<?php foreach ($logbook_columns_config as $col_cfg) { ?>
											<div class="col-sm-6 col-xl-4">
												<label class="form-label" for="<?php echo $col_cfg['id']; ?>"><?php echo $col_cfg['label']; ?></label>
												<select class="form-select" id="<?php echo $col_cfg['id']; ?>" name="<?php echo $col_cfg['name']; ?>">
													<?php foreach ($col_cfg['options'] as $opt_val => $opt_text) { ?>
														<option value="<?php echo htmlspecialchars($opt_val, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($col_cfg['value'] == $opt_val) echo 'selected="selected"'; ?>><?php echo $opt_text; ?></option>
													<?php } ?>
												</select>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>

							<!-- QSO Logging Options -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-pen-nib"></i> <?php echo lang('account_qso_logging_options'); ?></span>
								</div>
								<div class="card-body">
									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="logendtimes"><?php echo lang('account_log_end_time'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_log_end_time_hint'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_qso_end_times" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="logendtimes" name="user_qso_end_times" value="1" <?php if ($user_qso_end_times == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="profileimages"><?php echo lang('account_show_profile_picture_of_qso_partner_from_qrzcom_hamqthcom_profile_in_the_log_qso_section'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_please_set_your_qrzcom_hamqthcom_credentials_in_the_general_config_file'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_show_profile_image" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="profileimages" name="user_show_profile_image" value="1" <?php if ($user_show_profile_image == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="qthlookup"><?php echo lang('account_location_auto_lookup'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_if_set_gridsquare_is_fetched_based_on_location_name'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_qth_lookup" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="qthlookup" name="user_qth_lookup" value="1" <?php if ($user_qth_lookup == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="sotalookup"><?php echo lang('account_sota_auto_lookup_gridsquare_and_name_for_summit'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_sota_lookup" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="sotalookup" name="user_sota_lookup" value="1" <?php if ($user_sota_lookup == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="wwfflookup"><?php echo lang('account_wwff_auto_lookup_gridsquare_and_name_for_reference'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_wwff_lookup" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="wwfflookup" name="user_wwff_lookup" value="1" <?php if ($user_wwff_lookup == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="potalookup"><?php echo lang('account_pota_auto_lookup_gridsquare_and_name_for_park'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_pota_lookup" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="potalookup" name="user_pota_lookup" value="1" <?php if ($user_pota_lookup == 1) echo 'checked'; ?>>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>

					<!-- ===================================================== -->
					<!-- ZONE 4 / THIRD PARTY SERVICES                         -->
					<!-- ===================================================== -->
					<section id="panelsStayOpen-B_confirmation_account" class="settings-pane d-none">
						<div class="settings-pane-header">
							<h4><?php echo lang('account_third_party_services'); ?></h4>
							<p class="text-muted small mb-0">Connect logbook services, callbook lookups, satellite tracking, social links, and webhooks.</p>
						</div>

						<div class="d-flex flex-column gap-3">
							<!-- Row 1: LoTW + Callbook -->
							<div class="row g-3">
								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-globe"></i> <?php echo lang('account_logbook_of_the_world'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_lotw_name"><?php echo lang('account_logbook_of_the_world_lotw_username'); ?></label>
												<input id="user_lotw_name" class="form-control<?php echo isset($userlotwname_error) ? ' is-invalid' : ''; ?>" type="text" name="user_lotw_name" value="<?php echo isset($user_lotw_name) ? htmlspecialchars($user_lotw_name, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<?php if (isset($userlotwname_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $userlotwname_error; ?></div>
												<?php } ?>
											</div>

											<div class="mb-0">
												<label class="form-label" for="user_lotw_password"><?php echo lang('account_logbook_of_the_world_lotw_password'); ?></label>
												<div class="input-group">
													<input id="user_lotw_password" class="form-control<?php echo isset($lotwpassword_error) ? ' is-invalid' : ''; ?>" type="password" name="user_lotw_password" autocomplete="new-password" />
													<button class="btn btn-outline-secondary btn-pwd-showhide" type="button" aria-label="Toggle LoTW password visibility"><i class="fa fa-eye-slash"></i></button>
												</div>
												<?php if (isset($lotwpassword_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $lotwpassword_error; ?></div>
												<?php } else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-address-book"></i> Callbook</span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_callbook_type">Callbook Provider</label>
												<select id="user_callbook_type" class="form-select<?php echo isset($callbook_type_error) ? ' is-invalid' : ''; ?>" name="user_callbook_type">
													<option value="None" <?php if (!isset($user_callbook_type) || $user_callbook_type == '' || $user_callbook_type == 'None') echo 'selected'; ?>>None</option>
													<option value="QRZ" <?php if (isset($user_callbook_type) && $user_callbook_type == 'QRZ') echo 'selected'; ?>>QRZ</option>
													<option value="HamQTH" <?php if (isset($user_callbook_type) && $user_callbook_type == 'HamQTH') echo 'selected'; ?>>HamQTH</option>
												</select>
												<?php if (isset($callbook_type_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $callbook_type_error; ?></div>
												<?php } ?>
											</div>

											<div class="mb-3">
												<label class="form-label" for="user_callbook_username">Callbook Username</label>
												<input id="user_callbook_username" class="form-control<?php echo isset($callbook_username_error) ? ' is-invalid' : ''; ?>" type="text" name="user_callbook_username" value="<?php echo isset($user_callbook_username) ? htmlspecialchars($user_callbook_username, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<?php if (isset($callbook_username_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $callbook_username_error; ?></div>
												<?php } ?>
											</div>

											<div class="mb-0">
												<label class="form-label" for="user_callbook_password">Callbook Password</label>
												<div class="input-group">
													<input id="user_callbook_password" class="form-control<?php echo isset($callbook_password_error) ? ' is-invalid' : ''; ?>" type="password" name="user_callbook_password" autocomplete="new-password" />
													<button class="btn btn-outline-secondary btn-pwd-showhide" type="button" aria-label="Toggle Callbook password visibility"><i class="fa fa-eye-slash"></i></button>
												</div>
												<?php if (isset($callbook_password_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $callbook_password_error; ?></div>
												<?php } else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Row 2: eQSL + Club Log -->
							<div class="row g-3">
								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-at"></i> <?php echo lang('account_eqsl'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_eqsl_name"><?php echo lang('account_eqslcc_username'); ?></label>
												<input id="user_eqsl_name" class="form-control<?php echo isset($eqslusername_error) ? ' is-invalid' : ''; ?>" type="text" name="user_eqsl_name" value="<?php echo isset($user_eqsl_name) ? htmlspecialchars($user_eqsl_name, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<?php if (isset($eqslusername_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $eqslusername_error; ?></div>
												<?php } ?>
											</div>

											<div class="mb-0">
												<label class="form-label" for="user_eqsl_password"><?php echo lang('account_eqslcc_password'); ?></label>
												<div class="input-group">
													<input id="user_eqsl_password" class="form-control<?php echo isset($eqslpassword_error) ? ' is-invalid' : ''; ?>" type="password" name="user_eqsl_password" autocomplete="new-password" />
													<button class="btn btn-outline-secondary btn-pwd-showhide" type="button" aria-label="Toggle eQSL password visibility"><i class="fa fa-eye-slash"></i></button>
												</div>
												<?php if (isset($eqslpassword_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $eqslpassword_error; ?></div>
												<?php } else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-book"></i> <?php echo lang('account_clublog'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_clublog_name"><?php echo lang('account_clublog_email_callsign'); ?></label>
												<input id="user_clublog_name" class="form-control<?php echo (form_error('user_clublog_name') || isset($userclublogname_error)) ? ' is-invalid' : ''; ?>" type="email" name="user_clublog_name" placeholder="your@email.com" value="<?php echo isset($user_clublog_name) ? htmlspecialchars($user_clublog_name, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<small class="form-text text-muted"><?php echo lang('account_the_email_or_callsign_you_use_to_login_to_club_log'); ?></small>
												<?php if (isset($userclublogname_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $userclublogname_error; ?></div>
												<?php } elseif (form_error('user_clublog_name')) { ?>
													<div class="invalid-feedback d-block"><?php echo form_error('user_clublog_name'); ?></div>
												<?php } ?>
											</div>

											<div class="mb-0">
												<label class="form-label" for="user_clublog_password"><?php echo lang('account_clublog_password'); ?></label>
												<div class="input-group">
													<input id="user_clublog_password" class="form-control<?php echo isset($clublogpassword_error) ? ' is-invalid' : ''; ?>" type="password" name="user_clublog_password" autocomplete="new-password" />
													<button class="btn btn-outline-secondary btn-pwd-showhide" type="button" aria-label="Toggle Club Log password visibility"><i class="fa fa-eye-slash"></i></button>
												</div>
												<?php if (isset($clublogpassword_error)) { ?>
													<div class="invalid-feedback d-block"><?php echo $clublogpassword_error; ?></div>
												<?php } else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Row 3: Hams.at + Mastodon -->
							<div class="row g-3">
								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fas fa-satellite"></i> <?php echo lang('account_hamsat'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-3">
												<label class="form-label" for="user_hamsat_key"><?php echo lang('account_hamsat_private_feed_key'); ?></label>
												<input id="user_hamsat_key" class="form-control" type="text" name="user_hamsat_key" value="<?php echo isset($user_hamsat_key) ? htmlspecialchars($user_hamsat_key, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<small class="form-text text-muted"><?php echo lang('account_hamsat_hint'); ?></small>
											</div>

											<div class="setting-switch-row pb-0">
												<div>
													<label class="setting-switch-label" for="user_hamsat_workable_only"><?php echo lang('account_hamsat_workable_only'); ?></label>
													<p class="setting-switch-desc text-muted"><?php echo lang('account_hamsat_workable_only_hint'); ?></p>
												</div>
												<div class="form-check form-switch">
													<input type="hidden" name="user_hamsat_workable_only" value="0">
													<input class="form-check-input" type="checkbox" role="switch" id="user_hamsat_workable_only" name="user_hamsat_workable_only" value="1" <?php if ($user_hamsat_workable_only == 1) echo 'checked'; ?>>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="card h-100">
										<div class="card-header">
											<span class="card-header-title"><i class="fab fa-mastodon"></i> <?php echo lang('account_mastodon'); ?></span>
										</div>
										<div class="card-body">
											<div class="mb-0">
												<label class="form-label" for="user_mastodon_url"><?php echo lang('account_user_mastodon'); ?></label>
												<input id="user_mastodon_url" class="form-control" type="text" name="user_mastodon_url" placeholder="https://radiosocial.de" value="<?php echo isset($user_mastodon_url) ? htmlspecialchars($user_mastodon_url, ENT_QUOTES, 'UTF-8') : ''; ?>" />
												<small class="form-text text-muted"><?php echo lang('account_user_mastodon_hint'); ?></a></small>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Row 4: Satellite Status Upload -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-satellite-dish"></i> <?php echo lang('account_amsat_status_upload'); ?></span>
								</div>
								<div class="card-body">
									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="amsatstatusupload"><?php echo lang('account_upload_status_of_sat_qsos_to'); ?> <a href="https://status.amsat.org/" target="_blank" rel="noopener">status.amsat.org</a></label>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_amsat_status_upload" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="amsatstatusupload" name="user_amsat_status_upload" value="1" <?php if ($user_amsat_status_upload == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="oscarwatchstatusupload">Upload SAT QSO status to <a href="https://oscarwatch.org" target="_blank" rel="noopener">oscarwatch.org</a></label>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_oscarwatch_status_upload" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="oscarwatchstatusupload" name="user_oscarwatch_status_upload" value="1" <?php if ($user_oscarwatch_status_upload == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row" id="oscarwatchAmsatOverrideWrapper" style="display:none;">
										<div>
											<label class="setting-switch-label" for="forceAmsatWithOscarwatch">Force enable AMSAT Status in Cloudlog</label>
											<p class="setting-switch-desc text-muted">When enabled, Cloudlog keeps direct AMSAT uploads on even with OscarWatch enabled.</p>
										</div>
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" id="forceAmsatWithOscarwatch" name="user_force_amsat_status_upload" value="1" <?php if ((string)$user_force_amsat_status_upload === '1') echo 'checked'; ?>>
										</div>
									</div>

									<div class="alert alert-warning mt-3 mb-0 py-2 small" id="oscarwatchAmsatNotice" role="alert" style="display:none;">
										OscarWatch also forwards to AMSAT Status unless you disable forwarding in your OscarWatch account.
									</div>

									<div class="mt-3 pt-3 border-top">
										<label class="form-label" for="user_oscarwatch_token">OscarWatch API Token</label>
										<div class="input-group">
											<input class="form-control" id="user_oscarwatch_token" type="password" name="user_oscarwatch_token" autocomplete="new-password" value="<?php echo isset($user_oscarwatch_token) ? htmlspecialchars($user_oscarwatch_token, ENT_QUOTES, 'UTF-8') : ''; ?>" />
											<button class="btn btn-outline-secondary btn-pwd-showhide" type="button" aria-label="Toggle OscarWatch token visibility"><i class="fa fa-eye-slash"></i></button>
											<button class="btn btn-outline-secondary" type="button" id="testOscarwatchTokenBtn">Test Token</button>
										</div>
										<small id="oscarwatchTokenTestResult" class="form-text text-muted"></small>
										<small class="form-text text-muted">Optional. If set, SAT QSO status is also reported to <a href="https://oscarwatch.org" target="_blank" rel="noopener">oscarwatch.org</a> using your token.</small>
									</div>
								</div>
							</div>

							<!-- Row 5: QSO Webhook -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-paper-plane"></i> <?php echo lang('account_qso_webhook'); ?></span>
								</div>
								<div class="card-body">
									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="user_qso_webhook_enabled"><?php echo lang('account_qso_webhook_enabled'); ?></label>
											<p class="setting-switch-desc text-muted"><?php echo lang('account_qso_webhook_intro'); ?></p>
										</div>
										<div class="form-check form-switch">
											<input type="hidden" name="user_qso_webhook_enabled" value="0">
											<input class="form-check-input" type="checkbox" role="switch" id="user_qso_webhook_enabled" name="user_qso_webhook_enabled" value="1" <?php if ($user_qso_webhook_enabled == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="row g-3 mt-2">
										<div class="col-md-7">
											<label class="form-label" for="user_qso_webhook_url"><?php echo lang('account_qso_webhook_url'); ?></label>
											<div class="input-group">
												<input class="form-control<?php echo form_error('user_qso_webhook_url') ? ' is-invalid' : ''; ?>" type="url" id="user_qso_webhook_url" name="user_qso_webhook_url" placeholder="https://example.com/webhook" value="<?php echo isset($user_qso_webhook_url) ? htmlspecialchars($user_qso_webhook_url, ENT_QUOTES, 'UTF-8') : ''; ?>" autocomplete="off" />
												<button class="btn btn-outline-secondary" type="button" id="testQsoWebhookBtn"><?php echo lang('account_qso_webhook_send_test'); ?></button>
											</div>
											<?php if (form_error('user_qso_webhook_url')) { ?>
												<div class="invalid-feedback d-block"><?php echo form_error('user_qso_webhook_url'); ?></div>
											<?php } ?>
											<small id="qsoWebhookTestResult" class="form-text text-muted"></small>
											<small class="form-text text-muted"><?php echo lang('account_qso_webhook_url_hint'); ?></small>
										</div>

										<div class="col-md-5">
											<label class="form-label" for="user_qso_webhook_secret"><?php echo lang('account_qso_webhook_secret'); ?></label>
											<div class="input-group">
												<input class="form-control" type="password" id="user_qso_webhook_secret" name="user_qso_webhook_secret" value="" autocomplete="new-password" />
												<button class="btn btn-outline-secondary btn-pwd-showhide" type="button" aria-label="Toggle webhook secret visibility"><i class="fa fa-eye-slash"></i></button>
											</div>
											<small class="form-text text-muted"><?php echo lang('account_qso_webhook_secret_hint'); ?></small>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>

					<!-- ===================================================== -->
					<!-- ZONE 5 / HARDWARE                                     -->
					<!-- ===================================================== -->
					<section id="panelsStayOpen-B_miscellaneous" class="settings-pane d-none">
						<div class="settings-pane-header">
							<h4>Hardware</h4>
							<p class="text-muted small mb-0">Optional hardware integrations and experimental browser-based radio controls.</p>
						</div>

						<div class="d-flex flex-column gap-3">
							<!-- Winkeyer -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-keyboard"></i> <?php echo lang('account_winkeyer'); ?></span>
									<span class="badge text-bg-danger"><?php echo lang('admin_experimental'); ?></span>
								</div>
								<div class="card-body">
									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="user_winkeyer"><?php echo lang('account_winkeyer_enabled'); ?></label>
											<p class="setting-switch-desc text-muted">Enable CW keyer controls directly from the QSO logging page.</p>
										</div>
										<div class="form-check form-switch">
											<input name="user_winkey" class="form-check-input" type="checkbox" role="switch" id="user_winkeyer" value="1" <?php if ($user_winkey == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="user_winkey_websocket">Winkey Web Sockets</label>
											<p class="setting-switch-desc text-muted">Connect to your Winkeyer interface over WebSockets.</p>
										</div>
										<div class="form-check form-switch">
											<input name="user_winkey_websocket" class="form-check-input" type="checkbox" role="switch" id="user_winkey_websocket" value="1" <?php if ($user_winkey_websocket == 1) echo 'checked'; ?>>
										</div>
									</div>

									<div class="alert alert-secondary mt-3 mb-0 py-2 small">
										<i class="fas fa-info-circle me-1"></i>
										<?php echo str_replace('<br><br>', '<br>', lang('account_winkeyer_hint')); ?>
									</div>
								</div>
							</div>

							<!-- Remote Operation -->
							<div class="card">
								<div class="card-header">
									<span class="card-header-title"><i class="fas fa-headset"></i> Remote Operation</span>
									<span class="badge text-bg-danger"><?php echo lang('admin_experimental'); ?></span>
								</div>
								<div class="card-body">
									<div class="setting-switch-row">
										<div>
											<label class="setting-switch-label" for="user_remote_operation">Remote Operation</label>
											<p class="setting-switch-desc text-muted">Experimental browser-based remote audio control. Turn it on only if you want the QSO page card.</p>
										</div>
										<div class="form-check form-switch">
											<input name="user_remote_operation" class="form-check-input" type="checkbox" role="switch" id="user_remote_operation" value="1" <?php if ($user_remote_operation == 1) echo 'checked'; ?>>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>

					<!-- ===================================================== -->
					<!-- ZONE 6 / QSO FORM CUSTOMISATION                       -->
					<!-- ===================================================== -->
					<section id="panelsStayOpen-B_qso_form" class="settings-pane d-none">
						<div class="settings-pane-header">
							<h4>QSO Form</h4>
							<p class="text-muted small mb-0">Choose which fields and tabs appear in the QSO Entry form. Hide items you don't use to keep logging focused.</p>
						</div>

						<div class="row g-3">
							<div class="col-md-6">
								<div class="card h-100">
									<div class="card-header">
										<span class="card-header-title"><i class="fas fa-list-ul"></i> QSO Tab Fields</span>
									</div>
									<div class="card-body qso-child-list">
										<div class="form-check form-switch">
											<input name="qso_field_rst" class="form-check-input" type="checkbox" role="switch" id="qsoFieldRst" <?php if ($qso_fields['rst']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldRst">RST Sent &amp; Received</label>
										</div>
										<div class="form-check form-switch">
											<input name="qso_field_name" class="form-check-input" type="checkbox" role="switch" id="qsoFieldName" <?php if ($qso_fields['name']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldName">Name</label>
										</div>
										<div class="form-check form-switch">
											<input name="qso_field_qth" class="form-check-input" type="checkbox" role="switch" id="qsoFieldQth" <?php if ($qso_fields['qth']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldQth">QTH / Location</label>
										</div>
										<div class="form-check form-switch">
											<input name="qso_field_locator" class="form-check-input" type="checkbox" role="switch" id="qsoFieldLocator" <?php if ($qso_fields['locator']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldLocator">Gridsquare / Locator</label>
										</div>
										<div class="form-check form-switch">
											<input name="qso_field_comment" class="form-check-input" type="checkbox" role="switch" id="qsoFieldComment" <?php if ($qso_fields['comment']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldComment">Comment</label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="card h-100">
									<div class="card-header">
										<span class="card-header-title"><i class="fas fa-broadcast-tower"></i> Station Tab</span>
									</div>
									<div class="card-body">
										<div class="qso-master-toggle">
											<div class="form-check form-switch mb-0">
												<input name="qso_field_station_tab" class="form-check-input" type="checkbox" role="switch" id="qsoFieldStationTab" data-master-group="stationTabFields" <?php if ($qso_fields['station_tab']) echo 'checked'; ?>>
												<label class="form-check-label fw-semibold" for="qsoFieldStationTab">Show Station Tab</label>
											</div>
										</div>
										<div id="stationTabFields" class="qso-child-group qso-child-list ps-2 <?php echo !$qso_fields['station_tab'] ? 'is-muted' : ''; ?>">
											<div class="form-check form-switch">
												<input name="qso_field_freq_tx" class="form-check-input" type="checkbox" role="switch" id="qsoFieldFreqTx" <?php if ($qso_fields['freq_tx']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldFreqTx">Frequency TX</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_freq_rx" class="form-check-input" type="checkbox" role="switch" id="qsoFieldFreqRx" <?php if ($qso_fields['freq_rx']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldFreqRx">Frequency RX</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_band_rx" class="form-check-input" type="checkbox" role="switch" id="qsoFieldBandRx" <?php if ($qso_fields['band_rx']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldBandRx">Band RX</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_transmit_power" class="form-check-input" type="checkbox" role="switch" id="qsoFieldTransmitPower" <?php if ($qso_fields['transmit_power']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldTransmitPower">Transmit Power</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_operator_callsign" class="form-check-input" type="checkbox" role="switch" id="qsoFieldOperatorCallsign" <?php if ($qso_fields['operator_callsign']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldOperatorCallsign">Operator Callsign</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="card h-100">
									<div class="card-header">
										<span class="card-header-title"><i class="fas fa-award"></i> General Tab</span>
									</div>
									<div class="card-body">
										<div class="qso-master-toggle">
											<div class="form-check form-switch mb-0">
												<input name="qso_field_general_tab" class="form-check-input" type="checkbox" role="switch" id="qsoFieldGeneralTab" data-master-group="generalTabFields" <?php if ($qso_fields['general_tab']) echo 'checked'; ?>>
												<label class="form-check-label fw-semibold" for="qsoFieldGeneralTab">Show General Tab</label>
											</div>
										</div>
										<div id="generalTabFields" class="qso-child-group qso-child-list ps-2 <?php echo !$qso_fields['general_tab'] ? 'is-muted' : ''; ?>">
											<div class="form-check form-switch">
												<input name="qso_field_iota" class="form-check-input" type="checkbox" role="switch" id="qsoFieldIota" <?php if ($qso_fields['iota']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldIota">IOTA Reference</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_sota" class="form-check-input" type="checkbox" role="switch" id="qsoFieldSota" <?php if ($qso_fields['sota']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldSota">SOTA Reference</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_wwff" class="form-check-input" type="checkbox" role="switch" id="qsoFieldWwff" <?php if ($qso_fields['wwff']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldWwff">WWFF Reference</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_pota" class="form-check-input" type="checkbox" role="switch" id="qsoFieldPota" <?php if ($qso_fields['pota']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldPota">POTA Reference</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_sig" class="form-check-input" type="checkbox" role="switch" id="qsoFieldSig" <?php if ($qso_fields['sig']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldSig">SIG / SIG Info</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_dok" class="form-check-input" type="checkbox" role="switch" id="qsoFieldDok" <?php if ($qso_fields['dok']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldDok">DOK (DARC)</label>
											</div>
											<div class="form-check form-switch">
												<input name="qso_field_usa_state" class="form-check-input" type="checkbox" role="switch" id="qsoFieldUsaState" <?php if ($qso_fields['usa_state']) echo 'checked'; ?>>
												<label class="form-check-label" for="qsoFieldUsaState">USA State &amp; County</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="card h-100">
									<div class="card-header">
										<span class="card-header-title"><i class="fas fa-folder-open"></i> Other Tabs</span>
									</div>
									<div class="card-body qso-child-list">
										<div class="form-check form-switch">
											<input name="qso_field_satellite_tab" class="form-check-input" type="checkbox" role="switch" id="qsoFieldSatelliteTab" <?php if ($qso_fields['satellite_tab']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldSatelliteTab">Satellite Tab</label>
										</div>
										<div class="form-check form-switch">
											<input name="qso_field_notes_tab" class="form-check-input" type="checkbox" role="switch" id="qsoFieldNotesTab" <?php if ($qso_fields['notes_tab']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldNotesTab">Notes Tab</label>
										</div>
										<div class="form-check form-switch">
											<input name="qso_field_qsl_tab" class="form-check-input" type="checkbox" role="switch" id="qsoFieldQslTab" <?php if ($qso_fields['qsl_tab']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldQslTab">QSL Tab</label>
										</div>
										<div class="form-check form-switch">
											<input name="qso_field_dxcluster_tab" class="form-check-input" type="checkbox" role="switch" id="qsoFieldDxClusterTab" <?php if ($qso_fields['dxcluster_tab']) echo 'checked'; ?>>
											<label class="form-check-label" for="qsoFieldDxClusterTab">DX Cluster Tab</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>

				<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />

				<?php if ($is_add) { ?>
					<div class="card mt-3">
						<div class="card-body">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="send_welcome_email" id="send_welcome_email" value="1" checked>
								<label class="form-check-label" for="send_welcome_email">
									<i class="fas fa-envelope me-1"></i><strong>Send Welcome Email</strong>
								</label>
								<small class="form-text text-muted d-block mt-1">
									<i class="fas fa-info-circle"></i> Recommended: Sends an email to the user with their login credentials and instructions to change their password.
								</small>
							</div>
						</div>
					</div>
				<?php } ?>

				<div class="user-settings-footer d-flex flex-wrap align-items-center justify-content-between gap-2">
					<small class="text-muted">Changes across all tabs are saved together.</small>
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-save me-1"></i> <?php echo $submit_label; ?>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	(function() {
		const nav = document.getElementById('settings-nav');
		const form = document.getElementById('user-edit-form');
		const storageKey = 'cloudlog.userEdit.activeTab';
		if (!nav) {
			return;
		}

		const navButtons = Array.from(nav.querySelectorAll('[data-target]'));
		const panes = navButtons
			.map((button) => document.getElementById(button.dataset.target))
			.filter((pane) => pane !== null);

		let firstErrorTab = null;
		navButtons.forEach((button) => {
			const pane = document.getElementById(button.dataset.target);
			if (pane && pane.querySelector('.is-invalid, .invalid-feedback')) {
				button.classList.add('has-error');
				if (!firstErrorTab) {
					firstErrorTab = button.dataset.target;
				}
			}
		});

		function setActive(targetId, updateHash) {
			navButtons.forEach((button) => {
				const isActive = button.dataset.target === targetId;
				button.classList.toggle('active', isActive);
				button.setAttribute('aria-current', isActive ? 'page' : 'false');
			});

			panes.forEach((pane) => {
				pane.classList.toggle('d-none', pane.id !== targetId);
			});

			try {
				sessionStorage.setItem(storageKey, targetId);
			} catch (e) {}

			if (updateHash) {
				if (history.replaceState) {
					history.replaceState(null, '', '#' + targetId);
				} else {
					window.location.hash = targetId;
				}
			}
		}

		navButtons.forEach((button) => {
			button.addEventListener('click', function() {
				setActive(this.dataset.target, true);
			});
		});

		const hashTarget = window.location.hash ? window.location.hash.substring(1) : null;
		let storedTarget = null;
		try {
			storedTarget = sessionStorage.getItem(storageKey);
		} catch (e) {}

		const hasTarget = (id) => Boolean(id && navButtons.some((button) => button.dataset.target === id));
		const defaultTarget = navButtons.length > 0 ? navButtons[0].dataset.target : null;

		let initialTarget = defaultTarget;
		if (firstErrorTab) {
			initialTarget = firstErrorTab;
		} else if (hasTarget(hashTarget)) {
			initialTarget = hashTarget;
		} else if (hasTarget(storedTarget)) {
			initialTarget = storedTarget;
		}

		if (initialTarget) {
			setActive(initialTarget, false);
		}

		// Visual muting for QSO Form master tab toggles
		document.querySelectorAll('[data-master-group]').forEach((masterCheckbox) => {
			const group = document.getElementById(masterCheckbox.dataset.masterGroup);
			if (!group) {
				return;
			}
			const syncGroup = () => {
				group.classList.toggle('is-muted', !masterCheckbox.checked);
			};
			masterCheckbox.addEventListener('change', syncGroup);
			syncGroup();
		});

		// Unsaved changes indicator (compares against initial form snapshot)
		const unsavedBadge = document.getElementById('unsavedChangesBadge');
		if (form && unsavedBadge) {
			const snapshotForm = () => {
				const entries = [];
				for (const [k, v] of new FormData(form).entries()) {
					entries.push(k + '=' + v);
				}
				return entries.join('&');
			};
			let initialSnapshot = snapshotForm();
			// Re-snapshot after sibling init scripts (e.g., OscarWatch/AMSAT sync) settle
			window.addEventListener('load', () => {
				initialSnapshot = snapshotForm();
				unsavedBadge.classList.remove('is-visible');
			});
			const checkDirty = () => {
				unsavedBadge.classList.toggle('is-visible', snapshotForm() !== initialSnapshot);
			};
			form.addEventListener('input', checkDirty);
			form.addEventListener('change', checkDirty);
		}
	})();
</script>
<script>
	var oscarwatchStatusInput = document.getElementById('oscarwatchstatusupload');
	var amsatStatusInput = document.getElementById('amsatstatusupload');
	var forceAmsatToggle = document.getElementById('forceAmsatWithOscarwatch');
	var oscarwatchAmsatNotice = document.getElementById('oscarwatchAmsatNotice');
	var oscarwatchAmsatOverrideWrapper = document.getElementById('oscarwatchAmsatOverrideWrapper');

	function syncOscarwatchAmsatState(triggeredByOscarwatchToggle) {
		if (!oscarwatchStatusInput || !amsatStatusInput || !oscarwatchAmsatNotice || !oscarwatchAmsatOverrideWrapper || !forceAmsatToggle) {
			return;
		}

		var oscarwatchEnabled = oscarwatchStatusInput.checked;
		var forceAmsatEnabled = forceAmsatToggle.checked;

		if (!oscarwatchEnabled) {
			oscarwatchAmsatOverrideWrapper.style.display = 'none';
			oscarwatchAmsatNotice.style.display = 'none';
			amsatStatusInput.disabled = false;
			return;
		}

		oscarwatchAmsatOverrideWrapper.style.display = '';
		oscarwatchAmsatNotice.style.display = '';

		if (forceAmsatEnabled) {
			amsatStatusInput.disabled = false;
			amsatStatusInput.checked = true;
			oscarwatchAmsatNotice.textContent = 'OscarWatch also forwards to AMSAT Status unless you disable forwarding in your OscarWatch account.';
			return;
		}

		if (triggeredByOscarwatchToggle && amsatStatusInput.checked) {
			amsatStatusInput.checked = false;
			oscarwatchAmsatNotice.textContent = 'AMSAT Status Upload was turned off in Cloudlog because OscarWatch Status Upload is enabled. OscarWatch also forwards to AMSAT Status unless you disable forwarding in your OscarWatch account.';
		} else {
			oscarwatchAmsatNotice.textContent = 'OscarWatch also forwards to AMSAT Status unless you disable forwarding in your OscarWatch account.';
		}

		amsatStatusInput.disabled = true;
	}

	if (oscarwatchStatusInput && amsatStatusInput && forceAmsatToggle) {
		oscarwatchStatusInput.addEventListener('change', function() {
			syncOscarwatchAmsatState(true);
		});
		forceAmsatToggle.addEventListener('change', function() {
			syncOscarwatchAmsatState(false);
		});
		syncOscarwatchAmsatState(false);
	}

	var oscarwatchTestBtn = document.getElementById('testOscarwatchTokenBtn');
	if (oscarwatchTestBtn) {
		oscarwatchTestBtn.addEventListener('click', function() {
			const tokenInput = document.getElementById('user_oscarwatch_token');
			const result = document.getElementById('oscarwatchTokenTestResult');
			const btn = document.getElementById('testOscarwatchTokenBtn');

			if (!tokenInput || !result || !btn) {
				return;
			}

			const token = tokenInput.value.trim();
			if (token === '') {
				result.className = 'form-text text-danger';
				result.textContent = 'Please enter a token first.';
				return;
			}

			btn.disabled = true;
			result.className = 'form-text text-muted';
			result.textContent = 'Testing token...';

			const body = new URLSearchParams();
			body.append('token', token);

			fetch('<?php echo site_url('user/validate_oscarwatch_token'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
				},
				body: body.toString(),
			})
				.then(async function(response) {
					const payload = await response.json().catch(function() {
						return null;
					});
					if (payload && payload.ok) {
						result.className = 'form-text text-success';
						result.textContent = payload.message || 'OscarWatch token is valid.';
					} else {
						result.className = 'form-text text-danger';
						result.textContent = (payload && payload.message) ? payload.message : 'Token validation failed.';
					}
				})
				.catch(function() {
					result.className = 'form-text text-danger';
					result.textContent = 'Unable to validate token right now.';
				})
				.finally(function() {
					btn.disabled = false;
				});
		});
	}

	var qsoWebhookTestBtn = document.getElementById('testQsoWebhookBtn');
	if (qsoWebhookTestBtn) {
		qsoWebhookTestBtn.addEventListener('click', function() {
			const urlInput = document.getElementById('user_qso_webhook_url');
			const secretInput = document.getElementById('user_qso_webhook_secret');
			const result = document.getElementById('qsoWebhookTestResult');
			const btn = document.getElementById('testQsoWebhookBtn');

			if (!urlInput || !result || !btn) {
				return;
			}

			const url = urlInput.value.trim();
			if (url === '') {
				result.className = 'form-text text-danger';
				result.textContent = 'Please enter a webhook URL first.';
				return;
			}

			btn.disabled = true;
			result.className = 'form-text text-muted';
			result.textContent = 'Sending test webhook...';

			const body = new URLSearchParams();
			body.append('url', url);
			body.append('secret', secretInput ? secretInput.value : '');

			fetch('<?php echo site_url('user/test_qso_webhook'); ?>', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
				},
				body: body.toString(),
			})
				.then(async function(response) {
					const payload = await response.json().catch(function() {
						return null;
					});
					if (payload && payload.ok) {
						result.className = 'form-text text-success';
						result.textContent = payload.message || 'Test delivered.';
					} else {
						result.className = 'form-text text-danger';
						result.textContent = (payload && payload.message) ? payload.message : 'Test webhook failed.';
					}
				})
				.catch(function() {
					result.className = 'form-text text-danger';
					result.textContent = 'Unable to send test webhook right now.';
				})
				.finally(function() {
					btn.disabled = false;
				});
		});
	}
</script>
