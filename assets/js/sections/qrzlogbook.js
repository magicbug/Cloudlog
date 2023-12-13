function ExportQrz(station_id) {
	if ($(".alert").length > 0) {
		$(".alert").remove();
	}
	if ($(".errormessages").length > 0) {
		$(".errormessages").remove();
	}
	$(".ld-ext-right-"+station_id).addClass('running');
	$(".ld-ext-right-"+station_id).prop('disabled', true);

	$.ajax({
		url: base_url + 'index.php/qrz/upload_station',
		type: 'post',
		data: {'station_id': station_id},
		success: function (data) {
			$(".ld-ext-right-"+station_id).removeClass('running');
			$(".ld-ext-right-"+station_id).prop('disabled', false);
			if (data.status == 'OK') {
				$.each(data.info, function(index, value){
					$('#modcount'+value.station_id).html(value.modcount);
					$('#notcount'+value.station_id).html(value.notcount);
					$('#totcount'+value.station_id).html(value.totcount);
				});
				$(".card-body").append('<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data.infomessage + '</div>');
			}
			else {
				$(".card-body").append('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data.info + '</div>');
			}

			if (data.errormessages.length > 0) {
				$(".card-body").append('' +
					'<div class="errormessages"><p>\n' +
					'                            <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">\n' +
					'                                Show error messages\n' +
					'                            </button>\n' +
					'                            </p>\n' +
					'                            <div class="collapse" id="collapseExample">\n' +
					'                                <div class="card card-body"><div class="errors"></div>\n' +
					'                            </div>\n' +
					'                            </div></div>');
				$.each(data.errormessages, function(index, value) {
					$(".errors").append('<li>' + value);
				});
			}
		}
	});
}
