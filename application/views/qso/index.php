<h2>Add QSO</h2>

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

<?php if($notice) { ?>
<div id="message" >
	<?php echo $notice; ?>
</div>
<?php } ?>

<div class="wrap_content">
<script type="text/javascript">
setInterval("settime()", 1000);

function settime () {
  var curtime = new Date();
  var curhour = curtime.getHours();
  var curmin = curtime.getMinutes();
  var cursec = curtime.getSeconds();
  var time = "";

  if(curhour == 0) curhour = 24;
  time = (curhour > 24 ? curhour - 24 : curhour) + ":" +
		 (curmin < 10 ? "0" : "") + curmin + ":" +
		 (cursec < 10 ? "0" : "") + cursec

  document.qsos.start_time.value = time;
}
</script>

<table class="logbook" width="100%">
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

</table>


<?php echo validation_errors(); ?>

<form id="qso_input" method="post" action="<?php echo site_url('qso'); ?>" name="qsos">

<table width="100%">

	<tr class="log_title">
		<td class="title">Date</td>
		<td class="title">Time</td>
		<td class="title">Callsign</td>
		<?php if($this->config->item('display_freq') == true) { ?>
		<td class="title">Freq</td>
		<?php } ?>
		<td class="title">Mode</td>
		<td class="title">Band</td>
		<td class="title">RST(S)</td>
		<td class="title">RST(R)</td>
		<td class="title">QRA</td>
<!-- 		<td class="title">Name</td> -->
		<td class="title">Name</td>
	</tr>
	
	<tr>
		<td><input class="input_date" type="text" name="start_date" value="<?php echo date('d-m-Y'); ?>" size="10" /></td>
		<td><input class="input_time" type="text" name="start_time" value="" size="7" /></td>
		<td><input size="10" id="callsign" type="text" name="callsign" value="" /></td>
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
			<option value="FSK441" <?php if($this->session->userdata('mode') == "FSK441") { echo "selected=\"selected\""; } ?>>FSK441</option>
			<option value="JTMS" <?php if($this->session->userdata('mode') == "JTMS") { echo "selected=\"selected\""; } ?>>JTMS</option>
			<option value="ISCAT" <?php if($this->session->userdata('mode') == "ISCAT") { echo "selected=\"selected\""; } ?>>ISCAT</option>
			<option value="PKT" <?php if($this->session->userdata('mode') == "PKT") { echo "selected=\"selected\""; } ?>>PKT</option>
		</select></td> 
		
		<td><select name="band" class="band">
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
			<option value="33cm" <?php if($this->session->userdata('band') == "33cm") { echo "selected=\"selected\""; } ?>>33cm</option>
			<option value="23cm" <?php if($this->session->userdata('band') == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
		</select></td>
		<td><input class="rst" name="rst_sent" type="text" value="59"></td>
		<td><input class="rst" name="rst_recv" type="text" value="59"></td>
		<td><input id="locator" type="text" name="locator" value="" size="7" /></td>
		<td><input id="name" type="text" name="name" value="" /></td>
	</tr>
	
	
	<tr>
		<td colspan="9">QTH: <input id="qth" type"text" name"qth" value"" /> Comment: <input id="comment" type="text" name="comment" value="" /></td>
	</tr>
</table>

<div class="info">
	<input size="20" id="country" type="text" name="country" value="" /> <span id="locator_info"></span>
</div>


		<div id="tabs">

			<ul>
				<li><a href="#tabs-1">Logbook</a></li>
				<li><a href="#tabs-2">Satellite</a></li>
				<li><a href="#tabs-3">Station</a></li>
				<li><a href="#tabs-4">QSL</a></li>
				<li><a href="#tabs-5">Awards</a></li>
			</ul>

			<div id="tabs-1"><div id="partial_view">Partial Callsign Check</div></div>

			<div id="tabs-2">
				<table>
					<tr>
						<td>Sat Name</td>
						<td><input type="text" name="sat_name" value="<?php echo $this->session->userdata('sat_name'); ?>" /></td>
					</tr>
	
					<tr>
						<td>Sat Mode</td>
						<td><input type="text" name="sat_mode" value="<?php echo $this->session->userdata('sat_mode'); ?>" /></td>
					</tr>
				</table>
			</div>
			
			<div id="tabs-3">
				<table>
					<tr>
						<td>Radio</td>
						<td><input type="text" name="equipment" value="" /></td>
					</tr>
					<tr>
						<td>Frequnecy</td>
						<td><input type="text" id="frequency" name="freq_display" value="" /></td>
					</tr>
				</table>
				
			</div>

			<div id="tabs-4">
				<table>
					<tr>
						<td>Sent</td>
						<td><select name="qsl_sent">
							<option value="N" selected="selected">No</option>
							<option value="Y">Yes</option>
							<option value="R">Requested</option>
						</select></td>
						<td>Recv</td>
						<td><select name="qsl_recv">
							<option value="N" selected="selected">No</option>
							<option value="Y">Yes</option>
							<option value="R">Requested</option>
						</select></td>
					</tr>
					<tr>
						<td></td>
						<td><select name="qsl_sent_method">
							<option value="" selected="selected">Method</option>
							<option value="D">Direct</option>
							<option value="B">Bureau</option>
						</select></td>
						<td></td>
						<td><select name="qsl_recv_method">
							<option value="" selected="selected">Method</option>
							<option value="D">Direct</option>
							<option value="B">Bureau</option>
						</select></td>
					</tr>

				</table>
			</div>

			<div id="tabs-5">
				
				<table>
					<tr>
						<td>IOTA</td>
						<td><input type="text" name="iota_ref" value="" /> e.g: EU-005</td>
					</tr>
				</table>
				
			</div>

		</div>
<div class="controls"><input type="submit" value="Add QSO" /> <input type="reset" value="Reset" /></div>

</form>
<script type="text/javascript">
	i=0;
	$(document).ready(function(){
	
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
		
		$.get('qso/band_to_freq/' + $('.band').val() + '/' + $('.mode').val(), function(result) {
						$('#frequency').val(result);
		});	
	
		/* Calculate Frequency */
			/* on band change */
			$('.band').change(function() {
				$.get('qso/band_to_freq/' + $(this).val() + '/' + $('.mode').val(), function(result) {
						$('#frequency').val(result);
					});	
			});
			
			/* on mode change */
			$('.mode').change(function() {
				$.get('qso/band_to_freq/' + $('.band').val() + '/' + $('.mode').val(), function(result) {
						$('#frequency').val(result);
					});	
			});
	
		/* On Key up Calculate Bearing and Distance */
		$("#locator").keyup(function(){
			if ($(this).val()) {
				$('#locator_info').load("logbook/bearing/" + $(this).val()).fadeIn("slow");
			}
		});
	
		/* On Callsign Change */
		$("#callsign").keyup(function(){
			if ($(this).val()) {
				/* Find Callsign Matches */
				$('#partial_view').load("logbook/partial/" + $(this).val()).fadeIn("slow");
	
				/* Find and populate DXCC */
				$.get('logbook/find_dxcc/' + $(this).val(), function(result) {
					$('#country').val(result);
				});
	
				/* Find Locator if the field is empty */
				if($('#locator').val() != null) {
					$.get('logbook/callsign_qra/' + $(this).val(), function(result) {
						$('#locator').val(result);
						$('#locator_info').load("logbook/bearing/" + result).fadeIn("slow");
					});

				}
	
				/* Find Operators Name */
				if($('#name').val() == "") {
					$.get('logbook/callsign_name/' + $(this).val(), function(result) {
						$('#name').val(result);
					});
				}

			}
		});
	});
</script>

</div>