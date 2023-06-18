var map;
var grid_two = '';
var grid_four = '';
var grid_six = '';
var grid_two_confirmed = '';
var grid_four_confirmed = '';
var grid_six_confirmed = '';

function gridPlot(form) {
    $('#plot').prop("disabled", true);
    // If map is already initialized
    var container = L.DomUtil.get('gridsquare_map');

    if(container != null){
        container._leaflet_id = null;
        container.remove();
        $("#gridmapcontainer").append('<div id="gridsquare_map" style="width: 100%; height: 800px"></div>');
    }

    $.ajax({
		url: site_url + '/gridmap/getGridsjs',
		type: 'post',
		data: {
			band: $("#band").val(),
            mode: $("#mode").val(),
            qsl:  $("#qsl").is(":checked"),
            lotw: $("#lotw").is(":checked"),
            eqsl: $("#eqsl").is(":checked")
		},
		success: function (data) {
            $('#plot').prop("disabled", false);
			grid_two = data.grid_2char;
            grid_four = data.grid_4char;
            grid_six = data.grid_6char;
            grid_two_confirmed = data.grid_2char_confirmed;
            grid_four_confirmed = data.grid_4char_confirmed;
            grid_six_confirmed = data.grid_6char_confirmed;
            var layer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 9,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
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
            var maidenhead = L.maidenhead().addTo(map);
		},
		error: function (data) {
		},
	});
}

function spawnGridsquareModal(loc_4char) {
    $.ajax({
        url: base_url + 'index.php/awards/qso_details_ajax',
        type: 'post',
        data: {
            'Searchphrase': loc_4char,
            'Band': $("#band").val(),
            'Mode': $("#mode").val(),
            'Type': 'VUCC'
        },
        success: function (html) {
            BootstrapDialog.show({
                title: 'QSO Data',
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