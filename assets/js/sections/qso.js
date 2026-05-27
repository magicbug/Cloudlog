var lastCallsignUpdated=""
var callsignLookupRequestId = 0;
var callsignDxccQuickRequestId = 0;
var callsignDxccQuickTimer = null;
var isSubmitting = false;
var lastResetCatSyncNoticeAt = 0;
var suppressNextResetHandler = false;
var previousContactsLookupMode = false;
var htmxAutoRefreshTimer = null;

function hasFieldValue(value) {
	return value !== null && value !== undefined && String(value).trim() !== "";
}

function normalizeFieldValue(value) {
	return String(value ?? "").trim();
}

function escapeNoticeValue(value) {
	return String(value || '').replace(/[&<>"']/g, function(char) {
		var escapes = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#39;'
		};
		return escapes[char] || char;
	});
}

function showQsoNotice(message, alertType) {
	var safeType = alertType || 'info';
	var $container = $('#notice-alerts-container');
	if ($container.length === 0) {
		$container = $('<div id="notice-alerts-container"></div>');
		var $rightColumn = $('.col-sm-7').first();
		var $mapCard = $rightColumn.find('.qso-map').first();
		if ($mapCard.length > 0) {
			$container.insertBefore($mapCard);
		} else if ($rightColumn.length > 0) {
			$rightColumn.prepend($container);
		}
	}

	$container.html('<div id="notice-alerts" class="alert alert-' + safeType + '" role="alert">' + message + '</div>');

	setTimeout(function() {
		$('#notice-alerts').fadeOut(300, function() {
			$(this).remove();
		});
	}, 5000);
}

function shouldReplaceLookupField($field, incomingValue, fieldKey, approval) {
	if (!hasFieldValue(incomingValue)) {
		return false;
	}

	var currentValue = normalizeFieldValue($field.val());
	var nextValue = normalizeFieldValue(incomingValue);

	if (!hasFieldValue(currentValue) || currentValue === nextValue) {
		return true;
	}

	if (!approval) {
		return false;
	}

	if (approval.replaceAll) {
		return true;
	}

	return approval.replaceSelected.has(fieldKey);
}

function getLookupOverwriteConflicts(result) {
	var conflicts = [];

	var fieldMappings = [
		{ key: 'name', label: 'Name', selector: '#name', value: result.callsign_name },
		{ key: 'qth', label: 'QTH', selector: '#qth', value: result.callsign_qth },
		{ key: 'locator', label: 'Grid', selector: '#locator', value: result.callsign_qra },
		{ key: 'qsl_via', label: 'QSL Via', selector: '#qsl_via', value: result.qsl_manager }
	];

	fieldMappings.forEach(function(field) {
		var incomingValue = normalizeFieldValue(field.value);
		if (!hasFieldValue(incomingValue)) {
			return;
		}

		var currentValue = normalizeFieldValue($(field.selector).val());
		if (hasFieldValue(currentValue) && currentValue !== incomingValue) {
			conflicts.push({
				key: field.key,
				label: field.label,
				currentValue: currentValue,
				nextValue: incomingValue
			});
		}
	});

	return conflicts;
}

function showLookupOverwriteModal(conflicts) {
	return new Promise(function(resolve) {
		if (!conflicts || conflicts.length === 0) {
			resolve({ replaceAll: false, replaceSelected: new Set() });
			return;
		}

		var modalElement = document.getElementById('callsignOverwriteModal');
		var conflictList = document.getElementById('callsignOverwriteConflicts');
		var keepExistingBtn = document.getElementById('callsignOverwriteKeepExisting');
		var replaceSelectedBtn = document.getElementById('callsignOverwriteReplaceSelected');
		var replaceAllBtn = document.getElementById('callsignOverwriteReplaceAll');

		if (!modalElement || !conflictList || typeof bootstrap === 'undefined') {
			resolve({ replaceAll: false, replaceSelected: new Set() });
			return;
		}

		conflictList.innerHTML = '';
		conflicts.forEach(function(conflict) {
			var escapedCurrent = $('<div/>').text(conflict.currentValue).html();
			var escapedNext = $('<div/>').text(conflict.nextValue).html();
			var itemHtml = '' +
				'<div class="form-check mb-2">' +
					'<input class="form-check-input callsign-overwrite-choice" type="checkbox" id="overwrite_' + conflict.key + '" data-field-key="' + conflict.key + '" checked>' +
					'<label class="form-check-label" for="overwrite_' + conflict.key + '">' +
						'<strong>' + conflict.label + '</strong><br>' +
						'<small class="text-muted">Current: ' + escapedCurrent + '</small><br>' +
						'<small>Suggested: ' + escapedNext + '</small>' +
					'</label>' +
				'</div>';
			conflictList.insertAdjacentHTML('beforeend', itemHtml);
		});

		var modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
		var resolved = false;

		function finalizeDecision(decision) {
			if (resolved) {
				return;
			}
			resolved = true;
			resolve(decision);
			modalInstance.hide();
		}

		keepExistingBtn.onclick = function() {
			finalizeDecision({ replaceAll: false, replaceSelected: new Set() });
		};

		replaceSelectedBtn.onclick = function() {
			var selected = new Set();
			document.querySelectorAll('.callsign-overwrite-choice:checked').forEach(function(input) {
				selected.add(input.getAttribute('data-field-key'));
			});
			finalizeDecision({ replaceAll: false, replaceSelected: selected });
		};

		replaceAllBtn.onclick = function() {
			var selected = new Set();
			conflicts.forEach(function(conflict) {
				selected.add(conflict.key);
			});
			finalizeDecision({ replaceAll: true, replaceSelected: selected });
		};

		modalElement.addEventListener('hidden.bs.modal', function hiddenHandler() {
			modalElement.removeEventListener('hidden.bs.modal', hiddenHandler);
			if (!resolved) {
				resolve({ replaceAll: false, replaceSelected: new Set() });
			}
		}, { once: true });

		modalInstance.show();
	});
}

function applyLookupLocator(result, approval) {
	if (!shouldReplaceLookupField($('#locator'), result.callsign_qra, 'locator', approval)) {
		return;
	}

	$('#locator').val(result.callsign_qra);
	$('#locator_info').html(result.bearing);

	if (result.callsign_distance != "" && result.callsign_distance != 0) {
		document.getElementById("distance").value = result.callsign_distance;
	}

	if (result.callsign_qra != "") {
		if (result.confirmed) {
			$('#locator').addClass("confirmedGrid");
			$('#locator').attr('title', 'Grid was already worked and confirmed in the past');
		} else if (result.workedBefore) {
			$('#locator').addClass("workedGrid");
			$('#locator').attr('title', 'Grid was already worked in the past');
		} else {
			$('#locator').addClass("newGrid");
			$('#locator').attr('title', 'New grid!');
		}
	} else {
		$('#locator').removeClass("workedGrid");
		$('#locator').removeClass("confirmedGrid");
		$('#locator').removeClass("newGrid");
		$('#locator').attr('title', '');
	}
}

