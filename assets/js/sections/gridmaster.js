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

    ajax_url = site_url + '/awards/getGridmasterGridsjs';

    $.ajax({
		url: ajax_url,
		type: 'get',
		success: function (data) {
            $('.cohidden').show();
            $(".ld-ext-right-plot").removeClass('running');
            $(".ld-ext-right-plot").prop('disabled', false);
            $('#plot').prop("disabled", false);
            grid_four = data.grid_4char;
            grid_four_confirmed = data.grid_4char_confirmed;
            var layer = L.tileLayer(jslayer, {
                maxZoom: 12,
                attribution: jsattribution,
                id: 'mapbox.streets'
            });

            map = L.map('gridsquare_map', {
            layers: [layer],
            center: [38, -95],
            zoom: 5,
            minZoom: 5,
            maxZoom: 5,
            dragging: false,
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
                div.innerHTML += '<i style="background: red"></i><span>' + gridsquares_gridsquares_worked + ' ('+(grid_four.length)+')</span><br>';
                div.innerHTML += '<i style="background: green"></i><span>' + gridsquares_gridsquares_confirmed + ' ('+grid_four_confirmed.length+')</span><br>';
                return div;
            };

            legend.addTo(map);

            var maidenhead = L.maidenhead().addTo(map);
            map.on('mousemove', onMapMove);

		},
		error: function (data) {
		},
	});
}

function spawnGridsquareModal(loc_4char) {
    var ajax_data = ({
       'Searchphrase': loc_4char,
       'Band': 'SAT',
       'Type': 'VUCC'
    })
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
                    label: 'Close',
                    action: function(dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    });
}

$(document).ready(function(){
   gridPlot(this.form);
})
