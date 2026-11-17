// Callsign always has focus on load
$("#callsign").focus();

var sessiondata = {};
$(document).ready(function () {
	(async function() {
		sessiondata = await getSession();			// save sessiondata global (we need it later, when adding qso)
		await restoreContestSession(sessiondata);	// wait for restoring until finished
		setRst($("#mode").val());
		setContestingTabOrder($("#exchangetype").val());
		$("#callsign").focus().select();
	})();
	renderCallhistoryPanel([]);

	/* On Key up Calculate Bearing and Distance for Contest Gridsquare */
	$(document).on('keyup', '#exch_gridsquare_r', function(){
		calculateContestBearingDistance();
	});

	/* On Change also calculate Bearing and Distance for Contest Gridsquare */
	$(document).on('change', '#exch_gridsquare_r', function(){
		calculateContestBearingDistance();
	});
});

function escapeHtml(unsafeText) {
	return String(unsafeText || '').replace(/[&<>"]/g, function (tag) {
		var replacements = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;'
		};
		return replacements[tag] || tag;
	});
}

function normalizeCallhistoryText(value) {
	return String(value || '').trim().toLowerCase();
}

function renderCallhistoryPanel(matches) {
	var $card = $('#callhistory-info-panel');
	var $panel = $('#callhistory-results');
	if ($panel.length === 0 || $card.length === 0) {
		return;
	}

	if (!matches || matches.length === 0) {
		$card.hide();
		return;
	}

	var html = '<ul class="list-group list-group-flush">';

	$.each(matches, function (_, match) {
		var organizationLabel = String(match.organization_label || 'Member');
		var membershipNumber = String(match.exch1 || '');
		var memberName = String(match.name || '');
		var normalizedMembershipNumber = normalizeCallhistoryText(membershipNumber);
		var normalizedMemberName = normalizeCallhistoryText(memberName);

		var line = '<strong>' + escapeHtml(organizationLabel) + '</strong>';
		if (membershipNumber && normalizeCallhistoryText(organizationLabel).indexOf(normalizedMembershipNumber) === -1) {
			line += ' #' + escapeHtml(membershipNumber);
		}
		if (memberName && normalizedMemberName !== normalizedMembershipNumber) {
			line += ' - ' + escapeHtml(memberName);
		}

		html += '<li class="list-group-item px-0 py-2">' + line + '</li>';
	});

	html += '</ul>';
	$panel.html(html);
	$card.show();
}

function lookupCallhistory(call) {
	$.ajax({
		url: base_url + 'index.php/callhistory/lookup',
		type: 'post',
		data: { callsign: call },
		success: function (response) {
			if (!response || response.status !== 'ok') {
				renderCallhistoryPanel([]);
				return;
			}
			renderCallhistoryPanel(response.matches || []);
		},
		error: function () {
			renderCallhistoryPanel([]);
		}
	});
}

function setContestingTabOrder(exchangetype) {
	var orderedFieldIds = ['callsign', 'rst_sent'];

	switch (exchangetype) {
		case 'Exchange':
			orderedFieldIds.push('exch_sent');
			orderedFieldIds.push('rst_rcvd');
			orderedFieldIds.push('exch_rcvd');
			break;
		case 'Gridsquare':
			orderedFieldIds.push('rst_rcvd');
			orderedFieldIds.push('exch_gridsquare_r');
			break;
		case 'Serial':
			orderedFieldIds.push('exch_serial_s');
			orderedFieldIds.push('rst_rcvd');
			orderedFieldIds.push('exch_serial_r');
			break;
		case 'Serialexchange':
			orderedFieldIds.push('exch_serial_s');
			orderedFieldIds.push('exch_sent');
			orderedFieldIds.push('rst_rcvd');
			orderedFieldIds.push('exch_serial_r');
			orderedFieldIds.push('exch_rcvd');
			break;
		case 'Serialgridsquare':
			orderedFieldIds.push('exch_serial_s');
			orderedFieldIds.push('rst_rcvd');
			orderedFieldIds.push('exch_serial_r');
			orderedFieldIds.push('exch_gridsquare_r');
			break;
		default:
			orderedFieldIds.push('rst_rcvd');
			break;
	}

	orderedFieldIds.push('name');
	orderedFieldIds.push('comment');
	orderedFieldIds.push('save_qso');

	$('#qso_input').find('input, select, button, textarea').attr('tabindex', '-1');

	var tabindex = 1;
	orderedFieldIds.forEach(function (id) {
		var $field = $('#' + id);
		if ($field.length === 0 || $field.is(':disabled')) {
			return;
		}

		if (!$field.is(':visible')) {
			return;
		}

		$field.attr('tabindex', tabindex);
		tabindex += 1;
	});
}

