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

	<?php $this->load->view('view_log/partial/log') ?>
