<div id="container">

<h2>Edit user</h2>

<?php echo validation_errors(); ?>
<?php

$this->load->helper('form');

?>
<form method="post" action="<?php echo site_url('user/edit')."/".$this->uri->segment(3); ?>" name="users">
<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="user_name" value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
		<?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>Level</td>
		<td>
		<?php if($this->session->userdata('user_type') == 99) { ?>
		<select name="user_type">
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
		<td><input type="password" name="user_password" />
		<?php if(isset($password_error)) { echo "<div class=\"small error\">".$password_error."</div>"; } else { ?>
		<div class="small">Leave blank to keep existing password</div></td>
		<?php } ?>
	</tr>
	
	<tr>
		<td>First name</td>
		<td><input type="text" name="user_firstname" value="<?php if(isset($user_firstname)) { echo $user_firstname; } ?>" />
		<?php if(isset($firstname_error)) { echo "<div class=\"small error\">".$firstname_error."</div>"; } else { ?>
		<?php } ?>
	</tr>
	
	<tr>
		<td>Last name</td>
		<td><input type="text" name="user_lastname" value="<?php if(isset($user_lastname)) { echo $user_lastname; } ?>" />
		<?php if(isset($lastname_error)) { echo "<div class=\"small error\">".$lastname_error."</div>"; } else { ?>
		<?php } ?>
	</tr>
	
	<tr>
		<td>Callsign</td>
		<td><input type="text" name="user_callsign" value="<?php if(isset($user_callsign)) { echo $user_callsign; } ?>" />
		<?php if(isset($callsign_error)) { echo "<div class=\"small error\">".$callsign_error."</div>"; } else { ?>
		<?php } ?>
	</tr>
	
	<tr>
		<td>Locator</td>
		<td><input type="text" name="user_locator" value="<?php if(isset($user_locator)) { echo $user_locator; } ?>" />
		<?php if(isset($locator_error)) { echo "<div class=\"small error\">".$locator_error."</div>"; } else { ?>
		<?php } ?>
	</tr>

    <tr>
		<td>Timezone</td>
		<td><?php echo form_dropdown('user_timezone', $timezones, $user_timezone); ?></td>
	</tr>

	<tr>
		<td>Logbook of The World (LoTW) Username</td>
		<td><input type="text" name="user_lotw_name" value="<?php if(isset($user_lotw_name)) { echo $user_lotw_name; } ?>" />
		<?php if(isset($userlotwname_error)) { echo "<div class=\"small error\">".$userlotwname_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>Logbook of The World (LoTW) Password</td>
		<td><input type="password" name="user_lotw_password" />
		<?php if(isset($lotwpassword_error)) { echo "<div class=\"small error\">".$lotwpassword_error."</div>"; } else { ?>
		<div class="small">Leave blank to keep existing password</div></td>
		<?php } ?>
	</tr>
	
	<tr>
		<td>eQSL.cc Username</td>
		<td><input type="text" name="user_eqsl_name" value="<?php if(isset($user_eqsl_name)) { echo $user_eqsl_name; } ?>" />
		<?php if(isset($usereqslname_error)) { echo "<div class=\"small error\">".$usereqslname_error."</div>"; } ?>
		</td>
	</tr>
	
	<tr>
		<td>eQSL.cc Password</td>
		<td><input type="password" name="user_eqsl_password" />
		<?php if(isset($eqslpassword_error)) { echo "<div class=\"small error\">".$eqslpassword_error."</div>"; } else { ?>
		<div class="small">Leave blank to keep existing password</div></td>
		<?php } ?>
	</tr>
	
	<tr>
		<td>eQSL.cc QTH Nickname</td>
		<td><input type="text" name="user_eqsl_qth_nickname" value="<?php if(isset($user_eqsl_qth_nickname)) { echo $user_eqsl_qth_nickname; } ?>" />
		</td>
	</tr>
	
</table>	
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div class="actions"><input class="btn primary" type="submit" value="Update profile" /></div>

</form>

</div>
