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
					if ((band != '') && (band !== undefined)) {
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
				}


				function set_chart(band, de, maxAgeMinutes) {
					if ((band != '') && (band !== undefined)) {
					let dxurl = dxcluster_provider + "/spots/" + band + "/" +maxAgeMinutes + "/" + de;
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
				}

	$("#menutoggle").on("click", function() {
		if ($('.navbar').is(":hidden")) {
			$('.navbar').show();
			$('#dxtabs').show();
			$('#dxtitle').show();
			$('#menutoggle_i').removeClass('fa-arrow-down');
			$('#menutoggle_i').addClass('fa-arrow-up');
		} else {
			$('.navbar').hide();
			$('#dxtabs').hide();
			$('#dxtitle').hide();
			$('#menutoggle_i').removeClass('fa-arrow-up');
			$('#menutoggle_i').addClass('fa-arrow-down');
		}
	});

	set_chart($('#band option:selected').val(), $('#decontSelect option:selected').val(), dxcluster_maxage);
	setInterval(function () { update_chart($('#band option:selected').val(),dxcluster_maxage); },60000);
	$("#band").on("change",function() {
		set_chart($('#band option:selected').val(), $('#decontSelect option:selected').val(), dxcluster_maxage);
	});

	$("#decontSelect").on("change",function() {
		set_chart($('#band option:selected').val(), $('#decontSelect option:selected').val(), dxcluster_maxage);
	});
});

var updateFromCAT = function() {
	if($('select.radios option:selected').val() != '0') {
		radioID = $('select.radios option:selected').val();
		$.getJSON( base_url + "index.php/radio/json/" + radioID, function( data ) {

			if (data.error) {
				if (data.error == 'not_logged_in') {
					$(".radio_cat_state" ).remove();
					if($('.radio_login_error').length == 0) {
						$('.messages').prepend('<div class="alert alert-danger radio_login_error" role="alert"><i class="fas fa-broadcast-tower"></i> You\'re not logged it. Please <a href="'+base_url+'">login</a></div>');
					}
				}
				// Put future Errorhandling here
			} else {
				if($('.radio_login_error').length != 0) {
					$(".radio_login_error" ).remove();
				}
				var band = frequencyToBand(data.frequency);

				if (band !== $("#band").val()) {
					$("#band").val(band);
					$("#band").trigger("change");
				}

				var minutes = Math.floor(cat_timeout_interval / 60);

				if(data.updated_minutes_ago > minutes) {
					$(".radio_cat_state" ).remove();
					if($('.radio_timeout_error').length == 0) {
						$('.messages').prepend('<div class="alert alert-danger radio_timeout_error" role="alert"><i class="fas fa-broadcast-tower"></i> Radio connection timed-out: ' + $('select.radios option:selected').text() + ' data is ' + data.updated_minutes_ago + ' minutes old.</div>');
					} else {
						$('.radio_timeout_error').html('Radio connection timed-out: ' + $('select.radios option:selected').text() + ' data is ' + data.updated_minutes_ago + ' minutes old.');
					}
				} else {
					$(".radio_timeout_error" ).remove();
					text = '<i class="fas fa-broadcast-tower"></i><span style="margin-left:10px;"></span><b>TX:</b> '+(Math.round(parseInt(data.frequency)/100)/10000).toFixed(4)+' MHz';
					if(data.mode != null) {
						text = text+'<span style="margin-left:10px"></span>'+data.mode;
					}
					if(data.power != null && data.power != 0) {
						text = text+'<span style="margin-left:10px"></span>'+data.power+' W';
					}
					if (! $('#radio_cat_state').length) {
						$('.messages').prepend('<div aria-hidden="true"><div id="radio_cat_state" class="alert alert-success radio_cat_state" role="alert">'+text+'</div></div>');
					} else {
						$('#radio_cat_state').html(text);
					}
				}
			}
		});
	}
};

// Update frequency every three second
setInterval(updateFromCAT, 3000);

// If a radios selected from drop down select radio update.
$('.radios').change(updateFromCAT);
