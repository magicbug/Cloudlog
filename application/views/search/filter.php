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
	  <div class="card-body main">

		<div class="card-text col-md-4" id="builder"></div>

		<p class="card-text">
		<button class="btn btn-sm btn-primary ld-ext-right searchbutton" id="btn-get">Search<div class="ld ld-ring ld-spin"></div></button>

		<button class="btn btn-sm btn-warning" id="btn-reset">Reset</button>
		</p>
	  <p>
		<button style="display:none;" class="btn btn-sm btn-primary" id="btn-save">Save query</button>

		  <?php if ($stored_queries) { ?>
			<button class="btn btn-sm btn-primary" onclick="edit_stored_query_dialog()" id="btn-edit">Edit queries</button></p>


		  <div class="mb-3 row querydropdownform">
			  <label class="col-md-2 control-label" for="querydropdown">  Stored queries:</label>
			  <div class="col-md-2">
				  <select id="querydropdown" name="querydropdown" class="form-select form-select-sm">
					  <?php
					  foreach($stored_queries as $q){
						  echo '<option value="' . $q->id . '">'. $q->description . '</option>'."\n";
					  }
					  ?>
				  </select>
			  </div>
			  <button class="btn btn-sm btn-primary ld-ext-right runbutton col-md-1" onclick="run_query()">Run Query<div class="ld ld-ring ld-spin"></div></button>
		  </div>

			<?php
		  } else {
			echo '</p>';
		  }
		  ?>

	    	<div style="display:none;"><span  class="badge text-bg-info">Info</span> You can find out how to use the <a href="https://github.com/magicbug/Cloudlog/wiki/Search----Filter" target="_blank">search filter functions</a> in the wiki.</a></div>

	  </div>
	</div>

	<br>

	<!-- Search Results here -->
	<div class="card search-results-box">
	  <div class="card-header">
	    Search Results:  <div class="exportbutton"><button class="btn btn-sm btn-primary" id="btn-export">Export to ADIF</button></div>
	  </div>
	  <div class="card-body result">

	  </div>
	</div>

</div>
