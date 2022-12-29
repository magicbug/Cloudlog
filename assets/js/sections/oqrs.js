function loadStationInfo() {
    $(".stationinfo").empty();
    $(".searchinfo").empty();
    $.ajax({
        url: base_url+'index.php/oqrs/get_station_info',
        type: 'post',
        data: {'station_id': $("#station").val()},
        success: function (data) {
            if (data.count > 0) {
                $(".stationinfo").append('<br />' + data.count + ' Qsos logged between ' + data.mindate + ' and ' + data.maxdate + '.<br /><br />');
                $(".stationinfo").append('<form class="form-inline" onsubmit="return false;"><label class="my-1 mr-2" for="oqrssearch">Enter your callsign: </label><input class="form-control mr-sm-2" id="oqrssearch" type="search" name="callsign" placeholder="Search Callsign" aria-label="Search" required="required"><button onclick="searchOqrs();" class="btn btn-sm btn-primary" id="stationbuttonsubmit" type="button"><i class="fas fa-search"></i> Search</button></form>');
                // Get the input field
                var input = document.getElementById("oqrssearch");

                // Execute a function when the user presses a key on the keyboard
                input.addEventListener("keypress", function(event) {
                // If the user presses the "Enter" key on the keyboard
                if (event.key === "Enter") {
                    // Cancel the default action, if needed
                    event.preventDefault();
                    // Trigger the button element with a click
                    document.getElementById("stationbuttonsubmit").click();
                }
                });
            } else {
                $(".stationinfo").append("No QSOs for this callsign was found!");
            }
        }
    });
}

function searchOqrs() {
    $(".searchinfo").empty();
    $.ajax({
        url: base_url+'index.php/oqrs/get_qsos',
        type: 'post',
        data: {'station_id': $("#station").val(), 'callsign': $("#oqrssearch").val()},
        success: function (data) {
            $(".searchinfo").append(data);
        }
    });
}

function notInLog() {
    $.ajax({
        url: base_url + 'index.php/oqrs/not_in_log',
        type: 'post',
        data: {'station_id': $("#station").val(), 'callsign': $("#oqrssearch").val()},
        success: function(html) {
            $(".searchinfo").html(html);
        }
    }); 
}

function saveNotInLogRequest() {
    $(".alertinfo").remove();
    if ($("#emailInput").val() == '') {
        $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill out an email address!</div></div>');
    } else {
        const qsos = [];
        $(".notinlog-table tbody tr").each(function(i) {
            var data = [];
            var datecell = $("#date", this).val();
            var timecell = $("#time", this).val();
            var bandcell = $("#band", this).val();
            var modecell = $("#mode", this).val();
            if (datecell != "" && timecell != "" && bandcell != "" && modecell != "") {
                data.push(datecell);
                data.push(timecell);
                data.push(bandcell);
                data.push(modecell);
                qsos.push(data);
            }
        });
        if (qsos.length === 0) {
            $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill the QSO information before submitting a request!</div></div>');
        } else {
            $.ajax({
                url: base_url+'index.php/oqrs/save_not_in_log',
                type: 'post',
                data: { 'station_id': $("#station").val(), 
                        'callsign': $("#oqrssearch").val(),
                        'email': $("#emailInput").val(),
                        'message': $("#messageInput").val(),
                        'qsos': qsos
                },
                success: function (data) {
                    $(".stationinfo").empty();
                    $(".searchinfo").empty();
                    $(".stationinfo").append('<br /><div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your not in log query has been saved!</div>');
                }
            });
        }
    }
}

function oqrsAddLine() {
    var rowCount = $('.notinlog-table tr').length;
    var $myTable = $('.notinlog-table');

    var $row = $('<tr></tr>');

    var $iterator = $('<td></td>').html(rowCount);
    var $date = $('<td></td>').html('<input class="form-control" type="date" name="date" value="" id="date" placeholder="YYYY-MM-DD">');
    var $time = $('<td></td>').html('<input class="form-control" type="text" name="time" value="" id="time" maxlength="5" placeholder="HH:MM">');
    var $band = $('<td></td>').html('<input class="form-control" type="text" name="band" value="" id="band">');
    var $mode = $('<td></td>').html('<input class="form-control" type="text" name="mode" value="" id="mode">');

    $row.append($iterator, $date, $time, $band, $mode);

    $myTable.append($row);
}

