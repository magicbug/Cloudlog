

var map;
var ajaxRequest;
var plotlist;
var plotlayers=[];

function initmap() {
    // set up AJAX request
    ajaxRequest=getXmlHttpObject();
    if (ajaxRequest==null) {
        alert ("This browser does not support HTTP Request");
        return;
    }
    
    // set up the map
    map = new L.Map('map');

    // create the tile layer with correct attribution
    var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    var osm = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 9, attribution: osmAttrib});        

    // start the map in South-East England
    map.setView(new L.LatLng(q_lat, q_lng), q_zoom);
    map.addLayer(osm);

	askForPlots();
	map.on('moveend', onMapMove);
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
				var plotll = new L.LatLng(plotlist[i].lat,plotlist[i].lng, true);
				var plotmark = new L.Marker(plotll);
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
