    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	
<script type="text/javascript">
	$("#Callsign").keyup(function() {
	    console.log('callsign changed');
	    $.getJSON( "http://cloudlog.mg/index.php/lookup/dxcc/" + $("#Callsign").val() +"/<?php echo date("Y-m-d");?>", function( data ) {
		var items = [];
			$.each( data, function( key, val ) {
				if(key != "Error") {
					// Select DXCC from callsign ADIF no
					if(key == "adif") {
						$('[name=dxcc]').val( val );
					}

					// Set country Name
					if(key == "entity") {
						$("#country").val(val);
					}

					// Populate CQ Zone Field
					if(key == "cqz") {
						$("#cq_zone").val(val);
						console.log('Change CQ zone ' + val);
					}
				} else {
					console.log("Unknown Callsign");
					$("#country").val("");
					$("#cq_zone").val("");
				}
		  	});
		 
		  $( "<ul/>", {
		    "class": "my-new-list",
		    html: items.join( "" )
		  }).appendTo( "body" );
		});
  });
</script>

	</body>
</html>