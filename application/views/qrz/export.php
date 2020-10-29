
<div class="container adif">

    <h2><?php echo $page_title; ?></h2>

    <div class="card">
        <div class="card-header">
            Upload Logbook
        </div>

        <div class="card-body">

            <p>Here you can see and upload all QSOs which have not been previously uploaded to a QRZ logbook.</p>
            <p>You need to set a QRZ Logbook API key in your station profile. Only station profiles with an API Key set are displayed.</p>
            <p><span class="badge badge-warning">Warning</span>This might take a while as QSO uploads are processed sequentially.</p>
            
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
                    <td>Actions</td>
                </thead>
                <tbody>';
                foreach ($station_profile->result() as $station) {      // Fills the table with the data
                echo '<tr>';
                    echo '<td>' . $station->station_profile_name . '</td>';
                    echo '<td>' . $station->station_callsign . '</td>';
                    echo '<td id ="modcount'.$station->station_id.'">' . $station->modcount . '</td>';
                    echo '<td id ="notcount'.$station->station_id.'">' . $station->notcount . '</td>';
                    echo '<td id ="totcount'.$station->station_id.'">' . $station->totcount . '</td>';
                    echo '<td><button id="qrzUpload" type="button" name="qrzUpload" class="btn btn-primary btn-sm ld-ext-right" onclick="ExportQrz('. $station->station_id .')"><i class="fas fa-cloud-upload-alt"></i> Upload<div class="ld ld-ring ld-spin"></div></button></td>';
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
