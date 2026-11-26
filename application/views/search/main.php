<div class="container search awards-page">

	<?php $this->load->view('awards/components/topbar', array(
		'title' => 'Search',
		'subtitle' => 'Find QSOs by callsign or gridsquare, run advanced filters, or clean up duplicates',
		'icon' => 'fas fa-search',
	)); ?>

	<div class="card">
	  <div class="card-header pb-0">
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
	  <div class="card-body p-3">
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
		  <div class="row g-2 align-items-end">
		    <div class="col-12 col-md-9 col-lg-10">
		      <label for="callsign" class="form-label small fw-semibold text-uppercase text-muted mb-1">Callsign / Gridsquare</label>
		      <div class="input-group">
		        <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
		        <input type="text" class="form-control" id="callsign" placeholder="Enter callsign or Maidenhead gridsquare (e.g. 2M0SQL or IO85)" value="<?php echo htmlspecialchars($prefillCallsign); ?>">
		      </div>
		    </div>
		    <div class="col-12 col-md-3 col-lg-2 d-grid">
		    	<button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i>Search</button>
		    </div>
		  </div>
		  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2">
			  <div class="form-check mb-0">
				  <input class="form-check-input" type="checkbox" id="exact_match" <?php echo $prefillExact ? 'checked' : ''; ?>>
				  <label class="form-check-label small" for="exact_match">Exact callsign match only</label>
			  </div>
		  </div>
		  <div class="mt-2" id="recent_searches_wrapper" style="display:none;">
			  <small class="text-muted d-block mb-1">Recent searches</small>
			  <div id="recent_searches"></div>
		  </div>
		</form>
	  </div>
	</div>

	<div class="card mt-3" id="results_card" style="display:none;">
	  <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 py-2 px-3">
		  <span class="fw-semibold small text-uppercase text-muted"><i class="fas fa-list me-1 text-primary"></i>Results</span>
		  <div class="d-flex align-items-center gap-2">
			  <span class="badge rounded-pill text-bg-primary" id="results_count">0 QSOs</span>
			  <span class="badge rounded-pill text-bg-secondary" id="results_mode">Partial match</span>
		  </div>
	  </div>
	  <div class="card-body p-2" id="results_body">
		  <div id="partial_view"></div>
	  </div>
	</div>

</div>
