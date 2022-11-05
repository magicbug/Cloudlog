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
                $(".stationinfo").append('<form class="form-inline"><label class="my-1 mr-2" for="oqrssearch">Enter your callsign: </label><input class="form-control mr-sm-2" id="oqrssearch" type="search" name="callsign" placeholder="Search Callsign" aria-label="Search"><button onclick="searchOqrs();" class="btn btn-sm btn-primary" type="button"><i class="fas fa-search"></i> Search</button></form>');
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
        data: {'time': $('.oqrsid_'+id+ ' td:nth-child(2)').text(),
            'date': $('.oqrsid_'+id+ ' td:nth-child(1)').text(),
            'band': $('.oqrsid_'+id+ ' td:nth-child(3)').text(),
            'mode': $('.oqrsid_'+id+ ' td:nth-child(4)').text()
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