<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert alert-success" role="alert">
		  <?php echo $this->session->flashdata('message'); ?>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('error')) { ?>
		<!-- Display Message -->
		<div class="alert alert-danger" role="alert">
		  <?php echo $this->session->flashdata('error'); ?>
		</div>
	<?php } ?>

	<?php if($this->session->flashdata('warning')) { ?>
		<!-- Display Message -->
		<div class="alert alert-warning" role="alert">
		  <?php echo $this->session->flashdata('warning'); ?>
		</div>
	<?php } ?>

<div class="card">
	<h2 class="card-header">QSL Card Labels</h2>

	<div class="card-body">
	<a href="<?php echo site_url('labels/create'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"> </i> Create New Label Type</a>
	<a href="<?php echo site_url('labels/createpaper'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"> </i> Create New Paper Type</a>
<br><br>
	<?php if ($papertypes) { ?>
		<h4>Paper types</h4>
						<table style="width:100%" class="table-sm labeltable table-bordered table-hover table-striped table-condensed text-center">
						<thead>
							<tr>
								<th>Name</th>
								<th>Measurement</th>
								<th>Width</th>
								<th>Height</th>
								<th>Used by labels</th>
								<th>Orientation</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
						<?php
		foreach($papertypes as $paper) { ?>
			<tr class='paper_<?php echo $paper->paper_id ?>'>
			<td><?php echo $paper->paper_name; ?></td>
			<td><?php echo $paper->metric; ?></td>
			<td><?php echo $paper->width; ?></td>
			<td><?php echo $paper->height; ?></td>
			<td><?php echo $paper->lbl_cnt ?? '0' ?></td>
			<td><?php echo $paper->orientation == 'P' ? 'Portrait': 'Landscape'; ?></td>
			<td><a href="<?php echo site_url('labels/editpaper/' . $paper->paper_id); ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a></td>
			<td><a href="javascript:deletepaper(<?php echo $paper->paper_id; ?>);" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt"></i></a></td>
			</tr>

		<?php }
		echo '</tbody></table>';
	} ?>

	<?php if ($labels) { ?>
		<br>
		<h4>Label types</h4>
						<table style="width:100%" class="table-sm labeltable table-bordered table-hover table-striped table-condensed text-center">
						<thead>
							<tr>
								<th>Name</th>
								<th>Paper Type</th>
								<th>Measurement</th>
								<th>Width</th>
								<th>Height</th>
								<th>Font Size</th>
								<th>QSOs</th>
								<th>Use For Print</th>
								<th>Edit</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
						<?php
		foreach($labels as $label) { ?>
			<tr class='label_<?php echo $label->id ?>'>
			<td><?php echo $label->label_name; ?></td>
			<td><?php echo $label->paper_name ?? '<span class="badge badge-danger">No paper assigned</span>' ?></td>
			<td><?php echo $label->metric; ?></td>
			<td><?php echo $label->width; ?></td>
			<td><?php echo $label->height; ?></td>
			<td><?php echo $label->font_size; ?></td>
			<td><?php echo $label->qsos; ?></td>
			<?php if (($label->paper_name ?? '') == '') { ?>
			<td></td>
			<?php } else { ?>
			<td><input type="checkbox" <?php if ($label->useforprint == 1) {echo 'checked';}?>></td>
			<?php } ?>
			<td><a href="<?php echo site_url('labels/edit/' . $label->id); ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a></td>
			<td><a href="javascript:deletelabel(<?php echo $label->id; ?>);" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt"></i></a></td>
			</tr>

		<?php }
		echo '</tbody></table>';
	} ?>

</div>
</div>

<br><br>

<div class="card">
	<h2 class="card-header">QSL Card Labels Pending</h2>

	<div class="card-body">
	<table style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">
				<thead>
					<tr>
						<th>Callsign</th>
						<th>Station Location</th>
						<th>Gridsquare</th>
						<th>QSOs Waiting</th>
						<th>View QSOs</th>
						<th>Print</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($qsos as $qso) {
						echo '<tr>';
						echo '<td>' . $qso->station_callsign . '</td>';
						echo '<td>' . $qso->station_profile_name . '</td>';
						echo '<td>' . $qso->station_gridsquare . '</td>';
						echo '<td>' . $qso->count . '</td>';
						echo '<td><a href="'. site_url('qslprint') . '/index/'.$qso->station_id.'" class="btn btn-outline-info btn-sm"><i class="fas fa-search"></i></a></td>';
						echo '<td><button class="btn btn-outline-success btn-sm printbutton" onclick="printat('.$qso->station_id.')"><i class="fas fa-print"></i></button></td>';
						echo '</tr>';
					} ?>
				</tbody>
		</table>

	</div>
</div>

</div>
