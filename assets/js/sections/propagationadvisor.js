(function() {
  const form = $('#propagation-filters');
  const statusEl = $('#propagation-status');
  const summaryEl = $('#propagation-summary');
  const heatmapCard = $('#propagation-heatmap-card');
  const csvLink = $('#propagation-csv');
  const fallbackGrid = $('#propagationHeatmapFallback');
  const strongestBandChip = $('#strongest-band-chip');
  const trendCard = $('#propagation-trend-card');
  const trendSparkline = $('#propagation-trend-sparkline');
  const trend7d = $('#trend-7d');
  const trend30d = $('#trend-30d');
  const trend90d = $('#trend-90d');

  form.on('submit', function(e) {
    e.preventDefault();
    const dxcc = $('#propagation-dxcc').val();
    const band = $('#propagation-band').val();
    const mode = $('#propagation-mode').val();

    if (!dxcc) {
      showStatus(propagation_lang.required_filters, 'info');
      return;
    }

    fetchData(dxcc, band, mode);
  });

  function fetchData(dxcc, band, mode) {
    showStatus('Loading...', 'info');

    $.getJSON(propagation_lang.data_url, { dxcc: dxcc, band: band, mode: mode })
      .done(function(res) {
        if (!res.success) {
          showStatus(res.message || propagation_lang.no_data, 'warning');
          return;
        }

        if (!res.total_qsos || parseInt(res.total_qsos, 10) === 0) {
          showStatus(propagation_lang.no_data, 'warning');
          summaryEl.hide();
          heatmapCard.hide();
          fallbackGrid.hide();
          trendCard.hide();
          return;
        }

        showStatus('', null);
        renderSummary(res);
        renderHeatmap(res.hourly || [], res.hourly_by_band || {});
        renderTrend(res.trend || null);
        updateCsvLink(dxcc, band, mode);
      })
      .fail(function() {
        showStatus(propagation_lang.no_data, 'warning');
      });
  }

  function renderSummary(res) {
    summaryEl.show();
    heatmapCard.show();

    const bestWindow = res.best_window;
    $('#summary-best-window').text(bestWindow && bestWindow.label ? bestWindow.label : '-');
    $('#summary-best-window-count').text(bestWindow ? formatCount(bestWindow.total) : '');

    const bestBand = res.best_band;
    $('#summary-best-band').text(bestBand && bestBand.value ? bestBand.value : '-');
    $('#summary-best-band-count').text(bestBand && bestBand.total ? formatCount(bestBand.total) : '');

    if (bestBand && bestBand.value) {
      const totalText = bestBand.total ? ' (' + formatCount(bestBand.total) + ')' : '';
      const prefix = propagation_lang.strongest_band || 'Strongest band';
      strongestBandChip.text(prefix + ': ' + bestBand.value + totalText);
      strongestBandChip.removeClass('d-none');
    } else {
      strongestBandChip.addClass('d-none');
    }

    const bestMode = res.best_mode;
    $('#summary-best-mode').text(bestMode && bestMode.value ? bestMode.value : '-');
    $('#summary-best-mode-count').text(bestMode && bestMode.total ? formatCount(bestMode.total) : '');

    $('#summary-last-worked').text(res.last_worked ? res.last_worked : '-');
    $('#summary-total-qsos').text(res.total_qsos ? formatCount(res.total_qsos) : '');
  }

  function renderTrend(trend) {
    if (!trend || !trend.counts || trend.counts.length === 0) {
      trendCard.hide();
      return;
    }

    trendCard.show();

    const counts = trend.counts.slice(-30); // show last 30 days in sparkline
    const max = Math.max.apply(null, counts);
    const w = trendSparkline.width() || 240;
    const h = 70;
    const pad = 6;

    let path = '';
    let area = '';
    counts.forEach(function(val, idx) {
      const x = counts.length === 1 ? w / 2 : (idx / (counts.length - 1)) * w;
      const y = max > 0 ? h - pad - (val / max) * (h - pad * 2) : h - pad;
      path += (idx === 0 ? 'M' : 'L') + x + ' ' + y;
      area += (idx === 0 ? 'M' : 'L') + x + ' ' + y;
    });
    if (counts.length) {
      area += ' L ' + w + ' ' + (h - pad) + ' L 0 ' + (h - pad) + ' Z';
    }

    const svg = '<svg viewBox="0 0 ' + w + ' ' + h + '" role="img" aria-label="30 day activity sparkline">'
      + (counts.length ? '<path class="trend-area" d="' + area + '"></path><path class="trend-line" d="' + path + '"></path>' : '')
      + '</svg>';
    trendSparkline.html(svg);

    // Stats
    setTrendPill(trend7d, trend.last_7, trend.prev_7, propagation_lang.trend_7d || '7d');
    setTrendPill(trend30d, trend.last_30, trend.prev_30, propagation_lang.trend_30d || '30d');
    trend90d.text((propagation_lang.trend_90d || '90d') + ': ' + formatCount(trend.last_90)).removeClass('positive negative');
  }

  function setTrendPill(el, current, previous, label) {
    const diff = current - (previous || 0);
    const dirClass = diff > 0 ? 'positive' : (diff < 0 ? 'negative' : '');
    el.removeClass('positive negative');
    if (dirClass) el.addClass(dirClass);
    const sign = diff > 0 ? '+' : '';
    el.text(label + ': ' + formatCount(current) + (diff !== 0 ? ' (' + sign + diff + ')' : ''));
  }

  function renderHeatmap(hourly, hourlyByBand) {
    const values = hourly && hourly.length === 24 ? hourly : new Array(24).fill(0);
    const max = Math.max.apply(null, values);

    const bandCount = hourlyByBand ? Object.keys(hourlyByBand).length : 0;
    const showBandRows = bandCount > 0;
    if (showBandRows) {
      renderFallbackMultiRow(hourlyByBand);
    } else {
      renderFallback(values, max);
    }
  }

  function appendHourHeader(includeLabelCell) {
    if (includeLabelCell) {
      const spacer = $('<div class="heatmap-row-label"></div>');
      spacer.html('&nbsp;');
      fallbackGrid.append(spacer);
    }
    for (let h = 0; h < 24; h++) {
      const header = $('<div class="heatmap-header"></div>');
      header.text(pad(h));
      fallbackGrid.append(header);
    }
  }

  function renderFallbackMultiRow(hourlyByBand) {
    fallbackGrid.empty();
    fallbackGrid.show();
    fallbackGrid.css('grid-template-columns', 'auto repeat(24, minmax(0, 1fr))');

    appendHourHeader(true);

    const bands = Object.keys(hourlyByBand);
    const allValues = [];
    bands.forEach(function(band) {
      allValues.push.apply(allValues, hourlyByBand[band]);
    });
    const max = Math.max.apply(null, allValues);

    bands.forEach(function(band) {
      const labelCell = $('<div class="heatmap-row-label"></div>');
      labelCell.text(band);
      fallbackGrid.append(labelCell);

      const values = hourlyByBand[band];
      values.forEach(function(v, h) {
        const cell = $('<div class="heatmap-cell"></div>');
        cell.attr('title', band + ' @ ' + pad(h) + ':00 UTC - ' + formatCount(v));
        
        const intensity = max > 0 ? Math.min(1, v / max) : 0;
        let color;
        if (intensity === 0) {
          color = '#ebedf0';
        } else if (intensity < 0.25) {
          color = '#9be9a8';
        } else if (intensity < 0.5) {
          color = '#40c463';
        } else if (intensity < 0.75) {
          color = '#30a14e';
        } else {
          color = '#216e39';
        }
        
        cell.css('background-color', color);
        if (intensity > 0.5) {
          cell.css('color', '#ffffff');
        }
        
        fallbackGrid.append(cell);
      });
    });
  }

  function renderFallback(values, max) {
    fallbackGrid.empty();
    fallbackGrid.show();
    fallbackGrid.css('grid-template-columns', 'repeat(24, minmax(0, 1fr))');

    appendHourHeader(false);

    values.forEach(function(v, h) {
      const cell = $('<div class="heatmap-cell"></div>');
      cell.attr('title', pad(h) + ':00 UTC - ' + formatCount(v));
      
      const intensity = max > 0 ? Math.min(1, v / max) : 0;
      let color;
      if (intensity === 0) {
        color = '#ebedf0';
      } else if (intensity < 0.25) {
        color = '#9be9a8';
      } else if (intensity < 0.5) {
        color = '#40c463';
      } else if (intensity < 0.75) {
        color = '#30a14e';
      } else {
        color = '#216e39';
      }
      
      cell.css('background-color', color);
      if (intensity > 0.5) {
        cell.css('color', '#ffffff');
      }
      
      fallbackGrid.append(cell);
    });
  }

  function renderBands(bands) {
    // band breakdown table removed for this view
  }

  function updateCsvLink(dxcc, band, mode) {
    const params = $.param({ dxcc: dxcc, band: band, mode: mode });
    csvLink.attr('href', propagation_lang.export_url + '?' + params);
  }

  function showStatus(message, type) {
    if (!message) {
      statusEl.hide();
      return;
    }
    statusEl.removeClass('alert-info alert-warning alert-danger alert-success');
    if (type === 'warning') statusEl.addClass('alert-warning');
    else if (type === 'danger') statusEl.addClass('alert-danger');
    else if (type === 'success') statusEl.addClass('alert-success');
    else statusEl.addClass('alert-info');
    statusEl.text(message);
    statusEl.show();
  }

  function formatCount(count) {
    if (typeof count === 'undefined' || count === null) {
      return '';
    }
    const value = parseInt(count, 10) || 0;
    return value + ' QSO' + (value === 1 ? '' : 's');
  }

  function pad(value) {
    value = parseInt(value, 10);
    if (isNaN(value)) return value;
    return value < 10 ? '0' + value : '' + value;
  }
})();
