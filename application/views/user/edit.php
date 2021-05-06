<div class="container">
	<h3>
	  Edit Account
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
				   	 Account Information
					</div>
				<div class="card-body">
					<div class="form-group">
						<label>Username</label>
						<input class="form-control" type="text" name="user_name" value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
						<?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label>Email</label>
						<input class="form-control" type="text" name="user_email" value="<?php if(isset($user_email)) { echo $user_email; } ?>" />
						<?php if(isset($email_error)) { echo "<div class=\"small error\">".$email_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label>Password</label>
						<input class="form-control" type="password" name="user_password" />
						<?php if(isset($password_error)) { echo "<div class=\"small error\">".$password_error."</div>"; } else { ?>
						<div class="small form-text text-muted">Leave blank to keep existing password</div></td>
						<?php } ?>
					</div>
				</div>
			</div>
	    </div>

	    <div class="col-md">
	    	<div class="card">
		    	<div class="card-header">
				   	 Roles
					</div>
				<div class="card-body">
					<div class="form-group">
						<label>Level</label>

						<?php if($this->session->userdata('user_type') == 99) { ?>
						<select class="custom-select" name="user_type">
						<?php
							$levels = $this->config->item('auth_level');
							while (list($key, $val) = each($levels)) {
						?>
							<option value="<?php echo $key; ?>" <?php if($user_type == $key) { echo "selected=\"selected\""; } ?>><?php echo $val; ?></option>
							<?php } ?>
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
                    Theme
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Stylesheet</label>
                        <select class="custom-select" id="user_stylesheet" name="user_stylesheet" required>
                            <option value='default' <?php if($user_stylesheet == "default") { echo "selected=\"selected\""; } ?>>Default</option>
                            <option value='blue' <?php if($user_stylesheet == "blue") { echo "selected=\"selected\""; } ?>>Blue</option>
                            <option value='cosmo' <?php if($user_stylesheet == "cosmo") { echo "selected=\"selected\""; } ?>>Cosmo</option>
                            <option value='cyborg' <?php if($user_stylesheet == "cyborg") { echo "selected=\"selected\""; } ?>>Cyborg (Dark)</option>
                            <option value='darkly' <?php if($user_stylesheet == "darkly") { echo "selected=\"selected\""; } ?>> Darkly (Dark)</option>
                            <option value='superhero' <?php if($user_stylesheet == "superhero") { echo "selected=\"selected\""; } ?>>Superhero (Dark)</option>
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
			   	 Personal Information
				</div>
				<div class="card-body">
					<div class="form-group">
							<label>First name</label>
							<input class="form-control" type="text" name="user_firstname" value="<?php if(isset($user_firstname)) { echo $user_firstname; } ?>" />
								<?php if(isset($firstname_error)) { echo "<div class=\"small error\">".$firstname_error."</div>"; } else { ?>
								<?php } ?>
						</div>

						<div class="form-group">
							<label>Last name</label>
							<input class="form-control" type="text" name="user_lastname" value="<?php if(isset($user_lastname)) { echo $user_lastname; } ?>" />
								<?php if(isset($lastname_error)) { echo "<div class=\"small error\">".$lastname_error."</div>"; } else { ?>
								<?php } ?>
						</div>

						<div class="form-group">
							<label>Callsign</label>
							<input class="form-control" type="text" name="user_callsign" value="<?php if(isset($user_callsign)) { echo $user_callsign; } ?>" />
								<?php if(isset($callsign_error)) { echo "<div class=\"small error\">".$callsign_error."</div>"; } else { ?>
								<?php } ?>
						</div>

						<div class="form-group">
							<label>Locator</label>
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
			   	 Cloudlog Preferences
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Timezone</label>
						<?php echo form_dropdown('user_timezone', $timezones, $user_timezone); ?>
					</div>

					<div class="form-group">
					<label for="SelectDateFormat">Date Format</label>
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
						<small id="SelectDateFormatHelp" class="form-text text-muted">Select how you would like dates shown when logged into your account.</small>
					</div>

					<div class="form-group">
		                <label for="user_measurement_base">Measurement preference</label>
		                <select class="custom-select" id="user_measurement_base" name="user_measurement_base" aria-describedby="user_measurement_base_Help" required>
		                    <option value ''></option>
                            <option value='K' <?php if($user_measurement_base == "K") { echo "selected=\"selected\""; } ?>>Kilometers</option>
		                    <option value='M' <?php if($user_measurement_base == "M") { echo "selected=\"selected\""; } ?>>Miles</option>
		                    <option value='N' <?php if($user_measurement_base == "N") { echo "selected=\"selected\""; } ?>>Nautical miles</option>
		                </select>
		                <small id="user_measurement_base_Help" class="form-text text-muted">Choose which unit distances will be shown in.</small>
		            </div>
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
			   	 Logbook of the World
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Logbook of The World (LoTW) Username</label>
						<input class="form-control" type="text" name="user_lotw_name" value="<?php if(isset($user_lotw_name)) { echo $user_lotw_name; } ?>" />
						<?php if(isset($userlotwname_error)) { echo "<div class=\"small error\">".$userlotwname_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label>Logbook of The World (LoTW) Password</label>
						<input class="form-control" type="password" name="user_lotw_password" />
							<?php if(isset($lotwpassword_error)) { echo "<div class=\"small error\">".$lotwpassword_error."</div>"; } else { ?>
							<div class="small form-text text-muted">Leave blank to keep existing password</div></td>
							<?php } ?>
					</div>
				</div>
			</div>
	    </div>

	    <!-- eQSL -->
	    <div class="col-md">
			<div class="card">
				<div class="card-header">
			   	 eQSL
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>eQSL.cc Username</label>
						<input class="form-control" type="text" name="user_eqsl_name" value="<?php if(isset($user_eqsl_name)) { echo $user_eqsl_name; } ?>" />
							<?php if(isset($eqslusername_error)) { echo "<div class=\"small error\">".$eqslusername_error."</div>"; } ?>
					</div>

					<div class="form-group">
						<label>eQSL.cc Password</label>
						<input class="form-control" type="password" name="user_eqsl_password" />
							<?php if(isset($eqslpassword_error)) { echo "<div class=\"small error\">".$eqslpassword_error."</div>"; } else { ?>
							<div class="small form-text text-muted">Leave blank to keep existing password</div></td>
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
			   		Club Log
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Club Log Email/Callsign</label>
						<input class="form-control" type="text" name="user_clublog_name" value="<?php if(isset($user_clublog_name)) { echo $user_clublog_name; } ?>" />
							<div class="small form-text text-muted">The Email or Callsign you use to login to Club Log</div></td>
							<?php if(isset($userclublogname_error)) { echo "<div class=\"small error\">".$userclublogname_error."</div>"; } ?>

					</div>

					<div class="form-group">
						<label>Club Log Password</label>
						<input class="form-control" type="password" name="user_clublog_password" />
							<?php if(isset($clublogpassword_error)) { echo "<div class=\"small error\">".$clublogpassword_error."</div>"; } else { ?>
							<div class="small form-text text-muted">Leave blank to keep existing password</div></td>
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
						Summits On The Air
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="sotalookup">SOTA auto lookup gridsquare and name for summit.</label>
							<select class="custom-select" id="sotalookup" name="user_sota_lookup">
								<option value="1" <?php if ($user_sota_lookup == 1) { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_yes'); ?></option>
								<option value="0" <?php if ($user_sota_lookup == 0) { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_no'); ?></option>
							</select>
							<div class="small form-text text-muted">If this is set, name and gridsquare is fetched from the API and filled in location and locator.</div></td>
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
						Main menu
					</div>
					<div class="card-body">
						<div class="form-group">
							<label for="shownotes">Show notes in the main menu.</label>
							<select class="custom-select" id="shownotes" name="user_show_notes">
								<option value="1" <?php if ($user_show_notes == 1) { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_yes'); ?></option>
								<option value="0" <?php if ($user_show_notes == 0) { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_no'); ?></option>
							</select>
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
						<?php echo $this->lang->line('account_logbook_fields'); ?>
					</div>
					<div class="card-body">
						<div class="form-row">
						<div class="form-group col-md-3">
							<label for="column1"><?php echo $this->lang->line('account_column1_text'); ?></label>
							<select class="custom-select" id="column1" name="user_column1">
								<option value="Band" <?php if ($user_column1 == "Band") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_band'); ?></option>
								<option value="Mode" <?php if ($user_column1 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column1 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column1 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column1 == "Country") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column1 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
								<option value="State" <?php if ($user_column1 == "State") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column1 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
							</select>
						</div>

							<div class="form-group col-md-3">
							<label for="column2"><?php echo $this->lang->line('account_column2_text'); ?></label>
							<select class="custom-select" id="column2" name="user_column2">
								<option value="Band" <?php if ($user_column2 == "Band") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_band'); ?></option>
								<option value="Mode" <?php if ($user_column2 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column2 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column2 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column2 == "Country") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column2 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
								<option value="State" <?php if ($user_column2 == "State") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column2 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
							</select>
							</div>

							<div class="form-group col-md-3">
							<label for="column3"><?php echo $this->lang->line('account_column3_text'); ?></label>
							<select class="custom-select" id="column3" name="user_column3">
								<option value="Band" <?php if ($user_column3 == "Band") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_band'); ?></option>
								<option value="Mode" <?php if ($user_column3 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column3 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column3 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column3 == "Country") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column3 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
								<option value="State" <?php if ($user_column3 == "State") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column3 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
							</select>
							</div>

							<div class="form-group col-md-3">
							<label for="column4"><?php echo $this->lang->line('account_column4_text'); ?></label>
							<select class="custom-select" id="column4" name="user_column4">
								<option value="Band" <?php if ($user_column4 == "Band") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_band'); ?></option>
								<option value="Mode" <?php if ($user_column4 == "Mode") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
								<option value="RSTS" <?php if ($user_column4 == "RSTS") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
								<option value="RSTR" <?php if ($user_column4 == "RSTR") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
								<option value="Country" <?php if ($user_column4 == "Country") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('general_word_country'); ?></option>
								<option value="IOTA" <?php if ($user_column4 == "IOTA") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
								<option value="State" <?php if ($user_column4 == "State") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
								<option value="Grid" <?php if ($user_column4 == "Grid") { echo " selected =\"selected\""; } ?>><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
							</select>
						</div>
						</div>

					</div>
				</div>
			</div>
		</div>

	<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
	<br>
	<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Account Changes</button>
	<br>
	<br>
</form>
</div>
