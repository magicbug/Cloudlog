<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>View QSO Info</title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/reset.css" type="text/css" />
	<style type="text/css" media="screen">
		body { font-family: Arial, "Trebuchet MS", sans-serif; }
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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 

						<script type="text/javascript"> 
  function initialize() {
	var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
	var myOptions = {
	  zoom: 4,
	  center: myLatlng,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	var marker = new google.maps.Marker({
		position: myLatlng, 
		map: map,
		title:"Hello World!"
	});   
  }
</script> 
</head>

<body onload="initialize()">
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
					<td><?php echo $row->COL_RST_SENT; ?></td>
				</tr>
				
				<tr>
					<td>RST Recv</td>
					<td><?php echo $row->COL_RST_RCVD; ?></td>
				</tr>
				
				<?php if($row->COL_GRIDSQUARE != null) { ?>
				<tr>
					<td>QRA</td>
					<td><?php echo $row->COL_GRIDSQUARE; ?></td>
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
		</div>
		
		<div id="stat">

<div id="map_canvas" style="width: 420px; height: 300px"></div> 

<?php

	if($row->COL_GRIDSQUARE != null) {
				$stn_loc = qra2latlong($row->COL_GRIDSQUARE);
				
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

						<script type="text/javascript"> 
  function initialize() {
	var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
	var myOptions = {
	  zoom: 4,
	  center: myLatlng,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

	var marker = new google.maps.Marker({
		position: myLatlng, 
		map: map,
		title:"<?php echo $row->COL_CALL; ?>"
	});   
  }
</script> 

		</div>
	</div>
<?php } } ?>
</body>
</html>