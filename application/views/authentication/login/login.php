<form class="form-signin" method="post" action="<?php echo site_url('user/login'); ?>">
  <h1 class="display-3">Cloudlog</h1>
  <h2 class="h3 mb-3 font-weight-normal">Please sign in</h2>
  <label for="inputEmail" class="sr-only">Username</label>
  <input type="text" name="user_name" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" name="user_password" id="inputPassword" class="form-control" placeholder="Password" required>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
  <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y'); ?> Cloudlog</p>
</form>
</body>
</html>