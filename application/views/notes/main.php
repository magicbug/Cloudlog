<div class="container notes">

	<div class="card">
	  <div class="card-header">
	  	<h2 class="card-title"><?php echo lang('notes_menu_notes'); ?></h2>
	    <ul class="nav nav-tabs card-header-tabs">
	      <li class="nav-item">
	        <a class="nav-link active" href="<?php echo site_url('notes'); ?>"><?php echo lang('notes_menu_notes'); ?></a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" href="<?php echo site_url('notes/add'); ?>"><?php echo lang('notes_create_note'); ?></a>
	      </li>
	    </ul>
	  </div>

	  <div class="card-body">

	    		<?php

				if ($notes->num_rows() > 0)
				{
					echo "<h3>".lang('notes_your_notes')."</h3>";
					echo "<ul class=\"list-group\">";
					foreach ($notes->result() as $row)
					{
						echo "<li class=\"list-group-item\">";
						echo "<a href=\"".site_url()."/notes/view/".$row->id."\">".$row->title."</a>";
						echo "</li>";
					}
					echo "</ul>";
				} else {
					echo "<p>".lang('notes_welcome')."</p>";
				}

			?>
	  </div>
	</div>
</div>
