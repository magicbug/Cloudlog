<div class="container">
	<h3>
		<?php if (isset($user_add)) {
			echo lang('account_create_user_account');
		} else {
			echo lang('account_edit_account') . " <small class=\"text-muted\">" . $user_name . "</small>";
		}
		?>

	</h3>

	<?php if ($this->session->flashdata('success')) { ?>
		<!-- Display Success Message -->
		<div class="alert alert-success">
			<?php echo $this->session->flashdata('success'); ?>
		</div>
	<?php } ?>

	<?php if ($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php } ?>

	<?php if (validation_errors()) { ?>
		<div class="alert alert-danger">
			<a class="btn-close" data-bs-dismiss="alert">x</a>
			<?php echo validation_errors(); ?>
		</div>
	<?php } ?>

	<?php $this->load->helper('form'); ?>

	<form method="post" action="<?php echo $user_form_action; ?>" name="users" autocomplete="off">
		<div class="accordion user_edit">
			<!-- ZONE 1 / USER -->
			<div class="accordion-item">
				<h2 class="accordion-header" id="panelsStayOpen-H_user_general">
					<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-B_user_general" aria-expanded="true" aria-controls="panelsStayOpen-B_user_general">
						<?php echo lang('account_general_information'); ?></button>
				</h2>
				<div id="panelsStayOpen-B_user_general" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-H_user_general">
					<div class="accordion-body">
						<div class="row">
							<!-- Account Information -->
							<div class="col-md">
								<div class="card">
									<div class="card-header"><?php echo lang('account_account_information'); ?></div>
									<div class="card-body">
										<div class="mb-3">
											<label><?php echo lang('account_username'); ?></label>
											<input class="form-control" type="text" name="user_name" value="<?php if (isset($user_name)) {
																												echo $user_name;
																											} ?>" />
											<?php if (isset($username_error)) {
												echo "<small class=\"error\">" . $username_error . "</small>";
											} ?>
										</div>

										<div class="mb-3">
											<label><?php echo lang('account_email_address'); ?></label>
											<input class="form-control" type="text" name="user_email" value="<?php if (isset($user_email)) {
																													echo $user_email;
																												} ?>" />
											<?php if (isset($email_error)) {
												echo "<small class=\"error\">" . $email_error . "</small>";
											} ?>
										</div>

										<div class="mb-3">
											<label><?php echo lang('account_password'); ?></label>
											<div class="input-group">
												<input class="form-control" type="password" name="user_password" />
												<span class="input-group-btn"><button class="btn btn-default btn-pwd-showhide" type="button"><i class="fa fa-eye-slash"></i></button></span>
											</div>
											<?php if (isset($password_error)) {
												echo "<small class=\"error\">" . $password_error . "</small>";
											} else { ?>
												<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
											<?php } ?>
										</div>

										<hr />
										<div class="mb-3">
											<label><?php echo lang('account_user_role'); ?></label>
											<?php if ($this->session->userdata('user_type') == 99) { ?>
												<select class="form-select" name="user_type">
													<?php
													$levels = $this->config->item('auth_level');
													foreach ($levels as $key => $value) {
														echo '<option value="' . $key . '" ' . (($user_type == $key) ? "selected=\"selected\"" : "") . '>' . $value . '</option>';
													}
													?>
												</select>
											<?php } else {
												$l = $this->config->item('auth_level');
												echo $l[$user_type];
											} ?>
										</div>
									</div>
								</div>
							</div>

							<!-- Personal Information -->
							<div class="col-md">
								<div class="card">
									<div class="card-header"><?php echo lang('account_personal_information'); ?></div>
									<div class="card-body">
										<div class="mb-3">
											<label><?php echo lang('account_first_name'); ?></label>
											<input class="form-control" type="text" name="user_firstname" value="<?php if (isset($user_firstname)) {
																														echo $user_firstname;
																													} ?>" />
											<?php if (isset($firstname_error)) {
												echo "<small class=\"error\">" . $firstname_error . "</small>";
											} else { ?>
											<?php } ?>
										</div>

										<div class="mb-3">
											<label><?php echo lang('account_last_name'); ?></label>
											<input class="form-control" type="text" name="user_lastname" value="<?php if (isset($user_lastname)) {
																													echo $user_lastname;
																												} ?>" />
											<?php if (isset($lastname_error)) {
												echo "<small class=\"error\">" . $lastname_error . "</small>";
											} else { ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							<!-- Ham Radio Information -->
							<div class="col-md">
								<div class="card">
									<div class="card-header"><?php echo lang('account_hamradio_information'); ?></div>
									<div class="card-body">
										<div class="mb-3">
											<label><?php echo lang('account_callsign'); ?></label>
											<input class="form-control" type="text" name="user_callsign" value="<?php if (isset($user_callsign)) {
																													echo $user_callsign;
																												} ?>" />
											<?php if (isset($callsign_error)) {
												echo "<small class=\"error\">" . $callsign_error . "</small>";
											} else { ?>
											<?php } ?>
										</div>

										<div class="mb-3">
											<label><?php echo lang('account_gridsquare'); ?></label>
											<input class="form-control" type="text" name="user_locator" value="<?php if (isset($user_locator)) {
																													echo $user_locator;
																												} ?>" />
											<?php if (isset($locator_error)) {
												echo "<small class=\"error\">" . $locator_error . "</small>";
											} else { ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- ZONE 2 / Cloudlog -->
			<div class="accordion-item">
				<h2 class="accordion-header" id="panelsStayOpen-H_cloudlog_general">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-B_cloudlog_general" aria-expanded="false" aria-controls="panelsStayOpen-B_cloudlog_general">
						<?php echo lang('account_cloudlog_preferences'); ?></button>
				</h2>
				<div id="panelsStayOpen-B_cloudlog_general" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-H_cloudlog_general">
					<div class="accordion-body">
						<div class="row mb-3">
							<!-- Cloudlog Preferences -->
							<div class="col-md">
								<div class="card">
									<div class="card-header"><?php echo lang('general_word_general'); ?></div>
									<div class="card-body">
										<div class="mb-3">
											<label><?php echo lang('account_theme') . ' / ' . lang('account_stylesheet'); ?></label>
											<?php if (!isset($user_stylesheet)) {
												$user_stylesheet = 'default';
											} ?>
											<select class="form-select" id="user_stylesheet" name="user_stylesheet" required>
												<?php
												foreach ($themes as $theme) {
													echo '<option value="' . $theme->foldername . '" ' . (($user_stylesheet == $theme->foldername) ? 'selected="selected"' : "") . '>' . $theme->name . '</option>';
												}
												?>
											</select>
										</div>
										<hr />
										<?php if ($this->config->item('cl_multilanguage')) { ?>
											<div class="mb-3">
												<label for="language"><?php echo lang('account_cloudlog_language'); ?></label>
												<?php
												foreach ($existing_languages as $lang) {
													$options[$lang] = ucfirst($lang);
												}
												echo form_dropdown('language', $options, $language);
												?>
												<small id="language_Help" class="form-text text-muted"><?php echo lang('account_choose_cloudlog_language'); ?></small>
											</div>
										<?php } ?>

										<div class="mb-3">
											<label><?php echo lang('account_timezone'); ?></label>
											<?php
											if (!isset($user_timezone)) {
												$user_timezone = '151';
											}
											echo form_dropdown('user_timezone', $timezones, $user_timezone);
											?>
										</div>

										<div class="mb-3">
											<label for="SelectDateFormat"><?php echo lang('account_date_format'); ?></label>
											<?php if (!isset($user_date_format)) {
												$user_date_format = 'd/m/y';
											} ?>
											<select name="user_date_format" class="form-select" id="SelectDateFormat" aria-describedby="SelectDateFormatHelp">
												<option value="d/m/y" <?php if ($user_date_format == "d/m/y") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('d/m/y'); ?></option>
												<option value="d/m/Y" <?php if ($user_date_format == "d/m/Y") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('d/m/Y'); ?></option>
												<option value="m/d/y" <?php if ($user_date_format == "m/d/y") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('m/d/y'); ?></option>
												<option value="m/d/Y" <?php if ($user_date_format == "m/d/Y") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('m/d/Y'); ?></option>
												<option value="d.m.Y" <?php if ($user_date_format == "d.m.Y") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('d.m.Y'); ?></option>
												<option value="y/m/d" <?php if ($user_date_format == "y/m/d") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('y/m/d'); ?></option>
												<option value="Y-m-d" <?php if ($user_date_format == "Y-m-d") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('Y-m-d'); ?></option>
												<option value="M d, Y" <?php if ($user_date_format == "M d, Y") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('M d, Y'); ?></option>
												<option value="M d, y" <?php if ($user_date_format == "M d, y") {
																			echo "selected=\"selected\"";
																		} ?>><?php echo date('M d, y'); ?></option>
											</select>
											<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_select_how_you_would_like_dates_shown_when_logged_into_your_account'); ?></small>
										</div>

										<div class="mb-3">
											<label for="user_measurement_base"><?php echo lang('account_measurement_preferences'); ?></label>
											<?php if (!isset($user_measurement_base)) {
												$user_measurement_base = 'M';
											} ?>
											<select class="form-select" id="user_measurement_base" name="user_measurement_base" aria-describedby="user_measurement_base_Help" required>
												<option value ''></option>
												<option value='K' <?php if ($user_measurement_base == "K") {
																		echo "selected=\"selected\"";
																	} ?>>Kilometers</option>
												<option value='M' <?php if ($user_measurement_base == "M") {
																		echo "selected=\"selected\"";
																	} ?>>Miles</option>
												<option value='N' <?php if ($user_measurement_base == "N") {
																		echo "selected=\"selected\"";
																	} ?>>Nautical miles</option>
											</select>
											<small id="user_measurement_base_Help" class="form-text text-muted"><?php echo lang('account_choose_which_unit_distances_will_be_shown_in'); ?></small>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md">
								<div class="card">
									<div class="card-header">Dashboard Options</div>
									<div class="card-body">
										<div class="mb-3">
											<div class="form-check form-switch">
												<input name="user_dashboard_enable_dxpedition_card" class="form-check-input" type="checkbox" role="switch" id="DashboardUpcomingDXpeditionCheck" <?php if ($dashboard_upcoming_dx_card) { echo 'checked'; } ?>>
												<label class="form-check-label" for="DashboardUpcomingDXpeditionCheck" >Enable Upcoming DXPedition Card</label>
											</div>

											<div class="form-check form-switch">
												<input name="user_dashboard_enable_qslcards_card" class="form-check-input" type="checkbox" role="switch" id="DashboardQSLCardCheck" <?php if ($dashboard_qslcard_card) { echo 'checked'; } ?>>
												<label class="form-check-label" for="DashboardQSLCardCheck" >Enable QSL Cards Card</label>
											</div>

											<div class="form-check form-switch">
												<input name="user_dashboard_enable_eqslcards_card" class="form-check-input" type="checkbox" role="switch" id="DashboardeQSLCardCheck" <?php if ($dashboard_eqslcard_card) { echo 'checked'; } ?>>
												<label class="form-check-label" for="DashboardeQSLCardCheck" >Enable eQSL Cards Card</label>
											</div>

											<div class="form-check form-switch">
												<input name="user_dashboard_enable_lotw_card" class="form-check-input" type="checkbox" role="switch" id="DashboardlotwCardCheck" <?php if ($dashboard_lotw_card) { echo 'checked'; } ?>>
												<label class="form-check-label" for="DashboardlotwCardCheck" >Enable Logbook of the World Card</label>
											</div>

											<div class="form-check form-switch">
												<input name="user_dashboard_enable_vuccgrids_card" class="form-check-input" type="checkbox" role="switch" id="DashboardvuccgridsCardCheck" <?php if ($dashboard_vuccgrids_card) { echo 'checked'; } ?>>
												<label class="form-check-label" for="DashboardvuccgridsCardCheck" >Enable VUCC-Grids Card</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row mb-3">
								<!-- Logbook fields Setting -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_logbook_fields'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label for="column1"><?php echo lang('account_column1_text'); ?></label>
												<?php if (!isset($user_column1)) {
													$user_column1 = 'Mode';
												} ?>
												<select class="form-select" id="column1" name="user_column1">
													<option value="Band" <?php if ($user_column1 == "Band") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_band'); ?></option>
													<option value="Frequency" <?php if ($user_column1 == "Frequency") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
													<option value="Mode" <?php if ($user_column1 == "Mode") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_mode'); ?></option>
													<option value="RSTS" <?php if ($user_column1 == "RSTS") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
													<option value="RSTR" <?php if ($user_column1 == "RSTR") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
													<option value="Country" <?php if ($user_column1 == "Country") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_country'); ?></option>
													<option value="IOTA" <?php if ($user_column1 == "IOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_iota'); ?></option>
													<option value="SOTA" <?php if ($user_column1 == "SOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_sota'); ?></option>
													<option value="WWFF" <?php if ($user_column1 == "WWFF") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
													<option value="POTA" <?php if ($user_column1 == "POTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_pota'); ?></option>
													<option value="State" <?php if ($user_column1 == "State") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_state'); ?></option>
													<option value="Grid" <?php if ($user_column1 == "Grid") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
													<option value="Distance" <?php if ($user_column1 == "Distance") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_distance'); ?></option>
													<option value="Operator" <?php if ($user_column1 == "Operator") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_operator'); ?></option>
													<option value="Name" <?php if ($user_column1 == "Name") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_name'); ?></option>
													<option value="Flag" <?php if ($user_column1 == "Flag") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_flag'); ?></option>
												</select>
											</div>

											<div class="mb-3">
												<label for="column2"><?php echo lang('account_column2_text'); ?></label>
												<?php if (!isset($user_column2)) {
													$user_column2 = 'RSTS';
												} ?>
												<select class="form-select" id="column2" name="user_column2">
													<option value="Band" <?php if ($user_column2 == "Band") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_band'); ?></option>
													<option value="Frequency" <?php if ($user_column2 == "Frequency") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
													<option value="Mode" <?php if ($user_column2 == "Mode") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_mode'); ?></option>
													<option value="RSTS" <?php if ($user_column2 == "RSTS") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
													<option value="RSTR" <?php if ($user_column2 == "RSTR") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
													<option value="Country" <?php if ($user_column2 == "Country") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_country'); ?></option>
													<option value="IOTA" <?php if ($user_column2 == "IOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_iota'); ?></option>
													<option value="SOTA" <?php if ($user_column2 == "SOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_sota'); ?></option>
													<option value="WWFF" <?php if ($user_column1 == "WWFF") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
													<option value="POTA" <?php if ($user_column1 == "POTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_pota'); ?></option>
													<option value="State" <?php if ($user_column2 == "State") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_state'); ?></option>
													<option value="Grid" <?php if ($user_column2 == "Grid") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
													<option value="Distance" <?php if ($user_column2 == "Distance") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_distance'); ?></option>
													<option value="Operator" <?php if ($user_column2 == "Operator") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_operator'); ?></option>
													<option value="Name" <?php if ($user_column2 == "Name") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_name'); ?></option>
													<option value="Flag" <?php if ($user_column2 == "Flag") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_flag'); ?></option>																			
												</select>
											</div>

											<div class="mb-3">
												<label for="column3"><?php echo lang('account_column3_text'); ?></label>
												<?php if (!isset($user_column3)) {
													$user_column3 = 'RSTR';
												} ?>
												<select class="form-select" id="column3" name="user_column3">
													<option value="Band" <?php if ($user_column3 == "Band") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_band'); ?></option>
													<option value="Frequency" <?php if ($user_column3 == "Frequency") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
													<option value="Mode" <?php if ($user_column3 == "Mode") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_mode'); ?></option>
													<option value="RSTS" <?php if ($user_column3 == "RSTS") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
													<option value="RSTR" <?php if ($user_column3 == "RSTR") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
													<option value="Country" <?php if ($user_column3 == "Country") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_country'); ?></option>
													<option value="IOTA" <?php if ($user_column3 == "IOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_iota'); ?></option>
													<option value="SOTA" <?php if ($user_column3 == "SOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_sota'); ?></option>
													<option value="WWFF" <?php if ($user_column1 == "WWFF") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
													<option value="POTA" <?php if ($user_column1 == "POTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_pota'); ?></option>
													<option value="State" <?php if ($user_column3 == "State") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_state'); ?></option>
													<option value="Grid" <?php if ($user_column3 == "Grid") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
													<option value="Distance" <?php if ($user_column3 == "Distance") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_distance'); ?></option>
													<option value="Operator" <?php if ($user_column3 == "Operator") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_operator'); ?></option>
													<option value="Name" <?php if ($user_column3 == "Name") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_name'); ?></option>
													<option value="Flag" <?php if ($user_column3 == "Flag") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_flag'); ?></option>
												</select>
											</div>

											<div class="mb-3">
												<label for="column4"><?php echo lang('account_column4_text'); ?></label>
												<?php if (!isset($user_column4)) {
													$user_column4 = 'Band';
												} ?>
												<select class="form-select" id="column4" name="user_column4">
													<option value="Band" <?php if ($user_column4 == "Band") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_band'); ?></option>
													<option value="Frequency" <?php if ($user_column4 == "Frequency") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
													<option value="Mode" <?php if ($user_column4 == "Mode") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_mode'); ?></option>
													<option value="RSTS" <?php if ($user_column4 == "RSTS") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
													<option value="RSTR" <?php if ($user_column4 == "RSTR") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
													<option value="Country" <?php if ($user_column4 == "Country") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_country'); ?></option>
													<option value="IOTA" <?php if ($user_column4 == "IOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_iota'); ?></option>
													<option value="SOTA" <?php if ($user_column4 == "SOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_sota'); ?></option>
													<option value="WWFF" <?php if ($user_column1 == "WWFF") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
													<option value="POTA" <?php if ($user_column1 == "POTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_pota'); ?></option>
													<option value="State" <?php if ($user_column4 == "State") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_state'); ?></option>
													<option value="Grid" <?php if ($user_column4 == "Grid") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
													<option value="Distance" <?php if ($user_column4 == "Distance") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_distance'); ?></option>
													<option value="Operator" <?php if ($user_column4 == "Operator") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_operator'); ?></option>
													<option value="Name" <?php if ($user_column4 == "Name") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_name'); ?></option>
													<option value="Flag" <?php if ($user_column4 == "Flag") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_flag'); ?></option>
												</select>
											</div>

											<div class="mb-3">
												<label for="column5"><?php echo lang('account_column5_text'); ?></label>
												<?php if (!isset($user_column5)) {
													$user_column5 = 'Country';
												} ?>
												<select class="form-select" id="column5" name="user_column5">
													<option value="" <?php if ($user_column5 == "") {
																			echo " selected =\"selected\"";
																		} ?>></option>
													<option value="Band" <?php if ($user_column5 == "Band") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_band'); ?></option>
													<option value="Frequency" <?php if ($user_column5 == "Frequency") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
													<option value="Mode" <?php if ($user_column5 == "Mode") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_mode'); ?></option>
													<option value="RSTS" <?php if ($user_column5 == "RSTS") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
													<option value="RSTR" <?php if ($user_column5 == "RSTR") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
													<option value="Country" <?php if ($user_column5 == "Country") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_country'); ?></option>
													<option value="IOTA" <?php if ($user_column5 == "IOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_iota'); ?></option>
													<option value="SOTA" <?php if ($user_column5 == "SOTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_sota'); ?></option>
													<option value="WWFF" <?php if ($user_column1 == "WWFF") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
													<option value="POTA" <?php if ($user_column1 == "POTA") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_pota'); ?></option>
													<option value="State" <?php if ($user_column5 == "State") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_state'); ?></option>
													<option value="Grid" <?php if ($user_column5 == "Grid") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
													<option value="Distance" <?php if ($user_column5 == "Distance") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_distance'); ?></option>
													<option value="Operator" <?php if ($user_column5 == "Operator") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('gen_hamradio_operator'); ?></option>
													<option value="Name" <?php if ($user_column5 == "Name") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_name'); ?></option>
													<option value="Location" <?php if ($user_column5 == "Location") {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('cloudlog_station_profile'); ?></option>
													<option value="Flag" <?php if ($user_column5 == "Flag") {
																				echo " selected =\"selected\"";
																			} ?>><?php echo lang('general_word_flag'); ?></option>
												</select>
											</div>
										</div>
									</div>
								</div>

								<!-- QSO Logging Options -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_qso_logging_options'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label for="logendtime"><?php echo lang('account_log_end_time'); ?></label>
												<?php if (!isset($user_qso_end_times)) {
													$user_qso_end_times = '0';
												} ?>
												<select class="form-select" id="logendtimes" name="user_qso_end_times">
													<option value="1" <?php if ($user_qso_end_times == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_qso_end_times == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
												<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_log_end_time_hint'); ?></small>
											</div>

											<hr />
											<div class="mb-3">
												<label for="profileimages"><?php echo lang('account_show_profile_picture_of_qso_partner_from_qrzcom_hamqthcom_profile_in_the_log_qso_section'); ?></label>
												<?php if (!isset($user_show_profile_image)) {
													$user_show_profile_image = '0';
												} ?>
												<select class="form-select" id="profileimages" name="user_show_profile_image">
													<option value="1" <?php if ($user_show_profile_image == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_show_profile_image == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
												<small class="form-text text-muted"><?php echo lang('account_please_set_your_qrzcom_hamqthcom_credentials_in_the_general_config_file'); ?></small>
											</div>

											<hr />
											<div class="mb-3">
												<label for="qthlookup"><?php echo lang('account_location_auto_lookup'); ?></label>
												<?php if (!isset($user_qth_lookup)) {
													$user_qth_lookup = '0';
												} ?>
												<select class="form-select" id="qthlookup" name="user_qth_lookup">
													<option value="1" <?php if ($user_qth_lookup == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_qth_lookup == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
												<small class="form-text text-muted"><?php echo lang('account_if_set_gridsquare_is_fetched_based_on_location_name'); ?></small>
											</div>

											<div class="mb-3">
												<label for="sotalookup"><?php echo lang('account_sota_auto_lookup_gridsquare_and_name_for_summit'); ?></label>
												<?php if (!isset($user_sota_lookup)) {
													$user_sota_lookup = '0';
												} ?>
												<select class="form-select" id="sotalookup" name="user_sota_lookup">
													<option value="1" <?php if ($user_sota_lookup == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_sota_lookup == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
												<small class="form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></small>
											</div>

											<div class="mb-3">
												<label for="wwfflookup"><?php echo lang('account_wwff_auto_lookup_gridsquare_and_name_for_reference'); ?></label>
												<?php if (!isset($user_wwff_lookup)) {
													$user_wwff_lookup = '0';
												} ?>
												<select class="form-select" id="wwfflookup" name="user_wwff_lookup">
													<option value="1" <?php if ($user_wwff_lookup == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_wwff_lookup == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
												<small class="form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></small>
											</div>

											<div class="mb-3">
												<label for="potalookup"><?php echo lang('account_pota_auto_lookup_gridsquare_and_name_for_park'); ?></label>
												<?php if (!isset($user_pota_lookup)) {
													$user_pota_lookup = '0';
												} ?>
												<select class="form-select" id="potalookup" name="user_pota_lookup">
													<option value="1" <?php if ($user_pota_lookup == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_pota_lookup == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
												<small class="form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></small>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row mb-3">
								<!-- Menu Options -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_main_menu'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label for="shownotes"><?php echo lang('account_show_notes_in_the_main_menu'); ?></label>
												<?php if (!isset($user_show_notes)) {
													$user_show_notes = '0';
												} ?>
												<select class="form-select" id="shownotes" name="user_show_notes">
													<option value="1" <?php if ($user_show_notes == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_show_notes == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
											</div>

											<hr />
											<div class="mb-3">
												<label for="quicklog"><?php echo lang('account_quicklog_feature'); ?></label>
												<?php if (!isset($user_quicklog)) {
													$user_quicklog = '0';
												} ?>
												<select class="form-select" id="quicklog" name="user_quicklog">
													<option value="1" <?php if ($user_quicklog == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_quicklog == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
												<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_quicklog_feature_hint'); ?></small>
											</div>

											<div class="mb-3">
												<label for="quicklog_enter"><?php echo lang('account_quicklog_enter'); ?></label>
												<?php if (!isset($user_quicklog_enter)) {
													$user_quicklog_enter = '0';
												} ?>
												<select class="form-select" id="quicklog_enter" name="user_quicklog_enter">
													<option value="0" <?php if ($user_quicklog_enter == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('account_quicklog_enter_log'); ?></option>
													<option value="1" <?php if ($user_quicklog_enter == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('account_quicklog_enter_search'); ?></option>
												</select>
												<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_quicklog_enter_hint'); ?></small>
											</div>
										</div>
									</div>
								</div>

								<!-- Map Setting -->
								<?php if ($this->session->userdata('user_id') == $this->uri->segment(3)) { ?>
									<div class="col-md">
										<div class="card">
											<div class="card-header"><?php echo $this->lang->line('account_map_params'); ?></div>
											<div class="card-body">
												<div class="row"> <!-- Station -->
													<div class="mb-3 col-md-4">
														<label>&nbsp;</label><br /><label><?php echo $this->lang->line('gen_hamradio_station'); ?></label>
													</div>
													<div class="mb-3 col-md-3">
														<label><?php echo $this->lang->line('general_word_icon'); ?></label><br />
														<div class="icon_selectBox" data-boxcontent="station">
															<input type="hidden" name="user_map_station_icon" value="<?php echo $user_map_station_icon; ?>">
															<div class="form-select icon_overSelect"><?php echo (($user_map_station_icon == "0") ? substr($this->lang->line('general_word_not_display'), 0, 10) . '.' : ("<i class='" . $user_map_station_icon . "'></i>")); ?></div>
														</div>
														<div class="col-md-3 icon_selectBox_data" data-boxcontent="station">
															<?php foreach ($map_icon_select['station'] as $val) {
																echo "<label data-value='" . $val . "'>" . (($val == "0") ? $this->lang->line('general_word_not_display') : ("<i class='" . $val . "'></i>")) . "</label>";
															} ?>
														</div>
													</div>
													<div class="mb-3 col-md-2">
														<label><?php echo $this->lang->line('general_word_colors'); ?></label><br /><input type="color" class="form-control user_icon_color" name="user_map_station_color" id="user_map_station_color" value="<?php echo $user_map_station_color; ?>" style="padding:initial;<?php echo ($user_map_station_icon == "0") ? 'display:none;' : ''; ?>" data-icon="station" />
													</div>
												</div>
												<div class="row"> <!-- QSO (default) -->
													<div class="mb-3 col-md-4">
														<label><?php echo $this->lang->line('account_map_qso_by_default'); ?></label>
													</div>
													<div class="mb-3 col-md-3">
														<div class="icon_selectBox" data-boxcontent="qso">
															<input type="hidden" name="user_map_qso_icon" value="<?php echo $user_map_qso_icon; ?>">
															<div class="form-select icon_overSelect"><?php echo "<i class='" . $user_map_qso_icon . "'></i>"; ?></div>
														</div>
														<div class="col-md-3 icon_selectBox_data" data-boxcontent="qso">
															<?php foreach ($map_icon_select['qso'] as $val) {
																echo "<label data-value='" . $val . "'><i class='" . $val . "'></i></label>";
															} ?>
														</div>
													</div>
													<div class="mb-3 col-md-2">
														<input type="color" class="form-control user_icon_color" name="user_map_qso_color" id="user_map_qso_color" value="<?php echo $user_map_qso_color; ?>" style="padding:initial;" data-icon="qso" />
													</div>
												</div>
												<div class="row"> <!-- QSO (confirmed) -->
													<div class="mb-3 col-md-4">
														<label><?php echo $this->lang->line('account_map_qso_confirm'); ?></label>
														<small class="form-text text-muted"><?php echo lang('account_map_qso_confirm_same_qso'); ?></small>
													</div>
													<div class="mb-3 col-md-3">
														<div class="icon_selectBox" data-boxcontent="qsoconfirm">
															<input type="hidden" name="user_map_qsoconfirm_icon" value="<?php echo $user_map_qsoconfirm_icon; ?>">
															<div class="form-select icon_overSelect"><?php echo (($user_map_qsoconfirm_icon == "0") ? $this->lang->line('general_word_no') : ("<i class='" . $user_map_qsoconfirm_icon . "'></i>")); ?></div>
														</div>
														<div class="col-md-3 icon_selectBox_data" data-boxcontent="qsoconfirm">
															<?php foreach ($map_icon_select['qsoconfirm'] as $val) {
																echo "<label data-value='" . $val . "'>" . (($val == "0") ? $this->lang->line('general_word_no') : ("<i class='" . $val . "'></i>")) . "</label>";
															} ?>
														</div>
													</div>
													<div class="md-3 col-md-2">
														<input type="color" class="form-control user_icon_color" name="user_map_qsoconfirm_color" id="user_map_qsoconfirm_color" value="<?php echo $user_map_qsoconfirm_color; ?>" style="padding:initial;<?php echo ($user_map_qsoconfirm_icon == "0") ? 'display:none;' : ''; ?>" data-icon="qsoconfirm" />
													</div>
												</div>
												<div class="row">
													<div class="md-3 col-md-4">
														<label><?php echo $this->lang->line('gen_hamradio_gridsquare_show'); ?></label>
													</div>
													<div class="md-3 col-md-3">
														<select class="form-select" id="user_map_gridsquare_show" name="user_map_gridsquare_show">
															<option value="1" <?php if ($user_map_gridsquare_show == 1) {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('general_word_yes'); ?></option>
															<option value="0" <?php if ($user_map_gridsquare_show == 0) {
																					echo " selected =\"selected\"";
																				} ?>><?php echo lang('general_word_no'); ?></option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>

							<div class="row">
								<!-- Previous QSL -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_previous_qsl_type'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label for="profileimages"><?php echo lang('account_select_the_type_of_qsl_to_show_in_the_previous_qsos_section'); ?></label>
												<?php if (!isset($user_previous_qsl_type)) {
													$user_previous_qsl_type = '0';
												} ?>
												<select class="form-select" id="previousqsltype" name="user_previous_qsl_type">
													<option value="0" <?php if ($user_previous_qsl_type == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('gen_hamradio_qsl'); ?></option>
													<option value="1" <?php if ($user_previous_qsl_type == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('lotw_short'); ?></option>
													<option value="2" <?php if ($user_previous_qsl_type == 2) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('eqsl_short'); ?></option>
													<option value="4" <?php if ($user_previous_qsl_type == 4) {
																			echo " selected =\"selected\"";
																		} ?>>QRZ</option>
												</select>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- ZONE 3 / Default Value -->
				<div class="accordion-item">
					<h2 class="accordion-header" id="panelsStayOpen-H_default_value">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-B_default_value" aria-expanded="false" aria-controls="panelsStayOpen-B_default_value">
							<?php echo lang('account_default_values'); ?></button>
					</h2>
					<div id="panelsStayOpen-B_default_value" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-H_default_value">
						<div class="accordion-body">
							<div class="row">
								<!-- Default -->
								<div class="col-md">
									<div class="card">
										<!--<div class="card-header"><?php echo lang('account_default_band_settings'); ?></div>-->
										<div class="card-body">
											<div class="mb-3">
												<label for="user_default_band"><?php echo lang('account_gridmap_default_band'); ?></label>
												<?php if (!isset($user_default_band)) {
													$user_default_band = 'All';
												} ?>
												<select id="user_default_band" class="form-select" name="user_default_band">
													<option value="All">All</option>;
													<?php foreach ($bands as $band) {
														echo '<option value="' . $band . '" ' . (($user_default_band == $band) ? ' selected="selected"' : '') . '>' . $band . '</option>' . "\n";
													} ?>
												</select>
											</div>
											<div class="mb-3">
												<label class="my-1 me-2"><?php echo lang('account_qsl_settings'); ?></label>
												<div class="form-check-inline">
													<?php echo '<input class="form-check-input" type="checkbox" name="user_default_confirmation_qsl" id="user_default_confirmation_qsl"';
													if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Q') !== false) {
														echo ' checked';
													}
													echo '>'; ?>
													<label class="form-check-label" for="user_default_confirmation_qsl"><?php echo lang('gen_hamradio_qsl'); ?></label>
												</div>
												<div class="form-check-inline">
													<?php echo '<input class="form-check-input" type="checkbox" name="user_default_confirmation_lotw" id="user_default_confirmation_lotw"';
													if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
														echo ' checked';
													}
													echo '>'; ?>
													<label class="form-check-label" for="user_default_confirmation_lotw"><?php echo lang('lotw_short'); ?></label>
												</div>
												<div class="form-check-inline">
													<?php echo '<input class="form-check-input" type="checkbox" name="user_default_confirmation_eqsl" id="user_default_confirmation_eqsl"';
													if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
														echo ' checked';
													}
													echo '>'; ?>
													<label class="form-check-label" for="user_default_confirmation_eqsl"><?php echo lang('account_eqsl'); ?></label>
												</div>
												<div class="form-check-inline">
													<?php echo '<input class="form-check-input" type="checkbox" name="user_default_confirmation_qrz" id="user_default_confirmation_qrz"';
													if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'Z') !== false) {
														echo ' checked';
													}
													echo '>'; ?>
													<label class="form-check-label" for="user_default_confirmation_qrz">QRZ.com</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- ZONE 4 / Confirmation Account -->
				<div class="accordion-item">
					<h2 class="accordion-header" id="panelsStayOpen-H_confirmation_account">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-B_confirmation_account" aria-expanded="false" aria-controls="panelsStayOpen-B_confirmation_account">
							<?php echo lang('account_third_party_services'); ?></button>
					</h2>
					<div id="panelsStayOpen-B_confirmation_account" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-H_confirmation_account">
						<div class="accordion-body">
							<div class="row">
								<!-- Logbook of the World -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_logbook_of_the_world'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label><?php echo lang('account_logbook_of_the_world_lotw_username'); ?></label>
												<input class="form-control" type="text" name="user_lotw_name" value="<?php if (isset($user_lotw_name)) {
																															echo $user_lotw_name;
																														} ?>" />
												<?php if (isset($userlotwname_error)) {
													echo "<small class=\"error\">" . $userlotwname_error . "</small>";
												} ?>
											</div>

											<div class="mb-3">
												<label><?php echo lang('account_logbook_of_the_world_lotw_password'); ?></label>
												<div class="input-group">
													<input class="form-control" type="password" name="user_lotw_password" />
													<span class="input-group-btn"><button class="btn btn-default btn-pwd-showhide" type="button"><i class="fa fa-eye-slash"></i></button></span>
												</div>
												<?php if (isset($lotwpassword_error)) {
													echo "<small class=\"error\">" . $lotwpassword_error . "</small>";
												} else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>

								<!-- QRZ -->
								<div class="col-md">
									<div class="card">
										<div class="card-header">Callbook</div>
										<div class="card-body">
											<div class="mb-3">
												<label>Callbook Provider</label>
												<select class="form-control" name="user_callbook_type">
													<option value="None" <?php if (isset($user_callbook_type) && $user_callbook_type == 'None') echo 'selected'; ?>>None</option>
													<option value="QRZ" <?php if (isset($user_callbook_type) && $user_callbook_type == 'QRZ') echo 'selected'; ?>>QRZ</option>
													<option value="HamQTH" <?php if (isset($user_callbook_type) && $user_callbook_type == 'HamQTH') echo 'selected'; ?>>HamQTH</option>
												</select>
												<?php if (isset($callbook_type_error)) {
													echo "<small class=\"error\">" . $callbook_type_error . "</small>";
												} ?>
											</div>

											<div class="mb-3">
												<label>Callbook Username</label>
												<input class="form-control" type="text" name="user_callbook_username" value="<?php if (isset($user_callbook_username)) {
													echo $user_callbook_username;
												} ?>" />
												<?php if (isset($callbook_username_error)) {
													echo "<small class=\"error\">" . $callbook_username_error . "</small>";
												} ?>
											</div>

											<div class="mb-3">
												<label>Callbook Password</label>
												<div class="input-group">
													<input class="form-control" type="password" name="user_callbook_password" />
													<span class="input-group-btn"><button class="btn btn-default btn-pwd-showhide" type="button"><i class="fa fa-eye-slash"></i></button></span>
												</div>
												<?php if (isset($callbook_password_error)) {
													echo "<small class=\"error\">" . $callbook_password_error . "</small>";
												} else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>

								<!-- eQSL -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_eqsl'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label><?php echo lang('account_eqslcc_username'); ?></label>
												<input class="form-control" type="text" name="user_eqsl_name" value="<?php if (isset($user_eqsl_name)) {
																															echo $user_eqsl_name;
																														} ?>" />
												<?php if (isset($eqslusername_error)) {
													echo "<small class=\"error\">" . $eqslusername_error . "</small>";
												} ?>
											</div>

											<div class="mb-3">
												<label><?php echo lang('account_eqslcc_password'); ?></label>
												<div class="input-group">
													<input class="form-control" type="password" name="user_eqsl_password" />
													<span class="input-group-btn"><button class="btn btn-default btn-pwd-showhide" type="button"><i class="fa fa-eye-slash"></i></button></span>
												</div>
												<?php if (isset($eqslpassword_error)) {
													echo "<small class=\"error\">" . $eqslpassword_error . "</small>";
												} else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<!-- Club Log -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_clublog'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label><?php echo lang('account_clublog_email_callsign'); ?></label>
												<input class="form-control" type="text" name="user_clublog_name" value="<?php if (isset($user_clublog_name)) {
																															echo $user_clublog_name;
																														} ?>" />
												<small class="form-text text-muted"><?php echo lang('account_the_email_or_callsign_you_use_to_login_to_club_log'); ?></small>
												<?php if (isset($userclublogname_error)) {
													echo "<small class=\"error\">" . $userclublogname_error . "</small>";
												} ?>
											</div>

											<div class="mb-3">
												<label><?php echo lang('account_clublog_password'); ?></label>
												<div class="input-group">
													<input class="form-control" type="password" name="user_clublog_password" />
													<span class="input-group-btn"><button class="btn btn-default btn-pwd-showhide" type="button"><i class="fa fa-eye-slash"></i></button></span>
												</div>
												<?php if (isset($clublogpassword_error)) {
													echo "<small class=\"error\">" . $clublogpassword_error . "</small>";
												} else { ?>
													<small class="form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></small>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- ZONE 5 / Miscellaneous -->
				<div class="accordion-item">
					<h2 class="accordion-header" id="panelsStayOpen-H_miscellaneous">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-B_miscellaneous" aria-expanded="false" aria-controls="panelsStayOpen-B_miscellaneous">
							<?php echo lang('account_miscellaneous'); ?></button>
					</h2>
					<div id="panelsStayOpen-B_miscellaneous" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-H_miscellaneous">
						<div class="accordion-body">
							<div class="row">
								<!-- AMSAT Upload -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_amsat_status_upload'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label for="amsatsatatusupload"><?php echo lang('account_upload_status_of_sat_qsos_to'); ?> <a href="https://www.amsat.org/status/" target="_blank">https://www.amsat.org/status/</a>.</label>
												<?php if (!isset($user_amsat_status_upload)) {
													$user_amsat_status_upload = '0';
												} ?>
												<select class="form-select" id="amsatstatusupload" name="user_amsat_status_upload">
													<option value="1" <?php if ($user_amsat_status_upload == 1) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_yes'); ?></option>
													<option value="0" <?php if ($user_amsat_status_upload == 0) {
																			echo " selected =\"selected\"";
																		} ?>><?php echo lang('general_word_no'); ?></option>
												</select>
											</div>
										</div>
									</div>
								</div>

								<!-- Mastodon -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_mastodon'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label><?php echo lang('account_user_mastodon'); ?></label>
												<input class="form-control" type="text" name="user_mastodon_url" value="<?php if (isset($user_mastodon_url)) {
																															echo $user_mastodon_url;
																														} ?>" />
												<small class="form-text text-muted"><?php echo lang('account_user_mastodon_hint'); ?></a></small>
											</div>
										</div>
									</div>
								</div>

								<!-- Winkeyer -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_winkeyer'); ?> <span class="badge text-bg-danger float-end"><?php echo lang('admin_experimental'); ?></span></div>
										<div class="card-body">
											<div class="mb-3">
												<label><?php echo lang('account_winkeyer_enabled'); ?></label>
												<?php if (!isset($user_winkey)) {
													$user_winkey = '0';
												} ?>
												<select class="form-select" name="user_winkey" id="user_winkeyer">
													<option value="0" <?php if ($user_winkey == 0) {
																			echo 'selected="selected"';
																		} ?>><?php echo lang('general_word_no'); ?></option>
													<option value="1" <?php if ($user_winkey == 1) {
																			echo 'selected="selected"';
																		} ?>><?php echo lang('general_word_yes'); ?></option>
												</select>
												<small class="form-text text-muted"><?php echo lang('account_winkeyer_hint'); ?></small>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<!-- Hams.at Settings -->
								<div class="col-md">
									<div class="card">
										<div class="card-header"><?php echo lang('account_hamsat'); ?></div>
										<div class="card-body">
											<div class="mb-3">
												<label><?php echo lang('account_hamsat_private_feed_key'); ?></label>
												<input class="form-control" type="text" name="user_hamsat_key" value="<?php if (isset($user_hamsat_key)) {
																															echo $user_hamsat_key;
																														} ?>" />
												<small class="form-text text-muted"><?php echo lang('account_hamsat_hint'); ?></a></small>
											</div>
											<div class="mb-3">
												<label><?php echo lang('account_hamsat_workable_only'); ?></label>
												<?php if (!isset($user_hamsat_workable_only)) {
													$user_hamsat_workable_only = '0';
												} ?>
												<select class="form-select" name="user_hamsat_workable_only" id="user_hamsat_workable_only">
													<option value="0" <?php if ($user_hamsat_workable_only == 0) {
																			echo 'selected="selected"';
																		} ?>><?php echo lang('general_word_no'); ?></option>
													<option value="1" <?php if ($user_hamsat_workable_only == 1) {
																			echo 'selected="selected"';
																		} ?>><?php echo lang('general_word_yes'); ?></option>
												</select>
												<small class="form-text text-muted"><?php echo lang('account_hamsat_workable_only_hint'); ?></small>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
			<button type="submit" class="btn btn-primary mb-5 mt-3"><i class="fas fa-save"></i> <?php echo lang('account_save_account_changes'); ?></button>
	</form>
</div>