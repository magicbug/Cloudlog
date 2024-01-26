var map;
var grid_four = '';
var grid_four_lotw = '';
var grid_four_paper = '';

function gridPlot(form) {
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

    ajax_url = site_url + '/awards/getFfmaGridsjs';

    $.ajax({
      url: ajax_url,
      type: 'get',
      success: function (data) {
            $('.cohidden').show();
            $(".ld-ext-right-plot").removeClass('running');
            $(".ld-ext-right-plot").prop('disabled', false);
            $('#plot').prop("disabled", false);
            grids = data.grids;
            grid_max = data.grid_count;
            grid_four = data.grid_4char;
            grid_four_lotw = data.grid_4char_lotw;
            grid_four_paper = data.grid_4char_paper;
            paper_count = 0;
            grid_four_paper.forEach((element) => {
               if (!grid_four_lotw.includes(element)) {
                  paper_count++;
               }
            });
            var layer = L.tileLayer(jslayer, {
                maxZoom: 12,
                attribution: jsattribution,
                id: 'mapbox.streets'
            });

            map = L.map('gridsquare_map', {
            layers: [layer],
            center: [38, -91],
            zoom: 5,
            minZoom: 4,
            maxZoom: 12,
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
                //div.innerHTML += "<h4>" + gridsquares_gridsquares + "</h4>";
                html = "<table border=\"0\">";
                html += '<tr><td><i style="background: #90ee90"></i><span>' + gridsquares_gridsquares_lotw + ':</span></td><td style=\"padding-left: 1em; text-align: right;\"><span>'+grid_four_lotw.length+' / '+grid_max+'</span></td></tr>';
                html += '<tr><td><i style="background: #00b0f0"></i><span>' + gridsquares_gridsquares_paper + ':</span></td><td style=\"padding-left: 1em; text-align: right;\"><span>'+paper_count+' / '+grid_max+'</span></td></tr>';
                html += '<tr><td><i style="background: #ffd757"></i><span>' + gridsquares_gridsquares_worked + ' ('+(Math.round((grid_four.length / grid_max) * 10000) / 100)+'%):</span></td><td style=\"padding-left: 1em; text-align: right;\"><span>'+(grid_four.length)+' / '+grid_max+'</span></td></tr>';
                html += "</table>";
                div.innerHTML = html;
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
       'Band': '6m',
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
        }
    });
}

$(document).ready(function(){
   gridPlot(this.form);
})
