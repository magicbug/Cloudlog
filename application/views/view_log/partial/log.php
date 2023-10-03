<div class="table-responsive">
	<table class="table table-sm table-striped table-hover">
		<tr class="titles">
            <td><?php echo lang('general_word_date'); ?></td>
            <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
            <td><?php echo lang('general_word_time'); ?></td>
            <?php } ?>
            <td><?php echo lang('gen_hamradio_call'); ?></td>
            <td><?php echo lang('gen_hamradio_mode'); ?></td>
            <td><?php echo lang('gen_hamradio_rsts'); ?></td>
            <td><?php echo lang('gen_hamradio_rstr'); ?></td>
            <td><?php echo lang('gen_hamradio_band'); ?></td>
            <td><?php echo lang('general_word_country'); ?></td>
            <?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
                <td>QSL</td>
                <?php if($this->session->userdata('user_eqsl_name') != "") { ?>
                    <td>eQSL</td>
                <?php } ?>
                <?php if($this->session->userdata('user_lotw_name') != "") { ?>
                    <td>LoTW</td>
                <?php } ?>
                <td><?php echo lang('gen_hamradio_station'); ?></td>
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
			<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
			<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
				<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
			<?php } ?>
			<td><a data-fancybox data-type="iframe" data-width="750" data-height="520" data-src="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>" href="javascript:;"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a>
			</td>
			<td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
			<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX) { ?><span class="badge badge-light"><?php printf("%03d", $row->COL_STX);?></span><?php } ?><?php if ($row->COL_STX_STRING) { ?><span class="badge badge-light"><?php echo $row->COL_STX_STRING;?></span><?php } ?></td>
			<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX) { ?><span class="badge badge-light"><?php printf("%03d", $row->COL_SRX);?></span><?php } ?><?php if ($row->COL_SRX_STRING) { ?><span class="badge badge-light"><?php echo $row->COL_SRX_STRING;?></span><?php } ?></td>
			<?php if($row->COL_SAT_NAME != null) { ?>
			<td><?php echo $row->COL_SAT_NAME; ?></td>
			<?php } else { ?>
			<td><?php echo strtolower($row->COL_BAND); ?></td>
			<?php } ?>
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
				<a class="eqsl-green" href="<?php echo site_url("eqsl/image/".$row->COL_PRIMARY_KEY); ?>" data-fancybox="images" data-width="528" data-height="336">&#9660;</a>
				<?php } else { ?>
					&#9660;
				<?php } ?>
				</span>
			</td>
			<?php } ?>

			<?php if($this->session->userdata('user_lotw_name') != "") { ?>
			<td class="lotw">
			    <span class="lotw-<?php echo ($row->COL_LOTW_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
			    <span class="lotw-<?php echo ($row->COL_LOTW_QSL_RCVD=='Y')?'green':'red'?>">&#9660;</span>
			</td>
			<?php } ?>

				<?php if(isset($row->station_callsign)) { ?>
				<td>
					<span class="badge badge-light"><?php echo $row->station_callsign; ?></span>
				</td>
				<?php } ?>

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

                    </div>
                    <a class="dropdown-item" href="javascript:qso_delete(<?php echo $row->COL_PRIMARY_KEY; ?>, '<?php echo $row->COL_CALL; ?>')"><i class="fas fa-trash-alt"></i> <?php echo lang('general_delete_qso'); ?></a>
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
