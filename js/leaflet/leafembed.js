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
    
  // create the tile layer with correct attribution

  var osmUrl            = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
  var osmAttrib         = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';

  var osmBWUrl          = 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png';
  var osmBWAttrib       = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';

  var esriImageryUrl    = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
  var esriImageryAttrib = 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

  var osm = new L.tileLayer(osmUrl, {
    name: "osm",
    minZoom: 1, 
    maxZoom: 9, 
    attribution: osmAttrib
  });        
    
  var osmBW = new L.tileLayer(osmBWUrl, {
    name: "osmBW",
    minZoom: 1, 
    maxZoom: 9, 
    attribution: osmBWAttrib
  });        

  var esriImagery = new L.tileLayer(esriImageryUrl, {
    name: "esriImagery",
    minZoom: 1, 
    maxZoom: 9, 
    attribution: esriImageryAttrib
  });        

  // start the map in South-East England
  map = new L.map('map', {
    center: [q_lat, q_lng],
    zoom: q_zoom,
    fullscreenControl: true,
    layers: [osm]
  });

  var baseMaps= {
    "Colour": osm,
    "Grayscale": osmBW,
    "Satellite": esriImagery
  };

  L.control.layers(baseMaps).addTo(map);

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
