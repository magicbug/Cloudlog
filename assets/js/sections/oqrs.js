let station_id;

function loadStationInfo() {
	station_id = $("#station").val();
    $(".resulttable").empty();
    $(".searchinfo").empty();
    $.ajax({
        url: base_url+'index.php/oqrs/get_station_info',
        type: 'post',
        data: {'station_id': station_id},
        success: function (data) {
            if (data.count > 0) {
                $(".resulttable").append('<br />' + data.count + ' Qsos logged between ' + data.mindate + ' and ' + data.maxdate + '.<br /><br />');
                $(".resulttable").append('<form class="d-flex align-items-center" onsubmit="return false;"><label for="oqrssearch">Enter your callsign: </label><input class="form-control m-2 w-auto" id="oqrssearch" type="search" name="callsign" placeholder="Search Callsign" aria-label="Search" required="required"><button onclick="searchOqrs();" class="btn btn-sm btn-primary" id="stationbuttonsubmit" type="button"><i class="fas fa-search"></i> Search</button></form>');
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
        data: {'station_id': station_id, 'callsign': $("#oqrssearch").val().toUpperCase()},
        success: function (data) {
            $(".searchinfo").append(data);
        }
    });
}

function searchOqrsGrouped() {
    $(".searchinfo").empty();
    $.ajax({
        url: base_url+'index.php/oqrs/get_qsos_grouped',
        type: 'post',
        data: {'callsign': $("#oqrssearch").val().toUpperCase()},
        success: function (data) {
            $(".searchinfo").append(data);
            $('.qsotime').change(function() {
                var raw_time = $(this).val();
                if(raw_time.match(/^\d\[0-6]d$/)) {
                    raw_time = "0"+raw_time;
                }
                if(raw_time.match(/^[012]\d[0-5]\d$/)) {
                    raw_time = raw_time.substring(0,2)+":"+raw_time.substring(2,4);
                    $(this).val(raw_time);
                }
            });
            $('.result-table').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: false,
                "scrollY": "410px",
                "scrollCollapse": true,
                "paging": false,
                "scrollX": true,
                "language": {
                    url: getDataTablesLanguageUrl(),
                }
            });

            // Get the input field
            var input = document.getElementById("emailInput");

            // Execute a function when the user presses a key on the keyboard
            input.addEventListener("keypress", function(event) {
            // If the user presses the "Enter" key on the keyboard
            if (event.key === "Enter") {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("requestGroupedSubmit").click();
            }
            });
        }
    });
}

function notInLog() {
    $.ajax({
        url: base_url + 'index.php/oqrs/not_in_log',
        type: 'post',
        data: {'station_id': station_id, 'callsign': $("#oqrssearch").val().toUpperCase()},
        success: function(html) {
            $(".searchinfo").html(html);
            $('.qsotime').change(function() {
                var raw_time = $(this).val();
                if(raw_time.match(/^\d\[0-6]d$/)) {
                    raw_time = "0"+raw_time;
                }
                if(raw_time.match(/^[012]\d[0-5]\d$/)) {
                    raw_time = raw_time.substring(0,2)+":"+raw_time.substring(2,4);
                    $(this).val(raw_time);
                }
            });
        }
    });
}

function saveNotInLogRequest() {
	const qsos = [];
    $(".alertinfo").remove();
    if ($("#emailInput").val() == '') {
        $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill out an email address!</div></div>');
    } else {
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
                data: { 'station_id': station_id,
                        'callsign': $("#oqrssearch").val().toUpperCase(),
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
    var $time = $('<td></td>').html('<input class="form-control qsotime" type="text" name="time" value="" id="time" maxlength="5" placeholder="hh:mm">');
    var $band = $('<td></td>').html('<input class="form-control" type="text" name="band" value="" id="band">');
    var $mode = $('<td></td>').html('<input class="form-control" type="text" name="mode" value="" id="mode">');

    $row.append($iterator, $date, $time, $band, $mode);

    $myTable.append($row);
    $('.qsotime').change(function() {
        var raw_time = $(this).val();
        if(raw_time.match(/^\d\[0-6]d$/)) {
            raw_time = "0"+raw_time;
        }
        if(raw_time.match(/^[012]\d[0-5]\d$/)) {
            raw_time = raw_time.substring(0,2)+":"+raw_time.substring(2,4);
            $(this).val(raw_time);
        }
    });
}

function requestOqrs() {
    $.ajax({
        url: base_url + 'index.php/oqrs/request_form',
        type: 'post',
        data: {'station_id': station_id, 'callsign': $("#oqrssearch").val().toUpperCase()},
        success: function(html) {
            $(".searchinfo").html(html);
            /* time input shortcut */
            $('.qsotime').change(function() {
                var raw_time = $(this).val();
                if(raw_time.match(/^\d\[0-6]d$/)) {
                    raw_time = "0"+raw_time;
                }
                if(raw_time.match(/^[012]\d[0-5]\d$/)) {
                    raw_time = raw_time.substring(0,2)+":"+raw_time.substring(2,4);
                    $(this).val(raw_time);
                }
            });
            $('.result-table').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: false,
                "scrollY": "410px",
                "scrollCollapse": true,
                "paging": false,
                "scrollX": true,
                "language": {
                    url: getDataTablesLanguageUrl(),
                }
            });
            // Get the input field
            var input = document.getElementById("emailInput");

            // Execute a function when the user presses a key on the keyboard
            input.addEventListener("keypress", function(event) {
            // If the user presses the "Enter" key on the keyboard
            if (event.key === "Enter") {
                // Cancel the default action, if needed
                event.preventDefault();
                // Trigger the button element with a click
                document.getElementById("requestSubmit").click();
            }
            });
        }
    });
}

