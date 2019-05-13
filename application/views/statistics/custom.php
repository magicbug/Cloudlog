<script>
	$(function() {
		$( "#start_date" ).datepicker({ dateFormat: "yy-mm-dd" });
		$( "#end_date" ).datepicker({ dateFormat: "yy-mm-dd" });
	});
</script>

<div class="container statistics_custom">

<h2>
  <?php echo $page_title; ?>
  <small class="text-muted">Explore the logbook.</small>
</h2>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="/index.php/statistics" role="tab" aria-controls="home" aria-selected="true">General</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="satellite-tab" data-toggle="tab" href="/index.php/statistics#space" role="tab" aria-controls="satellite" aria-selected="false">Satellites</a>
  </li>
  <li class="nav-item">
    <a href="/index.php/statistics/custom" class="nav-link" role="tab">Custom</a>
  </li>
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
				<?php
				foreach($modes->result() as $row){
                    printf('<input type="checkbox" name="mode_%s" value="%s" />%s',  $row->COL_MODE, $row->COL_MODE, $row->COL_MODE);
				}
				?>
			</div>
			
			<div class="type">
				<p>Finished your selection? time to search!</p>
				<input type="submit" class="btn primary" name="submit" value="Search" />		
			</div>
			
			<div class="clear"></div>
		
			
			</form>
		</div>


	<div class="results">
		<p>Results go here</p>
	</div>

</div>
