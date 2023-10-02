<div class="table-responsive">
	<table style="width:100%" class="table table-sm tablewas table-bordered table-hover table-striped table-condensed text-center">
		<thead>
        <tr class="titles">
            <th><?php echo lang('general_word_date'); ?></th>
            <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
            <th><?php echo lang('general_word_time'); ?></th>
            <?php } ?>
            <th><?php echo lang('gen_hamradio_call'); ?></th>
<?php
$ci =& get_instance();
			echo '<th>';
				switch($this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1')) {
					case 'Mode': echo lang('gen_hamradio_mode'); break;
					case 'RSTS': echo lang('gen_hamradio_rsts'); break;
					case 'RSTR': echo lang('gen_hamradio_rstr'); break;
					case 'Country': echo lang('general_word_country'); break;
					case 'IOTA': echo lang('gen_hamradio_iota'); break;
					case 'SOTA': echo lang('gen_hamradio_sota'); break;
					case 'State': echo lang('gen_hamradio_state'); break;
					case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
					case 'Distance': echo lang('gen_hamradio_distance'); break;
					case 'Band': echo lang('gen_hamradio_band'); break;
					case 'Frequency': echo lang('gen_hamradio_frequency'); break;
					case 'Operator': echo lang('gen_hamradio_operator'); break;
				}
			echo '</th>';
			echo '<th>';
				switch($this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2')) {
					case 'Mode': echo lang('gen_hamradio_mode'); break;
					case 'RSTS': echo lang('gen_hamradio_rsts'); break;
					case 'RSTR': echo lang('gen_hamradio_rstr'); break;
					case 'Country': echo lang('general_word_country'); break;
					case 'IOTA': echo lang('gen_hamradio_iota'); break;
					case 'State': echo lang('gen_hamradio_state'); break;
					case 'SOTA': echo lang('gen_hamradio_sota'); break;
					case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
					case 'Distance': echo lang('gen_hamradio_distance'); break;
					case 'Band': echo lang('gen_hamradio_band'); break;
					case 'Frequency': echo lang('gen_hamradio_frequency'); break;
					case 'Operator': echo lang('gen_hamradio_operator'); break;
				}
			echo '</th>';
			echo '<th>';
				switch($this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3')) {
					case 'Mode': echo lang('gen_hamradio_mode'); break;
					case 'RSTS': echo lang('gen_hamradio_rsts'); break;
					case 'RSTR': echo lang('gen_hamradio_rstr'); break;
					case 'Country': echo lang('general_word_country'); break;
					case 'IOTA': echo lang('gen_hamradio_iota'); break;
					case 'SOTA': echo lang('gen_hamradio_sota'); break;
					case 'State': echo lang('gen_hamradio_state'); break;
					case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
					case 'Distance': echo lang('gen_hamradio_distance'); break;
					case 'Band': echo lang('gen_hamradio_band'); break;
					case 'Frequency': echo lang('gen_hamradio_frequency'); break;
					case 'Operator': echo lang('gen_hamradio_operator'); break;
				}
			echo '</th>';
			echo '<th>';
				switch($this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4')) {
					case 'Mode': echo lang('gen_hamradio_mode'); break;
					case 'RSTS': echo lang('gen_hamradio_rsts'); break;
					case 'RSTR': echo lang('gen_hamradio_rstr'); break;
					case 'Country': echo lang('general_word_country'); break;
					case 'IOTA': echo lang('gen_hamradio_iota'); break;
					case 'SOTA': echo lang('gen_hamradio_sota'); break;
					case 'State': echo lang('gen_hamradio_state'); break;
					case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
					case 'Distance': echo lang('gen_hamradio_distance'); break;
					case 'Band': echo lang('gen_hamradio_band'); break;
					case 'Frequency': echo lang('gen_hamradio_frequency'); break;
					case 'Operator': echo lang('gen_hamradio_operator'); break;
				}
			echo '</th>';
			echo '<th>';
			switch($this->session->userdata('user_column5')==""?'Country':$this->session->userdata('user_column5')) {
				case 'Mode': echo lang('gen_hamradio_mode'); break;
				case 'RSTS': echo lang('gen_hamradio_rsts'); break;
				case 'RSTR': echo lang('gen_hamradio_rstr'); break;
				case 'Country': echo lang('general_word_country'); break;
				case 'IOTA': echo lang('gen_hamradio_iota'); break;
				case 'SOTA': echo lang('gen_hamradio_sota'); break;
				case 'State': echo lang('gen_hamradio_state'); break;
				case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
				case 'Distance': echo lang('gen_hamradio_distance'); break;
				case 'Band': echo lang('gen_hamradio_band'); break;
				case 'Frequency': echo lang('gen_hamradio_frequency'); break;
				case 'Operator': echo lang('gen_hamradio_operator'); break;
			}
			echo '</th>';

            	if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <th>QSL</th>
                <?php if($this->session->userdata('user_eqsl_name') != "") { ?>
                    <th>eQSL</th>
                <?php } ?>
                <?php if($this->session->userdata('user_lotw_name') != "") { ?>
                    <th>LoTW</th>
                <?php } ?>
            <?php } ?>
                <th><?php echo lang('gen_hamradio_station'); ?></th>
            <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <th></th>
            <?php } ?>
        </tr>
		</thead>
		<tbody>
        <?php  $i = 0;  foreach ($results->result() as $row) { ?>

            <?php
                // Get Date format
                if($this->session->userdata('user_date_format')) {
                    // If Logged in and session exists
                    $custom_date_format = $this->session->userdata('user_date_format');
                } else {
                    // Get Default date format from /config/cloudlog.php
                    $custom_date_format = $this->config->item('qso_date_format');
                }
            ?>
            <?php  echo '<tr class="tr'.($i & 1).'" id="qso_'. $row->COL_PRIMARY_KEY .'">'; ?>
            <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
            <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
            <?php } ?>
            <td>
                <a id="edit_qso" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a>
            </td>
			<?php

			switch($this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
				case 'RSTR': echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
            case 'Frequency':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'; if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'">'.$row->COL_SAT_NAME.'</span>'; } else { echo $row->COL_SAT_NAME; } echo '</a></td>'; } else { if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$row->COL_BAND.'">'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'</span>'; } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
				case 'RSTR': echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
            case 'Frequency':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'; if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'">'.$row->COL_SAT_NAME.'</span>'; } else { echo $row->COL_SAT_NAME; } echo '</a></td>'; } else { if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$row->COL_BAND.'">'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'</span>'; } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
			}
			echo '</td>';

			switch($this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
				case 'RSTR': echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
            case 'Frequency':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'; if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'">'.$row->COL_SAT_NAME.'</span>'; } else { echo $row->COL_SAT_NAME; } echo '</a></td>'; } else { if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$row->COL_BAND.'">'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'</span>'; } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
				case 'RSTR': echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
            case 'Frequency':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'; if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'">'.$row->COL_SAT_NAME.'</span>'; } else { echo $row->COL_SAT_NAME; } echo '</a></td>'; } else { if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$row->COL_BAND.'">'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'</span>'; } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column5')==""?'Country':$this->session->userdata('user_column5')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
				case 'RSTR': echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
            case 'Frequency':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'; if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'">'.$row->COL_SAT_NAME.'</span>'; } else { echo $row->COL_SAT_NAME; } echo '</a></td>'; } else { if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$row->COL_BAND.'">'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'</span>'; } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
			}
			echo '</td>';
				if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>

                <?php
                  echo '<td id="qsl_'.$row->COL_PRIMARY_KEY.'" style=\'text-align: center\' class="qsl">';
                  echo '<span ';
                  if ($row->COL_QSL_SENT != "N") {
                     if ($row->COL_QSLSDATE != null) {
                        $timestamp = ' '.date($custom_date_format, strtotime($row->COL_QSLSDATE));
                     } else {
                        $timestamp = '';
                     }
                     switch ($row->COL_QSL_SENT) {
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
                  if ($row->COL_QSL_SENT_VIA != "") {
                     switch ($row->COL_QSL_SENT_VIA) {
                     case "B":
                        echo " (".lang('general_word_qslcard_bureau').")";
                        break;
                     case "D":
                        echo " (".lang('general_word_qslcard_direct').")";
                        break;
                     case "M":
                        echo " (".lang('general_word_qslcard_via').": ".($row->COL_QSL_VIA!="" ? $row->COL_QSL_VIA:"n/a").")";
                        break;
                     case "E":
                        echo " (".lang('general_word_qslcard_electronic').")";
                        break;
                     }
                  }
                  echo '">&#9650;</span>';
                  echo '<span ';
                  if ($row->COL_QSL_RCVD != "N") {
                     if ($row->COL_QSLRDATE != null) {
                        $timestamp = ' '.date($custom_date_format, strtotime($row->COL_QSLRDATE));
                     } else {
                        $timestamp = '';
                     }
                     switch ($row->COL_QSL_RCVD) {
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
                  if ($row->COL_QSL_RCVD_VIA != "") {
                     switch ($row->COL_QSL_RCVD_VIA) {
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
                ?>
                <?php if ($this->session->userdata('user_eqsl_name') != ""){
                  echo '<td style=\'text-align: center\' class="eqsl">';
                  echo '<span ';
                  if ($row->COL_EQSL_QSL_SENT == "Y") {
                     echo "data-original-title=\"".lang('eqsl_short')." ".lang('general_word_sent');
                     if ($row->COL_EQSL_QSLSDATE != null) {
                        $timestamp = strtotime($row->COL_EQSL_QSLSDATE);
                        echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
                     }
                     echo "\" data-toggle=\"tooltip\"";
                  }
                  echo ' class="eqsl-';
                  echo ($row->COL_EQSL_QSL_SENT=='Y')?'green':'red';
                  echo '">&#9650;</span>';

                  echo '<span ';
                  if ($row->COL_EQSL_QSL_RCVD == "Y") {
                     echo "data-original-title=\"".lang('eqsl_short')." ".lang('general_word_received');
                     if ($row->COL_EQSL_QSLRDATE != null) {
                        $timestamp = strtotime($row->COL_EQSL_QSLRDATE);
                        echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
                     }
                     echo "\" data-toggle=\"tooltip\"";
                  }
                  echo ' class="eqsl-';
                  echo ($row->COL_EQSL_QSL_RCVD=='Y')?'green':'red';
                  echo '">';
                  if($row->COL_EQSL_QSL_RCVD =='Y') {
                     echo '<a style="color: green" href="';
                     echo site_url("eqsl/image/".$row->COL_PRIMARY_KEY);
                     echo '" data-fancybox="images" data-width="528" data-height="336">&#9660;</a>';
                  } else {
                     echo '&#9660;';
                  }
                  echo '</span>';
                  echo '</td>';
                } ?>

                <?php if($this->session->userdata('user_lotw_name') != "") {
                echo '<td style=\'text-align: center\' class="lotw">';
                echo '<span ';
                if ($row->COL_LOTW_QSL_SENT == "Y") {
                   echo "data-original-title=\"".lang('lotw_short')." ".lang('general_word_sent');
                   if ($row->COL_LOTW_QSLSDATE != null) {
                     $timestamp = strtotime($row->COL_LOTW_QSLSDATE);
                     echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
                   }
                   echo "\" data-toggle=\"tooltip\"";
                }
                echo ' class="lotw-';
                echo ($row->COL_LOTW_QSL_SENT=='Y')?'green':'red';
                echo '">&#9650;</span>';

                echo '<span ';
                if ($row->COL_LOTW_QSL_RCVD == "Y") {
                   echo "data-original-title=\"".lang('lotw_short')." ".lang('general_word_received');
                   if ($row->COL_LOTW_QSLRDATE != null) {
                      $timestamp = strtotime($row->COL_LOTW_QSLRDATE);
                      echo " ".($timestamp != '' ? date($custom_date_format, $timestamp) : '');
                   }
                   echo "\" data-toggle=\"tooltip\"";
                }
                echo ' class="lotw-';
                echo ($row->COL_LOTW_QSL_RCVD=='Y')?'green':'red';
                echo '">&#9660;</span>';
                echo '</td>';
                } ?>

            <?php } ?>

                    <?php if(isset($row->station_callsign)) { ?>
                        <td>
                            <span class="badge badge-light"><?php echo $row->station_callsign; ?></span>
                        </td>
                    <?php } ?>

            <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <td>
                    <div class="dropdown">
                        <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" id="edit_qso" href="javascript:qso_edit(<?php echo $row->COL_PRIMARY_KEY; ?>)"><i class="fas fa-edit"></i> <?php echo lang('general_edit_qso'); ?></a>

                            <?php if($row->COL_QSL_SENT !='Y') { ?>
                                <div class="qsl_sent_<?php echo $row->COL_PRIMARY_KEY; ?>">
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:qsl_sent(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_tx_bureau'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_sent(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_tx_direct'); ?></a>
                                </div>
                            <?php } ?>

                            <?php if($row->COL_QSL_RCVD !='Y') { ?>
                                <div class="qsl_rcvd_<?php echo $row->COL_PRIMARY_KEY; ?>">
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_rx_bureau'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_rx_direct'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_requested(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i><?php echo lang('general_mark_qsl_requested'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_ignore(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i><?php echo lang('general_mark_qsl_not_required'); ?></a>
                                </div>
                            <?php } ?>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="https://www.qrz.com/db/<?php echo $row->COL_CALL; ?>" target="_blank"><i class="fas fa-question"></i><?php echo lang('general_lookup_qrz'); ?></a>

                            <a class="dropdown-item" href="https://www.hamqth.com/<?php echo $row->COL_CALL; ?>" target="_blank"><i class="fas fa-question"></i><?php echo lang('general_lookup_hamqth'); ?></a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="javascript:qso_delete(<?php echo $row->COL_PRIMARY_KEY; ?>, '<?php echo $row->COL_CALL; ?>')"><i class="fas fa-trash-alt"></i> <?php echo lang('general_delete_qso'); ?></a>
                        </div>
                    </div>
                </td>
            <?php } ?>
            </tr>
            <?php $i++; } ?>
			</tbody>
    </table>

</div>
