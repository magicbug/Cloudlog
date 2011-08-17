<h2>Create Contest Template</h2>

<script>
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>
<div class="wrap_content note">

<?php echo validation_errors(); ?>

<?php echo form_open('contest/add_template'); ?>
<table>
	<tr>
		<td>Name</td>
		<td><input type="text" name="contest_name" value="" /></td>
	</tr>
	
	<tr>
		<td>Bands</td>
		<td>
			<input type="checkbox" name="160m" value="Y" /> 160m<br />
			<input type="checkbox" name="80m" value="Y" /> 80m<br />
			<input type="checkbox" name="40m" value="Y" /> 40m<br />
			<input type="checkbox" name="20m" value="Y" /> 20m<br />
			<input type="checkbox" name="15m" value="Y" /> 15m<br />
			<input type="checkbox" name="10m" value="Y" /> 10m<br />
			<input type="checkbox" name="6m" value="Y" /> 6m<br />
			<input type="checkbox" name="4m" value="Y" /> 4m<br />
			<input type="checkbox" name="2m" value="Y" /> 2m<br />
			<input type="checkbox" name="70cm" value="Y" /> 70cm<br />
			<input type="checkbox" name="23cm" value="Y" /> 23cm<br />
		</td>
	</tr>
	
	<tr>
		<td>Modes</td>
		<td>
			<input type="checkbox" name="SSB" value="SSB" /> SSB<br />
			<input type="checkbox" name="CW" value="CW" /> CW<br />
		</td>	
	</tr>
	
	<tr>
		<td>Requires Serial:</td>
		<td>
			<select name="serial_num">
				<option value="Y" selected="selected">Yes</option>
				<option value="N">N</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Points Per KM</td>
		<td><input type="text" name="points_per_km" value="" size="3"/><br />(If needed entered amount of points)</td>
	</tr>
	
	<tr>
		<td>Gridsquare</td>
		<td>
			<select name="qra">
				<option value="N" selected="selected">No</option>
				<option value="Y">Yes</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Other Exchange</td>
		<td><input type="text" name="other_exch" value="" /></td>
	</tr>
	
	<tr>
		<td>Scoring</td>
		<td>
			<select name="scoring">
				<option value=""></option>
				<option value="km*qra">Per KM x QRA</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td><div class="controls"><input type="submit" value="Create" /></div></td>
	</tr>
</table>
</form>

</div>