function calculateCallsignBearingDistance(callsign) {
	if (!callsign || callsign.length < 3) {
		return;
	}

	// Only proceed if we have a home gridsquare
	if (!my_gridsquare || my_gridsquare.length < 4) {
		return;
	}

	// Look up the callsign's QRA and get bearing/distance
	$.ajax({
		url: base_url + 'index.php/logbook/contest_callsign_qra',
		type: 'post',
		data: {
			callsign: callsign,
			my_grid: my_gridsquare
		},
		success: function(data) {
			if (data && (data.bearing !== '' || data.distance > 0)) {
				var unit = (measurement_base === 'M' ? ' mi' : measurement_base === 'N' ? ' nmi' : ' km');

				// Display in the always-visible DXCC bearing area
				if (data.bearing !== '' && data.bearing !== undefined) {
					$('#locator_info_contest_dxcc').html(String(data.bearing) + '°');
					$('#locator_info_contest_dxcc').show();
				}
				if (data.distance && data.distance > 0) {
					$('#distance_contest_dxcc').text(parseFloat(data.distance).toFixed(0) + unit);
					$('#distance_contest_dxcc').show();
				}
			}
		},
		error: function(xhr, status, error) {
			console.log("Callsign QRA lookup error: " + error);
		},
	});
}

function calculateContestBearingDistance() {
	var received_grid = $("#exch_gridsquare_r").val();
	
	if (!received_grid || received_grid.length < 4) {
		$('#locator_info_contest').text("");
		$('#distance_contest').val("");
		return;
	}

	// Only proceed if we have a home gridsquare
	if (!my_gridsquare || my_gridsquare.length < 4) {
		$('#locator_info_contest').text("No home grid");
		return;
	}

	// Call backend to calculate bearing
	$.ajax({
		url: base_url + 'index.php/logbook/contest_bearing',
		type: 'post',
		data: {
			grid: received_grid,
			my_grid: my_gridsquare
		},
		success: function(data) {
			if (data && data.length > 0) {
				// Format bearing with degree symbol
				$('#locator_info_contest').html(data.trim() + '°');
			} else {
				$('#locator_info_contest').text("");
			}
		},
		error: function(xhr, status, error) {
			console.log("Bearing error: " + error);
		},
	});

	// Call backend to calculate distance
	$.ajax({
		url: base_url + 'index.php/logbook/contest_distance',
		type: 'post',
		data: {
			grid: received_grid,
			my_grid: my_gridsquare
		},
		success: function(data) {
			if (data && data.length > 0 && !isNaN(data)) {
				// Format distance with unit based on user preference
				var distance_value = parseFloat(data).toFixed(2);
				var unit = ' km'; // Default
				
				if (measurement_base === 'M') {
					unit = ' mi';
				} else if (measurement_base === 'N') {
					unit = ' nmi';
				}
				
				$('#distance_contest').val(distance_value + unit);
			} else {
				$('#distance_contest').val("");
			}
		},
		error: function(xhr, status, error) {
			console.log("Distance error: " + error);
		},
	});
}

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
	$('#locator_info_contest').text("");
	$('#distance_contest').val("");
	$('#locator_info_contest_dxcc').text("");
	$('#distance_contest_dxcc').text("");
	$('#locator_info_contest_dxcc').hide();
	$('#distance_contest_dxcc').hide();

	$("#callsign").focus();
	setRst($("#mode").val());
	$("#exchangetype").val("None");
	setExchangetype("None");
	$("#contestname").val("Other").change();
	$(".contest_qso_table_contents").empty();
	$('#copyexchangeto').val("None");

	$.ajax({
		url: base_url + 'index.php/contesting/deleteSession',
		type: 'post',
		success: function (data) {

		}
	});
}