function requestOqrs() {
    $.ajax({
        url: base_url + 'index.php/oqrs/request_form',
        type: 'post',
        data: {'station_id': $("#station").val(), 'callsign': $("#oqrssearch").val()},
        success: function(html) {
            $(".searchinfo").html(html);
            $('.result-table').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: false,
                "scrollY": "410px",
                "scrollCollapse": true,
                "paging": false,
                "scrollX": true,
            });
        }
    }); 
}

function submitOqrsRequest() {
    $(".alertinfo").remove();
    if ($("#emailInput").val() == '') {
        $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill out an email address!</div></div>');
    } else {
        const qsos = [];
        $(".result-table tbody tr").each(function(i) {
            var data = [];
            var datecell = $("#date", this).val();
            var timecell = $("#time", this).val();
            var bandcell = $("#band", this).text();
            var modecell = $("#mode", this).text();
            if (datecell != "" && timecell != "") {
                data.push(datecell);
                data.push(timecell);
                data.push(bandcell);
                data.push(modecell);
                qsos.push(data);
            }
        });

        if (qsos.length === 0) {
            $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill the QSO information before submitting a request!</div></div>');
        } else {
            $.ajax({
                url: base_url+'index.php/oqrs/save_oqrs_request',
                type: 'post',
                data: { 'station_id': $("#station").val(), 
                        'callsign': $("#oqrssearch").val(),
                        'email': $("#emailInput").val(),
                        'message': $("#messageInput").val(),
                        'qsos': qsos,
                        'qslroute': $('input[name="qslroute"]:checked').val()
                },
                success: function (data) {
                    $(".stationinfo").empty();
                    $(".searchinfo").empty();
                    $(".stationinfo").append('<br /><div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your QSL request has been saved!</div>');
                }
            });
        }
    }
}

function deleteOqrsLine(id) {
    BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to delete this OQRS request?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			$.ajax({
                url: base_url+'index.php/oqrs/delete_oqrs_line',
                type: 'post',
                data: { 'id': id,
                },
                success: function (data) {
                    $(".oqrsid_"+id).remove();
                }
            });
		}
	});
}

function searchLog(callsign) {
    $.ajax({
        url: base_url + 'index.php/oqrs/search_log',
        type: 'post',
        data: {'callsign': callsign,
        },
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
                    label: 'Close',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    });
}