function submitOqrsRequest() {
	const qsos = [];
    $(".alertinfo").remove();
    if ($("#emailInput").val() == '') {
        $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill out an email address!</div></div>');
    } else {
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
                data: { 'station_id': station_id,
                        'callsign': $("#oqrssearch").val().toUpperCase(),
                        'email': $("#emailInput").val(),
                        'message': $("#messageInput").val(),
                        'qsos': qsos,
                        'qslroute': $('input[name="qslroute"]:checked').val()
                },
                success: function (data) {
                    $(".resulttable").empty();
                    $(".stationinfo").empty();
                    $(".searchinfo").empty();
                    $(".stationinfo").append('<br /><div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your QSL request has been saved!</div>');
                }
            });
        }
    }
}

function submitOqrsRequestGrouped() {
	const qsos = [];
    $(".alertinfo").remove();
    if ($("#emailInput").val() == '') {
        $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill out an email address!</div></div>');
    } else {
        $(".result-table tbody tr").each(function(i) {
            var data = [];
            var stationid = this.getAttribute('stationid');;
            var datecell = $("#date", this).val();
            var timecell = $("#time", this).val();
            var bandcell = $("#band", this).text();
            var modecell = $("#mode", this).text();
            if (datecell != "" && timecell != "") {
                data.push(datecell);
                data.push(timecell);
                data.push(bandcell);
                data.push(modecell);
                data.push(stationid);
                qsos.push(data);
            }
        });

        if (qsos.length === 0) {
            $(".searchinfo").prepend('<div class="alertinfo"><br /><div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You need to fill the QSO information before submitting a request!</div></div>');
        } else {
            $.ajax({
                url: base_url+'index.php/oqrs/save_oqrs_request_grouped',
                type: 'post',
                data: {
                        'callsign': $("#oqrssearch").val().toUpperCase(),
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
            "language": {
				url: getDataTablesLanguageUrl(),
			},
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
			$(this).closest('tr').addClass('activeRow');
		} else {
			$(this).closest('tr').removeClass('activeRow');
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
						let id = $(this).first().closest('tr').data('oqrsID')
						$.ajax({
							url: base_url + 'index.php/oqrs/delete_oqrs_line',
							type: 'post',
							data: {'id': id
							},
							success: function(data) {
								var row = $("#oqrsID_" + id);
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
			message: 'Warning! Are you sure you want to mark OQRS request(s) as done?' ,
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			draggable: true,
			btnOKClass: 'btn-danger',
			callback: function(result) {
				if(result) {
					elements.each(function() {
						let id = $(this).first().closest('tr').data('oqrsID')
						$.ajax({
							url: base_url + 'index.php/oqrs/mark_oqrs_line_as_done',
							type: 'post',
							data: {'id': id
							},
							success: function(data) {
								$('#searchForm').submit();
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
	element.addClass('activeRow');
}

function unselectQsoID(qsoID) {
	var element = $("#oqrsID_" + qsoID);
	element.find("input[type=checkbox]").prop("checked", false);
	element.removeClass('activeRow');
	$('#checkBoxAll').prop("checked", false);
}

function addQsoToPrintQueue(id) {
	$.ajax({
		url: base_url + 'index.php/qslprint/add_qso_to_print_queue',
		type: 'post',
		data: {'id': id},
		success: function(html) {
			$("#qsolist_"+id).remove();''
		}
	});
}
