<script>
	$(function() {
		$( "#start_date" ).datepicker({ dateFormat: "yy-mm-dd" });
		$( "#end_date" ).datepicker({ dateFormat: "yy-mm-dd" });
	});
</script>

<div id="container">

	<h2><?php echo $page_title; ?></h2>

	<ul class="tabs">
	  <li><a href="<?php echo site_url('statistics');?>#home">General</a></li>
	  <li><a href="<?php echo site_url('statistics');?>#space">Satellite Contacts</a></li>
	  <li class="active"><a href="<?php echo site_url('statistics');?>/custom">Custom</a></li>
	</ul>
	
		<p>This is a work in-progress</p>
		
		<div id="filter_box">
		
			<h2>Options</h2>
			
			<?php echo validation_errors(); ?>

			<?php echo form_open('statistics/custom'); ?>
		
			<div class="type">
				<h3>Date</h3>
				<table>
					<tr>
						<td>Start</td>
						<td><input type="text" id="start_date" name="start_date" value="" autocomplete="off"/></td>
					</tr>
					
					<tr>
						<td>End</td>
						<td><input type="text" id="end_date" name="end_date" value="" autocomplete="off"/></td>
					</tr>
				</table>
			</div>
			
			<div class="type">
				<h3>Band</h3>
				<input type="checkbox" name="band_6m" value="6m" /> 6m
				<input type="checkbox" name="band_2m" value="2m" /> 2m
				<input type="checkbox" name="band_70cm" value="70cm" /> 70cm
				<input type="checkbox" name="band_23cm" value="23cm" /> 23cm
				<input type="checkbox" name="band_3cm" value="3cm" /> 3cm
				
				<h3>Mode</h3>
					<input type="checkbox" name="mode_ssb" value="ssb" /> SSB
					<input type="checkbox" name="mode_cw" value="cw" /> CW
					<input type="checkbox" name="mode_data" value="data" /> Data
					<input type="checkbox" name="mode_fm" value="FM" /> FM
					<input type="checkbox" name="mode_am" value="AM" /> AM
			</div>
			
			<div class="type">
				<p>Finished your selection? time to search!</p>
				<input type="submit" class="btn primary" name="submit" value="Search" />		
			</div>
			
			<div class="clear"></div>
		
			
			</form>
		</div>


	<div class="results">
			<table width="100%">
		<tr class="titles">
			<td>Date</td>
			<td>Time</td>
			<td>Call</td>
			<td>Mode</td>
			<td>Sent</td>
			<td>Recv</td>
			<td>Band</td>
			<td>Country</td>
		</tr>
		
		<?php  $i = 0;  foreach ($result->result() as $row) { ?>
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
			<td><?php echo $row->COL_COUNTRY; ?></td>
		</tr>
		<?php $i++; } ?>
		
	</table>
	</div>

</div>
