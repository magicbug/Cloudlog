var countiesMap = null;
var countiesGeoLayer = null;
var countiesInfo = null;
var countiesMapData = null;
var countiesShapesCache = {};
var countiesViewState = null;
var countiesBackControl = null;

$(function () {
	$('#counties-map-tab').on('shown.bs.tab', function () {
		countiesLoadMap();
	});

	$('#counties-table-tab').on('shown.bs.tab', function () {
		if ($.fn.dataTable && $.fn.dataTable.isDataTable('.countiestable')) {
			$('.countiestable').DataTable().columns.adjust();
		}
	});
});

function countiesLoadMap() {
	$.ajax({
		url: base_url + 'index.php/awards/counties_map',
		type: 'post',
		dataType: 'json'
	}).done(function (data) {
		countiesMapData = data || { states: {}, counties: {} };
		if (countiesViewState) {
			countiesShowState(countiesViewState);
		} else {
			countiesShowNational();
		}
	});
}

function countiesEnsureMap() {
	if (countiesMap !== null) {
		return;
	}

	countiesMap = L.map('countiesmap', {
		fullscreenControl: true,
		fullscreenControlOptions: {
			position: 'topleft'
		},
		worldCopyJump: false
	});

	L.tileLayer(
		typeof tileUrl !== 'undefined' && tileUrl
			? tileUrl
			: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
		{
			attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
			maxZoom: 12
		}
	).addTo(countiesMap);

	countiesInfo = L.control();
	countiesInfo.onAdd = function () {
		this._div = L.DomUtil.create('div', 'counties-map-info');
		this.update();
		return this._div;
	};
	countiesInfo.update = function (html) {
		this._div.innerHTML = html || '<h4>USA-CA</h4>Hover a state or county';
	};
	countiesInfo.addTo(countiesMap);

	var legend = L.control({ position: 'bottomright' });
	legend.onAdd = function () {
		this._div = L.DomUtil.create('div', 'legend');
		return this._div;
	};
	legend.addTo(countiesMap);
	countiesMap.countiesLegend = legend;

	countiesBackControl = L.control({ position: 'topright' });
	countiesBackControl.onAdd = function () {
		var div = L.DomUtil.create('div', 'counties-map-back');
		div.style.display = 'none';
		var btn = L.DomUtil.create('button', 'btn btn-sm btn-secondary', div);
		btn.type = 'button';
		btn.textContent = 'All states';
		L.DomEvent.disableClickPropagation(div);
		L.DomEvent.on(btn, 'click', function (e) {
			L.DomEvent.stop(e);
			countiesShowNational();
		});
		this._div = div;
		return div;
	};
	countiesBackControl.addTo(countiesMap);

	countiesMap.setView([39.5, -98.35], 4);
}

function countiesStateStatus(state) {
	var s = countiesMapData && countiesMapData.states ? countiesMapData.states[state] : null;
	if (!s || !s.worked) {
		return 'none';
	}
	if (s.confirmed >= s.total && s.total > 0) {
		return 'confirmed';
	}
	return 'worked';
}

function countiesCountyStatus(state, county) {
	var key = state + '|' + county;
	var value = countiesMapData && countiesMapData.counties ? countiesMapData.counties[key] : null;
	if (value === 'C') {
		return 'confirmed';
	}
	if (value === 'W') {
		return 'worked';
	}
	return 'none';
}

function countiesFillColor(status) {
	if (status === 'confirmed') {
		return 'green';
	}
	if (status === 'worked') {
		return 'orange';
	}
	return 'red';
}

function countiesSetLegend(counts, mode) {
	var title = typeof lang_general_word_colors !== 'undefined' ? lang_general_word_colors : 'Colors';
	var notWorked = typeof lang_general_word_not_worked !== 'undefined' ? lang_general_word_not_worked : 'Not worked';
	var confirmedLabel = mode === 'state' ? 'Every county confirmed' : 'Confirmed';
	var workedLabel = mode === 'state' ? 'Some counties worked' : 'Worked not confirmed';
	countiesMap.countiesLegend._div.innerHTML =
		'<h4>' + title + '</h4>' +
		"<i style='background: green'></i><span>" + confirmedLabel + " (" + counts.confirmed + ")</span><br>" +
		"<i style='background: orange'></i><span>" + workedLabel + " (" + counts.worked + ")</span><br>" +
		"<i style='background: red'></i><span>" + notWorked + " (" + counts.none + ")</span>";
}

function countiesReplaceLayer(geojson, styleFn, onEach) {
	if (countiesGeoLayer !== null) {
		countiesMap.removeLayer(countiesGeoLayer);
	}
	countiesGeoLayer = L.geoJson(geojson, {
		style: styleFn,
		onEachFeature: onEach
	}).addTo(countiesMap);
	countiesMap.invalidateSize();
}

