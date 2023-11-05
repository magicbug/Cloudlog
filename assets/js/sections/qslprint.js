function deleteFromQslQueue(id) {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to removes this QSL from the queue?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function(result) {
			$.ajax({
				url: base_url + 'index.php/qslprint/delete_from_qsl_queue',
				type: 'post',
				data: {'id': id	},
				success: function(html) {
					$("#qslprint_"+id).remove();
				}
			});
		}
	});
}

function openQsoList(callsign) {
	$.ajax({
		url: base_url + 'index.php/qslprint/open_qso_list',
		type: 'post',
		data: {'callsign': callsign},
		success: function(html) {
			BootstrapDialog.show({
				title: 'QSO List',
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'qso-dialog',
				nl2br: false,
				message: html,
				onshown: function(dialog) {
					$('[data-toggle="tooltip"]').tooltip();
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

function addQsoToPrintQueue(id) {
	$.ajax({
		url: base_url + 'index.php/qslprint/add_qso_to_print_queue',
		type: 'post',
		data: {'id': id},
		success: function(html) {
			var line = '<tr id="qslprint_'+id+'">';
			line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(0)").text()+'</td>';
			line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(1)").text()+'</td>';
			line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(2)").text()+'</td>';
			line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(3)").text()+'</td>';
			line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(4)").text()+'</td>';
			line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(6)").text()+'</td>';
			line += '<td style=\'text-align: center\'><span class="badge badge-light">'+$("#qsolist_"+id).find("td:eq(5)").text()+'</span></td>';
			line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(7)").text()+'</td>';
			line += '<td style=\'text-align: center\'><button onclick="mark_qsl_sent('+id+', \'B\')" class="btn btn-sm btn-success"><i class="fa fa-check"></i></button></td>';
			line += '<td style=\'text-align: center\'><button onclick="deleteFromQslQueue('+id+')" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button></td></td>';
			line += '<td style=\'text-align: center\'><button onclick="openQsoList(\''+$("#qsolist_"+id).find("td:eq(0)").text()+'\')" class="btn btn-sm btn-success"><i class="fas fa-search"></i></button></td>';
			line += '</tr>';
			$('.table tr:last').after(line);
			$("#qsolist_"+id).remove();''
		}
	});
}

$(".station_id").change(function(){
	var station_id = $(".station_id").val();
	$.ajax({
		url: base_url + 'index.php/qslprint/get_qsos_for_print_ajax',
		type: 'post',
		data: {'station_id': station_id},
		success: function(html) {
			$('.resulttable').empty();
			$('.resulttable').append(html);
		}
	});
});

function showOqrs(id) {
	$.ajax({
		url: base_url + 'index.php/qslprint/show_oqrs',
		type: 'post',
		data: {'id': id},
		success: function(html) {
			BootstrapDialog.show({
				title: 'OQRS',
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'qso-dialog',
				nl2br: false,
				message: html,
				onshown: function(dialog) {
					$('[data-toggle="tooltip"]').tooltip();
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

function mark_qsl_sent(id, method) {
    $.ajax({
        url: base_url + 'index.php/qso/qsl_sent_ajax',
        type: 'post',
        data: {'id': id,
            'method': method
        },
        success: function(data) {
            if (data.message == 'OK') {
                $("#qslprint_" + id).remove(); // removes choice from menu
            }
            else {
                $(".container").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You are not allowed to update QSL status!</div>');
            }
        }
    });
}

$('#checkBoxAll').change(function (event) {
	if (this.checked) {
		$('.qslprint tbody tr').each(function (i) {
			$(this).closest('tr').addClass('activeRow');
			$(this).closest('tr').find("input[type=checkbox]").prop("checked", true);
		});
	} else {
		$('.qslprint tbody tr').each(function (i) {
			$(this).closest('tr').removeClass('activeRow');
			$(this).closest('tr').find("input[type=checkbox]").prop("checked", false);
		});
	}
});

$('.qslprint').on('click', 'input[type="checkbox"]', function() {
	if ($(this).is(":checked")) {
		$(this).closest('tr').addClass('activeRow');
	} else {
		$(this).closest('tr').removeClass('activeRow');
	}
});

function markSelectedQsos() {
	var elements = $('.qslprint tbody input:checked');
	var nElements = elements.length;
	if (nElements == 0) {
		return;
	}
	$('.markallprinted').prop("disabled", true);
	var id_list=[];
	elements.each(function() {
		let id = $(this).first().closest('tr').attr('id');
		id = id.match(/\d/g);
		id = id.join("");
		id_list.push(id);
	});
	$.ajax({
		url: base_url + 'index.php/logbookadvanced/update_qsl',
		type: 'post',
		data: {'id': JSON.stringify(id_list, null, 2),
			'sent' : 'Y',
			'method' : 'B'
		},
		success: function(data) {
			if (data !== []) {
				$.each(data, function(k, v) {
					$("#qslprint_"+this.qsoID).remove();
				});
			}
			$('.markallprinted').prop("disabled", false);
		}
	});
}

function removeSelectedQsos() {
	var elements = $('.qslprint tbody input:checked');
	var nElements = elements.length;
	if (nElements == 0) {
		return;
	}
	$('.removeall').prop("disabled", true);

	var id_list=[];
	elements.each(function() {
		let id = $(this).first().closest('tr').attr('id');
		id = id.match(/\d/g);
		id = id.join("");
		id_list.push(id);
	});

	$.ajax({
		url: base_url + 'index.php/logbookadvanced/update_qsl',
		type: 'post',
		data: {'id': JSON.stringify(id_list, null, 2),
			'sent' : 'N',
			'method' : ''
		},
		success: function(data) {
			if (data !== []) {
				$.each(data, function(k, v) {
					$("#qslprint_"+this.qsoID).remove();
				});
			}
			$('.removeall').prop("disabled", false);
		}
	});
}
