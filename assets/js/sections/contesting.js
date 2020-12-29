// Callsign always has focus on load
$("#callsign").focus();

// Init serial sent as 1 when loading page
$("#exch_sent").val(1);

$( document ).ready(function() {
    restoreContestSession();
    setRst($("#mode").val());
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