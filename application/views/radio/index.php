<div class="container">

	<br>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>

	<h2><?php echo $page_title; ?></h2>

	<div class="card">
	  <div class="card-header">
	    Active Radios
	  </div>
	  <div class="card-body">
	    <p class="card-text">Below is a list of active radios that are connected to Cloudlog.</p>
	    <p class="card-text">If you haven't connected any radios yet, see the API page to generate API keys.</p>
	    <div class="table-responsive">
		    <!-- Display Radio Statuses -->	  
			<table class="table table-striped status"></table>
		</div>

		<p class="card-text">
	    	<span class="badge text-bg-info">Info</span> You can find out how to use the <a href="https://github.com/magicbug/Cloudlog/wiki/Radio-Interface" target="_blank">radio functions</a> in the wiki.</a>
	    </p>

	  </div>
	</div>

</div>
