<?php

defined('BASEPATH') OR exit('Non è consentito l\'accesso diretto allo script');


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
$lang['adif_alert_log_files_type'] = "I file di Log devono essere del tipo *.adi";
// $lang['general_word_warning']            --> application/language/english/general_words_lang.php "PHP Upload Warning"
// $lang['gen_max_file_upload_size']        --> application/language/english/general_words_lang.php "PHP Upload Warning"

$lang['adif_select_stationlocation'] = "Seleziona il luogo della stazione";
// $lang['gen_hamradio_callsign']           --> application/language/english/general_words_lang.php

// The File Input is translated by the Browser
$lang['adif_file_label'] = "File ADIF";

$lang['adif_hint_no_info_in_file'] = "Selezionare se l'ADIF importato non contiene queste informazioni.";

$lang['adif_import_dup'] = "Importa QSO duplicati";
$lang['adif_mark_imported_lotw'] = "Segna i QSO importati come caricati su LoTW";
$lang['adif_mark_imported_hrdlog'] = "Segna i QSO importati come caricati su HRDLog.net";
$lang['adif_mark_imported_qrz'] = "Segna i QSO importati come caricati su QRZ.com";
$lang['adif_mark_imported_clublog'] = "Segna i QSO importati come caricati su Clublog";

$lang['adif_dxcc_from_adif'] = "Utilizza le informazioni DXCC dell'ADIF";
$lang['adif_dxcc_from_adif_hint'] = "Se non selezionato, Cloudlog tenterà di determinare automaticamente le informazioni DXCC.";

$lang['adif_always_use_login_call_as_op'] = "Utilizza sempre il nominativo di login come nome dell'operatore durante l'importazione";

$lang['adif_ignore_station_call'] = "Ignora il nominativo della stazione durante l'importazione";
$lang['adif_ignore_station_call_hint'] = "Se selezionato, Cloudlog tenterà di importare <b>tutti</b> i QSO dell'ADIF, indipendentemente dal fatto che corrispondano al luogo della stazione scelta.";

$lang['adif_upload'] = "Carica";

/*
___________________________________________________________________________________________
ADIF Export
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "Porta il tuo file di Log ovunque!";
$lang['adif_export_take_it_anywhere_hint'] = "L'esportazione di ADIF ti consente di importare contatti in applicazioni di terze parti come LoTW, Awards o semplicemente per conservare un backup.";


$lang['adif_mark_exported_lotw'] = "Segna i QSO esportati come caricati su LoTW";
$lang['adif_mark_exported_no_lotw'] = "Esporta QSO non caricati su LoTW";

$lang['adif_export_qso'] = "Esporta QSO";

$lang['adif_export_sat_only_qso'] = "Esporta solo QSO via satellite";
$lang['adif_export_sat_only_qso_all'] = "Esporta tutti i QSO satellitari";
$lang['adif_export_sat_only_qso_lotw'] = "Esporta tutti i QSO satellitari confermati su LoTW";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "Se non viene selezionato un intervallo di date, tutti i QSO verranno contrassegnati!";
$lang['adif_mark_qso_as_exported_to_lotw'] = "Segna i QSO come esportati su LoTW";

$lang['adif_qso_marked'] = "QSO contrassegnati";
$lang['adif_yay_its_done'] = "Ok, è stato fatto!";
$lang['adif_qso_lotw_marked_confirm'] = "I QSO sono stati contrassegnati come esportati su LoTW.";

/*
___________________________________________________________________________________________
DARC DCL
___________________________________________________________________________________________
*/
$lang['adif_dcl_text_pre'] = "Vai a";
$lang['adif_dcl_text_post'] = "ed esporta il tuo registro con i DOK confermati. Per accelerare il processo puoi selezionare solo i QSO DL da scaricare (ovvero inserisci \"DL\" nell'elenco dei prefissi). Il file ADIF scaricato può essere caricato qui per aggiornare i QSO con le informazioni DOK.";

$lang['only_confirmed_qsos'] = "Importa solo i dati DOK dai QSO confermati su DCL.";
$lang['only_confirmed_qsos_hint'] = "Deseleziona se vuoi aggiornare DOK anche con i dati dei QSO non confermati in DCL.";

$lang['overwrite_by_dcl'] = "Sovrascrivi il DOK esistente nel log con DCL (se diverso)";
$lang['overwrite_by_dcl_hint'] = "Se selezionato Cloudlog sovrascriverà forzatamente il DOK esistente con DOK dal registro DCL.";

$lang['ignore_ambiguous'] = "Ignora i QSO che non possono essere abbinati";
$lang['ignore_ambiguous_hint'] = "Se deselezionato verranno visualizzate le informazioni sul QSO che non è stato possibile trovare in Cloudlog.";

/*
___________________________________________________________________________________________
Import Success
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF importato";
$lang['adif_yay_its_imported'] = "OK, è stato importato!";
$lang['adif_import_confirm'] = "Il file ADIF è stato importato.";

$lang['adif_import_dupes_inserted'] = " <b>Sono stati inseriti dei duplicati!</b>";
$lang['adif_import_dupes_skipped'] = "I duplicati sono stati saltati.";

$lang['adif_import_errors'] = "Errori ADIF";
$lang['adif_import_errors_hint'] = "Hai errori ADIF, i QSO sono stati aggiunti ma questi campi non sono stati popolati.";

/*
___________________________________________________________________________________________
DCL Success
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Risultati dell'aggiornamento DCL DOK";
$lang['dcl_info_updated'] = "Le informazioni DCL per i DOK sono state aggiornate.";
$lang['dcl_qsos_updated'] = "QSO aggiornati";
$lang['dcl_qsos_ignored'] = "QSO ignorati";
$lang['dcl_qsos_unmatched'] = "QSO non abbinati";
$lang['dcl_no_qsos_updated'] = "Nessun QSO trovato che possa essere aggiornato.";
$lang['dcl_dok_errors'] = "Errori DOK";
$lang['dcl_dok_errors_details'] = "Ci sono dati diversi per DOK nel tuo registro rispetto a DCL";
$lang['dcl_qsl_status'] = "Stato QSL DCL";
$lang['dcl_qsl_status_c'] = "confermato da LoTW/Clublog/eQSL/Contest";
$lang['dcl_qsl_status_mno'] = "confermato dall\'award manager";
$lang['dcl_qsl_status_i'] = "confermato dal controllo incrociato dei dati DCL";
$lang['dcl_qsl_status_w'] = "conferma in sospeso";
$lang['dcl_qsl_status_x'] = "non confermato";
$lang['dcl_qsl_status_unknown'] = "sconosciuto";
$lang['dcl_no_match'] = "Il QSO non può essere abbinato";
