<div class="container">
<br>
	<div class="card">
	  <div class="card-header">
	    <?php echo lang('account_delete_user_account'); ?> <?php echo $user_name; ?>
	  </div>
	  <div class="card-body">
	    <h5 class="card-title"></h5>

	    <p class="card-text"><?php echo lang('account_are_you_sure_you_want_to_delete_the_user_account'); ?> <b><?php echo $user_name; ?></b>?</p>

	    <form method="post" action="<?php echo site_url('user/delete')."/".$this->uri->segment(3); ?>" name="users">
	    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
			<input class="btn btn-danger" type="submit" value="<?php echo lang('account_yes_delete_this_user'); ?>" /> <a href="<?php echo site_url('user'); ?>" class="btn btn-success"><?php echo lang('account_no_do_not_delete_this_user'); ?></a>
			</form>
	  </div>
	</div>
</div>
