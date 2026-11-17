var rdaMap = null;
var rdaGeojsonLayer = null;
var rdaInfo = null;
var rdaOblastStats = {};
var rdaShapes = null;
var rdaDistrictTable = null;
var rdaOblastFilter = '';

$(function () {
	rdaDistrictTable = $('#rdadistricttable').DataTable({
		data: rdaDistricts,
		deferRender: true,
		pageLength: 25,
		order: [[0, 'asc']],
		scrollX: true,
		language: {
			url: getDataTablesLanguageUrl(),
		},
		dom: 'Bfrtip',
		buttons: [{ extend: 'csv' }],
		columns: [
			{
				render: function (data, type, row) {
					if (type !== 'display' || row[3] === 'Not worked') {
						return data;
					}
					return '<a href="#" class="rda-district-link" data-code="' + data + '">' + data + '</a>';
				}
			},
			{},
			{},
			{
				render: function (data, type) {
					if (type !== 'display') {
						return data;
					}
					var cls = data === 'Confirmed' ? 'text-bg-success' : (data === 'Worked' ? 'text-bg-warning' : 'text-bg-secondary');
					return '<span class="badge ' + cls + '">' + data + '</span>';
				}
			},
			{},
			{},
			{
				render: function (data, type, row) {
					return type === 'sort' ? row[7] : data;
				}
			}
		]
	});

	$('#rdaoblasttable').DataTable({
		paging: false,
		info: false,
		order: [[0, 'asc']],
		language: {
			url: getDataTablesLanguageUrl(),
		},
		dom: 'Bfrtip',
		buttons: [{ extend: 'csv' }]
	});

	if (isDarkModeTheme()) {
		$('[class*="buttons"]').css("color", "white");
	}

	$('#rdaStatusFilter').on('change', function () {
		var value = $(this).val();
		rdaDistrictTable.column(3).search(value ? '^(' + value + ')$' : '', true, false).draw();
	});

	$('#rdadistricttable').on('click', '.rda-district-link', function (e) {
		e.preventDefault();
		displayContacts($(this).data('code'), rdaFilters.band, rdaFilters.mode, 'RDA', '');
	});

	$('#rdaoblasttable').on('click', '.rda-oblast-link', function (e) {
		e.preventDefault();
		rdaShowOblast($(this).data('oblast'));
	});

	$('#rdaOblastFilter').on('click', function () {
		rdaShowOblast('');
	});

	$('#map-tab').on('shown.bs.tab', function () {
		rdaLoadMap();
	});

	$('#districts-tab').on('shown.bs.tab', function () {
		rdaDistrictTable.columns.adjust();
	});
});

function rdaShowOblast(code) {
	rdaOblastFilter = code;
	rdaDistrictTable.column(2).search(code ? '^' + code + '$' : '', true, false).draw();
	if (code) {
		$('#rdaOblastFilter').html(code + ' &times;').attr('title', 'Show every oblast').show();
	} else {
		$('#rdaOblastFilter').hide();
	}
	bootstrap.Tab.getOrCreateInstance(document.getElementById('districts-tab')).show();
	document.getElementById('rdaTabs').scrollIntoView({ block: 'start' });
}

function rdaLoadMap() {
	var requests = [
		$.ajax({
			url: base_url + 'index.php/awards/rda_map',
			type: 'post',
			dataType: 'json',
			data: {
				band: rdaFilters.band,
				mode: rdaFilters.mode,
				qsl: rdaFilters.qsl,
				lotw: rdaFilters.lotw,
				eqsl: rdaFilters.eqsl,
			}
		})
	];
	if (rdaShapes === null) {
		requests.push($.getJSON(base_url + 'assets/json/rda_oblasts.geojson'));
	}

	$.when.apply($, requests).done(function (statsResult, shapesResult) {
		rdaOblastStats = statsResult[0];
		if (shapesResult) {
			rdaShapes = shapesResult[0];
		}
		rdaDrawMap();
	});
}

function rdaStatus(code) {
	var s = rdaOblastStats[code];
	if (!s || s.worked === 0) {
		return 'none';
	}
	if (s.confirmed >= s.total) {
		return 'confirmed';
	}
	return 'worked';
}

function rdaStyle(feature) {
	var code = feature.properties.rda;
	var s = rdaOblastStats[code];
	var status = rdaStatus(code);
	var color = status === 'confirmed' ? 'green' : (status === 'worked' ? 'orange' : 'red');
	var share = s && s.total ? s.worked / s.total : 0;
	return {
		fillColor: color,
		weight: 1,
		opacity: 1,
		color: 'white',
		fillOpacity: status === 'worked' ? 0.3 + 0.5 * share : 0.6
	};
}

function rdaDrawMap() {
	if (rdaMap === null) {
		rdaMap = L.map('rdamap', {
			fullscreenControl: true,
			fullscreenControlOptions: {
				position: 'topleft'
			},
			worldCopyJump: false
		});

		L.tileLayer(tileUrl, {
			attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors | Boundaries: <a href="https://www.naturalearthdata.com/">Natural Earth</a>',
			maxZoom: 18
		}).addTo(rdaMap);

		rdaInfo = L.control();
		rdaInfo.onAdd = function () {
			this._div = L.DomUtil.create('div', 'rda-info');
			this.update();
			return this._div;
		};
		rdaInfo.update = function (props) {
			if (!props) {
				this._div.innerHTML = '<h4>Oblast</h4>Hover over an oblast';
				return;
			}
			var s = rdaOblastStats[props.rda] || { worked: 0, confirmed: 0, total: 0 };
			this._div.innerHTML = '<h4>' + props.rda + ' - ' + $('<span>').text(props.name).html() + '</h4>' +
				'Worked ' + s.worked + ' of ' + s.total + '<br>Confirmed ' + s.confirmed;
		};
		rdaInfo.addTo(rdaMap);

		var legend = L.control({ position: 'topright' });
		legend.onAdd = function () {
			this._div = L.DomUtil.create('div', 'legend');
			return this._div;
		};
		legend.addTo(rdaMap);
		rdaMap.rdaLegend = legend;

		rdaMap.setView([62, 95], 3);
	}

	if (rdaGeojsonLayer !== null) {
		rdaMap.removeLayer(rdaGeojsonLayer);
	}

	var counts = { confirmed: 0, worked: 0, none: 0 };
	rdaShapes.features.forEach(function (f) {
		counts[rdaStatus(f.properties.rda)]++;
	});
	rdaMap.rdaLegend._div.innerHTML = '<h4>' + lang_general_word_colors + '</h4>' +
		"<i style='background: green'></i><span>Every district confirmed (" + counts.confirmed + ")</span><br>" +
		"<i style='background: orange'></i><span>Some districts worked (" + counts.worked + ")</span><br>" +
		"<i style='background: red'></i><span>" + lang_general_word_not_worked + " (" + counts.none + ")</span><br>";

	rdaGeojsonLayer = L.geoJson(rdaShapes, {
		style: rdaStyle,
		onEachFeature: function (feature, layer) {
			layer.on({
				mouseover: function (e) {
					e.target.setStyle({ weight: 3, color: 'white', fillOpacity: 0.85 });
					e.target.bringToFront();
					rdaInfo.update(feature.properties);
				},
				mouseout: function (e) {
					rdaGeojsonLayer.resetStyle(e.target);
					rdaInfo.update();
				},
				click: function () {
					rdaShowOblast(feature.properties.rda);
				}
			});
		}
	}).addTo(rdaMap);

	rdaMap.invalidateSize();
}
