
<div class="container adif">

    <h1>QRZ.com Functions</h1>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><?php echo $page_title; ?></h5>
        </div>

        <div class="card-body">

            <p>Here you can upload all QSOs to QRZ.com, which have not been previously uploaded. This might take a while, since only 1 QSO is uploaded at a time.</p>
            
            <p>You need to set a QRZ API Key in your station profile. Only a station profile with an API Key set, is diplayed in the table below.</p>

<?php
            if ($station_profile->result()) {
            echo '

            <table class="table table-bordered table-hover table-striped table-condensed text-center">
                <thead>
                <tr>
                    <td>Profile name</td>
                    <td>Station callsign</td>
                    <td>Edited QSOs not uploaded</td>
                    <td>Total QSOs not uploaded</td>
                    <td>Total QSOs uploaded</td>
                    <td></td>
                </thead>
                <tbody>';
                foreach ($station_profile->result() as $station) {      // Fills the table with the data
                echo '<tr>';
                    echo '<td>' . $station->station_profile_name . '</td>';
                    echo '<td>' . $station->station_callsign . '</td>';
                    echo '<td id ="modcount'.$station->station_id.'">' . $station->modcount . '</td>';
                    echo '<td id ="notcount'.$station->station_id.'">' . $station->notcount . '</td>';
                    echo '<td id ="totcount'.$station->station_id.'">' . $station->totcount . '</td>';
                    echo '<td><button id="qrzUpload" type="button" name="qrzUpload" class="btn btn-primary btn-sm ld-ext-right" onclick="ExportQrz('. $station->station_id .')">Export<div class="ld ld-ring ld-spin"></div></button></td>';
                    echo '</tr>';
                }
                echo '</tfoot></table></div>';

        }
        else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
        }
        ?>

        </div>
    </div>
</div>
