<div class="table-responsive">
	<table class="table table-striped table-hover">
		<tr class="titles">
			<td>Date</td>
			<td>Time</td>
			<td>Call</td>
			<td>Mode</td>
			<td>Sent</td>
			<td>Recv'd</td>
			<td>Band</td>
			<td>Country</td>
			<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<td>QSL</td>
			
			<?php if($this->session->userdata('user_eqsl_name') != "") { ?>
				<td>eQSL</td>
			<?php } else { ?>
				<td></td>
			<?php } ?>

			<?php if($this->session->userdata('user_lotw_name') != "") { ?>
				<td>LoTW</td>
			<?php } else { ?>
				<td></td>
			<?php } ?>

			<td></td>
			<?php } ?>
		</tr>
		
		<?php  $i = 0;  foreach ($results->result() as $row) { ?>
			<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
			<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
			<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
			<td><a id="edit_qso" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a></td>
			<td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
			<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX_STRING) { ?><span class="label"><?php echo $row->COL_STX_STRING;?></span><?php } ?></td>
			<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX_STRING) { ?><span class="label"><?php echo $row->COL_SRX_STRING;?></span><?php } ?></td>
			<?php if($row->COL_SAT_NAME != null) { ?>
			<td><?php echo $row->COL_SAT_NAME; ?></td>
			<?php } else { ?>
			<td><?php echo $row->COL_BAND; ?></td>
			<?php } ?>
			<td><?php echo ucwords(strtolower(($row->COL_COUNTRY))); ?></td>
			<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
			<td>
				<span class="qsl-<?php echo ($row->COL_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
				<span class="qsl-<?php echo ($row->COL_QSL_RCVD=='Y')?'green':'red'?>">&#9660;</span>
			</td>

			<?php if ($this->session->userdata('user_eqsl_name')){ ?>
			<td class="eqsl">
			    <span class="eqsl-<?php echo ($row->COL_EQSL_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
			    <span class="eqsl-<?php echo ($row->COL_EQSL_QSL_RCVD=='Y')?'green':'red'?>">&#9660;</span>
			</td>
			<?php } ?>

			<?php if($this->session->userdata('user_lotw_name') != "") { ?>
			<td class="lotw">
			    <span class="lotw-<?php echo ($row->COL_LOTW_QSL_SENT=='Y')?'green':'red'?>">&#9650;</span>
			    <span class="lotw-<?php echo ($row->COL_LOTW_QSL_RCVD=='Y')?'green':'red'?>">&#9660;</span>
			</td>
			<?php } ?>

			<td><a class="editbox" href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" ><img src="<?php echo base_url(); ?>/images/application_edit.png" width="16" height="16" alt="Edit" />
			</a></td>
			<?php } ?>
		</tr>
		<?php $i++; } ?>
		
	</table>
</div>
<style>
TD.qsl{
    width: 20px;
}
TD.eqsl{
    width: 33px;
}
.eqsl-green{
    color: #00A000;
    font-size: 1.1em;
}
.eqsl-red{
    color: #F00;
    font-size: 1.1em;
}
TD.lotw{
    width: 33px;
}
.lotw-green{
    color: #00A000;
    font-size: 1.1em;
}
.lotw-red{
    color: #F00;
    font-size: 1.1em;
}

</style>
