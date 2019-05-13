
<div class="container notes">

<div class="card">
  <div class="card-body">
    <h2 class="card-title">Create Note</h2>

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
				<td><textarea name="content" id="markItUp" rows="10" cols="70"></textarea></td>
			</tr>
		</table>

		<div class="actions"><input class="btn primary" type="submit" value="Submit" /></div>

  </div>
</div>

</div>
