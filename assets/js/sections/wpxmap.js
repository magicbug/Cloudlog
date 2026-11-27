(function () {
	'use strict';

	var CONT_VIEWS = {
		'ALL': [[25, 10], 2],
		'EU':  [[51, 14], 4],
		'NA':  [[42, -98], 3],
		'SA':  [[-18, -60], 3],
		'AS':  [[34, 92], 3],
		'AF':  [[4, 20], 3],
		'OC':  [[-22, 138], 3]
	};

	var wpxMap = null;
	var markersLayer = null;
	var wpxInfo = null;
	var wpxLegend = null;
	var activeCont = 'ALL';

	function escapeHtml(str) {
		return String(str || '')
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function renderInfo(ent) {
		if (!wpxInfo || !wpxInfo._div) return;
		if (!ent) {
			wpxInfo._div.innerHTML =
				'<div class="rac-info-title">WPX World Prefix Map</div>' +
				'<div class="rac-info-sub">Hover over any country bubble</div>';
			return;
		}
		var isConf = ent.confirmed > 0;
		var badgeCls = isConf ? 'is-conf' : 'is-work';
		var badgeText = isConf ? 'Confirmed' : 'Worked only';
		var html = '<div class="rac-info-title">' + escapeHtml(ent.name) + ' <span class="rac-info-code">' + escapeHtml(ent.cont || '—') + '</span></div>';
		html += '<div class="mb-1"><span class="rac-info-badge ' + badgeCls + '">' + badgeText + '</span></div>';
		html += '<div class="rac-info-stat">Prefixes: <strong>' + ent.confirmed + '</strong> confirmed / <strong>' + ent.worked + '</strong> worked</div>';
		if (ent.sample && ent.sample.length) {
			var suffix = ent.worked > ent.sample.length ? ' +' + (ent.worked - ent.sample.length) : '';
			html += '<div class="rac-info-stat">Sample: ' + escapeHtml(ent.sample.join(', ')) + suffix + '</div>';
		}
		wpxInfo._div.innerHTML = html;
	}

	function renderMarkers() {
		if (!wpxMap || typeof wpxMapEntities === 'undefined') return;
		if (markersLayer) {
			wpxMap.removeLayer(markersLayer);
		}
		markersLayer = L.layerGroup();

		var totalEntities = 0;
		var confEntities = 0;
		var workOnlyEntities = 0;
		var totalPfx = 0;

		wpxMapEntities.forEach(function (ent) {
			if (activeCont !== 'ALL' && ent.cont !== activeCont) return;

			var isConf = ent.confirmed > 0;
			totalEntities++;
			totalPfx += ent.worked;
			if (isConf) confEntities++;
			else workOnlyEntities++;

			var radius = Math.min(18, Math.max(5, 4.5 + Math.log(ent.worked + 1) * 3.1));
			var color = isConf ? '#198754' : '#f59e0b';

			var marker = L.circleMarker([ent.lat, ent.lng], {
				radius: radius,
				fillColor: color,
				color: '#ffffff',
				weight: 1.4,
				opacity: 0.9,
				fillOpacity: 0.72
			});

			marker.bindTooltip('<strong>' + escapeHtml(ent.name) + '</strong> · ' + ent.worked + ' prefix' + (ent.worked === 1 ? '' : 'es'), {
				direction: 'top',
				offset: [0, -6]
			});

			marker.on('mouseover', function () {
				this.setStyle({ weight: 2.6, fillOpacity: 0.95 });
				renderInfo(ent);
			});
			marker.on('mouseout', function () {
				this.setStyle({ weight: 1.4, fillOpacity: 0.72 });
				renderInfo(null);
			});
			marker.on('click', function () {
				renderInfo(ent);
				var contSel = document.getElementById('wpxContSelect');
				if (contSel && ent.cont) {
					contSel.value = ent.cont;
					contSel.dispatchEvent(new Event('change'));
				}
			});

			marker.addTo(markersLayer);
		});

		markersLayer.addTo(wpxMap);

		if (wpxLegend && wpxLegend._div) {
			var scopeLabel = activeCont === 'ALL' ? 'World' : activeCont;
			wpxLegend._div.innerHTML =
				'<div class="rac-legend-title">' + escapeHtml(scopeLabel) + ' · ' + totalEntities + ' entities (' + totalPfx + ' pfx)</div>' +
				'<div class="rac-legend-item"><span class="rac-swatch" style="background:#198754;border-radius:50%"></span>Confirmed (' + confEntities + ')</div>' +
				'<div class="rac-legend-item"><span class="rac-swatch" style="background:#f59e0b;border-radius:50%"></span>Worked only (' + workOnlyEntities + ')</div>' +
				'<div class="rac-info-sub mt-1">Bubble size = unique prefixes</div>';
		}

		renderInfo(null);
	}

	function initWpxMap() {
		var el = document.getElementById('wpxmap');
		if (!el || typeof L === 'undefined') return;

		var tiles = (typeof wpxTileUrl !== 'undefined' && wpxTileUrl)
			? wpxTileUrl
			: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

		wpxMap = L.map('wpxmap', {
			fullscreenControl: true,
			fullscreenControlOptions: { position: 'topleft' },
			worldCopyJump: true,
			minZoom: 2
		});

		L.tileLayer(tiles, {
			attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
			maxZoom: 18
		}).addTo(wpxMap);

		wpxInfo = L.control({ position: 'topright' });
		wpxInfo.onAdd = function () {
			this._div = L.DomUtil.create('div', 'rac-info');
			return this._div;
		};
		wpxInfo.addTo(wpxMap);

		wpxLegend = L.control({ position: 'bottomright' });
		wpxLegend.onAdd = function () {
			this._div = L.DomUtil.create('div', 'rac-legend');
			return this._div;
		};
		wpxLegend.addTo(wpxMap);

		wpxMap.setView([25, 10], 2);
		renderMarkers();

		document.querySelectorAll('#wpxMapContPills .rac-mode-pill').forEach(function (btn) {
			btn.addEventListener('click', function () {
				activeCont = this.getAttribute('data-wpx-cont') || 'ALL';
				document.querySelectorAll('#wpxMapContPills .rac-mode-pill').forEach(function (b) {
					b.classList.toggle('is-active', b === btn);
				});
				var view = CONT_VIEWS[activeCont] || CONT_VIEWS.ALL;
				wpxMap.setView(view[0], view[1]);
				renderMarkers();
			});
		});
	}

	function initWpxTable() {
		var tbody = document.getElementById('wpxTableBody');
		if (!tbody || typeof wpxPrefixesData === 'undefined') return;

		var statusPills = document.querySelectorAll('#wpxStatusPills .rac-mode-pill');
		var contSelect = document.getElementById('wpxContSelect');
		var searchInput = document.getElementById('wpxFilter');
		var shownEl = document.getElementById('wpxShownCount');
		var prevBtn = document.getElementById('wpxPrevPage');
		var nextBtn = document.getElementById('wpxNextPage');
		var pageInd = document.getElementById('wpxPageIndicator');

		var statusFilter = 'all';
		var contFilter = 'ALL';
		var searchQuery = '';
		var pageSize = 50;
		var currentPage = 1;

		function render() {
			var filtered = wpxPrefixesData.filter(function (row) {
				if (statusFilter === 'confirmed' && !row.confirmed) return false;
				if (statusFilter === 'worked' && row.confirmed) return false;
				if (contFilter !== 'ALL' && (row.cont || '') !== contFilter) return false;
				if (searchQuery) {
					var hay = (row.prefix + ' ' + (row.cont || '') + ' ' + (row.bands || []).join(' ') + ' ' + (row.modes || []).join(' ')).toLowerCase();
					if (hay.indexOf(searchQuery) === -1) return false;
				}
				return true;
			});

			var total = filtered.length;
			var totalPages = Math.max(1, Math.ceil(total / pageSize));
			if (currentPage > totalPages) currentPage = totalPages;
			var start = (currentPage - 1) * pageSize;
			var end = Math.min(start + pageSize, total);
			var slice = filtered.slice(start, end);

			if (total === 0) {
				tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No prefixes match your current filter.</td></tr>';
				if (shownEl) shownEl.textContent = '0 prefixes';
			} else {
				tbody.innerHTML = slice.map(function (row) {
					var badge = row.confirmed
						? '<span class="badge text-bg-success">Confirmed</span>'
						: '<span class="badge text-bg-warning">Worked</span>';
					var bandHtml = (row.bands || []).map(function (b) {
						return '<span class="wae-band-pill ' + (row.confirmed ? 'is-conf' : 'is-work') + '">' + escapeHtml(b) + '</span>';
					}).join(' ');
					return '<tr>'
						+ '<td><span class="wae-code-chip">' + escapeHtml(row.prefix) + '</span></td>'
						+ '<td class="text-center"><span class="badge bg-secondary">' + escapeHtml(row.cont || '—') + '</span></td>'
						+ '<td class="text-center fw-semibold">' + row.qsos + '</td>'
						+ '<td><div class="d-flex flex-wrap gap-1">' + (bandHtml || '<span class="text-muted small">—</span>') + '</div></td>'
						+ '<td class="small">' + escapeHtml((row.modes || []).join(', ')) + '</td>'
						+ '<td class="text-end">' + badge + '</td>'
					+ '</tr>';
				}).join('');
				if (shownEl) shownEl.textContent = 'Showing ' + (start + 1) + '–' + end + ' of ' + total;
			}

			if (pageInd) pageInd.textContent = currentPage + ' / ' + totalPages;
			if (prevBtn) prevBtn.disabled = currentPage <= 1;
			if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
		}

		statusPills.forEach(function (btn) {
			btn.addEventListener('click', function () {
				statusFilter = this.getAttribute('data-wpx-status') || 'all';
				statusPills.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
				currentPage = 1;
				render();
			});
		});

		if (contSelect) {
			contSelect.addEventListener('change', function () {
				contFilter = this.value || 'ALL';
				currentPage = 1;
				render();
			});
		}

		if (searchInput) {
			searchInput.addEventListener('input', function () {
				searchQuery = this.value.trim().toLowerCase();
				currentPage = 1;
				render();
			});
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				if (currentPage > 1) { currentPage--; render(); }
			});
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				currentPage++;
				render();
			});
		}

		render();
	}

	$(document).ready(function () {
		initWpxMap();
		initWpxTable();
	});
})();
