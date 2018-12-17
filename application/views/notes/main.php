<div id="container">

	<h2>Note</h2>

	<div class="row show-grid">
	  <div class="span13">
	  
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
				echo "<p>You don't currently have any notes, these are a fantastic way of storing data like ATU settings, beacons and general station notes and its better than paper as you can't lose them!</p>";
			}

		?>


	  </div>
	  <div class="span2 offset1">
	  <a class="btn primary" href="<?php echo site_url('notes/add'); ?>">Create Note</a>
	  </div>
	</div>

</div>
