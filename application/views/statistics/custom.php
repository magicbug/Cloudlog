<script>
	$(function() {
		$( "#start_date" ).datepicker();
		$( "#end_date" ).datepicker();
	});
</script>

<div id="container">

	<h2><?php echo $page_title; ?></h2>

	<ul class="tabs">
	  <li><a href="statistics">General</a></li>
	  <li><a href="statistics">Satellite Contacts</a></li>
	  <li class="active"><a href="statistics/custom">Custom</a></li>
	</ul>
	
		<p>This is a work in-progress</p>
		
		<div id="filter_box">
		
			<h2>Options</h2>
			
			<?php echo validation_errors(); ?>

			<?php echo form_open('statistics/custom'); ?>
			
			<form method="post" action="testing.php" id="test" name="test">
				
			</form>
		
			<div class="type">
				<h3>Date</h3>
				<table>
					<tr>
						<td>Start</td>
						<td><input type="text" id="start_date" name="start_date" value="" /></td>
					</tr>
					
					<tr>
						<td>End</td>
						<td><input type="text" id="end_date" name="end_date" value="" /></td>
					</tr>
				</table>
			</div>
			
			<div class="type">
				<h3>Band</h3>
				<select name="band" class="band">
				<option value="">Select</option>
				<option value="HF" <?php if($this->session->userdata('band') == "HF") { echo "selected=\"selected\""; } ?>>HF</option>
				<option value="VHF" <?php if($this->session->userdata('band') == "VHF") { echo "selected=\"selected\""; } ?>>VHF</option>
				<option value="UHF" <?php if($this->session->userdata('band') == "UHF") { echo "selected=\"selected\""; } ?>>UHF</option>
				<option value="VHF/UHF" <?php if($this->session->userdata('band') == "VHF") { echo "selected=\"selected\""; } ?>>VHF</option>
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
				
				<h3>Mode</h3>
				<select name="mode" class="mode">
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
				</select>
			</div>
			
			<div class="type">
				<p>Finished your selection? time to search!</p>
				<input type="submit" class="btn primary" name="submit" value="Search" />		
			</div>
			
			<div class="clear"></div>
		
			
			</form
		</div>


	<div class="results">
		<p>Results go here</p>
	</div>

</div>
