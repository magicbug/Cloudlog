<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>View QSO Info</title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/reset.css" type="text/css" />
	<style type="text/css" media="screen">
		body { font-family: Arial, "Trebuchet MS", sans-serif; }
		h1 { font-weight: bold; font-size: 23px; margin-top: 5px; margin-bottom: 10px; }
		
		#info { float: left; width: 50%; }
		#stat { float: right; width: 50%; }
		td { padding: 5px; }
	</style>
</head>

<body>
<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) {
?>
	<h1>QSO Information for <?php echo $row->COL_CALL; ?></h1>
	
	<div id="wrap">
		<div id="info">
			<table width="100%">
				<tr>
					<td>Date/Time</td>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></td>
				</tr>
				
				<tr>
					<td>Callsign</td>
					<td><?php echo $row->COL_CALL; ?></td>
				</tr>
				
				<tr>
					<td>Band</td>
					<td><?php echo $row->COL_BAND; ?></td>
				</tr>
				
				<?php if($row->COL_FREQ != null) { ?>
				<tr>
					<td>Freq:</td>
					<td><?php echo $row->COL_FREQ; ?></td>
				</tr>
				<?php } ?>
				
				<tr>
					<td>Mode</td>
					<td><?php echo $row->COL_MODE; ?></td>
				</tr>
				
				<tr>
					<td>RST Sent</td>
					<td><?php echo $row->COL_RST_SENT; ?></td>
				</tr>
				
				<tr>
					<td>RST Recv</td>
					<td><?php echo $row->COL_RST_RCVD; ?></td>
				</tr>
				
				<?php if($row->COL_GRIDSQUARE != null) { ?>
				<tr>
					<td>Comment</td>
					<td><?php echo $row->COL_GRIDSQUARE; ?></td>
				</tr>
				<?php } ?>
				
				<?php if($row->COL_COMMENT != null) { ?>
				<tr>
					<td>Comment</td>
					<td><?php echo $row->COL_COMMENT; ?></td>
				</tr>
				<?php } ?>
				
				<?php if($row->COL_SAT_NAME != null) { ?>
				<tr>
					<td>Sat Name:</td>
					<td><?php echo $row->COL_SAT_NAME; ?></td>
				</tr>
				<?php } ?>
				
				<?php if($row->COL_SAT_MODE != null) { ?>
				<tr>
					<td>Sat Name:</td>
					<td><?php echo $row->COL_SAT_MODE; ?></td>
				</tr>
				<?php } ?>
				<?php if($row->COL_COUNTRY != null) { ?>
				<tr>
					<td>Country:</td>
					<td><?php echo $row->COL_COUNTRY; ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>
		
		<div id="stat">
			
		</div>
	</div>
<?php } } ?>
</body>

</html>