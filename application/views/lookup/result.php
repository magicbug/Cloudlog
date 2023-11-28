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
		switch($type) {
			case 'dxcc': $linkinfo = '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $dxcc).'","' . $key . '","' . $mode . '","DXCC2")\'>'  . $val . '</a>'; break;
			case 'iota': $linkinfo = '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $iota).'","' . $key . '","' . $mode . '","IOTA")\'>'   . $val . '</a>'; break;
			case 'vucc': $linkinfo = '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $grid).'","' . $key . '","' . $mode . '","VUCC")\'>'   . $val . '</a>'; break;
			case 'cq':  $linkinfo = '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $cqz).'","'  . $key . '","' . $mode . '","CQZone")\'>' . $val . '</a>'; break;
			case 'was':  $linkinfo = '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $was).'","'  . $key . '","' . $mode . '","WAS")\'>'    . $val . '</a>'; break;
			case 'sota': $linkinfo = '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $sota).'","' . $key . '","' . $mode . '","SOTA")\'>'   . $val . '</a>'; break;
			case 'wwff': $linkinfo = '<a href=\'javascript:displayContacts("'.str_replace("&", "%26", $wwff).'","' . $key . '","' . $mode . '","WWFF")\'>'   . $val . '</a>'; break;
		}

		$info = '<td>';

		if ($val == 'W') {
			$info .= '<div class=\'bg-danger awardsBgDanger\'>' . $linkinfo . '</div>';
		}
		else if ($val == 'C') {
			$info .= '<div class=\'bg-success awardsBgSuccess\'>' . $linkinfo . '</div>';
		}
		else {
			$info .= $val;
		}

		$info .= '</td>';

		echo $info;
	}
	echo '</tr>';
}
echo '</tbody></table>';
?>
