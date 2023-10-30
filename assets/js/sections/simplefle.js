var $textarea = $("textarea");
var qsodate = "";
var qsotime = "";
var band = "";
var mode = "";
var freq = "";
var callsign = "";
var errors = [];
var qsoList = [];

$('#simpleFleInfoButton').click(function (event) {
    var awardInfoLines = [
        lang_simplefle_info_ln2,
        lang_simplefle_info_ln3,
        lang_simplefle_info_ln4
    ];
    var simpleFleInfo = "";
    awardInfoLines.forEach(function (line) {
        simpleFleInfo += line + "<br><br>";
    });
    BootstrapDialog.alert({
        title: "<h4>"+lang_simplefle_info_ln1+"</h4>",
        message: simpleFleInfo,
    });
});

$('#js-syntax').click(function (event) {
    $('#js-syntax').prop("disabled", false);
    $.ajax({
        url: base_url + 'index.php/simplefle/displaySyntax',
        type: 'post',
        success: function (html) {
            BootstrapDialog.alert({
                title: "<h4>"+lang_simplefle_syntax_help_title+"</h4>",
                size: BootstrapDialog.SIZE_WIDE,
                nl2br: false,
                message: html,
            });
        }
    });
});

function handleInput() {
	var qsodate = "";
	if ($("#qsodate").val()) {
		qsodate = new Date($("#qsodate").val()).toISOString().split("T")[0];
	} else {
		qsodate = new Date().toISOString().split("T")[0];
	}

	var operator = $("#operator").val();
	operator = operator.toUpperCase();
	var ownCallsign = $("#station-call").val().toUpperCase();
	ownCallsign = ownCallsign.toUpperCase();

	var extraQsoDate = qsodate;
	var band = "";
	var mode = "";
	var freq = "";
	var callsign = "";
	var sotaWff = "";
	qsoList = [];
	$("#qsoTable tbody").empty();

	var text = $textarea.val().trim();
	lines = text.split("\n");
	lines.forEach((row) => {
		var rst_s = null;
		var rst_r = null;
		items = row.split(" ");
		var itemNumber = 0;
		items.forEach((item) => {
			if (item === "") {
				return;
			}
			if (
				item.match(/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/)
			) {
				extraQsoDate = item;
			} else if (
				item.match(/^[0-2][0-9][0-5][0-9]$/) &&
				itemNumber === 0
			) {
				qsotime = item;
			} else if (item.match(/^CW$|^SSB$|^FM$|^AM$|^PSK$|^FT8$/i)) {
				mode = item.toUpperCase();
			} else if (
				item.match(/^[0-9]{1,4}(?:m|cm|mm)$/) ||
				item.match(/^(sat)$/)
			) {
				band = item;
				freq = 0;
			} else if (item.match(/^\d+\.\d+$/)) {
				freq = item;
				band = "";
			} else if (
				item.match(/^[1-9]{1}$/) &&
				qsotime &&
				itemNumber === 0
			) {
				qsotime = qsotime.replace(/.$/, item);
			} else if (
				item.match(/^[0-5][0-9]{1}$/) &&
				qsotime &&
				itemNumber === 0
			) {
				qsotime = qsotime.slice(0, -2) + item;
			} else if (
				item.match(
					/^([A-Z]*[F]{2}-\d{4})|([A-Z]*[A-Z]\/[A-Z]{2}-\d{3})$/i
				)
			) {
				sotaWff = item.toUpperCase();
			} else if (
				item.match(
					/([a-zA-Z0-9]{1,3}[0123456789][a-zA-Z0-9]{0,3}[a-zA-Z])|.*\/([a-zA-Z0-9]{1,3}[0123456789][a-zA-Z0-9]{0,3}[a-zA-Z])|([a-zA-Z0-9]{1,3}[0123456789][a-zA-Z0-9]{0,3}[a-zA-Z])\/.*/
				)
			) {
				callsign = item.toUpperCase();
			} else if (itemNumber > 0 && item.match(/^\d{1,2}$/)) {
				if (rst_s === null) {
					rst_s = item;
				} else {
					rst_r = item;
				}
			}

			itemNumber = itemNumber + 1;
		});

		errors = [];
		checkMainFieldsErrors();

		if (callsign) {
			if (freq === 0) {
				freq = getFreqFromBand(band, mode);
			} else if (band === "") {
				band = getBandFromFreq(freq);
			}

			if (band === "") {
				addErrorMessage("Band is missing!");
			}
			if (mode === "") {
				addErrorMessage("Mode is missing");
			}
			if (qsotime === "") {
				addErrorMessage("Time is not set!");
			}

			if (isValidDate(extraQsoDate) === false) {
				addErrorMessage("Invalid date " + extraQsoDate);
				extraQsoDate = qsodate;
			}

			rst_s = getReportByMode(rst_s, mode);
			rst_r = getReportByMode(rst_r, mode);

			qsoList.push([
				extraQsoDate,
				qsotime,
				callsign,
				freq,
				band,
				mode,
				rst_s,
				rst_r,
				sotaWff,
			]);

			const tableRow = $(`<tr>
          <td>${extraQsoDate}</td>
          <td>${qsotime}</td>
          <td>${callsign}</td>
          <td><span data-toggle="tooltip" data-placement="left" title="${freq}">${band}</span></td>
          <td>${mode}</td>
          <td>${rst_s}</td>
          <td>${rst_r}</td>
          <td>${operator}</td>
          <td>${sotaWff}</td>
        </tr>`);

			$("#qsoTable > tbody:last-child").append(tableRow);

			localStorage.setItem(`user_${user_id}_tabledata`, $("#qsoTable").html());
			localStorage.setItem(`user_${user_id}_my-call`, $("#station-call").val());
			localStorage.setItem(`user_${user_id}_operator`, $("#operator").val());
			localStorage.setItem(`user_${user_id}_my-sota-wwff`, $("#my-sota-wwff").val());
			localStorage.setItem(`user_${user_id}_qso-area`, $(".qso-area").val());
			localStorage.setItem(`user_${user_id}_qsodate`, $("#qsodate").val());
			localStorage.setItem(`user_${user_id}_my-power`, $("#my-power").val());
			localStorage.setItem(`user_${user_id}_my-grid`, $("#my-grid").val());

			callsign = "";
			sotaWff = "";
		}

		showErrors();
	}); //lines.forEach((row)

	// Scroll to the bototm of #qsoTableBody (scroll by the value of its scrollheight property)
	$("#qsoTableBody").scrollTop($("#qsoTableBody").get(0).scrollHeight);

	var qsoCount = qsoList.length;
	if (qsoCount) {
		$(".js-qso-count").html("<strong>Total:</strong> " + qsoCount + " QSO");
	} else {
		$(".js-qso-count").html("");
	}

	if (errors) {
		$(".js-status").html(errors.join("<br>"));
	}
}