// Storing the contestid in contest session
$('#contestname, #copyexchangeto').change(function () {
	var formdata = new FormData(document.getElementById("qso_input"));
	setSession(formdata);
});

// Storing the exchange type in contest session
$('#exchangetype').change(function () {
	var exchangetype = $("#exchangetype").val();
	var formdata = new FormData(document.getElementById("qso_input"));
	setSession(formdata);
	setExchangetype(exchangetype);
	setContestingTabOrder(exchangetype);
});

function setSession(formdata) {
    formdata.set('copyexchangeto',$("#copyexchangeto option:selected").index());
	return $.ajax({
		url: base_url + 'index.php/contesting/setSession',
		type: 'post',
		data: formdata,
		processData: false,
		contentType: false,
	});
}

// realtime clock
if (!manual) {
	$(function ($) {
		handleStart = setInterval(function () { getUTCTimeStamp($('.input_time')); }, 500);
	});

	$(function ($) {
		handleDate = setInterval(function () { getUTCDateStamp($('.input_date')); }, 1000);
	});
}

// We don't want spaces to be written in callsign
// We don't want spaces to be written in exchange
// We don't want spaces to be written in time :)
$(function () {
	$('#callsign, #exch_rcvd, #start_time').on('keypress', function (e) {
		if (e.which == 32) {
			return false;
		}
	});
});

// We don't want anything but numbers to be written in serial
$(function () {
	$('#exch_serial_r, #exch_serial_s').on('keypress', function (e) {
		if (e.key.charCodeAt(0) < 48 || e.key.charCodeAt(0) > 57) {
			return false;
		}
	});
});

// checked if worked before after blur
$("#callsign").blur(function () {
		 checkIfWorkedBefore();
		// Restore full logbook table once user moves away from callsign field
		if ($.fn.DataTable.isDataTable('.qsotable')) {
			$('.qsotable').DataTable().search('').draw();
		}
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

        if (manual && $(document.activeElement).attr("id") == "start_time") {
          $("#callsign").focus();
          return false;
        }
        
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
$('#start_time').change(function () {
	var raw_time = $(this).val();
	if (raw_time.match(/^\d\[0-6]d$/)) {
		raw_time = "0" + raw_time;
	}
	if (raw_time.match(/^[012]\d[0-5]\d$/)) {
		raw_time = raw_time.substring(0, 2) + ":" + raw_time.substring(2, 4);
		$('#start_time').val(raw_time);
	}
});

/* date input shortcut */
$('#start_date').change(function () {
	raw_date = $(this).val();
	if (raw_date.match(/^[12]\d{3}[01]\d[0123]\d$/)) {
		raw_date = raw_date.substring(0, 4) + "-" + raw_date.substring(4, 6) + "-" + raw_date.substring(6, 8);
		$('#start_date').val(raw_date);
	}
});

// On Key up check and suggest callsigns
var dupeCheckTimer = null;
$("#callsign").keyup(function () {
	var call = $(this).val();
	if (call.length >= 3) {

		$.ajax({
			url: 'lookup/scp',
			method: 'POST',
			data: {
				callsign: $(this).val().toUpperCase()
			},
			success: function (result) {
				if (result && result.trim() !== '') {
					$('.callsign-suggestions').text(result);
					highlight(call.toUpperCase());
					$('.callsign-suggest').show();
				} else {
					$('.callsign-suggestions').text('');
					$('.callsign-suggest').hide();
				}
			}
		});
		// Debounced dupe check while typing
		clearTimeout(dupeCheckTimer);
		dupeCheckTimer = setTimeout(function() { checkIfWorkedBefore(); }, 400);
		var qTable = $('.qsotable').DataTable();
		qTable.search(call).draw();
		lookupCallhistory(call.toUpperCase());
	}
	else if (call.length <= 2) {
		$('.callsign-suggestions').text("");
		$('.callsign-suggest').hide();
		$('#callsign').css({'border-color': '', 'box-shadow': ''});
		$('#callsign_info').text("").removeClass('text-bg-danger text-bg-success');
		renderCallhistoryPanel([]);
		if ($.fn.DataTable.isDataTable('.qsotable')) {
			$('.qsotable').DataTable().search('').draw();
		}
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
				if (result.message.substr(0, 6) == 'Worked') {
					$('#callsign_info').removeClass('text-bg-success');
					$('#callsign_info').addClass('text-bg-danger');
					$('#callsign_info').text(result.message);
					$('#callsign').css({'border-color': '#dc3545', 'box-shadow': '0 0 0 0.2rem rgba(220,53,69,.25)'});
				}
				else if (result.message == "OKAY") {
					$('#callsign_info').removeClass('text-bg-danger');
					$('#callsign_info').addClass('text-bg-success');
					$('#callsign_info').text("Go Work Them!");
					$('#callsign').css({'border-color': '#198754', 'box-shadow': '0 0 0 0.2rem rgba(25,135,84,.25)'});
				} else {
					$('#callsign_info').text("");
					$('#callsign').css({'border-color': '', 'box-shadow': ''});
				}
			}
		});

		// If gridsquare field is empty, try to get it from callsign lookup
		if ($("#exch_gridsquare_r").val().length === 0) {
			calculateCallsignBearingDistance(call);
		}
	} else {
		$('#callsign_info').text("").removeClass('text-bg-danger text-bg-success');
		$('#callsign').css({'border-color': '', 'box-shadow': ''});
	}
}

