<form class="form col-md-12" enctype="multipart/form-data">
    <div class="mb-3 row">
        <div class="col-md-2 control-label" for="input">Locator 1</div>
        <div class="col-md-4">
            <input class="form-control input-group-sm" id="qrbcalc_locator1" type="text" name="locator1" placeholder="" value="<?php if ($station_locator != "0") echo $station_locator; ?>" aria-label="locator1">  
        </div>
    </div>

    <div class="mb-3 row">
        <div class="col-md-2 control-label" for="input">Locator 2</div>
        <div class="col-md-4">
        <input class="form-control input-group-sm" id="qrbcalc_locator2" type="text" name="locator2" placeholder="" aria-label="locator2">
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-md-2 control-label" for="button1id"></label>
        <div class="col-md-4">
            <button id="button2id" type="reset" name="button2id" class="btn btn-sm btn-warning">Reset</button>
            <button id="button1id" type="button" onclick="calculateQrb();" name="button1id" class="btn btn-sm btn-primary">Calculate</button>
        </div>
    </div>
</form>
<div class="qrbResult"></div>
<div id="mapqrb"><div id="mapqrbcontainer" style="Height: 500px"></div></div>

<script type="text/javascript">
    function newpath(latlng1, latlng2, locator1, locator2) {
        // If map is already initialized
        var container = L.DomUtil.get('mapqrbcontainer');

        if (container != null) {
            container._leaflet_id = null;
            container.remove();
            $("#mapqrb").append('<div id="mapqrbcontainer" style="Height: 500px"></div>');
        }

        var map = new L.Map('mapqrbcontainer', {
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: 'topleft'
            },
        }).setView([30, 0], 1.5);

        // Need to fix so that marker is placed at same place as end of line, but this only needs to be done when longitude is < -170
        if (latlng2[1] < -170) {
            latlng2[1] = parseFloat(latlng2[1]) + 360;
        }
        if (latlng1[1] < -170) {
            latlng1[1] = parseFloat(latlng1[1]) + 360;
        }

        map.fitBounds([
            [latlng1[0], latlng1[1]],
            [latlng2[0], latlng2[1]]
        ]);

        var maidenhead = L.maidenheadqrb().addTo(map);

        var osmUrl = '<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>';
        var osmAttrib = 'Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
        var osm = new L.TileLayer(osmUrl, {
            minZoom: 1,
            maxZoom: 12,
            attribution: osmAttrib
        });

        var redIcon = L.icon({
            iconUrl: icon_dot_url,
            iconSize: [10, 10], // size of the icon
        });

        map.addLayer(osm);

        var marker = L.marker([latlng1[0], latlng1[1]], {
            closeOnClick: false,
            autoClose: false
        }).addTo(map).bindPopup(locator1);

        var marker2 = L.marker([latlng2[0], latlng2[1]], {
            closeOnClick: false,
            autoClose: false
        }).addTo(map).bindPopup(locator2);

        const multiplelines = [];
        multiplelines.push(
            new L.LatLng(latlng1[0], latlng1[1]),
            new L.LatLng(latlng2[0], latlng2[1])
        )

        const geodesic = L.geodesic(multiplelines, {
            weight: 3,
            opacity: 1,
            color: 'red',
            wrap: false,
            steps: 100
        }).addTo(map);
    }
</script>