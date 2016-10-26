<!-- JS -->

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

<div id="container">

<?php if($notice) { ?>
<div class="alert-message info">
        <?php echo $notice; ?>
</div>
<?php } ?>

<?php if(validation_errors()) { ?>
<div class="alert-message error">
        <?php echo validation_errors(); ?>
</div>
<?php } ?>

	<div class="row show-grid">
	  <div class="span6">
	 
	  	<h2>Manual QSO</h2>
		
		<form id="qso_input" method="post" action="<?php echo site_url('qso/manual'); ?>" name="qsos">
			<input type="hidden" id="dxcc_id" name="dxcc_id" value=""/>
			<input type="hidden" id="cqz" name="cqz" value="" />

		<table style="margin-bottom: 0px;">

			<tr>
				<td class="title">Date</td>
				<td><input class="input_date" type="text" name="start_date" value="<?php echo date('d-m-Y'); ?>" size="10" /> <input class="input_time" type="text" name="start_time" value="<?php echo date('H:i'); ?>" size="7" /></td>
			</tr>

			<tr>
				<td class="title">Callsign</td>
				<td><input size="10" id="callsign" type="text" name="callsign" value="" /></td>
			</tr>	

			<tr>
				<td class="title">Mode</td>
				<td><select name="mode" class="mode">
				<option value="SSB" <?php if($this->session->userdata('mode') == "" || $this->session->userdata('mode') == "SSB") { echo "selected=\"selected\""; } ?>>SSB</option>
				<option value="AM" <?php if($this->session->userdata('mode') == "AM") { echo "selected=\"selected\""; } ?>>AM</option>
				<option value="FM" <?php if($this->session->userdata('mode') == "FM") { echo "selected=\"selected\""; } ?>>FM</option>
				<option value="CW" <?php if($this->session->userdata('mode') == "CW") { echo "selected=\"selected\""; } ?>>CW</option>
				<option value="RTTY" <?php if($this->session->userdata('mode') == "RTTY") { echo "selected=\"selected\""; } ?>>RTTY</option>
				<option value="PSK31" <?php if($this->session->userdata('mode') == "PSK31") { echo "selected=\"selected\""; } ?>>PSK31</option>
				<option value="PSK63" <?php if($this->session->userdata('mode') == "PSK63") { echo "selected=\"selected\""; } ?>>PSK63</option>
				<option value="JT65" <?php if($this->session->userdata('mode') == "JT65") { echo "selected=\"selected\""; } ?>>JT65</option>
				<option value="JT65B" <?php if($this->session->userdata('mode') == "JT65B") { echo "selected=\"selected\""; } ?>>JT65B</option>
				<option value="JT6C" <?php if($this->session->userdata('mode') == "JT6C") { echo "selected=\"selected\""; } ?>>JT6C</option>
				<option value="JT6M" <?php if($this->session->userdata('mode') == "JT6M") { echo "selected=\"selected\""; } ?>>JT6M</option>
				<option value="JT9-1" <?php if($this->session->userdata('mode') == "JT9-1") { echo "selected=\"selected\""; } ?>>JT9-1</option>
				<option value="FSK441" <?php if($this->session->userdata('mode') == "FSK441") { echo "selected=\"selected\""; } ?>>FSK441</option>
				<option value="JTMS" <?php if($this->session->userdata('mode') == "JTMS") { echo "selected=\"selected\""; } ?>>JTMS</option>
				<option value="ISCAT" <?php if($this->session->userdata('mode') == "ISCAT") { echo "selected=\"selected\""; } ?>>ISCAT</option>
				<option value="MSK144" <?php if($this->session->userdata('mode') == "MSK144") { echo "selected=\"selected\""; } ?>>MSK144</option>
				<option value="JTMSK" <?php if($this->session->userdata('mode') == "JTMSK") { echo "selected=\"selected\""; } ?>>JTMSK</option>
				<option value="QRA64" <?php if($this->session->userdata('mode') == "QRA64") { echo "selected=\"selected\""; } ?>>QRA64</option>
				<option value="PKT" <?php if($this->session->userdata('mode') == "PKT") { echo "selected=\"selected\""; } ?>>PKT</option>
				<option value="SSTV" <?php if($this->session->userdata('mode') == "SSTV") { echo "selected=\"selected\""; } ?>>SSTV</option>
				</select>

				<span class="title">Band</span>
				<select name="band" class="band">
				<option value="160m" <?php if($this->session->userdata('band') == "160m") { echo "selected=\"selected\""; } ?>>160m</option>
				<option value="80m" <?php if($this->session->userdata('band') == "80m") { echo "selected=\"selected\""; } ?>>80m</option>
				<option value="60m" <?php if($this->session->userdata('band') == "60m") { echo "selected=\"selected\""; } ?>>60m</option>
				<option value="40m" <?php if($this->session->userdata('band') == "40m") { echo "selected=\"selected\""; } ?>>40m</option>
				<option value="30m" <?php if($this->session->userdata('band') == "30m") { echo "selected=\"selected\""; } ?>>30m</option>
				<option value="20m" <?php if($this->session->userdata('band') == "20m") { echo "selected=\"selected\""; } ?>>20m</option>
				<option value="17m" <?php if($this->session->userdata('band') == "17m") { echo "selected=\"selected\""; } ?>>17m</option>
				<option value="15m" <?php if($this->session->userdata('band') == "15m") { echo "selected=\"selected\""; } ?>>15m</option>
				<option value="12m" <?php if($this->session->userdata('band') == "12m") { echo "selected=\"selected\""; } ?>>12m</option>
				<option value="10m" <?php if($this->session->userdata('band') == "10m") { echo "selected=\"selected\""; } ?>>10m</option>
				<option value="6m" <?php if($this->session->userdata('band') == "6m") { echo "selected=\"selected\""; } ?>>6m</option>
				<option value="4m" <?php if($this->session->userdata('band') == "4m") { echo "selected=\"selected\""; } ?>>4m</option>
				<option value="2m" <?php if($this->session->userdata('band') == "2m") { echo "selected=\"selected\""; } ?>>2m</option>
				<option value="70cm" <?php if($this->session->userdata('band') == "70cm") { echo "selected=\"selected\""; } ?>>70cm</option>
				<option value="23cm" <?php if($this->session->userdata('band') == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
				<option value="13cm" <?php if($this->session->userdata('band') == "14cm") { echo "selected=\"selected\""; } ?>>13cm</option>
				<option value="9cm" <?php if($this->session->userdata('band') == "9cm") { echo "selected=\"selected\""; } ?>>9cm</option>
				<option value="3cm" <?php if($this->session->userdata('band') == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
				</select></td>
			</tr>

			<tr>
				<td class="title">RST (S)</td>
				<td><input class="rst" name="rst_sent" type="text" size="3" value="59"> <span class="title">RST (R)</span> <input class="rst" name="rst_recv" type="text"  size="3"  value="59"></td>
			</tr>

			<tr>
				<td class="title">Name</td>
				<td><input id="name" type="text" name="name" value="" /></td>
			</tr>	

			<tr>
				<td class="title">Location</td>
				<td><input id="qth" type="text" name="qth" value="" /></td>
			</tr>

			<tr>
				<td class="title">Locator</td>
				<td><input id="locator" type="text" name="locator" value="" size="7" /></td>
			</tr>

			<tr>
				<td class="title">Comment</td>
				<td><input id="comment" type="text" name="comment" value="" /></td>
			</tr>
		</table>


		<div class="info">
			<input style="border: none; -webkit-box-shadow: none;" size="20" id="country" type="text" name="country" value="" /> <span id="locator_info"></span>
		</div>

		<ul class="tabs">
		  <li class="active"><a href="#home">Home</a></li>
		  <li><a href="#station">Station</a></li>
		  <li><a href="#satellite">Satellite</a></li>
		  <li><a href="#qsl">QSL</a></li>
		</ul>
		 
		<div class="pill-content">
		  <div class="active" id="home">
				<table>
					<tr>
						<td>Propagation Mode</td>
						<td>
							<select name="prop_mode">
								<option value="" selected="selected"></option>
								<option value="AUR">Aurora</option>
								<option value="AUE">Aurora-E</option>
								<option value="BS">Back scatter</option>
								<option value="ECH">EchoLink</option>
								<option value="EME">Earth-Moon-Earth</option>
								<option value="ES">Sporadic E</option>
								<option value="FAI">Field Aligned Irregularities</option>
								<option value="F2">F2 Reflection</option>
								<option value="INTERNET">Internet-assisted</option>
								<option value="ION">Ionoscatter</option>
								<option value="IRL">IRLP</option>
								<option value="MS">Meteor scatter</option>
								<option value="RPT">Terrestrial or atmospheric repeater or transponder</option>
								<option value="RS">Rain scatter</option>
								<option value="SAT">Satellite</option>
								<option value="TEP">Trans-equatorial</option>
								<option value="TR">Tropospheric ducting</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>IOTA</td>
						<td><input id="iota_ref" type="text" name="iota_ref" value="" /> e.g: EU-005</td>
					</tr>
				</table>
		  </div>
		  <div id="station">
				<table>
					<tr>
						<td>Radio</td>
						<td>
							<select class="radios" name="radio">
							<option value="0" selected="selected">None</option>
							<?php foreach ($radios->result() as $row) { ?>
							<option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
							<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Frequency</td>
						<td><input type="text" id="frequency" name="freq_display" value="" /></td>
					</tr>
				</table>
		  </div>
		  <div id="satellite">
				<table>
					<tr>
						<td>Sat Name</td>
						<td><input id="sat_name" type="text" name="sat_name" value="<?php echo $this->session->userdata('sat_name'); ?>" /></td>
					</tr>
	
					<tr>
						<td>Sat Mode</td>
						<td><input id="sat_mode" type="text" name="sat_mode" value="<?php echo $this->session->userdata('sat_mode'); ?>" /></td>
					</tr>
				</table>
		  </div>
		  <div id="qsl">
				<table>
					<tr>
						<td>Sent</td>
						<td><select name="qsl_sent">
							<option value="N" selected="selected">No</option>
							<option value="Y">Yes</option>
							<option value="R">Requested</option>
						</select></td>
					<tr>
						<td>Method</td>
						<td><select name="qsl_sent_method">
							<option value="" selected="selected">Method</option>
							<option value="D">Direct</option>
							<option value="B">Bureau</option>
						</select></td>
					</tr>
					
					<tr>
						<td>Via</td>
						<td><input type="text" name="qsl_via" value="" /></td>
					</tr>
				</table>
		  </div>
		</div>

		<div class="actions"><input class="btn primary" type="submit" value="Add QSO" /> <input type="reset" value="Reset" class="btn" /></div>
		

		</form>
	  </div>
	  <div class="span9 offset1">

		 <div id="partial_view">
		 	<h2>Last 16 QSOs</h2>

		 	<table class="zebra-striped" width="100%">
				<tr class="log_title titles">
					<td>Date</td>
					<td>Time</td>
					<td>Call</td>
					<td>Mode</td>
					<td>Sent</td>
					<td>Recv</td>
					<td>Band</td>
				</tr>

				<?php $i = 0; 
			 foreach ($query->result() as $row) { ?>

					<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
					<td><a class="qsobox" href="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>"><?php echo strtoupper($row->COL_CALL); ?></a></td>
					<td><?php echo $row->COL_MODE; ?></td>
					<td><?php echo $row->COL_RST_SENT; ?></td>
					<td><?php echo $row->COL_RST_RCVD; ?></td>
					<?php if($row->COL_SAT_NAME != null) { ?>
					<td><?php echo $row->COL_SAT_NAME; ?></td>
					<?php } else { ?>
					<td><?php echo $row->COL_BAND; ?></td>
					<?php } ?>
				</tr>
				<?php $i++; } ?>

			</table></div>

	  </div>
	</div>

</div>



<script type="text/javascript">
	i=0;
	$(document).ready(function(){

	// Set the focus input to the callsign field
	$("#callsign").focus();

	/* Javascript for controlling rig frequency. */

	// Update frequency every second
	setInterval(function() {
		if($('select.radios option:selected').val() != '0') {
			// Get frequency
			$.get('<?php echo site_url('radio/frequency');?>/' + $('select.radios option:selected').val(), function(result) {
				$('#frequency').val(result);
				
				result = parseInt(result);
				
				if(result >= 14000000 && result <= 14400000) {
					$(".band").val('20m');
				}
				else if(result >= 18000000 && result <= 19000000) {
					$(".band").val('17m');
				}
				else if(result >= 1810000 && result <= 2000000) {
					$(".band").val('160m');
				}
				else if(result >= 3000000 && result <= 4000000) {
					$(".band").val('80m');
				}
				else if(result >= 5250000 && result <= 5450000) {
					$(".band").val('60m');
				}
				else if(result >= 7000000 && result <= 7500000) {
					$(".band").val('40m');
				}
				else if(result >= 10000000 && result <= 11000000) {
					$(".band").val('30m');
				}
				else if(result >= 21000000 && result <= 21600000) {
					$(".band").val('15m');
				}
				else if(result >= 24000000 && result <= 25000000) {
					$(".band").val('12m');
				}
				else if(result >= 28000000 && result <= 30000000) {
					$(".band").val('10m');
				}
				else if(result >= 50000000 && result <= 56000000) {
					$(".band").val('6m');
				}
				else if(result >= 144000000 && result <= 148000000) {
					$(".band").val('2m');
				}
				else if(result >= 430000000 && result <= 440000000) {
					$(".band").val('70cm');
				}
			});
			
			// Get Mode
			$.get('<?php echo site_url('radio/mode');?>/' + $('select.radios option:selected').val(), function(result) {
				if (result == "LSB" || result == "USB" || result == "SSB") {
					$(".mode").val('SSB');
				} else {
					$(".mode").val(result);	
				}
			});
		}			
	}, 1000);


	// If a radios selected from drop down select radio update.
	$('.radios').change(function() {
		if($('select.radios option:selected').val() != '0') {
		// Get frequency
			$.get('<?php echo site_url('radio/frequency');?>/' + $('select.radios option:selected').val(), function(result) {
				$('#frequency').val(result);
				result = parseInt(result);
				
				if(result >= 14000000 && result <= 14400000) {
					$(".band").val('20m');
				}
				else if(result >= 18000000 && result <= 19000000) {
					$(".band").val('17m');
				}
				else if(result >= 1810000 && result <= 2000000) {
					$(".band").val('160m');
				}
				else if(result >= 3000000 && result <= 4000000) {
					$(".band").val('80m');
				}
				else if(result >= 5250000 && result <= 5450000) {
					$(".band").val('60m');
				}
				else if(result >= 7000000 && result <= 7500000) {
					$(".band").val('40m');
				}
				else if(result >= 10000000 && result <= 11000000) {
					$(".band").val('30m');
				}
				else if(result >= 21000000 && result <= 21600000) {
					$(".band").val('15m');
				}
				else if(result >= 24000000 && result <= 25000000) {
					$(".band").val('12m');
				}
				else if(result >= 28000000 && result <= 30000000) {
					$(".band").val('10m');
				}
				else if(result >= 50000000 && result <= 56000000) {
					$(".band").val('6m');
				}
				else if(result >= 144000000 && result <= 148000000) {
					$(".band").val('2m');
				}
				else if(result >= 430000000 && result <= 440000000) {
					$(".band").val('70cm');
				}
			});	
			
			// Get Mode
			$.get('<?php echo site_url('radio/mode');?>/' + $('select.radios option:selected').val(), function(result) {
				if (result == "LSB" || result == "USB" || result == "SSB") {
					$(".mode").val('SSB');
				} else {
					$(".mode").val(result);	
				}
			});	
	
		}
	});

		/* On Page Load */
		var catcher = function() {
		  var changed = false;
		  $('form').each(function() {
		    if ($(this).data('initialForm') != $(this).serialize()) {
		      changed = true;
		      $(this).addClass('changed');
		    } else {
		      $(this).removeClass('changed');
		    }
		  });
		  if (changed) {
		    return 'Unsaved QSO!';
		  }
		};

		$(function() {
		  $('form').each(function() {
		    $(this).data('initialForm', $(this).serialize());
		  }).submit(function(e) {
		    var formEl = this;
		    var changed = false;
		    $('form').each(function() {
		      if (this != formEl && $(this).data('initialForm') != $(this).serialize()) {
		        changed = true;
		        $(this).addClass('changed');
		      } else {
		        $(this).removeClass('changed');
		      }
		    });
		    if (changed && !confirm('You have an unsaved QSO. Continue with QSO?')) {
		      e.preventDefault();
		    } else {
		      $(window).unbind('beforeunload', catcher);
		    }
		  });
		  $(window).bind('beforeunload', catcher);
		});
		
		$.get('<?php echo site_url('qso/band_to_freq'); ?>/' + $('.band').val() + '/' + $('.mode').val(), function(result) {
						$('#frequency').val(result);
		});	
	
		/* Calculate Frequency */
			/* on band change */
			$('.band').change(function() {
				$.get('<?php echo site_url('qso/band_to_freq'); ?>/' + $(this).val() + '/' + $('.mode').val(), function(result) {
						$('#frequency').val(result);
					});	
			});
			
			/* on mode change */
			$('.mode').change(function() {
				$.get('<?php echo site_url('qso/band_to_freq'); ?>/' + $('.band').val() + '/' + $('.mode').val(), function(result) {
						$('#frequency').val(result);
					});	
			});
	
		/* On Key up Calculate Bearing and Distance */
		$("#locator").keyup(function(){
			if ($(this).val()) {
				$('#locator_info').load("<?php echo site_url('logbook/bearing'); ?>/" + $(this).val()).fadeIn("slow");
			}
		});
	
		/* On Callsign Change */
		$("#callsign").focusout(function(){
			if ($(this).val()) {
				/* Find Callsign Matches */
				$('#partial_view').load("<?php echo site_url('logbook/partial'); ?>/" + $(this).val()).fadeIn("slow");
	
				/* Find and populate DXCC */
				$.get('<?php echo site_url('logbook/find_dxcc'); ?>/' + $(this).val(), function(result) {
					//$('#country').val(result);
					obj = JSON.parse(result);
					$('#country').val(convert_case(obj.Name));
					$('#dxcc_id').val(obj.DXCC);
					$('#cqz').val(obj.CQZ);
				});
	
				/* Find Locator if the field is empty */
				if($('#locator').val() == "") {
					$.get('<?php echo site_url('logbook/callsign_qra'); ?>/' + $(this).val(), function(result) {
						$('#locator').val(result);
						$('#locator_info').load("<?php echo site_url('logbook/bearing'); ?>/" + result).fadeIn("slow");
					});

				}
	
				/* Find Operators Name */
				if($('#name').val() == "") {
					$.get('<?php echo site_url('logbook/callsign_name'); ?>/' + $(this).val(), function(result) {
						$('#name').val(result);
					});
				}

				if($('#qth').val() == "") {
					$.get('<?php echo site_url('logbook/callsign_qth'); ?>/' + $(this).val(), function(result) {
						$('#qth').val(result);
					});
				}

			}
		});
	});

	function convert_case(str) {
	  var lower = str.toLowerCase();
	  return lower.replace(/(^| )(\w)/g, function(x) {
	    return x.toUpperCase();
	  });
	}
</script>