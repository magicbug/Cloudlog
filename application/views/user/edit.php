<div class="container">
	<h3>
	  <?php echo lang('account_edit_account'); ?>
	  <small class="text-muted"><?php echo $user_name; ?></small>
	</h3>

	<?php if($this->session->flashdata('success')) { ?>
		<!-- Display Success Message -->
		<div class="alert alert-success">
		  <?php echo $this->session->flashdata('success'); ?>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php } ?>

	<?php if(validation_errors()) { ?>
    <div class="alert alert-danger">
    	<a class="close" data-dismiss="alert">x</a>
 		<?php echo validation_errors(); ?>
    </div>
	<?php } ?>

	<?php $this->load->helper('form'); ?>

	<form method="post" action="<?php echo site_url('user/edit')."/".$this->uri->segment(3); ?>" name="users" autocomplete="off">
	<div class="row">
	    <div class="col-md">
	    	<div class="card">
		    	<div class="card-header">
				   	 <?php echo lang('account_account_information'); ?>
					</div>
				<div class="card-body">
					<div class="form-group">
						<label><?php echo lang('account_username'); ?></label>
						<input class="form-control" type="text" name="user_name" value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
						<?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label><?php echo lang('account_email_address'); ?></label>
						<input class="form-control" type="text" name="user_email" value="<?php if(isset($user_email)) { echo $user_email; } ?>" />
						<?php if(isset($email_error)) { echo "<div class=\"small error\">".$email_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label><?php echo lang('account_password'); ?></label>
						<input class="form-control" type="password" name="user_password" />
						<?php if(isset($password_error)) { echo "<div class=\"small error\">".$password_error."</div>"; } else { ?>
						<div class="small form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></div></td>
						<?php } ?>
					</div>
				</div>
			</div>
	    </div>

	    <div class="col-md">
	    	<div class="card">
		    	<div class="card-header">
				   	 <?php echo lang('account_roles'); ?>
					</div>
				<div class="card-body">
					<div class="form-group">
						<label><?php echo lang('account_user_role'); ?></label>

						<?php if($this->session->userdata('user_type') == 99) { ?>
						<select class="custom-select" name="user_type">
						<?php
							$levels = $this->config->item('auth_level');
							foreach ($levels as $key => $value) {
								echo '<option value="'. $key . '"';
									if($user_type == $key) {
										echo "selected=\"selected\"";
									}
								echo '>' . $value . '</option>';
							}
							?>
						</select>
						<?php } else {
							$l = $this->config->item('auth_level');
							echo $l[$user_type];
						}?>
					</div>
				</div>
			</div>
	    </div>

        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <?php echo lang('account_theme'); ?>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><?php echo lang('account_stylesheet'); ?></label>
                        <select class="custom-select" id="user_stylesheet" name="user_stylesheet" required>
							<?php
							foreach ($themes as $theme) {
								echo '<option value="' . $theme->foldername . '"';
								if( $user_stylesheet == $theme->foldername) {
									echo 'selected="selected"';
								}
								echo '>' . $theme->name . '</option>';
							}
							?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<br>
	<div class="row">
	 	<!-- Personal Information -->
	    <div class="col-md">
	    	<div class="card">
				<div class="card-header">
			   	 <?php echo lang('account_personal_information'); ?>
				</div>
				<div class="card-body">
					<div class="form-group">
							<label><?php echo lang('account_first_name'); ?></label>
							<input class="form-control" type="text" name="user_firstname" value="<?php if(isset($user_firstname)) { echo $user_firstname; } ?>" />
								<?php if(isset($firstname_error)) { echo "<div class=\"small error\">".$firstname_error."</div>"; } else { ?>
								<?php } ?>
						</div>

						<div class="form-group">
							<label><?php echo lang('account_last_name'); ?></label>
							<input class="form-control" type="text" name="user_lastname" value="<?php if(isset($user_lastname)) { echo $user_lastname; } ?>" />
								<?php if(isset($lastname_error)) { echo "<div class=\"small error\">".$lastname_error."</div>"; } else { ?>
								<?php } ?>
						</div>

						<div class="form-group">
							<label><?php echo lang('account_callsign'); ?></label>
							<input class="form-control" type="text" name="user_callsign" value="<?php if(isset($user_callsign)) { echo $user_callsign; } ?>" />
								<?php if(isset($callsign_error)) { echo "<div class=\"small error\">".$callsign_error."</div>"; } else { ?>
								<?php } ?>
						</div>

						<div class="form-group">
							<label><?php echo lang('account_gridsquare'); ?></label>
							<input class="form-control" type="text" name="user_locator" value="<?php if(isset($user_locator)) { echo $user_locator; } ?>" />
								<?php if(isset($locator_error)) { echo "<div class=\"small error\">".$locator_error."</div>"; } else { ?>
								<?php } ?>
						</div>
				</div>
			</div>
	    </div>

	    <div class="col-md">
	    	<div class="card">
				<div class="card-header">
			   	 <?php echo lang('account_cloudlog_preferences'); ?>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label><?php echo lang('account_timezone'); ?></label>
						<?php echo form_dropdown('user_timezone', $timezones, $user_timezone); ?>
					</div>

					<div class="form-group">
						<label for="logendtime"><?php echo lang('account_log_end_time'); ?></label>
						<select class="custom-select" id="logendtimes" name="user_qso_end_times">
							<option value="1" <?php if ($user_qso_end_times == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
							<option value="0" <?php if ($user_qso_end_times == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
						</select>
					<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_log_end_time_hint'); ?></small>
					</div>

					<div class="form-group">
					<label for="SelectDateFormat"><?php echo lang('account_date_format'); ?></label>
						<select name="user_date_format" class="custom-select" id="SelectDateFormat" aria-describedby="SelectDateFormatHelp">
							<option value="d/m/y" <?php if($user_date_format == "d/m/y") { echo "selected=\"selected\""; } ?>><?php echo date('d/m/y'); ?></option>
							<option value="d/m/Y" <?php if($user_date_format == "d/m/Y") { echo "selected=\"selected\""; } ?>><?php echo date('d/m/Y'); ?></option>
							<option value="m/d/y" <?php if($user_date_format == "m/d/y") { echo "selected=\"selected\""; } ?>><?php echo date('m/d/y'); ?></option>
							<option value="m/d/Y" <?php if($user_date_format == "m/d/Y") { echo "selected=\"selected\""; } ?>><?php echo date('m/d/Y'); ?></option>
							<option value="d.m.Y" <?php if($user_date_format == "d.m.Y") { echo "selected=\"selected\""; } ?>><?php echo date('d.m.Y'); ?></option>
							<option value="y/m/d" <?php if($user_date_format == "y/m/d") { echo "selected=\"selected\""; } ?>><?php echo date('y/m/d'); ?></option>
							<option value="Y-m-d" <?php if($user_date_format == "Y-m-d") { echo "selected=\"selected\""; } ?>><?php echo date('Y-m-d'); ?></option>
							<option value="M d, Y" <?php if($user_date_format == "M d, Y") { echo "selected=\"selected\""; } ?>><?php echo date('M d, Y'); ?></option>
							<option value="M d, y" <?php if($user_date_format == "M d, y") { echo "selected=\"selected\""; } ?>><?php echo date('M d, y'); ?></option>
						</select>
						<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_select_how_you_would_like_dates_shown_when_logged_into_your_account'); ?></small>
					</div>

					<div class="form-group">
		                <label for="user_measurement_base"><?php echo lang('account_measurement_preferences'); ?></label>
		                <select class="custom-select" id="user_measurement_base" name="user_measurement_base" aria-describedby="user_measurement_base_Help" required>
		                    <option value ''></option>
                            <option value='K' <?php if($user_measurement_base == "K") { echo "selected=\"selected\""; } ?>>Kilometers</option>
		                    <option value='M' <?php if($user_measurement_base == "M") { echo "selected=\"selected\""; } ?>>Miles</option>
		                    <option value='N' <?php if($user_measurement_base == "N") { echo "selected=\"selected\""; } ?>>Nautical miles</option>
		                </select>
		                <small id="user_measurement_base_Help" class="form-text text-muted"><?php echo lang('account_choose_which_unit_distances_will_be_shown_in'); ?></small>
		            </div>

				<?php if ($this->config->item('cl_multilanguage')) { ?>
		 	    <div class="form-group">
		                <label for="language"><?php echo lang('account_cloudlog_language'); ?></label>
						<?php
						foreach($existing_languages as $lang){
							$options[$lang] = ucfirst($lang);
						}
						echo form_dropdown('language', $options, $language);
						?>
		                <small id="language_Help" class="form-text text-muted"><?php echo lang('account_choose_cloudlog_language'); ?></small>
		            </div>
				<?php } ?>
				</div>
			</div>
	    </div>
	</div>

	<br>
	<div class="row">
	 	<!-- Logbook of the World -->
	    <div class="col-md">
			<div class="card">
				<div class="card-header">
			   	 <?php echo lang('account_logbook_of_the_world'); ?>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label><?php echo lang('account_logbook_of_the_world_lotw_username'); ?></label>
						<input class="form-control" type="text" name="user_lotw_name" value="<?php if(isset($user_lotw_name)) { echo $user_lotw_name; } ?>" />
						<?php if(isset($userlotwname_error)) { echo "<div class=\"small error\">".$userlotwname_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label><?php echo lang('account_logbook_of_the_world_lotw_password'); ?></label>
						<input class="form-control" type="password" name="user_lotw_password" />
							<?php if(isset($lotwpassword_error)) { echo "<div class=\"small error\">".$lotwpassword_error."</div>"; } else { ?>
							<div class="small form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></div></td>
							<?php } ?>
					</div>
				</div>
			</div>
	    </div>

	    <!-- eQSL -->
	    <div class="col-md">
			<div class="card">
				<div class="card-header">
			   	 <?php echo lang('account_eqsl'); ?>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label><?php echo lang('account_eqslcc_username'); ?></label>
						<input class="form-control" type="text" name="user_eqsl_name" value="<?php if(isset($user_eqsl_name)) { echo $user_eqsl_name; } ?>" />
							<?php if(isset($eqslusername_error)) { echo "<div class=\"small error\">".$eqslusername_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label><?php echo lang('account_eqslcc_password'); ?></label>
						<input class="form-control" type="password" name="user_eqsl_password" />
							<?php if(isset($eqslpassword_error)) { echo "<div class=\"small error\">".$eqslpassword_error."</div>"; } else { ?>
							<div class="small form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></div></td>
							<?php } ?>
					</div>
				</div>
			</div>
	    </div>
	</div>

	<br>
	<div class="row">
	 	<!-- Club Log -->
	    <div class="col-md">
	    	<div class="card">
				<div class="card-header">
			   		<?php echo lang('account_clublog'); ?>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label><?php echo lang('account_clublog_email_callsign'); ?></label>
						<input class="form-control" type="text" name="user_clublog_name" value="<?php if(isset($user_clublog_name)) { echo $user_clublog_name; } ?>" />
							<div class="small form-text text-muted"><?php echo lang('account_the_email_or_callsign_you_use_to_login_to_club_log'); ?></div></td>
							<?php if(isset($userclublogname_error)) { echo "<div class=\"small error\">".$userclublogname_error."</div>"; } ?>

					</div>

					<div class="form-group">
						<label><?php echo lang('account_clublog_password'); ?></label>
						<input class="form-control" type="password" name="user_clublog_password" />
							<?php if(isset($clublogpassword_error)) { echo "<div class=\"small error\">".$clublogpassword_error."</div>"; } else { ?>
							<div class="small form-text text-muted"><?php echo lang('account_leave_blank_to_keep_existing_password'); ?></div></td>
							<?php } ?>
					</div>
				</div>
			</div>
	    </div>
			<div class="col-md">
				<div class="card">
					<div class="card-header">
						<?php echo lang('account_main_menu'); ?>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="shownotes"><?php echo lang('account_show_notes_in_the_main_menu'); ?></label>
							<select class="custom-select" id="shownotes" name="user_show_notes">
								<option value="1" <?php if ($user_show_notes == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
								<option value="0" <?php if ($user_show_notes == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="quicklog"><?php echo lang('account_quicklog_feature'); ?></label>
							<select class="custom-select" id="quicklog" name="user_quicklog">
								<option value="1" <?php if ($user_quicklog == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
								<option value="0" <?php if ($user_quicklog == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
							</select>
							<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_quicklog_feature_hint'); ?></small>
						</div>
						<div class="form-group">
							<label for="quicklog_enter"><?php echo lang('account_quicklog_enter'); ?></label>
							<select class="custom-select" id="quicklog_enter" name="user_quicklog_enter">
								<option value="0" <?php if ($user_quicklog_enter == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('account_quicklog_enter_log'); ?></option>
								<option value="1" <?php if ($user_quicklog_enter == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('account_quicklog_enter_search'); ?></option>
							</select>
							<small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_quicklog_enter_hint'); ?></small>
						</div>
					</div>
				</div>
			</div>
	</div>
		<br>
		<div class="row">
			<div class="col-md">
				<div class="card">
					<div class="card-header">
						<?php echo lang('account_gridsquare_and_location_autocomplete'); ?>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="qthlookup"><?php echo lang('account_location_auto_lookup'); ?></label>
								<select class="custom-select" id="qthlookup" name="user_qth_lookup">
									<option value="1" <?php if ($user_qth_lookup == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
									<option value="0" <?php if ($user_qth_lookup == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
								</select>
								<div class="small form-text text-muted"><?php echo lang('account_if_set_gridsquare_is_fetched_based_on_location_name'); ?></div></td>
							</div>
							<div class="form-group col-md-12">
								<label for="sotalookup"><?php echo lang('account_sota_auto_lookup_gridsquare_and_name_for_summit'); ?></label>
								<select class="custom-select" id="sotalookup" name="user_sota_lookup">
									<option value="1" <?php if ($user_sota_lookup == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
									<option value="0" <?php if ($user_sota_lookup == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
								</select>
								<div class="small form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></div></td>
							</div>
							<div class="form-group col-md-12">
								<label for="wwfflookup"><?php echo lang('account_wwff_auto_lookup_gridsquare_and_name_for_reference'); ?></label>
								<select class="custom-select" id="wwfflookup" name="user_wwff_lookup">
									<option value="1" <?php if ($user_wwff_lookup == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
									<option value="0" <?php if ($user_wwff_lookup == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
								</select>
								<div class="small form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></div></td>
							</div>
							<div class="form-group col-md-12">
								<label for="potalookup"><?php echo lang('account_pota_auto_lookup_gridsquare_and_name_for_park'); ?></label>
								<select class="custom-select" id="potalookup" name="user_pota_lookup">
									<option value="1" <?php if ($user_pota_lookup == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
									<option value="0" <?php if ($user_pota_lookup == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
								</select>
								<div class="small form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></div></td>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md">
				<div class="card">
					<div class="card-header">
						<?php echo lang('account_logbook_fields'); ?>
					</div>
					<div class="card-body">
						<div class="form-row">
						<div class="form-group col-md-12">
							<label for="column1"><?php echo lang('account_column1_text'); ?></label>
							<select class="custom-select" id="column1" name="user_column1">
								<option value="Band" <?php if ($user_column1 == "Band") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_band'); ?></option>
								<option value="Frequency" <?php if ($user_column1 == "Frequency") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
								<option value="Mode" <?php if ($user_column1 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column1 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column1 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column1 == "Country") { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column1 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_iota'); ?></option>
								<option value="SOTA" <?php if ($user_column1 == "SOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_sota'); ?></option>
								<option value="WWFF" <?php if ($user_column1 == "WWFF") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
								<option value="POTA" <?php if ($user_column1 == "POTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_pota'); ?></option>
								<option value="State" <?php if ($user_column1 == "State") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column1 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
								<option value="Distance" <?php if ($user_column1 == "Distance") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_distance'); ?></option>
								<option value="Operator" <?php if ($user_column1 == "Operator") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_operator'); ?></option>
							</select>
						</div>

							<div class="form-group col-md-12">
							<label for="column2"><?php echo lang('account_column2_text'); ?></label>
							<select class="custom-select" id="column2" name="user_column2">
								<option value="Band" <?php if ($user_column2 == "Band") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_band'); ?></option>
								<option value="Frequency" <?php if ($user_column2 == "Frequency") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
								<option value="Mode" <?php if ($user_column2 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column2 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column2 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column2 == "Country") { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column2 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_iota'); ?></option>
								<option value="SOTA" <?php if ($user_column2 == "SOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_sota'); ?></option>
								<option value="WWFF" <?php if ($user_column1 == "WWFF") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
								<option value="POTA" <?php if ($user_column1 == "POTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_pota'); ?></option>
								<option value="State" <?php if ($user_column2 == "State") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column2 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
								<option value="Distance" <?php if ($user_column2 == "Distance") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_distance'); ?></option>
								<option value="Operator" <?php if ($user_column2 == "Operator") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_operator'); ?></option>
							</select>
							</div>

							<div class="form-group col-md-12">
							<label for="column3"><?php echo lang('account_column3_text'); ?></label>
							<select class="custom-select" id="column3" name="user_column3">
								<option value="Band" <?php if ($user_column3 == "Band") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_band'); ?></option>
								<option value="Frequency" <?php if ($user_column3 == "Frequency") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
								<option value="Mode" <?php if ($user_column3 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column3 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column3 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column3 == "Country") { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column3 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_iota'); ?></option>
								<option value="SOTA" <?php if ($user_column3 == "SOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_sota'); ?></option>
								<option value="WWFF" <?php if ($user_column1 == "WWFF") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
								<option value="POTA" <?php if ($user_column1 == "POTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_pota'); ?></option>
								<option value="State" <?php if ($user_column3 == "State") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column3 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
								<option value="Distance" <?php if ($user_column3 == "Distance") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_distance'); ?></option>
								<option value="Operator" <?php if ($user_column3 == "Operator") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_operator'); ?></option>
							</select>
							</div>

							<div class="form-group col-md-12">
							<label for="column4"><?php echo lang('account_column4_text'); ?></label>
							<select class="custom-select" id="column4" name="user_column4">
								<option value="Band" <?php if ($user_column4 == "Band") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_band'); ?></option>
								<option value="Frequency" <?php if ($user_column4 == "Frequency") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
								<option value="Mode" <?php if ($user_column4 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column4 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column4 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column4 == "Country") { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column4 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_iota'); ?></option>
								<option value="SOTA" <?php if ($user_column4 == "SOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_sota'); ?></option>
								<option value="WWFF" <?php if ($user_column1 == "WWFF") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
								<option value="POTA" <?php if ($user_column1 == "POTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_pota'); ?></option>
								<option value="State" <?php if ($user_column4 == "State") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column4 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
								<option value="Distance" <?php if ($user_column4 == "Distance") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_distance'); ?></option>
								<option value="Operator" <?php if ($user_column4 == "Operator") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_operator'); ?></option>
							</select>
						</div>
							<div class="form-group col-md-12">
								<label for="column5"><?php echo lang('account_column5_text'); ?></label>
								<select class="custom-select" id="column5" name="user_column5">
									<option value="" <?php if ($user_column5 == "") { echo " selected =\"selected\""; } ?>></option>
									<option value="Band" <?php if ($user_column5 == "Band") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_band'); ?></option>
									<option value="Frequency" <?php if ($user_column5 == "Frequency") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_frequency'); ?></option>
									<option value="Mode" <?php if ($user_column5 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_mode'); ?></option>
									<option value="RSTS" <?php if ($user_column5 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rsts'); ?></option>
									<option value="RSTR" <?php if ($user_column5 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_rstr'); ?></option>
									<option value="Country" <?php if ($user_column5 == "Country") { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_country'); ?></option>
									<option value="IOTA" <?php if ($user_column5 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_iota'); ?></option>
									<option value="SOTA" <?php if ($user_column5 == "SOTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_sota'); ?></option>
									<option value="WWFF" <?php if ($user_column1 == "WWFF") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_wwff'); ?></option>
									<option value="POTA" <?php if ($user_column1 == "POTA") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_pota'); ?></option>
									<option value="State" <?php if ($user_column5 == "State") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_state'); ?></option>
									<option value="Grid" <?php if ($user_column5 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_gridsquare'); ?></option>
									<option value="Distance" <?php if ($user_column5 == "Distance") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_distance'); ?></option>
									<option value="Operator" <?php if ($user_column5 == "Operator") { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_operator'); ?></option>
									<option value="Location" <?php if ($user_column5 == "Location") { echo " selected =\"selected\""; } ?>><?php echo lang('cloudlog_station_profile'); ?></option>
								</select>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<?php echo lang('account_previous_qsl_type'); ?>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="profileimages"><?php echo lang('account_select_the_type_of_qsl_to_show_in_the_previous_qsos_section'); ?></label>
							<select class="custom-select" id="previousqsltype" name="user_previous_qsl_type">
								<option value="0" <?php if ($user_previous_qsl_type == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('gen_hamradio_qsl'); ?></option>
								<option value="1" <?php if ($user_previous_qsl_type == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('lotw_short'); ?></option>
								<option value="2" <?php if ($user_previous_qsl_type == 2) { echo " selected =\"selected\""; } ?>><?php echo lang('eqsl_short'); ?></option>
							</select>
						</div>

					</div>
				</div>
			</div>

			<!-- qrz.com Images -->
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<?php echo lang('account_qrzcom_hamqthcom_images'); ?>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="profileimages"><?php echo lang('account_show_profile_picture_of_qso_partner_from_qrzcom_hamqthcom_profile_in_the_log_qso_section'); ?></label>
							<select class="custom-select" id="profileimages" name="user_show_profile_image">
								<option value="1" <?php if ($user_show_profile_image == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
								<option value="0" <?php if ($user_show_profile_image == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
							</select>
							<div class="small form-text text-muted"><?php echo lang('account_please_set_your_qrzcom_hamqthcom_credentials_in_the_general_config_file'); ?></div></td>
						</div>

					</div>
				</div>
			</div>

		</div>
		<br>
		<div class="row">
			<div class="col-md">
				<div class="card">
					<div class="card-header">
						<?php echo lang('account_amsat_status_upload'); ?>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="amsatsatatusupload"><?php echo lang('account_upload_status_of_sat_qsos_to'); ?> <a href="https://www.amsat.org/status/" target="_blank">https://www.amsat.org/status/</a>.</label>
							<select class="custom-select" id="amsatstatusupload" name="user_amsat_status_upload">
								<option value="1" <?php if ($user_amsat_status_upload == 1) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_yes'); ?></option>
								<option value="0" <?php if ($user_amsat_status_upload == 0) { echo " selected =\"selected\""; } ?>><?php echo lang('general_word_no'); ?></option>
							</select>
						</div>

					</div>
				</div>
			</div>
			<div class="col-md">
				<div class="card">
		    			<div class="card-header">
					   	 <?php echo lang('account_mastodon'); ?>
					</div>
					<div class="card-body">
						<div class="form-group">
						<label><?php echo lang('account_user_mastodon'); ?></label>
							<input class="form-control" type="text" name="user_mastodon_url" value="<?php if(isset($user_mastodon_url)) { echo $user_mastodon_url; } ?>" />
							<div class="small form-text text-muted"><?php echo lang('account_user_mastodon_hint'); ?></a></div></td>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-md">
				<div class="card">
					<div class="card-header">
						<?php echo lang('account_default_band_settings'); ?>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="user_default_band"><?php echo lang('account_gridmap_default_band'); ?></label>

							<select id="user_default_band" class="form-control" name="user_default_band">
								<option value="All">All</option>;
							<?php foreach($bands as $band) {
								echo '<option value="'.$band.'"';
								if ($user_default_band == $band) {
									echo ' selected="selected"';
								}
								echo '>'.$band.'</option>'."\n";
							} ?>
							</select>
						</div>
						<div class="form-group">
							<label class="my-1 mr-2"><?php echo lang('account_qsl_settings'); ?></label>
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
						</div>
					</div>
				</div>
			</div>
		</div>

		<br>
		
		<div class="row">
				<div class="col-md">
					<div class="card">
						<div class="card-header">
							<?php echo lang('account_winkeyer'); ?> <span class="badge badge-danger"><?php echo lang('admin_experimental'); ?></span>
						</div>
						<div class="card-body">
							<div class="form-group">

							<p><?php echo lang('account_winkeyer_hint'); ?></p>

							<label><?php echo lang('account_winkeyer_enabled'); ?></label>
							
							<select class="custom-select" name="user_winkey" id="user_winkeyer">
								<option value="0" <?php if ($user_winkey == 0) { echo 'selected="selected"'; } ?>><?php echo lang('general_word_no'); ?></option>
								<option value="1" <?php if ($user_winkey == 1) { echo 'selected="selected"'; } ?>><?php echo lang('general_word_yes'); ?></option>
							</select>
							
							<div class="small form-text text-muted"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
	<br>
	<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo lang('account_save_account_changes'); ?></button>
	<br>
	<br>
</form>
</div>
