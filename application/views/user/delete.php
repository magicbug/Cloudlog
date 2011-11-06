<div id="container">

<h2>Edit user</h2>
<div class="wrap_content user">
<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('user/delete')."/".$this->uri->segment(3); ?>" name="users">
	<table>
		<tr>
			<td style="padding: 10px;">Are you sure you want to delete <?php echo $user_name; ?>?</td>
		</tr>
	</table>
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div class="actions"><input class="btn primary" type="submit" value="Yes, delete this user" /> <a href="<?php echo site_url('user'); ?>">No, do not delete this user</a></div>

</form>

</div>
