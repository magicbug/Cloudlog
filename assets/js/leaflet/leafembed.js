var map;
var plotlayers=[];

var homeIcon = L.divIcon({className:'cplotmap fas fa-home red'});
var redIcon = L.divIcon({className:'cplotmap fas fa-dot-circle red'}); //fa-circle fa-bullseye fa-dot-circle
var redIconImg = L.icon({ iconUrl: icon_dot_url, iconSize: [10, 10] });

var osmUrl = $('#leafembed').attr("tileUrl");

function initmap(ShowGrid='No', MapTag='map', options={}) {
    // set up the map
    map = new L.Map(MapTag);
    // create the tile layer with correct attribution
    var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    var osm = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 12, attribution: osmAttrib});        

	var printer = L.easyPrint({
		tileLayer: osm,
		sizeModes: ['Current'],
		filename: 'myMap',
		exportOnly: true,
		hideControlContainer: true
	}).addTo(map);

	// new synthaxe, use array by function argument //
	if (typeof options.q_lat!=="undefined") { _q_lat = options.q_lat; } else { if (typeof q_lat!=="undefined") { _q_lat = q_lat; } else { _q_lat = 0; } }
	if (typeof options.q_lng!=="undefined") { _q_lng = options.q_lng; } else { if (typeof q_lng!=="undefined") { _q_lng = q_lng; } else { _q_lng = 0; } }
	if (typeof options.q_zoom!=="undefined") { _q_zoom = options.q_zoom; } else { if (typeof q_zoom!=="undefined") { _q_zoom = q_zoom; } else { _q_zoom = 1; } }
	if (typeof options.url_qso!=="undefined") { _url_qso = options.url_qso; } else { if (typeof qso_loc!=="undefined") { _url_qso = qso_loc; } else { _url_qso = ''; console.log('[ERROR] url with qso location not defined.'); } }

    // start the map in center of word, it nothing is defined
    map.setView(new L.LatLng(_q_lat, _q_lng), _q_zoom);
	map.addLayer(osm);
	//map.on('moveend', onMapMove); // all data load directecty, without interest in recharging during a movement
	var layerControl = new L.Control.Layers(null, { 'Gridsquares': maidenhead = L.maidenhead() }).addTo(map);
	if(ShowGrid == "Yes") { maidenhead.addTo(map); }

	// set data //
	askForPlots(_url_qso, options);
	if ((typeof options.home!=="undefined")&&(typeof options.home.show!=="undefined")&&(options.home.show==true)) {
		if (typeof options.home.lat==="undefined") { options.home.lat = _q_lat; }
		if (typeof options.home.lng==="undefined") { options.home.lng = _q_lng; }
		if (typeof options.home.icon==="undefined") { options.home.icon = homeIcon; }
		if (typeof options.home.label==="undefined") { options.home.label = ''; }
		if (typeof options.home.html==="undefined") { options.home.html = ''; }
		createPlots({'lat':options.home.lat,'lng':options.home.lng,'icon':options.home.icon,'label':options.home.label,'html':options.home.html});
	}
}

function askForPlots(_url_qso, options={}) {
	removeMarkers();
    $.ajax({
        url: _url_qso, type: 'GET', dataType: 'json',
        error: function() { console.log('[ERROR] ajax askForPlots() function return error.'); },
        success: function(plotjson) {
        	if ((typeof plotjson['markers'] !== "undefined")&&(plotjson['markers'].length>0)) {
				for (i=0;i<plotjson['markers'].length;i++) { createPlots(plotjson['markers'][i]); }
        	}
        	if ((typeof plotjson['home']!=="undefined")&&(typeof plotjson['home'].show!=="undefined")&&(plotjson['home'].show==true)) {
				createPlots(plotjson['home']);
        	}        	
        }
    });
}

function createPlots(_plot) {
	var plotll = new L.LatLng(_plot.lat,_plot.lng, true);
	if (typeof _plot.icon==="undefined") { _plot.icon = redIcon; } //redIconImg
	eval('var plotmark = new L.Marker(plotll, {icon: '+_plot.icon+'})');
	plotmark.data=_plot;
	map.addLayer(plotmark);
	if ((typeof _plot.label!=="undefined")&&(typeof _plot.html!=="undefined")) {
		plotmark.bindPopup(((_plot.label!="")?("<h3>"+_plot.label+"</h3>"):"")+_plot.html);
	}
	plotlayers.push(plotmark);
}

function removeMarkers() {
	for (i=0;i<plotlayers.length;i++) {
		map.removeLayer(plotlayers[i]);
	}
	plotlayers=[];
}

/*
// deprecated //
function askForPlots(_url_qso) {
    // request the marker info with AJAX for the current bounds
    ajaxRequest.onreadystatechange = stateChanged;
    ajaxRequest.open('GET', _url_qso, true);
    ajaxRequest.send(null);
}

function stateChanged() {
	// if AJAX returned a list of markers, add them to the map
	if (ajaxRequest.readyState==4) {
		//use the info here that was returned
		if (ajaxRequest.status==200) {
            plotlist=eval("(" + ajaxRequest.responseText + ")");
            plotlist = plotlist['markers'];
			removeMarkers();
			for (i=0;i<plotlist.length;i++) {
				var plotll = new L.LatLng(plotlist[i].lat,plotlist[i].lng, true);
				var plotmark = new L.Marker(plotll, {icon: greenIcon});
				plotmark.data=plotlist[i];
				map.addLayer(plotmark);
				plotmark.bindPopup("<h3>"+plotlist[i].label+"</h3>"+plotlist[i].html);
				plotlayers.push(plotmark);
			}
		}
	}
} */

/*function onMapMove(e) {
    askForPlots();
}*/
