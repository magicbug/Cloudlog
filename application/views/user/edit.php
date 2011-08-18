<h2>Edit user</h2>
<div class="wrap_content user">
<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('user/edit')."/".$this->uri->segment(3); ?>" name="users">
<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="user_name" value="<?php echo $user_name; ?>" /></td>
	</tr>
	
	<tr>
		<td>E-mail</td>
		<td><input type="text" name="user_email" value="<?php echo $user_email; ?>" /></td>
	</tr>
	
	<tr>
		<td>Password</td>
		<td><input type="text" name="user_password" /><br><div class="small">Leave blank to keep existing password</div><div class="small">For testing: <i><?php echo $user_password; ?></i></td>
	</tr>
	
	<tr>
		<td>Type</td>
		<td><select name="user_type">
				<option value="99" <?php if($user_type == "99") { echo "selected=\"selected\""; } ?>>Owner</option>
				<option value="3" <?php if($user_type == "3") { echo "selected=\"selected\""; } ?>>Editor</option>
				<option value="2" <?php if($user_type == "2") { echo "selected=\"selected\""; } ?>>API User</option>
				<option value="1" <?php if($user_type == "1") { echo "selected=\"selected\""; } ?>>Viewer</option>
			</select>
		</td>
	</tr>
</table>	
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div><input type="submit" value="Submit" /></div>

</form>

</div>
