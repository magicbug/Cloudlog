<?php
$CI =& get_instance(); 
$clean_cert = trim($lotw_cert_info->cert);
$cert1 = str_replace("-----BEGIN CERTIFICATE-----", "", $clean_cert);
$cert2 = str_replace("-----END CERTIFICATE-----", "", $cert1);
?>
<TQSL_IDENT:54>TQSL V2.5.4 Lib: V2.5 Config: V11.12 AllowDupes: false

<Rec_Type:5>tCERT
<CERT_UID:1>1
<CERTIFICATE:<?php echo strlen(trim($cert2)) + 1; ?>><?php echo trim($cert2); ?>

<EOR>

<Rec_Type:8>tSTATION
<STATION_UID:1>1
<CERT_UID:1>1
<CALL:<?php echo strlen($lotw_cert_info->callsign); ?>><?php echo $lotw_cert_info->callsign; ?>

<DXCC:<?php echo strlen($lotw_cert_info->cert_dxcc_id); ?>><?php echo $lotw_cert_info->cert_dxcc_id; ?>

<?php if(isset($station_profile->station_gridsquare)) { ?><GRIDSQUARE:<?php echo strlen($station_profile->station_gridsquare); ?>><?php echo $station_profile->station_gridsquare; ?><?php } ?>

<?php if(isset($station_profile->station_itu)) { ?><ITUZ:<?php echo strlen($station_profile->station_itu); ?>><?php echo $station_profile->station_itu; ?><?php } ?>

<?php if(isset($station_profile->station_cq)) { ?><CQZ:<?php echo strlen($station_profile->station_cq); ?>><?php echo $station_profile->station_cq; ?><?php } ?>

<?php if(isset($station_profile->station_iota)) { ?><IOTA:<?php echo strlen($station_profile->station_iota); ?>><?php echo $station_profile->station_iota; ?><?php } ?>

<?php if($station_profile->state != "" && $station_profile->station_country == "CANADA") { ?><CA_PROVINCE:<?php echo strlen($CI->lotw_ca_province_map($station_profile->state)); ?>><?php echo $CI->lotw_ca_province_map($station_profile->state); ?><?php } ?>

<?php if($station_profile->state != "" && $station_profile->station_country == "UNITED STATES OF AMERICA") { ?><US_STATE:<?php echo strlen($station_profile->state); ?>><?php echo $station_profile->state; ?><?php } ?>

<?php if($station_profile->station_cnty != ""  && $station_profile->station_country == "UNITED STATES OF AMERICA") { ?><US_COUNTY:<?php echo strlen($station_profile->station_cnty); ?>><?php echo $station_profile->station_cnty; ?><?php } ?>

<EOR>

