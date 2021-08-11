// Callsign always has focus on load
$("#callsign").focus();

// Init serial sent as 1 when loading page
$("#exch_sent").val(1);

$( document ).ready(function() {
    restoreContestSession();
    setRst($("#mode").val());

    // Check to see what serial type is selected and set validation
    if($('#serial').is(':checked')) {
        set_serial_number_input_validation();
    }
    if($('#other').is(':checked')) {
        set_other_input_validation();
    }
});

// This erases the contest logging session which is stored in localStorage
function reset_contest_session() {
    $('#name').val("");
    $('.callsign-suggestions').text("");
    $('#callsign').val("");
    $('#comment').val("");
    $('#exch_sent').val("1");
    $('#exch_recv').val("");
    $("#callsign").focus();
    setRst($("#mode").val());
    $("#serial").prop("checked", true);
    $("#contestname").val("Other").change();
    $(".contest_qso_table_contents").empty();

    localStorage.removeItem("contestid");
    localStorage.removeItem("exchangetype");
    localStorage.removeItem("exchangesent");
    localStorage.removeItem("qso");
}

// Storing the contestid in contest session
$('#contestname').change(function() {
    localStorage.setItem("contestid", $("#contestname").val());
});

// Storing the exchange type in contest session
$('input[type=radio][name=exchangeradio]').change(function() {
    localStorage.setItem("exchangetype", $('input[name=exchangeradio]:checked', '#qso_input').val());
});


// realtime clock
$(function($) {
    var options = {
        utc: true,
        format: '%H:%M:%S'
    }
    $('.input_time').jclock(options);
});

$(function($) {
    var options = {
        utc: true,
        format: '%d-%m-%Y'
    }
    $('.input_date').jclock(options);
});

// We don't want spaces to be written in callsign
$(function() {
    $('#callsign').on('keypress', function(e) {
        if (e.which == 32){
            return false;
        }
    });
});

// We don't want spaces to be written in exchange
$(function() {
    $('#exch_recv').on('keypress', function(e) {
        if (e.which == 32){
            return false;
        }
    });
});

// Here we capture keystrokes fo execute functions
document.onkeyup = function(e) {
    // ALT-W wipe
    if (e.altKey && e.which == 87) {
        reset_log_fields();
    // CTRL-Enter logs QSO
    } else if ((e.keyCode == 10 || e.keyCode == 13) && (e.ctrlKey || e.metaKey)) {
        logQso();
    // Enter in sent exchange logs QSO
    }  else if((e.which == 13) && ($(document.activeElement).attr("id") == "exch_recv")) {
        logQso();
    } else if (e.which == 27) {
        reset_log_fields();
        // Space to jump to either callsign or sent exchange
    } else if (e.which == 32) {
        if ($(document.activeElement).attr("id") == "callsign") {
            $("#exch_recv").focus();
            return false;
        } else if ($(document.activeElement).attr("id") == "exch_recv") {
            $("#callsign").focus();
            return false;
        }
    }

};

// On Key up check and suggest callsigns
$("#callsign").keyup(function() {
    var call = $(this).val();
    if (call.length >= 3) {
        $.get('lookup/scp/' + call.toUpperCase(), function(result) {
            $('.callsign-suggestions').text(result);
            highlight(call.toUpperCase());
        });
    }
    else if (call.length <= 2) {
        $('.callsign-suggestions').text("");
    }
});

function reset_log_fields() {
    $('#name').val("");
    $('.callsign-suggestions').text("");
    $('#callsign').val("");
    $('#comment').val("");
    $('#exch_recv').val("");
    $("#callsign").focus();
    setRst($("#mode").val());
}

RegExp.escape = function(text) {
    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
}

function highlight(term, base) {
    if (!term) return;
    base = base || document.body;
    var re = new RegExp("(" + RegExp.escape(term) + ")", "gi");
    var replacement = "<span class=\"text-primary\">" + term + "</span>";
    $(".callsign-suggestions", base).contents().each( function(i, el) {
        if (el.nodeType === 3) {
            var data = el.data;
            if (data = data.replace(re, replacement)) {
                var wrapper = $("<span>").html(data);
                $(el).before(wrapper.contents()).remove();
            }
        }
    });
}

// Only set the frequency when not set by userdata/PHP.
if ($('#frequency').val() == "")
{
    $.get('qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function(result) {
        $('#frequency').val(result);
        $('#frequency_rx').val("");
    });
}

/* on mode change */
$('.mode').change(function() {
    $.get('qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function(result) {
        $('#frequency').val(result);
        $('#frequency_rx').val("");
    });
});

/* Calculate Frequency */
/* on band change */
$('#band').change(function() {
    $.get('qso/band_to_freq/' + $(this).val() + '/' + $('.mode').val(), function(result) {
        $('#frequency').val(result);
        $('#frequency_rx').val("");
    });
});

// Change Serial Validation when selected
$('#serial').change(function() {
    if($('#serial').is(':checked')) {
        set_serial_number_input_validation();
    }
});

// Change other serial type when selected
$('#other').change(function() {
    if($('#other').is(':checked')) {
        set_other_input_validation();
    }
});

