<div class="container">
    <h3>
	<?php echo lang('account_create_user_account'); ?>
    </h3>
    <?php if($this->session->flashdata('notice')) { ?>
    <div id="message">
        <?php echo $this->session->flashdata('notice'); ?>
    </div>
    <?php } ?>

    <?php $this->load->helper('form'); ?>
    <?php echo validation_errors(); ?>

    <form method="post" action="<?php echo site_url('user/add'); ?>" name="users">
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <?php echo lang('account_account_information'); ?>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label><?php echo lang('account_username'); ?></label>
                            <input class="form-control" type="text" name="user_name"
                                value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
                            <?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
                        </div>

                        <div class="form-group">
                            <label><?php echo lang('account_email_address'); ?></label>
                            <input class="form-control" type="text" name="user_email"
                                value="<?php if(isset($user_email)) { echo $user_email; } ?>" />
                            <?php if(isset($email_error)) { echo "<div class=\"small error\">".$email_error."</div>"; } ?>
                        </div>

                        <div class="form-group">
                            <label><?php echo lang('account_password'); ?></label>
                            <input class="form-control" type="password" name="user_password"
                                value="<?php if(isset($user_password)) { echo $user_password; } ?>" />
                            <?php if(isset($password_error)) { echo "<div class=\"small error\">".$password_error."</div>"; } ?>
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
                            <select class="custom-select" name="user_type">
                                <?php
										$levels = $this->config->item('auth_level');
										foreach ($levels as $key => $value) {
											echo '<option value="'. $key . '"';
											if(isset($user_type)) { 
												if($user_type == $key) { 
													echo "selected=\"selected\""; 
												} 
											} 
											echo '>' . $value . '</option>';
										} 
										?>
                            </select>
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
                            <label for="user_stylesheet"><?php echo lang('account_stylesheet'); ?></label>
                            <select class="custom-select" id="user_stylesheet" name="user_stylesheet" required>
                                <?php
				foreach ($themes as $theme) {
					echo '<option value="' . $theme->foldername . '"';
					if( $theme->foldername == 'default') {
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
        <br />
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <?php echo lang('account_personal_information'); ?>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label><?php echo lang('account_first_name'); ?></label>
                            <input class="form-control" type="text" name="user_firstname"
                                value="<?php if(isset($user_firstname)) { echo $user_firstname; } ?>" />
                            <?php if(isset($firstname_error)) { echo "<div class=\"small error\">".$firstname_error."</div>"; } ?>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('account_last_name'); ?></label>
                            <input class="form-control" type="text" name="user_lastname"
                                value="<?php if(isset($user_lastname)) { echo $user_lastname; } ?>" />
                            <?php if(isset($lastname_error)) { echo "<div class=\"small error\">".$lastname_error."</div>"; } ?>
                        </div>

                        <div class="form-group">
                            <label><?php echo lang('account_callsign'); ?></label>
                            <input class="form-control" type="text" name="user_callsign"
                                value="<?php if(isset($user_callsign)) { echo $user_callsign; } ?>" />
                            <?php if(isset($callsign_error)) { echo "<div class=\"small error\">".$callsign_error."</div>"; } ?>
                        </div>

                        <div class="form-group">
                            <label><?php echo lang('account_gridsquare'); ?></label>
                            <input class="form-control" type="text" name="user_locator"
                                value="<?php if(isset($user_locator)) { echo $user_locator; } ?>" />
                            <?php if(isset($locator_error)) { echo "<div class=\"small error\">".$locator_error."</div>"; } ?>
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
                            <?php
					if(!isset($user_timezone)) { $user_timezone = 0; }
					echo form_dropdown('user_timezone', $timezones, $user_timezone);
					?>
                        </div>

                        <div class="form-group">
                            <label for="logendtime"><?php echo lang('account_log_end_time'); ?></label>
                            <select class="custom-select" id="logendtime" name="user_qso_end_times">
                                <option value="0"><?php echo lang('general_word_no'); ?></option>
                                <option value="1"><?php echo lang('general_word_yes'); ?></option>
                            </select>
                            <small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_log_end_time_hint'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="SelectDateFormat"><?php echo lang('account_date_format'); ?></label>
                            <select name="user_date_format" class="custom-select" id="SelectDateFormat"
                                aria-describedby="SelectDateFormatHelp">
                                <option value="">Select Format</option>
                                <option value="d/m/y"><?php echo date('d/m/y'); ?></option>
                                <option value="d/m/Y"><?php echo date('d/m/Y'); ?></option>
                                <option value="m/d/y"><?php echo date('m/d/y'); ?></option>
                                <option value="m/d/Y"><?php echo date('m/d/Y'); ?></option>
                                <option value="d.m.Y"><?php echo date('d.m.Y'); ?></option>
                                <option value="Y-m-d"><?php echo date('Y-m-d'); ?></option>
                            </select>

                            <small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_select_how_you_would_like_dates_shown_when_logged_into_your_account'); ?></small>
                        </div>


                        <div class="form-group">
                            <label for="user_measurement_base"><?php echo lang('account_measurement_preferences'); ?></label>
                            <select class="custom-select" id="user_measurement_base" name="user_measurement_base"
                                required>
                                <option value=''></option>
                                <option value='K'
                                    <?php if($measurement_base == "K") { echo "selected=\"selected\""; } ?>>
                                    Kilometers</option>
                                <option value='M'
                                    <?php if($measurement_base == "M") { echo "selected=\"selected\""; } ?>>
                                    Miles</option>
                                <option value='N'
                                    <?php if($measurement_base == "N") { echo "selected=\"selected\""; } ?>>
                                    Nautical miles</option>
                            </select>
                            <small id="user_measurement_base_Help" class="form-text text-muted"><?php echo lang('account_choose_which_unit_distances_will_be_shown_in'); ?></small>
                        </div>
				<?php if ($this->config->item('cl_multilanguage')) { ?>
		 	    <div class="form-group">
		                <label for="language">Cloudlog Language</label>
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
        <br />
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                	<?php echo lang('account_main_menu'); ?>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="shownotes"><?php echo lang('account_show_notes_in_the_main_menu'); ?></label>
                            <select class="custom-select" id="shownotes" name="user_show_notes">
                                <option value="0"><?php echo lang('general_word_no'); ?></option>
                                <option value="1"><?php echo lang('general_word_yes'); ?></option>
                            </select>
                            <small> </small>
                        </div>
                        <div class="form-group">
                            <label for="quicklog"><?php echo lang('account_quicklog_feature'); ?></label>
                            <select class="custom-select" id="quicklog" name="user_quicklog">
                                <option value="0"><?php echo lang('general_word_no'); ?></option>
                                <option value="1"><?php echo lang('general_word_yes'); ?></option>
                            </select>
                            <small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_quicklog_feature_hint'); ?></small>
                        </div>
                        <div class="form-group">
                            <label for="quicklog_enter"><?php echo lang('account_quicklog_enter'); ?></label>
                            <select class="custom-select" id="quicklog_enter" name="user_quicklog_enter">
                                <option value="0"><?php echo lang('account_quicklog_enter_log'); ?></option>
                                <option value="1"><?php echo lang('account_quicklog_enter_search'); ?></option>
                            </select>
                            <small id="SelectDateFormatHelp" class="form-text text-muted"><?php echo lang('account_quicklog_enter_hint'); ?></small>
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
                        <?php echo lang('account_gridsquare_and_location_autocomplete'); ?>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="qthlookup"><?php echo lang('account_location_auto_lookup'); ?></label>
                                <select class="custom-select" id="qthlookup" name="user_qth_lookup">
                                    <option value="0"><?php echo lang('general_word_no'); ?>
                                    </option>
                                    <option value="1"><?php echo lang('general_word_yes'); ?>
                                    </option>
                                </select>
                                <div class="small form-text text-muted"><?php echo lang('account_if_set_gridsquare_is_fetched_based_on_location_name'); ?></div>
                                </td>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="sotalookup"><?php echo lang('account_sota_auto_lookup_gridsquare_and_name_for_summit'); ?></label>
                                <select class="custom-select" id="sotalookup" name="user_sota_lookup">
                                    <option value="0"><?php echo lang('general_word_no'); ?>
                                    </option>
                                    <option value="1"><?php echo lang('general_word_yes'); ?>
                                    </option>
                                </select>
                                <div class="small form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></div>
                                </td>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="wwfflookup"><?php echo lang('account_wwff_auto_lookup_gridsquare_and_name_for_reference'); ?></label>
                                <select class="custom-select" id="wwfflookup" name="user_wwff_lookup">
                                    <option value="0"><?php echo lang('general_word_no'); ?>
                                    </option>
                                    <option value="1"><?php echo lang('general_word_yes'); ?>
                                    </option>
                                </select>
				<div class="small form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></div>
                                </td>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="potalookup"><?php echo lang('account_pota_auto_lookup_gridsquare_and_name_for_park'); ?></label>
                                <select class="custom-select" id="potalookup" name="user_pota_lookup">
                                    <option value="0"><?php echo lang('general_word_no'); ?>
                                    </option>
                                    <option value="1"><?php echo lang('general_word_yes'); ?>
                                    </option>
                                </select>
				<div class="small form-text text-muted"><?php echo lang('account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'); ?></div>
                                </td>
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

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label
                                        for="column1"><?php echo lang('account_column1_text'); ?></label>
                                    <select class="custom-select" id="column1" name="user_column1">
                                        <option value="Band"> <?php echo lang('gen_hamradio_band'); ?>
                                        </option>
                                        <option value="Frequency">
                                            <?php echo lang('gen_hamradio_frequency'); ?></option>
                                        <option value="Mode" selected='selected'>
                                            <?php echo lang('gen_hamradio_mode'); ?></option>
                                        <option value="RSTS"><?php echo lang('gen_hamradio_rsts'); ?>
                                        </option>
                                        <option value="RSTR"><?php echo lang('gen_hamradio_rstr'); ?>
                                        </option>
                                        <option value="Country">
                                            <?php echo lang('general_word_country'); ?></option>
                                        <option value="IOTA"><?php echo lang('gen_hamradio_iota'); ?>
                                        </option>
                                        <option value="SOTA"><?php echo lang('gen_hamradio_sota'); ?>
                                        </option>
                                        <option value="State"><?php echo lang('gen_hamradio_state'); ?>
                                        </option>
                                        <option value="Grid">
                                            <?php echo lang('gen_hamradio_gridsquare'); ?></option>
                                        <option value="Distance">
                                            <?php echo lang('gen_hamradio_distance'); ?></option>
                                        <option value="Operator">
                                            <?php echo lang('gen_hamradio_operator'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label
                                        for="column2"><?php echo lang('account_column2_text'); ?></label>
                                    <select class="custom-select" id="column2" name="user_column2">
                                        <option value="Band"> <?php echo lang('gen_hamradio_band'); ?>
                                        </option>
                                        <option value="Frequency">
                                            <?php echo lang('gen_hamradio_frequency'); ?></option>
                                        <option value="Mode"> <?php echo lang('gen_hamradio_mode'); ?>
                                        </option>
                                        <option value="RSTS" selected='selected'>
                                            <?php echo lang('gen_hamradio_rsts'); ?></option>
                                        <option value="RSTR"><?php echo lang('gen_hamradio_rstr'); ?>
                                        </option>
                                        <option value="Country">
                                            <?php echo lang('general_word_country'); ?></option>
                                        <option value="IOTA"><?php echo lang('gen_hamradio_iota'); ?>
                                        </option>
                                        <option value="SOTA"><?php echo lang('gen_hamradio_sota'); ?>
                                        </option>
                                        <option value="State"><?php echo lang('gen_hamradio_state'); ?>
                                        </option>
                                        <option value="Grid">
                                            <?php echo lang('gen_hamradio_gridsquare'); ?></option>
                                        <option value="Distance">
                                            <?php echo lang('gen_hamradio_distance'); ?></option>
                                        <option value="Operator">
                                            <?php echo lang('gen_hamradio_operator'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label
                                        for="column3"><?php echo lang('account_column3_text'); ?></label>
                                    <select class="custom-select" id="column3" name="user_column3">
                                        <option value="Band"> <?php echo lang('gen_hamradio_band'); ?>
                                        </option>
                                        <option value="Frequency">
                                            <?php echo lang('gen_hamradio_frequency'); ?></option>
                                        <option value="Mode"> <?php echo lang('gen_hamradio_mode'); ?>
                                        </option>
                                        <option value="RSTS"><?php echo lang('gen_hamradio_rsts'); ?>
                                        </option>
                                        <option value="RSTR" selected='selected'>
                                            <?php echo lang('gen_hamradio_rstr'); ?></option>
                                        <option value="Country">
                                            <?php echo lang('general_word_country'); ?></option>
                                        <option value="IOTA"><?php echo lang('gen_hamradio_iota'); ?>
                                        </option>
                                        <option value="SOTA"><?php echo lang('gen_hamradio_sota'); ?>
                                        </option>
                                        <option value="State"><?php echo lang('gen_hamradio_state'); ?>
                                        </option>
                                        <option value="Grid">
                                            <?php echo lang('gen_hamradio_gridsquare'); ?></option>
                                        <option value="Distance">
                                            <?php echo lang('gen_hamradio_distance'); ?></option>
                                        <option value="Operator">
                                            <?php echo lang('gen_hamradio_operator'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label
                                        for="column4"><?php echo lang('account_column4_text'); ?></label>
                                    <select class="custom-select" id="column4" name="user_column4">
                                        <option value="Band" selected='selected'>
                                            <?php echo lang('gen_hamradio_band'); ?></option>
                                        <option value="Frequency">
                                            <?php echo lang('gen_hamradio_frequency'); ?></option>
                                        <option value="Mode"> <?php echo lang('gen_hamradio_mode'); ?>
                                        </option>
                                        <option value="RSTS"><?php echo lang('gen_hamradio_rsts'); ?>
                                        </option>
                                        <option value="RSTR"><?php echo lang('gen_hamradio_rstr'); ?>
                                        </option>
                                        <option value="Country">
                                            <?php echo lang('general_word_country'); ?></option>
                                        <option value="IOTA"><?php echo lang('gen_hamradio_iota'); ?>
                                        </option>
                                        <option value="SOTA"><?php echo lang('gen_hamradio_sota'); ?>
                                        </option>
                                        <option value="State"><?php echo lang('gen_hamradio_state'); ?>
                                        </option>
                                        <option value="Grid">
                                            <?php echo lang('gen_hamradio_gridsquare'); ?></option>
                                        <option value="Distance">
                                            <?php echo lang('gen_hamradio_distance'); ?></option>
                                        <option value="Operator">
                                            <?php echo lang('gen_hamradio_operator'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label
                                        for="column5"><?php echo lang('account_column5_text'); ?></label>
                                    <select class="custom-select" id="column5" name="user_column5">
                                        <option value=""></option>
                                        <option value="Band"> <?php echo lang('gen_hamradio_band'); ?>
                                        </option>
                                        <option value="Frequency">
                                            <?php echo lang('gen_hamradio_frequency'); ?></option>
                                        <option value="Mode"> <?php echo lang('gen_hamradio_mode'); ?>
                                        </option>
                                        <option value="RSTS"><?php echo lang('gen_hamradio_rsts'); ?>
                                        </option>
                                        <option value="RSTR"><?php echo lang('gen_hamradio_rstr'); ?>
                                        </option>
                                        <option value="Country" selected='selected'>
                                            <?php echo lang('general_word_country'); ?></option>
                                        <option value="IOTA"><?php echo lang('gen_hamradio_iota'); ?>
                                        </option>
                                        <option value="SOTA"><?php echo lang('gen_hamradio_sota'); ?>
                                        </option>
                                        <option value="State"><?php echo lang('gen_hamradio_state'); ?>
                                        </option>
                                        <option value="Grid">
                                            <?php echo lang('gen_hamradio_gridsquare'); ?></option>
                                        <option value="Distance">
                                            <?php echo lang('gen_hamradio_distance'); ?></option>
                                        <option value="Operator">
                                            <?php echo lang('gen_hamradio_operator'); ?></option>
                                        <option value="Location">
                                            <?php echo lang('cloudlog_station_profile'); ?></option>
                                    </select>
                                </div>
                            </div>

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
                        <?php echo lang('account_previous_qsl_type'); ?>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="previousqsltype"><?php echo lang('account_select_the_type_of_qsl_to_show_in_the_previous_qsos_section'); ?></label>
                            <select class="custom-select" id="previousqsltype" name="user_previous_qsl_type">
                                <option value="0"><?php echo lang('gen_hamradio_qsl'); ?></option>
                                <option value="1"><?php echo lang('lotw_short'); ?></option>
                                <option value="2"><?php echo lang('eqsl_short'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <?php echo lang('account_qrzcom_hamqthcom_images'); ?>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="profileimages"><?php echo lang('account_show_profile_picture_of_qso_partner_from_qrzcom_hamqthcom_profile_in_the_log_qso_section'); ?></label>
                            <select class="custom-select" id="profileimages" name="user_show_profile_image">
                                <option value="0"><?php echo lang('general_word_no'); ?></option>
                                <option value="1"><?php echo lang('general_word_yes'); ?></option>
                            </select>
                            <div class="small form-text text-muted"><?php echo lang('account_please_set_your_qrzcom_hamqthcom_credentials_in_the_general_config_file'); ?></div>
                            </td>
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
                         <?php echo lang('account_amsat_status_upload'); ?>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="amsatstatusupload"><?php echo lang('account_upload_status_of_sat_qsos_to'); ?> <a href="https://www.amsat.org/status/" target="_blank">https://www.amsat.org/status/</a>.</label>
                            <select class="custom-select" id="amsatstatusupload" name="user_amsat_status_upload">
                                <option value="0"><?php echo lang('general_word_no'); ?></option>
                                <option value="1"><?php echo lang('general_word_yes'); ?></option>
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
                                        if (isset($user_default_band) && $user_default_band == $band) {
                                           echo ' selected';
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
                                    <label class="form-check-label" for="user_default_confirmation_qsl">QSL</label>
                                </div>
                                <div class="form-check-inline">
                                    <?php echo '<input class="form-check-input" type="checkbox" name="user_default_confirmation_lotw" id="user_default_confirmation_lotw"';
                                        if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'L') !== false) {
                                           echo ' checked';
                                        }
                                        echo '>'; ?>
                                    <label class="form-check-label" for="user_default_confirmation_lotw">LoTW</label>
                                </div>
                                <div class="form-check-inline">
                                    <?php echo '<input class="form-check-input" type="checkbox" name="user_default_confirmation_eqsl" id="user_default_confirmation_eqsl"';
                                        if (isset($user_default_confirmation) && strpos($user_default_confirmation, 'E') !== false) {
                                           echo ' checked';
                                        }
                                        echo '>'; ?>
                                    <label class="form-check-label" for="user_default_confirmation_eqsl">eQSL</label>
                                </div>
                             </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
        <br />
        <button type="submit" class="btn btn-primary">Create Account</button>
        <br />
        <br />
    </form>
</div>
