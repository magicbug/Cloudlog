<?php
	header('Content-Type: text/plain; charset=utf-8');
?>
<TQSL_IDENT:54>TQSL V2.5.4 Lib: V2.5 Config: V11.12 AllowDupes: false

<Rec_Type:5>tCERT
<CERT_UID:1>1
<CERTIFICATE:<?php echo strlen($lotw_cert_info->cert_key); ?>><?php echo $lotw_cert_info->cert_key; ?>
<eor>

<Rec_Type:8>tSTATION
<STATION_UID:1>1
<CERT_UID:1>1
<CALL:<?php echo strlen($lotw_cert_info->callsign); ?>><?php echo $lotw_cert_info->callsign; ?>

<DXCC:<?php echo strlen($station_profile_dxcc->adif); ?>><?php echo $station_profile_dxcc->adif; ?>

<GRIDSQUARE:<?php echo strlen($station_profile->station_gridsquare); ?>><?php echo $station_profile->station_gridsquare; ?>

<?php if(isset($station_profile->station_itu)) { ?><ITUZ:<?php echo strlen($station_profile->station_itu); ?>><?php echo $station_profile->station_itu; ?><?php } ?>

<?php if(isset($station_profile->station_cq)) { ?><CQZ:<?php echo strlen($station_profile->station_cq); ?>><?php echo $station_profile->station_cq; ?><?php } ?>

<?php if(isset($station_profile->station_iota)) { ?><IOTA:<?php echo strlen($station_profile->station_iota); ?>><?php echo $station_profile->station_iota; ?><?php } ?>

<eor>