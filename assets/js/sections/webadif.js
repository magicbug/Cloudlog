$(function () {
    $('#datetimepicker5').datetimepicker({
        format: 'DD/MM/YYYY',
    });
});

$(function () {
    $('#datetimepicker6').datetimepicker({
        format: 'DD/MM/YYYY',
    });
});

$(document).ready(function(){
	$('#markWebAdifAsExported').click(function(e){
		let form = $(this).closest('form');
		let station = form.find('select[name=station_profile]');
		if (station.val() == 0) {
			station.addClass('is-invalid');
		}else{
			form.submit();
		}
	})
});

function ExportWebADIF(station_id) {
	if ($(".alert").length > 0) {
		$(".alert").remove();
	}
	if ($(".errormessages").length > 0) {
		$(".errormessages").remove();
	}
	$(".ld-ext-right-"+station_id).addClass('running');
	$(".ld-ext-right-"+station_id).prop('disabled', true);

	$.ajax({
		url: base_url + 'index.php/webadif/upload_station',
		type: 'post',
		data: {'station_id': station_id},
		success: function (data) {
			$(".ld-ext-right-"+station_id).removeClass('running');
			$(".ld-ext-right-"+station_id).prop('disabled', false);
			if (data.status == 'OK') {
				$.each(data.info, function(index, value){
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
