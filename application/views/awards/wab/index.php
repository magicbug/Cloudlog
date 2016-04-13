<div id="container">
	<h1><?php echo $page_title; ?></h1>

	<!-- Sub Nav for Awards -->
	
    <?php $this->load->view("awards/nav_bar")?>

	<table width="100%" class="zebra-striped">
		
	<tr>
		<td>Square</td>
		<td>Date/Time</td>
		<td>Callsign</td>
		<td>Band</td>
		<td>RST Sent</td>
		<td>RST Recvd</td>
	</tr>
	
	<?php
		if ($wab_all->num_rows() > 0) {
			foreach ($wab_all->result() as $row) {
	?>
	
	<tr>
		<td>	
				<?php
						$pieces = explode(" ", $row->COL_COMMENT);
						foreach($pieces as $val) {
							if (strpos($val,'WAB:') !== false) {
								//echo $val;
								echo $rest = substr($val, 4);  // returns "cde"
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
	<?php
		  }
		}
	?>
	
	</table>
</div>
