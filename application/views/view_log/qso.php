<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) { ?>
<div class="container-fluid">

    <ul style="margin-bottom: 10px;" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-toggle="tab" href="#qsodetails" role="tab" aria-controls="table" aria-selected="true"><?php echo $this->lang->line('qso_details'); ?></a>
        </li>
        <li class="nav-item">
            <a id="station-tab" class="nav-link" data-toggle="tab" href="#stationdetails" role="tab" aria-controls="table" aria-selected="true"><?php echo $this->lang->line('cloudlog_station_profile'); ?></a>
        </li>
        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {

            echo '<li ';
            if (count($qslimages) == 0) {
                echo 'hidden ';
            }
                echo 'class="qslcardtab nav-item">
                <a class="nav-link" id="qsltab" data-toggle="tab" href="#qslcard" role="tab" aria-controls="home" aria-selected="false">'. $this->lang->line('general_word_qslcard') .'</a>
                </li>';

            echo '<li class="nav-item">
            <a class="nav-link" id="qslmanagementtab" data-toggle="tab" href="#qslupload" role="tab" aria-controls="home" aria-selected="false">'. $this->lang->line('general_word_qslcard_management') .'</a>
            </li>';
        }

        ?>

    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane active" id="qsodetails" role="tabpanel" aria-labelledby="home-tab">

        <div class="row">
            <div class="col-md">

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

                        <td><?php echo $this->lang->line('general_word_datetime'); ?></td>
                        <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); $timestamp = strtotime($row->COL_TIME_ON); echo " at ".date('H:i', $timestamp); ?></td>
                        <?php } else { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
                        <?php } ?>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_callsign'); ?></td>
                        <td><b><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></b></td>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_band'); ?></td>
                        <td><?php echo $row->COL_BAND; ?></td>
                    </tr>

                    <?php if($this->config->item('display_freq') == true) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_frequency'); ?></td>
                        <td><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ); ?></td>
                    </tr>
                    <?php if($row->COL_FREQ_RX != 0) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_frequency_rx'); ?></td>
                        <td><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ_RX); ?></td>
                    </tr>
                    <?php }} ?>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_mode'); ?></td>
                        <td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_rsts'); ?></td>
                        <td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX) { ?>(<?php echo $row->COL_STX;?>)<?php } ?> <?php if ($row->COL_STX_STRING) { ?>(<?php echo $row->COL_STX_STRING;?>)<?php } ?></td>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_rstr'); ?></td>
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
                        <td><?php echo $this->lang->line('general_total_distance'); //Total distance ?></td>
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

                    <?php if($row->COL_CNTY != null && $row->COL_CNTY != ",") { ?>
                        <tr>
                            <td>USA County:</td>
                            <td><?php echo $row->COL_CNTY; ?></td>
                        </tr>
                    <?php } ?>

                    <?php if($row->COL_NAME != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('general_word_name'); ?></td>
                        <td><?php echo $row->COL_NAME; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                    <?php if($row->COL_COMMENT != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('general_word_comment'); ?></td>
                        <td><?php echo $row->COL_COMMENT; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>

                    <?php if($row->COL_SAT_NAME != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_satellite_name'); ?></td>
                        <td><?php echo $row->COL_SAT_NAME; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SAT_MODE != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_satellite_mode'); ?></td>
                        <td><?php echo $row->COL_SAT_MODE; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($row->COL_COUNTRY != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('general_word_country'); ?></td>
                        <td><?php echo ucwords(strtolower(($row->COL_COUNTRY))); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CONTEST_ID != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('contesting_contest_name'); ?></td>
                        <td><?php echo $row->COL_CONTEST_ID; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_IOTA != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_iota_reference'); ?></td>
                        <td><?php echo $row->COL_IOTA; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SOTA_REF != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_sota_reference'); ?></td>
                        <td><?php echo $row->COL_SOTA_REF; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_sig'); ?></td>
                        <td><?php echo $row->COL_SIG; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG_INFO != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_sig_info'); ?></td>
                        <td><?php echo $row->COL_SIG_INFO; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_DARC_DOK != null) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('gen_hamradio_dok'); ?></td>
                        <td><a href="https://www.darc.de/<?php echo $row->COL_DARC_DOK; ?>" target="_new"><?php echo $row->COL_DARC_DOK; ?></a></td>
                    </tr>
                    <?php } ?>

                </table>
                <?php if($row->COL_QSL_SENT == "Y" || $row->COL_QSL_RCVD == "Y") { ?>
                    <h3><?php echo $this->lang->line('qslcard_info'); ?></h3>

                    <?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "B") { ?>
                    <p><?php echo $this->lang->line('qslcard_sent_bureau'); ?></p>
                    <?php } ?>
                    <?php if($row->COL_QSL_SENT == "Y" && $row->COL_QSL_SENT_VIA == "D") { ?>
                    <p><?php echo $this->lang->line('qslcard_sent_direct'); ?></p>
                    <?php } ?>

                    <?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "B") { ?>
                    <p><?php echo $this->lang->line('qslcard_recvd_bureau'); ?></p>
                    <?php } ?>
                    <?php if($row->COL_QSL_RCVD == "Y" && $row->COL_QSL_RCVD_VIA == "D") { ?>
                    <p><?php echo $this->lang->line('qslcard_recvd_direct'); ?></p>
                    <?php } ?>
                <?php } ?>

                    <?php if($row->COL_LOTW_QSL_RCVD == "Y") { ?>
                    <h3><?php echo $this->lang->line('lotw_short'); ?></h3>
                    <p><?php echo $this->lang->line('gen_this_qso_was_confirmed_on'); ?> <?php $timestamp = strtotime($row->COL_LOTW_QSLRDATE); echo date($custom_date_format, $timestamp); ?>.</p>
                    <?php } ?>

                    <?php if($row->COL_EQSL_QSL_RCVD == "Y") { ?>
                    <h3>eQSL</h3>
                        <p><?php echo $this->lang->line('gen_this_qso_was_confirmed_on'); ?> <?php $timestamp = strtotime($row->COL_EQSL_QSLRDATE); echo date($custom_date_format, $timestamp); ?>.</p>
                    <?php } ?>
            </div>

                <div class="col-md">

                    <div id="mapqso" style="width: 100%; height: 250px"></div>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                        <br>
                            <p class="editButton"><a class="btn btn-primary" href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" href="javascript:;"><i class="fas fa-edit"></i><?php echo $this->lang->line('qso_btn_edit_qso'); ?></a></p>
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
            <h3>Station Details</h3>

            <table width="100%">
                    <tr>
                        <td>Station Callsign</td>
                        <td><?php echo $row->station_callsign; ?></td>
                    </tr>
                    <tr>
                        <td>Station Gridsquare</td>
                        <td><?php echo $row->station_gridsquare; ?></td>
                    </tr>

                    <?php if($row->station_city) { ?>
                    <tr>
                        <td>Station City</td>
                        <td><?php echo $row->station_city; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->station_country) { ?>
                    <tr>
                        <td>Station Country</td>
                        <td><?php echo ucwords(strtolower(($row->station_country))); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_OPERATOR) { ?>
                    <tr>
                        <td>Station Operator</td>
                        <td><?php echo $row->COL_OPERATOR; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_TX_PWR) { ?>
                    <tr>
                        <td>Station Transmit Power</td>
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
                        <label for="qslcardfront"><?php echo $this->lang->line('qslcard_upload_front'); ?></label>
                        <input class="form-control-file" type="file" id="qslcardfront" name="qslcardfront" accept="image/*" >
                    </div>

                    <div class="form-group">
                        <label for="qslcardback"><?php echo $this->lang->line('qslcard_upload_back'); ?></label>
                        <input class="form-control-file" type="file" id="qslcardback" name="qslcardback" accept="image/*">
                    </div>

                    <input type="hidden" class="form-control" id="qsoinputid" name="qsoid" value="<?php echo $row->COL_PRIMARY_KEY; ?>">

                    <button type="button" onclick="uploadQsl();" id="button1id"  name="button1id" class="btn btn-primary"><?php echo $this->lang->line('qslcard_upload_button'); ?></button>

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