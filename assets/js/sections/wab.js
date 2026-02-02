(function() {
  if (!window.wabConfig || !document.getElementById(wabConfig.mapContainer)) {
    return;
  }

  const workedSquares = wabConfig.workedSquares || [];
  const confirmedSquares = wabConfig.confirmedSquares || [];
  const confirmedOnly = wabConfig.confirmedOnly === true || wabConfig.confirmedOnly === 'true' || wabConfig.confirmedOnly === 1 || wabConfig.confirmedOnly === '1';
  const mapContainerId = wabConfig.mapContainer;

  function isConfirmed(name) {
    return confirmedSquares.indexOf(name) !== -1;
  }

  function isWorked(name) {
    return workedSquares.indexOf(name) !== -1;
  }

  function renderMap(geojson) {
    const layer = L.tileLayer(wabConfig.tileUrl, {
      maxZoom: 18,
      attribution: wabConfig.tileAttribution,
      id: 'mapbox.streets'
    });

    const map = L.map(mapContainerId, {
      layers: [layer],
      center: [54.970901, -2.45714],
      zoom: 8,
      minZoom: 8,
      fullscreenControl: true,
      fullscreenControlOptions: {
        position: 'topleft'
      }
    });

    L.easyPrint({
      tileLayer: layer,
      sizeModes: ['Current'],
      filename: 'wab-map',
      exportOnly: true,
      hideControlContainer: true
    }).addTo(map);

    const legend = L.control({ position: 'topright' });
    legend.onAdd = function() {
      const div = L.DomUtil.create('div', 'legend');
      div.innerHTML += `<h4>${lang_general_word_colors}</h4>`;
      div.innerHTML += "<i style='background: green'></i><span> " + lang_general_word_confirmed + "</span><br>";
      div.innerHTML += "<i style='background: orange'></i><span> " + lang_general_word_worked_not_confirmed + "</span><br>";
      div.innerHTML += "<i style='background: #e0e0e0'></i><span> Not worked</span><br>";
      return div;
    };
    legend.addTo(map);

    const geoLayer = L.geoJSON(geojson, {
      filter: function(feature) {
        if (confirmedOnly) {
          const name = feature.properties && feature.properties.name ? feature.properties.name : null;
          return name && isConfirmed(name);
        }
        return true;
      },
      style: function(feature) {
        const name = feature.properties && feature.properties.name ? feature.properties.name : '';
        if (isConfirmed(name)) {
          return { fillColor: 'green', fill: true, fillOpacity: 0.9, weight: 1, color: '#2f6f31' };
        } else if (isWorked(name)) {
          return { fillColor: 'orange', fill: true, fillOpacity: 0.85, weight: 1, color: '#ad5f00' };
        }
        return { fillColor: '#e0e0e0', fill: true, fillOpacity: 0.3, weight: 1, color: '#999' };
      },
      pointToLayer: function(feature, latlng) {
        if (feature.properties && feature.properties.name) {
          const labelIcon = L.divIcon({
            className: 'text-labels',
            html: feature.properties.name
          });
          return L.marker(latlng, { icon: labelIcon });
        }
      },
      onEachFeature: function(feature, layer) {
        layer.on('click', function() {
          if (feature.properties && feature.properties.name) {
            displaywabcontacts(feature.properties.name);
          }
        });
      }
    }).addTo(map);

    function updateLabels() {
      const zoom = map.getZoom();
      geoLayer.eachLayer(function(layer) {
        if (layer.getElement && layer.getElement()) {
          layer.getElement().style.display = zoom >= 8 ? 'block' : 'none';
        }
      });
    }

    map.on('zoomend', updateLabels);
    updateLabels();
  }

  function displaywabcontacts(wabSquare) {
    const payload = { Wab: wabSquare };
    if (wabConfig.filters) {
      payload.Band = wabConfig.filters.band || 'All';
      payload.Mode = wabConfig.filters.mode || 'All';
      if (wabConfig.filters.confirmed_only) {
        payload.ConfirmedOnly = 1;
      }
    }

    $.ajax({
      url: wabConfig.detailUrl,
      type: 'post',
      data: payload,
      success: function(html) {
        BootstrapDialog.show({
          title: lang_general_word_qso_data,
          size: BootstrapDialog.SIZE_WIDE,
          cssClass: 'qso-wab-dialog',
          nl2br: false,
          message: html,
          onshown: function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
          },
          buttons: [{
            label: lang_admin_close,
            action: function(dialogItself) { dialogItself.close(); }
          }]
        });
      }
    });
  }

  $.getJSON(wabConfig.geojsonUrl)
    .done(function(geojson) {
      renderMap(geojson);
    })
    .fail(function(xhr) {
      console.error('Unable to load WAB squares', xhr.statusText);
    });
})();