function checkMainFieldsErrors() {
	if ($("#station-call").val() === '-') {
		$('#warningStationCall').show();
        $('#station-call').css('border', '2px solid rgb(217, 83, 79)');
        $('#warningStationCall').text("Station Call is not selected");
	} else {
        $('#station-call').css('border', '');
        $('#warningStationCall').hide();
    }

	if ($("#operator").val() === "") {
		$('#warningOperatorField').show();
        $('#operator').css('border', '2px solid rgb(217, 83, 79)');
        $('#warningOperatorField').text("'Operator' Field is empty");
	}else {
        $('#operator').css('border', '');
        $('#warningOperatorField').hide();
    }
	if ($("textarea").val() === "") {
        $('#textarea').css('border', '2px solid rgb(217, 83, 79)');
		setTimeout(function() {
			$('#textarea').css('border', '');
		  }, 2000);
	}else {
        $('#textarea').css('border', '');
    }
}

$textarea.keydown(function (event) {
	if (event.which == 13) {
		handleInput();
	}
});

$textarea.focus(function () {
	errors = [];
	checkMainFieldsErrors();
	showErrors();
});

function addErrorMessage(errorMessage) {
	errorMessage = '<span class="text-danger">' + errorMessage + "</span>";
	if (errors.includes(errorMessage) == false) {
		errors.push(errorMessage);
	}
}

