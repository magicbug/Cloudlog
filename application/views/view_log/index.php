<h2>Logbook</h2>

<?php if($this->session->flashdata('notice')) { ?>
<div id="message" >
	<?php echo $this->session->flashdata('notice'); ?>
</div>
<?php } ?>

<div class="wrap_content">


<table class="logbook" width="100%">
	<tr class="log_title titles">
		<td>Date</td>
		<td>Time</td>
		<td>Call</td>
		<td>Mode</td>
		<td>Sent</td>
		<td>Recv</td>
		<td>Band</td>
		<td>Name</td>
		<td>Country</td>
		<td>Options</td>
	</tr>
	
	<?php  $i = 0;  foreach ($results->result() as $row) { ?>
		<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
		<td><?php echo strtoupper($row->COL_CALL); ?></td>
		<td><?php echo $row->COL_MODE; ?></td>
		<td><?php echo $row->COL_RST_SENT; ?></td>
		<td><?php echo $row->COL_RST_RCVD; ?></td>
		<td><?php echo $row->COL_BAND; ?></td>
		<td><?php echo substr($row->COL_NAME, 0, 15); ?></td>
		<td><?php echo $row->COL_COUNTRY; ?></td>
		<td><a href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" >Edit</a></td>
	</tr>
	<?php $i++; } ?>
	
</table>

<?php echo $this->pagination->create_links(); ?>
</div>