<h2>Add user</h2>
<div class="wrap_content user">
<?php if($this->session->flashdata('notice')) { ?>
    <div id="message" >
        <?php echo $this->session->flashdata('notice'); ?>
    </div>
<?php } ?>

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
		<td>Type</td>
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
</table>	
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div><input type="submit" value="Submit" /></div>

</form>

</div>
