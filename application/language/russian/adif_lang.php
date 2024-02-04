<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['adif_import'] = "Импорт ADIF";
$lang['adif_export'] = "Экспорт ADIF";
// $lang['lotw_title']                      --> application/language/english/lotw_lang.php
$lang['darc_dcl'] = "DARC DCL";


/*
___________________________________________________________________________________________
ADIF Import
___________________________________________________________________________________________
*/

// $lang['general_word_important']           --> application/language/english/general_words_lang.php
$lang['adif_alert_log_files_type'] = "Лог-файлы должны иметь расширение .adi";
// $lang['general_word_warning']            --> application/language/english/general_words_lang.php "PHP Upload Warning"
// $lang['gen_max_file_upload_size']        --> application/language/english/general_words_lang.php "PHP Upload Warning"

$lang['adif_select_stationlocation'] = "Выберите местоположение станции";
// $lang['gen_hamradio_callsign']           --> application/language/english/general_words_lang.php

// The File Input is translated by the Browser
$lang['adif_file_label'] = "Файл ADIF";

$lang['adif_hint_no_info_in_file'] = "Отметьте, если импортируемый ADIF  не сожержит этой информации.";

$lang['adif_import_dup'] = "Импортировать дубликаты QSO";
$lang['adif_mark_imported_lotw'] = "Отметить импортированные QSO, как загруженные в LoTW";
$lang['adif_mark_imported_hrdlog'] = "Отметить импортированные QSO, как загруженные в журнал HRDLog.net";
$lang['adif_mark_imported_qrz'] = "Отметить импортированные QSO, как загруженные в журнал QRZ";
$lang['adif_mark_imported_clublog'] = "Отметить импортированные QSO, как загруженные в журнал Clublog";

$lang['adif_dxcc_from_adif'] = "Использовать информацию о DXCC из ADIF";
$lang['adif_dxcc_from_adif_hint'] = "Если не отмечено, Cloudlog будет пытаться определить информацию о DXCC автоматически.";

$lang['adif_always_use_login_call_as_op'] = "Всегда использовать позывной (логин) как имя оператора при импорте";

$lang['adif_ignore_station_call'] = "Игнорировать позывной станции при импорте";
$lang['adif_ignore_station_call_hint'] = "Если отмечено, Cloudlog попытается импортировать <b>все</b> QSO из ADIF, независимо от соответствия из выбранному местоположению станции.";

$lang['adif_upload'] = "Загрузить";

/*
___________________________________________________________________________________________
ADIF Export
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "Возьми свой файл журнала куда угодно!";
$lang['adif_export_take_it_anywhere_hint'] = "Экспорт ADIF позволяет импортировать QSO в различные приложения (сервисы), такие как LoTW, Awards или просто сохранить, как резервную копию.";


$lang['adif_mark_exported_lotw'] = "Отметить экспортированные QSO как загруженные в LoTW";
$lang['adif_mark_exported_no_lotw'] = "Отметить экспортированные QSO как незагруженные в LoTW";

$lang['adif_export_qso'] = "Экспорт QSO";

$lang['adif_export_sat_only_qso'] = "Экспортировать QSO, проведённые через спутник";
$lang['adif_export_sat_only_qso_all'] = "Экспортировать все QSO, проведённые через спутник";
$lang['adif_export_sat_only_qso_lotw'] = "Экспортировать все QSO, проведённые через спутник, подтверждённые на LoTW";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "Если диапазон дат не выбран, то будут отмечены все QSO!";
$lang['adif_mark_qso_as_exported_to_lotw'] = "Отметить QSO как экспортированные в LoTW";

$lang['adif_qso_marked'] = "QSO отмечены!";
$lang['adif_yay_its_done'] = "Готово!";
$lang['adif_qso_lotw_marked_confirm'] = "QSO были отмечены, как экспортированные в LoTW.";

/*
___________________________________________________________________________________________
DARC DCL
___________________________________________________________________________________________
*/
$lang['adif_dcl_text_pre'] = "Перейдите на";
$lang['adif_dcl_text_post'] = "и экспортируйте ващ журнал с подтверждёнными DOK. Для ускорения процесса вы можете выбрать только DL QSO для скачивания (т.е. указать \"DL\" в списке префиксов). Скачанный ADIF может быть загружен тут для обновления данных DOK в QSO.";

$lang['only_confirmed_qsos'] = "Импортировать только данные DOK из QSO, подтверждённых на DCL.";
$lang['only_confirmed_qsos_hint'] = "Снисите отметку, если вы также хотите обновить данные DOK из QSO, не подтверждённых на DCL.";

$lang['overwrite_by_dcl'] = "Перезаписать имеющиеся данные в журнале DOK данными из DCL (если они различны)";
$lang['overwrite_by_dcl_hint'] = "Если отмеченно, то Cloudlog принудительно перезапишет имеющиемя данные DOK данными из журнала DCL.";

$lang['ignore_ambiguous'] = "Игнорировать несовпадающиe QSO";
$lang['ignore_ambiguous_hint'] = "Если не отмечено, будет отображена информация о QSO, не найденных в Cloudlog.";

/*
___________________________________________________________________________________________
Import Success
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF импортирован";
$lang['adif_yay_its_imported'] = "Готово!";
$lang['adif_import_confirm'] = "ADIF файл импортирован.";

$lang['adif_import_dupes_inserted'] = " <b>Добавлены дубликаты!</b>";
$lang['adif_import_dupes_skipped'] = " Дубликаты игнорированы.";

$lang['adif_import_errors'] = "Ошибки ADIF";
$lang['adif_import_errors_hint'] = "В ADIF присутствуют ошибки, QSOs добавлены, но эти поля не заполнены.";

/*
___________________________________________________________________________________________
DCL Success
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Результаты обновления DCL DOK";
$lang['dcl_info_updated'] = "Информация DCL для DOK была обновлена.";
$lang['dcl_qsos_updated'] = "QSO обновлены";
$lang['dcl_qsos_ignored'] = "QSO игнорированы";
$lang['dcl_qsos_unmatched'] = "QSO не совпадают";
$lang['dcl_no_qsos_updated'] = "Не найдено QSO, которые могут быть обновлены.";
$lang['dcl_dok_errors'] = "Ошибки DOK";
$lang['dcl_dok_errors_details'] = "Данные DOK в вашем логе отличаются от DCL";
$lang['dcl_qsl_status'] = "Статус DCL QSL";
$lang['dcl_qsl_status_c'] = "подтверждено LoTW/Clublog/eQSL/Contest";
$lang['dcl_qsl_status_mno'] = "подтверждено менеджером диплома";
$lang['dcl_qsl_status_i'] = "подтверждено кросс-проверкой с данными DCL";
$lang['dcl_qsl_status_w'] = "подтверждение ожидается";
$lang['dcl_qsl_status_x'] = "не подтверждено";
$lang['dcl_qsl_status_unknown'] = "неизвестно";
$lang['dcl_no_match'] = "QSO не может быть сопоставлено";
