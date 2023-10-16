<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['adif_import'] = "ADIF Import";
$lang['adif_export'] = "ADIF Export";
// $lang['lotw_title']                      --> application/language/english/lotw_lang.php


/*
___________________________________________________________________________________________
ADIF Import
___________________________________________________________________________________________
*/

// $lang['general_word_important']           --> application/language/english/general_words_lang.php
$lang['adif_alert_log_files_type'] = "Log Files must have the file type *.adi";
// $lang['general_word_warning']            --> application/language/english/general_words_lang.php "PHP Upload Warning"
// $lang['gen_max_file_upload_size']        --> application/language/english/general_words_lang.php "PHP Upload Warning"

$lang['adif_select_stationlocation'] = "Select Station Location";
// $lang['gen_hamradio_callsign']           --> application/language/english/general_words_lang.php

// The File Input is translated by the Browser
$lang['aif_file_label'] = "ADIF File";

$lang['adif_hint_no_info_in_file'] ="Select if ADIF being imported does not contain this information.";

$lang['adif_import_dup'] = "Import duplicate QSOs";
$lang['adif_mark_imported_lotw'] = "Mark imported QSOs as uploaded to LoTW";
$lang['adif_mark_imported_hrdlog'] = "Mark imported QSOs as uploaded to HRDLog.net Logbook";
$lang['adif_mark_imported_qrz'] = "Mark imported QSOs as uploaded to QRZ Logbook";
$lang['adif_mark_imported_clublog'] = "Mark imported QSOs as uploaded to Clublog Logbook";

$lang['adif_dxcc_from_adif'] = "Use DXCC information from ADIF";
$lang['adif_dxcc_from_adif_hint'] = "If not selected, Cloudlog will attempt to determine DXCC information automatically.";

$lang['adif_always_use_login_call_as_op'] = "Always use login-callsign as operator-name on import";

$lang['adif_ignore_station_call'] = "Ignore Stationcallsign on import";
$lang['adif_ignore_station_call_hint'] = "If selected, Cloudlog will try to import <b>all</b> QSO's of the ADIF, regardless if they match to the chosen station-location.";

$lang['adif_upload'] = "Upload";

/*
___________________________________________________________________________________________
ADIF Export
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "Take your logbook file anywhere!";
$lang['adif_export_take_it_anywhere_hint'] = "Exporting ADIFs allows you to import contacts into third party applications like LoTW, Awards or just for keeping a backup.";


$lang['adif_mark_exported_lotw'] = "Mark exported QSOs as uploaded to LoTW";
$lang['adif_mark_exported_no_lotw'] = "Export QSOs not uploaded to LoTW";

$lang['adif_export_qso'] = "Export QSO's";

$lang['adif_export_sat_only_qso'] = "Export Satellite-Only QSOs";
$lang['adif_export_sat_only_qso_all'] = "Export All Satellite QSOs";
$lang['adif_export_sat_only_qso_lotw'] = "Export All Satellite QSOs Confirmed on LoTW";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "If a date range is not selected then all QSOs will be marked!";
$lang['adif_mark_qso_as_exported_to_lotw'] = "Mark QSOs as exported to LoTW";

$lang['adif_qso_marked'] = "QSOs marked";
$lang['adif_yay_its_done'] = "Yay, its done!";
$lang['adif_qso_lotw_marked_confirm'] = "The QSOs are marked as exported to LoTW.";


/*
___________________________________________________________________________________________
Import Success
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF Imported";
$lang['adif_yay_its_imported'] = "Yay, its imported!";
$lang['adif_import_confirm'] = "The ADIF File has been imported.";

$lang['adif_import_dupes_inserted'] = " <b>Dupes were inserted!</b>";
$lang['adif_import_dupes_skipped'] = " Dupes were skipped.";

$lang['adif_import_errors'] = "ADIF Errors";
$lang['adif_import_errors_hint'] = "You have ADIF errors, the QSOs have still been added but these fields have not been populated.";
