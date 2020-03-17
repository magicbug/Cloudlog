var map;
var ajaxRequest;
var plotlist;
var plotlayers=[];
var amicon;
var cwicon;
var fmicon;
var ft4icon;
var ft8icon;
var jt9icon;
var jt65icon;
var psk31icon;
var ssbicon;
var digicon;
var iconbaseurl = baseURL + 'assets/js/leaflet/images/'

function initmap(ShowGrid = 'No') {
    // set up AJAX request
    ajaxRequest=getXmlHttpObject();
    if (ajaxRequest==null) {
        alert ("This browser does not support HTTP Request");
        return;
    }
    
    // set up the map
    map = new L.Map('map');
    amicon = L.icon({
        iconUrl: iconbaseurl + 'icon-am.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    cwicon = L.icon({
        iconUrl: iconbaseurl + 'icon-cw.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    fmicon = L.icon({
        iconUrl: iconbaseurl + 'icon-fm.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    ft4icon = L.icon({
        iconUrl: iconbaseurl + 'icon-ft4.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    ft8icon = L.icon({
        iconUrl: iconbaseurl + 'icon-ft8.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    jt9icon = L.icon({
        iconUrl: iconbaseurl + 'icon-jt9.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    jt65icon = L.icon({
        iconUrl: iconbaseurl + 'icon-jt65.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    psk31icon = L.icon({
        iconUrl: iconbaseurl + 'icon-psk31.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });
    ssbicon = L.icon({
        iconUrl: iconbaseurl + 'icon-ssb.png',
        iconSize: [25,41],
        iconAnchor: [12,40],
        popupAnchor: [0,-41],
        shadowUrl: iconbaseurl + 'marker-shadow.png',
        shadowSize: [41,41]
    });

    // create the tile layer with correct attribution
    var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    var osm = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 9, attribution: osmAttrib});        

    // start the map in South-East England
    map.setView(new L.LatLng(q_lat, q_lng), q_zoom);
	
	map.addLayer(osm);
	askForPlots();

	map.on('moveend', onMapMove);

	if(ShowGrid == "Yes") {
		var maidenhead = L.maidenhead().addTo(map);
	}

	var layerControl = new L.Control.Layers(null, {
    'Gridsquares': maidenhead = L.maidenhead()
	}).addTo(map);
}

function getXmlHttpObject() {
	if (window.XMLHttpRequest) { return new XMLHttpRequest(); }
	if (window.ActiveXObject)  { return new ActiveXObject("Microsoft.XMLHTTP"); }
	return null;
}

function askForPlots() {
    // request the marker info with AJAX for the current bounds
    ajaxRequest.onreadystatechange = stateChanged;
    ajaxRequest.open('GET', qso_loc, true);
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
				var icn = plotlist[i]["html"].split(" ")[8];
				var plotll = new L.LatLng(plotlist[i].lat,plotlist[i].lng, true);
				if (icn == 'CW'){
					var plotmark = new L.Marker(plotll, {icon: cwicon});
				} else if (icn == 'SSB'){
					var plotmark = new L.Marker(plotll, {icon: ssbicon});
				} else if (icn == 'FT4'){
 					var plotmark = new L.Marker(plotll, {icon: ft4icon});
				} else if (icn == 'FT8'){
 					var plotmark = new L.Marker(plotll, {icon: ft8icon});
				} else if (icn == 'AM'){
 					var plotmark = new L.Marker(plotll, {icon: amicon});
				} else if (icn == 'FM'){
 					var plotmark = new L.Marker(plotll, {icon: fmicon});
				} else if (icn == 'JT9'){
 					var plotmark = new L.Marker(plotll, {icon: jt9icon});
				} else if (icn == 'JT65'){
 					var plotmark = new L.Marker(plotll, {icon: jt65icon});
				} else if (icn == 'PSK31'){
 					var plotmark = new L.Marker(plotll, {icon: psk31icon});
				} else {
					var plotmark = new L.Marker(plotll);
				}
				plotmark.data=plotlist[i];
				map.addLayer(plotmark);
				plotmark.bindPopup("<h3>"+plotlist[i].label+"</h3>"+plotlist[i].html);
				plotlayers.push(plotmark);
			}
		}
	}
}

function removeMarkers() {
	for (i=0;i<plotlayers.length;i++) {
		map.removeLayer(plotlayers[i]);
	}
	plotlayers=[];
}

function onMapMove(e) {
    askForPlots();
}
