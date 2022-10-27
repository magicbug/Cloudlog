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
            BootstrapDialog.show({
                title: 'Not in log',
                size: BootstrapDialog.SIZE_WIDE,
                cssClass: 'notinlog-dialog',
                nl2br: false,
                message: html,
                buttons: [{
                    label: 'Close',
                    action: function(dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    }); 
}

function saveNotInLogRequest() {
    $(".alert").remove();
    $.ajax({
        url: base_url + 'index.php/oqrs/save_not_in_log',
        type: 'post',
        success: function(html) {
            BootstrapDialog.show({
                title: 'Stored Queries',
                size: BootstrapDialog.SIZE_WIDE,
                cssClass: 'queries-dialog',
                nl2br: false,
                message: html,
                buttons: [{
                    label: 'Close',
                    action: function(dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    });
}

function oqrsAddLine() {
    var rowCount = $('.notinlog-table tr').length;
    var $myTable = $('.notinlog-table');

    var $row = $('<tr></tr>');

    var $iterator = $('<td></td>').html(rowCount);
    var $date = $('<td></td>').html('<input class="form-control" type="date" name="date1" value="" id="date_1" placeholder="YYYY-MM-DD">');
    var $time = $('<td></td>').html('<input class="form-control" type="text" name="time1" value="" id="time_1" maxlength="5" placeholder="HH:MM">');
    var $band = $('<td></td>').html('<input class="form-control" type="text" name="band1" value="" id="band_1">');
    var $mode = $('<td></td>').html('<input class="form-control" type="text" name="mode1" value="" id="mode_1">');

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
        }
    }); 
}

function submitOqrsRequest() {
    alert("Yay, lot's of qsls coming your way :)");
}