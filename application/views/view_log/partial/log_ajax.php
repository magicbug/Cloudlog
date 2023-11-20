<?php
function echo_table_header_col($ctx, $name) {
	switch($name) {
		case 'Mode': echo '<th>'.$ctx->lang->line('gen_hamradio_mode').'</th>'; break;
		case 'RSTS': echo '<th>'.$ctx->lang->line('gen_hamradio_rsts').'</th>'; break;
		case 'RSTR': echo '<th>'.$ctx->lang->line('gen_hamradio_rstr').'</th>'; break;
		case 'Country': echo '<th>'.$ctx->lang->line('general_word_country').'</th>'; break;
		case 'IOTA': echo '<th>'.$ctx->lang->line('gen_hamradio_iota').'</th>'; break;
		case 'SOTA': echo '<th>'.$ctx->lang->line('gen_hamradio_sota').'</th>'; break;
		case 'WWFF': echo '<th>'.$ctx->lang->line('gen_hamradio_wwff').'</th>'; break;
		case 'POTA': echo '<th>'.$ctx->lang->line('gen_hamradio_pota').'</th>'; break;
		case 'State': echo '<th>'.$ctx->lang->line('gen_hamradio_state').'</th>'; break;
		case 'Grid': echo '<th>'.$ctx->lang->line('gen_hamradio_gridsquare').'</th>'; break;
		case 'Distance': echo '<th>'.$ctx->lang->line('gen_hamradio_distance').'</th>'; break;
		case 'Band': echo '<th>'.$ctx->lang->line('gen_hamradio_band').'</td>'; break;
		case 'Frequency': echo '<th>'.$ctx->lang->line('gen_hamradio_frequency').'</th>'; break;
		case 'Operator': echo '<th>'.$ctx->lang->line('gen_hamradio_operator').'</th>'; break;
		case 'Location': echo '<th>'.$ctx->lang->line('cloudlog_station_profile').'</th>'; break;
	}
}

function echo_table_col($row, $name) {
	$ci =& get_instance();
	switch($name) {
		case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE . '</td>'; break;
        case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
        case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo ' <span data-toggle="tooltip" data-original-title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
		case 'Country': echo '<td>' . ucwords(strtolower(($row->name==null?"- NONE -":$row->name))); if ($row->end != null) echo ' <span class="badge badge-danger">'.$ci->lang->line('gen_hamradio_deleted_dxcc').'</span>' . '</td>'; break;
		case 'IOTA':    echo '<td>' . ($row->COL_IOTA) . '</td>'; break;
		case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF) . '</td>'; break;
		case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF) . '</td>'; break;
		case 'POTA':    echo '<td>' . ($row->COL_POTA_REF) . '</td>'; break;
		case 'Grid':    echo '<td>'; echoQrbCalcLink($row->station_gridsquare, $row->COL_VUCC_GRIDS, $row->COL_GRIDSQUARE); echo '</td>'; break;
		case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : '') . '</td>'; break;
		case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank"><span data-toggle="tooltip" data-original-title="'.$row->COL_BAND.'">'.$row->COL_SAT_NAME.'</span></a></td>'; } else { if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'">'. strtolower($row->COL_BAND).'</span>'; } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
		case 'Frequency':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'; if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'">'.$row->COL_SAT_NAME.'</span>'; } else { echo $row->COL_SAT_NAME; } echo '</a></td>'; } else { if ($row->COL_FREQ != null) { echo ' <span data-toggle="tooltip" data-original-title="'.$row->COL_BAND.'">'.$ci->frequency->hz_to_mhz($row->COL_FREQ).'</span>'; } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
		case 'State':   echo '<td>' . ($row->COL_STATE) . '</td>'; break;
		case 'Operator':echo '<td>' . ($row->COL_OPERATOR) . '</td>'; break;
		case 'Location':echo '<td>' . ($row->station_profile_name) . '</td>'; break;
	}
}

function echoQrbCalcLink($mygrid, $grid, $vucc) {
	if (!empty($grid)) {
		echo $grid . ' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $grid . '\')"><i class="fas fa-globe"></i></a>';
	} else if (!empty($vucc)) {
		echo $vucc .' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $vucc . '\')"><i class="fas fa-globe"></i></a>';
	}
}
?>

