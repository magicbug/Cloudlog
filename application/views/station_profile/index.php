<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?>
  </div>
  <div class="card-body">
    <h5 class="card-title">Lets Explore Operating Locations</h5>
    <p class="card-text">Station Profiles define locations of operating positions, useful for portable operating or using a friends QTH.</p>

		<?php if ($stations->num_rows() > 0) { ?>

		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">Profile Name</th>
					<th scope="col">Country</th>
					<th scope="col">Gridsquare</th>
					<th scope="col">City</th>
					<th scope="col">IOTA</th>
					<th scope="col">SOTA</th>
					<th scope="col">Cnty</th>
					<th scope="col">CQ</th>
					<th scope="col">ITU</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($stations->result() as $row) { ?>
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
					<td><a href="<?php echo site_url('station/delete')."/".$row->station_id; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</a></td>
				</tr>	
				<?php } ?>
			</tbody>
		<table>
		<?php } ?>

		<p><a href="<?php echo site_url('station/create'); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create a Station Location</a></p>
  </div>
</div>


</div>