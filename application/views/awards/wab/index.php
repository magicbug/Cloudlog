<div class="container">

	<h2><?php echo $page_title; ?></h2>

	<table class="table table-sm table-striped table-hover">
		
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
