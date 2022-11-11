var osmUrl = $('#dxccmapjs').attr("tileUrl");

function load_dxcc_map() {
    $('.nav-tabs a[href="#dxccmaptab"]').tab('show');
    $.ajax({
        url: base_url + 'index.php/awards/dxcc_map',
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
            load_dxcc_map2(data);
        },
        error: function() {
            
        },
    });
}

function load_dxcc_map2(data) {

    // If map is already initialized
    var container = L.DomUtil.get('dxccmap');

    if(container != null){
        container._leaflet_id = null;
        container.remove();
        $("#dxccmaptab").append('<div id="dxccmap"></div>');
    }

    var map = L.map('dxccmap');
    L.tileLayer(
        osmUrl,
        {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            maxZoom: 18
        }
    ).addTo(map);

    var notworked = data.length;
    var confirmed = 0;
    var workednotconfirmed = 0;

    for (var i = 0; i < data.length; i++) {
    var D = data[i];
        var mapColor = 'red';

        if (D['status'] == 'C') {
            mapColor = 'green';
            confirmed++;
            notworked--;
        }
        if (D['status'] == 'W') {
          mapColor = 'orange';
          workednotconfirmed++;
          notworked--;
        }

        const markerHtmlStyles = `
          background-color: ${mapColor};
          width: 1rem;
          height: 1rem;
          display: block;
          position: relative;
          border-radius: 3rem 3rem 0;
          transform: rotate(45deg);
          border: 1px solid #FFFFFF`
        
        const icon = L.divIcon({
          className: "my-custom-pin",
          iconAnchor: [0, 24],
          labelAnchor: [-6, 0],
          popupAnchor: [0, -36],
          html: `<span style="${markerHtmlStyles}" />`
        })

        L.marker(
            [D['lat'], D['long']], {
                icon: icon,
                title: D['adif']
            }
          ).addTo(map).on('click', onClick);
    }

    /*Legend specific*/
    var legend = L.control({ position: "topright" });

    legend.onAdd = function(map) {
        var div = L.DomUtil.create("div", "legend");
        div.innerHTML += "<h4>Colors</h4>";
        div.innerHTML += '<i style="background: green"></i><span>Confirmed ('+confirmed+')</span><br>';
        div.innerHTML += '<i style="background: orange"></i><span>Worked not confirmed ('+workednotconfirmed+')</span><br>';
        div.innerHTML += '<i style="background: red"></i><span>Not worked ('+notworked+')</span><br>';
        return div;
    };

    legend.addTo(map);

    map.setView([20, 0], 2);
}

function onClick(e) {
    var marker = e.target;
    displayContacts(marker.options.title, $('#band2').val(), $('#mode').val(), 'DXCC2');
}
