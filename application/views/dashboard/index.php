	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

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
	
	<script type="text/javascript">
	  function create_map() {
	    var latlng = new google.maps.LatLng(40.313043, -32.695312);
	    var myOptions = {
	      zoom: 3,
	      center: latlng,
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    };
	    var infowindow = new google.maps.InfoWindow();

	    var marker, i;

	    /* Get QSO points via json*/
		 $.getJSON("/<?php echo $this->config->item('directory'); ?>//index.php/dashboard/map", function(data) {
		 	
			$.each(data.markers, function(i, val) {
				/* Create Markers */
			    marker = new google.maps.Marker({
		        	position: new google.maps.LatLng(this.lat, this.lng),
		        	map: map
		   		});
		   		
		   		/* Store Popup Text */
		   		var content = this.html;
		   	
		   		/* Create Popups */
		   		google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        	return function() {
		        		infowindow.setContent(content);
		          		infowindow.open(map, marker);
		        	}
				})(marker, i));
			});
		 });

	    var map = new google.maps.Map(document.getElementById("map"),
	        myOptions);
	  }

	    $(document).ready(function(){
			create_map();
	  });
	</script>

<div id="container">

<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
<div class="alert-message success">
	  <p>You have had <strong><?php echo $todays_qsos; ?></strong> QSOs Today!</p>
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
					<td><?php echo $row->COL_RST_SENT; ?></td>
					<td><?php echo $row->COL_RST_RCVD; ?></td>
					<?php if($row->COL_SAT_NAME != null) { ?>
					<td>SAT</td>
					<?php } else { ?>
					<td><?php echo $row->COL_BAND; ?></td>
					<?php } ?>
				</tr>
			<?php $i++; } ?>
		</table>
  </div>

  <div class="span5">
    	<table width="100%" class="zebra-striped">
			<tr class="titles">
				<td colspan="2">QSOs</td>
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
				<td colspan="2">Countries</td>
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
				<td colspan="2">QSL Cards</td>
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
