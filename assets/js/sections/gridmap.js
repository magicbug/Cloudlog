var modalloading=false;

$('#band').change(function(){
	var band = $("#band option:selected").text();
	if (band != "SAT") {
		$("#sats").prop('disabled', true);
        $("#satellite-filter-group").addClass('d-none');
        $("#sats").val('All');
        $("#sat_orbit").val('All');
	} else {
		$("#sats").prop('disabled', false);
        $("#satellite-filter-group").removeClass('d-none').addClass('d-flex');
	}
});

var map;
if (typeof(visitor) !== 'undefined' && visitor != true) {
   var grid_two = '';
   var grid_four = '';
   var grid_six = '';
   var grid_two_confirmed = '';
   var grid_four_confirmed = '';
   var grid_six_confirmed = '';
}

function gridPlot(form, visitor=true) {
    $(".ld-ext-right-plot").addClass('running');
    $(".ld-ext-right-plot").prop('disabled', true);
    $('#plot').prop("disabled", true);
    // If map is already initialized
    var container = L.DomUtil.get('gridsquare_map');

    if(container != null){
        container._leaflet_id = null;
        container.remove();
        $("#gridmapcontainer").append('<div id="gridsquare_map" class="map-leaflet" style="width: 100%; height: 800px"></div>');
    }

    if (typeof type == 'undefined') { type=''; }
    if (type == "activated") {
        ajax_url = site_url + '/activated_gridmap/getGridsjs';
    } else if (type == "worked") {
        ajax_url = site_url + '/gridmap/getGridsjs';
    } else {
        ajax_url = site_url + '/gridmap/getGridsjs';
    }

    // If visitor context, get the slug from the URL and use visitor endpoint
    if (visitor === true) {
        var pathParts = window.location.pathname.split('/');
        var slugIndex = pathParts.indexOf('satellites');
        if (slugIndex !== -1 && pathParts[slugIndex + 1]) {
            var slug = pathParts[slugIndex + 1];
            ajax_url = site_url + '/visitor/getGridsjs';
        }
    }

    if (visitor != true) {
    $.ajax({
		url: ajax_url,
		type: 'post',
		data: {
			band: $("#band").val(),
                sat_orbit: $("#sat_orbit").val(),
            mode: $("#mode").val(),
            qsl:  $("#qsl").is(":checked"),
            lotw: $("#lotw").is(":checked"),
            eqsl: $("#eqsl").is(":checked"),
            qrz: $("#qrz").is(":checked"),
            sat: $("#sats").val(),
		},
		success: function (data) {
            console.log(data);
            $('.cohidden').show();
            $(".ld-ext-right-plot").removeClass('running');
            $(".ld-ext-right-plot").prop('disabled', false);
            $('#plot').prop("disabled", false);
            grid_two = data.grid_2char;
            grid_four = data.grid_4char;
            grid_six = data.grid_6char;
            grid_two_confirmed = data.grid_2char_confirmed;
            grid_four_confirmed = data.grid_4char_confirmed;
            grid_six_confirmed = data.grid_6char_confirmed;
            updateSatelliteFilterVisibility();
				renderActivatedGridSummary({
					worked: grid_four.length,
					confirmed: grid_four_confirmed.length,
					satellite: data.satellite_breakdown || { total: 0, LEO: 0, MEO: 0, GEO: 0 },
					satelliteConfirmed: data.satellite_breakdown_confirmed || { total: 0, LEO: 0, MEO: 0, GEO: 0 }
				});
            plot(visitor, grid_two, grid_four, grid_six, grid_two_confirmed, grid_four_confirmed, grid_six_confirmed);

		},
		error: function (data) {
		},
	});
   } else {
       // Visitor context - use AJAX to get filtered data
       var pathParts = window.location.pathname.split('/');
       var slugIndex = pathParts.indexOf('satellites');
       if (slugIndex !== -1 && pathParts[slugIndex + 1]) {
           var slug = pathParts[slugIndex + 1];
           $.ajax({
               url: ajax_url,
               type: 'post',
               data: {
                   slug: slug,
                   band: $("#band").val(),
                   sat_orbit: $("#sat_orbit").val(),
                   mode: $("#mode").val(),
                   sat: $("#sats").val(),
               },
               success: function (data) {
                   console.log(data);
                   $('.cohidden').show();
                   $(".ld-ext-right-plot").removeClass('running');
                   $(".ld-ext-right-plot").prop('disabled', false);
                   $('#plot').prop("disabled", false);
                   grid_two = data.grid_2char;
                   grid_four = data.grid_4char;
                   grid_six = data.grid_6char;
                   grid_two_confirmed = data.grid_2char_confirmed;
                   grid_four_confirmed = data.grid_4char_confirmed;
                   grid_six_confirmed = data.grid_6char_confirmed;
                   updateSatelliteFilterVisibility();
                    renderActivatedGridSummary({
                        worked: grid_four.length,
                        confirmed: grid_four_confirmed.length,
                        satellite: data.satellite_breakdown || { total: 0, LEO: 0, MEO: 0, GEO: 0 },
                        satelliteConfirmed: data.satellite_breakdown_confirmed || { total: 0, LEO: 0, MEO: 0, GEO: 0 }
                    });
                   plot(visitor, grid_two, grid_four, grid_six, grid_two_confirmed, grid_four_confirmed, grid_six_confirmed);
               },
               error: function (data) {
                   console.error('Error loading visitor grid data:', data);
               },
           });
       } else {
           // Fallback: use predefined grid data if available
           plot(visitor, grid_two, grid_four, grid_six, grid_two_confirmed, grid_four_confirmed, grid_six_confirmed);
       }
   };
}

