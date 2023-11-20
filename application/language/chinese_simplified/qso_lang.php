<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Tiles
$lang['qso_title_qso_map'] = 'QSO 地图';
$lang['qso_title_suggestions'] = '建议';
$lang['qso_title_previous_contacts'] = '先前通联';
$lang['qso_title_image'] = '操作员照片';

// Quicklog on Dashboard
$lang['qso_quicklog_enter_callsign'] = 'QUICKLOG 输入呼号';

// Input Help Text on the /QSO Display
$lang['qso_transmit_power_helptext'] = '以瓦特为单位设置功率值。在输入中仅填写数值。';

$lang['qso_sota_ref_helptext'] = '例如: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = '例如: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = '例如: PA-0150.';

$lang['qso_sig_helptext'] = '例如: GMA';
$lang['qso_sig_info_helptext'] = '例如: DA/NW-357';

$lang['qso_dok_helptext'] = '例如: Q03';

$lang['qso_notes_helptext'] = '仅在 Cloudlog 使用而不上传到其他的服务的笔记。';
$lang['qsl_notes_helptext'] = '此笔记内容被导出到QSL服务，如 eqsl.cc。';

// Button Text on /qso Display

$lang['qso_btn_reset_qso'] = '重置';
$lang['qso_btn_save_qso'] = '保存 QSO';
$lang['qso_btn_edit_qso'] = '编辑 QSO';
$lang['qso_delete_warning'] = "警告！您确定要删除 QSO 与 ";

// QSO Details

$lang['qso_details'] = 'QSO 详情';

$lang['fav_add'] = '添加 模式/频段 到收藏';
$lang['qso_operator_callsign'] = '操作员呼号';

// Simple FLE (FastLogEntry)

$lang['qso_simplefle_info'] = "What is that?";
$lang['qso_simplefle_info_ln1'] = "Simple Fast Log Entry (FLE)";
$lang['qso_simplefle_info_ln2'] = "'Fast Log Entry', or simply 'FLE' is a system to log QSOs very quickly and efficiently. Due to its syntax, only a minimum of input is required to log many QSOs with as little effort as possible.";
$lang['qso_simplefle_info_ln3'] = "FLE was originally written by DF3CB. He offers a program for Windows on his website. Simple FLE was written by OK2CQR based on DF3CB's FLE and provides a web interface to log QSOs.";
$lang['qso_simplefle_info_ln4'] = "A common use-case is if you have to import your paperlogs from a outdoor session and now SimpleFLE is also available in Cloudlog. Information about the syntax and how FLE works can be found <a href='https://df3cb.com/fle/documentation/' target='_blank'>here</a>.";
$lang['qso_simplefle_qso_data'] = "QSO Data";
$lang['qso_simplefle_qso_date_hint'] = "If you don't choose a date, today's date will be used.";
$lang['qso_simplefle_qso_list'] = "QSO List";
$lang['qso_simplefle_qso_list_total'] = "Total";
$lang['qso_simplefle_qso_date'] = "QSO Date";
$lang['qso_simplefle_operator'] = "Operator";
$lang['qso_simplefle_operator_hint'] = "e.g. OK2CQR";
$lang['qso_simplefle_station_call_location'] = "Station Call/Location";
$lang['qso_simplefle_station_call_location_hint'] = "If you did operate from a new location, first create a new <a href=". site_url('station') . ">Station Location</a>";
$lang['qso_simplefle_utc_time'] = "Current UTC Time";
$lang['qso_simplefle_enter_the_data'] = "Enter the Data";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Close and Load Sample Data";
$lang['qso_simplefle_reload'] = "Reload QSO List";
$lang['qso_simplefle_save'] = "Save in Cloudlog";
$lang['qso_simplefle_clear'] = "Clear Logging Session";
$lang['qso_simplefle_refs_hint'] = "The Refs can be either <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA or <u>W</u>WFF";

$lang['qso_simplefle_error_band'] = "找不到波段！";
$lang['qso_simplefle_error_mode'] = "找不到模式！";
$lang['qso_simplefle_error_time'] = "时间未设定！";
$lang['qso_simplefle_error_stationcall'] = "Station Call is not selected";
$lang['qso_simplefle_error_operator'] = "'Operator' Field is empty";
$lang['qso_simplefle_warning_reset'] = "Warning! Do you really want to reset everything?";
$lang['qso_simplefle_warning_missing_band_mode'] = "Warning! You can't log the QSO List, because some QSO don't have band and/or mode defined!";
$lang['qso_simplefle_warning_missing_time'] = "Warning! You can't log the QSO List, because some QSO don't have a time defined!";
$lang['qso_simplefle_warning_example_data'] = "Attention! The Data Field containes example data. First Clear Logging Session!";
$lang['qso_simplefle_confirm_save_to_log'] = "Are you sure that you want to add these QSO to the Log and clear the session?";
$lang['qso_simplefle_success_save_to_log_header'] = "QSO Logged!";
$lang['qso_simplefle_success_save_to_log'] = "The QSO were successfully logged in the logbook!";
$lang['qso_simplefle_error_date'] = "Invalid date";

$lang['qso_simplefle_syntax_help_button'] = "Syntax Help";
$lang['qso_simplefle_syntax_help_title'] = "Syntax for FLE";
$lang['qso_simplefle_syntax_help_ln1'] = "Before starting to log a QSO, please note the basic rules.";
$lang['qso_simplefle_syntax_help_ln2'] = "- Each new QSO should be on a new line.";
$lang['qso_simplefle_syntax_help_ln3'] = "- On each new line, only write data that has changed from the previous QSO.";
$lang['qso_simplefle_syntax_help_ln4'] = "To begin, ensure you have already filled in the form on the left with the date, station call, and operator's call. The main data includes the band (or QRG in MHz, e.g., '7.145'), mode, and time. After the time, you provide the first QSO, which is essentially the callsign.";
$lang['qso_simplefle_syntax_help_ln5'] = "For example, a QSO that started at 21:34 (UTC) with 2M0SQL on 20m SSB.";
$lang['qso_simplefle_syntax_help_ln6'] = "If you don't provide any RST information, the syntax will use 59 (599 for data). Our next QSO wasn't 59 on both sides, so we provide the information with the sent RST first. It was 2 minutes later than the first QSO.";
$lang['qso_simplefle_syntax_help_ln7'] = "The first QSO was at 21:34, and the second one 2 minutes later at 21:36. We write down 6 because this is the only data that changed here. The information about band and mode didn't change, so this data is omitted.";
$lang['qso_simplefle_syntax_help_ln8'] = "For our next QSO at 21:40 on 14th May, 2021, we changed the band to 40m but still on SSB. If no RST information is given, the syntax will use 59 for every new QSO. Therefore we can add another QSO which took place at the exact same time two days later. The date must be in format YYYY-MM-DD.";
$lang['qso_simplefle_syntax_help_ln9'] = "For further information about the syntax, please check the website of DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>here.</a>";
    
