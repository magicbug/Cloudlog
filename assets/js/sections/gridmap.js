$('#band').change(function(){
	var band = $("#band option:selected").text();
	if (band != "SAT") {
		$("#sats").prop('disabled', true);
	} else {
		$("#sats").prop('disabled', false);
	}
});

var map;
var grid_two = '';
var grid_four = '';
var grid_six = '';
var grid_two_confirmed = '';
var grid_four_confirmed = '';
var grid_six_confirmed = '';

function gridPlot(form) {
    $(".ld-ext-right-plot").addClass('running');
	$(".ld-ext-right-plot").prop('disabled', true);
    $('#plot').prop("disabled", true);
    // If map is already initialized
    var container = L.DomUtil.get('gridsquare_map');

    if(container != null){
        container._leaflet_id = null;
        container.remove();
        $("#gridmapcontainer").append('<div id="gridsquare_map" style="width: 100%; height: 800px"></div>');
    }

    if (type == "activated") {
        ajax_url = site_url + '/activated_gridmap/getGridsjs';
    } else if (type == "worked") {
        ajax_url = site_url + '/gridmap/getGridsjs';
    } else {
        ajax_url = site_url + '/gridmap/getGridsjs';
    }

    $.ajax({
		url: ajax_url,
		type: 'post',
		data: {
			band: $("#band").val(),
            mode: $("#mode").val(),
            qsl:  $("#qsl").is(":checked"),
            lotw: $("#lotw").is(":checked"),
            eqsl: $("#eqsl").is(":checked"),
            sat: $("#sats").val(),
		},
		success: function (data) {
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

            var printer = L.easyPrint({
                tileLayer: layer,
                sizeModes: ['Current'],
                filename: 'myMap',
                exportOnly: true,
                hideControlContainer: true
            }).addTo(map);

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
            map.on('mousemove', onMapMove);
			map.on('click', onMapClick);

		},
		error: function (data) {
		},
	});
}

function spawnGridsquareModal(loc_4char) {
    var ajax_data = ({
       'Searchphrase': loc_4char,
       'Band': $("#band").val(),
       'Mode': $("#mode").val(),
       'Type': 'VUCC'
    })
    if (type == 'activated') {
       ajax_data.searchmode = 'activated';
    }
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

                    $('[data-toggle="tooltip"]').tooltip();
                    $('.contacttable').DataTable({
                            "pageLength": 25,
                            responsive: false,
                            ordering: false,
                            "scrollY":        "550px",
                            "scrollCollapse": true,
                            "paging":         false,
                            "scrollX": true,
                            dom: 'Bfrtip',
                            buttons: [
                                'csv'
                            ]
                        });
                            // change color of csv-button if dark mode is chosen
                    if (isDarkModeTheme()) {
                        $(".buttons-csv").css("color", "white");
                    }
                    },
                buttons: [{
                    label: lang_admin_close,
                    action: function(dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    });
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
   gridPlot(this.form);
})
