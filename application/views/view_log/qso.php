<div class="container-fluid">
	<div class="row">
		<div class="col">
			<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) { ?>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
			<h1 style="font-size: 28px;">QSO with <?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?> on the <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></h1>
			<?php } else { ?>
			<h1 style="font-size: 28px;">QSO with <?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?> on the <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp);?></h1>
			<?php } ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col">
			
			<table width="100%">
				<tr>
					<td>Date/Time:</td>
					<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($this->config->item('qso_date_format'), $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></td>
					<?php } else { ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($this->config->item('qso_date_format'), $timestamp); ?></td>
					<?php } ?>
				</tr>
				
				<tr>
					<td>Callsign:</td>
					<td><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></td>
				</tr>
				
				<tr>
					<td>Band:</td>
					<td><?php echo $row->COL_BAND; ?></td>
				</tr>
				
				<?php if($this->config->item('display_freq') == true) { ?>
				<tr>
					<td>Freq:</td>
					<td><?php echo frequency_display_string($row->COL_FREQ); ?></td>
				</tr>
				<?php if($row->COL_FREQ_RX != 0) { ?>
				<tr>
					<td>Freq (RX):</td>
					<td><?php echo frequency_display_string($row->COL_FREQ_RX); ?></td>
				</tr>
				<?php }} ?>
				
				<tr>
					<td>Mode:</td>
					<td><?php echo $row->COL_MODE; ?></td>
				</tr>
				
				<tr>
					<td>RST Sent:</td>
					<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX) { ?>(<?php echo $row->COL_STX;?>)<?php } ?> <?php if ($row->COL_STX_STRING) { ?>(<?php echo $row->COL_STX_STRING;?>)<?php } ?></td>
				</tr>
				
				<tr>
					<td>RST Recv:</td>
					<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX) { ?>(<?php echo $row->COL_SRX;?>)<?php } ?> <?php if ($row->COL_SRX_STRING) { ?>(<?php echo $row->COL_SRX_STRING;?>)<?php } ?></td>
				</tr>
				
				<?php if($row->COL_GRIDSQUARE != null) { ?>
				<tr>
					<td>Gridsquare:</td>
					<td><?php echo $row->COL_GRIDSQUARE; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_VUCC_GRIDS != null) { ?>
				<tr>
					<td>Gridsquare (Multi):</td>
					<td><?php echo $row->COL_VUCC_GRIDS; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_STATE != null) { ?>
				<tr>
					<td>USA State:</td>
					<td><?php echo $row->COL_STATE; ?></td>
				</tr>
				<?php } ?>
				
				
				<?php if($row->COL_NAME != null) { ?>
				<tr>
					<td>Name:</td>
					<td><?php echo $row->COL_NAME; ?></td>
				</tr>
				<?php } ?>
				
				<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
				<?php if($row->COL_COMMENT != null) { ?>
				<tr>
					<td>Comment:</td>
					<td><?php echo $row->COL_COMMENT; ?></td>
				</tr>
				<?php } ?>
				<?php } ?>
				
				<?php if($row->COL_SAT_NAME != null) { ?>
				<tr>
					<td>Sat Name:</td>
					<td><?php echo $row->COL_SAT_NAME; ?></td>
				</tr>
				<?php } ?>
				
				<?php if($row->COL_SAT_MODE != null) { ?>
				<tr>
					<td>Sat Mode:</td>
					<td><?php echo $row->COL_SAT_MODE; ?></td>
				</tr>
				<?php } ?>
				<?php if($row->COL_COUNTRY != null) { ?>
				<tr>
					<td>Country:</td>
					<td><?php echo $row->COL_COUNTRY; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_IOTA != null) { ?>
				<tr>
					<td>IOTA Ref:</td>
					<td><?php echo $row->COL_IOTA; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_SOTA_REF != null) { ?>
				<tr>
					<td>SOTA Ref:</td>
					<td><?php echo $row->COL_SOTA_REF; ?></td>
				</tr>
				<?php } ?>
				
				<?php if($row->COL_DARC_DOK != null) { ?>
				<tr>
					<td>DOK:</td>
					<td><a href="https://www.darc.de/<?php echo $row->COL_DARC_DOK; ?>" target="_new"><?php echo $row->COL_DARC_DOK; ?></a></td>
				</tr>
				<?php } ?>

			</table>
			<?php if($row->COL_QSL_SENT == "Y" || $row->COL_QSL_RCVD == "Y") { ?>
				<h3>QSL Info:</h3>
				
				<?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "B") { ?>
				<p>QSL Card has been sent via the bureau</p>
				<?php } ?>
				<?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "D") { ?>
				<p>QSL Card has been sent direct</p>
				<?php } ?>
				
				<?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "B") { ?>
				<p>QSL Card has been received via the bureau</p>
				<?php } ?>
				<?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "D") { ?>
				<p>QSL Card has been received direct</p>
				<?php } ?>
			<?php } ?>
				
				<?php if($row->COL_LOTW_QSL_RCVD == "Y") { ?>
				<h3>LoTW:</h3>
					<p>This QSO is confirmed on Lotw</p>
				<?php } ?>

			<h2 style="font-size: 22px;">Station Information</h2>

			<table width="100%">
				<tr>
					<td>Station Callsign</td>
					<td><?php echo $row->station_callsign; ?></td>
				</tr>
				<tr>
					<td>Station Gridsquare</td>
					<td><?php echo $row->station_gridsquare; ?></td>
				</tr>

				<?php if($row->station_city) { ?>
				<tr>
					<td>Station City:</td>
					<td><?php echo $row->station_city; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->station_country) { ?>
				<tr>
					<td>Station Country:</td>
					<td><?php echo $row->station_country; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_OPERATOR) { ?>
				<tr>
					<td>Station Operator</td>
					<td><?php echo $row->COL_OPERATOR; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_TX_PWR) { ?>
				<tr>
					<td>Station Transmit Power</td>
					<td><?php echo $row->COL_TX_PWR; ?>w</td>
				</tr>
				<?php } ?>

				<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
				<tr>
					<td><a href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" href="javascript:;"><i class="fas fa-edit"></i> Edit QSO</a></td>
				</tr>
				<?php } ?>
			</table>
		</div>
		<div class="col">
			

		<div id="map" style="width: 340px; height: 250px"></div> 
		</div>
	</div>
</div>

<?php
	if($row->COL_GRIDSQUARE != null) {
		$stn_loc = $this->qra->qra2latlong(trim($row->COL_GRIDSQUARE));			
		$lat = $stn_loc[0];
		$lng = $stn_loc[1];
	} else {

		$CI =& get_instance();
		$CI->load->model('Logbook_model');

		$result = $CI->Logbook_model->dxcc_lookup($row->COL_CALL, $row->COL_TIME_ON);
			$lat = $result['lat'];
			$lng = $result['long'];
	}
?>



<script>
var lat = <?php echo $lat; ?>;
var long = <?php echo $lng; ?>;
var callsign = "<?php echo $row->COL_CALL; ?>";
</script>

<?php } } ?>
<?php 
  // converts a frequency in Hz (e.g. 3650) to 3.650 MHz 
  function frequency_display_string($frequency)
  {
    return number_format (($frequency / 1000 / 1000), 3) . " MHz";
  }
?>