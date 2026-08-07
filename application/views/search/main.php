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
		  <?php
		  $prefillCallsign = '';
		  $prefillExact = false;
		  if (isset($initial_search) && $initial_search !== '') {
			  $prefillCallsign = $initial_search;
		  } elseif ($this->input->post('callsign') !== null) {
			  $prefillCallsign = $this->input->post('callsign');
		  }
		  if (isset($initial_exact) && $initial_exact) {
			  $prefillExact = true;
		  }
		  ?>
		  <div class="mb-3 row">
		    <label for="callsign" class="col-sm-2 col-form-label">Callsign / Gridsquare</label>
		    <div class="col-sm-8">
		      <input type="text" class="form-control" id="callsign" value="<?php echo htmlspecialchars($prefillCallsign); ?>">
			  <div class="form-check mt-2 text-start">
				  <input class="form-check-input" type="checkbox" id="exact_match" <?php echo $prefillExact ? 'checked' : ''; ?>>
				  <label class="form-check-label" for="exact_match">Exact callsign match only</label>
			  </div>
			  <div class="mt-2 text-start" id="recent_searches_wrapper" style="display:none;">
				  <small class="text-muted d-block mb-1">Recent searches</small>
				  <div id="recent_searches"></div>
			  </div>
		    </div>
		    <div class="col-sm-2">
		    	<button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i> Search</button>
		    </div>
		  </div>
		</form>
	  </div>
	</div>

	<div class="card mt-3" id="results_card" style="display:none;">
	  <div class="card-header d-flex justify-content-between align-items-center">
		  <span>Results</span>
		  <div>
			  <span class="badge text-bg-light me-2" id="results_count">0 QSOs</span>
			  <span class="badge text-bg-secondary" id="results_mode">Partial match</span>
		  </div>
	  </div>
	  <div class="card-body p-2" id="results_body">
		  <div id="partial_view"></div>
	  </div>
	</div>

</div>
