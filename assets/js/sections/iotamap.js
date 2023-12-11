var osmUrl = $('#iotamapjs').attr("tileUrl");

function load_iota_map() {
    $('.nav-tabs a[href="#iotamaptab"]').tab('show');
    $.ajax({
        url: base_url + 'index.php/awards/iota_map',
        type: 'post',
        data: {
            band: $('#band2').val(),
            mode: $('#mode').val(),
            worked: +$('#worked').prop('checked'),
            confirmed: +$('#confirmed').prop('checked'),
            notworked: +$('#notworked').prop('checked'),
            qsl: +$('#qsl').prop('checked'),
            lotw: +$('#lotw').prop('checked'),
            includedeleted: +$('#includedeleted').prop('checked'),
            Africa: +$('#Africa').prop('checked'),
            Asia: +$('#Asia').prop('checked'),
            Europe: +$('#Europe').prop('checked'),
            NorthAmerica: +$('#NorthAmerica').prop('checked'),
            SouthAmerica: +$('#SouthAmerica').prop('checked'),
            Oceania: +$('#Oceania').prop('checked'),
            Antarctica: +$('#Antarctica').prop('checked'),
        },
        success: function(data) {
            load_iota_map2(data, worked, confirmed, notworked);
        },
        error: function() {
            
        },
    });
}

function load_iota_map2(data, worked, confirmed, notworked) {

    // If map is already initialized
    var container = L.DomUtil.get('iotamap');

    if(container != null){
        container._leaflet_id = null;
        container.remove();
        $("#iotamaptab").append('<div id="iotamap" class="map-leaflet" ></div>');
    }

    var map = new L.Map('iotamap', {
        fullscreenControl: true,
        fullscreenControlOptions: {
          position: 'topleft'
        },
      });

    L.tileLayer(
        osmUrl,
        {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }
    ).addTo(map);

    var notworkedcount = data.length;
    var confirmedcount = 0;
    var workednotconfirmedcount = 0;

    for (var i = 0; i < data.length; i++) {
        var D = data[i];
        if (D['status'] != 'x') {
            var mapColor = 'red';
    
            if (D['status'] == 'C') {
                mapColor = 'green';
                if (confirmed != '0') {
                    addPolygon(L, D, mapColor, map);
                    addMarker(L, D, mapColor, map);
                    confirmedcount++;
                    notworkedcount--;
                }
            }
            if (D['status'] == 'W') {
                mapColor = 'orange';
                if (worked != '0') {
                    addPolygon(L, D, mapColor, map);
                    addMarker(L, D, mapColor, map);
                    workednotconfirmedcount++;
                    notworkedcount--;
                }
            }
    
            
        // Make a check here and hide what I don't want to show
            if (notworked != '0') {
                if (mapColor == 'red') {
                    addPolygon(L, D, mapColor, map);
                    addMarker(L, D, mapColor, map);
                }
            }
        }
    }

    /*Legend specific*/
    var legend = L.control({ position: "topright" });

    if (notworked.checked == false) {
        notworkedcount = 0;
    }

    legend.onAdd = function(map) {
        var div = L.DomUtil.create("div", "legend");
        div.innerHTML += "<h4>Colors</h4>";
        div.innerHTML += '<i style="background: green"></i><span>Confirmed ('+confirmedcount+')</span><br>';
        div.innerHTML += '<i style="background: orange"></i><span>Worked not confirmed ('+workednotconfirmedcount+')</span><br>';
        div.innerHTML += '<i style="background: red"></i><span>Not worked ('+notworkedcount+')</span><br>';
        return div;
    };

    legend.addTo(map);

    map.setView([20, 0], 2);
}

/*
 * We need to fix some islands that would wrap around the whole map.
 * That's why we add 360 degrees to some of them.
 * The following island have this problem:
 * 	 AN-016, AS-027, AS-092, AS-174, OC-016
 *   AN-020 is and exception
 */
function addPolygon(L, D, mapColor, map) {
    if (D['tag'] != 'AN-016') {
        if (D['lon1'] > 0 && D['lon2'] < 0 && D['lon1'] - D['lon2'] > 180) {
            D['lon2'] =  parseFloat(D['lon2'])+360;
        
        }
        
        if (D['lon1'] < 0 && D['lon2'] > 0 && D['lon2'] - D['lon1'] > 180) {
            D['lon1'] =  parseFloat(D['lon1'])+360;
        }
    }
    
    // It seems to me that latitudes have the wrong sign to be drawn correctly in leaflet. That's why they are mulitipled with -1 to be drawn in the correct hemisphere.
    var latlngs = [
        [D['lat1']*-1, D['lon1']],
        [D['lat2']*-1, D['lon1']],
        [D['lat2']*-1, D['lon2']],
        [D['lat1']*-1, D['lon2']]
    ];

    var polygon = L.polygon(latlngs, {color: mapColor}).addTo(map);
}

function addMarker(L, D, mapColor, map) {
    var title = '<span><font style="color: ' +mapColor+ '; text-shadow: 1px 0 #fff, -1px 0 #fff, 0 1px #fff, 0 -1px #fff, 1px 1px #fff, -1px -1px #fff, 1px -1px #fff, -1px 1px #fff;font-size: 14px; font-weight: 900;">' + D['tag'] + '</font></span>';
    var myIcon = L.divIcon({
        className: 'my-div-icon', 
        html: title, 
        iconSize: [60, 10]
    });

    var markerTitle = D['tag'] + ' - ' + D['prefix'] + ' - ' + D['name'];

    // It seems to me that latitudes have the wrong sign to be drawn correctly in leaflet. That's why they are mulitipled with -1 to be drawn in the correct hemisphere.
    L.marker(
    [D['lat1']*-1, D['lon1']], {
        icon: myIcon,
        iota: D['tag'],
        title: markerTitle,
    }
    ).addTo(map).on('click', onClick);
}

function onClick(e) {
    var marker = e.target;
    displayContactsOnMap($("#iotamap"), marker.options.iota,$('#band2').val(), $('#mode').val(), 'IOTA');
}
