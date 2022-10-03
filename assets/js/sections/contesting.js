// Callsign always has focus on load
$("#callsign").focus();

$(document).ready(function () {
	restoreContestSession();
	setRst($("#mode").val());
});

// This erases the contest logging session which is stored in localStorage
function reset_contest_session() {
	$('#name').val("");
	$('.callsign-suggestions').text("");
	$('#callsign').val("");
	$('#comment').val("");

	$("#exch_serial_s").val("1");
	$("#exch_serial_r").val("");
	$('#exch_sent').val("");
	$('#exch_recv').val("");
	$("#exch_gridsquare_r").val("");
	$("#exch_gridsquare_s").val("");

	$("#callsign").focus();
	setRst($("#mode").val());
	$("#exchangetype").val("None");
	setExchangetype("None");
	$("#contestname").val("Other").change();
	$(".contest_qso_table_contents").empty();
	$('#copyexchangetodok').prop('checked', false);

	localStorage.removeItem("contestid");
	localStorage.removeItem("exchangetype");
	localStorage.removeItem("qso");
	localStorage.removeItem("exchangereceived");
	localStorage.removeItem("exchangesent");
	localStorage.removeItem("serialreceived");
	localStorage.removeItem("serialsent");
	localStorage.removeItem("gridsquarereceived");
	localStorage.removeItem("gridsquaresent");
	localStorage.removeItem("copytodok");
}

// Storing the contestid in contest session
$('#contestname').change(function () {
	localStorage.setItem("contestid", $("#contestname").val());
});

// Storing the exchange type in contest session
$('#exchangetype').change(function () {
	localStorage.setItem("exchangetype", $('#exchangetype').val());
});

// realtime clock
if ( ! manual ) {
	$(function ($) {
		var options = {
			utc: true,
			format: '%H:%M:%S'
		}
		$('.input_time').jclock(options);
	});

	$(function ($) {
		var options = {
			utc: true,
			format: '%d-%m-%Y'
		}
		$('.input_date').jclock(options);
	});
}

// We don't want spaces to be written in callsign
$(function () {
	$('#callsign').on('keypress', function (e) {
		if (e.which == 32) {
			return false;
		}
	});
});

// We don't want spaces to be written in exchange
$(function () {
	$('#exch_recv').on('keypress', function (e) {
		if (e.which == 32) {
			return false;
		}
	});
});

// Here we capture keystrokes to execute functions
document.onkeyup = function (e) {
	// ALT-W wipe
	if (e.altKey && e.which == 87) {
		reset_log_fields();
		// CTRL-Enter logs QSO
	} else if ((e.keyCode == 10 || e.keyCode == 13) && (e.ctrlKey || e.metaKey)) {
		logQso();
		// Enter in sent exchange logs QSO
	} else if ((e.which == 13) && ($(document.activeElement).attr("id") == "exch_recv")) {
		logQso();
	} else if (e.which == 27) {
		reset_log_fields();
		// Space to jump to either callsign or the various exchanges
	} else if (e.which == 32) {
		var exchangetype = $("#exchangetype").val();
		if (exchangetype == 'Exchange') {
			if ($(document.activeElement).attr("id") == "callsign") {
				$("#exch_recv").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_recv") {
				$("#callsign").focus();
				return false;
			}
		}
		else if (exchangetype == 'Serial') {
			if ($(document.activeElement).attr("id") == "callsign") {
				$("#exch_serial_r").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_serial_r") {
				$("#callsign").focus();
				return false;
			}
		}
		else if (exchangetype == 'Serialexchange') {
			if ($(document.activeElement).attr("id") == "callsign") {
				$("#exch_serial_r").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_serial_r") {
				$("#exch_recv").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_recv") {
				$("#callsign").focus();
				return false;
			}
		}
		else if (exchangetype == 'Serialgridsquare') {
			if ($(document.activeElement).attr("id") == "callsign") {
				$("#exch_serial_r").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_serial_r") {
				$("#exch_gridsquare_r").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_gridsquare_r") {
				$("#callsign").focus();
				return false;
			}
		}
		else if (exchangetype == 'Gridsquare') {
			if ($(document.activeElement).attr("id") == "callsign") {
				$("#exch_gridsquare_r").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_gridsquare_r") {
				$("#callsign").focus();
				return false;
			}
		}

	}

};

/* time input shortcut */
$('#start_time').change(function() {
	var raw_time = $(this).val();
	if(raw_time.match(/^\d\[0-6]d$/)) {
		raw_time = "0"+raw_time;
	}
	if(raw_time.match(/^[012]\d[0-5]\d$/)) {
		raw_time = raw_time.substring(0,2)+":"+raw_time.substring(2,4);
		$('#start_time').val(raw_time);
	}
});

