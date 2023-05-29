<div class="container search">

	<h1>
		Search
		<small class="text-muted">Ready to find a QSO?</small>
	</h1>

	<div class="card text-center">
	  <div class="card-header">
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
	        <a class="nav-link " href="<?php echo site_url('search/incorrect_cq_zones'); ?>">Incorrect CQ Zones</a>
	      </li>
		  <li class="nav-item">
	        <a class="nav-link active" href="<?php echo site_url('search/lotw_unconfirmed'); ?>">QSOs unconfirmed on LoTW</a>
	      </li>
	    </ul>
	  </div>
	  <div class="card-body">
        The search displays QSOs which are unconfirmed on LoTW, but the callsign worked has uploaded to LoTW after your QSO date.<br/><br />
	  	<form method="post" action="" id="search_box" name="test">
		  <div class="form-group row">
		    <label for="callsign" class="col-sm-2 col-form-label">Station location:</label>
		    <select id="station_id" name="station_profile" class="custom-select col-sm-3 mb-3 mr-sm-3">
					<option value="All">All</option>
                    <?php foreach ($station_profile->result() as $station) { ?>
                    <option value="<?php echo $station->station_id; ?>">Callsign: <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)</option>
                    <?php } ?>
                    </select>
		    <div class="col-sm-2">
		    	<button onclick="findlotwunconfirmed();" class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i> Search</button>
		    </div>
		  </div>
		</form>

		<div id="partial_view"></div>

	  </div>
	</div>

</div>
