<div class="container search">

	<h1>
		Search
		<small class="text-muted">Ready to find a QSO?</small>
	</h1>

	<div class="card text-center">
	  <div class="card-header">
	    <ul class="nav nav-tabs card-header-tabs">
	      <li class="nav-item">
	        <a class="nav-link active" href="<?php echo site_url('search'); ?>">Search</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('search/filter'); ?>">Advanced Search</a>
	      </li>
		  <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('search/duplicates'); ?>">Duplicate QSOs</a>
	      </li>
		  <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('search/incorrect_cq_zones'); ?>">Incorrect CQ Zones</a>
	      </li>
		  <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('search/lotw_unconfirmed'); ?>">QSOs unconfirmed on LoTW</a>
	      </li>
	    </ul>
	  </div>
	  <div class="card-body">
	  	<form method="post" action="" id="search_box" name="test">
		  <div class="mb-3 row">
		    <label for="callsign" class="col-sm-2 col-form-label">Callsign / Gridsquare</label>
		    <div class="col-sm-8">
		      <input type="text" class="form-control" id="callsign" value="<?php if ($this->input->post('callsign') !== null) { echo htmlspecialchars($this->input->post('callsign')); }; ?>">
		    </div>
		    <div class="col-sm-2">
		    	<button onclick="searchButtonPress()" class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i> Search</button>
		    </div>
		  </div>
		</form>

		<div id="partial_view"></div>

	  </div>
	</div>

</div>
