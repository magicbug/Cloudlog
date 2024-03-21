<div class="container">

    <br>

    <h2><?php echo lang('general_word_qslcards'); ?></h2>

    <div class="alert alert-info" role="alert">
    <?php echo lang('qslcard_string_your_are_using'); ?> <?php echo $storage_used; ?> <?php echo lang('qslcard_string_disk_space'); ?>
    </div>

    <?php

	if($this->session->userdata('user_date_format')) {
		// If Logged in and session exists
		$custom_date_format = $this->session->userdata('user_date_format');
	} else {
		// Get Default date format from /config/cloudlog.php
		$custom_date_format = $this->config->item('qso_date_format');
	}

    if ($qslarray !== FALSE && is_array($qslarray->result())) {
        echo '<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed">
        <thead>
        <tr>
        <th style=\'text-align: center\'>'.lang('gen_hamradio_callsign').'</th>
        <th style=\'text-align: center\'>'.lang('gen_hamradio_mode').'</th>
        <th style=\'text-align: center\'>'.lang('general_word_date').'</th>
        <th style=\'text-align: center\'>'.lang('general_word_time').'</th>
        <th style=\'text-align: center\'>'.lang('gen_hamradio_band').'</th>
        <th style=\'text-align: center\'>'.lang('gen_hamradio_qsl').'</th>
        <th style=\'text-align: center\'></th>
        <th style=\'text-align: center\'></th>
        <th style=\'text-align: center\'></th>
        </tr>
        </thead><tbody>';

        foreach ($qslarray->result() as $qsl) {
            echo '<tr>';
            echo '<td style=\'text-align: center\'>' . str_replace("0","&Oslash;",$qsl->COL_CALL) . '</td>';
			echo '<td style=\'text-align: center\'>';
			echo $qsl->COL_SUBMODE==null?$qsl->COL_MODE:$qsl->COL_SUBMODE;
			echo '</td>';
			echo '<td style=\'text-align: center\'>';
			$timestamp = strtotime($qsl->COL_TIME_ON); echo date($custom_date_format, $timestamp);
			echo '</td>';
			echo '<td style=\'text-align: center\'>';
			$timestamp = strtotime($qsl->COL_TIME_ON); echo date('H:i', $timestamp);
			echo '</td>';
			echo '<td style=\'text-align: center\'>';
			if($qsl->COL_SAT_NAME != null) { echo $qsl->COL_SAT_NAME; } else { echo strtolower($qsl->COL_BAND); };
			echo '</td>';
			echo '<td style=\'text-align: center\'>' . $qsl->filename . '</td>';
			echo '<td id="'.$qsl->id.'" style=\'text-align: center\'><button onclick="deleteQsl(\''.$qsl->id.'\')" class="btn btn-sm btn-danger">Delete</button></td>';
            echo '<td style=\'text-align: center\'><button onclick="viewQsl(\''.$qsl->filename.'\', \''. $qsl->COL_CALL . '\')" class="btn btn-sm btn-success">View</button></td>';
			echo '<td style=\'text-align: center\'><button onclick="addQsosToQsl(\''.$qsl->filename.'\')" class="btn btn-sm btn-success">Add Qsos</button></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-warning" role="alert">No QSL Cards Found.</div>';
    }
    ?>

</div>
