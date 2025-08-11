<div class="container">
    <br>
    <h2>
        <?php echo lang('general_word_sstvimages'); ?>
    </h2>
    <div class="alert alert-info" role="alert">
        <?php echo lang('qslcard_string_your_are_using'); ?>
        <?php echo $storage_used; ?>
        <?php echo lang('sstv_string_disk_space'); ?>
    </div>

    <br>
    <div class="tabs">
	<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
	<a class="nav-link active" id="list-tab" data-bs-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true">List</a>
        </li>
	<li class="nav-item">
	<a class="nav-link" id="gallery-tab" data-bs-toggle="tab" href="#gallery" role="tab" aria-controls="gallery" aria-selected="false">Gallery</a>
	</li>
	</ul>
    </div>

    <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">
    <br/>

    <?php

    if ($this->session->userdata('user_date_format')) {
        // If Logged in and session exists
        $custom_date_format = $this->session->userdata('user_date_format');
    } else {
        // Get Default date format from /config/cloudlog.php
        $custom_date_format = $this->config->item('qso_date_format');
    }

    if ($sstvArray !== FALSE && is_array($sstvArray->result())) {
        echo '<table style="width:100%" class="sstvtable table table-sm table-bordered table-hover table-striped table-condensed">
        <thead>
        <tr>
        <th style=\'text-align: center\'>' . lang('gen_hamradio_callsign') . '</th>
        <th style=\'text-align: center\'>' . lang('gen_hamradio_mode') . '</th>
        <th style=\'text-align: center\'>' . lang('general_word_date') . '</th>
        <th style=\'text-align: center\'>' . lang('general_word_time') . '</th>
        <th style=\'text-align: center\'>' . lang('gen_hamradio_band') . '</th>
        <th style=\'text-align: center\'></th>
        <th style=\'text-align: center\'></th>
        <th style=\'text-align: center\'></th>
        </tr>
        </thead><tbody>';

        foreach ($sstvArray->result() as $sstvImage) {
            echo '<tr>';
            echo '<td style=\'text-align: center\'>' . str_replace("0", "&Oslash;", $sstvImage->COL_CALL) . '</td>';
            echo '<td style=\'text-align: center\'>';
            echo $sstvImage->COL_SUBMODE == null ? $sstvImage->COL_MODE : $sstvImage->COL_SUBMODE;
            echo '</td>';
            echo '<td style=\'text-align: center\'>';
            $timestamp = strtotime($sstvImage->COL_TIME_ON);
            echo date($custom_date_format, $timestamp);
            echo '</td>';
            echo '<td style=\'text-align: center\'>';
            $timestamp = strtotime($sstvImage->COL_TIME_ON);
            echo date('H:i', $timestamp);
            echo '</td>';
            echo '<td style=\'text-align: center\'>';
            if ($sstvImage->COL_SAT_NAME != null) {
                echo $sstvImage->COL_SAT_NAME;
            } else {
                echo strtolower($sstvImage->COL_BAND);
            }
            ;
            echo '</td>';
            echo '<td style=\'text-align: center\'>' . $sstvImage->filename . '</td>';
            echo '<td id="' . $sstvImage->id . '" style=\'text-align: center\'><button onclick="deleteSstv(\'' . $sstvImage->id . '\')" class="btn btn-sm btn-danger">Delete</button></td>';
            echo '<td style=\'text-align: center\'><button onclick="viewSstv(\'' . $sstvImage->filename . '\', \'' . $sstvImage->COL_CALL . '\')" class="btn btn-sm btn-success">View</button></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-warning" role="alert">No SSTV images Found.</div>';
    }
    ?>

    </div>
    <div class="tab-pane fade scrollable-div" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
	<br/>
    <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.magnific-popup.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/magnific-popup.min.css" />
    <script> $(document).ready(function() { $('a.photo').magnificPopup( { type:'image' } ); }); </script>
<?php
	if (function_exists("imagecreatefromstring")) {

		$folder_name = "assets/sstvimages";
		$desired_width=500;
		$thumb_generations = 100;
		 if (is_array($sstvArray->result())) {
			 foreach ($sstvArray->result() as $sstvImage) {
			   if ($thumb_generations >=0) {
				 $src = $folder_name.'/'.$sstvImage->filename;
				 $dest = $folder_name.'/_'.$sstvImage->filename;
				 if (!file_exists($folder_name.'/_'.$sstvImage->filename)) {
				    $thumb_generations--;
			    	    /* read the source image */
				    $data = file_get_contents( $src );
				    $source_image = imagecreatefromstring( $data );
				    $width = imagesx( $source_image );
				    $height = imagesy( $source_image );

				    /* find the "desired height" of this thumbnail, relative to the desired width  */
				    $desired_height = floor( $height * ( $desired_width / $width ) );

				    /* create a new, "virtual" image */
				    $virtual_image = imagecreatetruecolor( $desired_width, $desired_height );

				    /* copy source image at a resized size */
				    imagecopyresampled( $virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height );

				    /* create the physical thumbnail image to its destination */
				    imagejpeg( $virtual_image, $dest, 60 );
				 }
				 echo '<a href="#" onclick="viewSstv(\''.$sstvImage->filename.'\', \''. $sstvImage->COL_CALL . '\')" class="photo" style="background-image: url(/'.$dest.');"></a>';
			 }
		     }
		 }
	} else {
		echo 'Gallery requires GD libraries to be installed <a href="https://www.php.net/manual/en/image.installation.php">https://www.php.net/manual/en/image.installation.php</a>';
	}
        ?>
    </div>
</div>