function isValidDate(d) {
	return new Date(d) !== "Invalid Date" && !isNaN(new Date(d));
}

$(".js-reload-qso").click(function () {
	handleInput();
});

$(".js-empty-qso").click(function () {
	BootstrapDialog.confirm({
		title: "WARNING",
		message: "Warning! Do you really want to reset everything?",
		type: BootstrapDialog.TYPE_DANGER,
		btnCancelLabel: "Cancel",
		btnOKLabel: "OK!",
		btnOKClass: "btn-warning",
		callback: function (result) {
			if (result) {
				clearSession();
			}
		},
	});
});

function clearSession() {
	localStorage.removeItem(`user_${user_id}_tabledata`);
	localStorage.removeItem(`user_${user_id}_my-call`);
	localStorage.removeItem(`user_${user_id}_operator`);
	localStorage.removeItem(`user_${user_id}_my-sota-wwff`);
	localStorage.removeItem(`user_${user_id}_qso-area`);
	localStorage.removeItem(`user_${user_id}_qsodate`);
	localStorage.removeItem(`user_${user_id}_my-grid`);
	$("#qsodate").val("");
	$("#qsoTable tbody").empty();
	$("#my-sota-wwff").val("");
	// $("#station-call").val("");        	Do not clear that?
	// $("#operator").val("");				Do not clear that?
	$(".qso-area").val("");
	$("#my-grid").val("");
	qsoList = [];
	$(".js-qso-count").html("");
}

function showErrors() {
	if (errors) {
		$(".js-status").html(errors.join("<br>"));
	}
}

$(".js-download-qso").click(function () {
	handleInput();
});


function getBandFromFreq(freq) {
	if (freq > 1.7 && freq < 2) {
		return "160m";
	} else if (freq > 3.4 && freq < 4) {
		return "80m";
	} else if (freq > 6.9 && freq < 7.3) {
		return "40m";
	} else if (freq > 5 && freq < 6) {
		return "60m";
	} else if (freq > 10 && freq < 11) {
		return "30m";
	} else if (freq > 13 && freq < 15) {
		return "20m";
	} else if (freq > 18 && freq < 19) {
		return "17m";
	} else if (freq > 20 && freq < 22) {
		return "15m";
	} else if (freq > 24 && freq < 25) {
		return "12m";
	} else if (freq > 27 && freq < 30) {
		return "10m";
	} else if (freq > 50 && freq < 55) {
		return "6m";
	} else if (freq > 144 && freq < 149) {
		return "2m";
	} else if (freq > 430 && freq < 460) {
		return "70cm";
	}

	return "";
}

function getFreqFromBand(band, mode) {
	const settingsMode = getSettingsMode(mode.toUpperCase());
	const id = "#" + band + settingsMode;
	if ($(id).length) {
		return $(id).val();
	}
}

function getSettingsMode(mode) {
	if (mode === "AM" || mode === "FM" || mode === "SSB") {
		return "SSB";
	}

	if (mode === "CW") {
		return "CW";
	}

	return "DIGI";
}

var htmlSettings = "";
for (const [key, value] of Object.entries(Bands)) {
	htmlSettings = `
      ${htmlSettings}
      <div class="row">
        <div class="col-3 mt-4">
          <strong>${key.slice(1)}</strong>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="${key.slice(1)}CW">CW</label>
            <input type="text" class="form-control text-uppercase" id="${key.slice(
				1
			)}CW" value="${value.cw}">
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="${key.slice(1)}SSB">SSB</label>
            <input type="text" class="form-control text-uppercase" id="${key.slice(
				1
			)}SSB" value="${value.ssb}">
          </div>
        </div>
        <div class="col-3">
          <div class="form-group">
            <label for="${key.slice(1)}DIGI">DIGI</label>
            <input type="text" class="form-control text-uppercase" id="${key.slice(
				1
			)}DIGI" value="${value.digi}">
          </div>
        </div>

      </div>
    `;
}
$(".js-band-settings").html(htmlSettings);

