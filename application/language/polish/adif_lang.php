<?php

defined('BASEPATH') OR exit('Bezpośredni dostęp do skryptu nie jest dozwolony');

/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['adif_import'] = "ADIF Import";
$lang['adif_export'] = "ADIF Export";
// $lang['lotw_title'] --> application/language/english/lotw_lang.php
$lang['darc_dcl'] = "DARC DCL";

/*
______________________________________________________________________________________________________
ADIF Import
___________________________________________________________________________________________
*/

// $lang['general_word_important'] --> application/language/english/general_words_lang.php
$lang['adif_alert_log_files_type'] = "Pliki dziennika muszą mieć typ pliku *.adi";
// $lang['general_word_warning'] --> application/language/english/general_words_lang.php "Ostrzeżenie przed przesyłaniem PHP"
// $lang['gen_max_file_upload_size'] --> application/language/english/general_words_lang.php "Ostrzeżenie przed przesyłaniem PHP"

$lang['adif_select_stationlocation'] = "Wybierz lokalizację stacji";
// $lang['gen_hamradio_callsign'] --> application/language/english/general_words_lang.php

// Dane wejściowe pliku są tłumaczone przez przeglądarkę
$lang['adif_file_label'] = "Plik ADIF";

$lang['adif_hint_no_info_in_file'] = "Wybierz, jeśli importowany plik ADIF nie zawiera tych informacji.";

$lang['adif_import_dup'] = "Importuj zduplikowane QSO";
$lang['adif_mark_imported_lotw'] = "Oznacz importowane QSO jako przesłane do LoTW";
$lang['adif_mark_imported_hrdlog'] = "Oznacz importowane QSO jako przesłane do HRDLog.net Logbook";
$lang['adif_mark_imported_qrz'] = "Oznacz importowane QSO jako przesłane do dziennika QRZ";

$lang['adif_mark_imported_clublog'] = "Oznacz importowane QSO jako przesłane do dziennika Clublog";

$lang['adif_dxcc_from_adif'] = "Użyj informacji DXCC z ADIF";

$lang['adif_dxcc_from_adif_hint'] = "Jeśli nie zaznaczono, Cloudlog spróbuje automatycznie ustalić informacje DXCC.";

$lang['adif_always_use_login_call_as_op'] = "Zawsze używaj login-znaku wywoławczego jako nazwy operatora podczas importu";

$lang['adif_ignore_station_call'] = "Ignoruj ​​znak wywoławczy stacji podczas importu";
$lang['adif_ignore_station_call_hint'] = "Jeśli zaznaczone, Cloudlog spróbuje zaimportować <b>wszystkie</b> QSO ADIF, niezależnie od tego, czy pasują do wybranej lokalizacji stacji.";

$lang['adif_upload'] = "Prześlij";

/*
___________________________________________________________________________________________
Eksport ADIF
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "Zabierz swój plik dziennika wszędzie!";

$lang['adif_export_take_it_anywhere_hint'] = "Eksportowanie ADIF pozwala na importowanie kontaktów do aplikacji innych firm, takich jak LoTW, Awards lub po prostu w celu utworzenia kopii zapasowej.";

$lang['adif_mark_exported_lotw'] = "Oznacz wyeksportowane QSO jako przesłane do LoTW";
$lang['adif_mark_exported_no_lotw'] = "Eksportuj QSO, które nie zostały przesłane do LoTW";

$lang['adif_export_qso'] = "Eksportuj QSO";

$lang['adif_export_sat_only_qso'] = "Eksportuj QSO tylko z satelity";
$lang['adif_export_sat_only_qso_all'] = "Eksportuj wszystkie QSO z satelity";
$lang['adif_export_sat_only_qso_lotw'] = "Eksportuj wszystkie QSO z satelity potwierdzone w LoTW";

/*
______________________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "Jeśli zakres dat nie zostanie wybrany, wszystkie QSO zostaną oznaczone!";

$lang['adif_mark_qso_as_exported_to_lotw'] = "Oznacz QSO jako wyeksportowane do LoTW";

$lang['adif_qso_marked'] = "Oznaczone QSO";

$lang['adif_yay_its_done'] = "Yay, gotowe!";

$lang['adif_qso_lotw_marked_confirm'] = "QSO zostały oznaczone jako wyeksportowane do LoTW.";

/*
___________________________________________________________________________________________
DARC DCL
________________________________________________________________________________________________
*/
$lang['adif_dcl_text_pre'] = "Przejdź do";
$lang['adif_dcl_text_post'] = "i wyeksportuj swój dziennik z potwierdzonymi DOK. Aby przyspieszyć ten proces, możesz wybrać do pobrania tylko QSO DL (tj. umieścić „DL” na liście prefiksów). Pobrany plik ADIF można przesłać tutaj, aby zaktualizować QSO o informacje DOK.";

