<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) { ?>
<div class="container-fluid">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-toggle="tab" href="#qsodetails" role="tab" aria-controls="table" aria-selected="true">QSO Details</a>
        </li>
        <li class="nav-item">
            <a id="station-tab" class="nav-link" data-toggle="tab" href="#stationdetails" role="tab" aria-controls="table" aria-selected="true">Station Details</a>
        </li>
        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {

            echo '<li ';
            if (count($qslimages) == 0) {
                echo 'hidden ';
            }
                echo 'class="qslcardtab nav-item">
                <a class="nav-link" id="qsltab" data-toggle="tab" href="#qslcard" role="tab" aria-controls="home" aria-selected="false">QSL Card</a>
                </li>';

            echo '<li class="nav-item">
            <a class="nav-link" id="qslmanagementtab" data-toggle="tab" href="#qslupload" role="tab" aria-controls="home" aria-selected="false">QSL Card Management</a>
            </li>';
        }

        ?>

    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane active" id="qsodetails" role="tabpanel" aria-labelledby="home-tab">

        <div class="row">
            <div class="col">

                <table width="100%">
                    <tr>
                        <?php

                        // Get Date format
                        if($this->session->userdata('user_date_format')) {
                            // If Logged in and session exists
                            $custom_date_format = $this->session->userdata('user_date_format');
                        } else {
                            // Get Default date format from /config/cloudlog.php
                            $custom_date_format = $this->config->item('qso_date_format');
                        }

                        ?>

                        <td>Date/Time:</td>
                        <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></td>
                        <?php } else { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
                        <?php } ?>
                    </tr>

                    <tr>
                        <td>Callsign:</td>
                        <td><b><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></b></td>
                    </tr>

                    <tr>
                        <td>Band:</td>
                        <td><?php echo $row->COL_BAND; ?></td>
                    </tr>

                    <?php if($this->config->item('display_freq') == true) { ?>
                    <tr>
                        <td>Freq:</td>
                        <td><?php echo frequency_display_string($row->COL_FREQ); ?></td>
                    </tr>
                    <?php if($row->COL_FREQ_RX != 0) { ?>
                    <tr>
                        <td>Freq (RX):</td>
                        <td><?php echo frequency_display_string($row->COL_FREQ_RX); ?></td>
                    </tr>
                    <?php }} ?>

                    <tr>
                        <td>Mode:</td>
                        <td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
                    </tr>

                    <tr>
                        <td>RST Sent:</td>
                        <td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX) { ?>(<?php echo $row->COL_STX;?>)<?php } ?> <?php if ($row->COL_STX_STRING) { ?>(<?php echo $row->COL_STX_STRING;?>)<?php } ?></td>
                    </tr>

                    <tr>
                        <td>RST Recv'd:</td>
                        <td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX) { ?>(<?php echo $row->COL_SRX;?>)<?php } ?> <?php if ($row->COL_SRX_STRING) { ?>(<?php echo $row->COL_SRX_STRING;?>)<?php } ?></td>
                    </tr>

                    <?php if($row->COL_GRIDSQUARE != null) { ?>
                    <tr>
                        <td>Gridsquare:</td>
                        <td><?php echo $row->COL_GRIDSQUARE; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_GRIDSQUARE != null) { ?>
                    <!-- Total Distance Between the Station Profile Gridsquare and Logged Square -->
                    <tr>
                        <td>Total Distance</td>
                        <td>
                            <?php
                                // Load the QRA Library
                                $CI =& get_instance();
                                $CI->load->library('qra');

                                // Cacluate Distance
                                echo $CI->qra->distance($row->station_gridsquare, $row->COL_GRIDSQUARE, $measurement_base);

                                switch ($measurement_base) {
                                    case 'M':
                                        echo "mi";
                                        break;
                                    case 'K':
                                        echo "km";
                                        break;
                                    case 'N':
                                        echo "nmi";
                                        break;
                                }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_VUCC_GRIDS != null) { ?>
                    <tr>
                        <td>Gridsquare (Multi):</td>
                        <td><?php echo $row->COL_VUCC_GRIDS; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_STATE != null) { ?>
                    <tr>
                        <td>USA State:</td>
                        <td><?php echo $row->COL_STATE; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CNTY != null) { ?>
                        <tr>
                            <td>USA County:</td>
                            <td><?php echo $row->COL_CNTY; ?></td>
                        </tr>
                    <?php } ?>

                    <?php if($row->COL_NAME != null) { ?>
                    <tr>
                        <td>Name:</td>
                        <td><?php echo $row->COL_NAME; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                    <?php if($row->COL_COMMENT != null) { ?>
                    <tr>
                        <td>Comment:</td>
                        <td><?php echo $row->COL_COMMENT; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>

                    <?php if($row->COL_SAT_NAME != null) { ?>
                    <tr>
                        <td>Sat Name:</td>
                        <td><?php echo $row->COL_SAT_NAME; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SAT_MODE != null) { ?>
                    <tr>
                        <td>Sat Mode:</td>
                        <td><?php echo $row->COL_SAT_MODE; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($row->COL_COUNTRY != null) { ?>
                    <tr>
                        <td>Country:</td>
                        <td><?php echo ucwords(strtolower(($row->COL_COUNTRY))); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CONTEST_ID != null) { ?>
                    <tr>
                        <td>Contest Name:</td>
                        <td><?php echo $row->COL_CONTEST_ID; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_IOTA != null) { ?>
                    <tr>
                        <td>IOTA Ref:</td>
                        <td><?php echo $row->COL_IOTA; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SOTA_REF != null) { ?>
                    <tr>
                        <td>SOTA Ref:</td>
                        <td><?php echo $row->COL_SOTA_REF; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG != null) { ?>
                    <tr>
                        <td>Sig:</td>
                        <td><?php echo $row->COL_SIG; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG_INFO != null) { ?>
                    <tr>
                        <td>Sig Info:</td>
                        <td><?php echo $row->COL_SIG_INFO; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_DARC_DOK != null) { ?>
                    <tr>
                        <td>DOK:</td>
                        <td><a href="https://www.darc.de/<?php echo $row->COL_DARC_DOK; ?>" target="_new"><?php echo $row->COL_DARC_DOK; ?></a></td>
                    </tr>
                    <?php } ?>

                </table><br/>
                <?php if($row->COL_QSL_SENT == "Y" || $row->COL_QSL_RCVD == "Y") { ?>
                    <h5>QSL Info:</h5>

                    <?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "B") { ?>
                    <p>QSL card has been sent via the bureau.</p>
                    <?php } ?>
                    <?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "D") { ?>
                    <p>QSL card has been sent direct.</p>
                    <?php } ?>

                    <?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "B") { ?>
                    <p>QSL card has been received via the bureau.</p>
                    <?php } ?>
                    <?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "D") { ?>
                    <p>QSL card has been received direct.</p>
                    <?php } ?>
                <?php } ?>

                    <?php if($row->COL_LOTW_QSL_RCVD == "Y") { ?>
                    <h5>Logbook of The World:</h5>
                        <p>This QSO was confirmed on <?php echo $row->COL_LOTW_QSLRDATE; ?>.</p>
                    <?php } ?>
                        

                    <?php if($row->COL_EQSL_QSL_RCVD == "Y") { ?>
                    <h5>eQSL:</h5>
                        <p>This QSO was confirmed on <?php echo $row->COL_EQSL_QSLRDATE; ?>.</p>
                    <?php } ?>
            </div>


                <div class="col">

                    <div id="mapqso" style="width: 340px; height: 250px"></div>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                        <br>
                            <p class="editButton"><a class="btn btn-primary" href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" href="javascript:;"><i class="fas fa-edit"></i> Edit QSO</a></p>
                    <?php } ?>

                    <?php

                        if($row->COL_SAT_NAME != null) {
                            $twitter_band_sat = $row->COL_SAT_NAME;
                            $hashtags = "#hamr #cloudlog #amsat";
                        } else {
                            $twitter_band_sat = $row->COL_BAND;
                            $hashtags = "#hamr #cloudlog";
                        }

                        $twitter_string = urlencode("Just worked ".$row->COL_CALL." in ".ucwords(strtolower(($row->COL_COUNTRY)))." (Gridsquare: ".$row->COL_GRIDSQUARE.") on ".$twitter_band_sat." using ".$row->COL_MODE." ".$hashtags);
                    ?>

                    <div class="text-right"><a class="btn btn-sm btn-primary twitter-share-button" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $twitter_string; ?>"><i class="fab fa-twitter"></i> Tweet</a></div>

                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="stationdetails" role="tabpanel" aria-labelledby="table-tab">

            <table width="100%">
                    <tr>
                        <td>Station Callsign:</td>
                        <td><?php echo $row->station_callsign; ?></td>
                    </tr>
                    <tr>
                        <td>Station Gridsquare:</td>
                        <td><?php echo $row->station_gridsquare; ?></td>
                    </tr>

                    <?php if($row->station_city) { ?>
                    <tr>
                        <td>Station City:</td>
                        <td><?php echo $row->station_city; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->station_country) { ?>
                    <tr>
                        <td>Station Country:</td>
                        <td><?php echo ucwords(strtolower(($row->station_country))); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_OPERATOR) { ?>
                    <tr>
                        <td>Station Operator:</td>
                        <td><?php echo $row->COL_OPERATOR; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_TX_PWR) { ?>
                    <tr>
                        <td>Station Transmit Power:</td>
                        <td><?php echo $row->COL_TX_PWR; ?>w</td>
                    </tr>
                    <?php } ?>
            </table>
        </div>

        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {
        ?>
        <div class="tab-pane fade" id="qslupload" role="tabpanel" aria-labelledby="table-tab">
            <?php
            if (count($qslimages) > 0) {
            echo '<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed">
                <thead>
                <tr>
                    <th style=\'text-align: center\'>QSL image file</th>
                    <th style=\'text-align: center\'></th>
                    <th style=\'text-align: center\'></th>
                </tr>
                </thead><tbody>';

                foreach ($qslimages as $qsl) {
                echo '<tr>';
                    echo '<td style=\'text-align: center\'>' . $qsl->filename . '</td>';
                    echo '<td id="'.$qsl->id.'" style=\'text-align: center\'><button onclick="deleteQsl('.$qsl->id.')" class="btn btn-sm btn-danger">Delete</button></td>';
                    echo '<td style=\'text-align: center\'><button onclick="viewQsl(\''.$qsl->filename.'\')" class="btn btn-sm btn-success">View</button></td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            }
            ?>

            <p><div class="alert alert-warning" role="alert"><span class="badge badge-warning">Warning</span> Maximum file upload size is <?php echo $max_upload; ?>B.</div></p>

            <form class="form" id="fileinfo" name="fileinfo" enctype="multipart/form-data">
                <fieldset>

                    <div class="form-group">
                        <label for="qslcardfront">Upload QSL Card front image</label>
                        <input class="form-control-file" type="file" id="qslcardfront" name="qslcardfront" accept="image/*" >
                    </div>

                    <div class="form-group">
                        <label for="qslcardback">Upload QSL Card back image</label>
                        <input class="form-control-file" type="file" id="qslcardback" name="qslcardback" accept="image/*">
                    </div>

                    <input type="hidden" class="form-control" id="qsoinputid" name="qsoid" value="<?php echo $row->COL_PRIMARY_KEY; ?>">

                    <button type="button" onclick="uploadQsl();" id="button1id"  name="button1id" class="btn btn-primary">Upload QSL card image</button>

                </fieldset>
            </form>
        </div>

        <div class="tab-pane fade" id="qslcard" role="tabpanel" aria-labelledby="table-tab">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php
                    $i = 0;
                    foreach ($qslimages as $image) {
                        echo '<li data-target="#carouselExampleIndicators" data-slide-to="' . $i . '"';
                        if ($i == 0) {
                            echo 'class="active"';
                        }
                        $i++;
                        echo '></li>';
                    }
                    ?>
                </ol>
                <div class="carousel-inner">

                    <?php
                    $i = 1;
                    foreach ($qslimages as $image) {
                        echo '<div class="carousel-item carouselimageid_' . $image->id;
                        if ($i == 1) {
                            echo ' active';
                        }
                        echo '">';
                        echo '<img class="d-block w-100" src="' . base_url() . '/assets/qslcard/' . $image->filename .'" alt="QSL picture #'. $i++.'">';
                        echo '</div>';
                    }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <?php
        }
        ?>
</div>
</div>

<?php
	if($row->COL_GRIDSQUARE != null) {
		$stn_loc = $this->qra->qra2latlong(trim($row->COL_GRIDSQUARE));			
		$lat = $stn_loc[0];
		$lng = $stn_loc[1];
	} else {

		$CI =& get_instance();
		$CI->load->model('Logbook_model');

		$result = $CI->Logbook_model->dxcc_lookup($row->COL_CALL, $row->COL_TIME_ON);
			$lat = $result['lat'];
			$lng = $result['long'];
	}
?>



<script>
var lat = <?php echo $lat; ?>;
var long = <?php echo $lng; ?>;
var callsign = "<?php echo $row->COL_CALL; ?>";
</script>
    <div hidden id ='lat'><?php echo $lat; ?></div>
    <div hidden id ='long'><?php echo $lng; ?></div>
    <div hidden id ='callsign'><?php echo $row->COL_CALL; ?></div>
    <div hidden id ='qsoid'><?php echo $row->COL_PRIMARY_KEY; ?></div>

<?php } } ?>
<?php 
  // converts a frequency in Hz (e.g. 3650) to 3.650 MHz 
  function frequency_display_string($frequency)
  {
    return number_format (($frequency / 1000 / 1000), 3) . " MHz";
  }
?>
