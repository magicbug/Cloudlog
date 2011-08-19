<h2>Contests</h2>
<div class="wrap_content note">

<?php

	if ($contests->num_rows() > 0)
	{
		echo "<ul class=\"notes_list\">";
		foreach ($contests->result() as $row)
		{
			echo "<li>";
			echo "<a href=\"".site_url()."/contest/view/".$row->id."\">".$row->name."</a>";
			echo "</li>";
		}
		echo "</ul>";
	} else {
		echo "<p>You have no contests, why not create one!</p>";
	}

?>
</div>