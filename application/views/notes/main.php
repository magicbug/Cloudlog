<div class="container notes">

	<div class="card">
	  <div class="card-body">
	    <h2 class="card-title">Notes</h2>
	    <p class="card-body"><a href="<?php echo site_url('notes/add'); ?>" class="btn btn-primary">Create a Note</a><p>

	    		<?php

				if ($notes->num_rows() > 0)
				{
					echo "<h3>Your Notes</h3>";
					echo "<ul class=\"list-group\">";
					foreach ($notes->result() as $row)
					{
						echo "<li class=\"list-group-item\">";
						echo "<a href=\"".site_url()."/notes/view/".$row->id."\">".$row->title."</a>";
						echo "</li>";
					}
					echo "</ul>";
				} else {
					echo "<p>You don't currently have any notes, these are a fantastic way of storing data like ATU settings, beacons and general station notes and its better than paper as you can't lose them!</p>";
				}

			?>
	  </div>
	</div>
</div>
