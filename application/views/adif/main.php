<h2>ADIF Export</h2>
<div class="wrap_content note">

<p>Exporting your log is simple you can either export the whole log or use the finer controls to set the date.</p>

<ul class="notes_list">
	<li><a href="<?php echo site_url('adif/exportall'); ?>" title="Export All" target="_blank">Export All QSOs</a></li>
</ul>

<h3>Export Options</h3>

<form method="post" action="<?php echo site_url('adif/export_custom');?>" >
<table>
	<tr>
		<td>Start Date</td>
		<td><input type="text" id="from" name="from"/></td>
	</tr>
	
	<tr>
		<td>End Date</td>
		<td><input type="text" id="to" name="to"/></td>
	</tr>
	
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Download" /></td>
	</tr>
</table>
</form>

</div>

<script>
	
	$(function() {
		var dates = $( "#from, #to" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			dateFormat: "yy-mm-dd",
			onSelect: function( selectedDate ) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
	});
</script>