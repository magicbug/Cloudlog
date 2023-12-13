// Callsign always has focus on load
$("#callsign").focus();

var sessiondata={};
$(document).ready(async function () {
	sessiondata=await getSession();			// save sessiondata global (we need it later, when adding qso)
	await restoreContestSession(sessiondata);	// wait for restoring until finished
	setRst($("#mode").val());
});

// Resets the logging form and deletes session from database
function reset_contest_session() {
	$('#name').val("");
	$('.callsign-suggestions').text("");
	$('#callsign').val("");
	$('#comment').val("");

	$("#exch_serial_s").val("1");
	$("#exch_serial_r").val("");
	$('#exch_sent').val("");
	$('#exch_rcvd').val("");
	$("#exch_gridsquare_r").val("");

	$("#callsign").focus();
	setRst($("#mode").val());
	$("#exchangetype").val("None");
	setExchangetype("None");
	$("#contestname").val("Other").change();
	$(".contest_qso_table_contents").empty();
	$('#copyexchangetodok').prop('checked', false);

	$.ajax({
		url: base_url + 'index.php/contesting/deleteSession',
		type: 'post',
		success: function (data) {

		}
	});
}

// Storing the contestid in contest session
$('#contestname').change(function () {
	var formdata = new FormData(document.getElementById("qso_input"));
	setSession(formdata);
});

// Storing the exchange type in contest session
$('#exchangetype').change(function () {
	var exchangetype = $("#exchangetype").val();
	var formdata = new FormData(document.getElementById("qso_input"));
	setSession(formdata);
	setExchangetype(exchangetype);
});

function setSession(formdata) {
	$.ajax({
		url: base_url + 'index.php/contesting/setSession',
		type: 'post',
		data: formdata,
		processData: false,
		contentType: false,
		success: function (data) {

		}
	});
}

