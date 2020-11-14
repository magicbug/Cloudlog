<?php
	header('Content-Type: text/plain; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.$this->session->userdata('user_callsign').'-'.date('dmY-Hi').'.adi"')
?>
<ADIF_VERS:5>3.1.0
<PROGRAMID:<?php echo strlen($this->config->item('app_name')); ?>><?php echo $this->config->item('app_name')."\n"; ?>
<PROGRAMVERSION:<?php echo strlen($this->config->item('app_version')); ?>>Version <?php echo $this->config->item('app_version')."\n"; ?>
<EOH>

<?php
$CI =& get_instance();
$CI->load->library('adifhelper');

foreach ($qsos->result() as $qso) {
    echo $CI->adifhelper->getAdifLine($qso);
}

