function loadYears() {
    $(".contestyear").empty();
    $(".contestname").empty();
    $(".contestdates").empty();
    $.ajax({
        url: base_url+'index.php/cabrillo/getYears',
        type: 'post',
        data: {'station_id': $("#station_id").val()},
        success: function (data) {
            if (data.length > 0) {
                $(".contestyear").append('<div class="col-md-3 control-label" for="year">Select year: </div>' +
                '<select id="year" class="custom-select my-1 mr-sm-2 col-md-2" name="year">' +
                '</select>' +
                '  <button onclick="loadContests();" class="btn btn-sm btn-primary" type="button">Proceed</button>'); 

                $.each(data, function(key, value) {
                    $('#year')
                        .append($("<option></option>")
                        .attr("value",value.year)
                        .text(value.year));
                });
            } else {
                $(".contestyear").append("No contests were found for this station location!");
            }
        }
    });
}

function loadContests() {
    $(".contestname").empty();
    $(".contestdates").empty();
    $.ajax({
        url: base_url+'index.php/cabrillo/getContests',
        type: 'post',
        data: {'year': $("#year").val(),
                'station_id': $("#station_id").val()
        },
        success: function (data) {
                $(".contestname").append('<div class="col-md-3 control-label" for="contestid">Select contest: </div>' +
                '<select class="custom-select my-1 mr-sm-2 col-md-4" id="contestid" name="contestid">' +
                '</select>' +
                '  <button onclick="loadContestDates();" class="btn btn-sm btn-primary" type="button">Proceed</button>'); 

                $.each(data, function(key, value) {
                    $('#contestid')
                        .append($("<option></option>")
                        .attr("value",value.col_contest_id)
                        .text(value.col_contest_id));
                });
        }
    });
}

function loadContestDates() {
    $(".contestdates").empty();
    $.ajax({
        url: base_url+'index.php/cabrillo/getContestDates',
        type: 'post',
        data: {'year': $("#year").val(),
                'contestid': $("#contestid").val(),
                'station_id': $("#station_id").val()},
        success: function (data) {
                $(".contestdates").append('<div class="col-md-3 control-label" for="contestdates">Select date range: </div>' +
                '<select class="custom-select my-1 mr-sm-2 col-md-2" id="contestdatesfrom" name="contestdatesfrom">' +
                '</select>' +
                '<select class="custom-select my-1 mr-sm-2 col-md-2" id="contestdatesto" name="contestdatesto">' +
                '</select>' +
                '  <button class="btn btn-sm btn-primary" type="submit">Export</button>'); 

                $.each(data, function(key, value) {
                    $('#contestdatesfrom')
                        .append($("<option></option>")
                        .attr("value", value.date)
                        .text(value.date));
                });

                
                $.each(data, function(key, value) {
                    $('#contestdatesto')
                        .append($("<option></option>")
                        .attr("value", value.date)
                        .text(value.date));
                });
        }
    });
}