// realtime clock
if ( ! manual ) {
	$(function ($) {
		handleStart = setInterval(function() { getUTCTimeStamp($('.input_time')); }, 500);
	});

	$(function ($) {
		          handleDate = setInterval(function() { getUTCDateStamp($('.input_date')); }, 1000);
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
	$('#exch_rcvd').on('keypress', function (e) {
		if (e.which == 32) {
			return false;
		}
	});
});

// We don't want spaces to be written in serial
$(function () {
	$('#exch_serial_r').on('keypress', function (e) {
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
		// Enter in received exchange logs QSO
	} else if ((e.which == 13) && (
			($(document.activeElement).attr("id") == "exch_rcvd")
			|| ($(document.activeElement).attr("id") == "exch_gridsquare_r")
			|| ($(document.activeElement).attr("id") == "exch_serial_r")
		)
	) {
		logQso();
	} else if (e.which == 27) {
		reset_log_fields();
		// Space to jump to either callsign or the various exchanges
	} else if (e.which == 32) {
		var exchangetype = $("#exchangetype").val();
		if (exchangetype == 'Exchange') {
			if ($(document.activeElement).attr("id") == "callsign") {
				$("#exch_rcvd").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_rcvd") {
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
				$("#exch_rcvd").focus();
				return false;
			} else if ($(document.activeElement).attr("id") == "exch_rcvd") {
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

		$.ajax({
			url: 'lookup/scp',
			method: 'POST',
			data: {
			  callsign: $(this).val().toUpperCase()
			},
			success: function(result) {
			  $('.callsign-suggestions').text(result);
			  highlight(call.toUpperCase());
			}
		  });

		checkIfWorkedBefore();
		var qTable = $('.qsotable').DataTable();
		qTable.search(call).draw();
	}
	else if (call.length <= 2) {
		$('.callsign-suggestions').text("");
	}
});

function checkIfWorkedBefore() {
	var call = $("#callsign").val();
	if (call.length >= 3) {
		$('#callsign_info').text("");
		$.ajax({
			url: base_url + 'index.php/contesting/checkIfWorkedBefore',
			type: 'post',
			data: {
				'call': call,
				'mode': $("#mode").val(),
				'band': $("#band").val(),
				'contest': $("#contestname").val()
			},
			success: function (result) {
				if (result.message == 'Worked before') {
					$('#callsign_info').text("Worked before!");
				}
			}
		});
	}
}

async function reset_log_fields() {
	$('#name').val("");
	$('.callsign-suggestions').text("");
	$('#callsign').val("");
	$('#comment').val("");
	$('#exch_rcvd').val("");
	$('#exch_serial_r').val("");
	$('#exch_gridsquare_r').val("");
	$("#callsign").focus();
	setRst($("#mode").val());
	$('#callsign_info').text("");

	await refresh_qso_table(sessiondata);
	var qTable = $('.qsotable').DataTable();
	qTable.search('').draw();
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

function setSerial(data) {
	var serialsent = 1;
	if (data.serialsent != "") {
		serialsent = parseInt(data.serialsent);
	}
	$("#exch_serial_s").val(serialsent);
}

function setExchangetype(exchangetype) {
	// Perhaps a better approach is to hide everything, then just enable the things you need
	$(".exchanger").hide();
	$(".exchanges").hide();
	$(".serials").hide();
	$(".serialr").hide();
	$(".gridsquarer").hide();
	$(".gridsquares").hide();

	if (exchangetype == 'Exchange') {
		$(".exchanger").show();
		$(".exchanges").show();
	}
	else if (exchangetype == 'Serial') {
		$(".serials").show();
		$(".serialr").show();
	}
	else if (exchangetype == 'Serialexchange') {
		$(".exchanger").show();
		$(".exchanges").show();
		$(".serials").show();
		$(".serialr").show();
	}
	else if (exchangetype == 'Serialgridsquare') {
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
		var exchangetype = $("#exchangetype").val();

		var gridsquare = $("#exch_gridsquare_r").val();
		var vucc = '';

		if (gridsquare.indexOf(',') != -1) {
			vucc = gridsquare;
			gridsquare = '';
		}

		var gridr = '';
		var vuccr = '';
		var exchsent = '';
		var exchrcvd = '';
		var serials = '';
		var serialr = '';

		switch (exchangetype) {
			case 'Exchange':
				exchsent = $("#exch_sent").val();
				exchrcvd = $("#exch_rcvd").val();
			break;

			case 'Gridsquare':
				gridr = gridsquare;
				vuccr = vucc;
			break;

			case 'Serial':
				serials = $("#exch_serial_s").val();
				serialr = $("#exch_serial_r").val();
			break;
			
			case 'Serialexchange':
				exchsent = $("#exch_sent").val();
				exchrcvd = $("#exch_rcvd").val();
				serials = $("#exch_serial_s").val();
				serialr = $("#exch_serial_r").val();
			break;
			
			case 'Serialgridsquare':
				gridr = gridsquare;
				vuccr = vucc;
				serials = $("#exch_serial_s").val();
				serialr = $("#exch_serial_r").val();
			break;
		}

		var data = [[
			$("#start_date").val() + ' ' + $("#start_time").val(),
			$("#callsign").val().toUpperCase(),
			$("#band").val(),
			$("#mode").val(),
			$("#rst_sent").val(),
			$("#rst_rcvd").val(),
			exchsent,
			exchrcvd,
			serials,
			serialr,
			gridr,
			vuccr,
		]];

		var formdata = new FormData(document.getElementById("qso_input"));
		$.ajax({
			url: base_url + 'index.php/qso/saveqso',
			type: 'post',
			data: formdata,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			success: async function (html) {
				var exchangetype = $("#exchangetype").val();
				if (exchangetype == "Serial" || exchangetype == 'Serialexchange' || exchangetype == 'Serialgridsquare') {
					$("#exch_serial_s").val(+$("#exch_serial_s").val() + 1);
					formdata.set('exch_serial_s', $("#exch_serial_s").val());
				}

				$('#name').val("");
				
				$('#callsign').val("");
				$('#comment').val("");
				$('#exch_rcvd').val("");
				$('#exch_gridsquare_r').val("");
				$('#exch_serial_r').val("");
				$("#callsign").focus();
				setSession(formdata);
				
				await refresh_qso_table(sessiondata);
				var qTable = $('.qsotable').DataTable();
				qTable.search('').order([0, 'desc']).draw();

			}
		});
	}
}

async function getSession() {
	return await $.ajax({
		url: base_url + 'index.php/contesting/getSession',
		type: 'post',
	});
}
	
async function restoreContestSession(data) {
	if (data) {
		if (data.copytodok == "1") {
			$('#copyexchangetodok').prop('checked', true);
		}
	
		if (data.contestid != "") {
			$("#contestname").val(data.contestid);
		}
	
		if (data.exchangetype != "") {
			$("#exchangetype").val(data.exchangetype);
			setExchangetype(data.exchangetype);
			setSerial(data);
		}
	
		if (data.exchangesent != "") {
			$("#exch_sent").val(data.exchangesent);
		}

		if (data.qso != "") {
			await refresh_qso_table(data);
		}
	} else {
		$("#exch_serial_s").val("1");
	}
}

async function refresh_qso_table(data) {
	$.ajax({
		url: base_url + 'index.php/contesting/getSessionQsos',
		type: 'post',
		data: { 'qso': data.qso, },
		success: function (html) {
			var mode = '';
			$(".contest_qso_table_contents").empty();
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
				$.fn.dataTable.moment('DD-MM-YYYY HH:mm:ss');
				$('.qsotable').DataTable({
					"stateSave": true,
					"pageLength": 25,
					responsive: false,
					"scrollY": "400px",
					"scrollCollapse": true,
					"paging": false,
					"scrollX": true,
					order: [0, 'desc'],
					"columnDefs": [
						{
							"render": function ( data, type, row ) {
								return pad(row[8],3);
							},
							"targets" : 8
						},
						{
							"render": function ( data, type, row ) {
								return pad(row[9],3);
							},
							"targets" : 9
						}
					]
				});
			}
		}
	});
}

function pad (str, max) {
	str = str.toString();
	return str.length < max ? pad("0" + str, max) : str;
}

function getUTCTimeStamp(el) {
	var now = new Date();
	var localTime = now.getTime();
	var utc = localTime + (now.getTimezoneOffset() * 60000);
	$(el).attr('value', ("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2)+':'+("0" + now.getUTCSeconds()).slice(-2));
}

function getUTCDateStamp(el) {
	var now = new Date();
	var localTime = now.getTime();
	var utc = localTime + (now.getTimezoneOffset() * 60000);
	$(el).attr('value', ("0" + now.getUTCDate()).slice(-2)+'-'+("0" + (now.getUTCMonth()+1)).slice(-2)+'-'+now.getUTCFullYear());
}
