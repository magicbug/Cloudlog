<div class="container">

	<br>

	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<!-- Filter options here -->
	<div class="card">
	  <div class="card-header">
	    <?php echo $page_title; ?>
	  </div>
	  <div class="card-body">
	    <h5 class="card-title">Explore & Poke the Logbook</h5>
	    <p class="card-text">
	    	Select functions go here
	    </p>

		<p class="card-text">
	    	<span class="badge badge-info">Info</span> You can find out how to use the <a href="https://github.com/magicbug/Cloudlog/wiki/Search-Filter" target="_blank">search filter functons</a> in the wiki.</a>
	    </p>

	  </div>
	</div>

	<br>

	<!-- Search Results here -->
	<div class="card">
	  <div class="card-header">
	    Search Results
	  </div>
	  <div class="card-body">
	    <h5 class="card-title">This is what we found</h5>
	    <p class="card-text">
	    	Table here
	    </p>
	  </div>
	</div>

</div>