<div class="container">

	<h2><?php echo $page_title; ?></h2>
	
	<table class="table table-sm table-striped table-hover">
		
	<tr>
		<td>Reference</td>
		<td>Date/Time</td>
		<td>Callsign</td>
		<td>Band</td>
		<td>RST Sent</td>
		<td>RST Received</td>
	</tr>
	
	<?php
		if ($sota_all->num_rows() > 0) {
			foreach ($sota_all->result() as $row) {
	?>
	
	<tr>
		<td>	
			<?php echo $row->COL_SOTA_REF; ?>
		</td>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?> - <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
		<td><?php echo $row->COL_CALL; ?></td>
		<td><?php echo $row->COL_BAND; ?></td>
		<td><?php echo $row->COL_RST_SENT; ?></td>
		<td><?php echo $row->COL_RST_RCVD; ?></td>
	</tr>
	<?php
		  }
		}
	?>
	
	</table>
</div>
