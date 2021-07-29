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
	foreach ($value as $key => $val) {
		if ($val == 'W') {
			$info = '<td><div class=\'alert-danger\'>';
			switch($type) {
				case 'dxcc': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $dxcc).'","' . $key . '","' . $mode . '","DXCC2")\'>W</a>'; break;
				case 'iota': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $iota).'","' . $key . '","' . $mode . '","IOTA")\'>W</a>'; break;
				case 'grid': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $grid).'","' . $key . '","' . $mode . '","VUCC")\'>W</a>'; break;
				case 'cqz':  $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $cqz).'","' . $key . '","' . $mode . '","CQZone")\'>W</a>'; break;
				case 'was':  $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $was).'","' . $key . '","' . $mode . '","WAS")\'>W</a>'; break;
				case 'sota': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $sota).'","' . $key . '","' . $mode . '","SOTA")\'>W</a>'; break;
				case 'wwff': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $wwff).'","' . $key . '","' . $mode . '","WWFF")\'>W</a>'; break;
			}
			$info .= '</div></td>';
			echo $info;
		}
		else if ($val == 'C') {
			$info = '<td><div class=\'alert-success\'>';
			switch($type) {
				case 'dxcc': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $dxcc).'","' . $key . '","' . $mode . '","DXCC2")\'>C</a>'; break;
				case 'iota': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $iota).'","' . $key . '","' . $mode . '","IOTA")\'>C</a>'; break;
				case 'grid': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $grid).'","' . $key . '","' . $mode . '","VUCC")\'>C</a>'; break;
				case 'cqz':  $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $cqz).'","' . $key . '","' . $mode . '","CQZone")\'>C</a>'; break;
				case 'was':  $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $was).'","' . $key . '","' . $mode . '","WAS")\'>C</a>'; break;
				case 'sota': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $sota).'","' . $key . '","' . $mode . '","SOTA")\'>C</a>'; break;
				case 'wwff': $info .= '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $wwff).'","' . $key . '","' . $mode . '","WWFF")\'>C</a>'; break;
			}
			$info .= '</div></td>';
			echo $info;
		}
		else {
			echo '<td>' . $val . '</td>';
		}
	}
	echo '</tr>';
}
echo '</tbody></table>';
?>
