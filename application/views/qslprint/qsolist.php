<?php
if ($qsos->result() != NULL) {
	echo '<table style="width:100%" class="qsolist table-sm table-bordered table-hover table-striped table-condensed">
	<thead>
	<tr>
	<th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_callsign').'</th>
	<th style=\'text-align: center\'>' . $this->lang->line('general_word_date') . '</th>
	<th style=\'text-align: center\'>'. $this->lang->line('general_word_time') .'</th>
	<th style=\'text-align: center\'>' . $this->lang->line('gen_hamradio_mode') . '</th>
	<th style=\'text-align: center\'>' . $this->lang->line('gen_hamradio_band') . '</th>
	<th style=\'text-align: center\'>' . $this->lang->line('gen_hamradio_station') . '</th>
	<th style=\'text-align: center\'>QSL</th>';
	if($this->session->userdata('user_lotw_name') != "") {
		echo '<th style=\'text-align: center\'>LoTW</th>';
	}
	if ($this->session->userdata('user_eqsl_name') != "") {
		echo '<th style=\'text-align: center\'>eQSL</th>';
	}
	echo '<th style=\'text-align: center\'></th>
	</tr>
	</thead><tbody>';

	// Get Date format
	if($this->session->userdata('user_date_format')) {
		// If Logged in and session exists
		$custom_date_format = $this->session->userdata('user_date_format');
	} else {
		// Get Default date format from /config/cloudlog.php
		$custom_date_format = $this->config->item('qso_date_format');
	}

	foreach ($qsos->result() as $qsl) {
		echo '<tr id ="qsolist_'.$qsl->COL_PRIMARY_KEY.'">';
		echo '<td style=\'text-align: center\'>' . $qsl->COL_CALL . '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qsl->COL_TIME_ON); echo date($custom_date_format, $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; $timestamp = strtotime($qsl->COL_TIME_ON); echo date('H:i', $timestamp); echo '</td>';
		echo '<td style=\'text-align: center\'>'; echo $qsl->COL_SUBMODE==null?$qsl->COL_MODE:$qsl->COL_SUBMODE; echo '</td>';
		echo '<td style=\'text-align: center\'>'; if($qsl->COL_SAT_NAME != null) { echo $qsl->COL_SAT_NAME; } else { echo strtolower($qsl->COL_BAND); }; echo '</td>';
		echo '<td style=\'text-align: center\'><span class="badge badge-light">' . $qsl->station_callsign . '</span></td>';
		echo '<td style=\'text-align: center\' class="qsl">
				<span class="qsl-';
                switch ($qsl->COL_QSL_SENT) {
                    case "Y": echo "green"; break;
                    case "Q": echo "yellow"; break;
                    case "R": echo "yellow"; break;
                    case "I": echo "grey"; break;
                    default: echo "red";
                }
		echo '">&#9650;</span>
			<span class="qsl-';
                    switch ($qsl->COL_QSL_RCVD) {
						case "Y": echo "green"; break;
						case "Q": echo "yellow"; break;
						case "R": echo "yellow"; break;
						case "I": echo "grey"; break;
						default: echo "red";
                    }
		echo '">&#9660;</span></td>';
                    if($this->session->userdata('user_lotw_name') != "") {
						echo '<td style=\'text-align: center\' class="lotw">';
						if ($qsl->COL_LOTW_QSL_SENT != ''){
							echo '<span class="lotw-' . ($qsl->COL_LOTW_QSL_SENT=='Y'?'green':'red') . '">&#9650;</span>
								  <span class="lotw-' . ($qsl->COL_LOTW_QSL_RCVD=='Y'?'green':'red') . '">&#9660;</span>';
						}
						echo '</td>';
					}
		if ($this->session->userdata('user_eqsl_name') != ""){
			echo '<td style=\'text-align: center\' class="eqsl">
				<span class="eqsl-' . ($qsl->COL_EQSL_QSL_SENT=='Y'?'green':'red') . '">&#9650;</span>
				<span class="eqsl-' . ($qsl->COL_EQSL_QSL_RCVD=='Y'?'green':'red') . '">&#9650;</span>
				</td>';
		}
		echo '<td id="'.$qsl->COL_PRIMARY_KEY.'" style=\'text-align: center\'><button onclick="addQsoToPrintQueue(\''.$qsl->COL_PRIMARY_KEY.'\')" class="btn btn-sm btn-success">Add to print queue</button></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
	?>

	<?php
} else {
	echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>No additional QSO\'s were found. That means they are probably already in the queue.</div>';
}
?>
