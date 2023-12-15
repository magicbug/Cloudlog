Cloudlog ADIF export
<ADIF_VER:5>3.1.4
<PROGRAMID:<?php echo strlen($this->config->item('app_name')); ?>><?php echo $this->config->item('app_name')."\n"; ?>
<PROGRAMVERSION:<?php echo strlen($this->optionslib->get_option('version')); ?>><?php echo $this->optionslib->get_option('version')."\r\n"; ?>
<EOH>

<?php
$CI =& get_instance();
$CI->load->library('AdifHelper');

foreach ($qsos->result() as $qso) {
    echo $CI->adifhelper->getAdifLine($qso);
}
