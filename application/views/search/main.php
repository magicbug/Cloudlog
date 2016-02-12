<div id="container">

<h2>Search</h2>

<form method="post" action="" id="search_box" name="test">
	Callsign: <input type="text" id="callsign" name="callsign" value="" />
</form>

<div id="partial_view"></div>

</div>


<script type="text/javascript">
i=0;
$(document).ready(function(){

	$('#partial_view').load("logbook/search_result/<?php echo $this->input->post('callsign'); ?>", function() {
	      // after load trigger your fancybox 
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
				'width'         	: 450,
				'height'        	: 550,
				'transitionIn'		: 'fade',
				'transitionOut'		: 'fade',
				'type'				: 'iframe',
			});
	});

  $("#callsign").keyup(function(){
	if ($(this).val()) {

		$('#partial_view').load("logbook/search_result/" + $(this).val(), function() {
				$(".qsobox").fancybox({
					'autoDimensions'	: false,
					'width'         	: 700,
					'height'        	: 300,
					'transitionIn'		: 'fade',
					'transitionOut'		: 'fade',
					'type'				: 'iframe'
				});
	 
		      // after load trigger your fancybox 
		      	$(".editbox").fancybox({
					'autoDimensions'	: false,
					'width'         	: 450,
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
	
