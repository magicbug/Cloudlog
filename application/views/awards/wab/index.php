<div class="container">
    <!-- Award Info Box -->
    <br>
    <div id="awardInfoButton">
        <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_vucc_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_vucc_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_vucc_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_vucc_description_ln4'); ?>";
        </script>
        <h2><?php echo $page_title; ?></h2>
        <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
    </div>


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <div id="map" style="width: 100%; height: 100vh;"></div>
    <script>
        var wab_squares = $.ajax({
            url: "http://cloudlog.mg/assets/json/WABSquares.geojson",
            dataType: "json",
            success: console.log("WAB data successfully loaded."),
            error: function(xhr) {
                alert(xhr.statusText)
            }
        })

        $.when(wab_squares).done(function() {

            var map = L.map('map').setView([51.5074, -0.1278], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            console.log(wab_squares.responseJSON);
            // Add requested external GeoJSON to map
            var kywab_squares = L.geoJSON(wab_squares.responseJSON, {
                style: function(feature) {
                    if (feature.properties.name === 'Small Square SP50 Boundry Box') {
                        return {
                            fillColor: '#5cb85c',
                            fill: true,
                            fillOpacity: 1,
                        };
                    } else {
                        return {};
                    }
                },
                pointToLayer: function(feature, latlng) {
                    if (feature.properties && feature.properties.name) {
                        // Create a custom icon that displays the name from the GeoJSON data
                        var labelIcon = L.divIcon({
                            className: 'text-labels', // Set class for CSS styling
                            html: feature.properties.name
                        });

                        // Create a marker at the location of the point
                        return L.marker(latlng, {
                            icon: labelIcon
                        });
                    }
                },
                onEachFeature: function(feature, layer) {
                    layer.on('click', function() {
                        // Code to execute when the area is clicked
                        alert('Area clicked: ' + feature.properties.name);
                    });
                }
            }).addTo(map);
            // Function to update labels based on zoom level
            function updateLabels() {
                var currentZoom = map.getZoom();
                kywab_squares.eachLayer(function(layer) {
                    if (currentZoom >= 8) {
                        // Show labels if zoom level is 10 or higher
                        layer.getElement().style.display = 'block';
                    } else {
                        // Hide labels if zoom level is less than 10
                        layer.getElement().style.display = 'none';
                    }
                });
            }

            // Update labels when the map zoom changes
            map.on('zoomend', updateLabels);

            // Update labels immediately after adding the GeoJSON data to the map
            updateLabels();
        });
    </script>


</div>