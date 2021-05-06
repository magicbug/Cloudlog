
<div class="container">

<br>
<?php if($this->session->flashdata('notice')) { ?>
    <div id="message" >
        <?php echo $this->session->flashdata('notice'); ?>
    </div>
<?php } ?>

<div class="card">
  <div class="card-header">Create User Account</div>
  <div class="card-body">
    <h5 class="card-title"></h5>
    <p class="card-text"></p>

    <?php $this->load->helper('form'); ?>
    <?php echo validation_errors(); ?>

    <form method="post" action="<?php echo site_url('user/add'); ?>" name="users">

		<div class="form-group">
			<label>Username</label>
		    <input class="form-control" type="text" name="user_name" value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
			<?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
		</div>

		<div class="form-group">
			<label>User Role</label>
		    <select class="custom-select" name="user_type">
			<?php
				$levels = $this->config->item('auth_level');
				while (list($key, $val) = each($levels)) {
			?>
					<option value="<?php echo $key; ?>" <?php if(isset($user_type)) { if($user_type == $key) { echo "selected=\"selected\""; } } ?>><?php echo $val; ?></option>
				<?php } ?>
			</select>
		  </div>

		<div class="form-group">
			<label>Email Address</label>
			<input class="form-control" type="text" name="user_email" value="<?php if(isset($user_email)) { echo $user_email; } ?>" />
			<?php if(isset($email_error)) { echo "<div class=\"small error\">".$email_error."</div>"; } ?>
		</div>

		<div class="form-group">
			<label>Password</label>
		    <input class="form-control" type="password" name="user_password" value="<?php if(isset($user_password)) { echo $user_password; } ?>" />
				<?php if(isset($password_error)) { echo "<div class=\"small error\">".$password_error."</div>"; } ?>
		</div>

		<div class="form-group">
			<label>First Name</label>
		    <input class="form-control" type="text" name="user_firstname" value="<?php if(isset($user_firstname)) { echo $user_firstname; } ?>" />
			<?php if(isset($firstname_error)) { echo "<div class=\"small error\">".$firstname_error."</div>"; } ?>
		</div>

		<div class="form-group">
		    <label>Last Name</label>
		    <input class="form-control" type="text" name="user_lastname" value="<?php if(isset($user_lastname)) { echo $user_lastname; } ?>" />
			<?php if(isset($lastname_error)) { echo "<div class=\"small error\">".$lastname_error."</div>"; } ?>
		</div>

		<div class="form-group">
		    <label>Callsign</label>
		    <input class="form-control" type="text" name="user_callsign" value="<?php if(isset($user_callsign)) { echo $user_callsign; } ?>" />
			<?php if(isset($callsign_error)) { echo "<div class=\"small error\">".$callsign_error."</div>"; } ?>
		</div>

		<div class="form-group">
		    <label>Locator</label>
		    <input class="form-control" type="text" name="user_locator" value="<?php if(isset($user_locator)) { echo $user_locator; } ?>" />
			<?php if(isset($locator_error)) { echo "<div class=\"small error\">".$locator_error."</div>"; } ?>
		</div>

		<div class="form-group">
		    <label>Timezone</label>
			<?php
				if(!isset($user_timezone)) { $user_timezone = 0; }
				echo form_dropdown('user_timezone', $timezones, $user_timezone);
			?>
		</div>

		<div class="form-group">
        	<label for="SelectDateFormat">Date Format</label>
            <select name="user_date_format" class="custom-select" id="SelectDateFormat" aria-describedby="SelectDateFormatHelp">
            	<option value="">Select Format</option>
            	<option value="d/m/y"><?php echo date('d/m/y'); ?></option>
            	<option value="d/m/Y"><?php echo date('d/m/Y'); ?></option>
            	<option value="m/d/y"><?php echo date('m/d/y'); ?></option>
            	<option value="m/d/Y"><?php echo date('m/d/Y'); ?></option>
            	<option value="d.m.Y"><?php echo date('d.m.Y'); ?></option>
            	<option value="Y-m-d"><?php echo date('Y-m-d'); ?></option>
            </select>

            <small id="SelectDateFormatHelp" class="form-text text-muted">Select how you would like dates shown when logged into your account.</small>
        </div>


        <div class="form-group">
            <label for="user_measurement_base">Measurement preference</label>
            <select class="custom-select" id="user_measurement_base" name="user_measurement_base" required>
                <option value=''></option>
                <option value='K' <?php if($measurement_base == "K") { echo "selected=\"selected\""; } ?>>Kilometers</option>
                <option value='M' <?php if($measurement_base == "M") { echo "selected=\"selected\""; } ?>>Miles</option>
                <option value='N' <?php if($measurement_base == "N") { echo "selected=\"selected\""; } ?>>Nautical miles</option>
            </select>
            <small id="user_measurement_base_Help" class="form-text text-muted">Choose which unit distances will be shown in.</small>
        </div>

        <div class="form-group">
            <label for="user_stylesheet">Theme</label>
            <select class="custom-select" id="user_stylesheet" name="user_stylesheet" required>
                <option value='default' selected="selected">Default</option>
                <option value='blue'>Blue</option>
                <option value='cosmo'>Cosmo</option>
                <option value='cyborg'>Cyborg (Dark)</option>
                <option value='darkly'>Darkly (Dark)</option>
                <option value='superhero'>Superhero (Dark)</option>
            </select>
        </div>

		<div class="form-group">
			<label for="sotalookup">SOTA auto lookup gridsquare and name for summit.</label>
			<select class="custom-select" id="sotalookup" name="user_sota_lookup">
				<option value="0"><?php echo $this->lang->line('general_word_no'); ?></option>
				<option value="1"><?php echo $this->lang->line('general_word_yes'); ?></option>
			</select>
			<div class="small form-text text-muted">If this is set, name and gridsquare is fetched from the API and filled in location and locator.</div></td>
		</div>

		<div class="form-group">
			<label for="shownotes">Show notes in the main menu.</label>
			<select class="custom-select" id="shownotes" name="user_show_notes">
				<option value="0"><?php echo $this->lang->line('general_word_no'); ?></option>
				<option value="1"><?php echo $this->lang->line('general_word_yes'); ?></option>
			</select>
		</div>
		<div class="form-row">
			<div class="form-group col-md-3">
			<label for="column1"><?php echo $this->lang->line('account_column1_text'); ?></label>
			<select class="custom-select" id="column1" name="user_column1">
				<option value="Band"> <?php echo $this->lang->line('gen_hamradio_band'); ?></option>
				<option value="Mode" selected ='selected'> <?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
				<option value="RSTS"><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
				<option value="RSTR"><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
				<option value="Country"><?php echo $this->lang->line('general_word_country'); ?></option>
				<option value="IOTA"><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
				<option value="State"><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
				<option value="Grid"><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
			</select>
			</div>

			<div class="form-group col-md-3">
			<label for="column2"><?php echo $this->lang->line('account_column2_text'); ?></label>
			<select class="custom-select" id="column2" name="user_column2">
				<option value="Band"> <?php echo $this->lang->line('gen_hamradio_band'); ?></option>
				<option value="Mode"> <?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
				<option value="RSTS" selected ='selected'><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
				<option value="RSTR"><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
				<option value="Country"><?php echo $this->lang->line('general_word_country'); ?></option>
				<option value="IOTA"><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
				<option value="State"><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
				<option value="Grid"><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
			</select>
			</div>

			<div class="form-group col-md-3">
			<label for="column3"><?php echo $this->lang->line('account_column3_text'); ?></label>
			<select class="custom-select" id="column3" name="user_column3">
				<option value="Band"> <?php echo $this->lang->line('gen_hamradio_band'); ?></option>
				<option value="Mode"> <?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
				<option value="RSTS"><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
				<option value="RSTR" selected ='selected'><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
				<option value="Country"><?php echo $this->lang->line('general_word_country'); ?></option>
				<option value="IOTA"><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
				<option value="State"><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
				<option value="Grid"><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
			</select>
			</div>

			<div class="form-group col-md-3">
			<label for="column4"><?php echo $this->lang->line('account_column4_text'); ?></label>
			<select class="custom-select" id="column4" name="user_column4">
				<option value="Band" selected ='selected'> <?php echo $this->lang->line('gen_hamradio_band'); ?></option>
				<option value="Mode"> <?php echo $this->lang->line('gen_hamradio_mode'); ?></option>
				<option value="RSTS"><?php echo $this->lang->line('gen_hamradio_rsts'); ?></option>
				<option value="RSTR"><?php echo $this->lang->line('gen_hamradio_rstr'); ?></option>
				<option value="Country"><?php echo $this->lang->line('general_word_country'); ?></option>
				<option value="IOTA"><?php echo $this->lang->line('gen_hamradio_iota'); ?></option>
				<option value="State"><?php echo $this->lang->line('gen_hamradio_state'); ?></option>
				<option value="Grid"><?php echo $this->lang->line('gen_hamradio_gridsquare'); ?></option>
			</select>
		</div>

		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
		<button type="submit" class="btn btn-primary">Create Account</button>
    </form>
  </div>
</div>


</div>
