<h2>Search</h2>
<div class="wrap_content">
<form method="post" action="" id="search_box" name="test">
	Callsign: <input type="text" id="callsign" name="callsign" value="" />
</form>

<div id="partial_view"></div>

</div>


<script type="text/javascript">
i=0;
$(document).ready(function(){
  $("#callsign").keyup(function(){
	if ($(this).val()) {

	$('#partial_view').load("logbook/search_result/" + $(this).val(), function() {
	      // after load trigger your fancybox 
	      	$(".editbox").fancybox({
				'autoDimensions'	: false,
				'width'         	: 700,
				'height'        	: 550,
				'transitionIn'		: 'fade',
				'transitionOut'		: 'fade',
				'type'				: 'iframe',
			});
	});

	}

  });
});
</script>

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
				'width'         	: 700,
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