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

			$(".editbox").fancybox({
				'autoDimensions'	: false,
				'width'         	: 600,
				'height'        	: 550,
				'transitionIn'		: 'fade',
				'transitionOut'		: 'fade',
				'type'				: 'iframe',
				onCleanup   : function() {
                return window.location.reload();
            	}
			});

		});
	</script>
	
	<script type="text/javascript">
	  function create_map() {
	    <?php if($qra == "set") { ?>
		var latlng = new google.maps.LatLng(<?php echo $qra_lat; ?>, <?php echo $qra_lng; ?>);	
		<?php } else { ?>
		var latlng = new google.maps.LatLng(40.313043, -32.695312);
		<?php } ?>
	    var myOptions = {
	      zoom: 3,
	      center: latlng,
	      mapTypeId: google.maps.MapTypeId.ROADMAP
	    };
	    var infowindow = new google.maps.InfoWindow();

	    var marker, i;

	    /* Get QSO points via json*/
		 $.getJSON("<?php echo site_url('logbook/qso_map/25/'.$this->uri->segment(3)); ?>", function(data) {
		 	
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

	<h2>Logbook</h2>
	
	<!-- Map -->
	<div id="map" style="width: 100%; height: 300px"></div> 

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
			<td><?php echo strtolower($row->COL_BAND); ?></td>
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
			<?php if($this->config->item('callsign_tags') == true) { ?>
				<?php if($row->COL_STATION_CALLSIGN	 != null) { ?>
				<td><span class="label notice"><?php echo $row->COL_STATION_CALLSIGN; ?></span></td>
				<?php } elseif($row->COL_OPERATOR != null) { ?>
				<td><span class="label notice"><?php echo $row->COL_OPERATOR; ?></span></td>
				<?php } ?>
			<?php } ?>
			<?php } ?>
		</tr>
		<?php $i++; } ?>
		
	</table>

	<div class="pagination">
		<?php echo $this->pagination->create_links(); ?>
	</div>

</div>
