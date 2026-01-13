<?php
$summits = $summits ?? [];
$confirmed = isset($confirmed_refs) ? array_fill_keys($confirmed_refs, true) : [];
$qsos_by_ref = $qsos_by_ref ?? [];
$custom_date_format = $custom_date_format ?? 'Y-m-d';
$plottable = [];
foreach ($summits as $ref => $meta) {
    if (!empty($meta['lat']) && !empty($meta['lon'])) {
        $plottable[$ref] = $meta;
    }
}
?>
<?php if (count($plottable) === 0) : ?>
<div class="alert alert-secondary mb-0" role="alert">
    Map cannot be displayed as no summit coordinates are available.
</div>
<?php else: ?>
<div class="card">
    <div class="card-body p-0">
        <div id="sotaMapCanvas" style="height: 600px;"></div>
    </div>
</div>

<!-- QSO Modal -->
<div class="modal fade" id="sotaQsoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sotaQsoModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="sotaQsoModalContent"></div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    if (!window.L) { return; }
    
    // QSO data by ref
    var qsosByRef = <?php echo json_encode($qsos_by_ref); ?>;
    var dateFormat = '<?php echo $custom_date_format; ?>';
    
    var map = L.map('sotaMapCanvas').setView([20,0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var confirmed = <?php echo json_encode(array_keys($confirmed)); ?>;
    var confirmedSet = {};
    confirmed.forEach(function(c){ confirmedSet[c] = true; });

    var markers = [];
    <?php foreach ($plottable as $ref => $meta) {
        $name = isset($meta['name']) ? $meta['name'] : $ref;
        $assoc = isset($meta['association']) ? $meta['association'] : '';
        $region = isset($meta['region']) ? $meta['region'] : '';
        $popup = htmlspecialchars($ref . ' - ' . $name . ($assoc ? ' (' . $assoc . ')' : '') . ($region ? ' · ' . $region : ''), ENT_QUOTES);
    ?>
    (function(){
        var ref = '<?php echo addslashes($ref); ?>';
        var lat = <?php echo $meta['lat']; ?>;
        var lon = <?php echo $meta['lon']; ?>;
        var isConfirmed = confirmedSet[ref] === true;
        var marker = L.circleMarker([lat, lon], {
            radius: 6,
            color: isConfirmed ? '#28a745' : '#007bff',
            fillColor: isConfirmed ? '#28a745' : '#007bff',
            fillOpacity: 0.8,
            weight: 1
        }).bindPopup('<?php echo $popup; ?>');
        
        // Show modal on marker click
        marker.on('click', function() {
            showSotaQsoModal(ref);
        });
        
        markers.push(marker);
    })();
    <?php } ?>

    if (markers.length) {
        var group = L.featureGroup(markers).addTo(map);
        map.fitBounds(group.getBounds().pad(0.2));
    }
    
    window.showSotaQsoModal = function(ref) {
        var qsos = qsosByRef[ref] || [];
        var title = document.getElementById('sotaQsoModalTitle');
        var content = document.getElementById('sotaQsoModalContent');
        
        title.textContent = ref + ' QSOs';
        
        if (qsos.length === 0) {
            content.innerHTML = '<p class="text-muted">No QSO data found.</p>';
        } else {
            var html = '<table class="table table-sm table-striped"><thead><tr>';
            html += '<th>Date</th><th>Time</th><th>Callsign</th><th>Band</th><th>Mode</th><th>RST (S/R)</th><th>QSL</th></tr></thead><tbody>';
            
            qsos.forEach(function(qso) {
                var dateStr = new Date(qso.COL_TIME_ON).toLocaleDateString();
                var timeStr = new Date(qso.COL_TIME_ON).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                var mode = qso.COL_SUBMODE && qso.COL_SUBMODE.trim() ? qso.COL_SUBMODE : qso.COL_MODE;
                var qsl = (qso.col_qsl_rcvd === 'Y' || qso.COL_QSL_RCVD === 'Y' || qso.col_lotw_qsl_rcvd === 'Y' || qso.COL_LOTW_QSL_RCVD === 'Y') ? 'C' : 'W';
                var qslColor = qsl === 'C' ? '#28a745' : '#007bff';
                
                html += '<tr>';
                html += '<td>' + dateStr + '</td>';
                html += '<td>' + timeStr + '</td>';
                html += '<td>' + (qso.COL_CALL || '-') + '</td>';
                html += '<td>' + (qso.COL_BAND || '-') + '</td>';
                html += '<td>' + (mode || '-') + '</td>';
                html += '<td>' + (qso.COL_RST_SENT || '-') + '/' + (qso.COL_RST_RCVD || '-') + '</td>';
                html += '<td><span style="font-weight: bold; color: ' + qslColor + ';">' + qsl + '</span></td>';
                html += '</tr>';
            });
            
            html += '</tbody></table>';
            content.innerHTML = html;
        }
        
        var modal = new bootstrap.Modal(document.getElementById('sotaQsoModal'));
        modal.show();
    };
})();
</script>
<?php endif; ?>
