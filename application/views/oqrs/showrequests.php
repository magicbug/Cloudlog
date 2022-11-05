<div class="container">
    <br>
    <h2><?php echo $page_title; ?></h2>
		<?php
			if (count($result) > 0) {
				$station_id = '';
				$tablebody = '';
				$requester = '';
				$first = true;
				$second = true;
				foreach ($result as $qso) {
					if ($station_id != $qso->station_id) {
						if (!$first) {
							write_table($tablebody);
							$tablebody = '';
							echo '</div></div><br />';
						}
						insert_station_data($qso);
						$first = false;
					} 
					if ($requester != $qso->requestcallsign) {
						if (!$second) {
							write_table($tablebody);
						}
						$second = false;
						insert_requester($qso);
						write_table_header();
						$tablebody = '';
					}
					$tablebody .= insert_qso_data($qso);

					$requester = $qso->requestcallsign;
					$station_id = $qso->station_id;
				}
				write_table($tablebody);
				echo '</div></div><br />';
			} else {
				echo 'No OQRS requests were found at this time.';
			}
			?>
        </div>
    </div>
</div>

<?php

function insert_station_data($station) {
	?>
	   <div>
		   <h5>
			   Station id: <strong><?php echo $station->station_id; ?></strong> 
			   Station callsign: <strong><?php echo $station->station_callsign; ?></strong> 
			   Profile Name: <strong><?php echo $station->station_profile_name; ?></strong> 
			   Country: <strong><?php echo $station->station_country; ?></strong>
			   Gridsquare: <strong><?php echo $station->station_gridsquare; ?></strong>
			</h5>
		<div>
			<?php 
}

function insert_requester($requester) {
?>
OQRS Request:
	<table style="width:100%" class="result-table table-sm table table-bordered table-hover table-striped table-condensed text-center">
	<thead>
	<tr>
		<th>Requester</th>
		<th>Time of request</th>
		<th>E-mail</th>
		<th>Note</th>
		<th>QSL route</th>
	</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $requester->requestcallsign ?></td>
			<td><?php echo $requester->requesttime ?></td>
			<td><?php echo $requester->email ?></td>
			<td><?php echo $requester->note ?></td>
			<td><?php echo $requester->qslroute; ?></td>
		</tr>
	</tbody>
	</table>
	<?php
}

function insert_qso_data($qso) {
	$tablebody = '';
		$tablebody .= '<tr class="oqrsid_'.$qso->id.'">
			<td>' . $qso->date . '</td>
			<td>' . $qso->time . '</td>
			<td>' . $qso->band . '</td>
			<td>' . $qso->mode . '</td>
			<td><button class="btn btn-primary btn-sm" type="button" onclick="searchLog(\''. $qso->requestcallsign .'\');"><i class="fas fa-search"></i> Call</button>
			<button class="btn btn-primary btn-sm" type="button" onclick="searchLogTimeDate(\''. $qso->id .'\');"><i class="fas fa-search"></i> Date/Time</button>
			</td>
			<td><a href="javascript:markOqrsLineAsDone('. $qso->id .');" class="btn btn-danger btn-sm" onclick=""><i class="fas fa-plus-square"></i></a></td>
			<td><a href="javascript:deleteOqrsLine('. $qso->id .');" class="btn btn-danger btn-sm" onclick=""><i class="fas fa-trash-alt"></i></a></td>
			</tr>';
	return $tablebody;
}

function write_table_header() {
	?>
	<table style="width:100%" class="result-table table-sm table table-bordered table-hover table-striped table-condensed text-center">
	<thead>
	<tr>
		<th>Date</th>
		<th>Time (UTC)</th>
		<th>Band</th>
		<th>Mode</th>
		<th>Check log</th>
		<th>Mark as done</th>
		<th>Delete</th>
	</tr>
	</thead>
	<tbody>
<?php
}

function write_table($tablebody) {
	echo $tablebody; ?>
	</tbody>
	</table>
<?php
}