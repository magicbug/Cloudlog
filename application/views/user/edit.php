<h2>Edit user</h2>
<div class="wrap_content user">
<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('user/edit')."/".$this->uri->segment(3); ?>" name="users">
<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="user_name" value="<?php if(isset($user_name)) { echo $user_name; } ?>" />
		<?php if(isset($username_error)) { echo "<div class=\"small error\">".$username_error."</div>"; } ?>
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
		<td>Type</td>
		<td><select name="user_type">
				<?php
				
				$levels = $this->config->item('auth_level');
				while (list($key, $val) = each($levels)) {
				?>
				<option value="<?php echo $key; ?>" <?php if($user_type == $key) { echo "selected=\"selected\""; } ?>><?php echo $val; ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
</table>	
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div><input type="submit" value="Submit" /></div>

</form>

</div>
