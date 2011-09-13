  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">

	  // Load the Visualization API and the piechart package.
	  google.load('visualization', '1', {'packages':['corechart']});

	  // Set a callback to run when the Google Visualization API is loaded.
	  google.setOnLoadCallback(drawModeChart);
	  google.setOnLoadCallback(drawBandChart);
	  google.setOnLoadCallback(drawSatChart);


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
	   chart.draw(data, {width: 700, height: 440});
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
	  chart.draw(data, {width: 700, height: 440});
	}
	
	function drawSatChart() {

	  // Create our data table.
	  var data = new google.visualization.DataTable();
	  data.addColumn('string', 'Topping');
	  data.addColumn('number', 'Slices');
	  data.addRows([

		<?php foreach($total_sat->result() as $row1) { ?>
			<?php if($row1->COL_SAT_NAME != null) { ?>
			['<?php echo $row1->COL_SAT_NAME; ?>', <?php echo $row1->count; ?>],
			<?php } ?>
		<?php } ?>

	  ]);

	  // Instantiate and draw our chart, passing in some options.
	  var chart = new google.visualization.PieChart(document.getElementById('satchart_div'));
	  chart.draw(data, {width: 700, height: 440});
	}

	</script>



<h2><?php echo $page_title; ?></h2>

<div class="wrap_content note">

<p>Statistics built using information from the logbook.</p>

		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Main</a></li>
				<li><a href="#tabs-2">Satellite</a></li>
			</ul>
			<div id="tabs-1"><div id="modechart_div"></div> <div id="bandchart_div"></div></div>
			<div id="tabs-2"><div id="satchart_div"></div></div>
		</div>
</div>