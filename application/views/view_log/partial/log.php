<div class="table-responsive">
	<table class="table table-striped table-hover">
		<tr class="titles">
			<td>Date</td>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
			<td>Time</td>
			<?php } ?>
			<td>Call</td>
			<td>Mode</td>
			<td>Sent</td>
			<td>Recv</td>
			<td>Band</td>
			<td>Country</td>
			<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<td>QSL</td>
			<?php if($this->session->userdata('user_eqsl_name') != "") { ?>
			<td>eQSL</td>
			<?php } ?>
			<?php if($this->session->userdata('user_lotw_name') != "") { ?>
			<td>LoTW</td>
			<?php } ?>
			<td>Station</td>
			<td>Operator</td>
			<td></td>
			<?php } ?>
		</tr>
		
		<?php  $i = 0;  foreach ($results->result() as $row) { ?>
			<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
			<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
				<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
			<?php } ?>
			<td><a data-fancybox data-type="iframe" data-src="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>" href="javascript:;"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a></td>
			<td><?php echo $row->COL_MODE; ?></td>
			<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX) { ?><span class="badge badge-light"><?php echo $row->COL_STX;?></span><?php } ?><?php if ($row->COL_STX_STRING) { ?><span class="badge badge-light"><?php echo $row->COL_STX_STRING;?></span><?php } ?></td>
			<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX) { ?><span class="badge badge-light"><?php echo $row->COL_SRX;?></span><?php } ?><?php if ($row->COL_SRX_STRING) { ?><span class="badge badge-light"><?php echo $row->COL_SRX_STRING;?></span><?php } ?></td>
			<?php if($row->COL_SAT_NAME != null) { ?>
			<td><?php echo $row->COL_SAT_NAME; ?></td>
			<?php } else { ?>
			<td><?php echo strtolower($row->COL_BAND); ?></td>
			<?php } ?>
			<td><?php echo $row->COL_COUNTRY; ?></td>
			<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<td class="qsl">
				<span class="qsl-<?php
				switch ($row->COL_QSL_SENT) {
					case "Y":
						echo "green";
						break;
					case "R":
						echo "yellow";
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
					case "R":
						echo "yellow";
						break;
					default:
					   echo "red";
				}
				?>">&#9660;</span>
			</td>
			
			<?php if ($this->session->userdata('user_eqsl_name') != ""){ ?>
			<td class="eqsl">
			    <span class="eqsl-<?php echo ($row->COL_EQSL_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
			    <span class="eqsl-<?php echo ($row->COL_EQSL_QSL_RCVD=='Y')?'green':'red'?>">&#9660;</span>
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
			
			<?php if($this->config->item('callsign_tags') == true) { ?>
				<?php if(isset($row->station_callsign)) { ?>
				<td>
					<span class="badge badge-light"><?php echo $row->station_callsign; ?></span>
				</td>
				<?php } ?>
			<?php } ?>
			<td>
				<span class="badge badge-light"><?php echo $row->COL_OPERATOR; ?></span>
			</td>
			<td>
				<div class="dropdown">
				  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fas fa-cog"></i>
				  </a>

				  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
				  	<a class="dropdown-item" id="edit_qso" data-fancybox data-type="iframe" data-src="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" href="javascript:;"><i class="fas fa-edit"></i> Edit QSO</a>
				  	
				  	<div class="dropdown-divider"></div>	

				  	<?php if($row->COL_QSL_RCVD !='Y') { ?> 
				    <a class="dropdown-item" href="<?php echo site_url('qso/qsl_rcvd'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>/B" ><i class="fas fa-envelope"></i> Mark QSL Received (Bureau)</a>
				    <a class="dropdown-item" href="<?php echo site_url('qso/qsl_rcvd'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>/D" ><i class="fas fa-envelope"></i> Mark QSL Received (Direct)</a>
					<?php } ?>

					<div class="dropdown-divider"></div>	

					<a class="dropdown-item" href="<?php echo site_url('qso/delete'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" onclick="return confirm('Are you sure you want delete QSO <?php echo $row->COL_CALL; ?>?');"><i class="fas fa-trash-alt"></i> Delete QSO</a>
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
