<div class="container search awards-page">

	<?php $this->load->view('awards/components/topbar', array(
		'title' => 'QSOs Unconfirmed on LoTW',
		'subtitle' => 'Find unconfirmed QSOs where the worked callsign has uploaded to LoTW after your QSO date',
		'icon' => 'fas fa-cloud-upload-alt',
	)); ?>

	<div class="card">
	  <div class="card-header pb-0">
	    <ul class="nav nav-tabs card-header-tabs">
	      <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('search'); ?>">Search</a>
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
	        <a class="nav-link active" href="<?php echo site_url('search/lotw_unconfirmed'); ?>">QSOs unconfirmed on LoTW</a>
	      </li>
	    </ul>
	  </div>
	  <div class="card-body p-3">
        <p class="small text-muted mb-3">Displays QSOs which are unconfirmed on LoTW, where the callsign worked has uploaded to LoTW after your QSO date.</p>
	  	<form method="post" action="" id="search_box" name="test" class="mb-3">
		  <div class="row g-2 align-items-end">
		    <div class="col-12 col-md-6 col-lg-5">
		      <label for="station_id" class="form-label small fw-semibold text-uppercase text-muted mb-1">Station location</label>
		      <select id="station_id" name="station_profile" class="form-select form-select-sm">
					<option value="All">All</option>
                    <?php foreach ($station_profile->result() as $station) { ?>
                    <option value="<?php echo $station->station_id; ?>">Callsign: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
                    <?php } ?>
              </select>
		    </div>
		    <div class="col-12 col-md-auto">
		    	<button onclick="findlotwunconfirmed();" class="btn btn-sm btn-primary" type="submit"><i class="fas fa-search me-1"></i>Search</button>
		    </div>
		  </div>
		</form>

		<div id="partial_view"></div>

	  </div>
	</div>

</div>
