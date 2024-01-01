<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <p>Hello <?php echo $user_first_name; ?>,</p>
    
    <p>An admin initiated a password reset for your Cloudlog account.</p>
    
    <p>Click <a href="<?php echo site_url('user/reset_password/').$reset_code; ?>">here</a> to reset your password.</p>

    <p>Or copy this link into a browser:
    <?php echo site_url('user/reset_password/').$reset_code; ?></p>
    
    <p>If you didn't request any password reset, just ignore this email and talk to an admin of your Cloudlog instance.</p>
    
    <p>Regards,<br>
    Cloudlog</p>
</body>
</html>
