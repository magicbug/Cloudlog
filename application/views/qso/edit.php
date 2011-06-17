<h2>Add QSO</h2>
<div class="wrap_content">
<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('qso/edit'); ?>" name="qsos">
<table>
	<tr>
		<td>Start Date</td>
		<td><input type="text" name="time_on" value="<?php echo $COL_TIME_ON; ?>" /></td>
	</tr>
	
	<tr>
		<td>End Date</td>
		<td><input type="text" name="time_off" value="<?php echo $COL_TIME_OFF; ?>" /></td>
	</tr>
	
	<tr>
		<td>Callsign</td>
		<td><input type="text" name="callsign" value="<?php echo $COL_CALL; ?>" /></td>
	</tr>
	
	<?php if($COL_FREQ) { ?>
	<tr>
		<td>Freq</td>
		<td><input type="text" name="freq" value="<?php echo $COL_FREQ; ?>" /></td>
	</tr>
	<?php } ?>
	
	<tr>
		<td>Mode</td>
		<td><input type="text" name="mode" value="<?php echo $COL_MODE; ?>" /></td>
	</tr>
	
	<tr>
		<td>Band</td>
		<td><input type="text" name="band" value="<?php echo $COL_BAND; ?>" /></td>
	</tr>
	
	<tr>
		<td>RST Sent</td>
		<td><input type="text" name="rst_sent" value="<?php echo $COL_RST_SENT; ?>" /></td>
	</tr>
	
	<tr>
		<td>RST Recv</td>
		<td><input type="text" name="rst_recv" value="<?php echo $COL_RST_RCVD; ?>" /></td>
	</tr>
	
	<tr>
		<td>Name</td>
		<td><input type="text" name="name" value="<?php echo $COL_NAME; ?>" /></td>
	</tr>
	
	<tr>
		<td>Comment</td>
		<td><input type="text" name="comment" value="<?php echo $COL_COMMENT; ?>" /></td>
	</tr>
	
	<tr>
		<td>Sat Name</td>
		<td><input type="text" name="sat_name" value="<?php echo $COL_SAT_NAME; ?>" /></td>
	</tr>
	
	<tr>
		<td>Sat Mode</td>
		<td><input type="text" name="sat_mode" value="<?php echo $COL_SAT_MODE; ?>" /></td>
	</tr>
	
</table>
<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />
<div><input type="submit" value="Submit" /></div>

</form>

</div>