
<div id="container">

<h2>Add user</h2>

<?php if($this->session->flashdata('notice')) { ?>
    <div id="message" >
        <?php echo $this->session->flashdata('notice'); ?>
    </div>
<?php } ?>
<?php

$this->load->helper('form');

?>
<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('user/add'); ?>" name="users">
<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="user_name" value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
		<?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>Level</td>
		<td><select name="user_type">
				<?php
				
				$levels = $this->config->item('auth_level');
				while (list($key, $val) = each($levels)) {
				?>
				<option value="<?php echo $key; ?>" <?php if(isset($user_type)) { if($user_type == $key) { echo "selected=\"selected\""; } } ?>><?php echo $val; ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>

	<tr>
		<td>E-mail</td>
		<td><input type="text" name="user_email" value="<?php if(isset($user_email)) { echo $user_email; } ?>" />
		<?php if(isset($email_error)) { echo "<div class=\"small error\">".$email_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>Password</td>
		<td><input type="password" name="user_password" value="<?php if(isset($user_password)) { echo $user_password; } ?>" />
		<?php if(isset($password_error)) { echo "<div class=\"small error\">".$password_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>First name</td>
		<td><input type="text" name="user_firstname" value="<?php if(isset($user_firstname)) { echo $user_firstname; } ?>" />
		<?php if(isset($firstname_error)) { echo "<div class=\"small error\">".$firstname_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>Last name</td>
		<td><input type="text" name="user_lastname" value="<?php if(isset($user_lastname)) { echo $user_lastname; } ?>" />
		<?php if(isset($lastname_error)) { echo "<div class=\"small error\">".$lastname_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>Callsign</td>
		<td><input type="text" name="user_callsign" value="<?php if(isset($user_callsign)) { echo $user_callsign; } ?>" />
		<?php if(isset($callsign_error)) { echo "<div class=\"small error\">".$callsign_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>Locator</td>
		<td><input type="text" name="user_locator" value="<?php if(isset($user_locator)) { echo $user_locator; } ?>" />
		<?php if(isset($locator_error)) { echo "<div class=\"small error\">".$locator_error."</div>"; } ?>
		</td>
	</tr>

	<tr>
		<td>Timezone</td>
		<td><?php echo form_dropdown('user_timezone', $timezones, 0); ?></td>
	</tr>
	
</table>	
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div class="actions"><input class="btn primary" type="submit" value="Submit" /></div>

</form>

</div>