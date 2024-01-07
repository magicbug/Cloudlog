<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Cloudlog 设置';
$lang['options_message1'] = '本设置是针对所有用户的全局设置，会覆盖对于单个用户的设置。';

$lang['options_appearance'] = '外观';
$lang['options_theme'] = '主题';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = '全局主题选择，当用户未登录时使用。';
$lang['options_public_search_bar'] = '公共搜索栏';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = '允许未登录的用户访问搜索功能。';
$lang['options_dashboard_notification_banner'] = '仪表盘通知栏';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = '禁用仪表板上的全局通知横幅。';
$lang['options_dashboard_map'] = '仪表盘的地图';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = '允许禁用仪表板上的地图或将其放置在右侧。';
$lang['options_logbook_map'] = '日志地图';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = '允许禁用日志中的地图。';
$lang['options_theme_changed_to'] = '主题更改为 ';
$lang['options_global_search_changed_to'] = '全局搜索更改为 ';
$lang['options_dashboard_banner_changed_to'] = '仪表板横幅更改为 ';
$lang['options_dashboard_map_changed_to'] = '仪表板地图更改为 ';
$lang['options_logbook_map_changed_to'] = '日志地图更改为 ';

$lang['options_radios'] = '电台';
$lang['options_radio_settings'] = '电台设置';
$lang['options_radio_timeout_warning'] = '电台连接超时警告';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = '在QSO输入面板上使用电台连接超时警告，提醒您无线电接口断开。';
$lang['options_this_number_is_in_seconds'] = '此数字以秒为单位。';
$lang['options_radio_timeout_warning_changed_to'] = '无线电超时警告更改为 ';

$lang['options_email'] = '电子邮件';
$lang['options_outgoing_protocol'] = '传出协议';
$lang['options_smtp_encryption'] = 'SMTP加密';
$lang['options_email_address'] = '电子邮件地址';
$lang['options_email_sender_name'] = '发件人姓名';
$lang['options_smtp_host'] = 'SMTP 主机';
$lang['options_smtp_port'] = 'SMTP 端口';
$lang['options_smtp_username'] = 'SMTP 用户名';
$lang['options_smtp_password'] = 'SMTP 密码';
$lang['options_mail_settings_saved'] = "设置已保存";
$lang['options_mail_settings_failed'] = "保存时出现问题，请重试";
$lang['options_outgoing_protocol_hint'] = "发送邮件时使用的协议";
$lang['options_smtp_encryption_hint'] = "选择邮件将会通过TLS还是SSL发送";
$lang['options_email_address_hint'] = "发送邮件的邮箱地址，例如：'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "发送者的名字，例如：'Cloudlog'";
$lang['options_smtp_host_hint'] = "邮件服务器的域名，例如：'mail.example.com' (不带'ssl://'或'tls://')";
$lang['options_smtp_port_hint'] = "邮件服务器的SMTP端口，例如：如果使用了TLS -> '587'，如果使用了SSL -> '465'";
$lang['options_smtp_username_hint'] = "登录邮件服务器的用户名，通常情况下，这和发送邮件的邮箱地址相同";
$lang['options_smtp_password_hint'] = "登录邮件服务器的密码";
$lang['options_send_testmail'] = "发送测试邮件";
$lang['options_send_testmail_hint'] = "邮件将会发送到填写在个人信息中的邮箱中";
$lang['options_send_testmail_failed'] = "测试邮件发送失败，请检查设置";
$lang['options_send_testmail_success'] = "测试邮件发送成功，设置正常";

$lang['options_oqrs'] = 'OQRS设置';
$lang['options_global_text'] = '全局文本';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = '该文本是一个可选文本，可以显示在OQRS页面的顶部。';
$lang['options_grouped_search'] = '分组搜索';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = '当此选项打开时，所有具有OQRS活动的电台位置将同时搜索';
$lang['options_grouped_search_show_station_name'] = "在分组搜索结果中显示台站名称";
$lang['options_grouped_search_show_station_name_hint'] = "如果分组搜索被启用，台站名称将会显示在表格当中";
$lang['options_oqrs_options_have_been_saved'] = 'OQRS选项已保存';

