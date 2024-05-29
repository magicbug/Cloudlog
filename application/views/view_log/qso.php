<?php if ($query->num_rows() > 0) {  foreach ($query->result() as $row) { ?>
<div class="container-fluid">
    <ul style="margin-bottom: 10px;" class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-bs-toggle="tab" href="#qsodetails" role="tab" aria-controls="table" aria-selected="true"><?php echo lang('qso_details'); ?></a>
        </li>
        <li class="nav-item">
            <a id="station-tab" class="nav-link" data-bs-toggle="tab" href="#stationdetails" role="tab" aria-controls="table" aria-selected="true"><?php echo lang('cloudlog_station_profile'); ?></a>
        </li>
        <?php
        if ($row->COL_NOTES != null) {?>
        <li class="nav-item">
            <a id="notes-tab" class="nav-link" data-bs-toggle="tab" href="#notesdetails" role="tab" aria-controls="table" aria-selected="true"><?php echo "Notes"; ?></a>
        </li>
        <?php }?>
        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {

            echo '<li ';
            if (count($qslimages) == 0) {
                echo 'hidden ';
            }
                echo 'class="qslcardtab nav-item">
                <a class="nav-link" id="qsltab" data-bs-toggle="tab" href="#qslcard" role="tab" aria-controls="home" aria-selected="false">'. lang('general_word_qslcard') .'</a>
                </li>';

            echo '<li class="nav-item">
            <a class="nav-link" id="qslmanagementtab" data-bs-toggle="tab" href="#qslupload" role="tab" aria-controls="home" aria-selected="false">'. lang('general_word_qslcard_management') .'</a>
            </li>';
        }

        ?>
        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2) && ($row->COL_MODE == 'SSTV')) {
            echo '<li ';
            if (count($sstvimages) == 0) {
                echo 'hidden ';
            }
                echo 'class="sstvimagetab nav-item">
                <a class="nav-link" id="sstvtab" data-bs-toggle="tab" href="#sstvimage" role="tab" aria-controls="home" aria-selected="false">'. lang('general_word_sstvimages') .'</a>
                </li>';

            echo '<li class="nav-item">
            <a class="nav-link" id="sstvmanagementtab" data-bs-toggle="tab" href="#sstvupload" role="tab" aria-controls="home" aria-selected="false">'. lang('general_word_sstv_management') .'</a>
            </li>';
        }

        ?>
        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {

            echo '<li ';
            if ($row->eqsl_image_file == null) {
                echo 'hidden ';
            }
                echo 'class="eqslcardtab nav-item">
                <a class="nav-link" id="eqsltab" data-bs-toggle="tab" href="#eqslcard" role="tab" aria-controls="home" aria-selected="false">'. $this->lang->line('general_word_eqslcard') .'</a>
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

                        <td><?php echo lang('general_word_datetime'); ?></td>
                        <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); $timestamp = strtotime($row->COL_TIME_ON); $time_on = date('H:i', $timestamp); echo " at ".$time_on; ?>
                        <?php $timestamp = strtotime($row->COL_TIME_OFF); $time_off = date('H:i', $timestamp); if ($time_on != $time_off) { echo " - ".$time_off; } ?>
                        </td>
                        <?php } else { ?>
                        <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
                        <?php } ?>
                    </tr>

                    <tr>
                        <td><?php echo lang('gen_hamradio_callsign'); ?></td>
                        <td><b><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></b> <a target="_blank" href="https://www.qrz.com/db/<?php echo strtoupper($row->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/qrz.png" alt="Lookup <?php echo strtoupper($row->COL_CALL); ?> on QRZ.com"></a> <a target="_blank" href="https://www.hamqth.com/<?php echo strtoupper($row->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/hamqth.png" alt="Lookup <?php echo strtoupper($row->COL_CALL); ?> on HamQTH"></a> <a target="_blank" href="http://www.eqsl.cc/Member.cfm?<?php echo strtoupper($row->COL_CALL); ?>"><img width="16" height="16" src="<?php echo base_url(); ?>images/icons/eqsl.png" alt="Lookup <?php echo strtoupper($row->COL_CALL); ?> on eQSL.cc"></a></td>
                    </tr>

                    <tr>
                        <td><?php echo lang('gen_hamradio_band'); ?></td>
                        <td><?php echo $row->COL_BAND; ?></td>
                    </tr>

                    <?php if($this->config->item('display_freq') == true) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_frequency'); ?></td>
                        <td><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ); ?></td>
                    </tr>
                    <?php if($row->COL_FREQ_RX != 0) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_frequency_rx'); ?></td>
                        <td><?php echo $this->frequency->hz_to_mhz($row->COL_FREQ_RX); ?></td>
                    </tr>
                    <?php }} ?>

                    <tr>
                        <td><?php echo lang('gen_hamradio_mode'); ?></td>
                        <td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
                    </tr>

                    <tr>
                        <td><?php echo lang('gen_hamradio_rsts'); ?></td>
                        <td><?php echo $row->COL_RST_SENT; ?> <?php if ($row->COL_STX) { ?>(<?php printf("%03d", $row->COL_STX);?>)<?php } ?> <?php if ($row->COL_STX_STRING) { ?>(<?php echo $row->COL_STX_STRING;?>)<?php } ?></td>
                    </tr>

                    <tr>
                        <td><?php echo lang('gen_hamradio_rstr'); ?></td>
                        <td><?php echo $row->COL_RST_RCVD; ?> <?php if ($row->COL_SRX) { ?>(<?php printf("%03d", $row->COL_SRX);?>)<?php } ?> <?php if ($row->COL_SRX_STRING) { ?>(<?php echo $row->COL_SRX_STRING;?>)<?php } ?></td>
                    </tr>

                    <?php if($row->COL_GRIDSQUARE != null) { ?>
                    <tr>
                        <td>Gridsquare:</td>
                        <td><?php echo $row->COL_GRIDSQUARE; ?> <a href="javascript:spawnQrbCalculator('<?php echo $row->station_gridsquare . '\',\'' . $row->COL_GRIDSQUARE; ?>')"><i class="fas fa-globe"></i></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_GRIDSQUARE != null && strlen($row->COL_GRIDSQUARE) >= 4) { ?>
                    <!-- Total Distance Between the Station Profile Gridsquare and Logged Square -->
                    <tr>
                        <td><?php echo lang('general_total_distance'); //Total distance ?></td>
                        <td>
                            <?php
                                // Cacluate Distance
                                $distance = $this->qra->distance($row->station_gridsquare, $row->COL_GRIDSQUARE, $measurement_base);

                                switch ($measurement_base) {
                                    case 'M':
                                        $distance .= " mi";
                                        break;
                                    case 'K':
                                        $distance .= " km";
                                        break;
                                    case 'N':
                                        $distance .= " nmi";
                                        break;
                                }
                                echo $distance;
                            ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_VUCC_GRIDS != null) { ?>
                    <tr>
                        <td>Gridsquare (Multi):</td>
                        <td><?php echo $row->COL_VUCC_GRIDS; ?> <a href="javascript:spawnQrbCalculator('<?php echo $row->station_gridsquare . '\',\'' . $row->COL_VUCC_GRIDS; ?>')"><i class="fas fa-globe"></i></a></td>
                            <?php
                                // Cacluate Distance
                                $distance = $this->qra->distance($row->station_gridsquare, $row->COL_VUCC_GRIDS, $measurement_base);

                                switch ($measurement_base) {
                                    case 'M':
                                        $distance .= " mi";
                                        break;
                                    case 'K':
                                        $distance .= " km";
                                        break;
                                    case 'N':
                                        $distance .= " nmi";
                                        break;
                                }
                                echo $distance;
                            ?>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_STATE != null) { ?>
                    <tr>
                        <td><?php echo $primary_subdivision ?>:</td>
                        <td><?php echo $row->COL_STATE; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CNTY != null && $row->COL_CNTY != ",") { ?>
                    <tr>
                        <td><?php echo $secondary_subdivision ?>:</td>
                        <td><?php echo $row->COL_CNTY; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_NAME != null) { ?>
                    <tr>
                        <td><?php echo lang('general_word_name'); ?></td>
                        <td><?php echo $row->COL_NAME; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                    <?php if($row->COL_COMMENT != null) { ?>
                    <tr>
                        <td><?php echo lang('general_word_comment'); ?></td>
                        <td><?php echo $row->COL_COMMENT; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>

                    <?php if($row->COL_SAT_NAME != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_satellite_name'); ?></td>
                        <td><a href="https://db.satnogs.org/search/?q=<?php echo $row->COL_SAT_NAME; ?>" target="_blank"><?php echo $row->COL_SAT_NAME; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SAT_MODE != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_satellite_mode'); ?></td>
                        <td><?php echo (strlen($row->COL_SAT_MODE) == 2 ? (strtoupper($row->COL_SAT_MODE[0]).'/'.strtoupper($row->COL_SAT_MODE[1])) : strtoupper($row->COL_SAT_MODE)); ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_ANT_AZ != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_ant_az'); ?></td>
                        <td><?php echo $row->COL_ANT_AZ; ?>&deg; <span style="margin-left: 2px; display: inline-block; transform: rotate(<?php echo (-45+$row->COL_ANT_AZ); ?>deg);"><i class="fas fa-location-arrow fa-xs"></i></span></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_ANT_EL != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_ant_el'); ?></td>
                        <td><?php echo $row->COL_ANT_EL; ?>&deg; <span style="margin-left: 2px; display: inline-block; transform: rotate(<?php echo (-$row->COL_ANT_EL); ?>deg);"><i class="fas fa-arrow-right fa-xs"></i></span></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->name != null) { ?>
                    <tr>
                        <td><?php echo lang('general_word_country'); ?></td>
                        <td><?php
						$ci =& get_instance();
						$ci->load->library('DxccFlag');	
						$flag = strtolower($ci->dxccflag->getISO($row->COL_DXCC));
						echo '<span class="fi fi-' . $flag .'"></span> '; 
						echo ucwords(strtolower(($row->name)), "- (/"); if ($row->end != null) { echo ' <span class="badge text-bg-danger">'.lang('gen_hamradio_deleted_dxcc').'</span>'; } ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CONT != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_continent'); ?></td>
                        <td>
                        <?php
                           switch($row->COL_CONT) {
                             case "AF":
                               echo lang('africa');
                               break;
                             case "AN":
                               echo lang('antarctica');
                               break;
                             case "AS":
                               echo lang('asia');
                               break;
                             case "EU":
                               echo lang('europe');
                               break;
                             case "NA":
                               echo lang('northamerica');
                               break;
                             case "OC":
                               echo lang('oceania');
                               break;
                             case "SA":
                               echo lang('southamerica');
                               break;
                           }
                        ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_CONTEST_ID != null) { ?>
                    <tr>
                        <td><?php echo lang('contesting_contest_name'); ?></td>
                        <td><?php echo $row->COL_CONTEST_ID; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_IOTA != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_iota_reference'); ?></td>
                        <td><a href="https://www.iota-world.org/iotamaps/?grpref=<?php echo $row->COL_IOTA; ?>" target="_blank"><?php echo $row->COL_IOTA; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SOTA_REF != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_sota_reference'); ?></td>
                        <td><a href="https://summits.sota.org.uk/summit/<?php echo $row->COL_SOTA_REF; ?>" target="_blank"><?php echo $row->COL_SOTA_REF; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_WWFF_REF != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_wwff_reference'); ?></td>
                        <td><a href="https://www.cqgma.org/zinfo.php?ref=<?php echo $row->COL_WWFF_REF; ?>" target="_blank"><?php echo $row->COL_WWFF_REF; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_POTA_REF != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_pota_reference'); ?></td>
                        <td><a href="https://pota.app/#/park/<?php echo $row->COL_POTA_REF; ?>" target="_blank"><?php echo $row->COL_POTA_REF; ?></a></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_sig'); ?></td>
                        <td><?php echo $row->COL_SIG; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_SIG_INFO != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_sig_info'); ?></td>
                        <?php
                        switch ($row->COL_SIG) {
                        case "GMA":
                           echo "<td><a href=\"https://www.cqgma.org/zinfo.php?ref=".$row->COL_SIG_INFO."\" target=\"_blank\">".$row->COL_SIG_INFO."</a></td>";
                           break;
                        case "MQC":
                           echo "<td><a href=\"https://www.mountainqrp.it/awards/referenza.php?ref=".$row->COL_SIG_INFO."\" target=\"_blank\">".$row->COL_SIG_INFO."</a></td>";
                           break;
                        default:
                           echo "<td>".$row->COL_SIG_INFO."</td>";
                           break;
                        }
                        ?>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_DARC_DOK != null) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_dok'); ?></td>
                        <?php if (preg_match('/^[A-Y]\d{2}$/', $row->COL_DARC_DOK)) { ?>
                        <td><a href="https://www.darc.de/<?php echo $row->COL_DARC_DOK; ?>" target="_blank"><?php echo $row->COL_DARC_DOK; ?></a></td>
                        <?php } else if (preg_match('/^DV[ABCDEFGHIKLMNOPQRSTUVWXY]$/', $row->COL_DARC_DOK)) { ?>
                        <td><a href="https://www.darc.de/der-club/distrikte/<?php echo strtolower(substr($row->COL_DARC_DOK, 2, 1)); ?>" target="_blank"><?php echo $row->COL_DARC_DOK; ?></a></td>
                        <?php } else if (preg_match('/^Z\d{2}$/', $row->COL_DARC_DOK)) { ?>
                        <td><a href="https://<?php echo $row->COL_DARC_DOK; ?>.vfdb.org" target="_blank"><?php echo $row->COL_DARC_DOK; ?></a></td>
                        <?php } else { ?>
                        <td><?php echo $row->COL_DARC_DOK; ?></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>

                </table>
                <?php if($row->COL_QSL_SENT == "Y" || $row->COL_QSL_RCVD == "Y") { ?>
                    <h3><?php echo lang('qslcard_info'); ?></h3>

                    <?php if($row->COL_QSL_SENT == "Y") {?>
                        <?php if ($row->COL_QSL_SENT_VIA == "B") { ?>
                            <p><?php echo lang('qslcard_sent_bureau'); ?>
                        <?php } else if($row->COL_QSL_SENT_VIA == "D") { ?>
                            <p><?php echo lang('qslcard_sent_direct'); ?>
                        <?php } else if($row->COL_QSL_SENT_VIA == "E") { ?>
                            <p><?php echo lang('qslcard_sent_electronic'); ?>
                        <?php } else if($row->COL_QSL_SENT_VIA == "M") { ?>
                            <p><?php echo lang('qslcard_sent_manager'); ?>
                        <?php } else { ?>
                            <p><?php echo lang('qslcard_sent'); ?>
                        <?php } ?>
                        <?php if ($row->COL_QSLSDATE != null) { ?>
                            <?php $timestamp = strtotime($row->COL_QSLSDATE); echo " (".date($custom_date_format, $timestamp).")"; ?></p>
                        <?php } ?>
                    <?php } ?>

                    <?php if($row->COL_QSL_RCVD == "Y") { ?>
                        <?php if ($row->COL_QSL_RCVD_VIA == "B") { ?>
                            <p><?php echo lang('qslcard_rcvd_bureau'); ?>
                        <?php } else if($row->COL_QSL_RCVD_VIA == "D") { ?>
                            <p><?php echo lang('qslcard_rcvd_direct'); ?>
                        <?php } else if($row->COL_QSL_RCVD_VIA == "E") { ?>
                            <p><?php echo lang('qslcard_rcvd_electronic'); ?>
                        <?php } else if($row->COL_QSL_RCVD_VIA == "M") { ?>
                            <p><?php echo lang('qslcard_rcvd_manager'); ?>
                        <?php } else { ?>
                            <p><?php echo lang('qslcard_rcvd'); ?>
                        <?php } ?>
                        <?php if ($row->COL_QSLRDATE != null) { ?>
                            <?php $timestamp = strtotime($row->COL_QSLRDATE); echo " (".date($custom_date_format, $timestamp).")"; ?></p>
                        <?php } ?>
                    <?php } ?>

                <?php } ?>
                    <?php if($row->lotwuser != null) { ?>
                    <br /><p><?php echo lang('lotw_user'); ?> <a href="https://lotw.arrl.org/lotwuser/act?act=<?php echo $row->COL_CALL;?>" target="_blank"><?php echo lang('lotw_last_upload').'</a>: '; ?><?php $timestamp = strtotime($row->lastupload); echo date($custom_date_format, $timestamp); $timestamp = strtotime($row->lastupload); echo " ".date('H:i', $timestamp);?> UTC.</p>
                    <?php } ?>

                    <?php if($row->COL_LOTW_QSL_RCVD == "Y" && $row->COL_LOTW_QSLRDATE != null) { ?>
                    <h3><?php echo lang('lotw_short'); ?></h3>
                    <p><?php echo lang('gen_this_qso_was_confirmed_on'); ?> <?php $timestamp = strtotime($row->COL_LOTW_QSLRDATE); echo date($custom_date_format, $timestamp); ?>.</p>
                    <?php } ?>

                    <?php if($row->COL_EQSL_QSL_RCVD == "Y" && $row->COL_EQSL_QSLRDATE != null) { ?>
                    <h3>eQSL</h3>
                        <p><?php echo lang('gen_this_qso_was_confirmed_on'); ?> <?php $timestamp = strtotime($row->COL_EQSL_QSLRDATE); echo date($custom_date_format, $timestamp); ?>.</p>
                    <?php } ?>
            </div>

                <div class="col-md">

                    <div id="mapqso" class="map-leaflet" style="width: 100%; height: 250px"></div>

                    <?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
                        <br>
                            <div style="display: inline-block;"><p class="editButton"><a class="btn btn-primary" href="<?php echo site_url('qso/edit'); ?>/<?php echo $row->COL_PRIMARY_KEY; ?>" href="javascript:;"><i class="fas fa-edit"></i> <?php echo lang('qso_btn_edit_qso'); ?></a></p></div>
                            <div style="display: inline-block;"><form method="POST" action="<?php echo site_url('search'); ?>"><input type="hidden" value="<?php echo strtoupper($row->COL_CALL); ?>" name="callsign"><button class="btn btn-primary" type="submit"><i class="fas fa-eye"></i> <?php echo lang('general_more_qso'); ?></button></form></div>
                    <?php } ?>

                    <?php

                        if($row->COL_SAT_NAME != null) {
                            $twitter_band_sat = $row->COL_SAT_NAME." \u{1F6F0}\u{FE0F}";
                            $hashtags = "#hamr #cloudlog #amsat";
                        } else {
                            $twitter_band_sat = $row->COL_BAND;
                            $hashtags = "#hamr #cloudlog";
                        }
                        if($row->COL_IOTA != null) {
                            $hashtags .= " #IOTA ".$row->COL_IOTA;
                        }
                        if($row->COL_SOTA_REF != null) {
                            $hashtags .= " #SOTA ".$row->COL_SOTA_REF;
                        }
                        if($row->COL_POTA_REF != null) {
                            $hashtags .= " #POTA ".$row->COL_POTA_REF;
                        }
                        if($row->COL_WWFF_REF != null) {
                            $hashtags .= " #WWFF ".$row->COL_WWFF_REF;
                        }
                        if($row->COL_SIG != null && $row->COL_SIG_INFO != null) {
                            $hashtags .= " #".$row->COL_SIG." ".$row->COL_SIG_INFO;
                        }
                        if (!isset($distance)) {
                            $twitter_string = urlencode("Just worked ".$row->COL_CALL." ");
                            if ($row->COL_DXCC != 0) {
                               $twitter_string .= urlencode("in ".ucwords(strtolower(($row->COL_COUNTRY)))." ");
                            }
                            $twitter_string .= urlencode("on ".$twitter_band_sat." using ".($row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE)." ".$hashtags);
                        } else {
                            $twitter_string = urlencode("Just worked ".$row->COL_CALL." ");
                            if ($row->COL_DXCC != 0) {
                               $twitter_string .= urlencode("in ".ucwords(strtolower(($row->COL_COUNTRY)))." ");
                               if (isset($dxccFlag)) {
                                  $twitter_string .= $dxccFlag." ";
                               }
                            }
                            $distancestring = '';
                            if ($row->COL_VUCC_GRIDS == null) {
                               $distancestring = "(Gridsquare: ".$row->COL_GRIDSQUARE." / distance: ".$distance.")";
                            } else {
                               if (substr_count($row->COL_VUCC_GRIDS, ',') == 1) {
                                  $distancestring = "(Gridline: ".preg_replace('/\s+/', '', $row->COL_VUCC_GRIDS)." / distance: ".$distance.")";
                               } else if (substr_count($row->COL_VUCC_GRIDS, ',') == 3) {
                                  $distancestring = "(Gridcorner: ".preg_replace('/\s+/', '', $row->COL_VUCC_GRIDS)." / distance: ".$distance.")";
                               } else {
                                  $distancestring = "(Grids: ".$row->COL_VUCC_GRIDS.")";
                               }
                            }
                            $twitter_string .= urlencode($distancestring." on ".$twitter_band_sat." using ".($row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE)." ".$hashtags);
                        }
                    ?>

                    <div style="display: inline-block;"><a class="btn btn-primary twitter-share-button" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $twitter_string; ?>"><i class="fab fa-twitter"></i> Tweet</a></div>
                    <?php if($this->session->userdata('user_mastodon_url') != null) { echo '<div style="display: inline-block;"><a class="btn btn-primary twitter-share-button" target="_blank" href="'.$this->session->userdata('user_mastodon_url').'/share?text='.$twitter_string.'"><i class="fab fa-mastodon"></i> Toot</a></div>'; } ?>

                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="stationdetails" role="tabpanel" aria-labelledby="table-tab">
            <h3><?php echo lang('gen_hamradio_station') . ' ' . lang('general_word_details'); ?></h3>

            <table width="100%">
                    <tr>
                        <td><?php echo lang('gen_hamradio_callsign'); ?></td>
                        <td><?php echo $row->station_callsign; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('general_word_name'); ?></td>
                        <td><?php echo $row->station_profile_name; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo lang('gen_hamradio_gridsquare'); ?></td>
                        <td><?php echo $row->station_gridsquare; ?></td>
                    </tr>

                    <?php if($row->station_city) { ?>
                    <tr>
                        <td><?php echo lang('general_word_city'); ?></td>
                        <td><?php echo $row->station_city; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->station_country) { ?>
                    <tr>
                        <td><?php echo lang('general_word_country'); ?></td>
                        <td><?php echo ucwords(strtolower(($row->station_country)), "- (/"); if ($row->station_end != null) echo ' <span class="badge text-bg-danger">'.lang('gen_hamradio_deleted_dxcc').'</span>'; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_OPERATOR) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_operator'); ?></td>
                        <td><?php echo $row->COL_OPERATOR; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_TX_PWR) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_transmit_power'); ?></td>
                        <td><?php echo $row->COL_TX_PWR; ?> W</td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_MY_IOTA) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_iota_reference'); ?></td>
                        <td><?php echo $row->COL_MY_IOTA; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_MY_SOTA_REF) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_sota_reference'); ?></td>
                        <td><?php echo $row->COL_MY_SOTA_REF; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_MY_WWFF_REF) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_wwff_reference'); ?></td>
                        <td><?php echo $row->COL_MY_WWFF_REF; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->COL_MY_POTA_REF) { ?>
                    <tr>
                        <td><?php echo lang('gen_hamradio_pota_reference'); ?></td>
                        <td><?php echo $row->COL_MY_POTA_REF; ?></td>
                    </tr>
                    <?php } ?>

                    <?php if($row->station_wab) { ?>
                    <tr>
                        <td>WAB Reference</td>
                        <td><?php echo $row->station_wab; ?></td>
                    </tr>
                    <?php } ?>
            </table>
        </div>

        <div class="tab-pane fade" id="notesdetails" role="tabpanel" aria-labelledby="table-tab">
            <h3><?php echo lang('general_word_notes'); ?></h3>
            <?php if (isset($row->COL_NOTES)) { echo nl2br($row->COL_NOTES); } ?>
        </div>

        <?php
        if (($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) {
        ?>
        <div class="tab-pane fade" id="sstvupload" role="tabpanel" aria-labelledby="table-tab">
            <?php
                if (count($sstvimages) > 0) {
                echo '<table style="width:100%" class="sstvtable table table-sm table-bordered table-hover table-striped table-condensed">
                    <thead>
                    <tr>
                        <th style=\'text-align: center\'>SSTV image file</th>
                        <th style=\'text-align: center\'></th>
                        <th style=\'text-align: center\'></th>
                    </tr>
                    </thead><tbody>';

                    foreach ($sstvimages as $sstv) {
                    echo '<tr>';
                        echo '<td style=\'text-align: center\'>' . $sstv->filename . '</td>';
                        echo '<td id="'.$sstv->id.'" style=\'text-align: center\'><button onclick="deleteSstv('.$sstv->id.')" class="btn btn-sm btn-danger">Delete</button></td>';
                        echo '<td style=\'text-align: center\'><button onclick="viewSstv(\''.$sstv->filename.'\')" class="btn btn-sm btn-success">View</button></td>';
                        echo '</tr>';
                    }

                    echo '</tbody></table>';
                }
            ?>
            <p><div class="alert alert-warning" role="alert"><span class="badge text-bg-warning"><?php echo lang('general_word_warning'); ?></span><?php echo lang('gen_max_file_upload_size'); ?> <?php echo $max_upload; ?>B.</div></p>
            <form class="form" id="sstvinfo" name="sstvinfo" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md">
                            <fieldset>
                                <div class="mb-3">
                                    <label for="sstvimages"><?php echo lang('general_sstv_upload'); ?></label>
                                    <input class="form-control" type="file" id="sstvimages" name="sstvimages[]" accept="image/*" multiple>
                                </div>
                                <input type="hidden" class="form-control" id="qsoinputid" name="qsoid" value="<?php echo $row->COL_PRIMARY_KEY; ?>">
                                <button type="button" onclick="uploadSSTV();" id="button2id"  name="button2id" class="btn btn-primary"><?php echo lang('general_sstv_upload_button'); ?></button>
                            </fieldset>
                    </div>
                </div>
            </form>
        </div>
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

            <p><div class="alert alert-warning" role="alert"><span class="badge text-bg-warning"><?php echo lang('general_word_warning'); ?></span> <?php echo lang('gen_max_file_upload_size'); ?> <?php echo $max_upload; ?>B.</div></p>

            <form class="form" id="fileinfo" name="fileinfo" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md">
                        <fieldset>

                            <div class="mb-3">
                                <label for="qslcardfront"><?php echo lang('qslcard_upload_front'); ?></label>
                                <input class="form-control" type="file" id="qslcardfront" name="qslcardfront" accept="image/*" >
                            </div>

                            <input type="hidden" class="form-control" id="qsoinputid" name="qsoid" value="<?php echo $row->COL_PRIMARY_KEY; ?>">
                            <button type="button" onclick="uploadQsl();" id="button1id"  name="button1id" class="btn btn-primary"><?php echo lang('qslcard_upload_button'); ?></button>

                </div>
                <div class="col-md">
                            <div class="mb-3">
                                <label for="qslcardback"><?php echo lang('qslcard_upload_back'); ?></label>
                                <input class="form-control" type="file" id="qslcardback" name="qslcardback" accept="image/*">
                            </div>

                        </fieldset>
                    </div>
                </div>
            </form>
            <p>
            <div class="row">
                <div class="col-md">
                        <button type="button" onclick="qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B');" id="qslrxb"  name="qslrxb" class="btn btn-sm btn-success ld-ext-right ld-ext-right-r-B"><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_rx_bureau'); ?> <div class="ld ld-ring ld-spin"></div></button>

                        <button type="button" onclick="qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D');" id="qslrxd"  name="qslrxd" class="btn btn-sm btn-success ld-ext-right ld-ext-right-r-D"><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_rx_direct'); ?> <div class="ld ld-ring ld-spin"></div></button>

                        <button type="button" onclick="qsl_rcvd(<?php echo $row->COL_PRIMARY_KEY; ?>, 'E');" id="qslrxe"  name="qslrxe" class="btn btn-sm btn-success ld-ext-right ld-ext-right-r-E"><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_rx_electronic'); ?> <div class="ld ld-ring ld-spin"></div></button>
                </div>
            </div>
            <p>
            <div class="row">
                <div class="col-md">
                        <button type="button" onclick="qsl_requested(<?php echo $row->COL_PRIMARY_KEY; ?>, 'B');" id="qsltxb"  name="qsltxb" class="btn btn-sm btn-warning ld-ext-right ld-ext-right-t-B"><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_requested_bureau'); ?> <div class="ld ld-ring ld-spin"></div></button>

                        <button type="button" onclick="qsl_requested(<?php echo $row->COL_PRIMARY_KEY; ?>, 'D');" id="qsltxd"  name="qsltxd" class="btn btn-sm btn-warning ld-ext-right ld-ext-right-t-D"><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_requested_direct'); ?> <div class="ld ld-ring ld-spin"></div></button>

                        <button type="button" onclick="qsl_ignore(<?php echo $row->COL_PRIMARY_KEY; ?>, 'I');" id="qsltxi"  name="qsltxi" class="btn btn-sm btn-warning ld-ext-right ld-ext-right-ignore"><i class="fas fa-envelope"></i> <?php echo lang('general_mark_qsl_not_required'); ?> <div class="ld ld-ring ld-spin"></div></button>

                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="qslcard" role="tabpanel" aria-labelledby="table-tab">
            <?php $this->load->view('qslcard/qslcarousel', $qslimages); ?>
        </div>

        
        <div class="tab-pane fade" id="sstvimage" role="tabpanel" aria-labelledby="table-tab">
            <?php $this->load->view('sstv/sstvcarousel', $sstvimages); ?>
        </div>

        <div class="tab-pane fade" id="eqslcard" role="tabpanel" aria-labelledby="table-tab">
        <?php
	if ($row->eqsl_image_file != null) {
		echo '<img class="d-block" src="' . base_url() . '/images/eqsl_card_images/' . $row->eqsl_image_file .'" alt="eQSL picture">';
	}
        ?>
        </div>
        <?php
        }
        ?>
</div>
</div>

<?php
	if($row->COL_GRIDSQUARE != null && strlen($row->COL_GRIDSQUARE) >= 4) {
		$stn_loc = $this->qra->qra2latlong(trim($row->COL_GRIDSQUARE));	
        if($stn_loc[0] != 0) {
		    $lat = $stn_loc[0];
		    $lng = $stn_loc[1];
        }
    } elseif($row->COL_VUCC_GRIDS != null) {
        $grids = explode(",", $row->COL_VUCC_GRIDS);
        if (count($grids) == 2) {
            $grid1 = $this->qra->qra2latlong(trim($grids[0]));
            $grid2 = $this->qra->qra2latlong(trim($grids[1]));

            $coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
            $coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);    

            $midpoint = $this->qra->get_midpoint($coords);
            $lat = $midpoint[0];
		    $lng = $midpoint[1];
        }
        if (count($grids) == 4) {
            $grid1 = $this->qra->qra2latlong(trim($grids[0]));
            $grid2 = $this->qra->qra2latlong(trim($grids[1]));
            $grid3 = $this->qra->qra2latlong(trim($grids[2]));
            $grid4 = $this->qra->qra2latlong(trim($grids[3]));

            $coords[]=array('lat' => $grid1[0],'lng'=> $grid1[1]);
            $coords[]=array('lat' => $grid2[0],'lng'=> $grid2[1]);    
            $coords[]=array('lat' => $grid3[0],'lng'=> $grid3[1]);    
            $coords[]=array('lat' => $grid4[0],'lng'=> $grid4[1]);    

            $midpoint = $this->qra->get_midpoint($coords);
            $lat = $midpoint[0];
		    $lng = $midpoint[1];
        }
	} else {
        if(isset($row->lat)) {
			$lat = $row->lat;
        } else {
            $lat = 0;
        }

        if(isset($row->long)) {
			$lng = $row->long;
        } else {
            $lng = 0;
        }
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