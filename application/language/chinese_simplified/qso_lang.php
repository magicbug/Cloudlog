<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Tiles
$lang['qso_title_qso_map'] = 'QSO 地图';
$lang['qso_title_suggestions'] = '建议';
$lang['qso_title_previous_contacts'] = '先前通联';
$lang['qso_title_times_worked_before'] = "先前通联的次数";
$lang['qso_title_image'] = '操作员照片';
$lang['qso_previous_max_shown'] = "最多五次先前通联将会被显示";

// Quicklog on Dashboard
$lang['qso_quicklog_enter_callsign'] = '快速记录QSO输入呼号';

// Input Help Text on the /QSO Display
$lang['qso_transmit_power_helptext'] = '以W为单位设置功率值。在输入中仅填写数值';

$lang['qso_sota_ref_helptext'] = '例如: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = '例如: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = '例如: PA-0150.';

$lang['qso_sig_helptext'] = '例如: GMA';
$lang['qso_sig_info_helptext'] = '例如: DA/NW-357';

$lang['qso_dok_helptext'] = '例如: Q03';

$lang['qso_notes_helptext'] = '仅在 Cloudlog 使用而不上传到其他的服务的笔记。';
$lang['qsl_notes_helptext'] = '此笔记内容被导出到QSL服务，如 eqsl.cc。';

$lang['qso_eqsl_qslmsg_helptext'] = "获取该站的 eQSL 默认消息。";

// error text //
$lang['qso_error_timeoff_less_timeon'] = "结束时间小于开始时间";

// Button Text on /qso Display

$lang['qso_btn_reset_qso'] = '重置';
$lang['qso_btn_save_qso'] = '保存 QSO';
$lang['qso_btn_edit_qso'] = '编辑 QSO';
$lang['qso_delete_warning'] = "警告！您确定要删除 QSO 和 ";

// QSO Details

$lang['qso_details'] = 'QSO 详情';

$lang['fav_add'] = '添加 模式/频段 到收藏';
$lang['qso_operator_callsign'] = '操作员呼号';

// Simple FLE (FastLogEntry)

$lang['qso_simplefle_info'] = "这是什么?";
$lang['qso_simplefle_info_ln1'] = "简单快速日志输入 (FLE)";
$lang['qso_simplefle_info_ln2'] = "“快速日志输入”，或简称“FLE”，是一个非常快速、高效地记录 QSO 的系统。 由于其语法，只需最少的输入即可以尽可能少的努力记录许多 QSO。";
$lang['qso_simplefle_info_ln3'] = "FLE 最初由 DF3CB 编写。 他在他的网站上提供了一个适用于 Windows 的程序。 Simple FLE 是 OK2CQR 基于 DF3CB 的 FLE 编写的，并提供了一个 Web 界面来记录 QSO。";
$lang['qso_simplefle_info_ln4'] = "一个常见的用例是，如果您必须野架之后将纸质日志导入电脑，现在 Cloudlog 中也提供了 SimpleFLE。 有关语法和 FLE 工作原理的信息可以在<a href='https://df3cb.com/fle/documentation/' target='_blank'>此处</a>找到。";
$lang['qso_simplefle_qso_data'] = "QSO 数据";
$lang['qso_simplefle_qso_date_hint'] = "如果您不选择日期，则将使用今天的日期。";
$lang['qso_simplefle_qso_list'] = "QSO 列表";
$lang['qso_simplefle_qso_list_total'] = "QSO 总数";
$lang['qso_simplefle_qso_date'] = "QSO 日期";
$lang['qso_simplefle_operator'] = "操作员";
$lang['qso_simplefle_operator_hint'] = "例如 BA1AA";
$lang['qso_simplefle_station_call_location'] = "位置";
$lang['qso_simplefle_station_call_location_hint'] = "如果您确实在新位置进行操作，请首先创建一个新的<a href=".site_url('station') .">电台站位置</a>";
$lang['qso_simplefle_utc_time'] = "当前UTC时间";
$lang['qso_simplefle_enter_the_data'] = "输入信息";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "关闭并加载示例数据";
$lang['qso_simplefle_reload'] = "重新加载QSO列表";
$lang['qso_simplefle_save'] = "保存QSO列表";
$lang['qso_simplefle_clear'] = "清除记录会话";
$lang['qso_simplefle_refs_hint'] = "Refs 可以是 <u>S</u>OTA、<u>I</u>OTA、<u>P</u>OTA 或 <u>W</u>WFF";

