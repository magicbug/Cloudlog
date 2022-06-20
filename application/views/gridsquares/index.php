<div class="container">

	<br>

	<h2><?php echo $page_title; ?></h2>

	<?php if ($this->uri->segment(1) == "gridsquares" && $this->uri->segment(2) == "band") { ?>
<form class="form-inline">
	<label class="my-1 mr-2" for="gridsquare_bands">Band Selection</label>
	<select class="custom-select my-1 mr-sm-2"  id="gridsquare_bands"></select>
</form>

<?php } ?>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>
</div>

	<div id="gridsquare_map" style="width: 100%; height: 800px"></div>

<div class="container">
	<?php if ($this->uri->segment(2) == "satellites") { ?>
		<div class="alert alert-success" role="alert">
			Confirmed is Green <span id="confirmed_grids"></span>| Worked but not confirmed is Red <span id="worked_grids"></span>|<span id="sum_grids"></span>
		</div>
	<?php } ?>

	<?php if ($this->uri->segment(2) == "band") { ?>
		<div class="alert alert-success" role="alert">
			Confirmed is Green <span id="confirmed_grids"></span>| Worked but not confirmed is Red <span id="worked_grids"></span>|<span id="sum_grids"></span><br>
			[This map does not include satellite, internet or repeater QSOs]
		</div>
	<?php } ?>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><span id="qso_count"></span> QSO<span id="gt1_qso"></span> in Square: <span id="square_number"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<table id="grid_results" class="table table-sm">
		  <thead class="thead-dark">
		    <tr>
		      <th scope="col">Date/Time</th>
		      <th scope="col">Callsign</th>
		      <th scope="col">Mode</th>
		      <th scope="col">Band</th>
		      <th scope="col">Gridsquare</th>
		    </tr>
		  </thead>
		  <tbody>
		  </tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
