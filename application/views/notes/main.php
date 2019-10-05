<div class="container notes">

	<div class="card">
	  <div class="card-header">
	  	<h2 class="card-title">Notes</h2>
	    <ul class="nav nav-tabs card-header-tabs">
	      <li class="nav-item">
	        <a class="nav-link active" href="<?php echo site_url('notes'); ?>">Notes</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('notes/add'); ?>">Create Note</a>
	      </li>
	    </ul>
	  </div>

	  <div class="card-body">

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