/* date input shortcut */
$('#start_date').change(function() {
	 raw_date = $(this).val();
	if(raw_date.match(/^[12]\d{3}[01]\d[0123]\d$/)) {
		raw_date = raw_date.substring(0,4)+"-"+raw_date.substring(4,6)+"-"+raw_date.substring(6,8);
		$('#start_date').val(raw_date);
	}
});

// On Key up check and suggest callsigns
$("#callsign").keyup(function () {
	var call = $(this).val();
	if (call.length >= 3) {
		$.get('lookup/scp/' + call.toUpperCase(), function (result) {
			$('.callsign-suggestions').text(result);
			highlight(call.toUpperCase());
		});
		checkIfWorkedBefore();
	}
	else if (call.length <= 2) {
		$('.callsign-suggestions').text("");
	}
});

function checkIfWorkedBefore() {
	$('#callsign_info').text("");
	$.ajax({
		url: base_url + 'index.php/contesting/checkIfWorkedBefore',
		type: 'post',
		data: {
			'call': $("#callsign").val(),
			'mode': $("#mode").val(),
			'band': $("#band").val(),
			'contest': $("#contestname").val(),
			'qso': localStorage.getItem("qso")
		},
		success: function (result) {
			if (result.message == 'Worked before') {
				$('#callsign_info').text("Worked before!");
			}
		}
	});
}

function reset_log_fields() {
	$('#name').val("");
	$('.callsign-suggestions').text("");
	$('#callsign').val("");
	$('#comment').val("");
	$('#exch_recv').val("");
	$('#exch_serial_r').val("");
	$('#exch_gridsquare_r').val("");
	$("#callsign").focus();
	setRst($("#mode").val());
	$('#callsign_info').text("");
}

RegExp.escape = function (text) {
	return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
}

function highlight(term, base) {
	if (!term) return;
	base = base || document.body;
	var re = new RegExp("(" + RegExp.escape(term) + ")", "gi");
	var replacement = "<span class=\"text-primary\">" + term + "</span>";
	$(".callsign-suggestions", base).contents().each(function (i, el) {
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
if ($('#frequency').val() == "") {
	$.get('qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function (result) {
		$('#frequency').val(result);
		$('#frequency_rx').val("");
	});
}

/* on mode change */
$('#mode').change(function () {
	$.get('qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function (result) {
		$('#frequency').val(result);
		$('#frequency_rx').val("");
	});
	setRst($("#mode").val());
	checkIfWorkedBefore();
});

/* Calculate Frequency */
/* on band change */
$('#band').change(function () {
	$.get('qso/band_to_freq/' + $(this).val() + '/' + $('.mode').val(), function (result) {
		$('#frequency').val(result);
		$('#frequency_rx').val("");
	});
	checkIfWorkedBefore();
});

$('#exchangetype').change(function () {
	var exchangetype = $("#exchangetype").val();
	setExchangetype(exchangetype);
});

function setExchangetype(exchangetype) {
	// Perhaps a better approach is to hide everything, then just enable the things you need
	$(".exchanger").hide();
	$(".exchanges").hide();
	$(".serials").hide();
	$(".serialr").hide();
	$(".gridsquarer").hide();
	$(".gridsquares").hide();
	$("#exch_serial_s").val("");

	var serialsent = localStorage.getItem("serialsent");
	if (serialsent == null) {
		serialsent = 1;
	}

	if (exchangetype == 'Exchange') {
		$(".exchanger").show();
		$(".exchanges").show();
	}
	else if (exchangetype == 'Serial') {
		$("#exch_serial_s").val(serialsent);
		$(".serials").show();
		$(".serialr").show();
	}
	else if (exchangetype == 'Serialexchange') {
		$("#exch_serial_s").val(serialsent);
		$(".exchanger").show();
		$(".exchanges").show();
		$(".serials").show();
		$(".serialr").show();
	}
	else if (exchangetype == 'Serialgridsquare') {
		$("#exch_serial_s").val(serialsent);
		$(".serials").show();
		$(".serialr").show();
		$(".gridsquarer").show();
		$(".gridsquares").show();
	}
	else if (exchangetype == 'Gridsquare') {
		$(".gridsquarer").show();
		$(".gridsquares").show();
	}
}

/*
	Function: logQso
	Job: this handles the logging done in the contesting module.
 */
function logQso() {
	if ($("#callsign").val().length > 0) {

		$('.callsign-suggestions').text("");

		var table = $('.qsotable').DataTable();
		var gridsquare = $("#exch_gridsquare_r").val();
		var vucc = '';

		if (gridsquare.indexOf(',') != -1) {
			vucc = gridsquare;
			gridsquare = '';
		}

		var data = [[
			$("#start_date").val() + ' ' + $("#start_time").val(),
			$("#callsign").val().toUpperCase(),
			$("#band").val(),
			$("#mode").val(),
			$("#rst_sent").val(),
			$("#rst_recv").val(),
			$("#exch_sent").val(),
			$("#exch_recv").val(),
			$("#exch_serial_s").val(),
			$("#exch_serial_r").val(),
			gridsquare,
			vucc,
		]];

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
					localStorage.setItem("qso", $("#start_date").val() + ' ' + $("#start_time").val() + ',' + $("#callsign").val().toUpperCase() + ',' + $("#contestname").val());
				}

				$('#name').val("");

				$('#callsign').val("");
				$('#comment').val("");
				$('#exch_recv').val("");
				$('#exch_gridsquare_r').val("");
				$('#exch_serial_r').val("");
				var exchangetype = $("#exchangetype").val();
				if (exchangetype == "Serial" || exchangetype == 'Serialexchange' || exchangetype == 'Serialgridsquare') {
					$("#exch_serial_s").val(+$("#exch_serial_s").val() + 1);
				}
				$("#callsign").focus();

				// Store contest session
				localStorage.setItem("contestid", $("#contestname").val());
				localStorage.setItem("exchangetype", $("#exchangetype").val());
				localStorage.setItem("exchangereceived", $("#exch_recv").val());
				localStorage.setItem("exchangesent", $("#exch_sent").val());
				localStorage.setItem("serialreceived", $("#exch_serial_r").val());
				localStorage.setItem("serialsent", $("#exch_serial_s").val());
				localStorage.setItem("gridsquarereceived", $("#exch_gridsquare_r").val());
				localStorage.setItem("gridsquaresent", $("#exch_gridsquare_s").val());
				localStorage.setItem("copytodok", $('#copyexchangetodok').is(":checked"));
			}
		});
	}
}

