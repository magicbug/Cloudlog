<h2>Logbook</h2>

<div class="wrap_content">

<table width="100%">
	<tr class="titles">
		<td>Date</td>
		<td>Time</td>
		<td>Call</td>
		<td>Mode</td>
		<td>Sent</td>
		<td>Recv</td>
		<td>Band</td>
		<td>Name</td>
		<td>Country</td>
	</tr>
	
	<?php foreach ($results->result() as $row) { ?>
		<tr>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
		<td><?php echo strtoupper($row->COL_CALL); ?></td>
		<td><?php echo $row->COL_MODE; ?></td>
		<td><?php echo $row->COL_RST_SENT; ?></td>
		<td><?php echo $row->COL_RST_RCVD; ?></td>
		<td><?php echo $row->COL_BAND; ?></td>
		<td><?php echo substr($row->COL_NAME, 0, 15); ?></td>
		<td><?php echo $row->COL_COUNTRY; ?></td>
	</tr>
	<?php } ?>
	
</table>

<?php echo $this->pagination->create_links(); ?>
</div>