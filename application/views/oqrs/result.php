<?php

if ($qsocount > 0) {
$count = 0;
echo '<br />Log search result for ' . strtoupper($callsign) . ':<br />';
echo '
    <table style="width:100%" class="result-table table-sm table table-bordered table-hover table-striped table-condensed text-center">
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
	foreach ($value as $key => $val) {
		echo '<td>' . $val . '</td>';
		if ($val != '-') {
			$count++;
		}
	}
	echo '</tr>';
}
echo '</tbody></table>';
echo strtoupper($callsign) . ' has ' . $count . ' band slot(s) and has ' . $qsocount . ' QSO(s) in the log.<br /><br />';
?>
<button onclick="requestOqrs();" class="btn btn-primary btn-sm" type="button"> Request QSL</button>
<br>
<?php } else {
	echo '<br />No QSOs found in the log.<br />';
}
	?>
<br>
<button onclick="notInLog();" class="btn btn-primary btn-sm" type="button"> Not in log?</button>