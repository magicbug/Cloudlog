
<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) {
?>
	<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
	<h4>QSO with <?php echo $row->COL_CALL; ?> on the <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></h4>
	<?php } else { ?>
	<h4>QSO with <?php echo $row->COL_CALL; ?> on the <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp);?></h4>
	<?php } ?>
	
	<div class="row">
		<div class="col">
			
			<table width="100%">
				<tr>
					<td>Date/Time</td>
					<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></td>
					<?php } else { ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
					<?php } ?>
				</tr>
				
				<tr>
					<td>Callsign</td>
					<td><?php echo $row->COL_CALL; ?></td>
				</tr>
				
				<tr>
					<td>Band</td>
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
					<td>Mode</td>
					<td><?php echo $row->COL_MODE; ?></td>
				</tr>
				
				<tr>
					<td>RST Sent</td>
					<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX_STRING) { ?>(<?php echo $row->COL_STX_STRING;?>)<?php } ?></td>
				</tr>
				
				<tr>
					<td>RST Recv</td>
					<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX_STRING) { ?>(<?php echo $row->COL_SRX_STRING;?>)<?php } ?></td>
				</tr>
				
				<?php if($row->COL_GRIDSQUARE != null) { ?>
				<tr>
					<td>Gridsquare</td>
					<td><?php echo $row->COL_GRIDSQUARE; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_VUCC_GRIDS != null) { ?>
				<tr>
					<td>Gridsquare (Multi)</td>
					<td><?php echo $row->COL_VUCC_GRIDS; ?></td>
				</tr>
				<?php } ?>
				
				
				<?php if($row->COL_NAME != null) { ?>
				<tr>
					<td>Name</td>
					<td><?php echo $row->COL_NAME; ?></td>
				</tr>
				<?php } ?>
				
				<?php if($row->COL_COMMENT != null) { ?>
				<tr>
					<td>Comment</td>
					<td><?php echo $row->COL_COMMENT; ?></td>
				</tr>
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

			</table>
			<?php if($row->COL_QSL_SENT == "Y" || $row->COL_QSL_RCVD == "Y") { ?>
				<h3>QSL Info</h3>
				
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
				<h3>LoTW</h3>
					<p>This QSO is confirmed on Lotw</p>
				<?php } ?>

		</div>
		<div class="col">
			
			<div id="map" style="width: 340px; height: 250px"></div> 

<?php
	if($row->COL_GRIDSQUARE != null) {
		$stn_loc = $this->qra->qra2latlong(trim($row->COL_GRIDSQUARE));			
		$lat = $stn_loc[0];
		$lng = $stn_loc[1];
	} else {
		$query = $this->db->query('
			SELECT *
			FROM dxcc
			WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
			ORDER BY LENGTH( prefix ) DESC
			LIMIT 1 
		');

		foreach ($query->result() as $dxcc) {
			$lat = $dxcc->lat;
			$lng = $dxcc->long;
		}
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