$(".js-download-adif").click(function () {
	var operator = $("#operator").val();
	operator = operator.toUpperCase();
	var ownCallsign = $("#station-call").val().toUpperCase();
	ownCallsign = ownCallsign.toUpperCase();
	var mySotaWwff = $("#my-sota-wwff").val().toUpperCase();

	var myPower = $("#my-power").val();
	var myGrid = $("#my-grid").val().toUpperCase();

	const adifHeader = `
  ADIF export from Simple fast log entry by Petr, OK2CQR

  Internet: https://sfle.ok2cqr.com

  <ADIF_VER:5>2.2.1
  <PROGRAMID:4>SFLE
  <PROGRAMVERSION:5>0.0.1
  <EOH>

  `;

	if (false === isBandModeEntered()) {
		alert("Some QSO do not have band and/or mode defined!");

		return false;
	}

	var adif = adifHeader;
	qsoList.forEach((item) => {
		const qsodate = item[0].replace("-", "").replace("-", "");
		qso = getAdifTag("QSO_DATE", qsodate);
		qso = qso + getAdifTag("TIME_ON", item[1].replace(":", ""));
		qso = qso + getAdifTag("CALL", item[2]);
		qso = qso + getAdifTag("FREQ", item[3]);
		qso = qso + getAdifTag("BAND", item[4]);
		qso = qso + getAdifTag("MODE", item[5]);

		var rst = item[6];
		settingsMode = getSettingsMode(rst);
		if (settingsMode === "SSB") {
			rst = "59";
		}
		qso = qso + getAdifTag("RST_SENT", rst);

		var rst = item[7];
		settingsMode = getSettingsMode(rst);
		if (settingsMode === "SSB") {
			rst = "59";
		}
		qso = qso + getAdifTag("RST_RCVD", rst);

		qso = qso + getAdifTag("OPERATOR", operator);
		qso = qso + getAdifTag("STATION_CALLSIGN", ownCallsign);

		if (isSOTA(mySotaWwff)) {
			qso = qso + getAdifTag("MY_SOTA_REF", mySotaWwff);
		} else if (isWWFF(mySotaWwff)) {
			qso = qso + getAdifTag("MY_SIG", "WWFF");
			qso = qso + getAdifTag("MY_SIG_INFO", mySotaWwff);
		}

		if (isSOTA(item[8])) {
			qso = qso + getAdifTag("SOTA_REF", item[8]);
		} else if (isWWFF(item[8])) {
			qso = qso + getAdifTag("SIG", "WWFF");
			qso = qso + getAdifTag("SIG_INFO", item[8]);
		}

		if (myPower) {
			qso = qso + getAdifTag("TX_PWR", myPower);
		}

		if (myGrid) {
			qso = qso + getAdifTag("MY_GRIDSQUARE", myGrid);
		}

		qso = qso + "<EOR>";

		adif = adif + qso + "\n";
	});

	qsodate = qsoList[0][0].replace("-", "").replace("-", "");
	const filename =
		operator.replace("/", "-") +
		"_" +
		mySotaWwff.replace("/", "-") +
		"_" +
		qsodate +
		".adi";
	download(filename, adif);
});

function isBandModeEntered() {
	let isBandModeOK = true;
	qsoList.forEach((item) => {
		if (item[4] === "" || item[5] === "") {
			isBandModeOK = false;
		}
	});

	return isBandModeOK;
}

function getAdifTag(tagName, value) {
	return "<" + tagName + ":" + value.length + ">" + value + " ";
}

function getReportByMode(rst, mode) {
	settingsMode = getSettingsMode(mode);

	if (rst === null) {
		if (settingsMode === "SSB") {
			return "59";
		}

		return "599";
	}

	if (settingsMode === "SSB") {
		if (rst.length === 1) {
			return "5" + rst;
		}

		return rst;
	}

	if (rst.length === 1) {
		return "5" + rst + "9";
	} else if (rst.length === 2) {
		return rst + "9";
	}

	return rst;
}

function isSOTA(value) {
	if (value.match(/^[A-Z]*[A-Z]\/[A-Z]{2}-\d{3}$/)) {
		return true;
	}

	return false;
}

function isWWFF(value) {
	if (value.match(/^[A-Z]*[F]{2}-\d{4}$/)) {
		return true;
	}

	return false;
}