$lang['qso_simplefle_error_band'] = "找不到波段！";
$lang['qso_simplefle_error_mode'] = "找不到模式！";
$lang['qso_simplefle_error_time'] = "时间未设定！";
$lang['qso_simplefle_error_stationcall'] = "未选择呼叫的电台站！";
$lang['qso_simplefle_error_operator'] = "未输入操作员呼号！";
$lang['qso_simplefle_warning_reset'] = "警告！您确定要重置日志会话吗？";
$lang['qso_simplefle_warning_missing_band_mode'] = "警告！您不能记录 QSO 列表，因为某些 QSO 没有定义波段和模式！";
$lang['qso_simplefle_warning_missing_time'] = "警告！您不能记录 QSO 列表，因为某些 QSO 没有定义时间！";
$lang['qso_simplefle_warning_example_data'] = "警告！您不能记录 QSO 列表，因为您正在使用示例数据！";
$lang['qso_simplefle_confirm_save_to_log'] = "确认保存 QSO 到日志";
$lang['qso_simplefle_success_save_to_log_header'] = "QSO 已记录!";
$lang['qso_simplefle_success_save_to_log'] = "QSO 已成功记录到日志。";
$lang['qso_simplefle_error_date'] = "日期格式错误！";

$lang['qso_simplefle_syntax_help_button'] = "FLE 语法帮助";
$lang['qso_simplefle_syntax_help_title'] = "FLE 语法帮助";
$lang['qso_simplefle_syntax_help_ln1'] = "FLE 语法是一种简单的语法，用于快速记录 QSO。它的工作原理是，您只需输入每个 QSO 的更改部分。";
$lang['qso_simplefle_syntax_help_ln2'] = "在每一行上，只写与上一个 QSO 不同的数据。";
$lang['qso_simplefle_syntax_help_ln3'] = "例如，如果您在 20m 上与 DF3CB 进行了两次 QSO，您可以这样写：";
$lang['qso_simplefle_syntax_help_ln4'] = "";
$lang['qso_simplefle_syntax_help_ln5'] = "如果您不提供任何 RST 信息，语法将使用 59 (599 用于数据)。我们的下一个 QSO 不是双方都是 59，所以我们首先提供发送的 RST 信息。它比第一个 QSO 晚了 2 分钟。";
$lang['qso_simplefle_syntax_help_ln6'] = "第一个 QSO 是在 21:34，第二个 QSO 在 21:36，比第一个 QSO 晚了 2 分钟。我们写下 6，因为这是这里唯一改变的数据。关于波段和模式的信息没有改变，因此省略了这些数据。";
$lang['qso_simplefle_syntax_help_ln7'] = "";
$lang['qso_simplefle_syntax_help_ln8'] = "我们的下一个 QSO 是在 2021 年 5 月 14 日的 21:40，我们将波段更改为 40m，但仍然在 SSB 上。如果没有给出 RST 信息，语法将为每个新的 QSO 使用 59。因此，我们可以添加另一个 QSO，它在两天后的同一时间发生。日期必须是 YYYY-MM-DD 格式。";
$lang['qso_simplefle_syntax_help_ln9'] = "有关语法的更多信息，请查看 DF3CB 的网站<a href='https://df3cb.com/fle/documentation/' target='_blank'>此处</a>。";
    
