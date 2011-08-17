<h2>Create Contest</h2>

<script>
	$(function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>
<div class="wrap_content note">
<?php echo validation_errors(); ?>

<?php echo form_open('contest/create'); ?>
<table>
	<tr>
		<td>Name</td>
		<td><input type="text" name="contest_name" value="" /></td>
	</tr>
	
	<tr>
		<td>Start Date</td>
		<td><input type="text" name="start_date" value="" class="datepicker" /></td>
	</tr>
	
	<tr>
		<td>Start Time</td>
		<td><input type="text" name="start_time" value="hh:mm:ss" /></td>
	</tr>
	
	<tr>
		<td>End Date</td>
		<td><input type="text" name="end_date" value="" class="datepicker" /></td>
	</tr>
	
	<tr>
		<td>End Time</td>
		<td><input type="text" name="end_time" value="hh:mm:ss" /></td>
	</tr>
	
	<tr>
		<td>Template</td>
		<td>
		<?php if ($templates->num_rows() > 0) { ?>
		<select name="template">
			<?php foreach ($templates->result() as $row) { ?>
				<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
			<?php } ?>
		</select>
		<?php } ?>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td><div class="controls"><input type="submit" value="Create" /></div></td>
	</tr>
</table>
</form>
</div>