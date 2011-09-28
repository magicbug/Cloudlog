	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>

	<script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

	<script type="text/javascript">

		$(document).ready(function() {
			$(".qsobox").fancybox({
				'autoDimensions'	: false,
				'width'         	: 700,
				'height'        	: 300,
				'transitionIn'		: 'fade',
				'transitionOut'		: 'fade',
				'type'				: 'iframe'
			});


		});

	</script>

  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">

	  // Load the Visualization API and the piechart package.
	  google.load('visualization', '1', {'packages':['corechart']});

	  // Set a callback to run when the Google Visualization API is loaded.
	  google.setOnLoadCallback(drawModeChart);
	  google.setOnLoadCallback(drawBandChart);

	  // Callback that creates and populates a data table, 
	  // instantiates the pie chart, passes in the data and
	  // draws it.
	  function drawModeChart() {

	  // Create our data table.
	  var data = new google.visualization.DataTable();
	  data.addColumn('string', 'Topping');
	  data.addColumn('number', 'Slices');
	  data.addRows([
		['SSB', <?php echo $total_ssb; ?>],
		['CW', <?php echo $total_cw; ?>],
		['FM', <?php echo $total_fm; ?>], 
		['Digi', <?php echo $total_digi; ?>],
	  ]);

	  // Instantiate and draw our chart, passing in some options.
	  var chart = new google.visualization.PieChart(document.getElementById('modechart_div'));
	  chart.draw(data, {width: 280, height: 240, title: 'Total QSOs by Mode'});
	}
	
	 function drawBandChart() {

	  // Create our data table.
	  var data = new google.visualization.DataTable();
	  data.addColumn('string', 'Topping');
	  data.addColumn('number', 'Slices');
	  data.addRows([
		
		<?php foreach($total_bands->result() as $row) { ?>
			['<?php echo $row->band; ?>', <?php echo $row->count; ?>],
		<?php } ?>
		
	  ]);

	  // Instantiate and draw our chart, passing in some options.
	  var chart = new google.visualization.PieChart(document.getElementById('bandchart_div'));
	  chart.draw(data, {width: 280, height: 240, title: 'Total QSOs by Band'});
	}
	
	</script>

<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
	<div id="message" >
		<p>You have had <?php echo $todays_qsos; ?> QSOs Today!</p>
	</div>
<?php } ?>
<h2>Dashboard</h2>
<div class="wrap_content dashboard">

	<div id="map" style="width: 100%; height: 300px"></div> 


	<div id="dashboard_container">
		
		<div class="dashboard_top">
			
			<div class="dashboard_log">
				<table class="logbook" width="100%">
					<tr class="log_title titles">
						<td>Date</td>
						<td>Time</td>
						<td>Call</td>
						<td>Mode</td>
						<td>Sent</td>
						<td>Recv</td>
						<td>Band</td>
					</tr>

					<?php $i = 0; 
					foreach ($last_five_qsos->result() as $row) { ?>
						<?php  echo '<tr class="tr'.($i & 1).'">'; ?>
						<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?></td>
						<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
						<td><a class="qsobox" href="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>"><?php echo strtoupper($row->COL_CALL); ?></a></td>
						<td><?php echo $row->COL_MODE; ?></td>
						<td><?php echo $row->COL_RST_SENT; ?></td>
						<td><?php echo $row->COL_RST_RCVD; ?></td>
						<?php if($row->COL_SAT_NAME != null) { ?>
						<td>SAT</td>
						<?php } else { ?>
						<td><?php echo $row->COL_BAND; ?></td>
						<?php } ?>
					</tr>
					<?php $i++; } ?>

				</table>

			</div>
			
			<div class="dashboard_breakdown">
				<table width="100%">
					<tr class="dashboard_tr">
						<td class="item title" colspan="2">QSOs</td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Total </td>
						<td class="item"><?php echo $total_qsos; ?></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Year</td>
						<td class="item"><?php echo $year_qsos; ?></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Month</td>
						<td class="item"><?php echo $month_qsos; ?></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item"> </td>
						<td class="item"></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item title" colspan="2">Countrys</td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Worked</td>
						<td class="item"><?php echo $total_countrys; ?></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Needed</td>
						<td class="item"><?php $dxcc = 340 - $total_countrys; echo $dxcc; ?></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item"> </td>
						<td class="item"></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item title" colspan="2">QSL Cards</td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Sent</td>
						<td class="item"><?php echo $total_qsl_sent; ?></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Received</td>
						<td class="item"><?php echo $total_qsl_recv; ?></td>
					</tr>
					<tr class="dashboard_tr">
						<td class="item">Requested</td>
						<td class="item"><?php echo $total_qsl_requested; ?></td>
					</tr>
				</table>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<!-- <div class="dashboard_bottom">
			<div class="chart" id="modechart_div"></div>
			<div class="chart" id="bandchart_div"></div>
		</div> -->
		
	</div>

	<div class="clear"></div>

	<div class="dash_left">
	


	<script type="text/javascript"> 
	//<![CDATA[
	if (GBrowserIsCompatible()) {
	  // Display the map, with some controls
	  var map = new GMap(document.getElementById("map"));
	  map.addControl(new GLargeMapControl());
	  //map.addControl(new GMapTypeControl());
	  map.setCenter(new GLatLng(33.137551,0.703125),2);

	  // arrays to hold copies of the markers and html used by the side_bar
	  // because the function closure trick doesnt work there
	  var side_bar_html = "";
	  var gmarkers = [];
	  var htmls = [];
	  var i = 0;

	  // A function to create the marker and set up the event window
	  function createMarker(point,name,html) {
		var marker = new GMarker(point);
		GEvent.addListener(marker, "click", function() {
		  marker.openInfoWindowHtml(html);
		});
		// save the info we need to use later for the side_bar
		gmarkers[i] = marker;
		htmls[i] = html;
		// add a line to the side_bar html
		side_bar_html += '<a href="javascript:myclick(' + i + ')">' + name + '<\/a><br>';
		i++;
		return marker;
	  }

	  // This function picks up the click and opens the corresponding info window
	  function myclick(i) {
		gmarkers[i].openInfoWindowHtml(htmls[i]);
	  }

	  // ================================================================
	  // === Define the function thats going to process the JSON file ===
	  process_it = function(doc) {
		// === Parse the JSON document === 
		var jsonData = eval('(' + doc + ')');

		// === Plot the markers ===
		for (var i=0; i<jsonData.markers.length; i++) {
		  var marker = createMarker(jsonData.markers[i].point, jsonData.markers[i].label, jsonData.markers[i].html);
		  map.addOverlay(marker);
		}

		// put the assembled side_bar_html contents into the side_bar div
		document.getElementById("side_bar").innerHTML = side_bar_html;

		// === Plot the polylines ===
		for (var i=0; i<jsonData.lines.length; i++) {
		  map.addOverlay(new GPolyline(jsonData.lines[i].points, jsonData.lines[i].colour, jsonData.lines[i].width)); 
		}
	  }          

	  // ================================================================
	  // === Fetch the JSON data file ====    
	  GDownloadUrl("<?php echo site_url('/dashboard/map'); ?>", process_it);
	  // ================================================================

	}

	else {
	  alert("Sorry, the Google Maps API is not compatible with this browser");
	}

	// This Javascript is based on code provided by the
	// Community Church Javascript Team
	// http://www.bisphamchurch.org.uk/   
	// http://econym.org.uk/gmap/

	//]]>
	</script> 
	
	</div>

	
	<div class="clear"></div>
</div>