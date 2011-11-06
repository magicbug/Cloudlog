<div id="container">
<h2>Log in</h2>

<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('user/login'); ?>" name="users">
<table>
	<tr>
		<td>Username</td>
		<td><input type="text" name="user_name" value="<?php echo $this->input->post('user_name'); ?>"></td>
	</tr>
	
	<tr>
		<td>Password</td>
		<td><input type="password" name="user_password" /></td>
	</tr>
	
</table>	
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div><input type="submit" value="Log in" /></div>

</form>

</div>
