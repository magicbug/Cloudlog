<div id="container">
	<h1><?php echo $page_title; ?></h1>

	<!-- Sub Nav for Awards -->
	
    <?php $this->load->view("awards/nav_bar")?>

	<?php if ($wacral_all->num_rows() > 0) { ?>
	<table width="100%" class="zebra-striped">
		
	<tr>
		<td>Membership #</td>
		<td>Date/Time</td>
		<td>Callsign</td>
		<td>Band</td>
		<td>RST Sent</td>
		<td>RST Recvd</td>
	</tr>
	
	<?php
			foreach ($wacral_all->result() as $row) {
	?>
	
	<tr>
		<td>	
				<?php
						$pieces = explode(" ", $row->COL_COMMENT);
						foreach($pieces as $val) {
							if (strpos($val,'WACRAL:') !== false) {
								//echo $val;
								echo $rest = substr($val,7);  // returns "cde"
							}
						}
				?>
		</td>
		<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?> - <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
		<td><?php echo $row->COL_CALL; ?></td>
		<td><?php echo $row->COL_BAND; ?></td>
		<td><?php echo $row->COL_RST_SENT; ?></td>
		<td><?php echo $row->COL_RST_RCVD; ?></td>
	</tr>
	<?php } ?>
	
	</table>
	
	<?php } else { ?>
		<p>You have lot logged any <a href="http://www.wacral.org" target="_blank">WACRAL</a></p>
	<?php } ?>
</div>