<?php if ($results) { ?>

<div class="table-responsive">
    <table style="width:100%" class="table contacttable table-striped table-hover">
        <thead>
            <tr class="titles">
                <th><?php echo lang('general_word_date'); ?></th>
                <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                <th><?php echo lang('general_word_time'); ?></th>
                <?php } ?>
                <th><?php echo lang('gen_hamradio_call'); ?></th>
                <?php
                echo_table_header_col($this, $this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1'));
                echo_table_header_col($this, $this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2'));
                echo_table_header_col($this, $this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3'));
                echo_table_header_col($this, $this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4'));
                echo_table_header_col($this, $this->session->userdata('user_column5'));

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

        <?php  $i = 0;  
            foreach ($results->result() as $row) {
                // Get Date format
                if($this->session->userdata('user_date_format')) {
                    // If Logged in and session exists
                    $custom_date_format = $this->session->userdata('user_date_format');
                } else {
                    // Get Default date format from /config/cloudlog.php
                    $custom_date_format = $this->config->item('qso_date_format');
                }
                echo '<tr class="tr'.($i & 1).'" id="qso_'. $row->COL_PRIMARY_KEY .'">'; ?>
            <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
            <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
            <?php } ?>
            <td>
                <a id="edit_qso" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a>
                <?php
                   if (isset($row->lastupload) && ($row->lastupload)) {
                       $lotw_hint = '';
                       $diff = (time() - strtotime($row->lastupload)) / 86400;
                       if ($diff > 365) {
                          $lotw_hint = ' lotw_info_red';
                       } elseif ($diff > 30) {
                          $lotw_hint = ' lotw_info_orange';
                       } elseif ($diff > 7) {
                          $lotw_hint = ' lotw_info_yellow';
                       }
                       $timestamp = strtotime($row->lastupload); echo ($row->callsign == '' ? '' : ' <a id="lotw_badge" href="https://lotw.arrl.org/lotwuser/act?act='.$row->COL_CALL.'" target="_blank"><small id="lotw_info" class="badge badge-success'.$lotw_hint.'" data-toggle="tooltip" data-original-title="LoTW User. Last upload was '.date($custom_date_format." H:i", $timestamp).'">L</small></a>');
                    }
                 ?>
            </td>
			<?php

                echo_table_col($row, $this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1'));
                echo_table_col($row, $this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2'));
                echo_table_col($row, $this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3'));
                echo_table_col($row, $this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4'));
                echo_table_col($row, $this->session->userdata('user_column5'));
			
				if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <td id="qsl_<?php echo $row->COL_PRIMARY_KEY; ?>" class="qsl">
                <span <?php if ($row->COL_QSL_SENT != "N") {
                       switch ($row->COL_QSL_SENT) {
                       case "Y":
                          echo "class=\"qsl-green\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_sent');
                          break;
                       case "Q":
                          echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_queued');
                          break;
                       case "R":
                          echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_requested');
                          break;
                       case "I":
                          echo "class=\"qsl-grey\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_invalid_ignore');
                          break;
                       default:
                          echo "class=\"qsl-red";
                          break;
                       }
                        if ($row->COL_QSLSDATE != null) {
                            $timestamp = strtotime($row->COL_QSLSDATE); echo " "  .($timestamp != '' ? date($custom_date_format, $timestamp) : ''); 
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
                       } ?>">&#9650;</span>
                <span <?php if ($row->COL_QSL_RCVD != "N") {
                       switch ($row->COL_QSL_RCVD) {
                       case "Y":
                          echo "class=\"qsl-green\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_received');
                          break;
                       case "Q":
                          echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_queued');
                          break;
                       case "R":
                          echo "class=\"qsl-yellow\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_requested');
                          break;
                       case "I":
                          echo "class=\"qsl-grey\" data-toggle=\"tooltip\" data-original-title=\"".lang('general_word_invalid_ignore');
                          break;
                       default:
                          echo "class=\"qsl-red";
                          break;
                       }
                       if ($row->COL_QSLRDATE != null) {
                            $timestamp = strtotime($row->COL_QSLRDATE); echo " "  .($timestamp != '' ? date($custom_date_format, $timestamp) : ''); 
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
                       } ?>">&#9660;</span>
                </td>

                <?php if ($this->session->userdata('user_eqsl_name') != ""){ ?>
                    <td class="eqsl">
                        <span <?php if ($row->COL_EQSL_QSL_SENT == "Y") { echo "data-original-title=\"".lang('eqsl_short')." ".lang('general_word_sent'); if ($row->COL_EQSL_QSLSDATE != null) { $timestamp = strtotime($row->COL_EQSL_QSLSDATE); echo " ".($timestamp!=''?date($custom_date_format, $timestamp):''); } echo "\" data-toggle=\"tooltip\""; } ?> class="eqsl-<?php echo ($row->COL_EQSL_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
                        <span <?php if ($row->COL_EQSL_QSL_RCVD == "Y") { echo "data-original-title=\"".lang('eqsl_short')." ".lang('general_word_received'); if ($row->COL_EQSL_QSLRDATE != null) { $timestamp = strtotime($row->COL_EQSL_QSLRDATE); echo " ".($timestamp!=''?date($custom_date_format, $timestamp):''); } echo "\" data-toggle=\"tooltip\""; } ?> class="eqsl-<?php echo ($row->COL_EQSL_QSL_RCVD=='Y')?'green':'red'?>">
			    	<?php if($row->COL_EQSL_QSL_RCVD =='Y') { ?>
                        <a class="eqsl-green" href="<?php echo site_url("eqsl/image/".$row->COL_PRIMARY_KEY); ?>" data-fancybox="images" data-width="528" data-height="336">&#9660;</a>
                    <?php } else { ?>
                        &#9660;
                    <?php } ?>
			    </span>
                    </td>
                <?php } ?>

                <?php if($this->session->userdata('user_lotw_name') != "") { ?>
                    <td class="lotw">
                        <span <?php if ($row->COL_LOTW_QSL_SENT == "Y") { echo "data-original-title=\"".lang('lotw_short')." ".lang('general_word_sent'); if ($row->COL_LOTW_QSLSDATE != null) { $timestamp = strtotime($row->COL_LOTW_QSLSDATE); echo " ".($timestamp!=''?date($custom_date_format, $timestamp):''); } echo "\" data-toggle=\"tooltip\""; } ?> class="lotw-<?php echo ($row->COL_LOTW_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
                        <span <?php if ($row->COL_LOTW_QSL_RCVD == "Y") { echo "data-original-title=\"".lang('lotw_short')." ".lang('general_word_received'); if ($row->COL_LOTW_QSLRDATE != null) { $timestamp = strtotime($row->COL_LOTW_QSLRDATE); echo " ".($timestamp!=''?date($custom_date_format, $timestamp):''); } echo "\" data-toggle=\"tooltip\""; } ?> class="lotw-<?php echo ($row->COL_LOTW_QSL_RCVD=='Y')?'green':'red'?>">&#9660;</span>
                    </td>
                <?php } ?>

            <?php } ?>

                    <?php if(isset($row->station_callsign)) { ?>
                        <td>
                            <span class="badge badge-light"><?php echo $row->station_callsign; ?></span>
                        </td>
                    <?php } ?>

            <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <td>
                    <div class="dropdown">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" id="edit_qso" href="javascript:qso_edit(<?php echo $row->COL_PRIMARY_KEY; ?>)"><i class="fas fa-edit"></i> <?php echo lang('general_edit_qso'); ?></a>

                            <?php if($row->COL_QSL_SENT !='Y') { ?>
                                <div class="qsl_sent_<?php echo $row->COL_PRIMARY_KEY; ?>">
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:qsl_sent(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_tx_bureau'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_sent(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_tx_direct'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_requested(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_requested'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_ignore(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_not_required'); ?></a>
                                </div>
                            <?php } ?>

                            <?php if($row->COL_QSL_RCVD !='Y') { ?>
                                <div class="qsl_rcvd_<?php echo $row->COL_PRIMARY_KEY; ?>">
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_rx_bureau'); ?></a>
                                    <a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_rx_direct'); ?></a>
                                </div>
                            <?php } ?>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="https://www.qrz.com/db/<?php echo $row->COL_CALL; ?>" target="_blank"><i class="fas fa-question"></i> <?php echo lang('general_lookup_qrz'); ?></a>

                            <a class="dropdown-item" href="https://www.hamqth.com/<?php echo $row->COL_CALL; ?>" target="_blank"><i class="fas fa-question"></i> <?php echo lang('general_lookup_hamqth'); ?></a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="javascript:qso_delete(<?php echo $row->COL_PRIMARY_KEY; ?>, '<?php echo $row->COL_CALL; ?>')"><i class="fas fa-trash-alt"></i> <?php echo lang('general_delete_qso'); ?></a>
                        </div>
                    </div>
                </td>
            <?php } ?>
            </tr>
            <?php $i++; } ?>
                            </tbody>
    </table></div>
    <?php } ?>

    <?php if (isset($this->pagination)){ ?>
        <?php
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        ?>

        <?php echo $this->pagination->create_links(); ?>

    <?php } ?>

</div>
</div>
