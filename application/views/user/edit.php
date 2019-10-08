<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    Edit User
  </div>
  <div class="card-body">
    <h5 class="card-title"></h5>
    <p class="card-text"></p>
 
		<?php echo validation_errors(); ?>
		<?php $this->load->helper('form'); ?>
		
		<form method="post" action="<?php echo site_url('user/edit')."/".$this->uri->segment(3); ?>" name="users" autocomplete="off">
			<div class="form-group">
				<label>Username</label>
				<input class="form-control" type="text" name="user_name" value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
				<?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
			</div>

			<div class="form-group">
				<label>Level</label>
					<?php if($this->session->userdata('user_type') == 99) { ?>
					<select class="form-control" name="user_type">
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

			<div class="form-group">
				<label>Email</label>
				<input class="form-control" type="text" name="user_email" value="<?php if(isset($user_email)) { echo $user_email; } ?>" />
				<?php if(isset($email_error)) { echo "<div class=\"small error\">".$email_error."</div>"; } ?>
			</div>

			<div class="form-group">
				<label>Password</label>
				<input class="form-control" type="password" name="user_password" />
				<?php if(isset($password_error)) { echo "<div class=\"small error\">".$password_error."</div>"; } else { ?>
				<div class="small">Leave blank to keep existing password</div></td>
				<?php } ?>
			</div>

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

			<div class="form-group">
				<label>Timezone</label>
				<?php echo form_dropdown('user_timezone', $timezones, $user_timezone); ?>
			</div>

			<div class="form-group">
				<label>Logbook of The World (LoTW) Username</label>
				<input class="form-control" type="text" name="user_lotw_name" value="<?php if(isset($user_lotw_name)) { echo $user_lotw_name; } ?>" />
					<?php if(isset($userlotwname_error)) { echo "<div class=\"small error\">".$userlotwname_error."</div>"; } ?>
			</div>

			<div class="form-group">
				<label>Logbook of The World (LoTW) Password</label>
				<input class="form-control" type="password" name="user_lotw_password" />
					<?php if(isset($lotwpassword_error)) { echo "<div class=\"small error\">".$lotwpassword_error."</div>"; } else { ?>
					<div class="small">Leave blank to keep existing password</div></td>
					<?php } ?>
			</div>

			<div class="form-group">
				<label>eQSL.cc Username</label>
				<input class="form-control" type="text" name="user_eqsl_name" value="<?php if(isset($user_eqsl_name)) { echo $user_eqsl_name; } ?>" />
					<?php if(isset($eqslusername_error)) { echo "<div class=\"small error\">".$eqslusername_error."</div>"; } else { ?>
					<div class="small">Leave blank to keep existing password</div></td>
					<?php } ?>
			</div>

			<div class="form-group">
				<label>eQSL.cc Password</label>
				<input class="form-control" type="password" name="user_eqsl_password" />
					<?php if(isset($eqslpassword_error)) { echo "<div class=\"small error\">".$eqslpassword_error."</div>"; } else { ?>
					<div class="small">Leave blank to keep existing password</div></td>
					<?php } ?>
			</div>

			<div class="form-group">
				<label>Club Log Email/Callsign</label>
				<input class="form-control" type="text" name="user_clublog_name" value="<?php if(isset($user_clublog_name)) { echo $user_clublog_name; } ?>" />
					<div class="small">This is the Email or Callsign you use to login to Club Log</div></td>
					<?php if(isset($userclublogname_error)) { echo "<div class=\"small error\">".$userclublogname_error."</div>"; } ?>

			</div>

			<div class="form-group">
				<label>Club Log Password</label>
				<input class="form-control" type="password" name="user_clublog_password" />
					<?php if(isset($clublogpassword_error)) { echo "<div class=\"small error\">".$clublogpassword_error."</div>"; } else { ?>
					<div class="small">Leave blank to keep existing password</div></td>
					<?php } ?>
			</div>

			<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
			<button type="submit" class="btn btn-primary">Update Account</button>
		</form>
  </div>
</div>


</div>

