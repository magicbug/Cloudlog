<div class="container qsl_management">
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<div class="row">
	    <div class="col-sm-8">

	    	<div class="card">
	    		<div class="card-header"><h3>Incoming QSL Cards</h3></div>
	    		<div class="card-body">

	    			<form>
						<input type="text" class="form-control" placeholder="Callsign">
					</form>

  					<table class="table">
  						<thead>
  							<tr>
  								<th>Date/Time</th>
  								<th>Band</th>
  								<th>Report</th>
  								<th>Option</th>
  							</tr>
  						</thead>

  						<tbody>
  							<tr>
  								<td>1</td>
  								<td>2</td>
  								<td>3</td>
  								<td>4</td>
  							</tr>
  						</tbody>	
  					</table>
	    		</div>
	    	</div>

	    </div>

	    <div class="col-sm-4">
	    	<div class="card">
	    		<div class="card-header"><h3>Outgoing QSL Cards</h3></div>
	    		<div class="card-body">

	    			<form>
						<input type="text" class="form-control" placeholder="Callsign">
					</form>
	    		</div>
	    	</div>
	    </div>
	</div>

</div>