function plot(visitor, grid_two, grid_four, grid_six, grid_two_confirmed, grid_four_confirmed, grid_six_confirmed) {
            var layer = L.tileLayer(jslayer, {
                maxZoom: 12,
                attribution: jsattribution,
                id: 'mapbox.streets'
            });

            map = L.map('gridsquare_map', {
            layers: [layer],
            center: [19, 0],
            zoom: 3,
            minZoom: 2,
            fullscreenControl: true,
                fullscreenControlOptions: {
                    position: 'topleft'
                },
            });

            if (visitor != true) {
               var printer = L.easyPrint({
                   tileLayer: layer,
                   sizeModes: ['Current'],
                   filename: 'myMap',
                   exportOnly: true,
                   hideControlContainer: true
               }).addTo(map);
            }

            /*Legend specific*/
            var legend = L.control({ position: "topright" });

            legend.onAdd = function(map) {
                var div = L.DomUtil.create("div", "legend");
                div.innerHTML += "<h4>" + gridsquares_gridsquares + "</h4>";
                div.innerHTML += '<i style="background: green"></i><span>' + gridsquares_gridsquares_confirmed + ' ('+grid_four_confirmed.length+')</span><br>';
                div.innerHTML += '<i style="background: red"></i><span>' + gridsquares_gridsquares_not_confirmed + ' ('+(grid_four.length - grid_four_confirmed.length)+')</span><br>';
                div.innerHTML += '<i></i><span>' + gridsquares_gridsquares_total_worked + ' ('+grid_four.length+')</span><br>';
                return div;
            };

            legend.addTo(map);

            var maidenhead = L.maidenhead().addTo(map);
            if (visitor != true) {
               map.on('mousemove', onMapMove);
               map.on('click', onMapClick);
            }
}

