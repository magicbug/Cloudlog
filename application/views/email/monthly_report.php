<?php
$lines = array();
$lines[] = 'Hi '.($user_firstname != '' ? $user_firstname : strtoupper($user_callsign)).',';
$lines[] = '';
$lines[] = 'Here is your Cloudlog activity for '.$month_name.' '.$year.' in the logbook "'.$logbook_name.'".';
$lines[] = 'Dates and times are UTC.';
$lines[] = '';
$lines[] = 'SUMMARY';
$lines[] = '-------';
$lines[] = 'QSOs: '.$report['total_qsos'];
$lines[] = 'Unique callsigns: '.$report['unique_callsigns'];
$lines[] = 'Countries worked: '.count($report['countries_worked']);
$lines[] = 'New DXCC: '.count($report['new_dxcc']);
$lines[] = 'New gridsquares: '.count($report['new_grids']);
if ($report['top_band'] != '') {
	$lines[] = 'Most active band: '.$report['top_band'];
}
if ($report['top_mode'] != '') {
	$lines[] = 'Most used mode: '.$report['top_mode'];
}
if ($report['satellite_qsos'] > 0) {
	$lines[] = 'Satellite QSOs: '.$report['satellite_qsos'];
}
if ($report['eme_qsos'] > 0) {
	$lines[] = 'EME QSOs: '.$report['eme_qsos'];
}

if (count($report['new_dxcc']) > 0) {
	$lines[] = '';
	$lines[] = 'NEW DXCC';
	$lines[] = '--------';
	foreach (array_slice($report['new_dxcc'], 0, $list_cap) as $dxcc) {
		$lines[] = '- '.$dxcc['name'];
	}
	if (count($report['new_dxcc']) > $list_cap) {
		$lines[] = '...and '.(count($report['new_dxcc']) - $list_cap).' more';
	}
}

if (count($report['new_grids']) > 0) {
	$lines[] = '';
	$lines[] = 'NEW GRIDSQUARES';
	$lines[] = '---------------';
	$grids = array();
	foreach (array_slice($report['new_grids'], 0, $list_cap) as $grid) {
		$grids[] = $grid['grid'];
	}
	$lines[] = wordwrap(implode(', ', $grids), 72);
	if (count($report['new_grids']) > $list_cap) {
		$lines[] = '...and '.(count($report['new_grids']) - $list_cap).' more';
	}
}

if (!empty($report['bands'])) {
	$lines[] = '';
	$lines[] = 'BANDS';
	$lines[] = '-----';
	foreach ($report['bands'] as $band => $count) {
		$lines[] = sprintf('%-10s %d', $band, $count);
	}
}

if (!empty($report['modes'])) {
	$lines[] = '';
	$lines[] = 'MODES';
	$lines[] = '-----';
	foreach ($report['modes'] as $mode => $count) {
		$lines[] = sprintf('%-10s %d', $mode, $count);
	}
}

$lines[] = '';
$lines[] = 'The full report, with exports, is on the Monthly Report page:';
$lines[] = $report_url;
$lines[] = '';
$lines[] = 'You get this email because monthly report emails are turned on for your account.';
$lines[] = 'You can turn them off on the same page.';
$lines[] = '';
$lines[] = 'Regards,';
$lines[] = '';
$lines[] = 'Cloudlog.';

echo implode("\n", $lines)."\n";
