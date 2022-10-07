<div class="container">

    <br>

    <?php if($this->session->flashdata('message')) { ?>
        <!-- Display Message -->
        <div class="alert-message error">
          <p><?php echo $this->session->flashdata('message'); ?></p>
        </div>
    <?php } ?>

    <h2><?php echo $page_title; ?></h2>

    <p class="lead">After many years and hard work Cloudlog version 2.0 has finally arrived, this brings multi-user support, logbooks to group station locations, improved code with lots of speed increases sprinkled around.</p>
    <p class="lead">I'd like to thank Andreas (LA8AJA) and Flo (DF2ET) for helping getting this over the finish line.</p>
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