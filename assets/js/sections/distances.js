$('#distplot_bands').change(function(){
	var band = $("#distplot_bands option:selected").text();
	if (band != "SAT") {
		$("#distplot_sats").prop('disabled', true);
	} else {
		$("#distplot_sats").prop('disabled', false);
	}
});

function distPlot(form) {
	$(".alert").remove();
	var baseURL= "<?php echo base_url();?>";
	$.ajax({
		url: base_url+'index.php/distances/get_distances',
		type: 'post',
		data: {'band': form.distplot_bands.value,
			'sat': form.distplot_sats.value},
		success: function(tmp) {
			if (tmp.ok == 'OK') {
				if (!($('#information').length > 0))
					$("#distances_div").append('<div id="information"></div><div id="graphcontainer" style="height: 600px; margin: 0 auto"></div>');
				var color = ifDarkModeThemeReturn('white', 'grey');
				var options = {
					chart: {
						type: 'column',
						zoomType: 'xy',
						renderTo: 'graphcontainer',
						backgroundColor: getBodyBackground()
					},
					title: {
						text: 'Distance Distribution',
						style: {
							color: color
						}
					},
					xAxis: {
						categories: [],
						crosshair: true,
						type: "category",
						min:0,
						max:100,
						labels: {
							style: {
								color: color
							}
						}
					},
					yAxis: {
						title: {
							text: '# QSOs',
							style: {
								color: color
							}
						},
						labels: {
							style: {
								color: color
							}
						}
					},
					navigator: {
						enabled: true,
						xAxis: {
							labels: {
								formatter: function() {
									return this.value * '50' + ' ' + tmp.unit;
								},
								style: {
									color: color
								}
							}
						}
					},
					rangeSelector: {
						selected: 1
					},
					tooltip: {
						formatter: function () {
							if(this.point) {
								return "Distance: " + options.xAxis.categories[this.point.x] +
									"<br />Callsign(s) worked (max 5 shown): " + myComments[this.point.x] +
									"<br />Number of QSOs: <strong>" + series.data[this.point.x] + "</strong>";
							}
						}
					},
					legend: {
						itemStyle: {
							color: color
						}
					},
					series: []
				};
				var myComments=[];

				var series = {
					data: [],
					showInNavigator: true
				};

				$.each(tmp.qsodata, function(){
					myComments.push(this.calls);
					options.xAxis.categories.push(this.dist);
					series.name = 'Number of QSOs';
					series.data.push(this.count);
				});

				options.series.push(series);

				$('#information').html(tmp.qrb.Qsos + " contacts were plotted.<br /> Your furthest contact was with " + tmp.qrb.Callsign
					+ " in gridsquare "+ tmp.qrb.Grid
					+"; the distance was "
					+tmp.qrb.Distance + tmp.unit +".");

				var chart = new Highcharts.Chart(options);
			}
			else {
				if (($('#information').length > 0)) {
					$("#information").remove();
					$("#graphcontainer").remove();
				}
				$("#distances_div").append('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + tmp.Error + '</div>');
			}
		}
	});
}