$lang['only_confirmed_qsos'] = "Importuj tylko dane DOK z QSO potwierdzonych na DCL.";
$lang['only_confirmed_qsos_hint'] = "Odznacz, jeśli chcesz również zaktualizować DOK o dane z niepotwierdzonych QSO w DCL.";

$lang['overwrite_by_dcl'] = "Nadpisz istniejący DOK w logu przez DCL (jeśli jest inny)";

$lang['overwrite_by_dcl_hint'] = "Jeśli zaznaczone, Cloudlog wymusi nadpisanie istniejącego DOK przez DOK z logu DCL.";

$lang['ignore_ambiguous'] = "Ignoruj ​​QSO, których nie można dopasować";

$lang['ignore_ambiguous_hint'] = "Jeśli niezaznaczone, zostaną wyświetlone informacje o QSO, których nie można znaleźć w Cloudlog.";

/*
___________________________________________________________________________________________________________
Import zakończony sukcesem
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF zaimportowany";

$lang['adif_yay_its_imported'] = "Hurra, zaimportowano!";
$lang['adif_import_confirm'] = "Plik ADIF został zaimportowany.";

$lang['adif_import_dupes_inserted'] = " <b>Dupy zostały wstawione!</b>";
$lang['adif_import_dupes_skipped'] = "Dupy zostały pominięte.";

$lang['adif_import_errors'] = "Błędy ADIF";
$lang['adif_import_errors_hint'] = "Masz błędy ADIF, QSO zostały dodane, ale te pola nie zostały wypełnione.";

/*
___________________________________________________________________________________________________________
Sukces DCL
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Wyniki aktualizacji DCL DOK";
$lang['dcl_info_updated'] = "Informacje DCL dla DOK zostały zaktualizowane.";
$lang['dcl_qsos_updated'] = "Zaktualizowano QSO";
$lang['dcl_qsos_ignored'] = "Zignorowano QSO";
$lang['dcl_qsos_unmatched'] = "Niezgodne QSO";
$lang['dcl_no_qsos_updated'] = "Nie znaleziono QSO, które można by zaktualizować.";
$lang['dcl_dok_errors'] = "Błędy DOK";
$lang['dcl_dok_errors_details'] = "W Twoim logu znajdują się inne dane dla DOK niż dla DCL";
$lang['dcl_qsl_status'] = "Status QSL DCL";
$lang['dcl_qsl_status_c'] = "potwierdzone przez LoTW/Clublog/eQSL/Contest";
$lang['dcl_qsl_status_mno'] = "potwierdzone przez kierownika nagrody";
$lang['dcl_qsl_status_i'] = "potwierdzone przez krzyżową kontrolę danych DCL";
$lang['dcl_qsl_status_w'] = "oczekiwanie na potwierdzenie";
$lang['dcl_qsl_status_x'] = "niepotwierdzone";
$lang['dcl_qsl_status_unknown'] = "nieznane";
$lang['dcl_no_match'] = "Nie można dopasować QSO";
