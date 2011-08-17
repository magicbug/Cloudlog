<h2>Add Note</h2>
<div class="wrap_content">
<?php echo validation_errors(); ?>
<form method="post" action="<?php echo site_url('notes/add'); ?>" name="notes_add" id="notes_add">
<table>
	<tr>
		<td><label for="title">Title</label></td>
		<td><input type="text" name="title" value="" /></td>
	</tr>
	
	<tr>
		<td><label for="category">Category</label></td>
		<td><select name="category">
			<option value="General" selected="selected">General</option>
			<option value="Antennas">Antennas</option>
			<option value="Satellites">Satellites</option>
		</select></td>
	</tr>
	
	<tr>
		<td></td>
		<td><textarea name="content" id="markItUp" rows="10" cols="10"></textarea></td>
	</tr>
</table>

<div><input type="submit" value="Submit" /></div>

</form>

</div>
<script type="text/javascript"> 
<!--
$(document).ready(function()	{
	// Add markItUp! to your textarea in one line
	// $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
	$('#markItUp').markItUp(mySettings);

	// You can add content from anywhere in your page
	// $.markItUp( { Settings } );	
	$('.add').click(function() {
		$.markItUp( { 	openWith:'<opening tag>',
						closeWith:'<\/closing tag>',
						placeHolder:"New content"
					}
				);
		return false;
	});

	// And you can add/remove markItUp! whenever you want
	// $(textarea).markItUpRemove();
	$('.toggle').click(function() {
		if ($("#markItUp.markItUpEditor").length === 1) {
			$("#markItUp").markItUpRemove();
			$("span", this).text("get markItUp! back");
		} else {
			$('#markItUp').markItUp(mySettings);
			$("span", this).text("remove markItUp!");
		}
		return false;
	});
});
-->
</script> 
<script type="text/javascript" src="<?php echo base_url(); ?>markitup/jquery.markitup.js"></script> 
<!-- markItUp! toolbar settings --> 
<script type="text/javascript" src="<?php echo base_url(); ?>markitup/sets/html/set.js"></script> 
<!-- markItUp! skin --> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>markitup/skins/markitup/style.css" /> 
<!--  markItUp! toolbar skin --> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>markitup/sets/html/style.css" /> 