		<form class="form-signin" method="post" action="<?php echo site_url('login'); ?>">
			
			<h1 class="display-3">Cloudlog</h1>

			<?php if($this->session->flashdata('notice')) { ?>
				<div class="alert alert-info" role="alert">
					<?php echo $this->session->flashdata('notice'); ?>
				</div>
			<?php } ?>

			<?php if($this->session->flashdata('error')) { ?>
				<div class="alert alert-danger" role="alert">
					<?php echo $this->session->flashdata('error'); ?>
				</div>
			<?php } ?>

		 	<h2 class="h3 mb-3 font-weight-normal">Please sign in</h2>

			<label for="inputEmail" class="sr-only">Username</label>
		 
		 	<input type="text" name="user_name" id="inputUsername" class="form-control" placeholder="Username" required autofocus>

			<label for="inputPassword" class="sr-only">Password</label>

			<input type="password" name="user_password" id="inputPassword" class="form-control" placeholder="Password" required>

			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

			<p>Need an account? <a href="<?php echo site_url('register'); ?>">Register</a></p>

			<p class="mt-5 mb-3 text-muted"><p class="mb-1">&copy; <?php echo date("Y");?> Cloudlog</p></p>
		</form>
	</body>
</html>