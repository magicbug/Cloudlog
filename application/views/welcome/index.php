<div class="container">

    <br>

    <?php if($this->session->flashdata('message')) { ?>
        <!-- Display Message -->
        <div class="alert-message error">
          <p><?php echo $this->session->flashdata('message'); ?></p>
        </div>
    <?php } ?>

    <h1 class="display-3"><?php echo $page_title; ?></h1>

    <p class="lead">After many years and hard work Cloudlog version 2.0 has finally arrived, this brings multi-user support, logbooks to group station locations, improved code with lots of speed increases sprinkled around.</p>

    <p class="lead">This guide is to help you get your installation configured to work with all the new features please follow it!</p>

    <?php if(ENVIRONMENT != "production") { ?>
    <div class="card">
	  <div class="card-header">
      <span class="badge badge-danger">File Change</span> /index.php - Turn off debugging messages
	  </div>
	  <div class="card-body">
        <p class="card-text">While some users love seeing errors even the development messages most don't so we recommend turning it off..</p>
        
        <p class="card-text">Edit <span class="badge badge-dark">/index.php</span> and find <code>define('ENVIRONMENT', 'development');</code> and replace with</p>
        
        <code>
            define('ENVIRONMENT', 'production');
        </code>
	  </div>
    </div>

    <br>
    <?php } ?>

    <?php if($this->config->item('auth_level')[3] != "Operator") { ?>
	<div class="card">
	  <div class="card-header">
      <span class="badge badge-danger">File Change</span> /application/config/config.php - Changes
	  </div>
	  <div class="card-body">
        <p class="card-text">As part of fully supporting multi-user, we recommend making some changes to the role names as shown below, "Operators" do not have the admin features.</p>
        
        <p class="card-text">Edit <span class="badge badge-dark">/application/config/config.php</span> and find $config['auth_level'] and replace the options with only the ones below.</p>
        
        <code>
            $config['auth_level'][3] = "Operator";<br>
            $config['auth_level'][99] = "Administrator";
        </code>
	  </div>
    </div>

    <br>
    <?php } ?>

    <div class="card">
        <div class="card-header">Cronjob Refresher</div>
        <div class="card-body">
            <p class="card-text">Theres some new cronjobs to add with version 2.0.</p>

            <code>
            # Update the Cloudlog installation every day at midnight <br>
            0 0 * * * /bin/bash -c "Full-Path-To-Bash-Script/cloudlog.sh" <br>
            <br>
            # Upload QSOs to Club Log (ignore cron job if this integration is not required) <br>
            0 */6 * * * curl --silent <?php echo site_url();?>/clublog/upload/username-with-clublog-login &>/dev/null <br>
            <br>
            # Upload QSOs to LoTW if certs have been provided every hour. <br>
            0 */1 * * * curl --silent <?php echo site_url();?>/lotw/lotw_upload &>/dev/null <br>
            <br>
            # Upload QSOs to HRDLog.net Logbook (ignore cron job if this integration is not required) <br>
            0 */6 * * * curl --silent <?php echo site_url();?>/hrdlog/upload &>/dev/null <br>
             <br>
            # Upload QSOs to QRZ Logbook (ignore cron job if this integration is not required) <br>
            0 */6 * * * curl --silent <?php echo site_url();?>/qrz/upload &>/dev/null <br>
            <br>
            # Update LoTW Users Database <br>
            @weekly curl --silent <?php echo site_url();?>/lotw/load_users &>/dev/null <br>
            <br>
            # Update Clublog SCP Database File <br>
            @weekly curl --silent <?php echo site_url();?>/update/update_clublog_scp &>/dev/null <br>
            <br>
            # Update DOK File for autocomplete <br>
            @monthly curl --silent <?php echo site_url();?>/update/update_dok &>/dev/null <br>
            <br>
            # Update SOTA File for autocomplete <br>
            @monthly curl --silent <?php echo site_url();?>/update/update_sota &>/dev/null <br>
            <br>
            # Update WWFF File for autocomplete <br>
            @monthly curl --silent <?php echo site_url();?>/update/update_wwff &>/dev/null <br>
            </code>
        </div>
    </div>

    <br>

    <?php if($CountAllStationLocations > 0) { ?>
    <div class="card">
        <div class="card-header">Assign ALL Station Locations to this username</div>
        <div class="card-body">
            <p class="card-text">With Cloudlog Version 2.0, Station Locations must be associated with a user pressing the button below will assign all Station Locations to the first user in the database</p>
            <button type="button" class="btn btn-primary" hx-post="<?php echo site_url('welcome/locationsclaim'); ?>">Associate Station Locations with the Administrator account.</button>
        </div>
    </div>

    <br>
    <?php } ?>

    <?php if($NumberOfStationLogbooks > 0) { ?>
        <div class="card">
            <div class="card-header">Create Station Logbooks</div>
            <div class="card-body">
                <p class="card-text">All the views now in Cloudlog are based around Station Logbooks, you can create as many as you want and group Station Locations it makes tracking awards a lot easier.</p>
                <p class="card-text">You don't have one at the moment and it can cause issues so press the button below and create a default logbook, you can change this later!</p>
                <button type="button" class="btn btn-primary" hx-post="<?php echo site_url('welcome/defaultlogbook'); ?>">Create a default logbook.</button>
            </div>
        </div>

        <br>
    <?php } ?>
    <?php if($NumberOfNotes > 0) { ?>
        <div class="card">
            <div class="card-header">Claim Notes</div>
            <div class="card-body">
                <p class="card-text">Looks like you have some notes saved, we need to assign them to your username.</p>
                <button type="button" class="btn btn-primary" hx-post="<?php echo site_url('welcome/claimnotes'); ?>">Claim Notes</button>
            </div>
        </div>

        <br>
    <?php } ?>

    <?php if($NumberOfAPIKeys > 0) { ?>
        <div class="card">
            <div class="card-header">Claim API Keys</div>
            <div class="card-body">
                <p class="card-text">Looks like you have some API Keys, we need to assign them to your username else they will stop working.</p>
                <button type="button" class="btn btn-primary" hx-post="<?php echo site_url('welcome/claimapikeys'); ?>">Claim API Keys</button>
            </div>
        </div>

        <br>
    <?php } ?>

    <div class="card">
        <div class="card-header">Update Country Files</div>
        <div class="card-body">
            <p class="card-text">Just a friendly reminder to update country files within Cloudlog so they are nice and up to date :)</p>
        </div>
    </div>

    <br>

    <button  class="btn btn-primary" onClick="window.location.reload();">Check if Migration Complete</button>

    <br><br>

</div>
