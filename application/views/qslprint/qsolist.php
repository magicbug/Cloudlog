<?php
if ($qsos->result() != NULL) {
	echo '<table style="width:100%" class="qsolist table-sm table-bordered table-hover table-striped table-condensed">
	<thead>
	<tr>
	<th style=\'text-align: center\'>'.lang('gen_hamradio_callsign').'</th>
	<th style=\'text-align: center\'>' . lang('general_word_date') . '</th>
	<th style=\'text-align: center\'>'. lang('general_word_time') .'</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_mode') . '</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_band') . '</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_station') . '</th>
	<th style=\'text-align: center\'>' . lang('gen_hamradio_qsl') . ' ' . lang('general_word_qslcard_via') . '</th>
	<th style=\'text-align: center\'>Sent method</th>
	<th style=\'text-align: center\'>QSL</th>';
	if ($this->session->userdata('user_eqsl_name') != "") {
		echo '<th style=\'text-align: center\'>eQSL</th>';
	}
	if($this->session->userdata('user_lotw_name') != "") {
		echo '<th style=\'text-align: center\'>LoTW</th>';
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
		echo '<td style=\'text-align: center\'>'; if($qsl->COL_SAT_NAME != null) { echo $qsl->COL_SAT_NAME; } else { echo strtolower($qsl->COL_BAND ?? ""); }; echo '</td>';
		echo '<td style=\'text-align: center\'><span class="badge badge-light">' . $qsl->station_callsign . '</span></td>';
		echo '<td style=\'text-align: center\'>' . $qsl->COL_QSL_VIA . '</td>';
		echo '<td style=\'text-align: center\'>'; echo_qsl_sent_via($qsl->COL_QSL_SENT_VIA); echo '</td>';
		echo '<td style=\'text-align: center\' class="qsl">';
		echo '<span ';
		if ($qsl->COL_QSL_SENT != "N") {
			if ($qsl->COL_QSLSDATE != null) {
				$timestamp = ' '.date($custom_date_format, strtotime($qsl->COL_QSLSDATE));
			} else {
				$timestamp = '';
			}
			switch ($qsl->COL_QSL_SENT) {
			case "Y":
				echo "class=\"qsl-green\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_sent').$timestamp;
				break;
			case "Q":
				echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_queued').$timestamp;
				break;
			case "R":
				echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_requested').$timestamp;
				break;
			case "I":
				echo "class=\"qsl-grey\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_invalid_ignore').$timestamp;
				break;
			default:
				echo "class=\"qsl-red";
				break;
			}
		} else { echo "class=\"qsl-red"; }
		if ($qsl->COL_QSL_SENT_VIA != "") {
			switch ($qsl->COL_QSL_SENT_VIA) {
			case "B":
				echo " (".lang('general_word_qslcard_bureau').")";
				break;
			case "D":
				echo " (".lang('general_word_qslcard_direct').")";
				break;
			case "M":
				echo " (".lang('general_word_qslcard_via').": ".($qsl->COL_QSL_VIA!="" ? $qsl->COL_QSL_VIA:"n/a").")";
				break;
			case "E":
				echo " (".lang('general_word_qslcard_electronic').")";
				break;
			}
		}
		echo '">&#9650;</span>';
		echo '<span ';
		if ($qsl->COL_QSL_RCVD != "N") {
			if ($qsl->COL_QSLRDATE != null) {
				$timestamp = ' '.date($custom_date_format, strtotime($qsl->COL_QSLRDATE));
			} else {
				$timestamp = '';
			}
			switch ($qsl->COL_QSL_RCVD) {
			case "Y":
				echo "class=\"qsl-green\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_received').$timestamp;
				break;
			case "Q":
				echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_queued').$timestamp;
				break;
			case "R":
				echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_requested').$timestamp;
				break;
			case "I":
				echo "class=\"qsl-grey\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_invalid_ignore').$timestamp;
				break;
			default:
				echo "class=\"qsl-red";
				break;
			}
		} else { echo "class=\"qsl-red"; }
		if ($qsl->COL_QSL_RCVD_VIA != "") {
			switch ($qsl->COL_QSL_RCVD_VIA) {
			case "B":
				echo " (".lang('general_word_qslcard_bureau').")";
				break;
			case "D":
				echo " (".lang('general_word_qslcard_direct').")";
				break;
			case "M":
				echo " (Manager)";
				break;
			case "E":
				echo " (".lang('general_word_qslcard_electronic').")";
				break;
			}
		}
		echo '">&#9660;</span>';

		if ($this->session->userdata('user_eqsl_name') != ""){
			echo '<td style=\'text-align: center\' class="eqsl">';
			echo '<span ';
			if ($qsl->COL_EQSL_QSL_SENT == "Y") {
				echo "data-original-title=\"".lang('eqsl_short')." ".lang('general_word_sent');
				if ($qsl->COL_EQSL_QSLSDATE != null) {
					$timestamp = strtotime($qsl->COL_EQSL_QSLSDATE);
					echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
				}
				echo "\" data-toggle=\"tooltip\"";
			}
			echo ' class="eqsl-';
			echo ($qsl->COL_EQSL_QSL_SENT=='Y')?'green':'red';
			echo '">&#9650;</span>';

			echo '<span ';
			if ($qsl->COL_EQSL_QSL_RCVD == "Y") {
				echo "data-original-title=\"".lang('eqsl_short')." ".lang('general_word_received');
				if ($qsl->COL_EQSL_QSLRDATE != null) {
					$timestamp = strtotime($qsl->COL_EQSL_QSLRDATE);
					echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
				}
				echo "\" data-toggle=\"tooltip\"";
			}
			echo ' class="eqsl-';
			echo ($qsl->COL_EQSL_QSL_RCVD=='Y')?'green':'red';
			echo '">&#9660;</span>';
			echo '</td>';
		}
		if($this->session->userdata('user_lotw_name') != "") {
			echo '<td style=\'text-align: center\' class="lotw">';
			echo '<span ';
			if ($qsl->COL_LOTW_QSL_SENT == "Y") {
				echo "data-original-title=\"".lang('lotw_short')." ".lang('general_word_sent');
				if ($qsl->COL_LOTW_QSLSDATE != null) {
					$timestamp = strtotime($qsl->COL_LOTW_QSLSDATE);
					echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
				}
				echo "\" data-toggle=\"tooltip\"";
			}
			echo ' class="lotw-';
			echo ($qsl->COL_LOTW_QSL_SENT=='Y')?'green':'red';
			echo '">&#9650;</span>';

			echo '<span ';
			if ($qsl->COL_LOTW_QSL_RCVD == "Y") {
				echo "data-original-title=\"".lang('lotw_short')." ".lang('general_word_received');
				if ($qsl->COL_LOTW_QSLRDATE) {
					$timestamp = strtotime($qsl->COL_LOTW_QSLRDATE);
					echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
				}
				echo "\" data-toggle=\"tooltip\"";
			}
			echo ' class="lotw-';
			echo ($qsl->COL_LOTW_QSL_RCVD=='Y')?'green':'red';
			echo '">&#9660;</span>';
			echo '</td>';
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

function echo_qsl_sent_via($method) {
	switch($method) {
		case 'B': echo 'Bureau'; break;
		case 'D': echo 'Direct'; break;
		case 'E': echo 'Electronic'; break;
	}
}
?>
