var map;
var plotlayers=[];
var iconsList={'qso':{'color':'#FF0000','icon':'fas fa-dot-circle'}};

var stationIcon = L.divIcon({className:'cspot_station'});
var qsoIcon = L.divIcon({className:'cspot_qso'}); //default (fas fa-dot-circle red)
var qsoconfirmIcon = L.divIcon({className:'cspot_qsoconfirm'});
var redIconImg = L.icon({ iconUrl: icon_dot_url, iconSize: [10, 10] }); // old //

var osmUrl = $('#leafembed').attr("tileUrl");

// function to initialise map (can called alone, without plot) //
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
	options.map_id = '#'+MapTag;

    // start the map in center of word, it nothing is defined
    map.setView(new L.LatLng(_q_lat, _q_lng), _q_zoom);
	map.addLayer(osm);
	//map.on('moveend', onMapMove); // all data load directecty, without interest in recharging during a movement
	var layerControl = new L.Control.Layers(null, { 'Gridsquares': maidenhead = L.maidenhead() }).addTo(map);
	if(ShowGrid == "Yes") { maidenhead.addTo(map); }

	if ((typeof options.initmap_only=="undefined") || (options.initmap_only!=true))  { initplot(_url_qso, options); }
}

// function to initialise plot on map - Don't forget the "map_id" arg on options, if call alone //
function initplot(_url_qso, options={}) {
	//console.log(_url_qso);
	// get map custon infos //
	if (_url_qso != '') {
		var _visitor = (typeof visitor==="undefined")?false:visitor;
		if (_visitor) {
			askForPlots(_url_qso, options);
		} else {
			$.ajax({
				url: base_url+'index.php/user_options/get_map_custom', type: 'GET', dataType: 'json',
				error: function() { askForPlots(_url_qso, options); console.log('[ERROR] ajax get_map_custom() function return error.'); },
				success: function(json_mapinfo) {
					if (typeof json_mapinfo.qso !== "undefined") { iconsList = json_mapinfo; }
					if(json_mapinfo.gridsquare_show == "1") { maidenhead.addTo(map); }
					askForPlots(_url_qso, options);
				}
			});
		}
	}
}

function askForPlots(_url_qso, options={}) {
	removeMarkers();
	if (typeof options.dataPost !== "undefined") { _dataPost = options.dataPost; } else { _dataPost = {}; }
    $.ajax({
        url: _url_qso, type: 'POST', dataType: 'json', data: _dataPost,
        error: function() { console.log('[ERROR] ajax askForPlots() function return error.'); },
        success: function(plotjson) {
        	if ((typeof plotjson['markers'] !== "undefined")&&(plotjson['markers'].length>0)) {
				for (i=0;i<plotjson['markers'].length;i++) { createPlots(plotjson['markers'][i]); }
        	}
        	if ((typeof plotjson['station']!=="undefined")&&(plotjson['station'].icon!="0")) {
        		createPlots(plotjson['station']);
        	}
        	$.each(iconsList, function(icon, data){
        		$(options.map_id+' .cspot_'+icon).addClass(data.icon).css("color",data.color);
        	});
        }
    });
}

function createPlots(_plot) {
	var plotll = new L.LatLng(_plot.lat,_plot.lng, true);
	if (typeof _plot.icon==="undefined") { _plot.icon = 'qsoIcon'; }
	if ((typeof iconsList.qsoconfirm!=="undefined")&&(typeof iconsList.qsoconfirm.icon!=="undefined")&&(iconsList.qsoconfirm.icon!="0")&&(typeof _plot.confirmed!=="undefined")&&(_plot.confirmed=='Y')) { _plot.icon = 'qsoconfirmIcon'; }
	eval('var plotmark = new L.Marker(plotll, {icon: '+_plot.icon+' })');
	plotmark.data=_plot;
	map.addLayer(plotmark);
	if ((typeof _plot.label!=="undefined")&&(typeof _plot.html!=="undefined")) {
		_plot.label = (_plot.label!="")?("<h3>"+ _plot.flag  +  _plot.label+"</h3>"):"";
		if ((_plot.label+_plot.html)!="") { plotmark.bindPopup(_plot.label+_plot.html); }
	}
	plotlayers.push(plotmark);
}

function removeMarkers() {
	for (i=0;i<plotlayers.length;i++) {
		map.removeLayer(plotlayers[i]);
	}
	plotlayers=[];
}