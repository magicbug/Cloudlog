<div class="container">

    <br>

    <h2><?php echo $this->lang->line('general_word_eqslcards'); ?></h2>

    <div class="alert alert-info" role="alert">
    <?php echo $this->lang->line('qslcard_string_your_are_using'); ?> <?php echo $storage_used; ?> <?php echo $this->lang->line('qslcard_string_disk_space'); ?>
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

	if($this->session->userdata('user_date_format')) {
		// If Logged in and session exists
		$custom_date_format = $this->session->userdata('user_date_format');
	} else {
		// Get Default date format from /config/cloudlog.php
		$custom_date_format = $this->config->item('qso_date_format');
	}

    if (is_array($qslarray->result())) {
        echo '<table style="width:100%" class="eqsltable table table-sm table-bordered table-hover table-striped table-condensed">
        <thead>
        <tr>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_callsign').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_mode').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('general_word_date').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('general_word_time').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_band').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_qsl').'</th>
        <th style=\'text-align: center\'></th>
        </tr>
        </thead><tbody>';

        foreach ($qslarray->result() as $qsl) {
            echo '<tr>';
            echo '<td style=\'text-align: center\'><a id="edit_qso" href="javascript:displayQso('.$qsl->COL_PRIMARY_KEY.')">' . str_replace("0","&Oslash;",$qsl->COL_CALL) . '</a></td>';
			echo '<td style=\'text-align: center\'>';
			echo $qsl->COL_SUBMODE==null?$qsl->COL_MODE:$qsl->COL_SUBMODE;
			echo '</td>';
			echo '<td style=\'text-align: center\'>';
			$timestamp = strtotime($qsl->COL_TIME_ON); echo date($custom_date_format, $timestamp);
			echo '</td>';
			echo '<td style=\'text-align: center\'>';
			$timestamp = strtotime($qsl->COL_TIME_ON); echo date('H:i', $timestamp);
			echo '</td>';
			echo '<td style=\'text-align: center\'>';
			if($qsl->COL_SAT_NAME != null) { echo $qsl->COL_SAT_NAME; } else { echo strtolower($qsl->COL_BAND); };
			echo '</td>';
			echo '<td style=\'text-align: center\'>' . $qsl->image_file . '</td>';
            echo '<td style=\'text-align: center\'><button onclick="viewEqsl(\''.$qsl->image_file.'\', \''. $qsl->COL_CALL . '\')" class="btn btn-sm btn-success">View</button></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
    ?>
    </div>
    <div class="tab-pane fade scrollable-div" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
	<br/>
    <style>
    body {
        margin: 0;
        padding: 0;
    }

    .photo {
        width: 100%;
        display: block;
        background-size: cover;
        background-position: center center;
        box-sizing: border-box;
    }
    .no-touch .photo {
        filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */
        filter: gray; /* IE6-9 */
        -webkit-filter: grayscale( 100% ); /* Chrome 19+, Safari 6+, Safari 6+ iOS */
        opacity: .7;
        transition: all 400ms ease-in-out;
    }
    .no-touch .photo:hover {
        filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'1 0 0 0 0, 0 1 0 0 0, 0 0 1 0 0, 0 0 0 1 0\'/></filter></svg>#grayscale");
        -webkit-filter: grayscale(0%);
        opacity: 1;
    }
    .scrollable-div {
        height: 70vh; /* set a fixed height for the div */
        overflow-y: scroll; /* enable vertical scrollbar */
    }
    @media screen and ( min-width: 768px ) {
        .photo { float: left; width: 50%; }
    }
    @media screen and ( min-width: 1023px ) {
        .photo { width: 33.3333%; }
    }
    @media screen and ( min-width: 1220px ) {
        .photo { width: 25%; }
    }
    @media screen and ( min-width: 1440px ) {
        .photo { width: 20%; }
    }

    .photo:after {
        content: "";
        display: block;
        padding-bottom: 100%;
    }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
    <script> $(document).ready(function() { $('a.photo').magnificPopup( { type:'image' } ); }); </script>
<?php
	if (function_exists("imagecreatefromstring")) {

		$folder_name = "images/eqsl_card_images";
		$desired_width=500;
		$thumb_generations = 100;
		 if (is_array($qslarray->result())) {
			 foreach ($qslarray->result() as $qsl) {
			   if ($thumb_generations >=0) {
				 $src = $folder_name.'/'.$qsl->image_file;
				 $dest = $folder_name.'/_'.$qsl->image_file;
				 if (!file_exists($folder_name.'/_'.$qsl->image_file)) {
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
				 echo '<a href="#" onclick="viewEqsl(\''.$qsl->image_file.'\', \''. $qsl->COL_CALL . '\')" class="photo" style="background-image: url(/'.$dest.');"></a>';
			 }
		     }
		 }
	} else {
		echo 'Gallery requires GD libraries to be installed <a href="https://www.php.net/manual/en/image.installation.php">https://www.php.net/manual/en/image.installation.php</a>';
	}
        ?>
    </div>

</div>