$('#exchangetype').change(function(){
	var exchangetype = $("#exchangetype").val();
	if (exchangetype == 'None') {
		$(".exchanger").hide();
		$(".exchanges").hide();
		$(".serials").hide();
		$(".serialr").hide();
		$(".gridsquarer").hide();
		$(".gridsquares").hide();
	}
	if (exchangetype == 'Serial') {
		$(".exchanger").hide();
		$(".exchanges").hide();
		$(".serials").show();
		$(".serialr").show();
		$(".gridsquarer").hide();
		$(".gridsquares").hide();
	}
	if (exchangetype == 'Serialexchange') {
		$(".exchanger").show();
		$(".exchanges").show();
		$(".serials").show();
		$(".serialr").show();
		$(".gridsquarer").hide();
		$(".gridsquares").hide();
	}
	if (exchangetype == 'Serialgridsquare') {
		$(".exchanger").hide();
		$(".exchanges").hide();
		$(".serials").show();
		$(".serialr").show();
		$(".gridsquarer").show();
		$(".gridsquares").show();
	}
	if (exchangetype == 'Gridsquare') {
		$(".exchanger").hide();
		$(".exchanges").hide();
		$(".serials").hide();
		$(".serialr").hide();
		$(".gridsquarer").show();
		$(".gridsquares").show();
	}
});

/*
    Function: set_serial_number_input_validation
    Job: This sets the field input to number for validation
*/
function set_serial_number_input_validation() {
    $('#exch_sent').attr('type', 'number');
    $('#exch_recv').attr('type', 'number');
}

/*
    Function: set_other_input_validation
    Job: This sets the field input to text for validation
*/
function set_other_input_validation() {
    $('#exch_sent').attr('type', 'text');
    $('#exch_recv').attr('type', 'text');
}

/*
	Function: logQso
	Job: this handles the logging done in the contesting module.
 */
function logQso() {
	if ($("#callsign").val().length > 0) {

		$('.callsign-suggestions').text("");

		var table = $('.qsotable').DataTable();

		var data = [[$("#start_date").val()+ ' ' + $("#start_time").val(),
			$("#callsign").val().toUpperCase(),
			$("#band").val(),
			$("#mode").val(),
			$("#rst_sent").val(),
			$("#rst_recv").val(),
			$("#exch_sent").val(),
			$("#exch_recv").val()]];

		table.rows.add(data).draw();

		var formdata = new FormData(document.getElementById("qso_input"));
		$.ajax({
			url: base_url + 'index.php/qso/saveqso',
			type: 'post',
			data: formdata,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			success: function (html) {
				if (localStorage.getItem("qso") == null) {
					localStorage.setItem("qso", $("#start_date").val()+ ' ' + $("#start_time").val() + ',' + $("#callsign").val().toUpperCase() + ',' + $("#contestname").val());
				}

				$('#name').val("");

				$('#callsign').val("");
				$('#comment').val("");
				$('#exch_recv').val("");
				if ($('input[name=exchangeradio]:checked', '#qso_input').val() == "serial") {
					$("#exch_sent").val(+$("#exch_sent").val() + 1);
				}
				$("#callsign").focus();

				// Store contest session
				localStorage.setItem("contestid", $("#contestname").val());
				localStorage.setItem("exchangetype", $('input[name=exchangeradio]:checked', '#qso_input').val());
				localStorage.setItem("exchangesent", $("#exch_sent").val());
			}
		});
	}
}

// We are restoring the settings in the contest logging form here
function restoreContestSession() {
	var contestname = localStorage.getItem("contestid");

	if (contestname != null) {
		$("#contestname").val(contestname);
	}

	var exchangetype = localStorage.getItem("exchangetype");

	if (exchangetype == "other") {
		$("[name=exchangeradio]").val(["other"]);
	}

	var exchangesent = localStorage.getItem("exchangesent");

	if (exchangesent != null) {
		$("#exch_sent").val(exchangesent);
	}

	if (localStorage.getItem("qso") != null) {
		var baseURL= "<?php echo base_url();?>";
		//alert(localStorage.getItem("qso"));
		var qsodata = localStorage.getItem("qso");
		$.ajax({
			url: base_url + 'index.php/contesting/getSessionQsos',
			type: 'post',
			data: {'qso': qsodata,},
			success: function (html) {
				var mode = '';
				var sentexchange = '';
				var receivedexchange = '';
				$.each(html, function(){
					if (this.col_submode == null || this.col_submode == '') {
						mode = this.col_mode;
					} else {
						mode = this.col_submode;
					}

					if (this.col_srx == null || this.col_srx == '') {
						receivedexchange = this.col_srx_string;
					} else {
						receivedexchange = this.col_srx;
					}

					if (this.col_stx == null || this.col_stx == '') {
						sentexchange = this.col_stx_string;
					} else {
						sentexchange = this.col_stx;
					}

					$(".qsotable tbody").prepend('<tr>' +
						'<td>'+ this.col_time_on + '</td>' +
						'<td>'+ this.col_call + '</td>' +
						'<td>'+ this.col_band + '</td>' +
						'<td>'+ mode + '</td>' +
						'<td>'+ this.col_rst_sent + '</td>' +
						'<td>'+ this.col_rst_rcvd + '</td>' +
						'<td>'+ sentexchange + '</td>' +
						'<td>'+ receivedexchange + '</td>' +
						'</tr>');
				});
				if (!$.fn.DataTable.isDataTable('.qsotable')) {
					$('.qsotable').DataTable({
						"pageLength": 25,
						responsive: false,
						"scrollY":        "400px",
						"scrollCollapse": true,
						"paging":         false,
						"scrollX": true,
						"order": [[ 0, "desc" ]]
					});
				}
			}
		});
	}
}
