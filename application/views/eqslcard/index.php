<div class="container">

    <br>

    <h2><?php echo $this->lang->line('general_word_eqslcards'); ?></h2>

    <div class="alert alert-info" role="alert">
    <?php echo $this->lang->line('qslcard_string_your_are_using'); ?> <?php echo $storage_used; ?> <?php echo $this->lang->line('qslcard_string_disk_space'); ?>
    </div>

    <?php

	if($this->session->userdata('user_date_format')) {
		// If Logged in and session exists
		$custom_date_format = $this->session->userdata('user_date_format');
	} else {
		// Get Default date format from /config/cloudlog.php
		$custom_date_format = $this->config->item('qso_date_format');
	}

    if (is_array($qslarray->result())) {
        echo '<table style="width:100%" class="eqsltable table table-sm table-bordered table-hover table-striped table-condensed">
        <thead>
        <tr>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_callsign').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_mode').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('general_word_date').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('general_word_time').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_band').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_qsl').'</th>
        <th style=\'text-align: center\'></th>
        </tr>
        </thead><tbody>';

        foreach ($qslarray->result() as $qsl) {
            echo '<tr>';
            echo '<td style=\'text-align: center\'><a id="edit_qso" href="javascript:displayQso('.$qsl->COL_PRIMARY_KEY.')">' . str_replace("0","&Oslash;",$qsl->COL_CALL) . '</a></td>';
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
			echo '<td style=\'text-align: center\'>' . $qsl->image_file . '</td>';
            echo '<td style=\'text-align: center\'><button onclick="viewEqsl(\''.$qsl->image_file.'\', \''. $qsl->COL_CALL . '\')" class="btn btn-sm btn-success">View</button></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
    ?>

</div>
