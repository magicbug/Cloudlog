<div class="container">

	<br>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>

	<div class="card">
	  <div class="card-header">
	    <?php echo $page_title; ?>
	  </div>
	  <div class="card-body">
	    <h5 class="card-title"></h5>
	    <p class="card-text">
	    	These are active radios that are connected to Cloudlog via the API.
	    </p>

	    <div class="table-responsive">
		    <!-- Display Radio Statuses -->	  
			<table class="table table-striped status"></table>
		</div>

		<p class="card-text">
	    	<span class="badge badge-info">Info</span> You can find out how to use the <a href="https://github.com/magicbug/Cloudlog/wiki/Radio-Interface" target="_blank">radio functions</a> in the wiki.</a>
	    </p>

	  </div>
	</div>

</div>