function renderActivatedGridSummary(summary) {
    var worked = Number(summary.worked || 0);
    var confirmed = Number(summary.confirmed || 0);
    var unconfirmed = Math.max(worked - confirmed, 0);
    var confirmedPercent = worked > 0 ? Math.round((confirmed / worked) * 100) : 0;
    var satellite = summary.satellite || { total: 0, LEO: 0, MEO: 0, GEO: 0 };
    var satelliteConfirmed = summary.satelliteConfirmed || { total: 0, LEO: 0, MEO: 0, GEO: 0 };

    var html = '';
    html += '<div class="col-lg-4 col-md-6">';
    html += '  <div class="card shadow-sm border-0 h-100">';
    html += '    <div class="card-body">';
    html += '      <div class="text-uppercase text-muted small fw-semibold">Worked grids</div>';
    html += '      <div class="display-6 fw-bold">' + worked + '</div>';
    html += '      <div class="text-muted">Unique 4-character squares on the map</div>';
    html += '    </div>';
    html += '  </div>';
    html += '</div>';

    html += '<div class="col-lg-4 col-md-6">';
    html += '  <div class="card shadow-sm border-0 h-100">';
    html += '    <div class="card-body">';
    html += '      <div class="text-uppercase text-muted small fw-semibold">Confirmed grids</div>';
    html += '      <div class="display-6 fw-bold text-success">' + confirmed + '</div>';
    html += '      <div class="progress mb-2" style="height: 8px;">';
    html += '        <div class="progress-bar bg-success" role="progressbar" style="width: ' + confirmedPercent + '%" aria-valuenow="' + confirmedPercent + '" aria-valuemin="0" aria-valuemax="100"></div>';
    html += '      </div>';
    html += '      <div class="text-muted">' + confirmedPercent + '% of worked squares are confirmed</div>';
    html += '      <div class="mt-2 small text-muted">Unconfirmed: <span class="fw-semibold text-danger">' + unconfirmed + '</span></div>';
    html += '    </div>';
    html += '  </div>';
    html += '</div>';

    html += '<div class="col-lg-4 col-md-12">';
    html += '  <div class="card shadow-sm border-0 h-100">';
    html += '    <div class="card-body">';
    html += '      <div class="d-flex justify-content-between align-items-start gap-2">';
    html += '        <div>';
    html += '          <div class="text-uppercase text-muted small fw-semibold">Satellite split</div>';
    html += '          <div class="display-6 fw-bold">' + satellite.total + '</div>';
    html += '        </div>';
    html += '        <span class="badge rounded-pill text-bg-dark align-self-start">LEO / MEO / GEO</span>';
    html += '      </div>';
    html += '      <div class="d-flex flex-wrap gap-2">';
    html += '        <span class="badge rounded-pill text-bg-primary">LEO ' + satellite.LEO + '</span>';
    html += '        <span class="badge rounded-pill text-bg-warning">MEO ' + satellite.MEO + ' (IO-117)</span>';
    html += '        <span class="badge rounded-pill text-bg-info">GEO ' + satellite.GEO + ' (QO-100)</span>';
    html += '      </div>';
    html += '      <div class="mt-2 small text-muted">Counts are per satellite class and can overlap if a grid has been worked on more than one satellite.</div>';
    html += '    </div>';
    html += '  </div>';
    html += '</div>';

    $('#activated-grid-summary').html(html);
}

function updateSatelliteFilterVisibility() {
    var band = $("#band").val();
    if (band === 'SAT') {
        $("#satellite-filter-group").removeClass('d-none').addClass('d-flex');
        $("#sats").prop('disabled', false);
    } else {
        $("#satellite-filter-group").addClass('d-none').removeClass('d-flex');
        $("#sats").prop('disabled', true).val('All');
        $("#sat_orbit").val('All');
    }
}

function spawnGridsquareModal(loc_4char) {
	if (!(modalloading)) {
		var ajax_data = ({
			'Searchphrase': loc_4char,
			'Band': $("#band").val(),
			'Mode': $("#mode").val(),
			'Type': 'VUCC'
		})
		if (type == 'activated') {
			ajax_data.searchmode = 'activated';
		}
		modalloading=true;
		$.ajax({
			url: base_url + 'index.php/awards/qso_details_ajax',
			type: 'post',
			data: ajax_data,
			success: function (html) {
				BootstrapDialog.show({
					title: lang_general_word_qso_data,
					cssClass: 'qso-dialog',
					size: BootstrapDialog.SIZE_WIDE,
					nl2br: false,
					message: html,
					onshown: function(dialog) {
						modalloading=false;
						$('[data-bs-toggle="tooltip"]').tooltip();
						$('.contacttable').DataTable({
							"pageLength": 25,
							responsive: false,
							ordering: false,
							"scrollY":        "550px",
							"scrollCollapse": true,
							"paging":         false,
							"scrollX": true,
                            "language": {
                                url: getDataTablesLanguageUrl(),
                            },
							dom: 'Bfrtip',
							buttons: [
								'csv'
							]
						});
						// change color of csv-button if dark mode is chosen
						if (isDarkModeTheme()) {
							$(".buttons-csv").css("color", "white");
						}
                        $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function () {
                            showQsoActionsMenu($(this).closest('.dropdown'));
                        });
					},
					buttons: [{
						label: lang_admin_close,
						action: function(dialogItself) {
							dialogItself.close();
						}
					}]
				});
			},
			error: function(e) {
				modalloading=false;
			}
		});
	}
}

function clearMarkers() {
	$(".ld-ext-right-clear").addClass('running');
	$(".ld-ext-right-clear").prop('disabled', true);
	clicklines.forEach(function (item) {
		map.removeLayer(item)
	});
	clickmarkers.forEach(function (item) {
		map.removeLayer(item)
	});
	$(".ld-ext-right-clear").removeClass('running');
	$(".ld-ext-right-clear").prop('disabled', false);
}

$(document).ready(function(){
    updateSatelliteFilterVisibility();
    gridPlot(this.form, visitor);
})
