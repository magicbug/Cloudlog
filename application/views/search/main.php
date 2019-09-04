<div class="container search">

	<h2>Search</h2>

	<form method="post" action="" id="search_box" name="test">
	  <div class="form-group row">
	    <label for="callsign" class="col-sm-2 col-form-label">Callsign</label>
	    <div class="col-sm-8">
	      <input type="text" class="form-control" id="callsign" value="">
	    </div>
	    <div class="col-sm-2">
	    	<button onclick="searchButtonPress()" class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i> Search</button>
	    </div>
	  </div>
	</form>

	<div id="partial_view"></div>

</div>