function download(filename, text) {
	var element = document.createElement("a");
	element.setAttribute(
		"href",
		"data:text/plain;charset=utf-8," + encodeURIComponent(text)
	);
	element.setAttribute("download", filename);

	element.style.display = "none";
	document.body.appendChild(element);

	element.click();

	document.body.removeChild(element);
}

$(document).ready(function () {
	var tabledata = localStorage.getItem(`user_${user_id}_tabledata`);
	var mycall = localStorage.getItem(`user_${user_id}_my-call`);
	var operator = localStorage.getItem(`user_${user_id}_operator`);
	var mysotawwff = localStorage.getItem(`user_${user_id}_my-sota-wwff`);
	var qsoarea = localStorage.getItem(`user_${user_id}_qso-area`);
	var qsodate = localStorage.getItem(`user_${user_id}_qsodate`);
	var myPower = localStorage.getItem(`user_${user_id}_my-power`);
	var myGrid = localStorage.getItem(`user_${user_id}_my-grid`);

	if (mycall != null) {
		$("#station-call").val(mycall);
	}

	if (operator != null) {
		$("#operator").val(operator);
	}

	if (mysotawwff != null) {
		$("#my-sota-wwff").val(mysotawwff);
	}

	if (qsoarea != null) {
		$(".qso-area").val(qsoarea);
	}

	if (qsodate != null) {
		$("#qsodate").val(qsodate);
	}

	if (myPower != null) {
		$("#my-power").val(myPower);
	}

	if (myGrid != null) {
		$("#my-grid").val(myGrid);
	}

	if (tabledata != null) {
		$("#qsoTable").html(tabledata);
		handleInput();
	}
});

$(".js-save-to-log").click(function () {
	if ($("textarea").val() === "") {
        $('#textarea').css('border', '2px solid rgb(217, 83, 79)');
		setTimeout(function() {
			$('#textarea').css('border', '');
		  }, 2000);
	}
	if (false === isBandModeEntered()) {
		BootstrapDialog.alert({
			title: "WARNING",
			message: "Warning! You can't log the QSO list because some QSO do not have band and/or mode defined!?",
			type: BootstrapDialog.TYPE_DANGER,
			btnCancelLabel: "Cancel",
			btnOKLabel: "OK!",
			btnOKClass: "btn-warning",
		});
		return false;
	}
	else {
		handleInput();
		BootstrapDialog.confirm({
			title: "ATTENTION",
			message:
				"Are you sure that you want to add these QSO to the Log and clear the session?",
			type: BootstrapDialog.TYPE_INFO,
			btnCancelLabel: "Cancel",
			btnOKLabel: "Log it!",
			btnOKClass: "btn-info",
			callback: function (result) {
				if (result) {
					var operator = $("#operator").val();
					operator = operator.toUpperCase();
					var ownCallsign = $("#station-call").val().toUpperCase();
					ownCallsign = ownCallsign.toUpperCase();
					// var mySotaWwff = $("#my-sota-wwff").val().toUpperCase();

					// var myPower = $("#my-power").val();
					// var myGrid = $("#my-grid").val().toUpperCase();

					qsoList.forEach((item) => {
						var callsign = item[2];
						var rst_rcvd = item[7];
						var rst_sent = item[6];
						var start_date = item[0];
						var start_time = item[1][0] +item[1][1] + ":" + item[1][2] + item[1][3];
						var band = item[4];
						var mode = item[5];
						var freq_display = item[3];
						var station_profile = $(".station_id").val();

						$.ajax({
							url: base_url + "index.php/qso/saveqso",
							type: "post",
							data: {
								callsign: callsign,
								rst_rcvd: rst_rcvd,
								rst_sent: rst_sent,
								start_date: start_date,
								band: band,
								mode: mode,
								freq_display: freq_display,
								start_time: start_time,
								station_profile: station_profile,
							},
							success: function (result) {},
						});
					});

					clearSession();
					BootstrapDialog.alert({
						title: "QSO logged",
						message:
							"The QSO were successfully logged in the logbook!",
					});
				}
			},
		});
	}
});