<?php foreach ($qsos->result() as $qso) { ?>
<Rec_Type:8>tCONTACT
<STATION_UID:1>1
<CALL:<?php echo strlen($qso->COL_CALL); ?>><?php echo $qso->COL_CALL; ?>

<BAND:<?php echo strlen($qso->COL_BAND); ?>><?php echo strtoupper($qso->COL_BAND); ?>

<MODE:<?php echo strlen($CI->mode_map($qso->COL_MODE, $qso->COL_SUBMODE)); ?>><?php echo strtoupper($CI->mode_map(($qso->COL_MODE == null ? '' : strtoupper($qso->COL_MODE)), ($qso->COL_SUBMODE == null ? '' : strtoupper($qso->COL_SUBMODE)))); ?>

<?php if($qso->COL_FREQ != "" && $qso->COL_FREQ != "0") { ?><?php $freq_in_mhz = $qso->COL_FREQ / 1000000; ?><FREQ:<?php echo strlen($freq_in_mhz); ?>><?php echo $freq_in_mhz; ?><?php } ?>

<?php if($qso->COL_FREQ_RX != "" && $qso->COL_FREQ_RX != "0") { ?><?php $freq_in_mhz_rx = $qso->COL_FREQ_RX / 1000000; ?><FREQ_RX:<?php echo strlen($freq_in_mhz_rx); ?>><?php echo $freq_in_mhz_rx; ?><?php } ?>

<?php if($qso->COL_PROP_MODE) { ?><PROP_MODE:<?php echo strlen($qso->COL_PROP_MODE); ?>><?php echo strtoupper($qso->COL_PROP_MODE); ?><?php } ?>

<?php if($qso->COL_SAT_NAME) { $satellite_name_check = $CI->lotw_satellite_map(strtoupper($qso->COL_SAT_NAME)); if($satellite_name_check != FALSE) { $satname = $satellite_name_check; } else { $satname = $qso->COL_SAT_NAME; } ?>
<SAT_NAME:<?php echo strlen($satname); ?>><?php echo strtoupper($satname); ?><?php } ?>

<?php if($qso->COL_BAND_RX) { ?><BAND_RX:<?php echo strlen($qso->COL_BAND_RX); ?>><?php echo strtoupper($qso->COL_BAND_RX); ?><?php } ?>

<?php $date_on = strtotime($qso->COL_TIME_ON); $new_date = date('Y-m-d', $date_on); ?>
<QSO_DATE:<?php echo strlen($new_date); ?>><?php echo $new_date; ?>

<?php $time_on = strtotime($qso->COL_TIME_ON); $new_on = date('H:i:s', $time_on); ?>
<QSO_TIME:<?php echo strlen($new_on."Z"); ?>><?php echo $new_on."Z"; ?>

<?php 


$sign_string = "";

// Adds CA Prov
if($station_profile->state != "" && $station_profile->station_country == "CANADA") {
	$sign_string .= strtoupper($station_profile->state);
}

// Add CQ Zone
if($station_profile->station_cq) {
	$sign_string .= $station_profile->station_cq;
}

// Add Gridsquare
if($station_profile->station_gridsquare) {
	$sign_string .= strtoupper($station_profile->station_gridsquare);
}

if($station_profile->station_iota) {
	$sign_string .= strtoupper($station_profile->station_iota);
}

if($station_profile->station_itu) {
	$sign_string .= $station_profile->station_itu;
}

if($station_profile->station_cnty != "" && $station_profile->station_country == "UNITED STATES OF AMERICA") {
	$sign_string .= strtoupper($station_profile->station_cnty);
}

if($station_profile->station_cnty != "" && $station_profile->station_country == "ALASKA") {
	$sign_string .= strtoupper($station_profile->station_cnty);
}

if($station_profile->station_cnty != "" && $station_profile->station_country == "HAWAII") {
	$sign_string .= strtoupper($station_profile->station_cnty);
}

if($station_profile->state != "" && $station_profile->station_country == "UNITED STATES OF AMERICA") {
	$sign_string .= strtoupper($station_profile->state);
}

if($station_profile->state != "" && $station_profile->station_country == "ALASKA") {
	$sign_string .= strtoupper($station_profile->state);
}

if($station_profile->state != "" && $station_profile->station_country == "HAWAII") {
	$sign_string .= strtoupper($station_profile->state);
}

if($qso->COL_BAND) {
	$sign_string .= strtoupper($qso->COL_BAND);
}

if($qso->COL_BAND_RX) {
	$sign_string .= strtoupper($qso->COL_BAND_RX);
}

if($qso->COL_CALL) {
	$sign_string .= strtoupper($qso->COL_CALL);
}

if($freq_in_mhz) {
	$sign_string .= strtoupper($freq_in_mhz);
}

if($qso->COL_FREQ_RX != "" && $qso->COL_FREQ_RX != "0") {
	$sign_string .= strtoupper($freq_in_mhz_rx);
}

if($qso->COL_MODE) {
	$sign_string .= strtoupper($CI->mode_map($qso->COL_MODE, $qso->COL_SUBMODE));
}


if($qso->COL_PROP_MODE) {
	$sign_string .= strtoupper($qso->COL_PROP_MODE);
}

$sign_string .= $new_date;

$sign_string .= $new_on."Z";

if($qso->COL_SAT_NAME) {
	$satellite_name_check = $CI->lotw_satellite_map(strtoupper($qso->COL_SAT_NAME)); if($satellite_name_check != FALSE) { $satname = $satellite_name_check; } else { $satname = $qso->COL_SAT_NAME; }

	$sign_string .= strtoupper($satname);
}

 ?>
<?php 
    $signed_item = $CI->signlog($lotw_cert_info->cert_key, $sign_string);
?>
<SIGN_LOTW_V2.0:<?php echo strlen($signed_item)+1; ?>:6><?php echo $signed_item; ?>

<SIGNDATA:<?php echo strlen($sign_string); ?>><?php echo $sign_string; ?>

<EOR>

<?php } ?>