$lang['options_save'] = '保存';
$lang['options_dxcluster_provider'] = 'DXClusterCache 的信息来源';
$lang['options_dxcluster_longtext'] = 'DXClusterCache 的信息来源，您可以通过 <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> 来设置自己的来源或使用公共来源';
$lang['options_dxcluster_hint'] = 'DXClusterCache 来源，例如：https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'DXCluster Cache 的网址已更换为： ';
$lang['options_dxcluster_maxage'] = '最多关心的报告数量';
$lang['options_dxcluster_maxage_hint'] = '报告的时长（分钟为单位）将会在‘频段/查找’中进行处理';
$lang['options_dxcluster_decont'] = '显示来自以下大洲的报告';
$lang['options_dxcluster_maxage_changed_to']='最多关心的报告数量已被更新为  ';
$lang['options_dxcluster_decont_changed_to']='大洲已被更新为 ';
$lang['options_dxcluster_decont_hint']='只有来自这个大洲的报告才会被显示';

$lang['options_version_dialog'] = "版本信息";
$lang['options_version_dialog_close'] = "关闭";
$lang['options_version_dialog_dismiss'] = "不再显示";
$lang['options_version_dialog_settings'] = "版本设置";
$lang['options_version_dialog_header'] = "版本信息标题";
$lang['options_version_dialog_header_hint'] = "你可以更改版本信息的标题";
$lang['options_version_dialog_header_changed_to'] = "版本信息的标题被更换为 ";
$lang['options_version_dialog_mode'] = "版本信息模式";
$lang['options_version_dialog_mode_release_notes'] = "只有发布版的更新内容";
$lang['options_version_dialog_mode_custom_text'] = "只有自定义文字";
$lang['options_version_dialog_mode_both'] = "发布版的更新内容和自定义文字";
$lang['options_version_dialog_mode_disabled'] = "禁用";
$lang['options_version_dialog_mode_hint'] = "版本信息将会对所有用户显示. 用户可以选择在阅读后关闭对话框，选择是否显示GitHub上发布版的更新内容还是自定义文字，或者两者";
$lang['options_version_dialog_custom_text'] = "版本信息————自定义文字";
$lang['options_version_dialog_custom_text_hint'] = "自定义文字将会在对话框中显示";
$lang['options_version_dialog_mode_changed_to'] = "版本信息模式切换为";
$lang['options_version_dialog_custom_text_saved'] = "版本信息自定义文字已保存";
$lang['options_version_dialog_success_show_all'] = "版本信息将会重新向用户显示";
$lang['options_version_dialog_success_hide_all'] = "版本信息不再会向用户显示";
$lang['options_version_dialog_show_hide'] = "显示/隐藏版本信息对话框";
$lang['options_version_dialog_show_all'] = "对所有用户显示";
$lang['options_version_dialog_hide_all'] = "对所有用户隐藏";
$lang['options_version_dialog_show_all_hint'] = "这将会在用户刷新页面时重新显示版本信息对话框";
$lang['options_version_dialog_hide_all_hint'] = "这将会关闭对用户显示版本信息对话框";

$lang['options_save'] = '保存';

// Bands

$lang['options_bands'] = "波段设置";
$lang['options_bands_text_ln1'] = "使用波段列表，您可以控制创建新 QSO 时显示哪些波段。";
$lang['options_bands_text_ln2'] = "启用的波段将显示在 QSO“波段”下拉列表中，而停用的频段将被隐藏且无法选择。";
$lang['options_bands_create'] = "创建波段";
$lang['options_bands_edit'] = "编辑波段";
$lang['options_bands_activate_all'] = "启用所有";
$lang['options_bands_activateall_warning'] = "警告！你要启用所有波段吗？";
$lang['options_bands_deactivate_all'] = "停用所有";
$lang['options_bands_deactivateall_warning'] = "警告！你要停用所有波段吗？";
$lang['options_bands_ssb_qrg'] = "SSB 频率";
$lang['options_bands_ssb_qrg_hint'] = "波段中 SSB 的频率（以Hz为单位）";
$lang['options_bands_data_qrg'] = "DATA 频率";
$lang['options_bands_data_qrg_hint'] = "波段中 DATA 的频率（以Hz为单位）";
$lang['options_bands_cw_qrg'] = "CW 频率";
$lang['options_bands_cw_qrg_hint'] = "波段中 CW 的频率（以Hz为单位）";

$lang['options_bands_name_band'] = "波段名称（例如：20m）";
$lang['options_bands_name_bandgroup'] = "频段名称（例如：HF、VHF、UHF、SHF）";
$lang['options_bands_delete_warning'] = "警告！ 您确定要删除以下波段：";

