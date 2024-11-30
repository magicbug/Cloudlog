daysPerYear();
weekDays();
historyDays();

function daysPerYear() {
	$.ajax({
		url: base_url + 'index.php/dayswithqso/get_days',
		success: function (data) {
			if ($.trim(data)) {
				var labels = [];
				var dataDxcc = [];
				$.each(data, function () {
					labels.push(this.Year);
					dataDxcc.push(this.Days);
				});
				var ctx = document.getElementById("myChartDiff").getContext('2d');
				var color = ifDarkModeThemeReturn('white', 'grey');
				var myChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: lang_days_with_qso,
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
			}
		}
	});
}

function weekDays() {
	$.ajax({
		url: base_url + 'index.php/dayswithqso/get_weekdays',
		success: function (data) {
			if ($.trim(data)) {
				var labels = [];
				var dataDays = [];
				$.each(data, function () {
					labels.push(this.weekday);
					dataDays.push(this.qsos);
				});
				var ctx = document.getElementById("weekdaysChart").getContext('2d');
				var color = ifDarkModeThemeReturn('white', 'grey');
				var myChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: lang_qsos_this_weekday,
							data: dataDays,
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
			}
		}
	});
}

function historyDays() {
	$.ajax({
		url: base_url + 'index.php/dayswithqso/get_historydays',
		success: function (data) {
			if ($.trim(data)) {
				var labels = [];
				var dataDays = [];
				$.each(data, function () {
					labels.push(this.day);
					dataDays.push(this.qsos);
				});
				var ctx = document.getElementById("dailyChart").getContext('2d');
				var color = ifDarkModeThemeReturn('white', 'grey');
				var myChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: labels,
						datasets: [{
							label: lang_qsos_this_day,
							data: dataDays,
							pointRadius: 0,
							pointHoverRadius: 6,
							hitRadius: 4,
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
									color: color,
									precision: 0
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
			}
		}
	});
}
