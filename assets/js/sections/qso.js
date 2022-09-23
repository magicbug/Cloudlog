$( document ).ready(function() {

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
		loadThrottle: 250,
		valueField: 'name',
		labelField: 'name',
		searchField: 'name',
		options: [],
		create: false,
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
		loadThrottle: 250,
		valueField: 'name',
		labelField: 'name',
		searchField: 'name',
		options: [],
		create: false,
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
		$('#wwff_info').html('<a target="_blank" href="https://wwff.co/directory/?showRef='+$('#wwff_ref').val()+'"><img width="32" height="32" src="'+base_url+'images/icons/wwff.co.png"></a>'); 
		$('#wwff_info').attr('title', 'Lookup '+$('#wwff_ref').val()+' reference info on wwff.co');
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

								if (val2[0].Uplink_Mode == "LSB" || val2[0].Uplink_Mode == "USB") {
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
		$.getJSON('logbook/jsonlookupdxcc/' + convert_case(entityname) + '/SAT/0/0', function(result)
		{

			$('#callsign_info').removeClass("badge-secondary");
			$('#callsign_info').removeClass("badge-success");
			$('#callsign_info').removeClass("badge-danger");
			$('#callsign_info').attr('title', '');

			if (result.workedBefore)
			{
				$('#callsign_info').addClass("badge-success");
				$('#callsign_info').attr('title', 'DXCC was already worked in the past on this band and mode!');
			}
			else
			{
				$('#callsign_info').addClass("badge-danger");
				$('#callsign_info').attr('title', 'New DXCC, not worked on this band and mode!');
			}
		})
	} else {
		$.getJSON('logbook/jsonlookupdxcc/' + convert_case(entityname) + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
		{
			// Reset CSS values before updating
			$('#callsign_info').removeClass("badge-secondary");
			$('#callsign_info').removeClass("badge-success");
			$('#callsign_info').removeClass("badge-danger");
			$('#callsign_info').attr('title', '');

			if (result.workedBefore)
			{
				$('#callsign_info').addClass("badge-success");
				$('#callsign_info').attr('title', 'DXCC was already worked in the past on this band and mode!');
			}
			else
			{
				$('#callsign_info').addClass("badge-danger");
				$('#callsign_info').attr('title', 'New DXCC, not worked on this band and mode!');
			}
		})
	}
}

/* Function: reset_fields is used to reset the fields on the QSO page */
function reset_fields() {

	$('#locator_info').text("");
	$('#country').val("");
	$('#lotw_info').text("");
	$('#qrz_info').text("");
	$('#hamqth_info').text("");
	$('#sota_info').text("");
	$('#dxcc_id').val("");
	$('#cqz').val("");
	$('#name').val("");
	$('#qth').val("");
	$('#locator').val("");
	$('#iota_ref').val("");
	$('#sota_ref').val("");
	$("#locator").removeClass("workedGrid");
	$("#locator").removeClass("newGrid");
	$("#callsign").removeClass("workedGrid");
	$("#callsign").removeClass("newGrid");
	$('#callsign_info').removeClass("badge-secondary");
	$('#callsign_info').removeClass("badge-success");
	$('#callsign_info').removeClass("badge-danger");
	$('#callsign-image').attr('style', 'display: none;');
	$('#callsign-image-content').text("");
	$('#qsl_via').val("");
	$('#callsign_info').text("");
	$('#input_usa_state').val("");
	$('#qso-last-table').show();
	$('#partial_view').hide();
	var $select = $('#wwff_ref').selectize();
	var selectize = $select[0].selectize;
	selectize.clear();
	var $select = $('#darc_dok').selectize();
	var selectize = $select[0].selectize;
	selectize.clear();
	$select = $('#stationCntyInput').selectize();
	selectize = $select[0].selectize;
	selectize.clear();

	mymap.setView(pos, 12);
	mymap.removeLayer(markers);
	$('.callsign-suggest').hide();
}

$("#callsign").focusout(function() {
	if ($(this).val().length >= 3) {

		// Temp store the callsign
		var temp_callsign = $(this).val();

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

		find_callsign.replace(/\//g, "-");

		// Replace / in a callsign with - to stop urls breaking
		$.getJSON('logbook/json/' + find_callsign.replace(/\//g, "-") + '/' + sat_type + '/' + json_band + '/' + json_mode + '/' + $('#stationProfile').val(), function(result)
		{
			// Make sure the typed callsign and temp callsign match
			if($('#callsign').val = temp_callsign){

				if(result.dxcc.entity != undefined) {
					$('#country').val(convert_case(result.dxcc.entity));
					$('#callsign_info').text(convert_case(result.dxcc.entity));

					if($("#sat_name" ).val() != "") {
						//logbook/jsonlookupgrid/io77/SAT/0/0
						$.getJSON('logbook/jsonlookupcallsign/' + find_callsign.replace(/\//g, "-") + '/SAT/0/0', function(result)
						{
							// Reset CSS values before updating
							$('#callsign').removeClass("workedGrid");
							$('#callsign').removeClass("newGrid");
							$('#callsign').attr('title', '');

							if (result.workedBefore)
							{
								$('#callsign').addClass("workedGrid");
								$('#callsign').attr('title', 'Callsign was already worked in the past on this band and mode!');
							}
							else
							{
								$('#callsign').addClass("newGrid");
								$('#callsign').attr('title', 'New Callsign!');
							}
						})
					} else {
						$.getJSON('logbook/jsonlookupcallsign/' + find_callsign.replace(/\//g, "-") + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
						{
							// Reset CSS values before updating
							$('#callsign').removeClass("workedGrid");
							$('#callsign').removeClass("newGrid");
							$('#callsign').attr('title', '');

							if (result.workedBefore)
							{
								$('#callsign').addClass("workedGrid");
								$('#callsign').attr('title', 'Callsign was already worked in the past on this band and mode!');
							}
							else
							{
								$('#callsign').addClass("newGrid");
								$('#callsign').attr('title', 'New Callsign!');
							}
						})
					}

					changebadge(result.dxcc.entity);
				}

				if(result.lotw_member == "active") {
					$('#lotw_info').text("LoTW");
				}
				$('#qrz_info').html('<a target="_blank" href="https://www.qrz.com/db/'+find_callsign+'"><img width="32" height="32" src="'+base_url+'images/icons/qrz.com.png"></a>'); 
				$('#qrz_info').attr('title', 'Lookup '+find_callsign+' info on qrz.com');
				$('#hamqth_info').html('<a target="_blank" href="https://www.hamqth.com/'+find_callsign+'"><img width="32" height="32" src="'+base_url+'images/icons/hamqth.com.png"></a>'); 
				$('#hamqth_info').attr('title', 'Lookup '+find_callsign+' info on hamqth.com');

				var $dok_select = $('#darc_dok').selectize();
				var dok_selectize = $dok_select[0].selectize;
				if (result.dxcc.adif == '230') {
					$.get('lookup/dok/' + $('#callsign').val().toUpperCase(), function(result) {
						if (result) {
							dok_selectize.addOption({name: result});
							dok_selectize.setValue(result, false);
						}
					});
				} else {
					dok_selectize.clear();
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


				/* Find Locator if the field is empty */
				if($('#locator').val() == "") {
					$('#locator').val(result.callsign_qra);
					$('#locator_info').html(result.bearing);

					if (result.callsign_qra != "")
					{
						if (result.workedBefore)
						{
							$('#locator').addClass("workedGrid");
							$('#locator').attr('title', 'Grid was already worked in the past');
						}
						else
						{
							$('#locator').addClass("newGrid");
							$('#locator').attr('title', 'New grid!');
						}
					}
					else
					{
						$('#locator').removeClass("workedGrid");
						$('#locator').removeClass("newGrid");
						$('#locator').attr('title', '');
					}

				}

				/* Find Operators Name */
				if($('#qsl_via').val() == "") {
					$('#qsl_via').val(result.qsl_manager);
				}

				/* Find Operators Name */
				if($('#name').val() == "") {
					$('#name').val(result.callsign_name);
				}

				if($('#qth').val() == "") {
					$('#qth').val(result.callsign_qth);
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
				if( $('#stationCntyInput').has('option').length == 0 && result.callsign_us_county != "") {
					var $county_select = $('#stationCntyInput').selectize();
					var county_selectize = $county_select[0].selectize;
					county_selectize.addOption({name: result.callsign_us_county});
					county_selectize.setValue(result.callsign_us_county, false);
				}

				if($('#iota_ref').val() == "") {
					$('#iota_ref').val(result.callsign_iota);
				}
				// Hide the last QSO table
				$('#qso-last-table').hide();
				$('#partial_view').show();
				/* display past QSOs */
				$('#partial_view').html(result.partial);
			}
		});
	} else {
		/* Reset fields ... */
		$('#callsign_info').text("");
		$('#locator_info').text("");
		$('#country').val("");
		$('#dxcc_id').val("");
		$('#cqz').val("");
		$('#name').val("");
		$('#qth').val("");
		$('#locator').val("");
		$('#iota_ref').val("");
		$('#sota_ref').val("");
		$("#locator").removeClass("workedGrid");
		$("#locator").removeClass("newGrid");
		$("#callsign").removeClass("workedGrid");
		$("#callsign").removeClass("newGrid");
		$('#callsign_info').removeClass("badge-secondary");
		$('#callsign_info').removeClass("badge-success");
		$('#callsign_info').removeClass("badge-danger");
		$('#input_usa_state').val("");
	}
})

// Only set the frequency when not set by userdata/PHP.
if ($('#frequency').val() == "")
{
	$.get('qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function(result) {
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

/* On Key up Calculate Bearing and Distance */
$("#locator").keyup(function(){
	if ($(this).val()) {
		var qra_input = $(this).val();

		var qra_lookup = qra_input.substring(0, 4);

		if(qra_lookup.length >= 4) {

			// Check Log if satname is provided
			if($("#sat_name" ).val() != "") {

				//logbook/jsonlookupgrid/io77/SAT/0/0

				$.getJSON('logbook/jsonlookupgrid/' + qra_lookup.toUpperCase() + '/SAT/0/0', function(result)
				{
					// Reset CSS values before updating
					$('#locator').removeClass("workedGrid");
					$('#locator').removeClass("newGrid");
					$('#locator').attr('title', '');

					if (result.workedBefore)
					{
						$('#locator').addClass("workedGrid");
						$('#locator').attr('title', 'Grid was already worked in the past');
					}
					else
					{
						$('#locator').addClass("newGrid");
						$('#locator').attr('title', 'New grid!');
					}
				})
			} else {
				$.getJSON('logbook/jsonlookupgrid/' + qra_lookup.toUpperCase() + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
				{
					// Reset CSS values before updating
					$('#locator').removeClass("workedGrid");
					$('#locator').removeClass("newGrid");
					$('#locator').attr('title', '');

					if (result.workedBefore)
					{
						$('#locator').addClass("workedGrid");
						$('#locator').attr('title', 'Grid was already worked in the past');
					}
					else
					{
						$('#locator').addClass("newGrid");
						$('#locator').attr('title', 'New grid!');
					}
				})
			}
		}

		if(qra_input.length >= 4 && $(this).val().length > 0) {
			$.getJSON('logbook/qralatlngjson/' + $(this).val(), function(result)
			{
				// Set Map to Lat/Long
				markers.clearLayers();
				if (typeof result !== "undefined") {
					var redIcon = L.icon({
						iconUrl: icon_dot_url,
						iconSize:     [18, 18], // size of the icon
					});

					var marker = L.marker([result[0], result[1]], {icon: redIcon});
					mymap.setZoom(8);
					mymap.panTo([result[0], result[1]]);
					mymap.setView([result[0], result[1]], 8);
				}
				markers.addLayer(marker).addTo(mymap);
			})

			$('#locator_info').load("logbook/searchbearing/" + $(this).val() + "/" + $('#stationProfile').val()).fadeIn("slow");
		}
	}
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

$('#dxcc_id').on('change', function() {
	$.getJSON('logbook/jsonentity/' + $(this).val(), function (result) {
		if (result.dxcc.name != undefined) {

			$('#country').val(convert_case(result.dxcc.name));
			$('#cqz').val(convert_case(result.dxcc.cqz));

			$('#callsign_info').removeClass("badge-secondary");
			$('#callsign_info').removeClass("badge-success");
			$('#callsign_info').removeClass("badge-danger");
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

// On Key up check and suggest callsigns
$("#callsign").keyup(function() {
	if ($(this).val().length >= 3) {
		$('.callsign-suggest').show();
		$.get('lookup/scp/' + $(this).val().toUpperCase(), function(result) {
			$('.callsign-suggestions').text(result);
		});
	}
});
