
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
		    <select class="form-control" name="user_type">
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
            <label for="user_measurement_base">Measurement preference</label>
            <select class="custom-select" id="user_measurement_base" name="user_measurement_base" required>
                <option value='K' selected='selected'>Kilometers</option>
                <option value='M'>Miles</option>
                <option value='N'>Nautical miles</option>
            </select>
            <small id="user_measurement_base_Help" class="form-text text-muted">Choose which unit distances will be shown in.</small>
        </div>

		  <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
			<button type="submit" class="btn btn-primary">Create Account</button>
    </form>
  </div>
</div>


</div>