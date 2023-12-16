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
				$(".card-body").append('<div class="alert alert-danger" role="alert">' + data.info + '</div>');
			}

			if (data.errormessages.length > 0) {
				$("#adif_import").append(
					'<div class="errormessages">\n' +
					'    <div class="card mt-2">\n' +
					'        <div class="card-header bg-danger">\n' +
					'            Error Message\n' +
					'        </div>\n' +
					'        <div class="card-body">\n' +
					'            <div class="errors"></div>\n' +
					'        </div>\n' +
					'    </div>\n' +
					'</div>'
				);
				$.each(data.errormessages, function (index, value) {
					$(".errors").append('<li>' + value);
				});
			}
		}
	});
}
