<?php


$db_config_path = '../application/config/';

$db_file_path = $db_config_path."database.php";

function delDir($dir) {
	$files = glob( $dir . '*', GLOB_MARK );
    foreach ( $files as $file ) {
        if ( substr( $file, -1 ) == '/' ) {
			if (file_exists($file)) {
            	delDir( $file );
			}
		} else {
			if (file_exists($file)) {
				unlink( $file );
			}
		}
    }
	// This step may be not needed
    // if (file_exists($dir)) {
	// 	rmdir( $dir );
	// }
}

if (file_exists($db_file_path)) {
	delDir(getcwd());
	header("../");
	exit;
}

// Only load the classes in case the user submitted the form
if($_POST) {

	// Load the classes and create the new objects
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();


	// Validate the post data
	if($core->validate_post($_POST) == true)
	{

		// First create the database, then create tables, then write config file
		if($database->create_database($_POST) == false) {
			$message = $core->show_message('error',"The database could not be created, please verify your settings.");
		} else if ($database->create_tables($_POST) == false) {
			$message = $core->show_message('error',"The database tables could not be created, please verify your settings.");
		} else if ($core->write_config($_POST) == false) {
			$message = $core->show_message('error',"The database configuration file could not be written, please chmod /application/config/database.php file to 777");
		}

		if ($core->write_configfile($_POST) == false) {
			$message = $core->show_message('error',"The config configuration file could not be written, please chmod /application/config/config.php file to 777");
		}

		// If no errors, redirect to registration page
		if(!isset($message)) {
			sleep(1);
			$ch = curl_init();
			$protocol=((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
			list($realHost,)=explode(':',$_SERVER['HTTP_HOST']);
			$cloudlog_url=$protocol."://".$realHost.":".$_SERVER['SERVER_PORT'];
			curl_setopt($ch, CURLOPT_URL,$cloudlog_url);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_setopt($ch, CURLOPT_URL,$cloudlog_url."/index.php/update/dxcc");
			$result = curl_exec($ch);
			delDir(getcwd());
			header('Location: '.$protocol."://".$_SERVER['HTTP_HOST'].$_POST['directory']);
			echo "<h1>Install successful</h1>";
			echo "<p>Please delete the install folder";
			exit;
		}
	}
	else {
		$message = $core->show_message('error','Not all fields have been filled in correctly. The host, username, password, and database name are required.');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<title>Install | Cloudlog</title>

		<style type="text/css">
		  body {
		    font-size: 75%;
		    font-family: Helvetica,Arial,sans-serif;
		    width: 300px;
		    margin: 0 auto;
		  }
		  input, label {
		    display: block;
		    font-size: 18px;
		    margin: 0;
		    padding: 0;
		  }
		  label {
		    margin-top: 20px;
		  }
		  input.input_text {
		    width: 270px;
		  }
		  input#submit {
		    margin: 25px auto 0;
		    font-size: 25px;
		  }
		  fieldset {
		    padding: 15px;
		  }
		  legend {
		    font-size: 18px;
		    font-weight: bold;
		  }
		  .error {
		    background: #ffd1d1;
		    border: 1px solid #ff5858;
        padding: 4px;
		  }
		</style>
	</head>
	<body>

    <h1>Install Cloudlog</h1>
   <?php if(is_writable($db_config_path)):?>

		  <?php if(isset($message)) {echo '<p class="error">' . $message . '</p>';}?>

		  <form id="install_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		  <fieldset>
		  	<legend>Configuration Settings</legend>
		  	<label for="directory">Directory</label><input type="text" id="directory" value="<?php echo str_replace("index.php", "", str_replace("/install/", "", $_SERVER['REQUEST_URI'])); ?>" class="input_text" name="directory" />
		  	<label for="websiteurl">Website URL</label><input type="text" id="websiteurl" value="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://<?php echo str_replace("index.php", "", $_SERVER['HTTP_HOST'].str_replace("/install/", "", $_SERVER['REQUEST_URI'])); ?>" class="input_text" name="websiteurl" />
		  	<label for="locator">Default Gridsquare</label><input type="text" id="locator" value="IO91JS" class="input_text" name="locator" />
		  </fieldset>

		  	<br>

        <fieldset>
          <legend>Database settings</legend>
          <label for="hostname">Hostname</label><input type="text" id="hostname" value="localhost" class="input_text" name="hostname" />
          <label for="username">Username</label><input type="text" id="username" class="input_text" name="username" />
          <label for="password">Password</label><input type="password" id="password" class="input_text" name="password" />
          <label for="database">Database Name</label><input type="text" id="database" class="input_text" name="database" />
          <input type="submit" value="Install" id="submit" />
        </fieldset>
		  </form>

		 <h2>Demo User Account</h2>

		  	<ul>
		  		<li>Username: m0abc</li>
		  		<li>Password: demo</li>
		  	</ul>

		  	<p>When you login create a new admin account and delete the m0abc user account.</p>

	 <?php else: ?>
     <p class="error">Please make the /application/config/ folder writable. <strong>Example</strong>:<br /><br /><code>chmod -R 777 /application/config/</code><br /><br /><i>Don't forget to restore the permissions afterwards.</i></p>
	 <?php endif; ?>

	</body>
</html>
