<div id="container">
		<h2><?php echo $page_title; ?></h2>

		<p>Station Profiles define locations of operating positions, useful for portable operating or using a friends QTH.</p>

	<?php if($this->session->flashdata('message')) { ?>
	<!-- Display Message -->
	<div class="alert-message error">
	  <p><?php echo $this->session->flashdata('message'); ?></p>
	</div>
	<?php } ?>

	<div class="row show-grid">
	  <div class="span15">
		
		<!-- Display Radio Statuses -->	  
		<table class="station_profiles">
			<?php

			if ($stations->num_rows() > 0)
			{
			?>
				<tr>
					<td>Profile Name</td>
					<td>Country</td>
					<td>Gridsquare</td>
					<td>City</td>
					<td>IOTA</td>
					<td>SOTA</td>
					<td>Cnty</td>
					<td>CQ</td>
					<td>ITU</td>
					<td></td>
				</tr>
			<?php
				foreach ($stations->result() as $row)
				{
			?>
				<tr>
					<td><?php echo $row->station_profile_name;?></td>
					<td><?php echo $row->station_country;?></td>
					<td><?php echo $row->station_gridsquare;?></td>
					<td><?php echo $row->station_city;?></td>	
					<td><?php echo $row->station_iota;?></td>	
					<td><?php echo $row->station_sota;?></td>
					<td><?php echo $row->station_cnty;?></td>
					<td><?php echo $row->station_cq;?></td>
					<td><?php echo $row->station_itu;?></td>
					<td><a href="<?php echo site_url('station/delete')."/".$row->station_id; ?>">Delete</a></td>
				</tr>	
			<?php

				}

			} else {
				echo "<p>Nothing to show</p>";
			}

		?>
		</table>

	<a class="btn primary" href="<?php echo site_url('station/create'); ?>">Create Station Profile</a>


	  </div>
	</div>