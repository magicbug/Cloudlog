function accumulatePlot(form) {
	$(".ld-ext-right").addClass('running');
	$(".ld-ext-right").prop('disabled', true);

	// using this to change color of legend and label according to background color
	var color = ifDarkModeThemeReturn('white', 'grey');

	var award = form.awardradio.value;
	var mode = form.mode.value;
	var period = form.periodradio.value;
	$.ajax({
		url: base_url + 'index.php/accumulated/get_accumulated_data',
		type: 'post',
		data: { 'Band': form.band.value, 'Award': award, 'Mode': mode, 'Period': period },
		success: function (data) {
			if (!$.trim(data)) {
				$("#accumulateContainer").empty();
				$("#accumulateContainer").append('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>');
				$(".ld-ext-right").removeClass('running');
				$(".ld-ext-right").prop('disabled', false);
			}
			else {
				// used for switching award text in the table and the chart
				switch (award) {
					case 'dxcc': var awardtext = "DXCC\'s"; break;
					case 'was': var awardtext = "states"; break;
					case 'iota': var awardtext = "IOTA\'s"; break;
					case 'waz': var awardtext = "CQ zones"; break;
				}

				var periodtext = 'Year';
				if (period == 'month') {
					periodtext += ' + month';
				}
				// removing the old chart so that it will not interfere when loading chart again
				$("#accumulateContainer").empty();
				$("#accumulateContainer").append("<canvas id=\"myChartAccumulate\" width=\"400\" height=\"150\"></canvas><div id=\"accumulateTable\"></div>");

				// appending table to hold the data
				$("#accumulateTable").append('<table style="width:100%" class="accutable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>' +
					'<tr>' +
					'<td>#</td>' +
					'<td>' + periodtext + '</td>' +
					'<td>Accumulated # of ' + awardtext + ' worked </td>' +
					'</tr>' +
					'</thead>' +
					'<tbody></tbody></table>');
				var labels = [];
				var dataDxcc = [];

				var $myTable = $('.accutable');
				var i = 1;

				// building the rows in the table
				var rowElements = data.map(function (row) {

					var $row = $('<tr></tr>');

					var $iterator = $('<td></td>').html(i++);
					var $type = $('<td></td>').html(row.year);
					var $content = $('<td></td>').html(row.total);

					$row.append($iterator, $type, $content);

					return $row;
				});

				// finally inserting the rows
				$myTable.append(rowElements);

				$.each(data, function () {
					labels.push(this.year);
					dataDxcc.push(this.total);
				});

				var ctx = document.getElementById("myChartAccumulate").getContext('2d');
				var myChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: 'Accumulated number of ' + awardtext + ' worked each ' + period,
							data: dataDxcc,
							backgroundColor: 'rgba(54, 162, 235, 0.2)',
							borderColor: 'rgba(54, 162, 235, 1)',
							borderWidth: 2,
							color: color
						}]
					},
					options: {
						scales: {
							y: {
								ticks: {
									beginAtZero: true,
									color: color
								}
							},
							x: {
								ticks: {
									color: color
								}
							}
						},
						plugins: {
							legend: {
								labels: {
									color: color
								}
							}
						}
					}
				});
				$(".ld-ext-right").removeClass('running');
				$(".ld-ext-right").prop('disabled', false);
				$('.accutable').DataTable({
					responsive: false,
					ordering: false,
					"scrollY": "400px",
					"scrollCollapse": true,
					"paging": false,
					"scrollX": true,
					dom: 'Bfrtip',
					buttons: [
						'csv'
					]
				});

				// using this to change color of csv-button if dark mode is chosen
				var background = $('body').css("background-color");

				if (background != ('rgb(255, 255, 255)')) {
					$(".buttons-csv").css("color", "white");
				}
			}
		}
	});
}
