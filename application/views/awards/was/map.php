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
	<script src="<?php echo base_url(); ?>assets/js/jquery.usmap.min.js"></script>

	<script>
	$(document).ready(function() {
	  $('#map').usmap({
        showLabels: true,
        'stateStyles': {
	      fill: '#4ECDC4',
	      "stroke-width": 1,
	      'stroke' : '#036'
	    },
	    'stateHoverStyles': {
	      fill: 'blue'
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
                        echo "{fill: '#DB4325'},";
                        break;
                    }
                }
            }
        }
?>
	    },
		  // Show tooltip when hovering over state
		  mouseover: function(event, data) {
			  let nameList = {
				  "AL":"Alabama",
				  "AK":"Alaska",
				  "AZ":"Arizona",
				  "AR":"Arkansas",
				  "CA":"California",
				  "CO":"Colorado",
				  "CT":"Connecticut",
				  "DE":"Delaware",
				  "DC":"District Of Columbia",
				  "FL":"Florida",
				  "GA":"Georgia",
				  "HI":"Hawaii",
				  "ID":"Idaho",
				  "IL":"Illinois",
				  "IN":"Indiana",
				  "IA":"Iowa",
				  "KS":"Kansas",
				  "KY":"Kentucky",
				  "LA":"Louisiana",
				  "ME":"Maine",
				  "MD":"Maryland",
				  "MA":"Massachusetts",
				  "MI":"Michigan",
				  "MN":"Minnesota",
				  "MS":"Mississippi",
				  "MO":"Missouri",
				  "MT":"Montana",
				  "NE":"Nebraska",
				  "NV":"Nevada",
				  "NH":"New Hampshire",
				  "NJ":"New Jersey",
				  "NM":"New Mexico",
				  "NY":"New York",
				  "NC":"North Carolina",
				  "ND":"North Dakota",
				  "OH":"Ohio",
				  "OK":"Oklahoma",
				  "OR":"Oregon",
				  "PA":"Pennsylvania",
				  "RI":"Rhode Island",
				  "SC":"South Carolina",
				  "SD":"South Dakota",
				  "TN":"Tennessee",
				  "TX":"Texas",
				  "UT":"Utah",
				  "VT":"Vermont",
				  "VA":"Virginia",
				  "WA":"Washington",
				  "WV":"West Virginia",
				  "WI":"Wisconsin",
				  "WY":"Wyoming"
			  }

			  $('#tooltip').text(nameList[data.name]).show();
			  $('#map').mousemove(function(e){
				  var mouseX = e.pageX - 350;
				  var mouseY = e.pageY - 90;
				  $('#tooltip').css({
					  top:mouseY,
					  left:mouseX,
					  'position': 'absolute',
					  'border':'1px solid black',
					  'background': '#fff',
					  'color': '#000',
					  'font-size': '1.5 em',
					  'padding': '5px',
					  'opacity': '1',
					  'border-radius': '2px'
				  });
			  });
		  },
		  // Hide tooltip when not hovering over state
		  mouseout: function(event, data){
			  $('#tooltip').hide();
		  },


	    'click' : function(event, data) {
        	displayContacts(data.name,$('#band2').val(), $('#mode').val(), 'WAS');
	    }
	  });

	});
	</script>
</head>
<body>
  <div id="map" style="width: 930px; height: 630px;"></div>
  <div id="tooltip2"></div>

  <ul>
    <li>Red - Not Worked</li>
    <li>Orange - Worked but not confirmed</li>
    <li>Green - Confirmed</li>
  </ul>
</body>
</html>
