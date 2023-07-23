$(function() {

	function SortByQrg(a, b){
		var a = a.frequency;
		var b = b.frequency;
		return ((a< b) ? -1 : ((a> b) ? 1 : 0));
	}


	function fill_list(band,maxAgeMinutes) {
		let dxurl = dxcluster_provider + "/spots/" + band + "/" +maxAgeMinutes;
		$.ajax({
			url: dxurl,
			cache: false,
			dataType: "json"
		}).done(function(dxspots) {
			var table = $('.spottable').DataTable();
			table.clear();
			table.page.len(50);
			table.order([1, 'asc']);
			if (dxspots.length>0) {
				dxspots.sort(SortByQrg);
				dxspots.forEach((single) => {
					// var data = [[ single.when_pretty, single.frequency, single.spotted, single.dxcc_spotted.call ]];
					var data=[];
					data[0]=[];
					data[0].push(single.when_pretty);
					data[0].push(single.frequency);
					data[0].push((single.worked_call ?'<span class="text-success">' : '')+single.spotted+(single.worked_call ? '</span>' : ''));
					data[0].push(single.dxcc_spotted.entity);
					table.rows.add(data).draw();
					// add to datatable single
				});
			}
		});
	}

	fill_list($('#band option:selected').val(),30);
	setInterval(function () { fill_list($('#band option:selected').val(),30); },60000);
	$("#band").on("change",function() {
		fill_list($('#band option:selected').val(),30);
	});

	
	var updateFromCAT = function() {
	if($('select.radios option:selected').val() != '0') {
		radioID = $('select.radios option:selected').val();
		$.getJSON( "radio/json/" + radioID, function( data ) {

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

$.fn.dataTable.moment(custom_date_format + ' HH:mm');
// Update frequency every three second
// setInterval(updateFromCAT, 3000);

// If a radios selected from drop down select radio update.
$('.radios').change(updateFromCAT);

});


