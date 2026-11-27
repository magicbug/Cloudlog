(function () {
	'use strict';

	// Approximate geographic coordinates [lat, lng] for all 73 current WAE entities
	var WAE_COORDS = {
		'1A':   [41.905, 12.480],   // S.M.O.M. (Rome)
		'3A':   [43.738, 7.424],    // Monaco
		'4O':   [42.708, 19.374],   // Montenegro
		'4U1I': [46.222, 6.140],    // UN Geneva
		'4U1V': [48.234, 16.416],   // UN Vienna
		'9A':   [45.100, 15.200],   // Croatia
		'9H':   [35.937, 14.375],   // Malta
		'C3':   [42.546, 1.601],    // Andorra
		'CT':   [39.557, -7.853],   // Portugal
		'CU':   [38.722, -27.220],  // Azores
		'DL':   [51.165, 10.451],   // Germany
		'E7':   [43.915, 17.679],   // Bosnia-Herzegovina
		'EA':   [40.463, -3.749],   // Spain
		'EA6':  [39.695, 3.017],    // Balearic Islands
		'EI':   [53.412, -8.243],   // Ireland
		'ER':   [47.411, 28.369],   // Moldova
		'ES':   [58.595, 25.013],   // Estonia
		'EU':   [53.709, 27.953],   // Belarus
		'F':    [46.227, 2.213],    // France
		'G':    [52.355, -1.174],   // England
		'GD':   [54.236, -4.548],   // Isle of Man
		'GI':   [54.787, -6.492],   // Northern Ireland
		'GJ':   [49.214, -2.131],   // Jersey
		'GM':   [56.490, -4.202],   // Scotland
		'GM/S': [60.347, -1.236],   // Shetland Islands
		'GU':   [49.465, -2.585],   // Guernsey
		'GW':   [52.130, -3.783],   // Wales
		'HA':   [47.162, 19.503],   // Hungary
		'HB':   [46.818, 8.227],    // Switzerland
		'HB0':  [47.166, 9.555],    // Liechtenstein
		'HV':   [41.902, 12.453],   // Vatican
		'I':    [42.833, 12.833],   // Italy
		'IS':   [40.120, 9.012],    // Sardinia
		'IT':   [37.600, 14.015],   // Sicily
		'JW':   [78.223, 15.626],   // Spitsbergen
		'JW/B': [74.450, 19.100],   // Bear Island
		'JX':   [71.031, -8.292],   // Jan Mayen
		'LA':   [60.472, 8.468],    // Norway
		'LX':   [49.815, 6.129],    // Luxembourg
		'LY':   [55.169, 23.881],   // Lithuania
		'LZ':   [42.733, 25.485],   // Bulgaria
		'OE':   [47.516, 14.550],   // Austria
		'OH':   [61.924, 25.748],   // Finland
		'OH0':  [60.178, 19.915],   // Aland Islands
		'OJ0':  [60.300, 19.131],   // Market Reef
		'OK':   [49.817, 15.473],   // Czech Republic
		'OM':   [48.669, 19.699],   // Slovakia
		'ON':   [50.503, 4.469],    // Belgium
		'OY':   [61.892, -6.911],   // Faroe Islands
		'OZ':   [56.263, 9.501],    // Denmark
		'PA':   [52.132, 5.291],    // Netherlands
		'R1FJ': [80.583, 54.783],   // Franz Josef Land
		'S5':   [46.151, 14.995],   // Slovenia
		'SM':   [60.128, 18.643],   // Sweden
		'SP':   [51.919, 19.145],   // Poland
		'SV':   [39.074, 21.824],   // Greece
		'SV/A': [40.264, 24.215],   // Mount Athos
		'SV5':  [36.434, 28.217],   // Dodecanese
		'SV9':  [35.240, 24.809],   // Crete
		'T7':   [43.942, 12.457],   // San Marino
		'TA1':  [41.200, 27.900],   // Turkey (European)
		'TF':   [64.963, -19.020],  // Iceland
		'TK':   [42.039, 9.012],    // Corsica
		'UA':   [55.755, 37.617],   // European Russia
		'UA2':  [54.710, 20.452],   // Kaliningrad
		'UR':   [48.379, 31.165],   // Ukraine
		'YL':   [56.879, 24.603],   // Latvia
		'YO':   [45.943, 24.966],   // Romania
		'YU':   [44.016, 21.005],   // Serbia
		'Z3':   [41.608, 21.745],   // North Macedonia
		'Z6':   [42.602, 20.902],   // Kosovo
		'ZA':   [41.153, 20.168],   // Albania
		'ZB':   [36.140, -5.353]    // Gibraltar
	};

	var waeMap = null;
	var markersLayer = null;
	var waeInfo = null;
	var waeLegend = null;
	var activeMapFilter = 'all';

	function escapeHtml(str) {
		return String(str || '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function getEntityStatus(row) {
		if (row.confirmed_points > 0) return 'confirmed';
		if (row.worked_points > 0) return 'worked';
		return 'none';
	}

	function renderInfo(row) {
		if (!waeInfo || !waeInfo._div) return;
		if (!row) {
			waeInfo._div.innerHTML =
				'<div class="rac-info-title">Worked All Europe</div>' +
				'<div class="rac-info-sub">Hover over any entity marker</div>';
			return;
		}
		var st = getEntityStatus(row);
		var stLabel = st === 'confirmed' ? 'Confirmed' : (st === 'worked' ? 'Worked (unconfirmed)' : 'Not worked');
		var badgeCls = st === 'confirmed' ? 'is-conf' : (st === 'worked' ? 'is-work' : 'is-none');

		var html = '<div class="rac-info-title">' + escapeHtml(row.name) + ' <span class="rac-info-code">' + escapeHtml(row.code) + '</span></div>';
		html += '<div class="mb-1"><span class="rac-info-badge ' + badgeCls + '">' + stLabel + '</span></div>';
		html += '<div class="rac-info-stat">Band Points: <strong>' + row.confirmed_points + ' / 5</strong> confirmed (' + row.worked_points + ' worked)</div>';
		if (row.confirmed_bands && row.confirmed_bands.length) {
			html += '<div class="rac-info-stat">Confirmed: ' + escapeHtml(row.confirmed_bands.join(', ')) + '</div>';
		} else if (row.worked_bands && row.worked_bands.length) {
			html += '<div class="rac-info-stat">Worked: ' + escapeHtml(row.worked_bands.join(', ')) + '</div>';
		}
		waeInfo._div.innerHTML = html;
	}

	function renderMarkers() {
		if (!waeMap || typeof waeMapRows === 'undefined') return;
		if (markersLayer) {
			waeMap.removeLayer(markersLayer);
		}
		markersLayer = L.layerGroup();

		var counts = { confirmed: 0, worked: 0, none: 0, fiveBand: 0, total: 0 };

		waeMapRows.forEach(function (row) {
			if (row.deleted) return;
			var coords = WAE_COORDS[row.code];
			if (!coords) return;

			var st = getEntityStatus(row);
			counts.total++;
			counts[st]++;
			if (row.confirmed_points >= 5) counts.fiveBand++;

			if (activeMapFilter === 'confirmed' && st !== 'confirmed') return;
			if (activeMapFilter === 'worked' && st !== 'worked') return;
			if (activeMapFilter === 'none' && st !== 'none') return;
			if (activeMapFilter === 'five' && row.confirmed_points < 5) return;

			var color = st === 'confirmed' ? '#198754' : (st === 'worked' ? '#f59e0b' : '#dc3545');
			var radius = st === 'confirmed' ? (6 + Math.min(4, row.confirmed_points)) : (st === 'worked' ? 6.5 : 5);
			var fillOpacity = st === 'none' ? 0.45 : 0.85;

			var marker = L.circleMarker(coords, {
				radius: radius,
				fillColor: color,
				color: '#ffffff',
				weight: 1.5,
				opacity: 0.95,
				fillOpacity: fillOpacity
			});

			marker.bindTooltip('<strong>' + escapeHtml(row.code) + '</strong> ' + escapeHtml(row.name), {
				direction: 'top',
				offset: [0, -6]
			});

			marker.on('mouseover', function () {
				this.setStyle({ weight: 2.8, fillOpacity: 1 });
				renderInfo(row);
			});
			marker.on('mouseout', function () {
				this.setStyle({ weight: 1.5, fillOpacity: fillOpacity });
				renderInfo(null);
			});
			marker.on('click', function () {
				renderInfo(row);
				var searchEl = document.getElementById('waeSearchInput');
				if (searchEl) {
					searchEl.value = row.code;
					searchEl.dispatchEvent(new Event('input'));
				}
			});

			marker.addTo(markersLayer);
		});

		markersLayer.addTo(waeMap);

		if (waeLegend && waeLegend._div) {
			waeLegend._div.innerHTML =
				'<div class="rac-legend-title">WAE Entities (' + (counts.confirmed + counts.worked) + ' / ' + counts.total + ')</div>' +
				'<div class="rac-legend-item"><span class="rac-swatch" style="background:#198754;border-radius:50%"></span>Confirmed (' + counts.confirmed + ')</div>' +
				'<div class="rac-legend-item"><span class="rac-swatch" style="background:#f59e0b;border-radius:50%"></span>Worked (' + counts.worked + ')</div>' +
				'<div class="rac-legend-item"><span class="rac-swatch" style="background:rgba(220,53,69,0.65);border-radius:50%"></span>Not worked (' + counts.none + ')</div>';
		}

		renderInfo(null);
	}

	function initWaeMap() {
		var el = document.getElementById('waemap');
		if (!el || typeof L === 'undefined') return;

		var tiles = (typeof waeTileUrl !== 'undefined' && waeTileUrl)
			? waeTileUrl
			: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

		waeMap = L.map('waemap', {
			fullscreenControl: true,
			fullscreenControlOptions: { position: 'topleft' },
			worldCopyJump: false,
			minZoom: 2
		});

		L.tileLayer(tiles, {
			attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
			maxZoom: 18
		}).addTo(waeMap);

		waeInfo = L.control({ position: 'topright' });
		waeInfo.onAdd = function () {
			this._div = L.DomUtil.create('div', 'rac-info');
			return this._div;
		};
		waeInfo.addTo(waeMap);

		waeLegend = L.control({ position: 'bottomright' });
		waeLegend.onAdd = function () {
			this._div = L.DomUtil.create('div', 'rac-legend');
			return this._div;
		};
		waeLegend.addTo(waeMap);

		waeMap.setView([52.5, 13.0], 4);
		renderMarkers();

		document.querySelectorAll('#waeMapFilterPills .rac-mode-pill').forEach(function (btn) {
			btn.addEventListener('click', function () {
				activeMapFilter = this.getAttribute('data-wae-map') || 'all';
				document.querySelectorAll('#waeMapFilterPills .rac-mode-pill').forEach(function (b) {
					b.classList.toggle('is-active', b === btn);
				});
				renderMarkers();
			});
		});
	}

	function initWaeTableFilters() {
		var pills = document.querySelectorAll('#waeTableFilterPills .rac-mode-pill');
		var searchInput = document.getElementById('waeSearchInput');
		var rows = document.querySelectorAll('#waeTableBody tr[data-wae-status]');
		var emptyRow = document.getElementById('waeEmptyRow');
		var countEl = document.getElementById('waeShownCount');
		if (!rows.length) return;

		var currentStatus = 'all';

		function applyFilter() {
			var q = searchInput ? searchInput.value.trim().toLowerCase() : '';
			var shown = 0;
			rows.forEach(function (tr) {
				var st = tr.getAttribute('data-wae-status');
				var hay = tr.getAttribute('data-wae-search') || '';
				var matchStatus = (currentStatus === 'all') || (st === currentStatus);
				var matchSearch = (!q) || (hay.indexOf(q) !== -1);
				var visible = matchStatus && matchSearch;
				tr.style.display = visible ? '' : 'none';
				if (visible) shown++;
			});
			if (emptyRow) emptyRow.style.display = (shown === 0) ? '' : 'none';
			if (countEl) countEl.textContent = shown + ' shown';
		}

		pills.forEach(function (btn) {
			btn.addEventListener('click', function () {
				currentStatus = this.getAttribute('data-wae-filter') || 'all';
				pills.forEach(function (p) { p.classList.toggle('is-active', p === btn); });
				applyFilter();
			});
		});

		if (searchInput) {
			searchInput.addEventListener('input', applyFilter);
		}
	}

	$(document).ready(function () {
		initWaeMap();
		initWaeTableFilters();
	});
})();
