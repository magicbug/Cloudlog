<h2>Note</h2>
<div class="wrap_content note">

<?php

	if ($notes->num_rows() > 0)
	{
		echo "<ul class=\"notes_list\">";
		foreach ($notes->result() as $row)
		{
			echo "<li>";
			echo "<a href=\"".site_url()."/notes/view/".$row->id."\">".$row->title."</a>";
			echo "</li>";
		}
		echo "</ul>";
	} else {
		echo "<p>You have no notes, why not create one!</p>";
	}

?>

<p><a href="<?php echo site_url('notes/add'); ?>" title="Add Note">Create a Note</a></p>

</div>