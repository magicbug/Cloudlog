<div class="container dashboard">
<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>

	<?php if($todays_qsos >= 1) { ?>
		<div class="alert alert-success" role="alert">
			  You have had <strong><?php echo $todays_qsos; ?></strong> QSOs Today!
		</div>
	<?php } else { ?>
		<div class="alert alert-danger" role="alert">
			  <span class="badge badge-info">Important</span> <i class="fas fa-broadcast-tower"></i> You have made no QSOs today, time to turn on the radio!
		</div>
	<?php } ?>

	<?php if($current_active == 0) { ?>
		<div class="alert alert-danger" role="alert">
		  Attention you need to set an active station profile.
		</div>
	<?php } ?>

<?php } ?>
</div>

<!-- Map -->
<div id="map" style="width: 100%; height: 350px"></div>

<div style="padding-top: 0px; margin-top: 5px;" class="container dashboard">

<!-- Log Data -->
<div class="row logdata">
  <div class="col-sm-8">

  	<div class="table-responsive">
    	<table class="table table-striped table-hover">

    		<thead>
				<tr class="titles">
					<th>Date</th>

					<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
					<th>Time</th>
					<?php } ?>
					<th>Call</th>
					<th>Mode</th>
					<th class="d-none d-sm-table-cell">Sent</th>
					<th class="d-none d-sm-table-cell">Recv</th>
					<th>Band</th>
				</tr>
			</thead>

			<?php 
			$i = 0; 
			foreach ($last_five_qsos->result() as $row) { ?>
				<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($this->config->item('qso_date_format'), $timestamp); ?></td>
					<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
					<?php } else { ?>
					<?php } ?>
					<td>
						<a data-fancybox data-type="iframe" data-width="800" data-height="520" data-src="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>" href="javascript:;">
							<?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?>
						</a>
					</td>
					<td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
					<td class="d-none d-sm-table-cell"><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX_STRING) { ?><span class="label"><?php echo $row->COL_STX_STRING;?></span><?php } ?></td>
					<td  class="d-none d-sm-table-cell"><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX_STRING) { ?><span class="label"><?php echo $row->COL_SRX_STRING;?></span><?php } ?></td>
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
    	<table class="table table-striped">
			<tr class="titles">
				<td colspan="2"><i class="fas fa-chart-bar"></i> QSOs Breakdown</td>
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
		</table>



		<table class="table table-striped">
			<tr class="titles">
				<td colspan="2"><i class="fas fa-globe-europe"></i> Countries Breakdown</td>
			</tr>
			
			<tr>
				<td>Worked</td>
				<td><?php echo $total_countrys; ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="return false" data-original-title="QSL Cards / eQSL / LoTW" data-toggle="tooltip">Confirmed</a></td>
				<td>
					<?php echo $total_countrys_confirmed_paper; ?> /
					<?php echo $total_countrys_confirmed_eqsl; ?> /
					<?php echo $total_countrys_confirmed_lotw; ?>	
				</td>
			</tr>
			
			<tr>
				<td>Needed</td>
				<td><?php $dxcc = 340 - $total_countrys; echo $dxcc; ?></td>
			</tr>
		</table>

		<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
		<table class="table table-striped">	
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
		<?php } ?>
	</div>
  </div>
</div>

</div>
