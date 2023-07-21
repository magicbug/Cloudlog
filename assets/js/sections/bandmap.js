$(function() {
	(function(H) {
		H.seriesTypes.timeline.prototype.distributeDL = function() {
			var series = this,
				dataLabelsOptions = series.options.dataLabels,
				options,
				pointDLOptions,
				newOptions = {},
				visibilityIndex = 1,
				j = 2,
				distance;

			series.points.forEach(function(point, i) {
				distance = dataLabelsOptions.distance;

				if (point.visible && !point.isNull) {
					options = point.options;
					pointDLOptions = point.options.dataLabels;

					if (!series.hasRendered) {
						point.userDLOptions = H.merge({}, pointDLOptions);
					}

					/*
					if (i === j || i === j + 1) {
						distance = distance * 2.5

						if (i === j + 1) {
							j += 4
						}
					}
					*/
					if (i % 6 == 0) { distance = distance * 1; }
					if (i % 6 == 1) { distance = distance * -1; }
					if (i % 6 == 2) { distance = distance * 2; }
					if (i % 6 == 3) { distance = distance * -2; }
					if (i % 6 == 4) { distance = distance * 3; }
					if (i % 6 == 5) { distance = distance * -3; }

					newOptions[series.chart.inverted ? 'x' : 'y'] = distance;
					// newOptions[series.chart.inverted ? 'x' : 'y'] = dataLabelsOptions.alternate && (visibilityIndex % 3 != 0) ?  -distance : distance;

					options.dataLabels = H.merge(newOptions, point.userDLOptions);
					visibilityIndex++;
				}
			});
		}
	}(Highcharts));

	var bandMapChart;
	var color = ifDarkModeThemeReturn('white', 'grey');

	function render_chart (band,spot_data) {
		let chartObject=Highcharts.chart('bandmap', {
			chart: {
				type: 'timeline',
				zoomType: 'x',
				inverted: true,
				backgroundColor: getBodyBackground(),
				height: '800px'
			},
			accessibility: {
				screenReaderSection: {
					beforeChartFormat: '<h5>{chartTitle}</h5>' +
						'<div>{typeDescription}</div>' +
						'<div>{chartSubtitle}</div>' +
						'<div>{chartLongdesc}</div>' +
						'<div>{viewTableButton}</div>'
				},
				point: {
					valueDescriptionFormat: '{index}. {point.label}. {point.description}.'
				}
			},
			xAxis: {
				lineColor: color,
				visible: true,
				type: 'linear',
				labels: {
					style: {
						color: color,
					}
				}
			},
			yAxis: {
				visible: false,
			},
			title: {
				text: band,
				style: {
					color: color
				}
			},
			series: [ { data: spot_data } ]
		});
		return chartObject;
	}

				function SortByQrg(a, b){
					var a = a.frequency;
					var b = b.frequency;
					return ((a< b) ? -1 : ((a> b) ? 1 : 0));
				}

				function reduce_spots(spotobject) {
					let unique=[];
					spotobject.forEach((single) => {
						if (!spotobject.find((item) => ((item.spotted == single.spotted) && (item.frequency == single.frequency) && (Date.parse(item.when)>Date.parse(single.when))))) {
							unique.push(single);
						}
					});
					return unique;
				}

				function convert2high(spotobject) {
					let ret={};
					ret.name=spotobject.spotted;
					ret.x=spotobject.frequency;
					ret.description=spotobject.frequency + " / "+Math.round( (Date.now() - Date.parse(spotobject.when)) / 1000 / 60)+"min. ago";
					ret.dataLabels={};
					ret.dataLabels.alternate=true;
					ret.dataLabels.distance=200;
					return ret;
				}

				function update_chart(band,maxAgeMinutes) {
					let dxurl = dxcluster_provider + "/spots/" + band + "/" +maxAgeMinutes;
					$.ajax({
						url: dxurl,
						cache: false,
						dataType: "json"
					}).done(function(dxspots) {
						spots4chart=[];
						if (dxspots.length>0) {
							dxspots.sort(SortByQrg);
							dxspots=reduce_spots(dxspots);
							dxspots.forEach((single) => {
								spots4chart.push(convert2high(single));
							});
						}
						bandMapChart.title.text=band;
						bandMapChart.series[0].setData(spots4chart);
						bandMapChart.redraw();
					});
				}


				function set_chart(band,maxAgeMinutes) {
					let dxurl = dxcluster_provider + "/spots/" + band + "/" +maxAgeMinutes;
					$.ajax({
						url: dxurl, 
						cache: false,
						dataType: "json"
					}).done(function(dxspots) {
						spots4chart=[];
						if (dxspots.length>0) {
							dxspots.sort(SortByQrg);
							dxspots=reduce_spots(dxspots);
							dxspots.forEach((single) => {
								spots4chart.push(convert2high(single));
							});
						}
						bandMapChart=render_chart(band,spots4chart);
					});
				}

	set_chart($('#band option:selected').val(),30);
	setInterval(function () { update_chart($('#band option:selected').val(),30); },60000);
	$("#band").on("change",function() {
		set_chart($('#band option:selected').val(),30);
	});
});
