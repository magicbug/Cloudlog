<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['adif_import'] = "ADIF 导入";
$lang['adif_export'] = "ADIF 导出";
// $lang['lotw_title']                      --> application/language/english/lotw_lang.php
$lang['darc_dcl'] = "DARC DCL";


/*
___________________________________________________________________________________________
ADIF Import
___________________________________________________________________________________________
*/

// $lang['general_word_important']           --> application/language/english/general_words_lang.php
$lang['adif_alert_log_files_type'] = "Log文件的拓展名必须是.adi";
// $lang['general_word_warning']            --> application/language/english/general_words_lang.php "PHP Upload Warning"
// $lang['gen_max_file_upload_size']        --> application/language/english/general_words_lang.php "PHP Upload Warning"

$lang['adif_select_stationlocation'] = "选择站点位置";
// $lang['gen_hamradio_callsign']           --> application/language/english/general_words_lang.php

// The File Input is translated by the Browser
$lang['adif_file_label'] = "ADIF 文件";

$lang['adif_hint_no_info_in_file'] ="如果导入的ADIF文件不包含此信息，选择此项。";

$lang['adif_import_dup'] = "导入重复的QSO";
$lang['adif_mark_imported_lotw'] = "标记导入的QSO为已上传至 LoTW";
$lang['adif_mark_imported_hrdlog'] = "标记导入的QSO为已上传至 HRDLog.net Logbook";
$lang['adif_mark_imported_qrz'] = "标记导入的QSO为已上传至 QRZ Logbook";
$lang['adif_mark_imported_clublog'] = "标记导入的QSO为已上传至 Clublog Logbook";

$lang['adif_dxcc_from_adif'] = "使用ADIF文件中的DXCC信息";
$lang['adif_dxcc_from_adif_hint'] = "如果不选择，Cloudlog会尝试自动确定DXCC信息。";

$lang['adif_always_use_login_call_as_op'] = "总是在导入时使用登录的呼号作为操作者名称";

$lang['adif_ignore_station_call'] = "导入时忽略台站的呼号";
$lang['adif_ignore_station_call_hint'] = "如果选择，Cloudlog会尝试导入ADIF文件中的 <b>所有</b> QSO，无论他们是否与所选台站位置匹配。";

$lang['adif_upload'] = "上传";

/*
___________________________________________________________________________________________
ADIF Export
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "导出你的日志！";
$lang['adif_export_take_it_anywhere_hint'] = "导出 ADIF 允许您将日志导入到 LoTW 等第三方应用程序或只是为了保留备份。";


$lang['adif_mark_exported_lotw'] = "将导出的 QSO 标记为已上传到 LoTW";
$lang['adif_mark_exported_no_lotw'] = "导出没有上传到 LoTw 的 QSO";

$lang['adif_export_qso'] = "导出 QSO";

$lang['adif_export_sat_only_qso'] = "只导出卫星 QSO";
$lang['adif_export_sat_only_qso_all'] = "导出所有卫星 QSO";
$lang['adif_export_sat_only_qso_lotw'] = "导出 LoTW 上确认的所有卫星 QSO";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "如果未选择日期范围，则所有 QSO 都将被标记！";
$lang['adif_mark_qso_as_exported_to_lotw'] = "将 QSO 标记为导出到 LoTW";

$lang['adif_qso_marked'] = "QSO 已标记";
$lang['adif_yay_its_done'] = "耶, 完事儿了！";
$lang['adif_qso_lotw_marked_confirm'] = "这些 QSO 已经被标记为已导出到 LoTW。";

/*
___________________________________________________________________________________________
DARC DCL
___________________________________________________________________________________________
*/
$lang['adif_dcl_text_pre'] = "Go to";
$lang['adif_dcl_text_post'] = "and export your logbook with confirmed DOKs. To speed up the process you can select only DL QSOs to download (i.e. put \"DL\" into Prefix List). The downloaded ADIF file can be uploaded here in order to update QSOs with DOK info.";

$lang['only_confirmed_qsos'] = "Only import DOK data from QSOs confirmed on DCL.";
$lang['only_confirmed_qsos_hint'] = "Uncheck if you also want to update DOK with data from unconfirmed QSOs in DCL.";

$lang['overwrite_by_dcl'] = "Overwrite exisiting DOK in log by DCL (if different)";
$lang['overwrite_by_dcl_hint'] = "If checked Cloudlog will forcibly overwrite existing DOK with DOK from DCL log.";

$lang['ignore_ambiguous'] = "Ignore QSOs that cannot be matched";
$lang['ignore_ambiguous_hint'] = "If unchecked information about QSO which could not be found in Cloudlog will be displayed.";

/*
___________________________________________________________________________________________
Import Success
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF 已导入";
$lang['adif_yay_its_imported'] = "耶，导入成功！";
$lang['adif_import_confirm'] = "ADIF 文件已成功导入";

$lang['adif_import_dupes_inserted'] = " <b>重复条目已导入！</b>";
$lang['adif_import_dupes_skipped'] = " 重复条目已跳过。";

$lang['adif_import_errors'] = "ADIF 错误";
$lang['adif_import_errors_hint'] = "ADIF 错误，QSO 虽然已添加，但这些字段尚未填充。";

/*
___________________________________________________________________________________________
DCL Success
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Results of DCL DOK Update";
$lang['dcl_info_updated'] = "DCL information for DOKs has been updated.";
$lang['dcl_qsos_updated'] = "QSOs updated";
$lang['dcl_qsos_ignored'] = "QSOs ignored";
$lang['dcl_qsos_unmatched'] = "QSOs unmatched";
$lang['dcl_no_qsos_updated'] = "No QSOs found which could be updated.";
$lang['dcl_dok_errors'] = "DOK Errors";
$lang['dcl_dok_errors_details'] = "There is different data for DOK in your log compared to DCL";
$lang['dcl_qsl_status'] = "DCL QSL Status";
$lang['dcl_qsl_status_c'] = "confirmed by LoTW/Clublog/eQSL/Contest";
$lang['dcl_qsl_status_mno'] = "confirmed by award manager";
$lang['dcl_qsl_status_i'] = "confirmed by cross-check of DCL data";
$lang['dcl_qsl_status_w'] = "confirmation pending";
$lang['dcl_qsl_status_x'] = "unconfirmed";
$lang['dcl_qsl_status_unknown'] = "unknown";
$lang['dcl_no_match'] = "QSO could not be matched";