// We are restoring the settings in the contest logging form here
function restoreContestSession() {
	var dokcopy = localStorage.getItem("copytodok");
	if (dokcopy != null) {
		$('#copyexchangetodok').prop('checked', true);
	}

	var contestname = localStorage.getItem("contestid");
	if (contestname != null) {
		$("#contestname").val(contestname);
	}

	var exchangetype = localStorage.getItem("exchangetype");
	if (exchangetype != null) {
		$("#exchangetype").val(exchangetype);
		setExchangetype(exchangetype);
	}

	var exchangereceived = localStorage.getItem("exchangereceived");
	if (exchangereceived != null) {
		$("#exch_recv").val(exchangereceived);
	}

	var exchangesent = localStorage.getItem("exchangesent");
	if (exchangesent != null) {
		$("#exch_sent").val(exchangesent);
	}

	var serialreceived = localStorage.getItem("serialreceived");
	if (serialreceived != null) {
		$("#exch_serial_r").val(serialreceived);
	}

	var serialsent = localStorage.getItem("serialsent");
	if (serialsent != null) {
		$("#exch_serial_s").val(serialsent);
	}

	var gridsquarereceived = localStorage.getItem("gridsquarereceived");
	if (gridsquarereceived != null) {
		$("#exch_gridsquare_r").val(gridsquarereceived);
	}

	var gridsquaresent = localStorage.getItem("gridsquaresent");
	if (gridsquaresent != null) {
		$("#exch_gridsquare_s").val(gridsquaresent);
	}

	if (localStorage.getItem("qso") != null) {
		var qsodata = localStorage.getItem("qso");
		$.ajax({
			url: base_url + 'index.php/contesting/getSessionQsos',
			type: 'post',
			data: { 'qso': qsodata, },
			success: function (html) {
				var mode = '';

				$.each(html, function () {
					if (this.col_submode == null || this.col_submode == '') {
						mode = this.col_mode;
					} else {
						mode = this.col_submode;
					}

					$(".qsotable tbody").prepend('<tr>' +
						'<td>' + this.col_time_on + '</td>' +
						'<td>' + this.col_call + '</td>' +
						'<td>' + this.col_band + '</td>' +
						'<td>' + mode + '</td>' +
						'<td>' + this.col_rst_sent + '</td>' +
						'<td>' + this.col_rst_rcvd + '</td>' +
						'<td>' + this.col_stx_string + '</td>' +
						'<td>' + this.col_srx_string + '</td>' +
						'<td>' + this.col_stx + '</td>' +
						'<td>' + this.col_srx + '</td>' +
						'<td>' + this.col_gridsquare + '</td>' +
						'<td>' + this.col_vucc_grids + '</td>' +
						'</tr>');
				});
				if (!$.fn.DataTable.isDataTable('.qsotable')) {
					$('.qsotable').DataTable({
						"pageLength": 25,
						responsive: false,
						"scrollY": "400px",
						"scrollCollapse": true,
						"paging": false,
						"scrollX": true,
						"order": [[0, "desc"]]
					});
				}
			}
		});
	}
}
