	<table width="100%">
		<tr class="titles">
			<td>Date</td>
			<td>Time</td>
			<td>Call</td>
			<td>Mode</td>
			<td>Sent</td>
			<td>Recv</td>
			<td>Band</td>
			<td>Country</td>
			<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<td></td>
			<td></td>
			<?php } ?>
		</tr>
		
		<?php  $i = 0;  foreach ($results->result() as $row) { ?>
			<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
			<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
			<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
			<td><a class="qsobox" href="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>"><?php echo strtoupper($row->COL_CALL); ?></a></td>
			<td><?php echo $row->COL_MODE; ?></td>
			<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX_STRING) { ?><span class="label"><?php echo $row->COL_STX_STRING;?></span><?php } ?></td>
			<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX_STRING) { ?><span class="label"><?php echo $row->COL_SRX_STRING;?></span><?php } ?></td>
			<?php if($row->COL_SAT_NAME != null) { ?>
			<td><?php echo $row->COL_SAT_NAME; ?></td>
			<?php } else { ?>
			<td><?php echo $row->COL_BAND; ?></td>
			<?php } ?>
			<td><?php echo $row->COL_COUNTRY; ?></td>
			<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<td>
				<?php
					if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_SENT == "Y") 
					{
				?>
					<img src="<?php echo base_url();?>images/icons/qslcard.png" alt="QSL Cards Both sent and received" title="QSL Cards Both sent and received" />
				<?php
					} elseif($row->COL_QSL_RCVD == "Y") {
				?>
					<img src="<?php echo base_url();?>images/icons/qslcard_in.png" alt="QSL Cards received" title="QSL Cards received" />
				<?php
					} elseif($row->COL_QSL_SENT == "Y") {
				?>
					<img src="<?php echo base_url();?>images/icons/qslcard_sent.png" alt="QSL Cards sent" title="QSL Cards sent" />
				<?php } ?>
			</td>
			<td><a class="editbox" href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" ><img src="<?php echo base_url(); ?>/images/application_edit.png" width="16" height="16" alt="Edit" />
			</a></td>
			<?php } ?>
		</tr>
		<?php $i++; } ?>
		
	</table>