async function reset_log_fields() {
	$('#name').val("");
	$('.callsign-suggestions').text("");
	$('.callsign-suggest').hide();
	$('#callsign').val("").css({'border-color': '', 'box-shadow': ''});
	$('#comment').val("");
	$('#exch_rcvd').val("");
	$('#exch_serial_r').val("");
	$('#exch_gridsquare_r').val("");
	$('#locator_info_contest').text("");
	$('#distance_contest').val("");
	$('#locator_info_contest_dxcc').text("");
	$('#distance_contest_dxcc').text("");
	$('#locator_info_contest_dxcc').hide();
	$('#distance_contest_dxcc').hide();
	$("#callsign").focus();
	setRst($("#mode").val());
	$('#callsign_info').text("").removeClass('text-bg-danger text-bg-success');
	renderCallhistoryPanel([]);

	sessiondata = await getSession();
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

	setContestingTabOrder(exchangetype);
	updateTableColumns(exchangetype);
}

async function completeContestQsoSave(formdata) {
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
	$('.callsign-suggestions').text("");
	$('.callsign-suggest').hide();
	$('#callsign').css({'border-color': '', 'box-shadow': ''});
	$('#callsign_info').text("").removeClass('text-bg-danger text-bg-success');
	renderCallhistoryPanel([]);
	if (manual) {
		$("#start_time").focus().select();
	} else {
		$("#callsign").focus();
	}
	await setSession(formdata);

	sessiondata = await getSession();
	await refresh_qso_table(sessiondata);
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
			$("#callsign").val($("#callsign").val().replace(/\s+/g, '').toUpperCase()),
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
		var clientRef = (window.CloudlogOfflineQsoQueue)
			? window.CloudlogOfflineQsoQueue.generateClientRef()
			: '';
		if (clientRef) {
			formdata.set('client_ref', clientRef);
		}
		var saveUrl = base_url + 'index.php/qso/saveqso';
		$.ajax({
			url: saveUrl,
			type: 'post',
			data: formdata,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			success: async function () {
				await completeContestQsoSave(formdata);
			},
			error: async function (xhr, textStatus) {
				if (window.CloudlogOfflineQsoQueue && window.CloudlogOfflineQsoQueue.shouldEnqueue(xhr, textStatus)) {
					window.CloudlogOfflineQsoQueue.enqueue({
						clientRef: clientRef,
						url: saveUrl,
						source: 'contesting',
						payloadType: 'object',
						payload: window.CloudlogOfflineQsoQueue.formDataToObject(formdata),
					});
					window.CloudlogOfflineQsoQueue.showQueuedNotice();
					await completeContestQsoSave(formdata);
					return;
				}
				alert('Unable to save QSO due to a network or server error.');
			},
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
		if (data.copytodok != "") {
			$('#copyexchangeto option')[data.copytodok].selected = true;
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
	if (data && data.qso) {
		$.ajax({
			url: base_url + 'index.php/contesting/getSessionQsos',
			type: 'post',
			data: { 'qso': data.qso, },
			success: function (html) {
				// Destroy DataTables FIRST so DOM manipulation is clean
				if ($.fn.DataTable.isDataTable('.qsotable')) {
					$('.qsotable').DataTable().destroy();
				}
				var mode = '';
				$(".contest_qso_table_contents").empty();
				var dupeCounts = {};
				$.each(html, function () {
					var key = this.col_call + '|' + this.col_band + '|' + this.col_mode;
					dupeCounts[key] = (dupeCounts[key] || 0) + 1;
				});
				$.each(html, function () {
					if (this.col_submode == null || this.col_submode == '') {
						mode = this.col_mode;
					} else {
						mode = this.col_submode;
					}
					var isDupe = dupeCounts[this.col_call + '|' + this.col_band + '|' + this.col_mode] > 1;
					$(".qsotable tbody").prepend('<tr' + (isDupe ? ' class="table-warning"' : '') + '>' +
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
				$.fn.dataTable.moment('DD-MM-YYYY HH:mm:ss');
				$('.qsotable').DataTable({
					"pageLength": 25,
					responsive: false,
					"scrollY": "400px",
					"scrollCollapse": true,
					"paging": false,
					"scrollX": true,
					"dom": 'rt<"bottom"i>',
					"language": {
						url: getDataTablesLanguageUrl(),
					},
					"search": { "search": $('#logbook-search').val() },
					order: [0, 'desc'],
					"columnDefs": [
						{
							"render": function (data, type, row) {
								return pad(row[8], 3);
							},
							"targets": 8
						},
						{
							"render": function (data, type, row) {
								return pad(row[9], 3);
							},
							"targets": 9
						}
					]
				});
				$('#logbook-search').off('keyup.logbook').on('keyup.logbook', function () {
					$('.qsotable').DataTable().search(this.value).draw();
				});
				updateContestStats(html);
				updateTableColumns($('#exchangetype').val());
			}
		});
	} else {
		// Runs when no session is set usually when its a clean contest
		var selectElement = document.getElementById('contestname');
		var selected_contest_id = selectElement.options[selectElement.selectedIndex].value;
		$.ajax({
			url: base_url + 'index.php/contesting/getSessionFreshQsos',
			type: 'post',
			data: { 'contest_id': selected_contest_id },
			success: function (html) {
				// Destroy DataTables FIRST so DOM manipulation is clean
				if ($.fn.DataTable.isDataTable('.qsotable')) {
					$('.qsotable').DataTable().destroy();
				}
				var mode = '';
				$(".contest_qso_table_contents").empty();
				var dupeCounts = {};
				$.each(html, function () {
					var key = this.col_call + '|' + this.col_band + '|' + this.col_mode;
					dupeCounts[key] = (dupeCounts[key] || 0) + 1;
				});
				$.each(html, function () {
					if (this.col_submode == null || this.col_submode == '') {
						mode = this.col_mode;
					} else {
						mode = this.col_submode;
					}
					var isDupe = dupeCounts[this.col_call + '|' + this.col_band + '|' + this.col_mode] > 1;
					$(".qsotable tbody").prepend('<tr' + (isDupe ? ' class="table-warning"' : '') + '>' +
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
				$.fn.dataTable.moment('DD-MM-YYYY HH:mm:ss');
				$('.qsotable').DataTable({
					"pageLength": 25,
					responsive: false,
					"scrollY": "400px",
					"scrollCollapse": true,
					"paging": false,
					"scrollX": true,
					"dom": 'rt<"bottom"i>',
					"language": {
						url: getDataTablesLanguageUrl(),
					},
					"search": { "search": $('#logbook-search').val() },
					order: [0, 'desc'],
					"columnDefs": [
						{
							"render": function (data, type, row) {
								return pad(row[8], 3);
							},
							"targets": 8
						},
						{
							"render": function (data, type, row) {
								return pad(row[9], 3);
							},
							"targets": 9
						}
					]
				});
				$('#logbook-search').off('keyup.logbook').on('keyup.logbook', function () {
					$('.qsotable').DataTable().search(this.value).draw();
				});
				updateContestStats(html);
				updateTableColumns($('#exchangetype').val());
			}
		});
	}
}

function updateTableColumns(exchangetype) {
	if (!$.fn.DataTable.isDataTable('.qsotable')) return;
	var table = $('.qsotable').DataTable();
	var showExch   = ['Exchange', 'Serialexchange'].indexOf(exchangetype) !== -1;
	var showSerial = ['Serial', 'Serialexchange', 'Serialgridsquare'].indexOf(exchangetype) !== -1;
	var showGrid   = ['Gridsquare', 'Serialgridsquare'].indexOf(exchangetype) !== -1;
	table.column(6).visible(showExch, false);
	table.column(7).visible(showExch, false);
	table.column(8).visible(showSerial, false);
	table.column(9).visible(showSerial, false);
	table.column(10).visible(showGrid, false);
	table.column(11).visible(showGrid, false);
	table.draw(false);
}

function updateContestStats(qsoData) {
	if (!qsoData || qsoData.length === 0) {
		$('#contest-stats-card').hide();
		return;
	}

	var bandOrder = ['160m','80m','60m','40m','30m','20m','17m','15m','12m','10m','6m','4m','2m','70cm','23cm'];
	var bandCounts = {};
	var now = new Date();
	var cutoff = new Date(now.getTime() - 60 * 60 * 1000);
	var recentCount = 0;

	$.each(qsoData, function () {
		var band = this.col_band || 'Unknown';
		bandCounts[band] = (bandCounts[band] || 0) + 1;

		// Parse col_time_on: format DD-MM-YYYY HH:mm:ss
		var parts = this.col_time_on.match(/(\d{2})-(\d{2})-(\d{4}) (\d{2}):(\d{2}):(\d{2})/);
		if (parts) {
			var qsoTime = new Date(Date.UTC(
				parseInt(parts[3]), parseInt(parts[2]) - 1, parseInt(parts[1]),
				parseInt(parts[4]), parseInt(parts[5]), parseInt(parts[6])
			));
			if (qsoTime >= cutoff) {
				recentCount++;
			}
		}
	});

	var total = qsoData.length;
	$('#stats-total').text(total + (total === 1 ? ' QSO' : ' QSOs'));
	$('#stats-rate').text(recentCount + '/hr');

	// Build per-band badges in canonical order, then remaining bands alphabetically
	var orderedBands = bandOrder.filter(function (b) { return bandCounts[b]; });
	var extraBands = Object.keys(bandCounts).filter(function (b) { return bandOrder.indexOf(b) === -1; }).sort();
	var allBands = orderedBands.concat(extraBands);

	var html = allBands.map(function (b) {
		return '<span class="badge text-bg-secondary me-1">' + b + ': ' + bandCounts[b] + '</span>';
	}).join('');
	$('#stats-bands').html(html);

	$('#contest-stats-card').show();
}

function pad(str, max) {
	str = str.toString();
	return str.length < max ? pad("0" + str, max) : str;
}

function getUTCTimeStamp(el) {
	var now = new Date();
	var localTime = now.getTime();
	var utc = localTime + (now.getTimezoneOffset() * 60000);
	$(el).attr('value', ("0" + now.getUTCHours()).slice(-2) + ':' + ("0" + now.getUTCMinutes()).slice(-2) + ':' + ("0" + now.getUTCSeconds()).slice(-2));
}

function getUTCDateStamp(el) {
	var now = new Date();
	var localTime = now.getTime();
	var utc = localTime + (now.getTimezoneOffset() * 60000);
	$(el).attr('value', ("0" + now.getUTCDate()).slice(-2) + '-' + ("0" + (now.getUTCMonth() + 1)).slice(-2) + '-' + now.getUTCFullYear());
}
