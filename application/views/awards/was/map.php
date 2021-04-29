<!DOCTYPE html>
<html>
<head>
	<title>US Map Demo</title>
	
	<style>
	  #alert {
	    font-family: Arial, Helvetica, sans-serif;
	    font-size: 16px;
	    background-color: #ddd;
	    color: #333;
	    padding: 5px;
	    font-weight: bold;
	  }
	</style>
	
	<script src="<?php echo base_url(); ?>assets/js/raphael.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/color.jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.usmap.js"></script>
	
	<script>
	$(document).ready(function() {
	  $('#map').usmap({
        'stateStyles': {
	      fill: '#4ECDC4', 
	      "stroke-width": 1,
	      'stroke' : '#036'
	    },
	    'stateHoverStyles': {
	      fill: 'red'
	    },
	    'stateSpecificStyles': {
            <?php foreach ($was_array as $was => $value) { ?>
    '<?php echo $was; ?>' :
    <?php    
        foreach ($value  as $key) {
                if($key != "") {
                    if (strpos($key, '>W<') !== false) {
                        echo "{fill: 'orange'},";
                        break;
                    }
                    if (strpos($key, '>C<') !== false) {
                        echo "{fill: '#32a852'},";
                        break;
                    }
                    if (strpos($key, '-') !== false) {
                        echo "{fill: '#e34949'},";
                        break;
                    }
                }
            }
        }
?>
	    },
	    'stateSpecificHoverStyles': {
	      'HI' : {fill: '#ff0'}
	    },
	    
	    'mouseoverState': {
	      'HI' : function(event, data) {
	        //return false;
	      }
	    },
	    
	    
	    'click' : function(event, data) {
	      $('#alert')
	        .text('Click '+data.name+' on map 1')
	        .stop()
	        .css('backgroundColor', '#ff0')
	        .animate({backgroundColor: '#ddd'}, 1000);
	    }
	  });
	  
	});
	</script>
</head>
<body>
  <div id="map" style="width: 930px; height: 630px;"></div>

  <ul>
    <li>Red - Not Worked</li>
    <li>Orange - Worked but not confirmed</li>
    <li>Green - Confirmed</li>
  </ul>
</body>
</html>
