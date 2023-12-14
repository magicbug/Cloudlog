<?php

function echo_status($status) {
	switch($status) {
		case '0': echo 'Open request'; break;
		case '1': echo 'Not in log request'; break;
		case '2': echo 'Request done'; break;
	}
}
function echo_qsl_method($method) {
	switch(strtoupper($method)) {
		case 'B': echo 'Bureau'; break;
		case 'D': echo 'Direct'; break;
		case 'E': echo 'Electronic'; break;
	}
}

?>
<div class="container-fluid oqrs pt-3 ps-4 pe-4">
	<h2><?php echo $page_title; ?></h2>
	<?php if ($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>
<div class="row">
	<form id="searchForm" name="searchForm" action="<?php echo base_url()."index.php/oqrs/search";?>" method="post">
		<div class="row">
			<div class="forn-group col-lg-2 col-md-2 col-sm-3 col-xl">
				<label class="form-label" for="de">De</label>
				<select id="de" name="de" class="form-select form-select-sm">
					<option value="">All</option>
					<?php
					foreach($stations->result() as $station){
						?><option value="<?php echo htmlentities($station->station_id);?>"><?php echo htmlspecialchars($station->station_profile_name . ' - ' . $station->station_callsign);?></option><?php
					}
					?>
				</select>
			</div>
			<div class="forn-group col-lg-2 col-md-2 col-sm-3 col-xl">
				<label class="form-label" for="dx">Dx</label>
				<input type="text" name="dx" id="dx" class="form-control form-control-sm" value="">
			</div>
			
			<div class="forn-group col-lg-2 col-md-2 col-sm-3 col-xl">
				<label for="status">OQRS Status</label>
				<select id="status" name="status" class="form-select form-select-sm">
					<option value="">All</option>
					<option value="0">Open request</option>
					<option value="1">Not in log request</option>
					<option value="2">Request done</option>
				</select>
			</div>
			<div class="forn-group col-lg-2 col-md-2 col-sm-3 col-xl">
				<label for="oqrsResults"># Results</label>
				<select id="oqrsResults" name="oqrsResults" class="form-select form-select-sm">
					<option value="50">50</option>
					<option value="200">200</option>
					<option value="500">500</option>
					<option value="1000">1000</option>
					<option value="All">All</option>
				</select>
			</div>
			<div class="mb-3">
				<label>&nbsp;</label><br>
				<button type="submit" class="btn btn-sm btn-primary" id="searchButton">Search</button>
				<button type="reset" class="btn btn-sm btn-danger" id="resetButton">Reset</button>
			</div>
		</div>
	</form>
</div>
<div class="row">
	<div class=" mb-2">
		<span class="h6">With selected :</span>
		<button type="button" class="btn btn-sm btn-warning" id="markOqrs">Mark as done</button>
        <button type="button" class="btn btn-sm btn-danger" id="deleteOqrs">Delete</button>
		<span id="infoBox"></span>
	</div>
</div>
<table style="width:100%" class="table-sm oqrstable table table-striped table-bordered table-hover table-condensed text-center" id="qsoList">
	<thead>
		<tr>
			<th><div class="form-check" style="margin-top: -1.5em"><input class="form-check-input" type="checkbox" id="checkBoxAll" /></div></th>
			<th>Time of request</th>
			<th>QSO Date</th>
			<th>QSO Time</th>
			<th>Band</th>
			<th>Mode</th>
			<th>Request callsign</th>
			<th>Station callsign</th>
			<th>E-mail</th>
			<th>Note</th>
			<th>QSL route</th>
			<th>Check log</th>
			<th>Status</th>

		</tr>
	</thead>
	<tbody>
		<?php
		/*foreach ($result as $qso) {
			echo '<tr class="oqrsid_'.$qso->id.'" oqrsid="'.$qso->id.'">';
			echo '<td><div class="form-check"><input class="form-check-input" type="checkbox" /></div></td>';
			echo '<td>'. $qso->requesttime .'</td>';
			echo '<td>'. $qso->date .'</td>';
			echo '<td>'. $qso->time .'</td>';
			echo '<td>'. $qso->band .'</td>';
			echo '<td>'. $qso->mode .'</td>';
			echo '<td>'. $qso->requestcallsign .'</td>';
			echo '<td>'. $qso->station_callsign .'</td>';
			echo '<td>'. $qso->email .'</td>';
			echo '<td>'. $qso->note .'</td>';
			echo '<td>'; echo_qsl_method($qso->qslroute); echo '</td>';
			echo '<td><button class="btn btn-primary btn-sm" type="button" onclick="searchLog(\''. $qso->requestcallsign .'\');"><i class="fas fa-search"></i> Call</button>
				<button class="btn btn-primary btn-sm" type="button" onclick="searchLogTimeDate(\''. $qso->id .'\');"><i class="fas fa-search"></i> Date/Time</button>
				</td>';
			echo '<td>'; echo_status($qso->status); echo '</td>';
			echo '</tr>';
		}*/
		?>
	</tbody>
</table>