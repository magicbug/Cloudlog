$('#distplot_bands').change(function(){
	var band = $("#distplot_bands option:selected").text();
	if (band != "SAT") {
		$("#distplot_sats").prop('disabled', true);
		$("#distplot_sats_container").hide();
	} else {
		$("#distplot_sats").prop('disabled', false);
		$("#distplot_sats_container").show();
	}
});

// Auto-load chart on page load
$(document).ready(function() {
	distPlot();
});

function distPlot() {
	$(".alert").remove();
	
	// Show loading spinner
	$("#loading_spinner").removeClass('d-none');
	$("#plot").prop('disabled', true);
	
	var baseURL= "<?php echo base_url();?>";
	$.ajax({
		url: base_url+'index.php/distances/get_distances',
		type: 'post',
		data: {
			'band': $("#distplot_bands").val(),
			'sat': $("#distplot_sats").val(),
			'mode': $("#distplot_modes").val(),
			'pwr': $("#distplot_powers").val(),
			'propag': $("#distplot_propag").val()
		},
		success: function(tmp) {
			// Hide loading spinner
			$("#loading_spinner").addClass('d-none');
			$("#plot").prop('disabled', false);
			
			if (tmp.ok == 'OK') {
				// Create containers if they don't exist
				if (!($('#stats_container').length > 0)) {
					$("#distances_div").append('<div id="stats_container" class="mt-3"></div>');
				}
				if (!($('#graphcontainer').length > 0)) {
					$("#distances_div").append('<div class="card mt-3"><div class="card-body"><div id="graphcontainer" style="height: 600px;"></div></div></div>');
				}
				var color = ifDarkModeThemeReturn('white', 'grey');
				var options = {
					chart: {
						type: 'column',
						zoomType: 'xy',
						renderTo: 'graphcontainer',
						backgroundColor: getBodyBackground()
					},
					title: {
						text: lang_statistics_distances_worked,
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
							text: lang_statistics_distances_number_of_qsos,
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
								return lang_gen_hamradio_distance + ": " + options.xAxis.categories[this.point.x] +
									"<br />" + lang_statistics_distances_callsigns_worked + ": " + myComments[this.point.x] +
									"<br />" + lang_statistics_distances_number_of_qsos + ": <strong>" + series.data[this.point.x] + "</strong>";
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
					showInNavigator: true,
					point: {
						events: {
							click: function () {
								getDistanceQsos(this.category);

							}
						}
					}
				};

				$.each(tmp.qsodata, function(){
					myComments.push(this.calls);
					options.xAxis.categories.push(this.dist);
					series.name = lang_statistics_distances_number_of_qsos;
					series.data.push(this.count);
				});

				options.series.push(series);

				// Create metric cards for statistics
				var statsHtml = '<div class="row g-3">' +
					'<div class="col-md-3">' +
						'<div class="card text-center">' +
							'<div class="card-body">' +
								'<h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-satellite-dish"></i> Contacts Plotted</h6>' +
								'<h3 class="card-title mb-0">' + tmp.qrb.Qsos.toLocaleString() + '</h3>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div class="col-md-3">' +
						'<div class="card text-center">' +
							'<div class="card-body">' +
								'<h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-ruler"></i> Furthest Distance</h6>' +
								'<h3 class="card-title mb-0">' + tmp.qrb.Distance + ' ' + tmp.unit + '</h3>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div class="col-md-3">' +
						'<div class="card text-center">' +
							'<div class="card-body">' +
								'<h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-trophy"></i> Furthest Contact</h6>' +
								'<h3 class="card-title mb-0">' + tmp.qrb.Callsign + '</h3>' +
								'<small class="text-muted">' + tmp.qrb.Grid + '</small>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div class="col-md-3">' +
						'<div class="card text-center">' +
							'<div class="card-body">' +
								'<h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-chart-line"></i> Average Distance</h6>' +
								'<h3 class="card-title mb-0">' + tmp.qrb.Avg_distance + ' ' + tmp.unit + '</h3>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>';
				
				$('#stats_container').html(statsHtml);

				var chart = new Highcharts.Chart(options);
			}
			else {
				if (($('#stats_container').length > 0)) {
					$("#stats_container").remove();
					$("#graphcontainer").parent().parent().remove();
				}
				$("#distances_div").append('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + tmp.Error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
			}
		},
		error: function() {
			// Hide loading spinner on error
			$("#loading_spinner").addClass('d-none');
			$("#plot").prop('disabled', false);
			$("#distances_div").append('<div class="alert alert-danger alert-dismissible fade show" role="alert">An error occurred while loading the distances data.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
		}
	});
}

function getDistanceQsos(distance) {
	// alert('Category: ' + distance);
	$.ajax({
		url: base_url + 'index.php/distances/getDistanceQsos',
		type: 'post',
		data: {
			'distance': distance,
			'band': $("#distplot_bands").val(),
			'sat' : $("#distplot_sats").val(),
			'mode': $("#distplot_modes").val(),
			'pwr': $("#distplot_powers").val(),
			'propag': $("#distplot_propag").val(),
		},
		success: function (html) {
			BootstrapDialog.show({
				title: lang_general_word_qso_data,
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'qso-dialog',
				nl2br: false,
				message: html,
				onshown: function(dialog) {
				   $('[data-bs-toggle="tooltip"]').tooltip();
				   $('.contacttable').DataTable({
						"pageLength": 25,
						responsive: false,
						ordering: false,
						"scrollY":        "550px",
						"scrollCollapse": true,
						"paging":         false,
						"scrollX": true,
						"language": {
							url: getDataTablesLanguageUrl(),
						},
						dom: 'Bfrtip',
						buttons: [
							'csv'
						]
					});
                    $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function () {
                        showQsoActionsMenu($(this).closest('.dropdown'));
                    });
				},
				buttons: [{
					label: lang_admin_close,
					action: function (dialogItself) {
						dialogItself.close();
					}
				}]
			});
		}
	});
}
