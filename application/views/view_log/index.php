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
	
	<script type="text/javascript" src="<?php echo base_url();?>js/leaflet/leafembed.js"></script>
	<script type="text/javascript">
	  
	  	<?php if($qra == "set") { ?>
		var q_lat = <?php echo $qra_lat; ?>;
		var q_lng = <?php echo $qra_lng; ?>;	
		<?php } else { ?>
		var q_lat = 40.313043;
		var q_lng = -32.695312;
		<?php } ?>

		var qso_loc = '<?php echo site_url('logbook/qso_map/25/'.$this->uri->segment(3)); ?>';
		var q_zoom = 2;

	  $(document).ready(function(){
			initmap();
	  });
	</script>

<div id="container">

	<h2>Logbook</h2>
	
	<!-- Map -->
	<div id="map" style="width: 100%; height: 300px"></div> 

	<?php $this->load->view('view_log/partial/log') ?>
