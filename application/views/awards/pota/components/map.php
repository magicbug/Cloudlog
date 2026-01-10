<?php
$parks = isset($parks) ? $parks : [];
$plottable = [];
foreach ($parks as $ref => $meta) {
    if (!empty($meta['lat']) && !empty($meta['lon'])) {
        $plottable[$ref] = $meta;
    }
}
?>

<?php if (count($plottable) === 0) : ?>
<div class="alert alert-secondary mb-0" role="alert">
    Map cannot be displayed as no park coordinates are available.
</div>
<?php else: ?>
<div class="card">
    <div class="card-body">
        <div id="potaMapCanvas" style="height: 400px;"></div>
    </div>
</div>
<script>
(function(){
    if (!window.L) { return; }
    var map = L.map('potaMapCanvas').setView([20,0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    var markers = [];
    <?php foreach ($plottable as $ref => $meta) {
        $name = isset($meta['name']) ? $meta['name'] : $ref;
        $label = htmlspecialchars($ref.' - '.$name, ENT_QUOTES);
    ?>
    markers.push(L.marker([<?= $meta['lat']; ?>, <?= $meta['lon']; ?>]).bindPopup('<?= $label; ?>'));
    <?php } ?>
    if (markers.length) {
        var group = L.featureGroup(markers).addTo(map);
        map.fitBounds(group.getBounds().pad(0.2));
    }
})();
</script>
<?php endif; ?>
