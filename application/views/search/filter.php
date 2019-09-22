<div class="container search">

	<h1>
		Advanced Search
		<small class="text-muted"></small>
	</h1>

	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<!-- Filter options here -->
	<div class="card">
	  <div class="card-header">
	    <ul class="nav nav-tabs card-header-tabs">
	      <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('search'); ?>">Search</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link active" href="<?php echo site_url('search/filter'); ?>">Advanced Search</a>
	      </li>
	    </ul>
	  </div>
	  <div class="card-body">

		<div class="card-text" id="builder"></div>
		
		<p class="card-text">
		<button class="btn btn-primary" id="btn-get">Search</button>

		<button class="btn btn-warning" id="btn-reset">Reset</button>
		</p>

	    	<span class="badge badge-info">Info</span> You can find out how to use the <a href="https://github.com/magicbug/Cloudlog/wiki/Search----Filter" target="_blank">search filter functions</a> in the wiki.</a>
	    </p>

	  </div>
	</div>

	<br>

	<!-- Search Results here -->
	<div class="card search-results-box">
	  <div class="card-header">
	    Search Results
	  </div>
	  <div class="card-body">

		<div class="table-responsive">
			<table id="results" class="table table-striped table-hover">
				<tr class="titles">
					<td>Date/Time</td>
					<td>Call</td>
					<td>Mode</td>
					<td>Sent</td>
					<td>Recv</td>
					<td>Band</td>
					<td>Country</td>
					<td></td>
				</tr>
	    	</table>
	    </div>

	  </div>
	</div>

</div>
