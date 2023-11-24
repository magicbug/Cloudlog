<?php
	header('Content-Type: text/plain; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.$this->session->userdata('user_callsign').'-SOTA-'.date('Ymd-Hi').'.csv"');
$CI =& get_instance();
$bands = array(
   "2190m"  => "VLF",
   "560m"   => "VLF",
   "160m"   => "1.8MHz",
   "80m"    => "3.5MHz",
   "60m"    => "5MHz",
   "40m"    => "7MHz",
   "30m"    => "10MHz",
   "20m"    => "14MHz",
   "17m"    => "18MHz",
   "15m"    => "21MHz",
   "12m"    => "24MHz",
   "10m"    => "28MHz",
   "6m"     => "50MHz",
   "4m"     => "70MHz",
   "2m"     => "144MHz",
   "1.25m"  => "220MHz",
   "70cm"   => "433MHz",
   "33cm"   => "900MHz",
   "23cm"   => "1240MHz",
   "13cm"   => "2.3GHz",
   "9cm"    => "3.4GHz",
   "6cm"    => "5.6GHz",
   "3cm"    => "10GHz",
   "1.25cm" => "24GHz",
   "6mm"    => "MICROWAVE",
   "4mm"    => "MICROWAVE",
   "2.5mm"  => "MICROWAVE",
   "2mm"    => "MICROWAVE",
   "1mm"    => "MICROWAVE"
);
foreach ($qsos as $qso) {
   $timestamp = strtotime($qso['COL_TIME_ON']);
   printf("V2,%s,%s,%s,%s,%s,%s,%s,%s,\"%s\"\n",
      $qso['station_callsign'],
      $qso['COL_MY_SOTA_REF'],
      date('d/m/y', $timestamp),
      date('Hi', $timestamp),
      $bands[$qso['COL_BAND']],
      $qso['COL_MODE'],
      $qso['COL_CALL'],
      $qso['COL_SOTA_REF'],
      $qso['COL_COMMENT']
   );
}
?>
