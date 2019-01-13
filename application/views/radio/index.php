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
	    <p class="card-text"></p>

	    <div class="table-responsive">
		    <!-- Display Radio Statuses -->	  
				<table class="table table-striped"></table>
			</div>
	  </div>
	</div>

</div>
