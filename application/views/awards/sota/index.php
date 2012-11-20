<div id="container">
	<h1><?php echo $page_title; ?></h1>
	
	<!-- Sub Nav for Awards -->
	
	<ul class="tabs">
	  <li><a href="<?php echo site_url('awards/dxcc'); ?>">DXCC</a></li>
	  <li><a href="<?php echo site_url('awards/wab'); ?>">WAB</a></li>
	  <li class="active"><a href="<?php echo site_url('awards/sota'); ?>">SOTA</a></li>
	  <li><a href="<?php echo site_url('awards/wacral'); ?>">WACRAL</a></li>
	</ul>
	
	<table width="100%" class="zebra-striped">
		
	<tr>
		<td>Reference</td>
		<td>Date/Time</td>
		<td>Callsign</td>
		<td>Band</td>
		<td>RST Sent</td>
		<td>RST Recvd</td>
	</tr>
	
	<?php
		if ($sota_all->num_rows() > 0) {
			foreach ($sota_all->result() as $row) {
	?>
	
	<tr>
		<td>	
				<?php
						$pieces = explode(" ", $row->COL_COMMENT);
						foreach($pieces as $val) {
							if (strpos($val,'SOTA:') !== false) {
								//echo $val;
								echo $rest = substr($val, 5);  // returns "cde"
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