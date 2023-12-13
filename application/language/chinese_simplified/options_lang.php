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
$lang['options_radio_timeout_warning'] = '电台超时警告';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = '在QSO输入面板上使用无线电超时警告，提醒您无线电接口断开。';
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
$lang['options_mail_settings_saved'] = "The settings were saved successfully.";
$lang['options_mail_settings_failed'] = "Something went wrong with saving the settings. Try again.";
$lang['options_outgoing_protocol_hint'] = "The protocol that will be used to send out emails.";
$lang['options_smtp_encryption_hint'] = "Choose whether emails should be sent with TLS or SSL.";
$lang['options_email_address_hint'] = "The email address from which the emails are sent, e.g. 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "The email sender name, e.g. 'Cloudlog'";
$lang['options_smtp_host_hint'] = "The hostname of the mail server, e.g. 'mail.example.com' (without 'ssl://' or 'tls://')";
$lang['options_smtp_port_hint'] = "The SMTP port of the mail server, e.g. if TLS is used -> '587', if SSL is used -> '465'";
$lang['options_smtp_username_hint'] = "The username to log in to the mail server, usually this is the email address that is used.";
$lang['options_smtp_password_hint'] = "The password to log in to the mail server.";
$lang['options_send_testmail'] = "Send Test-Mail";
$lang['options_send_testmail_hint'] = "The email will be sent to the address defined in your account settings.";
$lang['options_send_testmail_failed'] = "Testmail failed. Something went wrong.";
$lang['options_send_testmail_success'] = "Testmail sent. Email settings seem to be correct.";

$lang['options_oqrs'] = 'OQRS设置';
$lang['options_global_text'] = '全局文本';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = '该文本是一个可选文本，可以显示在OQRS页面的顶部。';
$lang['options_grouped_search'] = '分组搜索';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = '当此选项打开时，所有具有OQRS活动的电台位置将同时搜索。';
$lang['options_oqrs_options_have_been_saved'] = 'OQRS选项已保存';

$lang['options_save'] = '保存';
$lang['options_dxcluster_provider'] = 'Provider of DXClusterCache';
$lang['options_dxcluster_longtext'] = 'The Provider of the DXCluster-Cache. You can set up your own Cache with <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> or use a public one';
$lang['options_dxcluster_hint'] = 'URL of the DXCluster-Cache. e.g. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'DXCluster Cache URL changed to ';
$lang['options_dxcluster_maxage'] = 'Maximum Age of spots taken care of';
$lang['options_dxcluster_maxage_hint'] = 'The Age in Minutes of spots, that will be taken care at bandplan/lookup';
$lang['options_dxcluster_decont'] = 'Show spots which are spotted from following continent';
$lang['options_dxcluster_maxage_changed_to']='Maximum age of spots changed to ';
$lang['options_dxcluster_decont_changed_to']='de continent changed to ';
$lang['options_dxcluster_decont_hint']='Only spots by spotters from this continent are shown';

$lang['options_version_dialog'] = "Version Info";
$lang['options_version_dialog_close'] = "Close";
$lang['options_version_dialog_dismiss'] = "Don't show again";
$lang['options_version_dialog_settings'] = "Version Info Settings";
$lang['options_version_dialog_header'] = "Version Info Header";
$lang['options_version_dialog_header_hint'] = "You can change the header of the version info dialog.";
$lang['options_version_dialog_header_changed_to'] = "Version Info Header changed to";
$lang['options_version_dialog_mode'] = "Version Info Mode";
$lang['options_version_dialog_mode_release_notes'] = "Only Release Notes";
$lang['options_version_dialog_mode_custom_text'] = "Only Custom Text";
$lang['options_version_dialog_mode_both'] = "Release Notes and Custom Text";
$lang['options_version_dialog_mode_disabled'] = "Disabled";
$lang['options_version_dialog_mode_hint'] = "The Version Info is shown to every user. The user has the option to dismiss the dialog after he read it. Select if you want to show only release notes (fetched from github), only custom text or both.";
$lang['options_version_dialog_custom_text'] = "Version Info Custom Text";
$lang['options_version_dialog_custom_text_hint'] = "This is the custom text which is shown in the dialog.";
$lang['options_version_dialog_mode_changed_to'] = "Version Info Mode changed to";
$lang['options_version_dialog_custom_text_saved'] = "Version Info Custom Text saved!";
$lang['options_version_dialog_success_show_all'] = "Version Info will be shown to all users again";
$lang['options_version_dialog_success_hide_all'] = "Version Info will not be shown to any user";
$lang['options_version_dialog_show_hide'] = "Show/Hide Version Info Dialog for all Users";
$lang['options_version_dialog_show_all'] = "Show for all Users";
$lang['options_version_dialog_hide_all'] = "Hide for all Users";
$lang['options_version_dialog_show_all_hint'] = "This will show the version dialog automatically to all users on their next page reload.";
$lang['options_version_dialog_hide_all_hint'] = "This will deactivate the automatic popup of the version dialog for all users.";

$lang['options_save'] = '保存';

// Bands

$lang['options_bands'] = "波段";
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

