function loadContests() {
    $(".contestname").empty();
    $(".contestdates").empty();
    $.ajax({
        url: base_url+'index.php/cabrillo/getContests',
        type: 'post',
        data: {'year': $("#year").val()},
        success: function (data) {
                $(".contestname").append('<div class="col-md-2 control-label" for="contestid">Select contest: </div>' +
                '<select class="custom-select my-1 mr-sm-2 col-md-3" id="contestid" name="contestid">' +
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
                'contestid': $("#contestid").val()},
        success: function (data) {
                $(".contestdates").append('<div class="col-md-2 control-label" for="contestdates">Select daterange: </div>' +
                '<select class="custom-select my-1 mr-sm-2 col-md-3" id="contestdates" name="contestdates">' +
                '</select>' +
                '  <button class="btn btn-sm btn-primary" type="submit">Export</button>'); 

                $.each(data, function(key, value) {
                    $('#contestdates')
                        .append($("<option></option>")
                        .attr("value", value.mindate + ',' + value.maxdate)
                        .text(value.mindate + ' - ' + value.maxdate));
                });
        }
    });
}