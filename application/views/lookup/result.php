<?php
echo '
    <table style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">
	    <thead>
			<tr>
				<th></th>';
			foreach($bands as $band) {
				echo '<th>' . $band . '</th>';
			}
    echo '</tr>
		</thead>
		<tbody>';
foreach ($result as $mode => $value) {
	echo '<tr>
                <td>'. strtoupper($mode) .'</td>';
	foreach ($value  as $key) {
		if ($key == 'W') {
			echo '<td><div class=\'alert-danger\'>' . $key . '</div></td>';
		}
		else if ($key == 'C') {
			echo '<td><div class=\'alert-success\'>' . $key . '</div></td>';
		}
		else {
			echo '<td>' . $key . '</td>';
		}
	}
	echo '</tr>';
}
echo '</tbody></table>';
?>
