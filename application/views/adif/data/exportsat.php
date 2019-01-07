<?php

	header("content-type: plain/text"); 
	header('Content-Disposition: attachment; filename="'.$this->session->userdata('user_callsign').'-'.date('dmY-Hi').'.adi"')
?>
<ADIF_VERS:3>2.2
<PROGRAMID:<?php echo strlen($this->config->item('app_name')); ?>><?php echo $this->config->item('app_name')."\n"; ?>
<PROGRAMVERSION:<?php echo strlen($this->config->item('app_version')); ?>>Version <?php echo $this->config->item('app_version')."\n"; ?>
<EOH>

<?php foreach ($qsos->result() as $qso) { //print_r($qso);?>
	<?php if($qso->COL_SAT_NAME) { ?>
	<call:<?php echo strlen($qso->COL_CALL); ?>><?php echo $qso->COL_CALL; ?><band:<?php echo strlen($qso->COL_BAND); ?>><?php echo $qso->COL_BAND; ?><mode:<?php echo strlen($qso->COL_MODE); ?>><?php echo $qso->COL_MODE; ?><?php if($qso->COL_FREQ != "0") { ?><?php $freq_in_mhz = $qso->COL_FREQ / 1000000; ?><freq:<?php echo strlen($freq_in_mhz); ?>><?php echo $freq_in_mhz; ?><?php } ?><?php $date_on = strtotime($qso->COL_TIME_ON); $new_date = date('Ymd', $date_on); ?><qso_date:<?php echo strlen($new_date); ?>><?php echo $new_date; ?><?php $time_on = strtotime($qso->COL_TIME_ON); $new_on = date('His', $time_on); ?><time_on:<?php echo strlen($new_on); ?>><?php echo $new_on; ?><?php $time_off = strtotime($qso->COL_TIME_OFF); $new_off = date('His', $time_off); ?><time_off:<?php echo strlen($new_off); ?>><?php echo $new_off; ?><rst_rcvd:<?php echo strlen($qso->COL_RST_RCVD); ?>><?php echo $qso->COL_RST_RCVD; ?><rst_sent:<?php echo strlen($qso->COL_RST_SENT); ?>><?php echo $qso->COL_RST_SENT; ?><qsl_rcvd:<?php echo strlen($qso->COL_QSL_RCVD); ?>><?php echo $qso->COL_QSL_RCVD; ?><qsl_sent:<?php echo strlen($qso->COL_QSL_SENT); ?>><?php echo $qso->COL_QSL_SENT; ?><country:<?php echo strlen($qso->COL_COUNTRY); ?>><?php echo $qso->COL_COUNTRY; ?><?php if($qso->COL_VUCC_GRIDS != "") { ?><vucc_grids:<?php echo strlen($qso->COL_VUCC_GRIDS); ?>><?php echo $qso->COL_VUCC_GRIDS; ?><?php } ?><?php if($qso->COL_VUCC_GRIDS == "" && $qso->COL_GRIDSQUARE != "") { ?><gridsquare:<?php echo strlen($qso->COL_GRIDSQUARE); ?>><?php echo $qso->COL_GRIDSQUARE; ?><?php } ?><?php if($qso->COL_SAT_NAME) { ?><sat_mode:<?php echo strlen($qso->COL_SAT_MODE); ?>><?php echo $qso->COL_SAT_MODE; ?><sat_name:<?php echo strlen($qso->COL_SAT_NAME); ?>><?php echo $qso->COL_SAT_NAME; ?><?php } ?><?php if($qso->COL_PROP_MODE) { ?><prop_mode:<?php echo strlen($qso->COL_PROP_MODE); ?>><?php echo $qso->COL_PROP_MODE; ?><?php } ?><?php if($qso->COL_NAME) { ?><name:<?php echo strlen($qso->COL_NAME); ?>><?php echo $qso->COL_NAME; ?><?php } ?><?php if($qso->COL_COMMENT) { ?><comment:<?php echo strlen($qso->COL_COMMENT); ?>><?php echo $qso->COL_COMMENT; ?><?php } ?><eor>
	<?php } ?>
<?php } ?>