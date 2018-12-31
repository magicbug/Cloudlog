<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>View QSO Info</title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/reset.css" type="text/css" />
	<style type="text/css" media="screen">
		body { font-family: Arial, "Trebuchet MS", sans-serif; font-size: 12px;}
		h1 { font-weight: bold; font-size: 23px; margin-top: 5px; margin-bottom: 10px; }
		
		h2 { font-weight: bold; font-size: 18px; margin-top: 5px; margin-bottom: 10px; }
		
		h3 { font-weight: bold; font-size: 14px; margin-top: 10px; margin-bottom: 10px; }
		
		.clear { clear: both }
		#info { float: left; width: 50%; }
		#stat { float: right; width: 50%; }
		td { padding: 5px; }
		p {
line-height: 1.7;
margin: 10px 0;
}
	</style>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.5.1.min.js"></script>	
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/leaflet/leaflet.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>js/leaflet/leaflet.js"></script>
</head>

<body>
<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) {
?>
	<h1>QSO with <?php echo $row->COL_CALL; ?> on the <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></h1>
	
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
				
				<?php if($this->config->item('display_freq') == true) { ?>
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
					<td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX_STRING) { ?>(<?php echo $row->COL_STX_STRING;?>)<?php } ?></td>
				</tr>
				
				<tr>
					<td>RST Recv</td>
					<td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX_STRING) { ?>(<?php echo $row->COL_SRX_STRING;?>)<?php } ?></td>
				</tr>
				
				<?php if($row->COL_GRIDSQUARE != null) { ?>
				<tr>
					<td>Gridsquare</td>
					<td><?php echo $row->COL_GRIDSQUARE; ?></td>
				</tr>
				<?php } ?>

				<?php if($row->COL_VUCC_GRIDS != null) { ?>
				<tr>
					<td>Gridsquare (Multi)</td>
					<td><?php echo $row->COL_VUCC_GRIDS; ?></td>
				</tr>
				<?php } ?>
				
				
				<?php if($row->COL_NAME != null) { ?>
				<tr>
					<td>Name</td>
					<td><?php echo $row->COL_NAME; ?></td>
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
					<td>Sat Mode:</td>
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
			<?php if($row->COL_QSL_SENT == "Y" || $row->COL_QSL_RCVD == "Y") { ?>
				<h3>QSL Info</h3>
				
				<?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "B") { ?>
				<p>QSL Card has been sent via the bureau</p>
				<?php } ?>
				<?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "D") { ?>
				<p>QSL Card has been sent direct</p>
				<?php } ?>
				
				<?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "B") { ?>
				<p>QSL Card has been received via the bureau</p>
				<?php } ?>
				<?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "D") { ?>
				<p>QSL Card has been received direct</p>
				<?php } ?>
			<?php } ?>
				
				<?php if($row->COL_LOTW_QSL_RCVD == "Y") { ?>
				<h3>LoTW</h3>
					<p>This QSO is confirmed on Lotw</p>
				<?php } ?>
		</div>
		
		<div id="stat">

<div id="map" style="width: 340px; height: 250px"></div> 

<?php
	if($row->COL_GRIDSQUARE != null) {
		$stn_loc = $this->qra->qra2latlong(trim($row->COL_GRIDSQUARE));			
		$lat = $stn_loc[0];
		$lng = $stn_loc[1];
	} else {
		$query = $this->db->query('
			SELECT *
			FROM dxcc
			WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
			ORDER BY LENGTH( prefix ) DESC
			LIMIT 1 
		');

		foreach ($query->result() as $dxcc) {
			$lat = $dxcc->lat;
			$lng = $dxcc->long;
		}
	}
?>


<script>

	var mymap = L.map('map').setView([<?php echo $lat; ?>,<?php echo $lng; ?>], 5);

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>, ' +
			'Generated by <a href="http://www.cloudlog.co.uk/">Cloudlog</a>',
		id: 'mapbox.streets'
	}).addTo(mymap);

	L.marker([<?php echo $lat; ?>,<?php echo $lng; ?>]).addTo(mymap)
		.bindPopup("<?php echo $row->COL_CALL; ?>");

	mymap.on('click', onMapClick);

</script>

		</div>
	</div>
<?php } } ?>
</body>
</html>
