  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">

	  // Load the Visualization API and the piechart package.
	  google.load('visualization', '1', {'packages':['corechart']});

	  // Set a callback to run when the Google Visualization API is loaded.
	  google.setOnLoadCallback(drawModeChart);
	  google.setOnLoadCallback(drawBandChart);
	  google.setOnLoadCallback(drawSatChart);
	  google.setOnLoadCallback(drawQSLChart);


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
	<script type="text/javascript">
	  google.setOnLoadCallback(barchart);
	  function barchart() {
		var data = google.visualization.arrayToDataTable([
		  ['Year', 'QSO'],
		  <?php foreach($totals_year->result() as $qso_numbers) { ?>
		  ['<?php echo $qso_numbers->year; ?>',  <?php echo $qso_numbers->total; ?>],
		  <?php } ?>
		]);

		var options = {
		  title: 'Total QSOs Per Year',
		  vAxis: {title: 'QSOs',  titleTextStyle: {color: 'black'}},
		  hAxis: {title: 'Year', titleTextStyle: {color: 'black'}}

		};

		var chart = new google.visualization.ColumnChart(document.getElementById('totals_year'));
		chart.draw(data, options);
	  }
	</script>

<div id="container">

	<h2><?php echo $page_title; ?></h2>

	<ul class="tabs">
	  <li class="active"><a href="#home">General</a></li>
	  <li><a href="#space">Satellite Contacts</a></li>
	  <li><a href="statistics/custom">Custom</a></li>
	</ul>
	 
	<div class="pill-content">
	  <div class="active" id="home">
	<div id="totals_year" style="width: 900px; height: 500px;"></div><div id="modechart_div"></div> <div id="bandchart_div"></div></div>
	  <div id="space"><div id="satchart_div"></div></div>
	</div>

</div>
