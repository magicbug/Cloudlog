	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script type="text/javascript">

		$(document).ready(function() {
			$(".qsobox").fancybox({
				'width'				: 849,
				'autoScale'			: true,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
			});


		});

	</script>

<?php
			$serial_number = $info->serial_num + 1;
			if($serial_number <= 009) { 
				$new_serial = "00".$serial_number;
			}
			
			if($serial_number >= 010 && $serial_number <= 100) {
				$new_serial = "0".$serial_number;
			}
			
			if($serial_number > 100) {
				$new_serial = $serial_number;
			}

		?>
		
<script type="text/javascript">
setInterval("settime()", 1000);

function settime () {
  var curtime = new Date();
  var curhour = curtime.getHours();
  var curmin = curtime.getMinutes();
  var cursec = curtime.getSeconds();
  var time = "";

  if(curhour == 0) curhour = 24;
  time = (curhour > 24 ? curhour - 24 : curhour - 1) + ":" +
		 (curmin < 10 ? "0" : "") + curmin + ":" +
		 (cursec < 10 ? "0" : "") + cursec

  document.qsos.start_time.value = time;
}
</script>

<div class="contest_wrap">
	<h2>Contest - <?php echo $info->name; ?></h2>
	<div class="contest_view">
	<div class="contest_sidebar">
	<h3>Summary</h3>
	<table width="100%">
		<tr>
			<td>Band</td>
			<td>QSOs</td>
			<td></td>
		</tr>
		
		<?php foreach ($summary->result() as $row) { ?>
		<?php if($row->band == "160m" && $info->band_160 == "Y") { ?>
		<tr>
			<td>160m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "80m" && $info->band_80 == "Y") { ?>
		<tr>
			<td>80m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "40m" && $info->band_40 == "Y") { ?>
		<tr>
			<td>40m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "20m" && $info->band_20 == "Y") { ?>
		<tr>
			<td>20m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "15m" && $info->band_15 == "Y") { ?>
		<tr>
			<td>15m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "10m" && $info->band_10 == "Y") { ?>
		<tr>
			<td>10m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "6m" && $info->band_6m == "Y") { ?>
		<tr>
			<td>6m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "4m" && $info->band_4m == "Y") { ?>
		<tr>
			<td>4m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "2m" && $info->band_2m == "Y") { ?>
		<tr>
			<td>2m</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "70cm" && $info->band_70cm == "Y") { ?>
		<tr>
			<td>70cm</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>

		<?php if($row->band == "23cm" && $info->band_23cm == "Y") { ?>
		<tr>
			<td>23cm</td>
			<td><?php echo $row->count; ?></td>
			<td></td>
		</tr>
		<?php } ?>
		<?php } ?>
		
	</table>
</div>
	<table class="logbook" width="50%">
	<tr class="log_title titles">
		<td>Date</td>
		<td>Time</td>
		<td>Band</td>
		<td>Call</td>
		<td>Mode</td>
		<td>RST(S)</td>
		<td>Serial</td>
		<td>Recv</td>
		<td>Serial</td>
	</tr>

	<?php $i = 0; 
 foreach ($log->result() as $row) { ?>

		<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
		<td><?php echo $row->COL_BAND; ?></td>
		<td><a class="qsobox" href="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>"><?php echo strtoupper($row->COL_CALL); ?></a></td>
		<td><?php echo $row->COL_MODE; ?></td>
		<td><?php echo $row->COL_RST_SENT; ?></td>
		<td><?php echo $row->COL_STX_STRING; ?></td>
		<td><?php echo $row->COL_RST_RCVD; ?></td>
		<td><?php echo $row->COL_SRX_STRING; ?></td>
		
	</tr>
	<?php $i++; } ?>

</table>



<!-- Entry Box -->

<?php echo validation_errors(); ?>

<div class="contest_qso_box">
<form method="post" action="<?php echo site_url('contest/view/'.$info->id); ?>" name="qsos">

<table>
	<tr class="log_title">
		<td class="title">Time</td>
		<td class="title">Callsign</td>
		<?php if($this->config->item('display_freq') == true) { ?>
		<td class="title">Freq</td>
		<?php } ?>
		<td class="title">Mode</td>
		<td class="title">Band</td>
		<td class="title">RST(S)</td>
		<td class="title"></td>
		<td class="title">RST(R)</td>
		<td class="title">Serial</td>
		<?php if($info->qra == "Y") { ?>
		<td class="title">QRA</td>
		<?php } ?>
		<td class="title"></td>
	</tr>

	<tr>
		<td><input class="input_time" type="text" name="start_time" value="" size="7" /></td>
		<td><input size="10" id="callsign" type="text" name="callsign" value="" /></td>
		<td><select name="mode">
			<?php if ($info->mode_ssb == "Y") { ?>
				<option value="SSB" <?php if($this->session->userdata('mode') == "" || $this->session->userdata('mode') == "FM") { echo "selected=\"selected\""; } ?>>SSB</option>
			<?php } ?>
			
			<?php if ($info->mode_cw == "Y") { ?>
				<option value="CW" <?php if($this->session->userdata('mode') == "CW") { echo "selected=\"selected\""; } ?>>CW</option>
			<?php } ?>

		</select></td> 

		<td><select name="band">
			<?php if($info->band_160 == "Y") { ?>
			<option value="160m" <?php if($this->session->userdata('band') == "160m") { echo "selected=\"selected\""; } ?>>160m</option>
			<?php } ?>
			
			<?php if($info->band_80 == "Y") { ?>
			<option value="80m" <?php if($this->session->userdata('band') == "80m") { echo "selected=\"selected\""; } ?>>80m</option>
			<?php } ?>
			
			<?php if($info->band_40 == "Y") { ?>
			<option value="40m" <?php if($this->session->userdata('band') == "40m") { echo "selected=\"selected\""; } ?>>40m</option>
			<?php } ?>
			
			<?php if($info->band_20 == "Y") { ?>
			<option value="20m" <?php if($this->session->userdata('band') == "20m") { echo "selected=\"selected\""; } ?>>20m</option>
			<?php } ?>
			
			<?php if($info->band_15 == "Y") { ?>
			<option value="15m" <?php if($this->session->userdata('band') == "15m") { echo "selected=\"selected\""; } ?>>15m</option>
			<?php } ?>
			
			<?php if($info->band_10 == "Y") { ?>
			<option value="10m" <?php if($this->session->userdata('band') == "10m") { echo "selected=\"selected\""; } ?>>10m</option>
			<?php } ?>
			
			<?php if($info->band_6m == "Y") { ?>
			<option value="6m" <?php if($this->session->userdata('band') == "6m") { echo "selected=\"selected\""; } ?>>6m</option>
			<?php } ?>
			
			<?php if($info->band_4m == "Y") { ?>
			<option value="4m" <?php if($this->session->userdata('band') == "4m") { echo "selected=\"selected\""; } ?>>4m</option>
			<?php } ?>
			
			<?php if($info->band_2m == "Y") { ?>
			<option value="2m" <?php if($this->session->userdata('band') == "2m") { echo "selected=\"selected\""; } ?>>2m</option>
			<?php } ?>
			
			<?php if($info->band_70cm == "Y") { ?>
			<option value="70cm" <?php if($this->session->userdata('band') == "70cm") { echo "selected=\"selected\""; } ?>>70cm</option>
			<?php } ?>
			
			<?php if($info->band_23cm == "Y") { ?>
			<option value="23cm" <?php if($this->session->userdata('band') == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
			<?php } ?>
		</select></td>
		<td><select name="rst_sent">
			<option value="51">51</option>
			<option value="52">52</option>
			<option value="53">53</option>
			<option value="54">54</option>
			<option value="55">55</option>
			<option value="56">56</option>
			<option value="57">57</option>
			<option value="58">58</option>
			<option value="59" selected="selected">59</option>
			<option value="59+10dB">59+10dB</option>
			<option value="59+20dB">59+20dB</option>
			<option value="59+30dB">59+30dB</option>
		</select></td>
	
		
		<td><input type="text" name="sent_serial" value="<?php echo $new_serial; ?>" size="4" /></td>
		
		<td><input type="text" name="rst_recv" value="59" size="2" /></td>
		<td><input type="text" name="recv_serial" value="" size="4" /></td>
		
		<?php if($info->qra == "Y") { ?>
		<td><input id="locator" type="text" name="locator" value="" size="8" /></td>
		<?php } ?>
		<td><input type="submit" value="Add QSO" /></td>
	</tr>
</table>

</form>

<div class="info">
	<input size="20" id="country" type="text" name="country" value="" /> <span id="locator_info"></span>
</div>

</div>



	</div>
</div>

<script type="text/javascript">
i=0;
$(document).ready(function(){
	$("#locator").keyup(function(){
		if ($(this).val()) {
			$('#locator_info').load("<?php echo site_url(); ?>/logbook/bearing/" + $(this).val()).fadeIn("slow");
		}
	});

  $("#callsign").keyup(function(){
	if ($(this).val()) {
	$('#partial_view').load("<?php echo site_url(); ?>//logbook/partial/" + $(this).val()).fadeIn("slow");

	$.get('<?php echo site_url(); ?>/logbook/find_dxcc/' + $(this).val(), function(result) {
	$('#country').val(result);
		});

	$.get('<?php echo site_url(); ?>/logbook/callsign_qra/' + $(this).val(), function(result) {
	$('#locator').val(result);
		});
	}

  });
});
</script>