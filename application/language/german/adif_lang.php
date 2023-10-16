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
$lang['adif_alert_log_files_type'] = "Die Log Datei muss im *.adi Format vorliegen.";
// $lang['general_word_warning']            --> application/language/english/general_words_lang.php "PHP Upload Warning"
// $lang['gen_max_file_upload_size']        --> application/language/english/general_words_lang.php "PHP Upload Warning"

$lang['adif_select_stationlocation'] = "Wähle Stationsstandort";
// $lang['gen_hamradio_callsign']           --> application/language/english/general_words_lang.php

// The File Input is translated by the Browser
$lang['aif_file_label'] = "ADIF Datei";

$lang['adif_hint_no_info_in_file'] ="Wähle dies aus, wenn die hochgeladene ADIF-Datei diese Information nicht enthält.";

$lang['adif_import_dup'] = "Doppelte QSO hochladen";
$lang['adif_mark_imported_lotw'] = "Markiere hochgeladene QSO als bereits zu LoTW hochgeladen";
$lang['adif_mark_imported_hrdlog'] = "Markiere hochgeladene QSO als bereits zu HRDlog.net hochgeladen";
$lang['adif_mark_imported_qrz'] = "Markiere hochgeladene QSO als bereits zu QRZ.com hochgeladen";
$lang['adif_mark_imported_clublog'] = "Markiere hochgeladene QSO als bereits zu Clublog hochgeladen";

$lang['adif_dxcc_from_adif'] = "Benutze die DXCC Informationen aus der ADIF Datei";
$lang['adif_dxcc_from_adif_hint'] = "Wenn diese Option nicht ausgewählt ist, wird Cloudlog versuchen die DXCC Informationen automatisch zu ermitteln.";

$lang['adif_always_use_login_call_as_op'] = "Nutze während des Import immer das eingeloggte Rufzeichen als Operator-Name";

$lang['adif_ignore_station_call'] = "Ignoriere das Stations Rufzeichen beim Import";
$lang['adif_ignore_station_call_hint'] = "Wenn diese Option ausgewählt ist, wirdCloudlog versuchen <b>alle</b> QSO hochzuladen, unabhängig davon, ob sie mit dem aktiven Stationsstandort zusammenpassen.";

$lang['adif_upload'] = "Hochladen";

/*
___________________________________________________________________________________________
ADIF Export
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "Exportiere deine Logs überall hin!";
$lang['adif_export_take_it_anywhere_hint'] = "Deine ADIF Logbücher zu exportieren bietet dir die Möglichkeite diese zum Beispiel in Drittanbieter Software (z.B. LoTW) einzubinden oder sie einfach nur als Backup zu speichern.";


$lang['adif_mark_exported_lotw'] = "Markiere die exportierten QSO als 'zu LoTW hochgeladen'";
$lang['adif_mark_exported_no_lotw'] = "Markiere die exportierten QSO als 'zu LoTW nicht hochgeladen'";

$lang['adif_export_qso'] = "Exportiere QSO's";

$lang['adif_export_sat_only_qso'] = "Exportiere nur Satelliten QSO";
$lang['adif_export_sat_only_qso_all'] = "Exportiere ALLE Satelliten QSO";
$lang['adif_export_sat_only_qso_lotw'] = "Exportiere nur die Satelliten QSO, welche auf LoTW bestätigt sind.";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "Wenn kein Datum gewählt ist, werden alle QSO markiert!";
$lang['adif_mark_qso_as_exported_to_lotw'] = "Markiere die QSO als 'zu LoTW hochgeladen'";

$lang['adif_qso_marked'] = "QSO's markiert";
$lang['adif_yay_its_done'] = "Yay, geschafft!";
$lang['adif_qso_lotw_marked_confirm'] = "Die QSO wurden als 'zu LoTW hochgeladen' markiert";


/*
___________________________________________________________________________________________
Import Success
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF Importiert";
$lang['adif_yay_its_imported'] = "Yay, Datei importiert!";
$lang['adif_import_confirm'] = "Die ADIF Datei wurde importiert.";

$lang['adif_import_dupes_inserted'] = " <b>Duplikate wurden ebenfalls importiert!</b>";
$lang['adif_import_dupes_skipped'] = " Duplikate wurden übersprungen.";

$lang['adif_import_errors'] = "ADIF Fehler";
$lang['adif_import_errors_hint'] = "Es gibt ADIF Fehler. Die QSO wurden hinzugefügt, jedoch wurden die fehlerhaften Felder nicht ausgefüllt.";
