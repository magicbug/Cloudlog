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
					$('[data-bs-toggle="tooltip"]').tooltip();
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
			line += '<td style=\'text-align: center\'><span class="badge text-bg-light">'+$("#qsolist_"+id).find("td:eq(5)").text()+'</span></td>';
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
			try {
				// Destroy existing DataTable if it exists
				if ($.fn.DataTable.isDataTable('#qslprint_table')) {
					$('#qslprint_table').DataTable().destroy();
				}
				$('.resulttable').empty();
				$('.resulttable').append(html);
				// Reinitialize DataTable
				$('#qslprint_table').DataTable({
					"stateSave": true,
					paging: false,
					"language": {
						url: getDataTablesLanguageUrl(),
					},
					"drawCallback": function(settings) {
						// Re-attach event handlers after DataTable draws/redraws
						attachCheckboxEvents();
					}
				});
				// Attach checkbox events immediately after initialization
				attachCheckboxEvents();
			} catch (error) {
				console.error('Error reinitializing DataTable:', error);
			}
		}
	});
});

// Initialize DataTable only if it exists and isn't already initialized
$(document).ready(function() {
	try {
		if ($('#qslprint_table').length && !$.fn.DataTable.isDataTable('#qslprint_table')) {
			$('#qslprint_table').DataTable({
				"stateSave": true,
				paging: false,
				"language": {
					url: getDataTablesLanguageUrl(),
				},
				"drawCallback": function(settings) {
					// Re-attach event handlers after DataTable draws/redraws
					attachCheckboxEvents();
				}
			});
		}
		// Initial attachment of events
		attachCheckboxEvents();
	} catch (error) {
		console.error('Error initializing DataTable:', error);
		// Still try to attach checkbox events even if DataTable fails
		attachCheckboxEvents();
	}
});

// Function to attach checkbox events
function attachCheckboxEvents() {
	// Remove any existing handlers to prevent duplicates
	$('#checkBoxAll').off('change.qslprint');
	$('.qso-checkbox').off('click.qslprint');
	
	// Attach select all functionality
	$('#checkBoxAll').on('change.qslprint', function (event) {
		var isChecked = this.checked;
		$('#qslprint_table tbody tr .qso-checkbox').each(function (i) {
			$(this).prop("checked", isChecked);
			if (isChecked) {
				$(this).closest('tr').addClass('activeRow');
			} else {
				$(this).closest('tr').removeClass('activeRow');
			}
		});
	});

	// Attach individual checkbox functionality
	$(document).on('click.qslprint', '.qso-checkbox', function() {
		if ($(this).is(":checked")) {
			$(this).closest('tr').addClass('activeRow');
		} else {
			$(this).closest('tr').removeClass('activeRow');
		}
		
		// Update the "select all" checkbox state
		var totalCheckboxes = $('#qslprint_table tbody tr .qso-checkbox').length;
		var checkedCheckboxes = $('#qslprint_table tbody tr .qso-checkbox:checked').length;
		$('#checkBoxAll').prop('checked', totalCheckboxes === checkedCheckboxes);
	});
}

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
					$('[data-bs-toggle="tooltip"]').tooltip();
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

function markSelectedQsos() {
	var elements = $('.qso-checkbox:checked');
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
			'method' : ''
		},
		success: function(data) {
			if (data && data.length > 0) {
				$.each(data, function(k, v) {
					$("#qslprint_"+this.qsoID).remove();
				});
			}
			$('.markallprinted').prop("disabled", false);
		},
		error: function(xhr, status, error) {
			console.error('Error marking QSOs as printed:', error);
			$('.markallprinted').prop("disabled", false);
		}
	});
}

function removeSelectedQsos() {
	var elements = $('.qso-checkbox:checked');
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
			if (data && data.length > 0) {
				$.each(data, function(k, v) {
					$("#qslprint_"+this.qsoID).remove();
				});
			}
			$('.removeall').prop("disabled", false);
		},
		error: function(xhr, status, error) {
			console.error('Error removing QSOs from queue:', error);
			$('.removeall').prop("disabled", false);
		}
	});
}
