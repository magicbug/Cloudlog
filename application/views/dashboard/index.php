	<script type="text/javascript" src="<?php echo base_url() ;?>fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ;?>fancybox/jquery.fancybox-1.3.4.pack.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script type="text/javascript">

		$(document).ready(function() {
			$(".qsobox").fancybox({
				'autoDimensions'	: false,
				'width'         	: 700,
				'height'        	: 300,
				'transitionIn'		: 'fade',
				'transitionOut'		: 'fade',
				'type'				: 'iframe'
			});
		});

	</script>
	
	<script type="text/javascript" src="<?php echo base_url();?>js/leaflet/leafembed.js"></script>
	<script type="text/javascript">
	  
	  	<?php if($qra == "set") { ?>
		var q_lat = <?php echo $qra_lat; ?>;
		var q_lng = <?php echo $qra_lng; ?>;	
		<?php } else { ?>
		var q_lat = 40.313043;
		var q_lng = -32.695312;
		<?php } ?>

		var qso_loc = '<?php echo site_url('dashboard/map');?>';
		var q_zoom = 2;

	  $(document).ready(function(){
			initmap();
	  });
	</script>

<div id="container">

<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
<div class="alert-message success">
	  <p>You have had <strong><?php echo $todays_qsos; ?></strong> QSO<?php echo ( $todays_qsos != 1  ? "s" : "" ); ?> today!</p>
</div>
<?php } ?>

<!-- Map -->
<div id="map" style="width: 100%; height: 300px"></div>

<!-- Log Data -->
<div class="row" style="margin-top: 10px;">
  <div class="span10" style="padding-left: 15px; padding-right: 25px; border-right: 1px solid #dfdfdf;">
    	<table width="100%" class="zebra-striped">
			<tr class="titles">
				<td>Date</td>
				<td>Time</td>
				<td>Call</td>
				<td>Mode</td>
				<td>Sent</td>
				<td>Recv</td>
				<td>Band</td>
			</tr>

			<?php $i = 0; 
			foreach ($last_five_qsos->result() as $row) { ?>
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
					<td><?php echo strtolower($row->COL_BAND); ?></td>
					<?php } ?>
				</tr>
			<?php $i++; } ?>
		</table>
  </div>

  <div class="span5">
    	<table width="100%" class="zebra-striped">
			<tr class="titles">
				<td colspan="2"><span class="icon_stats">QSOs</span></td>
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
				<td colspan="2"><span class="icon_world">Countries</span></td>
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
				<td colspan="2"><span class="icon_qsl">QSL Cards</span></td>
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
