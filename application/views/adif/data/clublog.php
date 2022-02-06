Cloudlog ADIF export
<ADIF_VER:5>3.1.2
<PROGRAMID:<?php echo strlen($this->config->item('app_name')); ?>><?php echo $this->config->item('app_name')."\r\n"; ?>
<PROGRAMVERSION:<?php echo strlen('Version ' . $this->config->item('app_version')); ?>>Version <?php echo $this->config->item('app_version')."\r\n"; ?>
<EOH>

<?php
$CI =& get_instance();
$CI->load->library('AdifHelper');

foreach ($qsos->result() as $qso) {
    echo $CI->adifhelper->getAdifLine($qso);
}