$( document ).ready(function() {
	setTimeout(function() {
		var callsignValue = localStorage.getItem("quicklogCallsign");
		if (callsignValue !== null && callsignValue !== undefined) {
		  $("#callsign").val(callsignValue);
		  $("#mode").focus();
		  localStorage.removeItem("quicklogCallsign");
		}
	}, 100);
	$('#reset_time').click(function() {
		var now = new Date();
		var localTime = now.getTime();
		var utc = localTime + (now.getTimezoneOffset() * 60000);
		$('#start_time').val(("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2)+':'+("0" + now.getUTCSeconds()).slice(-2));
		$("[id='start_time']").each(function() {
			$(this).attr("value", ("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2)+':'+("0" + now.getUTCSeconds()).slice(-2));
		});
	});
	$('#reset_start_time').click(function() {
		var now = new Date();
		var localTime = now.getTime();
		var utc = localTime + (now.getTimezoneOffset() * 60000);
		$('#start_time').val(("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2));
		$("[id='start_time']").each(function() {
			$(this).attr("value", ("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2)+':'+("0" + now.getUTCSeconds()).slice(-2));
		});
		$('#end_time').val(("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2));
		$("[id='end_time']").each(function() {
			$(this).attr("value", ("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2)+':'+("0" + now.getUTCSeconds()).slice(-2));
		});
	});
	$('#reset_end_time').click(function() {
		var now = new Date();
		var localTime = now.getTime();
		var utc = localTime + (now.getTimezoneOffset() * 60000);
		$('#end_time').val(("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2));
		$("[id='end_time']").each(function() {
			$(this).attr("value", ("0" + now.getUTCHours()).slice(-2)+':'+("0" + now.getUTCMinutes()).slice(-2)+':'+("0" + now.getUTCSeconds()).slice(-2));
		});
	});
var favs={};
	get_fav();

	$('#fav_add').click(function (event) {
		save_fav();
	});

	$(document).on("click", "#fav_del", function (event) {
		del_fav($(this).attr('name'));
	});

	$(document).on("click", "#fav_recall", function (event) {
		$('#sat_name').val(favs[this.innerText].sat_name);
		$('#sat_mode').val(favs[this.innerText].sat_mode);
		$('#band_rx').val(favs[this.innerText].band_rx);
		$('#band').val(favs[this.innerText].band);
		$('#frequency_rx').val(favs[this.innerText].frequency_rx);
		$('#frequency').val(favs[this.innerText].frequency);
		$('#selectPropagation').val(favs[this.innerText].prop_mode);
		$('#mode').val(favs[this.innerText].mode);
	});


	function del_fav(name) {
		if (confirm("Are you sure to delete Fav?")) {
			$.ajax({
				url: base_url+'index.php/user_options/del_fav',
				method: 'POST',
				dataType: 'json',
				contentType: "application/json; charset=utf-8",
				data: JSON.stringify({ "option_name": name }),
				success: function(result) {
					get_fav();
				}
			});
		}
	}

	function get_fav() {
		$.ajax({
			url: base_url+'index.php/user_options/get_fav',
			method: 'GET',
			dataType: 'json',
			contentType: "application/json; charset=utf-8",
			success: function(result) {
				$("#fav_menu").empty();
				for (const key in result) {
					$("#fav_menu").append('<label class="dropdown-item" style="display: flex; justify-content: space-between;"><span id="fav_recall">' + key + '</span><span class="badge bg-danger" id="fav_del" name="' + key + '"><i class="fas fa-trash-alt"></i></span></label>');
				}
				favs=result;
			}
		});
	}

	function save_fav() {
		var payload={};
		payload.sat_name=$('#sat_name').val();
		payload.sat_mode=$('#sat_mode').val();
		payload.band_rx=$('#band_rx').val();
		payload.band=$('#band').val();
		payload.frequency_rx=$('#frequency_rx').val();
		payload.frequency=$('#frequency').val();
		payload.prop_mode=$('#selectPropagation').val();
		payload.mode=$('#mode').val();
		$.ajax({
			url: base_url+'index.php/user_options/add_edit_fav',
			method: 'POST',
			dataType: 'json',
			contentType: "application/json; charset=utf-8",
			data: JSON.stringify(payload),
			success: function(result) {
				get_fav();
			}
		});
	}


	var bc_bandmap = new BroadcastChannel('qso_window');
	bc_bandmap.onmessage = function (ev) {
		if (ev.data == 'ping') {
			bc_bandmap.postMessage('pong');
		}
	}

	var bc = new BroadcastChannel('qso_wish');
	bc.onmessage = function (ev) {
		if (ev.data.ping) {
			let message={};
			message.pong=true;
			bc.postMessage(message);
		} else {
			$('#frequency').val(ev.data.frequency);
			$("#band").val(frequencyToBand(ev.data.frequency));
			if (ev.data.frequency_rx != "") {
				$('#frequency_rx').val(ev.data.frequency_rx);
				$("#band_rx").val(frequencyToBand(ev.data.frequency_rx));
			}
			$("#callsign").val(ev.data.call);
			$("#callsign").focusout();
			$("#callsign").blur();
		}
	} /* receive */

	$("#locator")
		.popover({ placement: 'top', title: 'Gridsquare Formatting', content: "Enter multiple (4-digit) grids separated with commas. For example: IO77,IO78" })
		.focus(function () {
			$('#locator').popover('show');
		})
		.blur(function () {
			$('#locator').popover('hide');
		});

	$("#sat_name").change(function(){
		var sat = $("#sat_name").val();
		if (sat == "") {
			$("#sat_mode").val("");
			$("#selectPropagation").val("");
		}
	});

	$('#input_usa_state').change(function(){
		var state = $("#input_usa_state option:selected").text();
		if (state != "") {
			$("#stationCntyInput").prop('disabled', false);

			$('#stationCntyInput').selectize({
				maxItems: 1,
				closeAfterSelect: true,
				loadThrottle: 250,
				valueField: 'name',
				labelField: 'name',
				searchField: 'name',
				options: [],
				create: false,
				load: function(query, callback) {
					var state = $("#input_usa_state option:selected").text();

					if (!query || state == "") return callback();
					$.ajax({
						url: base_url+'index.php/qso/get_county',
						type: 'GET',
						dataType: 'json',
						data: {
							query: query,
							state: state,
						},
						error: function() {
							callback();
						},
						success: function(res) {
							callback(res);
						}
					});
				}
			});

		} else {
			$("#stationCntyInput").prop('disabled', true);
			//$('#stationCntyInput')[0].selectize.destroy();
			$("#stationCntyInput").val("");
		}
	});

	$('#sota_ref').selectize({
		maxItems: 1,
		closeAfterSelect: true,
		createOnBlur: true,
		selectOnTab: true,
		loadThrottle: 250,
		valueField: 'name',
		labelField: 'name',
		searchField: 'name',
		options: [],
		create: true,
		load: function(query, callback) {
			if (!query || query.length < 3) return callback();  // Only trigger if 3 or more characters are entered
			$.ajax({
				url: base_url+'index.php/qso/get_sota',
				type: 'GET',
				dataType: 'json',
				data: {
					query: query,
				},
				error: function() {
					callback();
				},
				success: function(res) {
					callback(res);
				}
			});
		}
	});

	$('#sota_ref').change(function(){
		$('#sota_info').html('<a target="_blank" href="https://summits.sota.org.uk/summit/'+$('#sota_ref').val()+'"><img width="32" height="32" src="'+base_url+'images/icons/sota.org.uk.png"></a>');
		$('#sota_info').attr('title', 'Lookup '+$('#sota_ref').val()+' summit info on sota.org.uk');
	});

	$('#wwff_ref').selectize({
		maxItems: 1,
		closeAfterSelect: true,
		createOnBlur: true,
		selectOnTab: true,
		loadThrottle: 250,
		valueField: 'name',
		labelField: 'name',
		searchField: 'name',
		options: [],
		create: true,
		load: function(query, callback) {
			if (!query || query.length < 3) return callback();  // Only trigger if 3 or more characters are entered
			$.ajax({
				url: base_url+'index.php/qso/get_wwff',
				type: 'GET',
				dataType: 'json',
				data: {
					query: query,
				},
				error: function() {
					callback();
				},
				success: function(res) {
					callback(res);
				}
			});
		}
	});

	$('#wwff_ref').change(function(){
		$('#wwff_info').html('<a target="_blank" href="https://www.cqgma.org/zinfo.php?ref='+$('#wwff_ref').val()+'"><img width="32" height="32" src="'+base_url+'images/icons/wwff.co.png"></a>');
		$('#wwff_info').attr('title', 'Lookup '+$('#wwff_ref').val()+' reference info on cqgma.org');
	});

	$('#pota_ref').selectize({
		maxItems: null,
		closeAfterSelect: true,
		createOnBlur: true,
		selectOnTab: true,
		loadThrottle: 250,
		valueField: 'name',
		labelField: 'name',
		searchField: 'name',
		options: [],
		create: true,
		load: function(query, callback) {
			if (!query || query.length < 3) return callback();  // Only trigger if 3 or more characters are entered
			$.ajax({
				url: base_url+'index.php/qso/get_pota',
				type: 'GET',
				dataType: 'json',
				data: {
					query: query,
				},
				error: function() {
					callback();
				},
				success: function(res) {
					callback(res);
				}
			});
		}
	});

	$('#pota_ref').change(function(){
		var raw = $('#pota_ref').val() || '';
		var refs = raw.split(',').map(function(ref) {
			return ref.trim();
		}).filter(function(ref) {
			return ref.length > 0;
		});

		if (refs.length === 0) {
			$('#pota_info').html('');
			$('#pota_info').attr('title', '');
			return;
		}

		var links = refs.map(function(ref) {
			return '<a target="_blank" href="https://pota.app/#/park/' + ref + '"><img width="32" height="32" src="' + base_url + 'images/icons/pota.app.png"></a>';
		}).join(' ');

		$('#pota_info').html(links);
		$('#pota_info').attr('title', 'Lookup ' + refs.join(', ') + ' reference info on pota.app');
	});

	$('#darc_dok').selectize({
		maxItems: 1,
		closeAfterSelect: true,
		loadThrottle: 250,
		valueField: 'name',
		labelField: 'name',
		searchField: 'name',
		options: [],
		create: true,
		load: function(query, callback) {
			if (!query) return callback();  // Only trigger if at least 1 character is entered
			$.ajax({
				url: base_url+'index.php/qso/get_dok',
				type: 'GET',
				dataType: 'json',
				data: {
					query: query,
				},
				error: function() {
					callback();
				},
				success: function(res) {
					callback(res);
				}
			});
		}
	});

	/*
	  Populate the Satellite Names Field on the QSO Panel
	*/
	$.getJSON(base_url+"assets/json/satellite_data.json", function( data ) {

		// Build the options array
		var items = [];
		$.each( data, function( key, val ) {
			items.push(
				'<option value="' + key + '">' + key + '</option>'
			);
		});

		// Add to the datalist
		$('.satellite_names_list').append(items.join( "" ));
	});

	// Test Consistency value on submit form //
	$("#qso_input").off('submit').on('submit', function(e){
		e.preventDefault();

		// Prevent double submission
		if (isSubmitting) {
			return false;
		}
		
		var _submit = true;
		if ((typeof qso_manual !== "undefined")&&(qso_manual == "1")) {
			if ($('#qso_input input[name="end_time"]').length == 1) { _submit = testTimeOffConsistency(); }
		}
		
		if (_submit) {
			// Mark as submitting and disable the submit button
			isSubmitting = true;
			$('#qso_input .warningOnSubmit').hide();
			$('#qso_input .warningOnSubmit_txt').empty();

			var $form = $(this);
			var submitBtn = $(this).find('button[type="submit"]');
			var originalText = submitBtn.data('original-text');
			if (!originalText) {
				// Store original text first time
				originalText = submitBtn.html();
				submitBtn.data('original-text', originalText);
			}
			submitBtn.prop('disabled', true);
			submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');

			var ajaxSaveUrl = $form.data('ajax-save-url') || (base_url + 'index.php/qso/ajax_saveqso');

			$.ajax({
				url: ajaxSaveUrl,
				type: 'POST',
				data: $form.serialize(),
				dataType: 'json',
				success: function(response) {
					if (response && response.status === 'ok') {
						var savedCallsign = normalizeFieldValue($('#callsign').val()).toUpperCase();
						var savedStartDate = normalizeFieldValue($('#qso_input [name="start_date"]').first().val());
						var savedBand = normalizeFieldValue($('#band').val());
						var savedMode = normalizeFieldValue($('#mode').val());
						var savedSatName = normalizeFieldValue($('#sat_name').val());
						var savedSatMode = normalizeFieldValue($('#sat_mode').val());
						var savedRadio = normalizeFieldValue($('#qso_input select[name="radio"]').val());
						var postSaveDefaults = {
							start_date: savedStartDate,
							band: savedBand,
							mode: savedMode,
							sat_name: savedSatName,
							sat_mode: savedSatMode,
							radio: savedRadio
						};
						var saveMessage = (response && response.message) ? response.message : 'QSO Added';
						if (savedCallsign && savedBand) {
							saveMessage += ': <strong>' + escapeNoticeValue(savedCallsign) + ' on ' + escapeNoticeValue(savedBand) + '</strong>';
						} else if (savedCallsign) {
							saveMessage += ': <strong>' + escapeNoticeValue(savedCallsign) + '</strong>';
						} else if (savedBand) {
							saveMessage += ': <strong>on ' + escapeNoticeValue(savedBand) + '</strong>';
						}

						var qsoFormElement = document.getElementById('qso_input');
						if (qsoFormElement) {
							suppressNextResetHandler = true;
							qsoFormElement.reset();
						}

						reset_fields();
						if (document.getElementById('qsp-tab')) {
							new bootstrap.Tab(document.getElementById('qsp-tab')).show();
						}
						reapplyPostSaveDefaults(postSaveDefaults);
						showQsoNotice(saveMessage, 'info');

						if (typeof htmx !== 'undefined' && document.getElementById('qso-last-table')) {
							htmx.ajax('GET', base_url + 'index.php/qso/component_past_contacts', {
								target: '#qso-last-table',
								swap: 'innerHTML'
							});
						}

						$('#callsign').focus();
						$('#qso_input').data('initialForm', $('#qso_input').serialize());
					} else {
						var warningMessage = (response && response.message) ? response.message : 'Unable to save QSO. Please try again.';

						if (response && response.validation_errors) {
							var validationMessages = [];
							$.each(response.validation_errors, function(_, msg) {
								if (msg) {
									validationMessages.push(msg);
								}
							});
							if (validationMessages.length > 0) {
								warningMessage = validationMessages.join('<br>');
							}
						}

						$('#qso_input .warningOnSubmit_txt').html(warningMessage);
						$('#qso_input .warningOnSubmit').show();
					}
				},
				error: function() {
					$('#qso_input .warningOnSubmit_txt').html('Unable to save QSO due to a network or server error.');
					$('#qso_input .warningOnSubmit').show();
				},
				complete: function() {
					resetSubmissionState();
				}
			});
		}
		
		return false;
	})
	
	// Prevent Enter key from causing double submissions
	$("#qso_input").on('keydown', function(e) {
		if (e.key === 'Enter' && e.target.type !== 'textarea') {
			if (isSubmitting) {
				e.preventDefault();
				return false;
			}
		}
	});
	
	// Reset submission state when page becomes visible again (handles cases where submission gets stuck)
	document.addEventListener('visibilitychange', function() {
		if (!document.hidden && typeof isSubmitting !== 'undefined' && isSubmitting) {
			setTimeout(function() {
				resetSubmissionState();
			}, 1000); // Wait 1 second before resetting to avoid interfering with legitimate submissions
		}
	});
});

var selected_sat;
var selected_sat_mode;

$(document).on('change', 'input', function(){
	var optionslist = $('.satellite_modes_list')[0].options;
	var value = $(this).val();
	for (var x=0;x<optionslist.length;x++){
		if (optionslist[x].value === value) {

			// Store selected sat mode
			selected_sat_mode = value;

			// get Json file
			$.getJSON(base_url + "assets/json/satellite_data.json", function( data ) {

				// Build the options array
				var sat_modes = [];
				$.each( data, function( key, val ) {
					if (key == selected_sat) {
						$.each( val.Modes, function( key1, val2 ) {
							if(key1 == selected_sat_mode) {

								if ( (val2[0].Downlink_Mode == "LSB" && val2[0].Uplink_Mode == "USB") || (val2[0].Downlink_Mode == "USB" && val2[0].Uplink_Mode == "LSB") )   { // inverting Transponder? set to SSB
									$("#mode").val("SSB");
								} else {
									$("#mode").val(val2[0].Uplink_Mode);
								}
								$("#band").val(frequencyToBand(val2[0].Uplink_Freq));
								$("#band_rx").val(frequencyToBand(val2[0].Downlink_Freq));
								$("#frequency").val(val2[0].Uplink_Freq);
								$("#frequency_rx").val(val2[0].Downlink_Freq);
								$("#selectPropagation").val('SAT');
							}
						});
					}
				});

			});
		}
	}
});

$(document).on('change', 'input', function(){
	var optionslist = $('.satellite_names_list')[0].options;
	var value = $(this).val();
	for (var x=0;x<optionslist.length;x++){
		if (optionslist[x].value === value) {
			$("#sat_mode").val("");
			$('.satellite_modes_list').find('option').remove().end();
			selected_sat = value;
			// get Json file
			$.getJSON( base_url+"assets/json/satellite_data.json", function( data ) {

				// Build the options array
				var sat_modes = [];
				$.each( data, function( key, val ) {
					if (key == value) {
						$.each( val.Modes, function( key1, val2 ) {
							//console.log (key1);
							sat_modes.push('<option value="' + key1 + '">' + key1 + '</option>');
						});
					}
				});

				// Add to the datalist
				$('.satellite_modes_list').append(sat_modes.join( "" ));

			});
		}
	}
});

function changebadge(entityname) {
	if($("#sat_name" ).val() != "") {
		$.getJSON(base_url + 'index.php/logbook/jsonlookupdxcc/' + convert_case(entityname) + '/SAT/0/0', function(result)
		{

			$('#callsign_info').removeClass("lotw_info_orange");
			$('#callsign_info').removeClass("text-bg-secondary");
			$('#callsign_info').removeClass("text-bg-success");
			$('#callsign_info').removeClass("text-bg-danger");
			$('#callsign_info').attr('title', '');

			if (result.confirmed) {
				$('#callsign_info').addClass("text-bg-success");
				$('#callsign_info').attr('title', 'DXCC was already worked and confirmed in the past on this band and mode!');
			} else if (result.workedBefore) {
				$('#callsign_info').addClass("text-bg-success");
				$('#callsign_info').addClass("lotw_info_orange");
				$('#callsign_info').attr('title', 'DXCC was already worked in the past on this band and mode!');
			} else {
				$('#callsign_info').addClass("text-bg-danger");
				$('#callsign_info').attr('title', 'New DXCC, not worked on this band and mode!');
			}
		})
	} else {
		$.getJSON(base_url + 'index.php/logbook/jsonlookupdxcc/' + convert_case(entityname) + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
		{
			// Reset CSS values before updating
			$('#callsign_info').removeClass("lotw_info_orange");
			$('#callsign_info').removeClass("text-bg-secondary");
			$('#callsign_info').removeClass("text-bg-success");
			$('#callsign_info').removeClass("text-bg-danger");
			$('#callsign_info').attr('title', '');

			if (result.confirmed) {
				$('#callsign_info').addClass("text-bg-success");
				$('#callsign_info').attr('title', 'DXCC was already worked and confirmed in the past on this band and mode!');
			} else if (result.workedBefore) {
				$('#callsign_info').addClass("text-bg-success");
				$('#callsign_info').addClass("lotw_info_orange");
				$('#callsign_info').attr('title', 'DXCC was already worked in the past on this band and mode!');
			} else {
				$('#callsign_info').addClass("text-bg-danger");
				$('#callsign_info').attr('title', 'New DXCC, not worked on this band and mode!');
			}
		})
	}
}

/* Function: resetSubmissionState resets the form submission state */
function resetSubmissionState() {
	if (typeof isSubmitting !== 'undefined') {
		isSubmitting = false;
	}
	var submitBtn = $('#qso_input button[type="submit"]');
	if (submitBtn.length > 0) {
		submitBtn.prop('disabled', false);
		// Get the original text from the button or use a fallback
		var originalText = submitBtn.data('original-text');
		if (!originalText) {
			// Store original text first time
			originalText = submitBtn.html();
			submitBtn.data('original-text', originalText);
		}
		submitBtn.html(originalText);
	}
}

function resetCallsignLookupState() {
	lastCallsignUpdated = '';
	// Invalidate in-flight responses from older callsign lookups.
	callsignLookupRequestId++;
	callsignDxccQuickRequestId++;
	if (callsignDxccQuickTimer) {
		clearTimeout(callsignDxccQuickTimer);
		callsignDxccQuickTimer = null;
	}
}

function isLookupStillCurrent(requestId, find_callsign) {
	if (requestId !== callsignLookupRequestId) {
		return false;
	}

	var currentCallsign = $('#callsign').val().toUpperCase().replace(/\//g, "-").replace('Ø', '0');
	return currentCallsign === find_callsign;
}

function clearSatelliteFields() {
	$('#sat_name').val('');
	$('#sat_mode').val('');
	$('.satellite_modes_list').find('option').remove().end();
	selected_sat = '';
	selected_sat_mode = '';

	if ($('#selectPropagation').val() === 'SAT') {
		$('#selectPropagation').val('');
	}
}

function clearCatTrackedFieldState() {
	$('#frequency, #frequency_rx, #sat_name, #sat_mode, #transmit_power, #selectPropagation, #mode').removeData('catValue');
}

/* Function: reset_fields is used to reset the fields on the QSO page */
function reset_fields() {
	// Reset submission state
	resetSubmissionState();
	resetCallsignLookupState();

	$('#locator_info').text("");
	$('#country').val("");
	$('#continent').val("");
	$('#lotw_info').text("");
	$('#lotw_info').removeClass("lotw_info_red");
	$('#lotw_info').removeClass("lotw_info_yellow");
	$('#lotw_info').removeClass("lotw_info_orange");
	$('#qrz_info').text("");
	$('#hamqth_info').text("");
	$('#sota_info').text("");
	$('#wwff_info').html('').attr('title', '');
	$('#pota_info').html('').attr('title', '');
	$('#dxcc_id').val("").trigger('change');
	$('#cqz').val("");
	$('#name').val("");
	$('#qth').val("");
	$('#locator').val("");
	$('#iota_ref').val("");
	$select = $('#sota_ref').selectize();
	selectize = $select[0] ? $select[0].selectize : null;
	if (selectize) selectize.clear();
	$("#locator").removeClass("confirmedGrid");
	$("#locator").removeClass("workedGrid");
	$("#locator").removeClass("newGrid");
	$("#callsign").removeClass("confirmedGrid");
	$("#callsign").removeClass("workedGrid");
	$("#callsign").removeClass("newGrid");
	$('#callsign_info').removeClass("text-bg-secondary");
	$('#callsign_info').removeClass("text-bg-success");
	$('#callsign_info').removeClass("text-bg-danger");
	$('#callsign-image').attr('style', 'display: none;');
	$('#callsign-image-content').text("");
	$('#qsl_via').val("");
	$('#callsign_info').text("");
	$('#input_usa_state').val("");
	$('#qso-last-table').show();
	$('#partial_view').html('');
	$('#partial_view').hide();
	previousContactsLookupMode = false;
	resumeAutoRefresh();
	var $select = $('#wwff_ref').selectize();
	var selectize = $select[0] ? $select[0].selectize : null;
	if (selectize) selectize.clear();
	$select = $('#pota_ref').selectize();
	selectize = $select[0] ? $select[0].selectize : null;
	if (selectize) selectize.clear();
	$select = $('#darc_dok').selectize();
	selectize = $select[0] ? $select[0].selectize : null;
	if (selectize) selectize.clear();
	$select = $('#stationCntyInput').selectize();
	selectize = $select[0] ? $select[0].selectize : null;
	if (selectize) selectize.clear();

	clearSatelliteFields();
	clearCatTrackedFieldState();

	mymap.setView(pos, 12);
	mymap.removeLayer(markers);
	$('.callsign-suggest').hide();
	$('.dxccsummary').remove();
	$('#timesWorked').html(lang_qso_title_previous_contacts);
	renderQsoCallhistoryPanel([], 'Type a callsign to see membership details from your uploaded call history files.');

	// Reapply default RST values for the current mode (e.g., CW => 599).
	if (typeof setRst === 'function') {
		setRst($('.mode').val());
	}
}

function reapplyPostSaveDefaults(defaults) {
	if (!defaults) {
		return;
	}

	if (typeof defaults.start_date !== 'undefined') {
		$('#qso_input [name="start_date"]').val(defaults.start_date);
	}

	if (typeof defaults.band !== 'undefined') {
		$('#band').val(defaults.band);
	}

	if (typeof defaults.mode !== 'undefined') {
		$('#mode').val(defaults.mode);
	}

	if (typeof defaults.sat_name !== 'undefined') {
		$('#sat_name').val(defaults.sat_name);
	}

	if (typeof defaults.sat_mode !== 'undefined') {
		$('#sat_mode').val(defaults.sat_mode);
	}

	if (typeof defaults.radio !== 'undefined' && defaults.radio !== '') {
		var radioValue = String(defaults.radio);
		$('#qso_input select[name="radio"]').val(radioValue);
		$('select.radios').val(radioValue);
		if (typeof localStorage !== 'undefined') {
			localStorage.setItem('selectedRadio', radioValue);
		}
	}

	if ((defaults.sat_name && defaults.sat_name !== '') || (defaults.sat_mode && defaults.sat_mode !== '')) {
		$('#sat_name').trigger('input');
	}

	if (typeof setRst === 'function') {
		setRst($('#mode').val());
	}
}

function resetTimers(manual) {
	if (typeof manual !== 'undefined' && manual != 1) {
		handleStart = setInterval(function() { getUTCTimeStamp($('.input_start_time')); }, 500);
		handleEnd = setInterval(function() { getUTCTimeStamp($('.input_end_time')); }, 500);
		handleDate = setInterval(function() { getUTCDateStamp($('.input_date')); }, 1000);
	}
}

$("#callsign").focusout(function() {
	// Temp store the callsign
	var temp_callsign = $(this).val();
	if (temp_callsign == lastCallsignUpdated) {
		return;
	}
	lastCallsignUpdated = temp_callsign;

	if ($(this).val().length >= 3) {

		/* Find and populate DXCC */
		$('.callsign-suggest').hide();

		if($("#sat_name").val() != ""){
			var sat_type = "SAT";
			var json_band = "0";
			var json_mode = "0";
		} else {
			var sat_type = "0";
			var json_band = $("#band").val();
			var json_mode = $("#mode").val();
		}

		var find_callsign = $(this).val().toUpperCase();
		var callsign = find_callsign;
		var requestId = ++callsignLookupRequestId;

		find_callsign=find_callsign.replace(/\//g, "-");
		find_callsign=find_callsign.replace('Ø', '0');

		// Replace / in a callsign with - to stop urls breaking
		$.getJSON(base_url + 'index.php/logbook/json/' + find_callsign + '/' + sat_type + '/' + json_band + '/' + json_mode + '/' + $('#stationProfile').val(), function(result)
		{
			if (!isLookupStillCurrent(requestId, find_callsign)) {
				return;
			}

			// Make sure the typed callsign and json result match
			var currentCallsign = $('#callsign').val().toUpperCase().replace(/\//g, "-").replace('Ø', '0');
			if(currentCallsign === find_callsign) {

			// Enter lookup mode - pause auto-refresh of logbook pagination
			previousContactsLookupMode = true;
			pauseAutoRefresh();

			// Reset QSO fields but keep current DXCC badge/country to avoid flicker.
			resetDefaultQSOFields(true);

			if(result.dxcc.entity != undefined) {
				$('#country').val(convert_case(result.dxcc.entity));
				$('#callsign_info').text(convert_case(result.dxcc.entity));

				// Reset CSS values before updating
				$('#callsign').removeClass("confirmedGrid");
				$('#callsign').removeClass("workedGrid");
				$('#callsign').removeClass("newGrid");
				$('#callsign').attr('title', '');

				if (result.callsignConfirmed || result.confirmed) {
					$('#callsign').addClass("confirmedGrid");
					$('#callsign').attr('title', 'Callsign was already worked and confirmed in the past on this band and mode!');
				} else if (result.callsignWorkedBefore) {
					$('#callsign').addClass("workedGrid");
					$('#callsign').attr('title', 'Callsign was already worked in the past on this band and mode!');
				} else {
					$('#callsign').addClass("newGrid");
					$('#callsign').attr('title', 'New Callsign!');
				}

				changebadge(result.dxcc.entity);
			}

				if(result.lotw_member == "active") {
					$('#lotw_info').text("LoTW");
					if (result.lotw_days > 365) {
						$('#lotw_info').addClass('lotw_info_red');
					} else if (result.lotw_days > 30) {
						$('#lotw_info').addClass('lotw_info_orange');
						$lotw_hint = ' lotw_info_orange';
					} else if (result.lotw_days > 7) {
						$('#lotw_info').addClass('lotw_info_yellow');
					}
					$('#lotw_link').attr('href',"https://lotw.arrl.org/lotwuser/act?act="+callsign);
					$('#lotw_link').attr('target',"_blank");
					$('#lotw_info').attr('data-bs-toggle',"tooltip");
					$('#lotw_info').attr('title',"LoTW User. Last upload was "+result.lotw_days+" days ago");
					$('[data-bs-toggle="tooltip"]').tooltip();
				}
				$('#qrz_info').html('<a target="_blank" href="https://www.qrz.com/db/'+callsign+'"><img width="32" height="32" src="'+base_url+'images/icons/qrz.com.png"></a>');
				$('#qrz_info').attr('title', 'Lookup '+callsign+' info on qrz.com');
				$('#hamqth_info').html('<a target="_blank" href="https://www.hamqth.com/'+callsign+'"><img width="32" height="32" src="'+base_url+'images/icons/hamqth.com.png"></a>');
				$('#hamqth_info').attr('title', 'Lookup '+callsign+' info on hamqth.com');

				var $dok_select = $('#darc_dok').selectize();
				var dok_selectize = $dok_select[0] ? $dok_select[0].selectize : null;
				if (result.dxcc.adif == '230') {
					$.get(base_url + 'index.php/lookup/dok/' + $('#callsign').val().toUpperCase(), function(result) {
						if (!isLookupStillCurrent(requestId, find_callsign)) {
							return;
						}
						if (result && dok_selectize) {
							dok_selectize.addOption({name: result});
							dok_selectize.setValue(result, false);
						}
					});
				} else {
					if (dok_selectize) dok_selectize.clear();
				}

				$('#dxcc_id').val(result.dxcc.adif);
				$('#cqz').val(result.dxcc.cqz);
				$('#ituz').val(result.dxcc.ituz);

				var redIcon = L.icon({
					iconUrl: icon_dot_url,
					iconSize:     [18, 18], // size of the icon
				});

				// Set Map to Lat/Long
				markers.clearLayers();
				mymap.setZoom(8);
				if (typeof result.latlng !== "undefined" && result.latlng !== false) {
					var marker = L.marker([result.latlng[0], result.latlng[1]], {icon: redIcon});
					mymap.panTo([result.latlng[0], result.latlng[1]]);
					mymap.setView([result.latlng[0], result.latlng[1]], 8);
				} else {
					var marker = L.marker([result.dxcc.lat, result.dxcc.long], {icon: redIcon});
					mymap.panTo([result.dxcc.lat, result.dxcc.long]);
					mymap.setView([result.dxcc.lat, result.dxcc.long], 8);
				}

				markers.addLayer(marker).addTo(mymap);


				var overwriteConflicts = getLookupOverwriteConflicts(result);
				showLookupOverwriteModal(overwriteConflicts).then(function(approval) {
					if (!isLookupStillCurrent(requestId, find_callsign)) {
						return;
					}

					if (shouldReplaceLookupField($('#qsl_via'), result.qsl_manager, 'qsl_via', approval)) {
						$('#qsl_via').val(result.qsl_manager);
					}

					if (shouldReplaceLookupField($('#name'), result.callsign_name, 'name', approval)) {
						$('#name').val(result.callsign_name);
					}

					if (shouldReplaceLookupField($('#qth'), result.callsign_qth, 'qth', approval)) {
						$('#qth').val(result.callsign_qth);
					}

					applyLookupLocator(result, approval);
				});

				if($('#continent').val() == "") {
					$('#continent').val(result.dxcc.cont);
				}

				/* Find link to qrz.com picture */
				if (result.image != "n/a") {
					$('#callsign-image-content').html('<img class="callsign-image-pic" src="'+result.image+'">');
					$('#callsign-image').attr('style', 'display: true;');
				}

				/*
				* Update state with returned value
				*/
				if($("#input_usa_state").val() == "") {
					$("#input_usa_state").val(result.callsign_state).trigger('change');
				}

				/*
				* Update county with returned value
				*/
				var $county_elem = $('#stationCntyInput');
				if( $county_elem.length && $county_elem.has('option').length == 0 && result.callsign_us_county != "") {
					var $county_select = $county_elem.selectize();
					var county_selectize = $county_select[0] ? $county_select[0].selectize : null;
					if (county_selectize) {
						county_selectize.addOption({name: result.callsign_us_county});
						county_selectize.setValue(result.callsign_us_county, false);
					}
				}

				if(result.timesWorked != "") {
					$('#timesWorked').html(result.timesWorked + ' ' + lang_qso_title_times_worked_before);
				} else {
					$('#timesWorked').html(lang_qso_title_previous_contacts);
				}
				if($('#iota_ref').val() == "") {
					$('#iota_ref').val(result.callsign_iota);
				}
				/* display past QSOs */
				var partialHtml = (typeof result.partial === 'string') ? result.partial : '';
				
				if (partialHtml.trim() !== '') {
					// State 2: Callsign found in log with past QSOs
					$('#partial_view').html(partialHtml);
					setPreviousContactsPanelState(true);
				} else {
					// State 3: Callsign found but NO past QSOs in database
					var noQsoMsg = '<div class="alert alert-info small mb-2">'
						+ '<i class="fa fa-info-circle me-2"></i>'
						+ 'No past QSOs with <strong>' + escapeNoticeValue(find_callsign) + '</strong>&mdash;see callbook details above.'
						+ '</div>';
					$('#partial_view').html(noQsoMsg);
					setPreviousContactsPanelState(true);
				}
				// Get DXX Summary
				getDxccResult(result.dxcc.adif, convert_case(result.dxcc.entity));
			}
		});
	} else {
		// Reset QSO fields
		resetDefaultQSOFields();
		// Reset tabs - go back to Previous Contacts when callsign is cleared
		resetToPreviousContactsTab();
	}
})

function pauseAutoRefresh() {
	if (typeof htmx !== 'undefined' && document.getElementById('qso-last-table-content')) {
		var elem = document.getElementById('qso-last-table-content');
		if (elem) {
			elem.removeAttribute('hx-trigger');
		}
	}
}

function resumeAutoRefresh() {
	if (typeof htmx !== 'undefined' && document.getElementById('qso-last-table-content')) {
		var elem = document.getElementById('qso-last-table-content');
		if (elem) {
			elem.setAttribute('hx-trigger', 'every 5s');
			htmx.process(elem);
		}
	}
}

function setPreviousContactsPanelState(showLookupDetails) {
	if (showLookupDetails) {
		// Show callsign-specific results (either QSOs found or "not found" message)
		$('#qso-last-table').hide();
		$('#qso-last-table').next('small').hide();
		$('#partial_view').show();
		previousContactsLookupMode = true;
		return;
	}

	// Show paginated logbook (no callsign lookup active)
	$('#qso-last-table').show();
	$('#qso-last-table').next('small').show();
	$('#partial_view').html('');
	$('#partial_view').hide();
	previousContactsLookupMode = false;
}

// Function to reset back to Previous Contacts tab
function resetToPreviousContactsTab() {
	// Clear DXCC Summary tab content
	$('#dxcc-summary-content').html('');
	// Switch back to Previous Contacts tab
	if (document.getElementById('previous-contacts-tab')) {
		var previousContactsTab = new bootstrap.Tab(document.getElementById('previous-contacts-tab'));
		previousContactsTab.show();
	}
	// Show the default previous contacts table and hide lookup details.
	setPreviousContactsPanelState(false);
}

// Re-apply visibility state after HTMX updates the previous contacts markup.
if (typeof htmx !== 'undefined' && document.body) {
	document.body.addEventListener('htmx:afterSwap', function(evt) {
		var detail = evt && evt.detail ? evt.detail : null;
		var target = detail && detail.target ? detail.target : null;
		if (!target) {
			return;
		}

		if (target.id !== 'qso-last-table' && target.id !== 'qso-last-table-content') {
			return;
		}

		// If we're in lookup mode, keep showing the partial_view (callsign-specific results)
		// If we're not in lookup mode, show the main pagination table
		if (previousContactsLookupMode) {
			setPreviousContactsPanelState(true);
		} else if ($('#partial_view').html().trim() !== '') {
			setPreviousContactsPanelState(true);
		}
	});
}

// If a radio is selected, prefer current CAT values over stale form defaults.
function syncFromSelectedRadioAfterReset() {
	var selectedRadioID = String($('select.radios option:selected').val() || '0');
	if (selectedRadioID === '0') {
		return false;
	}

	if (typeof updateFromCAT === 'function') {
		var now = Date.now();
		if (now - lastResetCatSyncNoticeAt > 1000) {
			showQsoNotice('Form reset. Syncing live data from selected radio.', 'info');
			lastResetCatSyncNoticeAt = now;
		}
		updateFromCAT(selectedRadioID);
		return true;
	}

	return false;
}

// Reset to Previous Contacts tab when form is reset
$('#qso_input').on('reset', function() {
	resetCallsignLookupState();

	if (suppressNextResetHandler) {
		suppressNextResetHandler = false;
		return;
	}

	setTimeout(function() {
		reset_fields();
		resetToPreviousContactsTab();
		syncFromSelectedRadioAfterReset();
	}, 100);
});

function resetQsoEntryOnEscape() {
	var qsoForm = document.getElementById('qso_input');
	if (!qsoForm) {
		return;
	}

	// Capture the operating context the user currently has selected BEFORE native
	// form reset clobbers it with server-session defaults (last logged QSO values).
	var preBand     = $('#band').val();
	var preMode     = $('#mode').val();
	var preSatName  = $('#sat_name').val();
	var preSatMode  = $('#sat_mode').val();
	var prePropMode = $('#selectPropagation').val();

	qsoForm.reset();
	resetCallsignLookupState();
	resetDefaultQSOFields();
	resetToPreviousContactsTab();
	$('#callsign').trigger('focus');

	// The on('reset') handler runs reset_fields() + clearSatelliteFields() in 100ms.
	// Re-apply the captured context afterwards so the user stays on the band/mode/
	// satellite they had selected, not the one from the previous QSO session.
	setTimeout(function() {
		$('#band').val(preBand);
		$('#mode').val(preMode);
		if (preSatName) {
			$('#sat_name').val(preSatName);
			$('#sat_mode').val(preSatMode);
			$('#selectPropagation').val(prePropMode || 'SAT');
		}
		if (typeof setRst === 'function') {
			setRst($('#mode').val());
		}
		syncFromSelectedRadioAfterReset();
	}, 150);
}

// Global ESC handling on the QSO page: reset form, return to Previous Contacts, and focus callsign.
$(document).off('keydown.qsoEscapeReset').on('keydown.qsoEscapeReset', function(e) {
	if (e.key !== 'Escape' && e.keyCode !== 27) {
		return;
	}

	if (!document.getElementById('qso_input')) {
		return;
	}

	if ($(e.target).closest('.modal.show').length) {
		return;
	}

	e.preventDefault();
	e.stopPropagation();
	resetQsoEntryOnEscape();
});

// Also handle when callsign is cleared (empty value entered)
$('#callsign').on('input keyup', function() {
	if ($(this).val() === '' && lastCallsignUpdated !== '') {
		resetCallsignLookupState();
		resetDefaultQSOFields();
		resetToPreviousContactsTab();
	}
});

// Only set the frequency when not set by userdata/PHP.
if ($('#frequency').val() == "")
{
	$.get(base_url + 'index.php/qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function(result) {
		$('#frequency').val(result);
		$('#frequency_rx').val("");
	});
}

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
$('#end_time').change(function() {
	var raw_time = $(this).val();
	if(raw_time.match(/^\d\[0-6]d$/)) {
		raw_time = "0"+raw_time;
	}
	if(raw_time.match(/^[012]\d[0-5]\d$/)) {
		raw_time = raw_time.substring(0,2)+":"+raw_time.substring(2,4);
		$('#end_time').val(raw_time);
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

/* on mode change */
$('.mode').change(function() {
	$.get(base_url + 'index.php/qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function(result) {
		$('#frequency').val(result);
		$('#frequency_rx').val("");
	});
});

/* Calculate Frequency */
/* on band change */
$('#band').change(function() {
	$.get(base_url + 'index.php/qso/band_to_freq/' + $(this).val() + '/' + $('.mode').val(), function(result) {
		$('#frequency').val(result);
		$('#frequency_rx').val("");
	});
});

/* On Key up Calculate Bearing and Distance */
var locatorDebounceTimer = null;
$("#locator").keyup(function(){
	clearTimeout(locatorDebounceTimer);
	var $locator = $(this);
	locatorDebounceTimer = setTimeout(function(){
	if ($locator.val()) {
		var qra_input = $locator.val();

		var qra_lookup = qra_input.substring(0, 4);

		if(qra_lookup.length >= 4) {

			// Check Log if satname is provided
			if($("#sat_name" ).val() != "") {

				//logbook/jsonlookupgrid/io77/SAT/0/0

				$.getJSON(base_url + 'index.php/logbook/jsonlookupgrid/' + qra_lookup.toUpperCase() + '/SAT/0/0', function(result)
				{
					// Reset CSS values before updating
					$('#locator').removeClass("confirmedGrid");
					$('#locator').removeClass("workedGrid");
					$('#locator').removeClass("newGrid");
					$('#locator').attr('title', '');

					if (result.confirmed) {
						$('#locator').addClass("confirmedGrid");
						$('#locator').attr('title', 'Grid was already worked and confirmed in the past');
					} else if (result.workedBefore) {
						$('#locator').addClass("workedGrid");
						$('#locator').attr('title', 'Grid was already worked in the past');
					} else {
						$('#locator').addClass("newGrid");
						$('#locator').attr('title', 'New grid!');
					}
				})
			} else {
				$.getJSON(base_url + 'index.php/logbook/jsonlookupgrid/' + qra_lookup.toUpperCase() + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
				{
					// Reset CSS values before updating
					$('#locator').removeClass("confirmedGrid");
					$('#locator').removeClass("workedGrid");
					$('#locator').removeClass("newGrid");
					$('#locator').attr('title', '');

					if (result.confirmed) {
						$('#locator').addClass("confirmedGrid");
						$('#locator').attr('title', 'Grid was already worked and confimred in the past');
					} else if (result.workedBefore) {
						$('#locator').addClass("workedGrid");
						$('#locator').attr('title', 'Grid was already worked in the past');
					} else {
						$('#locator').addClass("newGrid");
						$('#locator').attr('title', 'New grid!');
					}

				})
			}
		}

		if(qra_input.length >= 4 && $locator.val().length > 0) {
			// Map pin — grid → lat/lng (pure frontend math, no server round-trip)
			var latlng = QraUtils.qra2latlong(qra_input);
			markers.clearLayers();
			if (latlng && typeof latlng[0] !== "undefined" && typeof latlng[1] !== "undefined") {
				var redIcon = L.icon({
					iconUrl: icon_dot_url,
					iconSize: [18, 18],
				});
				var marker = L.marker([latlng[0], latlng[1]], {icon: redIcon});
				mymap.setZoom(8);
				mymap.panTo([latlng[0], latlng[1]]);
				mymap.setView([latlng[0], latlng[1]], 8);
				markers.addLayer(marker).addTo(mymap);
			}

			// Bearing and distance — pure frontend math using injected station gridsquares
			var myGrid = station_gridsquares[$('#stationProfile').val()];
			if (myGrid) {
				var bearingStr = QraUtils.bearingString(myGrid, qra_input, qso_measurement_base);
				if (bearingStr) {
					$('#locator_info').html(bearingStr).fadeIn("slow");
				}

				var dist = QraUtils.distanceKm(myGrid, qra_input);
				document.getElementById("distance").value = dist !== null ? dist : '';
			}
		}
	}
	}, 300);
});

// Change report based on mode
$('.mode').change(function(){
	setRst($('.mode') .val());
});

function convert_case(str) {
	var lower = str.toLowerCase();
	return lower.replace(/(^| )(\w)/g, function(x) {
		return x.toUpperCase();
	});
}

var qsoCallhistoryLookupTimer = null;

function qsoCallhistoryEscapeHtml(unsafeText) {
	return String(unsafeText || '').replace(/[&<>\"]/g, function(tag) {
		var replacements = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;'
		};
		return replacements[tag] || tag;
	});
}

function qsoCallhistoryNormalizeText(value) {
	return String(value || '').trim().toLowerCase();
}

function renderQsoCallhistoryPanel(matches, defaultText) {
	var $card = $('#qso-callhistory-inline');
	var $panel = $('#qso-callhistory-results');
	if ($panel.length === 0 || $card.length === 0) {
		return;
	}

	if (!matches || matches.length === 0) {
		$panel.html('');
		$card.hide();
		return;
	}

	var html = '<ul class="list-group list-group-flush">';

	$.each(matches, function(_, match) {
		var organizationLabel = String(match.organization_label || 'Member');
		var membershipNumber = String(match.exch1 || '');
		var memberName = String(match.name || '');
		var normalizedMembershipNumber = qsoCallhistoryNormalizeText(membershipNumber);
		var normalizedMemberName = qsoCallhistoryNormalizeText(memberName);

		var line = '<strong>' + qsoCallhistoryEscapeHtml(match.organization_label || 'Member') + '</strong>';
		if (membershipNumber && qsoCallhistoryNormalizeText(organizationLabel).indexOf(normalizedMembershipNumber) === -1) {
			line += ' #' + qsoCallhistoryEscapeHtml(membershipNumber);
		}
		if (memberName && normalizedMemberName !== normalizedMembershipNumber) {
			line += ' - ' + qsoCallhistoryEscapeHtml(memberName);
		}

		var sigValue = qsoCallhistoryEscapeHtml(match.organization_label || '');
		var sigInfoValue = qsoCallhistoryEscapeHtml(match.exch1 || '');
		if (sigValue !== '' || sigInfoValue !== '') {
			line += ' <button type="button" class="btn btn-sm btn-outline-secondary qso-copy-sig-btn ms-2 py-0 px-2" data-sig="' + sigValue + '" data-siginfo="' + sigInfoValue + '"><i class="fas fa-copy me-1"></i>Copy to SIG</button>';
		}

		html += '<li class="list-group-item px-0 py-2">' + line + '</li>';
	});

	html += '</ul>';
	$panel.html(html);
	$card.show();
}

$(document).on('click', '.qso-copy-sig-btn', function() {
	var sig = $(this).data('sig') || '';
	var sigInfo = $(this).data('siginfo') || '';

	$('#sig').val(sig);
	$('#sig_info').val(sigInfo);
});

function lookupQsoCallhistory(callsign) {
	if (qsoCallhistoryLookupTimer !== null) {
		clearTimeout(qsoCallhistoryLookupTimer);
	}

	qsoCallhistoryLookupTimer = setTimeout(function() {
		$.ajax({
			url: base_url + 'index.php/callhistory/lookup',
			type: 'post',
			data: { callsign: callsign },
			success: function(response) {
				if (!response || response.status !== 'ok') {
					renderQsoCallhistoryPanel([], 'No call history match for this callsign.');
					return;
				}
				renderQsoCallhistoryPanel(response.matches || [], 'No call history match for this callsign.');
			},
			error: function() {
				renderQsoCallhistoryPanel([], 'Call history lookup failed.');
			}
		});
	}, 250);
}

$('#dxcc_id').on('change', function() {
	if (typeof toggleDokField === 'function') toggleDokField();
	if (typeof toggleUsaFields === 'function') toggleUsaFields();
	$.getJSON(base_url + 'index.php/logbook/jsonentity/' + $(this).val(), function (result) {
		if (result.dxcc.name != undefined) {

			$('#country').val(convert_case(result.dxcc.name));
			$('#cqz').val(convert_case(result.dxcc.cqz));

			$('#callsign_info').removeClass("text-bg-secondary");
			$('#callsign_info').removeClass("text-bg-success");
			$('#callsign_info').removeClass("text-bg-danger");
			$('#callsign_info').attr('title', '');
			$('#callsign_info').text(convert_case(result.dxcc.name));

			changebadge(result.dxcc.name);

			// Set Map to Lat/Long it locator is not empty
			if($('#locator').val() == "") {
				var redIcon = L.icon({
					iconUrl: icon_dot_url,
					iconSize:     [18, 18], // size of the icon
				});

				markers.clearLayers();
				var marker = L.marker([result.dxcc.lat, result.dxcc.long], {icon: redIcon});
				mymap.setZoom(8);
				mymap.panTo([result.dxcc.lat, result.dxcc.long]);
				markers.addLayer(marker).addTo(mymap);
			}
		}
	});
});

//Spacebar moves to the name field when you're entering a callsign
//Similar to contesting ux, good for pileups.
$("#callsign").on("keypress", function(e) {
	if (e.which == 32){
		$("#name").focus();
		return false; //Eliminate space char
	}
});

function quickLookupDxcc(callsign) {
	if (!callsign || callsign.length < 3) {
		return;
	}

	var requestId = ++callsignDxccQuickRequestId;
	var find_callsign = callsign.toUpperCase().replace(/\//g, "-").replace('Ø', '0');

	$.getJSON(base_url + 'index.php/logbook/jsondxcc/' + find_callsign, function(result) {
		if (requestId !== callsignDxccQuickRequestId) {
			return;
		}

		var currentCallsign = $('#callsign').val().toUpperCase().replace(/\//g, "-").replace('Ø', '0');
		if (currentCallsign !== find_callsign) {
			return;
		}

		if (result.dxcc && result.dxcc.entity !== undefined) {
			$('#country').val(convert_case(result.dxcc.entity));
			$('#callsign_info').text(convert_case(result.dxcc.entity));
			changebadge(result.dxcc.entity);
			$('#dxcc_id').val(result.dxcc.adif);
			$('#cqz').val(result.dxcc.cqz);
			$('#ituz').val(result.dxcc.ituz);
		}
	});
}

// On Key up check and suggest callsigns
$("#callsign").keyup(function() {
	if (callsignDxccQuickTimer) {
		clearTimeout(callsignDxccQuickTimer);
	}

	var currentCall = $(this).val();
	if (currentCall.length >= 3) {
		callsignDxccQuickTimer = setTimeout(function() {
			quickLookupDxcc(currentCall);
		}, 200);
	}

	if ($(this).val().length >= 3) {
	  $('.callsign-suggest').show();
	  $callsign = $(this).val().replace('Ø', '0');
	  lookupQsoCallhistory($callsign.toUpperCase());
	  $.ajax({
		url: 'lookup/scp',
		method: 'POST',
		data: {
		  callsign: $callsign.toUpperCase()
		},
		success: function(result) {
		  $('.callsign-suggestions').text(result);
		}
	  });
	} else {
	  renderQsoCallhistoryPanel([], 'Type a callsign to see membership details from your uploaded call history files.');
	}
  });

//Reset QSO form Fields function
function resetDefaultQSOFields(preserveDxccState) {
	var keepDxcc = preserveDxccState === true;
	var preservedCountry = '';
	var preservedCallsignInfoText = '';
	var preservedCallsignInfoTitle = '';
	var preservedCallsignInfoClass = '';
	var preservedDxccId = '';
	var preservedCqz = '';
	var preservedItuz = '';

	if (keepDxcc) {
		preservedCountry = $('#country').val();
		preservedCallsignInfoText = $('#callsign_info').text();
		preservedCallsignInfoTitle = $('#callsign_info').attr('title') || '';
		preservedCallsignInfoClass = $('#callsign_info').attr('class') || '';
		preservedDxccId = $('#dxcc_id').val();
		preservedCqz = $('#cqz').val();
		preservedItuz = $('#ituz').val();
	}

	$('#callsign_info').text("");
	$('#locator_info').text("");
	$('#country').val("");
	$('#continent').val("");
	$('#lotw_info').text("");
	$('#lotw_info').removeClass("lotw_info_red");
	$('#lotw_info').removeClass("lotw_info_yellow");
	$('#lotw_info').removeClass("lotw_info_orange");
	$('#qrz_info').text("");
	$('#hamqth_info').text("");
	$('#dxcc_id').val("").trigger('change');
	$('#cqz').val("");
	$('#ituz').val("");
	$("#locator").removeClass("workedGrid");
	$("#locator").removeClass("confirmedGrid");
	$("#locator").removeClass("newGrid");
	$("#callsign").removeClass("workedGrid");
	$("#callsign").removeClass("confirmedGrid");
	$("#callsign").removeClass("newGrid");
	$('#callsign_info').removeClass("text-bg-secondary");
	$('#callsign_info').removeClass("text-bg-success");
	$('#callsign_info').removeClass("text-bg-danger");
	$('#callsign-image').attr('style', 'display: none;');
	$('#callsign-image-content').text("");
	$('.dxccsummary').remove();
	$('#timesWorked').html(lang_qso_title_previous_contacts);

	if (keepDxcc) {
		$('#country').val(preservedCountry);
		$('#callsign_info').text(preservedCallsignInfoText);
		$('#callsign_info').attr('title', preservedCallsignInfoTitle);
		$('#callsign_info').attr('class', preservedCallsignInfoClass);
		$('#dxcc_id').val(preservedDxccId);
		$('#cqz').val(preservedCqz);
		$('#ituz').val(preservedItuz);
	}
}

function closeModal() {
	var container = document.getElementById("modals-here")
	var backdrop = document.getElementById("modal-backdrop")
	var modal = document.getElementById("modal")

	modal.classList.remove("show")
	backdrop.classList.remove("show")

	setTimeout(function() {
		container.removeChild(backdrop)
		container.removeChild(modal)
	}, 200)
}

// [TimeOff] test Consistency timeOff value (concidering start and end are between 23:00 and 00:59) //
function testTimeOffConsistency() {
	var _start_time = $('#qso_input input[name="start_time"]').val();
	var _end_time = $('#qso_input input[name="end_time"]').val();
	$('#qso_input input[name="end_time"]').removeClass('inputError');
	$('#qso_input .warningOnSubmit').hide();
	$('#qso_input .warningOnSubmit_txt').empty();
	if ( !( (parseInt(_start_time.replaceAll(':','')) <= parseInt(_end_time.replaceAll(':','')))
			|| ((_start_time.substring(0,2)=="23")&&(_end_time.substring(0,2)=="00")) ) ) {
		$('#qso_input input[name="end_time"]').addClass('inputError');
		$('#qso_input .warningOnSubmit_txt').html(text_error_timeoff_less_timeon);
		$('#qso_input .warningOnSubmit').show();
		$('#qso_input input[name="end_time"]').off('change').on('change',function(){ testTimeOffConsistency(); });
		return false;
	}
	return true;
}
