<div id="container" class="container mx-auto pt-5" style="max-width:400px">

<h2>Log in</h2>
<?php $this->load->view('layout/messages'); ?>

<form method="post" action="<?php echo site_url('user/login'); ?>" name="users">
	<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
	<div class="form-group">
		<label for="user_name">Username</label>
		<input id="user_name" type="text" name="user_name" class="form-control" value="<?php echo $this->input->post('user_name'); ?>">
	</div>
	<div class="form-group">
		<label for="password">Password</label>
		<input id="password" type="password" name="user_password" class="form-control">
	</div>
	<div class="form-group">
		<input class="btn-info p-2 col" type="submit" value="Log in" />
	</div
</form>

</div>
