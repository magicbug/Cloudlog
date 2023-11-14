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
$lang['darc_dcl'] = "DARC DCL";


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
$lang['adif_file_label'] = "ADIF Datei";

$lang['adif_hint_no_info_in_file'] = "Wähle dies aus, wenn die hochgeladene ADIF-Datei diese Information nicht enthält.";

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
DARC DCL
___________________________________________________________________________________________
*/
$lang['adif_dcl_text_pre'] = "Gehe zum";
$lang['adif_dcl_text_post'] = "und exportiere dein Logbuch mit bestätigten DOKs. Um den Prozess zu beschleunigen, kannst du QSOs ausschließlich mit DL-Stationen auswählen (indem du \"DL\" in die Präfixliste einträgst). Die heruntergeladene ADIF-Datei kannst du hier hochladen, um dein Logbuch mit DOK Informationen zu aktualisieren.";

$lang['only_confirmed_qsos'] = "Importiere nur DOK Informationen von QSOs, die auf DCL bestätigt sind.";
$lang['only_confirmed_qsos_hint'] = "Deaktiviere diese Option, um auch DOK Infos von QSOS zu importieren, die auf DCL nicht bestätig sind.";

$lang['overwrite_by_dcl'] = "Überschreibe existierende DOK im Logbuch durch DCL (wenn unterschiedlich).";
$lang['overwrite_by_dcl_hint'] = "Wenn aktiviert, wird Cloudlog den existierenden DOK mit dem DOK aus dem DCL überschreiben.";

$lang['ignore_ambiguous'] = "Ignoriere QSOs, die nicht eindeutig zugeordnet werden können.";
$lang['ignore_ambiguous_hint'] = "Wenn deaktiviert, werden auch Infos zu QSOs angezeigt, die im Logbuch nicht gefunden werden konnten.";

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

/*
___________________________________________________________________________________________
DCL Success
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Ergebnisse des DCL DOK Updates";
$lang['dcl_info_updated'] = "QSOs wurden mit der DOK Information aus dem DCL aktualisiert.";
$lang['dcl_qsos_updated'] = "Aktualisierte QSOs";
$lang['dcl_qsos_ignored'] = "Ignorierte QSOs";
$lang['dcl_qsos_unmatched'] = "Nicht gefundene QSOs";
$lang['dcl_no_qsos_updated'] = "Keine QSOs gefunden, die aktualisiert werden konnten.";
$lang['dcl_dok_errors'] = "DOK Fehler";
$lang['dcl_dok_errors_details'] = "Die DOK Informationen im Logbuch weichen von denen im DCL ab";
$lang['dcl_qsl_status'] = "DCL QSL Status";
$lang['dcl_qsl_status_c'] = "bestätigt durch LoTW/Clublog/eQSL/Contest";
$lang['dcl_qsl_status_mno'] = "bestätigt durch Diplommananger";
$lang['dcl_qsl_status_i'] = "bestätigt durch Cross-Check von DCL-Daten";
$lang['dcl_qsl_status_w'] = "Bestätigung ausstehend";
$lang['dcl_qsl_status_x'] = "nicht bestätigt";
$lang['dcl_qsl_status_unknown'] = "unbekannt";
$lang['dcl_no_match'] = "QSO konnte nicht gefunden werden";
