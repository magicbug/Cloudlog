Hello <?php echo $user_firstname . ", " . $user_callsign ?>


An admin initiated a password reset for your Cloudlog account.

Your username is:    <?php echo $user_name; ?>


Click here to reset your password: <?php echo site_url('user/reset_password/').$reset_code; ?>


If you didn't request any password reset, just ignore this email and talk to an admin of your Cloudlog instance.

Regards,
Cloudlog
