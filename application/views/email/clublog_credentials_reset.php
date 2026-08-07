Hi <?php echo $user_firstname; ?> <?php echo $user_lastname; ?>,

Your Cloudlog Clublog login credentials were automatically cleared after Clublog returned an authorization failure (HTTP 403).

Callsign: <?php echo $user_callsign; ?>
Username: <?php echo $user_name; ?>

<?php if (!empty($reset_context)) { ?>
Details: <?php echo $reset_context; ?>

<?php } ?>To continue Clublog uploads, please log in and re-enter your Clublog email and password in your account settings.

Log in here: <?php echo $base_url; ?>

If you did not expect this, please verify your Clublog account credentials and API access.

Regards,

Cloudlog.
