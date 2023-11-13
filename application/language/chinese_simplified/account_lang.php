<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['account_logbook_fields'] = '日志簿字段';
$lang['account_column1_text'] = '选择第1列';
$lang['account_column2_text'] = '选择第2列';
$lang['account_column3_text'] = '选择第3列';
$lang['account_column4_text'] = '选择第4列';
$lang['account_column5_text'] = '选择第5列（仅日志簿）';

$lang['account_create_user_account'] = '创建用户账户';
$lang['account_edit_account'] = '编辑账户';

$lang['account_account_information'] = '账户信息';
$lang['account_user'] = "用户"; 
$lang['account_word_edited'] = "已编辑";
$lang['account_username'] = '用户名';
$lang['account_email_address'] = '电子邮件';
$lang['account_password'] = '密码';

$lang['account_roles'] = '角色';
$lang['account_user_role'] = '用户角色';
$lang['account_word_admin'] = '管理员';

$lang['account_theme'] = '主题';
$lang['account_stylesheet'] = '样式表';

$lang['account_personal_information'] = '个人信息';
$lang['account_first_name'] = '姓';
$lang['account_last_name'] = '名';
$lang['account_callsign'] = '呼号';
$lang['account_gridsquare'] = '梅登海德网格';

$lang['account_cloudlog_preferences'] = '偏好选项';
$lang['account_timezone'] = '时区';
$lang['account_date_format'] = '日期格式';
$lang['account_log_end_time'] = '单独记录QSO结束时间';
$lang['account_log_end_time_hint'] = '如果想分别记录QSO的开始和结束时间，选择\'是\'，若选择\'否\'则QSO开始与结束时间相同。';
$lang['account_quicklog_feature'] = "快速日志功能";
$lang['account_quicklog_feature_hint'] = "快速日志功能可以使用标题栏中的搜索字段来记录呼号。";
$lang['account_quicklog_enter'] = "快速日志 - 回车键行为";
$lang['account_quicklog_enter_hint'] = "在快速日志字段中按 回车键 后应执行什么操作？";
$lang['account_quicklog_enter_log'] = "记录呼号";
$lang['account_quicklog_enter_search'] = "查询呼号";
$lang['account_measurement_preferences'] = '距离单位偏好';
$lang['account_select_how_you_would_like_dates_shown_when_logged_into_your_account'] = '选择您登录账户时要显示的日期格式';
$lang['account_choose_which_unit_distances_will_be_shown_in'] = '选择距离单位';
$lang['account_cloudlog_language'] = 'Cloudlog语言';
$lang['account_choose_cloudlog_language'] = '选择Cloudlog语言。';

$lang['account_main_menu'] = '主菜单';
$lang['account_show_notes_in_the_main_menu'] = '在主菜单显示便签栏';

$lang['account_gridsquare_and_location_autocomplete'] = '自动填写梅登海德网格和位置';
$lang['account_location_auto_lookup'] = '自动查找位置';
$lang['account_if_set_gridsquare_is_fetched_based_on_location_name'] = '如果开启本选项，将根据位置名称获取梅登海德网格。';
$lang['account_sota_auto_lookup_gridsquare_and_name_for_summit'] = '根据 SOTA 编号自动查找梅登海德网格和峰名。';
$lang['account_wwff_auto_lookup_gridsquare_and_name_for_reference'] = '根据 WWFF 编号自动查找梅登海德网格和名称。';
$lang['account_pota_auto_lookup_gridsquare_and_name_for_park'] = '根据 POTA 编号自动查找梅登海德网格和名称。';
$lang['account_if_set_name_and_gridsquare_is_fetched_from_the_api_and_filled_in_location_and_locator'] = '如果开启此项设置，将从API获取名称和梅登海德网格，并填写位置和定位器。';

$lang['account_previous_qsl_type'] = '上一个QSL类型';
$lang['account_select_the_type_of_qsl_to_show_in_the_previous_qsos_section'] = '选择要在上一个QSO部分中显示的QSL类型。';

$lang['account_qrzcom_hamqthcom_images'] = 'qrz.com/hamqth.com Images';
$lang['account_show_profile_picture_of_qso_partner_from_qrzcom_hamqthcom_profile_in_the_log_qso_section'] = '在日志QSO部分中显示qrz.com/hamqth.com配置文件的QSO合作伙伴的个人资料图片。';
$lang['account_please_set_your_qrzcom_hamqthcom_credentials_in_the_general_config_file'] = '请在general_config.php文件中设置qrz.com/hamqth.com凭据。';

$lang['account_amsat_status_upload'] = '上传到AMSAT';
$lang['account_upload_status_of_sat_qsos_to'] = '上传卫星QSO到';

$lang['account_logbook_of_the_world'] = 'Logbook of the World（LoTW）';
$lang['account_logbook_of_the_world_lotw_username'] = 'Logbook of The World (LoTW) 用户名';
$lang['account_logbook_of_the_world_lotw_password'] = 'Logbook of The World (LoTW) 密码';
$lang['account_leave_blank_to_keep_existing_password'] = '留空以保留现有密码';

$lang['account_clublog'] = 'Club Log（俱乐部日志）';
$lang['account_clublog_email_callsign'] = 'Club Log 邮件地址/呼号';
$lang['account_clublog_password'] = 'Club Log 密码';
$lang['account_the_email_or_callsign_you_use_to_login_to_club_log'] = '您用于登录Club Log的电子邮件或呼号。';

$lang['account_eqsl'] = 'eQSL';
$lang['account_eqslcc_username'] = 'eQSL.cc 用户名';
$lang['account_eqslcc_password'] = 'eQSL.cc 密码';

$lang['account_save_account_changes'] = '保存账户更改';
$lang['account_create_account'] = '创建账户';

$lang['account_delete_user_account'] = '删除用户账户';
$lang['account_are_you_sure_you_want_to_delete_the_user_account'] = '您确定要删除用户账户吗？';
$lang['account_yes_delete_this_user'] = '是的，删除此用户';
$lang['account_no_do_not_delete_this_user'] = '不，不要删除此用户';

$lang['account_forgot_password'] = '忘记密码';
$lang['account_you_can_reset_your_password_here'] = '您可以在此处重置密码。';
$lang['account_reset_password'] = '重置密码';
$lang['account_the_email_field_is_required'] = '电子邮件必填';
$lang['account_confirm_password'] = '确认密码';

$lang['account_forgot_your_password'] = '忘记密码？';

$lang['account_login_to_cloudlog'] = '登录Cloudlog';
$lang['account_login'] = '登录';

$lang['account_mastodon'] = 'Mastodon服务器';
$lang['account_user_mastodon'] = 'Mastodon 地址';
$lang['account_user_mastodon_hint'] = "Mastodon服务器的主URL地址，例如 <a href='https://radiosocial.de/' target='_blank'>https://radiosocial.de";

$lang['account_default_band_settings'] = '默认波段和QSL确认方式设置';
$lang['account_gridmap_default_band'] = '默认波段';
$lang['account_qsl_settings'] = '默认QSL方式';

$lang['account_winkeyer'] = 'Winkeyer';
$lang['account_winkeyer_hint'] = "Cloudlog 中对 Winkeyer 的支持是实验性的，请在开启前先阅读 <a href='https://github.com/magicbug/Cloudlog/wiki/Winkey' target='_blank'>https://github.com/magicbug/Cloudlog/wiki/Winkey</a>。";
$lang['account_winkeyer_enabled'] = "启用 Winkeyer 功能";

