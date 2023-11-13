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

$lang['adif_hint_no_info_in_file'] = "如果导入的ADIF文件不包含此信息，选择此项。";

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
$lang['adif_dcl_text_pre'] = "前往";
$lang['adif_dcl_text_post'] = "并导出包含已确认 DOK 的日志。为了加快该过程，您可以仅选择 DL QSO 进行下载（即将“DL”放入前缀列表中）。可以在此处上传下载的 ADIF 文件，以便使用 DOK 信息更新 QSO。";

$lang['only_confirmed_qsos'] = "仅导入在 DCL 上确认的 QSO 的 DOK 数据。";
$lang['only_confirmed_qsos_hint'] = "如果您还想使用 DCL 中未经确认的 QSO 的数据更新 DOK，请取消选中。";

$lang['overwrite_by_dcl'] = "用 DCL 覆盖日志中现有的 DOC（如果不同）";
$lang['overwrite_by_dcl_hint'] = "如果选中，Cloudlog 将使用 DCL 日志中的 DOK 强制覆盖现有的 DOK 信息。";

$lang['ignore_ambiguous'] = "忽略无法匹配的 QSO";
$lang['ignore_ambiguous_hint'] = "如果不勾选，会显示 Cloudlog 中找不到的 QSO 信息。";

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
$lang['adif_import_errors_hint'] = "ADIF 错误，QSO 虽然已添加，但以下字段尚未填充。";

/*
___________________________________________________________________________________________
DCL Success
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "DCL DOK 更新结果";
$lang['dcl_info_updated'] = "DOK 的 DCL 信息已更新。";
$lang['dcl_qsos_updated'] = "已更新的QSO";
$lang['dcl_qsos_ignored'] = "已忽略的QSO";
$lang['dcl_qsos_unmatched'] = "不匹配的QSO";
$lang['dcl_no_qsos_updated'] = "未找到可以更新的 QSO。";
$lang['dcl_dok_errors'] = "DOK 错误";
$lang['dcl_dok_errors_details'] = "与 DCL 相比，您的日志中 DOK 的数据不同";
$lang['dcl_qsl_status'] = "DCL QSL 状态";
$lang['dcl_qsl_status_c'] = "已经通过 LoTW/Clublog/eQSL/Contest 确认";
$lang['dcl_qsl_status_mno'] = "已经通过 award manager 确认";
$lang['dcl_qsl_status_i'] = "已经通过 DCL 数据交叉检查确认";
$lang['dcl_qsl_status_w'] = "等待确认";
$lang['dcl_qsl_status_x'] = "未确认";
$lang['dcl_qsl_status_unknown'] = "未知";
$lang['dcl_no_match'] = "QSO 无法匹配";