function countiesShowNational() {
	countiesEnsureMap();
	countiesViewState = null;
	if (countiesBackControl && countiesBackControl._div) {
		countiesBackControl._div.style.display = 'none';
	}
	countiesInfo.update('<h4>USA-CA</h4>Click a state to see its counties');

	var url = base_url + 'assets/json/counties/US-states.geojson';
	var request = countiesShapesCache.states
		? $.Deferred().resolve(countiesShapesCache.states).promise()
		: $.getJSON(url);

	request.done(function (shapes) {
		countiesShapesCache.states = shapes;
		var counts = { confirmed: 0, worked: 0, none: 0 };
		shapes.features.forEach(function (f) {
			counts[countiesStateStatus(f.properties.state)]++;
		});
		countiesSetLegend(counts, 'state');

		countiesReplaceLayer(shapes, function (feature) {
			var status = countiesStateStatus(feature.properties.state);
			var s = (countiesMapData.states && countiesMapData.states[feature.properties.state]) || { worked: 0, confirmed: 0, total: 0 };
			var share = s.total ? s.worked / s.total : 0;
			return {
				fillColor: countiesFillColor(status),
				weight: 1,
				opacity: 1,
				color: 'white',
				fillOpacity: status === 'worked' ? 0.35 + 0.45 * share : 0.55
			};
		}, function (feature, layer) {
			layer.on({
				mouseover: function (e) {
					e.target.setStyle({ weight: 2, color: '#333', fillOpacity: 0.85 });
					e.target.bringToFront();
					var state = feature.properties.state;
					var s = (countiesMapData.states && countiesMapData.states[state]) || { worked: 0, confirmed: 0, total: 0 };
					countiesInfo.update(
						'<h4>' + state + '</h4>Worked ' + s.worked + ' of ' + s.total +
						'<br>Confirmed ' + s.confirmed
					);
				},
				mouseout: function (e) {
					countiesGeoLayer.resetStyle(e.target);
					countiesInfo.update('<h4>USA-CA</h4>Click a state to see its counties');
				},
				click: function () {
					countiesShowState(feature.properties.state);
				}
			});
		});

		countiesMap.setView([39.5, -98.35], 4);
	});
}

function countiesShowState(state) {
	countiesEnsureMap();
	countiesViewState = state;
	if (countiesBackControl && countiesBackControl._div) {
		countiesBackControl._div.style.display = 'block';
	}
	countiesInfo.update('<h4>' + state + '</h4>Hover a county');

	var cached = countiesShapesCache[state];
	var request = cached
		? $.Deferred().resolve(cached).promise()
		: $.getJSON(base_url + 'assets/json/counties/US-' + state + '.geojson');

	request.done(function (shapes) {
		countiesShapesCache[state] = shapes;
		var counts = { confirmed: 0, worked: 0, none: 0 };
		shapes.features.forEach(function (f) {
			if (f.properties.kind === 'city') {
				return;
			}
			counts[countiesCountyStatus(state, f.properties.county)]++;
		});
		countiesSetLegend(counts, 'county');

		countiesReplaceLayer(shapes, function (feature) {
			var props = feature.properties;
			var status = props.kind === 'city' ? 'none' : countiesCountyStatus(state, props.county);
			return {
				fillColor: countiesFillColor(status),
				weight: 1,
				opacity: 1,
				color: 'white',
				fillOpacity: props.kind === 'city' ? 0.25 : 0.6
			};
		}, function (feature, layer) {
			layer.on({
				mouseover: function (e) {
					e.target.setStyle({ weight: 2, color: '#333', fillOpacity: 0.85 });
					e.target.bringToFront();
					var props = feature.properties;
					var label = props.county + (props.kind === 'city' ? ' (city)' : '');
					var status = props.kind === 'city' ? 'Independent city' : countiesCountyStatus(state, props.county);
					var statusText = status === 'confirmed' ? 'Confirmed' : (status === 'worked' ? 'Worked' : (status === 'Independent city' ? status : 'Not worked'));
					countiesInfo.update('<h4>' + state + ', ' + $('<span>').text(label).html() + '</h4>' + statusText);
				},
				mouseout: function (e) {
					countiesGeoLayer.resetStyle(e.target);
					countiesInfo.update('<h4>' + state + '</h4>Hover a county');
				},
				click: function () {
					var props = feature.properties;
					if (props.kind === 'city') {
						return;
					}
					var status = countiesCountyStatus(state, props.county);
					if (status === 'none' || typeof displayCountyContacts !== 'function') {
						return;
					}
					displayCountyContacts(state, props.county);
				}
			});
		});

		try {
			countiesMap.fitBounds(countiesGeoLayer.getBounds(), { padding: [20, 20] });
		} catch (err) {
			countiesMap.setView([39.5, -98.35], 5);
		}
	});
}
