(function () {
	'use strict';

	var racMap = null;
	var racGeoLayer = null;
	var racShapes = null;
	var racInfo = null;
	var racLegend = null;
	var activeMode = 'Mixed';

	function getStatus(code) {
		var item = (typeof racMapData !== 'undefined' && racMapData[code]) ? racMapData[code] : null;
		if (!item) return 'none';
		var m = item.modes && item.modes[activeMode] ? item.modes[activeMode] : '';
		if (m === 'C') return 'confirmed';
		if (m === 'W') return 'worked';
		return 'none';
	}

	function provinceStyle(feature) {
		var status = getStatus(feature.properties.code);
		if (status === 'confirmed') {
			return {
				fillColor: '#198754',
				weight: 1.4,
				opacity: 1,
				color: '#ffffff',
				fillOpacity: 0.68
			};
		}
		if (status === 'worked') {
			return {
				fillColor: '#f59e0b',
				weight: 1.4,
				opacity: 1,
				color: '#ffffff',
				fillOpacity: 0.62
			};
		}
		return {
			fillColor: '#dc3545',
			weight: 1,
			opacity: 0.85,
			color: '#ffffff',
			fillOpacity: 0.22
		};
	}

	function escapeHtml(str) {
		return String(str || '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function renderInfo(props) {
		if (!racInfo || !racInfo._div) return;
		if (!props) {
			racInfo._div.innerHTML =
				'<div class="rac-info-title">Canada (' + escapeHtml(activeMode) + ')</div>' +
				'<div class="rac-info-sub">Hover over a province or territory</div>';
			return;
		}
		var code = props.code;
		var item = (typeof racMapData !== 'undefined' && racMapData[code]) ? racMapData[code] : null;
		var status = getStatus(code);
		var statusLabel = status === 'confirmed' ? 'Confirmed' : (status === 'worked' ? 'Worked (unconfirmed)' : 'Not worked');
		var badgeCls = status === 'confirmed' ? 'is-conf' : (status === 'worked' ? 'is-work' : 'is-none');

		var html = '<div class="rac-info-title">' + escapeHtml(props.name) + ' <span class="rac-info-code">' + escapeHtml(code) + '</span></div>';
		html += '<div class="mb-1"><span class="rac-info-badge ' + badgeCls + '">' + statusLabel + '</span></div>';

		if (item && item.worked > 0) {
			html += '<div class="rac-info-stat">Eligible QSOs: <strong>' + item.confirmed + '</strong> confirmed / <strong>' + item.worked + '</strong> worked</div>';
			if (item.bands && item.bands.length) {
				html += '<div class="rac-info-stat">Bands: ' + escapeHtml(item.bands.join(', ')) + '</div>';
			}
		} else {
			html += '<div class="rac-info-sub">No eligible QSOs logged yet.</div>';
		}

		racInfo._div.innerHTML = html;
	}

	function updateLegend() {
		if (!racLegend || !racLegend._div || !racShapes) return;
		var counts = { confirmed: 0, worked: 0, none: 0 };
		racShapes.features.forEach(function (f) {
			counts[getStatus(f.properties.code)]++;
		});

		var lblConf = (typeof lang_general_word_confirmed !== 'undefined' && lang_general_word_confirmed) ? lang_general_word_confirmed : 'Confirmed';
		var lblWork = (typeof lang_general_word_worked_not_confirmed !== 'undefined' && lang_general_word_worked_not_confirmed) ? lang_general_word_worked_not_confirmed : 'Worked';
		var lblNone = (typeof lang_general_word_not_worked !== 'undefined' && lang_general_word_not_worked) ? lang_general_word_not_worked : 'Not worked';

		racLegend._div.innerHTML =
			'<div class="rac-legend-title">' + escapeHtml(activeMode) + ' (' + (counts.confirmed + counts.worked) + ' / 13)</div>' +
			'<div class="rac-legend-item"><span class="rac-swatch" style="background:#198754"></span>' + escapeHtml(lblConf) + ' (' + counts.confirmed + ')</div>' +
			'<div class="rac-legend-item"><span class="rac-swatch" style="background:#f59e0b"></span>' + escapeHtml(lblWork) + ' (' + counts.worked + ')</div>' +
			'<div class="rac-legend-item"><span class="rac-swatch" style="background:rgba(220,53,69,0.45)"></span>' + escapeHtml(lblNone) + ' (' + counts.none + ')</div>';
	}

	function drawLayer() {
		if (!racMap || !racShapes) return;
		if (racGeoLayer) {
			racMap.removeLayer(racGeoLayer);
		}
		racGeoLayer = L.geoJson(racShapes, {
			style: provinceStyle,
			onEachFeature: function (feature, layer) {
				layer.on({
					mouseover: function (e) {
						e.target.setStyle({ weight: 2.8, color: '#ffffff', fillOpacity: 0.85 });
						if (e.target.bringToFront) e.target.bringToFront();
						renderInfo(feature.properties);
					},
					mouseout: function (e) {
						racGeoLayer.resetStyle(e.target);
						renderInfo(null);
					}
				});
			}
		}).addTo(racMap);

		updateLegend();
		renderInfo(null);
	}

	function initRacMap() {
		var container = document.getElementById('racmap');
		if (!container || typeof L === 'undefined') return;

		var tiles = (typeof racTileUrl !== 'undefined' && racTileUrl)
			? racTileUrl
			: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

		racMap = L.map('racmap', {
			fullscreenControl: true,
			fullscreenControlOptions: { position: 'topleft' },
			worldCopyJump: false,
			minZoom: 2
		});

		L.tileLayer(tiles, {
			attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
			maxZoom: 18
		}).addTo(racMap);

		racInfo = L.control({ position: 'topright' });
		racInfo.onAdd = function () {
			this._div = L.DomUtil.create('div', 'rac-info');
			return this._div;
		};
		racInfo.addTo(racMap);

		racLegend = L.control({ position: 'bottomright' });
		racLegend.onAdd = function () {
			this._div = L.DomUtil.create('div', 'rac-legend');
			return this._div;
		};
		racLegend.addTo(racMap);

		racMap.setView([61.5, -96.5], 3);

		$.getJSON(base_url + 'assets/json/canada_provinces.geojson', function (geojson) {
			racShapes = geojson;
			drawLayer();
			try {
				racMap.fitBounds(racGeoLayer.getBounds(), { padding: [16, 16], maxZoom: 4 });
			} catch (e) {}
		});

		document.querySelectorAll('#racMapModePills .rac-mode-pill').forEach(function (btn) {
			btn.addEventListener('click', function () {
				activeMode = this.getAttribute('data-rac-mode') || 'Mixed';
				document.querySelectorAll('#racMapModePills .rac-mode-pill').forEach(function (b) {
					b.classList.toggle('is-active', b === btn);
				});
				drawLayer();
			});
		});
	}

	$(document).ready(initRacMap);
})();
