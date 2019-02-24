<div class="container">
<br>
	<div class="card">
	  <div class="card-header">
	    Delete User Account <?php echo $user_name; ?>
	  </div>
	  <div class="card-body">
	    <h5 class="card-title"></h5>

	    <p class="card-text">Are you sure you want to delete the user account <b><?php echo $user_name; ?></b>?</p>

	    <form method="post" action="<?php echo site_url('user/delete')."/".$this->uri->segment(3); ?>" name="users">
	    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
			<input class="btn btn-danger" type="submit" value="Yes, delete this user" /> <a href="<?php echo site_url('user'); ?>" class="btn btn-success">No, do not delete this user</a>
			</form>
	  </div>
	</div>
</div>
