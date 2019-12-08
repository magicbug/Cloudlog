    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

<?php if ($this->uri->segment(1) == "register") { ?>
<script type="text/javascript">
	$("#Callsign").keyup(function() {
		// Call API to lookup dxcc information
	    $.getJSON( "<?php echo site_url('lookup/dxcc/');?>" + $("#Callsign").val() +"/<?php echo date("Y-m-d");?>", function( data ) {
	    	// Process the json fields
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
					//Unknown Callsign clear the fields
					$("#country").val("");
					$("#cq_zone").val("");
				}
		  	});
		});
	});

		$("#username").keyup(function() {
		// Call API to lookup dxcc information
	    $.getJSON( "<?php echo site_url('user/username_check/'); ?>" + $("#username").val(), function( data ) {
	    	console.log(data);
			$.each( data, function( key, val ) {
				if(key == "Status") {
					// Select DXCC from callsign ADIF no
					if(val == "Available") {
						console.log('username available');
						$("#username").css("border", "2px solid #4BB543");
					} else {
						$("#username").css("border", "2px solid #FF9494");
					}
				}
		  	});
		});
	});
</script>
<?php } ?>
	</body>
</html>