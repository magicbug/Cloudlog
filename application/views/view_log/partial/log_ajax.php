<div class="table-responsive">
    <table class="table table-striped table-hover">
        <tr class="titles">
            <td><?php echo $this->lang->line('general_word_date'); ?></td>
            <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
            <td><?php echo $this->lang->line('general_word_time'); ?></td>
            <?php } ?>
            <td><?php echo $this->lang->line('gen_hamradio_call'); ?></td>
<?php
			echo '<td>';
				switch($this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1')) {
					case 'Mode': echo $this->lang->line('gen_hamradio_mode'); break;
					case 'RSTS': echo $this->lang->line('gen_hamradio_rsts'); break;
					case 'RSTR': echo $this->lang->line('gen_hamradio_rstr'); break;
					case 'Country': echo $this->lang->line('general_word_country'); break;
					case 'IOTA': echo $this->lang->line('gen_hamradio_iota'); break;
					case 'State': echo $this->lang->line('gen_hamradio_state'); break;
					case 'Grid': echo $this->lang->line('gen_hamradio_gridsquare'); break;
					case 'Band': echo $this->lang->line('gen_hamradio_band'); break;
				}
			echo '</td>';
			echo '<td>';
				switch($this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2')) {
					case 'Mode': echo $this->lang->line('gen_hamradio_mode'); break;
					case 'RSTS': echo $this->lang->line('gen_hamradio_rsts'); break;
					case 'RSTR': echo $this->lang->line('gen_hamradio_rstr'); break;
					case 'Country': echo $this->lang->line('general_word_country'); break;
					case 'IOTA': echo $this->lang->line('gen_hamradio_iota'); break;
					case 'State': echo $this->lang->line('gen_hamradio_state'); break;
					case 'Grid': echo $this->lang->line('gen_hamradio_gridsquare'); break;
					case 'Band': echo $this->lang->line('gen_hamradio_band'); break;
				}
			echo '</td>';
			echo '<td>';
				switch($this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3')) {
					case 'Mode': echo $this->lang->line('gen_hamradio_mode'); break;
					case 'RSTS': echo $this->lang->line('gen_hamradio_rsts'); break;
					case 'RSTR': echo $this->lang->line('gen_hamradio_rstr'); break;
					case 'Country': echo $this->lang->line('general_word_country'); break;
					case 'IOTA': echo $this->lang->line('gen_hamradio_iota'); break;
					case 'State': echo $this->lang->line('gen_hamradio_state'); break;
					case 'Grid': echo $this->lang->line('gen_hamradio_gridsquare'); break;
					case 'Band': echo $this->lang->line('gen_hamradio_band'); break;
				}
			echo '</td>';
			echo '<td>';
				switch($this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4')) {
					case 'Mode': echo $this->lang->line('gen_hamradio_mode'); break;
					case 'RSTS': echo $this->lang->line('gen_hamradio_rsts'); break;
					case 'RSTR': echo $this->lang->line('gen_hamradio_rstr'); break;
					case 'Country': echo $this->lang->line('general_word_country'); break;
					case 'IOTA': echo $this->lang->line('gen_hamradio_iota'); break;
					case 'State': echo $this->lang->line('gen_hamradio_state'); break;
					case 'Grid': echo $this->lang->line('gen_hamradio_gridsquare'); break;
					case 'Band': echo $this->lang->line('gen_hamradio_band'); break;
				}
			echo '</td>';
			?>
            <td><?php echo $this->lang->line('general_word_country'); ?></td>
            <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <td>QSL</td>
                <?php if($this->session->userdata('user_eqsl_name') != "") { ?>
                    <td>eQSL</td>
                <?php } ?>
                <?php if($this->session->userdata('user_lotw_name') != "") { ?>
                    <td>LoTW</td>
                <?php } ?>
            <?php } ?>
                <td><?php echo $this->lang->line('gen_hamradio_station'); ?></td>
            <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <td></td>
            <?php } ?>
        </tr>

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
            <?php  echo '<tr class="tr'.($i & 1).'" id ="qso_'. $row->COL_PRIMARY_KEY .'">'; ?>
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
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge badge-light">' . $row->COL_STX . '</span>';}if ($row->COL_STX_STRING) { echo '<span class="badge badge-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge badge-light">' . $row->COL_SRX . '</span>';}if ($row->COL_SRX_STRING) { echo '<span class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'Grid':    echo '<td>' . ($row->COL_GRIDSQUARE); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge badge-light">' . $row->COL_STX . '</span>';}if ($row->COL_STX_STRING) { echo '<span class="badge badge-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge badge-light">' . $row->COL_SRX . '</span>';}if ($row->COL_SRX_STRING) { echo '<span class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'Grid':    echo '<td>' . ($row->COL_GRIDSQUARE); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
			}
			echo '</td>';

			switch($this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge badge-light">' . $row->COL_STX . '</span>';}if ($row->COL_STX_STRING) { echo '<span class="badge badge-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge badge-light">' . $row->COL_SRX . '</span>';}if ($row->COL_SRX_STRING) { echo '<span class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'Grid':    echo '<td>' . ($row->COL_GRIDSQUARE); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge badge-light">' . $row->COL_STX . '</span>';}if ($row->COL_STX_STRING) { echo '<span class="badge badge-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge badge-light">' . $row->COL_SRX . '</span>';}if ($row->COL_SRX_STRING) { echo '<span class="badge badge-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'Grid':    echo '<td>' . ($row->COL_GRIDSQUARE); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
			}
			echo '</td>';
			?>

            <td><?php echo ucwords(strtolower(($row->COL_COUNTRY))); ?></td>
            <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <td class="qsl">
				<span class="qsl-<?php
                switch ($row->COL_QSL_SENT) {
                    case "Y":
                        echo "green";
                        break;
                    case "Q":
                        echo "yellow";
                        break;
                    case "R":
                        echo "yellow";
                        break;
                    case "I":
                        echo "grey";
                        break;
                    default:
                        echo "red";
                }
                ?>">&#9650;</span>
                    <span class="qsl-<?php
                    switch ($row->COL_QSL_RCVD) {
                        case "Y":
                            echo "green";
                            break;
                        case "Q":
                            echo "yellow";
                            break;
                        case "R":
                            echo "yellow";
                            break;
                        case "I":
                            echo "grey";
                            break;
                        default:
                            echo "red";
                    }
                    ?>">&#9660;</span>
                </td>

                <?php if ($this->session->userdata('user_eqsl_name') != ""){ ?>
                    <td class="eqsl">
                        <span class="eqsl-<?php echo ($row->COL_EQSL_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
                        <span class="eqsl-<?php echo ($row->COL_EQSL_QSL_RCVD=='Y')?'green':'red'?>">
			    	<?php if($row->COL_EQSL_QSL_RCVD =='Y') { ?>
                        <a style="color: green" href="<?php echo site_url("eqsl/image/".$row->COL_PRIMARY_KEY."/".$row->COL_CALL."/".$row->COL_MODE."/".$row->COL_BAND."/".date('H', $timestamp)."/".date('i', $timestamp)."/".date('d', $timestamp)."/".date('m', $timestamp)."/".date('Y', $timestamp)); ?>" data-fancybox="images" data-width="528" data-height="336">&#9660;</a>
                    <?php } else { ?>
                        &#9660;
                    <?php } ?>
			    </span>
                    </td>
                <?php } ?>

                <?php if($this->session->userdata('user_lotw_name') != "") { ?>
                    <td class="lotw">
                        <?php if ($row->COL_LOTW_QSL_SENT != ''){ ?>
                            <span class="lotw-<?php echo ($row->COL_LOTW_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
                            <span class="lotw-<?php echo ($row->COL_LOTW_QSL_RCVD=='Y')?'green':'red'?>">&#9660;</span>
                        <?php } ?>
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
                            <a class="dropdown-item" id="edit_qso" href="javascript:qso_edit(<?php echo $row->COL_PRIMARY_KEY; ?>)"><i class="fas fa-edit"></i> <?php echo $this->lang->line('general_edit_qso'); ?></a>
                            <div class="qsl_<?php echo $row->COL_PRIMARY_KEY; ?>">
                            <div class="dropdown-divider"></div>

                            <?php if($row->COL_QSL_RCVD !='Y') { ?>
                                <a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B')" ><i class="fas fa-envelope"></i> <?php echo $this->lang->line('general_mark_qsl_rx_bureau'); ?></a>
                                <a class="dropdown-item" href="javascript:qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D')" ><i class="fas fa-envelope"></i> <?php echo $this->lang->line('general_mark_qsl_rx_direct'); ?></a>
                            <?php } ?>

                            <div class="dropdown-divider"></div>
                            </div>
                            <a class="dropdown-item" href="javascript:qso_delete(<?php echo $row->COL_PRIMARY_KEY; ?>, '<?php echo $row->COL_CALL; ?>')"><i class="fas fa-trash-alt"></i> <?php echo $this->lang->line('general_delete_qso'); ?></a>
                        </div>
                    </div>
                </td>
            <?php } ?>
            </tr>
            <?php $i++; } ?>

    </table>

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
