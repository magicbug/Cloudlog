<div class="container notes">
<?php foreach ($note->result() as $row) { ?>
<div class="card">
  <div class="card-body">
    <h2 class="card-title">Edit Note - <?php echo $row->title; ?></h2>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>


	<?php echo validation_errors(); ?>
	<form method="post" action="<?php echo site_url('notes/edit'); ?>/<?php echo $id; ?>" name="notes_add" id="notes_add">
	<table>
		<tr>
			<td><label for="title">Title</label></td>
			<td><input type="text" name="title" value="<?php echo $row->title; ?>" /></td>
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
			<td><input type="hidden" name="id" value="<?php echo $id; ?>" /></td>
			<td><textarea name="content" id="markItUp" rows="10" cols="75"><?php echo $row->note; ?></textarea></td>
		</tr>
	</table>

	<div class="actions"><input class="btn primary" type="submit" value="Submit" /></div>

	</form>
  </div>
</div>
	  
	


	  </div>

	</div>
<?php } ?>
</div>