function searchLogTimeDate(id) {
    $.ajax({
        url: base_url + 'index.php/oqrs/search_log_time_date',
        type: 'post',
        data: {'time': $('#oqrsID_'+id+ ' td:nth-child(4)').text(),
            'date': $('#oqrsID_'+id+ ' td:nth-child(3)').text(),
            'band': $('#oqrsID_'+id+ ' td:nth-child(5)').text(),
            'mode': $('#oqrsID_'+id+ ' td:nth-child(6)').text()
        },
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
                    label: 'Close',
                    action: function (dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    });
}

function markOqrsLineAsDone(id) {
    $.ajax({
        url: base_url+'index.php/oqrs/mark_oqrs_line_as_done',
        type: 'post',
        data: { 'id': id,
        },
        success: function (data) {
            $(".oqrsid_"+id).remove();
        }
    });
}
function loadOqrsTable(rows) {
	var uninitialized = $('.oqrstable').filter(function() {
		return !$.fn.DataTable.fnIsDataTable(this);
	});

	uninitialized.each(function() {
	$(this).DataTable({
			searching: false,
			responsive: false,
			ordering: true,
			"scrollY": window.innerHeight - $('#searchForm').innerHeight() - 250,
			"scrollCollapse": true,
			"paging":         false,
			"scrollX": true,
			"order": [ 0, 'asc' ],
            'white-space': 'nowrap',
		});
	});

	var table = $('.oqrstable').DataTable();

	table.clear();
	
	for (i = 0; i < rows.length; i++) {
		let qso = rows[i];
		
		var data = [
			'<div class="form-check"><input class="form-check-input" type="checkbox" /></div>',
			qso.requesttime,
			qso.date,
			qso.time,
			qso.band,
			qso.mode,
			qso.requestcallsign,
			qso.station_callsign,
			qso.email,
			qso.note,
			echo_qsl_method(qso.qslroute),
			echo_searchlog_button(qso.requestcallsign, qso.id),
            echo_status(qso.status),
		];
		
		let createdRow = table.row.add(data).index();
		table.rows(createdRow).nodes().to$().data('oqrsID', qso.id);
		table.row(createdRow).node().id = 'oqrsID_' + qso.id;
	}
    table.columns.adjust().draw();
}

function echo_status(status) {
	switch(status.toUpperCase()) {
		case '0': return 'Open request'; break;
		case '1': return 'Not in log request'; break;
		case '2': return 'Request done'; break;
        default: return '';
	}
}
function echo_qsl_method(method) {
	switch(method.toUpperCase()) {
		case 'B': return 'Bureau'; break;
		case 'D': return 'Direct'; break;
		case 'E': return 'Electronic'; break;
        default: return '';
	}
}

function echo_searchlog_button(callsign, id) {
    return '<button class="btn btn-primary btn-sm" type="button" onclick="searchLog(\'' + callsign + '\');"><i class="fas fa-search"></i> Call</button> ' +
    '<button class="btn btn-primary btn-sm" type="button" onclick="searchLogTimeDate(' + id + ');"><i class="fas fa-search"></i> Date/Time</button>';
}

$(document).ready(function () {

	$('#searchForm').submit(function (e) {
		$('#searchButton').prop("disabled", true);

		$.ajax({
			url: this.action,
			type: 'post',
			data: {
				de: this.de.value,
				dx: this.dx.value,
				status: this.status.value,
				oqrsResults: this.oqrsResults.value
			},
			dataType: 'json',
			success: function (data) {
				$('#searchButton').prop("disabled", false);
				loadOqrsTable(data);
			},
			error: function (data) {
				$('#searchButton').prop("disabled", false);
				BootstrapDialog.alert({
					title: 'ERROR',
					message: 'An error ocurred while making the request',
					type: BootstrapDialog.TYPE_DANGER,
					closable: false,
					draggable: false,
					callback: function (result) {
					}
				});
			},
		});
		return false;
	});

	$('.oqrstable').on('click', 'input[type="checkbox"]', function() {
		if ($(this).is(":checked")) {
			$(this).closest('tr').addClass('alert-success');
		} else {
			$(this).closest('tr').removeClass('alert-success');
		}
	});

	$('#deleteOqrs').click(function (event) {
		var elements = $('.oqrstable tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}

		$('#deleteOqrs').prop("disabled", true);

		var table = $('.oqrstable').DataTable();

		BootstrapDialog.confirm({
			title: 'DANGER',
			message: 'Warning! Are you sure you want to delete the marked OQRS request(s)?' ,
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			draggable: true,
			btnOKClass: 'btn-danger',
			callback: function(result) {
				if(result) {
					elements.each(function() {
						let id = $(this).first().closest('tr').data('qsoID')
						$.ajax({
							url: base_url + 'index.php/oqrs/delete_ajax',
							type: 'post',
							data: {'id': id
							},
							success: function(data) {
								var row = $("#qsoID_" + id);
								table.row(row).remove().draw(false);
							}
						});
						$('#deleteOqrs').prop("disabled", false);
					})
				}
			}
		});
	});

	$('#markOqrs').click(function (event) {
		var elements = $('.oqrstable tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}

		$('#markOqrs').prop("disabled", true);

		var table = $('.oqrstable').DataTable();

		BootstrapDialog.confirm({
			title: 'DANGER',
			message: 'Warning! Are you sure you want to delete the marked OQRS request(s)?' ,
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			draggable: true,
			btnOKClass: 'btn-danger',
			callback: function(result) {
				if(result) {
					elements.each(function() {
						let id = $(this).first().closest('tr').data('qsoID')
						$.ajax({
							url: base_url + 'index.php/oqrs/mark_ajax',
							type: 'post',
							data: {'id': id
							},
							success: function(data) {
								var row = $("#qsoID_" + id);
								table.row(row).remove().draw(false);
							}
						});
						$('#markOqrs').prop("disabled", false);
					})
				}
			}
		});
	});


	$('#checkBoxAll').change(function (event) {
		if (this.checked) {
			$('.oqrstable tbody tr').each(function (i) {
				selectQsoID($(this).data('oqrsID'))
			});
		} else {
			$('.oqrstable tbody tr').each(function (i) {
				unselectQsoID($(this).data('oqrsID'))
			});
		}
	});

	$('#searchForm').submit();

    $('#searchForm').on('reset', function(e) {
        setTimeout(function() {
            $('#searchForm').submit();
        });
    });
});

function selectQsoID(qsoID) {
	var element = $("#oqrsID_" + qsoID);
	element.find("input[type=checkbox]").prop("checked", true);
	element.addClass('alert-success');
}

function unselectQsoID(qsoID) {
	var element = $("#oqrsID_" + qsoID);
	element.find("input[type=checkbox]").prop("checked", false);
	element.removeClass('alert-success');
	$('#checkBoxAll').prop("checked", false);
}

