<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Настройки';
$lang['options_message1'] = 'Это глобальные настройки, используемые для всех пользователей, которые могут быть переопределены, если есть соответствующие настройки на уровне пользователя.';

$lang['options_appearance'] = 'Внешний вид';
$lang['options_theme'] = 'Тема оформления';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Тема оформления по умолчанию, используется, когда пользователи не вошли в систему.';
$lang['options_public_search_bar'] = 'Публично доступный поиск по журналу';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Разрешение пользователям, не вошедшим в систему, получить доступ к функциям поиска.';
$lang['options_dashboard_notification_banner'] = 'Баннер на экране сводных данных';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Включение отображения баннера на экране сводных данных';
$lang['options_dashboard_map'] = 'Карта на экране сводных данных';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Включение отображения карты на экране сводных данных во всю ширину или справа';
$lang['options_logbook_map'] = 'Карта на экране обычного вида журнала ';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Включение отображения карты на экране обычного вида журнала.';
$lang['options_theme_changed_to'] = 'Тема оформления переключена на ';
$lang['options_global_search_changed_to'] = 'Публично доступный поиск переключен в состояние ';
$lang['options_dashboard_banner_changed_to'] = 'Баннер на экране сводных данных переключен в состояние ';
$lang['options_dashboard_map_changed_to'] = 'Карта на экране сводных данных переключена в состояние ';
$lang['options_logbook_map_changed_to'] = 'Карта на экране обычного вида журнала переключена в состояние ';

$lang['options_radios'] = 'Радиоинтерфейсы';
$lang['options_radio_settings'] = 'Радиоинтерфейсы';
$lang['options_radio_timeout_warning'] = 'Предупреждение о таймауте радиоинтерфейса';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'Используется для отображения на панели ввода QSO предупреждения об отключении радиоинтерфейса.';
$lang['options_this_number_is_in_seconds'] = 'Значение в секундах.';
$lang['options_radio_timeout_warning_changed_to'] = 'Значение таймаута радиоинтерфейса изменено на ';

$lang['options_email'] = 'Емэйл';
$lang['options_outgoing_protocol'] = 'Протокол отправки емэйл';
$lang['options_smtp_encryption'] = 'Шифрование SMTP';
$lang['options_email_address'] = 'Адрес электронной почты';
$lang['options_email_sender_name'] = 'Имя отправителя';
$lang['options_smtp_host'] = 'SMTP хост';
$lang['options_smtp_port'] = 'SMTP порт';
$lang['options_smtp_username'] = 'SMTP логин';
$lang['options_smtp_password'] = 'SMTP пароль';
$lang['options_mail_settings_saved'] = "Настройки сохранены успешно.";
$lang['options_mail_settings_failed'] = "Что-то пошло не так с сохранёнными настройками. Попробуйте ещё раз.";
$lang['options_outgoing_protocol_hint'] = "Протокол, который будет использоваться для отправки емэйл.";
$lang['options_smtp_encryption_hint'] = "Выберите, что будет использоваться при отправке емэйл: TLS или SSL.";
$lang['options_email_address_hint'] = "Адрес, с которого будет отправляться емэйл, к примеру, 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "Имя отправителя, к примеру, 'Cloudlog'";
$lang['options_smtp_host_hint'] = "Адрес почтового сервера, к примеру, 'mail.example.com' (без 'ssl://' или 'tls://')";
$lang['options_smtp_port_hint'] = "SMTP порт почтового сервера, к примеру, при использовании TLS -> '587', при использовании SSL -> '465'";
$lang['options_smtp_username_hint'] = "Имя пользователя для входа на почтовый сервер, обычно это -- указанный выше адрес емэйл.";
$lang['options_smtp_password_hint'] = "Пароль для входа на почтовый сервер.";
$lang['options_send_testmail'] = "Отправить тестовое сообщение.";
$lang['options_send_testmail_hint'] = "Емэйл будет отпрален на адрес, указанный в настройках вашего аккаунта.";
$lang['options_send_testmail_failed'] = "Отправка тестового сообщения не удалась. Что-то пошло не так.";
$lang['options_send_testmail_success'] = "Тестовое сообщение отправлено. Настройки емэйл похожи на правильные.";

$lang['options_oqrs'] = 'OQRS';
$lang['options_global_text'] = 'Сообщение на странице OQRS';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Необязательный текст, который может быть отображён в верхней части страницы OQRS.';
$lang['options_grouped_search'] = 'Объединённый поиск';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Если включено, то поиск будет осуществляться во всех местоположениях станций, где активен OQRS.';
$lang['options_grouped_search_show_station_name'] = "Show station location name in grouped search results";
$lang['options_grouped_search_show_station_name_hint'] = "If grouped search is ON, you can decide if the name of the station location shall be shown in the results table.";
$lang['options_oqrs_options_have_been_saved'] = 'Настройки OQRS сохранены.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Провайдер кэша DXCluster';
$lang['options_dxcluster_longtext'] = 'Провайдер кэша DXCluster. Вы можете настроить собственный кэш: <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> или использовать публичный';
$lang['options_dxcluster_hint'] = 'URL кэша DXCluster-Cache. К примеру, https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'URL кэша DXCluster изменён на  ';
$lang['options_dxcluster_maxage'] = 'Максимальный возраст спотов';
$lang['options_dxcluster_maxage_hint'] = 'Возраст спотов, которые будут отображаться в плане диапазонов/поиске';
$lang['options_dxcluster_decont'] = 'Отображать споты, которые присланы с континента  ';
$lang['options_dxcluster_maxage_changed_to']='Максимальный возраст спотов изменён на ';
$lang['options_dxcluster_decont_changed_to']='континент изменён на ';
$lang['options_dxcluster_decont_hint']='Бубт показаны споты только от споттеров с указанного континента';

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

$lang['options_save'] = 'Сохранить';

// Bands

$lang['options_bands'] = "Диапазоны";
$lang['options_bands_text_ln1'] = "Используя список диапазонов, вы можете контролировать, какие диапазоны будут отображены при заполнении данных о новом QSO.";
$lang['options_bands_text_ln2'] = "Активные диапазоны будут показаны в выпадающем списке 'Диапазон' формы ввода данных QSO, неактивные диапазоны будут скрыты и не смогут быть выбраны.";
$lang['options_bands_create'] = "Создать диапазон";
$lang['options_bands_edit'] = "Редактировать диапазон";
$lang['options_bands_activate_all'] = "Активировать все";
$lang['options_bands_activateall_warning'] = "Предупреждение! Вы уверены в том, что хотите активировать все диапазоны?";
$lang['options_bands_deactivate_all'] = "Деактивировать все";
$lang['options_bands_deactivateall_warning'] = "Предупреждение! Вы уверены в том, что хотите деактивировать все диапазоны?";
$lang['options_bands_ssb_qrg'] = "SSB QRG";
$lang['options_bands_ssb_qrg_hint'] = "Частота SSB QRG в диапазоне (в Гц)";
$lang['options_bands_data_qrg'] = "DATA QRG";
$lang['options_bands_data_qrg_hint'] = "Частота DATA QRG в диапазоне (в Гц)";
$lang['options_bands_cw_qrg'] = "CW QRG";
$lang['options_bands_cw_qrg_hint'] = "Частота CW QRG в диапазоне (в Гц)";

$lang['options_bands_name_band'] = "Название диапазона (к прмиеру, 20м)";
$lang['options_bands_name_bandgroup'] = "Название группы диапазонов (к примеру, hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "Предупреждение! Вы уверены в том, что хотите удалить следующий диапазон: ";

