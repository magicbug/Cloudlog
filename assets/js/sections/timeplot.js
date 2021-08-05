function timeplot(form) {
	$(".ld-ext-right").addClass('running');
	$(".ld-ext-right").prop('disabled', true);
	$(".alert").remove();
	$.ajax({
		url: base_url+'index.php/timeplotter/getTimes',
		type: 'post',
		data: {'band': form.band.value, 'dxcc': form.dxcc.value, 'cqzone': form.cqzone.value},
		success: function(tmp) {
			$(".ld-ext-right").removeClass('running');
			$(".ld-ext-right").prop('disabled', false);
			if (tmp.ok == 'OK') {
				plotTimeplotterChart(tmp);
			}
			else {
				$("#container").remove();
				$("#info").remove();
				$("#timeplotter_div").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n' +
					tmp.error +
					'</div>');
			}
		}
	});
}

function plotTimeplotterChart(tmp) {
	$("#container").remove();
	$("#info").remove();
	$("#timeplotter_div").append('<p id="info">' + tmp.qsocount + ' contacts were plotted.</p><div id="container" style="height: 600px;"></div>');
	var options = {
		chart: {
			type: 'column',
			zoomType: 'xy',
			renderTo: 'container'
		},
		title: {
			text: 'Time Distribution'
		},
		xAxis: {
			categories: [],
			crosshair: true,
			type: "category",
			min:0,
			max:47,
		},
		yAxis: {
			title: {
				text: '# QSOs'
			}
		},
		rangeSelector: {
			selected: 1
		},
		tooltip: {
			formatter: function () {
				if(this.point) {
					return "Time: " + options.xAxis.categories[this.point.x] +
						"<br />Callsign(s) worked (max 5): " + myComments[this.point.x] +
						"<br />Number of QSOs: <strong>" + series.data[this.point.x] + "</strong>";
				}
			}
		},
		series: []
	};
	var myComments=[];

	var series = {
		data: []
	};

	$.each(tmp.qsodata, function(){
		myComments.push(this.calls);
		options.xAxis.categories.push(this.time);
		series.name = 'Number of QSOs';
		series.data.push(this.count);
	});

	options.series.push(series);

	var chart = new Highcharts.Chart(options);
}
