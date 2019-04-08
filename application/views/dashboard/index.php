<div class="container dashboard">
<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>

	<?php if($todays_qsos >= 1) { ?>
		<div class="alert alert-success" role="alert">
			  You have had <strong><?php echo $todays_qsos; ?></strong> QSOs Today!
		</div>
	<?php } else { ?>
		<div class="alert alert-danger" role="alert">
			  You have made no QSOs today, time to turn on the radio!
		</div>
	<?php } ?>
<?php } ?>

<!-- Map -->
<div id="map" style="width: 100%; height: 300px"></div>

<!-- Log Data -->
<div class="row logdata">
  <div class="col-sm-8">

  	<div class="table-responsive">
    	<table class="table table-striped table-hover">

    		<thead>
				<tr class="titles">
					<th>Date</th>
					<th>Time</th>
					<th>Call</th>
					<th>Mode</th>
					<th>Sent</th>
					<th>Recv</th>
					<th>Band</th>
				</tr>
			</thead>

			<?php $i = 0; 
			foreach ($last_five_qsos->result() as $row) { ?>
				<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
					<td>
						<a data-fancybox data-type="iframe" data-src="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>" href="javascript:;">
							<?php echo strtoupper($row->COL_CALL); ?>
						</a>
					</td>
					<td><?php echo $row->COL_MODE; ?></td>
					<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX_STRING) { ?><span class="label"><?php echo $row->COL_STX_STRING;?></span><?php } ?></td>
					<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX_STRING) { ?><span class="label"><?php echo $row->COL_SRX_STRING;?></span><?php } ?></td>
					<?php if($row->COL_SAT_NAME != null) { ?>
					<td><?php echo $row->COL_SAT_NAME; ?></td>
					<?php } else { ?>
					<td><?php echo strtolower($row->COL_BAND); ?></td>
					<?php } ?>
				</tr>
			<?php $i++; } ?>
		</table>
	</div>
  </div>

  <div class="col-sm-4">
  	<div class="table-responsive">
    	<table class="table table-striped table-hover">
			<tr class="titles">
				<td colspan="2"><i class="fas fa-chart-bar"></i> QSOs</td>
			</tr>
			
			<tr>
				<td>Total </td>
				<td><?php echo $total_qsos; ?></td>
			</tr>
			
			<tr>
				<td>Year</td>
				<td><?php echo $year_qsos; ?></td>
			</tr>

			<tr>
				<td>Month</td>
				<td><?php echo $month_qsos; ?></td>
			</tr>

			<tr>
				<td></td>
				<td></td>
			</tr>
			
			<tr class="titles">
				<td colspan="2"><i class="fas fa-globe-europe"></i> Countries</td>
			</tr>
			
			<tr>
				<td>Worked</td>
				<td><?php echo $total_countrys; ?></td>
			</tr>
			
			<tr>
				<td>Needed</td>
				<td><?php $dxcc = 340 - $total_countrys; echo $dxcc; ?></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>
			</tr>
					
			<tr class="titles">
				<td colspan="2"><i class="fas fa-envelope"></i> QSL Cards</td>
			</tr>
			
			<tr>
				<td>Sent</td>
				<td><?php echo $total_qsl_sent; ?></td>
			</tr>
					
			<tr>
				<td>Received</td>
				<td><?php echo $total_qsl_recv; ?></td>
			</tr>
			
			<tr>
				<td>Requested</td>
				<td><?php echo $total_qsl_requested; ?></td>
			</tr>
		</table>
	</div>
  </div>
</div>

</div>
